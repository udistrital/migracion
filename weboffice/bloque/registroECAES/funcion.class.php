<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion_usu_wo.class.php");

class funciones_registroInscripcionEcaes extends funcionGeneral
{
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		
		$this->cripto=new encriptar();
		$this->tema=$tema;
		$this->sql=$sql;
		$this->configuracion=$configuracion;
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		$this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
                
		//Conexion ORACLE
                if($this->nivel==4){
                    $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
                }elseif($this->nivel==110){
                    $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
                }elseif($this->nivel==114){
                    $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
                }else{
                    echo "NO TIENE PERMISOS PARA ESTE MODULO";
                    exit;
                }	
		$this->formulario="registroECAES";
		$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'lugar')";
                $this->validacion=new validarUsu();
		
	}
	
	//Rescata los valores del formulario para guardarlos en la base de datos.
	function guardarInscripcion($configuracion, $accesoOracle,$acceso_db)
	{		
		$unUsuario=$this->verificarUsuario();
		
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		
		if(is_array($unUsuario))
		{
			$calendario=$this->validaCalendario();
						
			$verifica=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "verificaAnoper",'');
			@$rowVerifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $verifica, "busqueda");
			
			$valor[0]=$_REQUEST['codigo'];
			$valor[1]= $rowVerifica[0][0];
			$valor[2]= $rowVerifica[0][1];
			$valor[3]=$_REQUEST['presento'];
						
			$verifica=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "verificaRegistro",$valor);
			@$rowVerificareg=$this->ejecutarSQL($configuracion, $this->accesoOracle, $verifica, "busqueda");
			
			if(!is_array($rowVerificareg))
			{
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertarRegistro",$valor);
				@$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
				//echo "<br>->".$cadena_sql;
									
				if(isset($resultado))
				{
					$this->redireccionarInscripcion($configuracion,"registroexitoso");	
				}
				else
				{
					exit;
				}
			
			}
			else
			{
				$this->redireccionarInscripcion($configuracion,"yainscrito");
			}
		}
		else
		{
			echo "<table align=center><tr><td><h3>IMPOSIBLE GUARDAR EL FORMULARIO</h3></td></tr></table>";
		}
		
	}
		
	//Rescata la informacion del estudiante, y al final muestra en pantalla el formulario para inscribir el trabao de grado.
	function nuevoRegistro($configuracion,$conexion)
	{
                $tab=0;
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
						
		$valor[4]=$usuario;
		
		if($this->nivel==4){
                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle, "datosUsuario",$usuario);
                    $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");  
		
                }elseif($this->nivel==114||$this->nivel==110){
                    $proyectos=$this->validacion->consultarProyectosAsistente($this->usuario,$this->nivel,$this->accesoOracle,$this->configuracion,$this->acceso_db);
                    if(is_array($proyectos)){
                        $cadena_proyectos='';
                        foreach ($proyectos as $key => $proyecto) {
                            $resultado[$key][0]=$proyecto[0];
                            $resultado[$key][1]=$proyecto[4];
                            $resultado[$key][2]=$this->usuario;
                            if(!$cadena_proyectos){
                                $cadena_proyectos=$proyecto[0];
                            }else{
                                $cadena_proyectos.=", ".$proyecto[0];
                            }
                        }
                    }
                }	
                $totreg=count($resultado);

		$confec = "SELECT TO_CHAR(current_date, 'yyyymmdd')";
		@$rows=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confec, "busqueda");
		$fechahoy =$rows[0][0];
               
		if($this->nivel==4){
                    $qryFechas=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle, "validaFechas",$valor);
                    @$calendario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $qryFechas, "busqueda");
                }elseif($this->nivel==114||$this->nivel==110){
                    $qryFechas=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle, "validaFechasAsistente",$cadena_proyectos);
                    @$calendario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $qryFechas, "busqueda");
                }
		
		$FormFecIni = $calendario[0][2];
		$FormFecFin = $calendario[0][3];
		if( $calendario[0][0] == "" ||  $calendario[0][1] == "")
		{
			die('<br><br><p align="center"><b><font color="#0000FF" size="3">No se han programado fechas para inscripci&oacute;n a SaberPro.</font></p>');
			exit;
		}
		elseif($fechahoy <  $calendario[0][0] &&  $calendario[0][0] > '0')
		{
			
			echo '<table width="60%" height="40%" border="0" cellpadding="5" cellspacing="0" align="center">
				<tr>
					<td><fieldset style="padding:20;">
						<table width="80%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
							<tr>
								<td valign="top">
								<div><h3><center>Aviso</center></h3></div>
								<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
									<p align="justify">&nbsp;</p>
									<p align="center"><font color="red"><b>El proceso de inscripci&oacute;n a SaberPro ser&aacute; del: <br>'.$FormFecIni.' al '.$FormFecFin.'</b></font></p>
									<p align="justify">&nbsp;</p>
								</fieldset>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
			
		}
		elseif($fechahoy >  $calendario[0][1] &&  $calendario[0][1] > '0')
		{
			echo '<table width="60%" height="40%" border="0" cellpadding="5" cellspacing="0" align="center">
				<tr>
					<td><fieldset style="padding:20;">
						<table width="80%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
							<tr>
								<td valign="top">
								<div><h3><center>Aviso</center></h3></div>
								<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
									<p align="justify">&nbsp;</p>
									<p align="center"><font color="red"><b>El proceso de inscripci&oacute;n a SaberPro  termin&oacute; el: '.$FormFecFin.'</b></font></p>
									<p align="justify">&nbsp;</p>
								</fieldset>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
			
		}
		else
		{
			?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
				<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
					<tr>
						<td>	
							<table class="formulario" align="center">
								<tr>
									<td class="cuadro_brown" >
									<br>
										<ul>
										<li>Señor Coordinador, de acuerdo al decreto No. 03963 de 2009, artículo 4. Es responsabilidad de las instituciones de educaci&oacute;n superior
										realizar a trav&eacute;s del SNIES o de cualquier otro mecanismo que para tal efecto establezca el ICFES, el reporte de la totalidad de los estudiantes
										que hayan aprobado por lo menos el 70% de los cr&eacute;ditos acad&eacute;micos del programa correspondiente o que tengan previsto graduarse en el año 
										siguiente, de conformidad con los t&eacute;rminos y procedimientos que el ICFES establezca para dicho efecto.</li>
										<li>Tenga en cuenta que el listado de 70% es solamente informativo.</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td>
										<b>* El proceso de inscripci&oacute;n a SABER PRO finalizar&aacute; el <? echo $FormFecFin; ?>.</b>
									</td>
								</tr>
							</table>
							<br>
							<table class="formulario" align="center">
								<tr  class="bloquecentralencabezado">
									<td colspan="3" align="center">
										<p><span class="texto_negrita">Inscribir estudiante para SaberPro</span></p>
									</td>
								</tr>
								<tr>
									<td>
										<font color="red">*</font>C&oacute;digo de estudiante:
									</td>
									<td colspan="2">
										<input maxlength="450" size="25" tabindex="<? echo $tab++ ?>" name="codigo"><br>
									</td>
								</tr>
								
								
								<tr align='center'>
									<td colspan="3">
										<table class="tablaBase">
											<tr>
												
												<td align="center">
													<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
													<input type='hidden' name='opcion' value='nuevo'>
													<input value="Consultar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
												</td>
												<td align="center">
													<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr class="bloquecentralcuerpo">
									<td colspan="3" rowspan="1">
										Los campos marcados con <font color="red">*</font> deben ser diligenciados obligatoriamente.<br><br>
									</td>
								</tr>
								<tr class="bloquecentralcuerpo">
									<td colspan="3" rowspan="1">
										<p><a href="https://condor.udistrital.edu.co/appserv/manual/inscripcion_ecaes.pdf">
										<img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfGrande.png"?>" />
										Ver Manual de Usuario, para inscripci&oacute;n a SABER-PRO.</a></p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>		
							
			<?
		}
	}
	
	//Muestra el formulario con toda la información del estudiante y de la inscripción a grado..	
	function ejecutarConsulta($configuracion, $accesoOracle,$acceso_db)
	{
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		
                if($this->nivel==4){
                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle, "datosUsuario",$usuario);
                    $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");  
		
                }elseif($this->nivel==114||$this->nivel==110){
                    $proyectos=$this->validacion->consultarProyectosAsistente($this->usuario,$this->nivel,$this->accesoOracle,$this->configuracion,$this->acceso_db);
                    if(is_array($proyectos)){
                        $cadena_proyectos='';
                        foreach ($proyectos as $key => $proyecto) {
                            $resultado[$key][0]=$proyecto[0];
                            $resultado[$key][1]=$proyecto[4];
                            $resultado[$key][2]=$this->usuario;
                            if(!$cadena_proyectos){
                                $cadena_proyectos=$proyecto[0];
                            }else{
                                $cadena_proyectos.=", ".$proyecto[0];
                            }
                        }
                    }
                }	
		$valor[0]=$_REQUEST['codigo'];
		$valor[1]=$resultado[0][0];
		$valor[2]=$usuario;
				
		if($this->nivel==4){
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"mostrarEstudiante",$valor);
                    $resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                }elseif($this->nivel==114||$this->nivel==110){
                    $datos[0]=$valor[0];
                    $datos[1]=$cadena_proyectos;
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"mostrarEstudianteAsistente",$datos);
                    $resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                }
                
			if(is_array($resultado1))
			{
				$valor[2]=$resultado1[0][0];
				$valor[3]=$resultado1[0][1];
				$valor[4]=$resultado1[0][2];
				$valor[5]=$resultado1[0][3];
				$valor[6]=$resultado1[0][4];
				$valor[7]=$resultado1[0][5];
				
				$this->redireccionarInscripcion($configuracion,"mostrardatos",$valor);	
			}
			else
			{
				$this->redireccionarInscripcion($configuracion,"codigonoencontrado");
			}		
	}
	
	function datosEstudiante($configuracion,$registro, $total, $opcion="",$variable)
	{
            $tab=0;
                if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
						
		$calendario=$this->validaCalendario();
						
		?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
			<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
								<td class="cuadro_brown" >
								<br>
									<ul>
									<li>Señor Coordinador, de acuerdo al decreto No. 03963 de 2009, artículo 4. Es responsabilidad de las instituciones de educaci&oacute;n superior
									realizar a trav&eacute;s del SNIES o de cualquier otro mecanismo que para tal efecto establezca el ICFES, el reporte de la totalidad de los estudiantes
									que hayan aprobado por lo menos el 75% de los cr&eacute;ditos acad&eacute;micos del programa correspondiente o que tengan previsto graduarse en el año 
									siguiente, de conformidad con los t&eacute;rminos y procedimientos que el ICFES establezca para dicho efecto.</li>
									</ul>
								</td>
							</tr>
						</table>
						<br>
						<table class="formulario" align="center">
							<tr  class="bloquecentralencabezado">
								<td colspan="3" align="center">
									<p><span class="texto_negrita">Datos para Inscripci&oacute;n a SaberPro</span></p>
								</td>
							</tr>
							<tr>
								<td>
									C&oacute;digo del estudiante:
								</td>
								<td colspan="2">
									<? echo "<b>".$_REQUEST['codigo']."</b>"?>
								</td>
							</tr>
							<tr>
								<td>
									Nombre del estudiante:
								</td>
								<td colspan="2">
									<? echo "<b>".$_REQUEST['nombre']."</b>"?>
								</td>
							</tr>
							<tr>
								<td>
									Semestre cursado:
								</td>
								<td colspan="2">
									<? echo "<b>".$_REQUEST['semestre']."</b>"?>
								</td>
							</tr>
							<tr>
								<td>
									Porcentaje cursado:
								</td>
								<td colspan="2">
									<? echo "<b>".$_REQUEST['porcentaje']."%</b>"?>
								</td>
							</tr>
							<tr>
								<td>
									Recibo generado per. anterior:
								</td>
								<td colspan="2">
									<? echo "<b>".$_REQUEST['presento']."</b>"?>
								</td>
							</tr>
							
							<tr align='center'>
								<td colspan="3">
									<table class="tablaBase">
										<tr>
											
											<td align="center">
												<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
												<input type='hidden' name='codigo' value='<? echo $_REQUEST['codigo'] ?>'>
												<input type='hidden' name='presento' value='<? echo $_REQUEST['presento'] ?>'>
												<input type='hidden' name='inscribir' value='inscribir'>
												<input value="Inscribir" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
											</td>
											<td align="center">
												<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								
								<tr>
									<td class="cuadro_brown" colspan="3">
										<br>
										<ul>
										<li>El porcentaje cursado depende del plan de estudios al cual pertenece el estudiante</li>
										<li>El semestre se toma de acuerdo a las asignaturas inscritas en el periodo activo</li>
										</ul>
									</td>
								</tr>
								
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>		
						
		<?
	}
	function confirmacionRegistro($configuracion, $accesoOracle,$acceso_db)
	{
		?><table class="fondoImportante" align="center">
			<tr>
				<td class="cuadro_brown" >
				<br>
					<?
					$regresar="<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-2)' style='cursor:pointer;'>";
					$regresar.='<img src="';
					$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
					$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
					$regresar.= '<br>Regresar</a></center>';
					$html='Registro Exitoso';
					$html.=$regresar;
					echo $html;
					?>
				</td>
			</tr>
		</table><?
	}
	function mensajeyainscrito($configuracion, $accesoOracle,$acceso_db)
	{
		?><table class="fondoImportante" align="center">
			<tr>
				<td class="cuadro_brown" >
				<br>
					<?
					$regresar="<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-2)' style='cursor:pointer;'>";
					$regresar.='<img src="';
					$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
					$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
					$regresar.= '<br>Regresar</a></center>';
					$html='El estudiante ya presenta una inscripci&oacute;n a SaberPro.';
					$html.=$regresar;
					echo $html;
					?>
				</td>
			</tr>
		</table><?
	}
	function mensajecodnoencontrado($configuracion, $accesoOracle,$acceso_db)
	{
		
		?><table class="fondoImportante" align="center">
			<tr>
				<td class="cuadro_brown" >
				<br>
					<?
					$regresar="<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-1)' style='cursor:pointer;'>";
					$regresar.='<img src="';
					$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
					$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
					$regresar.= '<br>Regresar</a></center>';
					$html='El c&oacute;digo no es correcto, no pertenece a su Coordinaci&oacute;n, el estudiante no ha aprobado por lo menos el 70% de los créditos académicos, o el estudiante se encuentra inactivo.';
					$html.=$regresar;
					echo $html;
					?>
				</td>
			</tr>
		</table><?	
	}
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
	
	function verificarUsuario()
	{
		//Verificar existencia del usuario 	
                if($this->nivel==4){
                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle, "datosUsuario",$this->identificacion);
                    @$unUsuario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                }elseif($this->nivel==114||$this->nivel==110){
                    $proyectos=$this->validacion->consultarProyectosAsistente($this->usuario,$this->nivel,$this->accesoOracle,$this->configuracion,$this->acceso_db);
                    if(is_array($proyectos)){
                        foreach ($proyectos as $key => $proyecto) {
                            @$unUsuario[$key][0]=$proyecto[0];
                            @$unUsuario[$key][1]=$proyecto[4];
                            @$unUsuario[$key][2]=$this->usuario;
                        }
                    }
                }
                
		if(is_array($unUsuario))
		{
			return $unUsuario;			
		}
		else
		{
			if($this->nivel==4){
                            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->acceso_db, "datosUsuario",$this->usuario);
                            @$unUsuario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");         
                        }elseif($this->nivel==114||$this->nivel==110){
                            $proyectos=$this->validacion->consultarProyectosAsistente($this->usuario,$this->nivel,$this->accesoOracle,$this->configuracion,$this->acceso_db);
                            if(is_array($proyectos)){
                                foreach ($proyectos as $key => $proyecto) {
                                    @$unUsuario[$key][0]=$proyecto[0];
                                    @$unUsuario[$key][1]=$proyecto[4];
                                    @$unUsuario[$key][2]=$this->usuario;
                                }
                            }
                        }
                
                        if(is_array($unUsuario))
			{
				return $unUsuario;
			}
			else
			{
				return false;
			}
		
		}
		
	}	
	
	//Valida que la inscripcion para grados se pueda realizar dentro de las fechas establecidas.
	function validaCalendario()
	{
		//Valida las fechas del calendario
		
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		$valor[4]=$usuario;
		
		
                if($this->nivel==4){
                    $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle, "datosUsuario",$usuario);
                    $resultado=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");  
		
                }elseif($this->nivel==114||$this->nivel==110){
                    $proyectos=$this->validacion->consultarProyectosAsistente($this->usuario,$this->nivel,$this->accesoOracle,$this->configuracion,$this->acceso_db);
                    if(is_array($proyectos)){
                        $cadena_proyectos='';
                        foreach ($proyectos as $key => $proyecto) {
                            $resultado[$key][0]=$proyecto[0];
                            $resultado[$key][1]=$proyecto[4];
                            $resultado[$key][2]=$this->usuario;
                            if(!$cadena_proyectos){
                                $cadena_proyectos=$proyecto[0];
                            }else{
                                $cadena_proyectos.=", ".$proyecto[0];
                            }
                        }
                    }
                }
                $totreg=count($resultado);
                
		$confec = "SELECT TO_CHAR(current_date, 'yyyymmdd')";
		@$rows=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $confec, "busqueda");
		$fechahoy =$rows[0][0];
		
                if($this->nivel==4){
                    $qryFechas=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle, "validaFechas",$valor);
                    @$calendario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $qryFechas, "busqueda");
                }elseif($this->nivel==114||$this->nivel==110){
                    $qryFechas=$this->sql->cadena_sql($this->configuracion,$this->accesoOracle, "validaFechasAsistente",$cadena_proyectos);
                    @$calendario=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $qryFechas, "busqueda");
                }
		$FormFecIni = $calendario[0][2];
		$FormFecFin = $calendario[0][3];
			if( $calendario[0][0] == "" ||  $calendario[0][1] == "")
			{
				die('<br><br><p align="center"><b><font color="#0000FF" size="3">No se han programado fechas para inscripci&oacute;n a SaberPro.</font></p>');
				exit;
			}
			elseif($fechahoy <  $calendario[0][0] &&  $calendario[0][0] > '0')
			{
				
				echo '<table width="60%" height="40%" border="0" cellpadding="5" cellspacing="0" align="center">
					<tr>
						<td><fieldset style="padding:20;">
							<table width="80%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
								<tr>
									<td valign="top">
									<div><h3><center>Aviso</center></h3></div>
									<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
										<p align="justify">&nbsp;</p>
										<p align="center"><font color="red"><b>El proceso de inscripci&oacute;n a SaberPro ser&aacute; del: <br>'.$FormFecIni.' al '.$FormFecFin.'</b></font></p>
										<p align="justify">&nbsp;</p>
									</fieldset>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
				exit;
			}
			elseif($fechahoy >  $calendario[0][1] &&  $calendario[0][1] > '0')
			{
				echo '<table width="60%" height="40%" border="0" cellpadding="5" cellspacing="0" align="center">
					<tr>
						<td><fieldset style="padding:20;">
							<table width="80%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
								<tr>
									<td valign="top">
									<div><h3><center>Aviso</center></h3></div>
									<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
										<p align="justify">&nbsp;</p>
										<p align="center"><font color="red"><b>El proceso de inscripci&oacute;n a SaberPro  termin&oacute; el: '.$FormFecFin.'</b></font></p>
										<p align="justify">&nbsp;</p>
									</fieldset>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
				exit;
			}
			else
			{
				return $calendario;
			}
	}
			
	//Redirecciona la página dependiendo de la acción que se esté realizando en el módulo.
	function redireccionarInscripcion($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		unset($_REQUEST['action']);
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		switch($opcion)
		{
			case "administracion":
				$variable="pagina=admin_usuario";
				$variable.="&accion=1";
				$variable.="&hoja=0";
				break;
				
			case "confirmacion":
				$variable="pagina=admin_inscripcion_ECAES";
				$variable.="&opcion=confirmar";
				$variable.="&identificador=".$valor;
				break;
				
			case "formgrado":
				$variable="pagina=admin_inscripcion_ECAES";
				//$variable.="&opcion=cancelar";
				break;
						
			case "principal":
				$variable="pagina=index";
				break;
				
			case "mostrardatos":
				$variable="pagina=admin_inscripcion_ECAES";
				$variable.="&opcion=mostrar";
				$variable.="&carrera=".$valor[2];
				$variable.="&codigo=".$valor[3];
				$variable.="&nombre=".$valor[4];
				$variable.="&semestre=".$valor[5];
				$variable.="&porcentaje=".$valor[6];
				$variable.="&presento=".$valor[7];
				break;
							
			case "registroexitoso":
				$variable="pagina=admin_inscripcion_ECAES";	
				$variable.="&opcion=exito";
				break;
				
			case "yainscrito":
				$variable="pagina=admin_inscripcion_ECAES";	
				$variable.="&opcion=yains";
				break;
			
			case "codigonoencontrado":
				$variable="pagina=admin_inscripcion_ECAES";	
				$variable.="&opcion=noencontrado";
				break;
			
			case "inicioEcaes":
				$variable="pagina=admin_inscripcion_ECAES";	
				$variable.="&opcion=inecaes";
				break;
				
			case "finEcaes":
				$variable="pagina=admin_inscripcion_ECAES";	
				$variable.="&opcion=finecaes";
				break;
				
			
		
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

