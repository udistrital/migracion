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

class funciones_registroCargaAcademica extends funcionGeneral
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
		
		//Conexion ORACLE
		$this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="registro_cargaAcademica";
		$this->verificar="control_vacio(".$this->formulario.",'numhoras')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'docente')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'nivel')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'proyecto')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'curso')";
	}

	//Funcion para escoger las opciones de gestion de Carga Académica
	function opciones($configuracion)
	{
		?>
		<table class="contenidotabla centrar">
			<tr>
				<td colspan="2" class="cuadro_plano centrar"><h4 >
					GESTI&Oacute;N DE CARGA ACAD&Eacute;MICA</h4>
				</td>
			</tr>
			<?
			if($this->usuario)
			{
				$usuario=$this->usuario;
			}
			else
			{
				$usuario=$this->identificacion;
			}
							
			if($usuario=="")
			{
				echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
				EXIT;
			}

			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$ruta="pagina=registroCargaAcademica";
			$ruta.="&opcion=nuevo";
			$ruta.="&item=crear";
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$rutaadd=$this->cripto->codificar_url($ruta,$configuracion);

			$ruta="pagina=registroCargaAcademica";
			$ruta.="&opcion=duplicaCarga";
			$ruta.="&item=copiar";
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$rutaconsulta=$this->cripto->codificar_url($ruta,$configuracion);

			?>
			<tr>
				<td class="cuadro_plano centrar" colspan="2">
					<a href="<?echo $indice.$rutaadd?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/addHorario.PNG" alt="add" border="0"><br>Registrar Carga</a>
				</td>
				<!--td class="cuadro_plano centrar">		
					<a href="<?/*echo $indice.$rutaconsulta*/?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/copiarHorario.PNG" alt="add" border="0"><br>Copiar Carga</a>
				</td-->
			</tr>
		</table>

		<?
	}
	
	//Selecciona el el periodo actual o prximo al que le va a asignar la carga
	function seleccionarPeriodo($configuracion)
	{
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		
		if($usuario=="")
		{
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
    
		$this->opciones($configuracion);
		
		$item=isset($_REQUEST['item'])?$_REQUEST['item']:'';
		if($item == "crear")
		{
			$str="REGISTRAR CARGA";
		}
		elseif($item == "copiar")
		{
			$str="COPIAR CARGA";
		}
		else
		{
			$str="REGISTRAR CARGA";
		}
  
		?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
			<table align="center" border="0" cellpadding="0" width="500" height="100" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
				<tr>
					<td style='text-align:center' align="center" colspan="2">
						<h4 class="bloquelateralcuerpo"><?echo $str?></h4>
						<hr>
					</td>
				</tr>
			</table>
			<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
									<td class="cuadro_brown">
										<br>
										<ul>
											<li> Seleccione el Periodo para registar la carga acad&eacute;mica.</li>
										</ul>
									</td>
									
								</tr>
								<tr>
									<td>
										<p><a href="https://condor.udistrital.edu.co/appserv/manual/carga_academica.pdf">
										<img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfito.png"?>" />
										Ver Manual de Usuario.</a></p>
									</td>
								</tr>
							
							<tr class="texto_subtitulo">
								<td class="" colspan="5" align="center">
									<p><span class="texto_negrita">SELECCIONE EL PERIODO ACAD&Eacute;MICO</span></p>
								</td>
							</tr>
						</table>
						
						<table class="contenidotabla centrar">
							<tr>
								<td align="center">
									<fieldset>
										<legend>
											Seleccionar periodo acad&eacute;mico
										</legend>
										<table class="formulario">
											<tr>								
												<td>
													<font color="red">*</font>Periodo acad&eacute;mico:
												</td>
												<td>
													<?
													include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
													$html=new html();
  
													$busqueda="SELECT ";
													$busqueda.="ape_estado, ";
													$busqueda.="ape_ano||'-'||ape_per ";
													$busqueda.="FROM ";
													$busqueda.="acasperi ";
													$busqueda.="WHERE ";
													$busqueda.="ape_estado IN ('A','X') ";
													$busqueda.="order by ape_ano ASC ";
													//echo $busqueda.'<br>';					
													$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
																										
													for ($i=0; $i<count($resultado);$i++)
													{
														$registro[$i][0]=$resultado[$i][0];
														$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
													}								
													$mi_cuadro=$html->cuadro_lista($registro,'nivel',$configuracion,1,3,FALSE,"nivel",100);
													
													echo $mi_cuadro;
												?>
												</td>
											</tr>
										</table>
									</fieldset>	
								</td>
							</tr>
							<tr align='center'>
								<td colspan="16">
									<table class="tablaBase">
										<tr>
											
											<td align="center">
												<? $tab1=isset($tab)?$tab:''; ?>
												<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
												<input type='hidden' name='opcion' value='seleccionarPeriodo'>
												<input value="Seleccionar periodo" name="aceptar" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>	
		<?	
	}

	//Funcion para escoger las opciones de gestion de Carga Académica por cursos o docentes
	function opcionCursosDocentes($configuracion)
	{
		?>
		<table class="formulario">
			<tr>
				<td colspan="2" class="cuadro_plano centrar">
				</td>
			</tr>  
			<?
			if($this->usuario)
			{
				$usuario=$this->usuario;
			}
			else
			{
				$usuario=$this->identificacion;
			}
							
			if($usuario=="")
			{
				echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
				EXIT;
			}

			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$ruta="pagina=registroCargaAcademica";
			$ruta.="&opcion=ListasCursos";
			$ruta.="&tipo=cursos";
			$ruta.="&nivel=".$_REQUEST["nivel"];
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$rutaadd=$this->cripto->codificar_url($ruta,$configuracion);

			$ruta="pagina=registroCargaAcademica";
			$ruta.="&opcion=ListaDocentes";
			$ruta.="&tipo=docentes";
			$ruta.="&nivel=".$_REQUEST["nivel"];
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$rutaconsulta=$this->cripto->codificar_url($ruta,$configuracion);

			?>
			<tr>
				<td class="cuadro_plano centrar">
					<a href="<?echo $indice.$rutaadd?>"><input type="button" value="Registrar Carga por Cursos"></a>
				</td>
				<td class="cuadro_plano centrar">		
					<a href="<?echo $indice.$rutaconsulta?>"><input type="button" value="Registrar Carga por Docentes"></a>
				</td>
			</tr>
		</table>

		<?
	}

	//Rescata la lista de los Proyectos Curriculares con los cursos registrados en cada Proyecto.
	function verListaCursos($configuracion)
	{
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		
		if($usuario=="")
		{
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		
		$this->opciones($configuracion);

		$this->opcionCursosDocentes($configuracion);
		
		$valor[10]=$_REQUEST['nivel'];
		$valor[1]=$usuario;
		$valor[13]=isset($_REQUEST['tipo'])?$_REQUEST['tipo']:'';
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$valor[2]=$resultAnioPer[0][0];
		$valor[3]=$resultAnioPer[0][1];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carreras",$valor);
		$registrocarreras=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentaRegistros=count($registrocarreras);
		?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
			<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
									<td class="cuadro_brown">
										<br>
										<ul>
											<li> Seleccione el Proyecto Curricular (si tiene mas de un Proyecto a su cargo), luego seleccione el curso y haga Click en "Enviar".</li>
											<li> Si al seleccionar un Proyecto, en la lista de cursos aparece el mensaje "Imposible rescatar los datos", es porque el Proyecto Curricular seleccionado, no tiene cursos registrados.</li>
										</ul>
									</td>
									
								</tr>
								<tr>
									<td>
										<p><a href="https://condor.udistrital.edu.co/appserv/manual/carga_academica.pdf">
										<img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfito.png"?>" />
										Ver Manual de Usuario.</a></p>
									</td>
								</tr>
							
							<tr class="texto_subtitulo">
								<td class="" colspan="5" align="center">
									<p><span class="texto_negrita">REGISTRO CARGA ACAD&Eacute;MICA POR CURSOS, PERIODO <?echo $ano.'-'.$per;?> </span></p>
								</td>
							</tr>
						</table>
						
						<table class="contenidotabla centrar">
							<tr>
								<td align="center">
									<fieldset>
										<legend>
											Seleccionar curso
										</legend>
										<table class="formulario">
											<tr>
												<td>
													<font color="red">*</font>Proyecto:
												</td>
												<td><?
													include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
													$html=new html();
													$valor[10]=$_REQUEST['nivel'];
													$valor[2]=$ano;
													$valor[3]=$per;
														
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carreras",$valor);
													$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													
													$configuracion["ajax_function"]="xajax_nombreCurso";
													$configuracion["ajax_control"]="proyecto";
													for ($i=0; $i<count($resultado);$i++)
													{
														$registro[$i][0]=$resultado[$i][0];
                                                                                                                $registro[$i][1]=$resultado[$i][1];
														//$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
													}
													$tab1=isset($tab)?$tab:'';
													$mi_cuadro=$html->cuadro_lista($registro,'proyecto',$configuracion,-1,3,FALSE,$tab1++,"proyecto",100);
													
													echo $mi_cuadro;
												
												?></td>
											</tr>
											<tr>								
												<td>
													<font color="red">*</font>Curso:
												</td>
												<td>
													<div id="divCurso"><?
													$var=explode('#',$resultado[0][0]);
													
													$busqueda="SELECT ";
													$busqueda.="ASI_COD, ";
													$busqueda.="ASI_COD||' '|| ASI_NOMBRE||' '||CUR_NRO ";
													$busqueda.="FROM ";
													$busqueda.="ACASPERI, ACASI, ACCRA, ACCURSO ";
													$busqueda.="WHERE ";
													$busqueda.="CRA_COD =".$var[0];
													$busqueda.=" AND ";
													$busqueda.="APE_ESTADO ='".$valor[10]."' ";
													$busqueda.="AND ";
													$busqueda.="APE_ANO = CUR_APE_ANO ";
													$busqueda.="AND ";
													$busqueda.="APE_PER = CUR_APE_PER ";
													$busqueda.="AND ";
													$busqueda.="ASI_COD = CUR_ASI_COD ";
													$busqueda.="AND ";
													$busqueda.="CRA_COD = CUR_CRA_COD ";
													$busqueda.="AND ";
													$busqueda.="CUR_ESTADO = 'A' ";
													$busqueda.="order by ASI_COD, CUR_NRO asc ";
													//echo $busqueda.'<br>';					
													$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
																					
													$mi_cuadro=$html->cuadro_lista("",'curso',$configuracion,-1,3,FALSE,$tab1++,"curso",100);
													
													echo $mi_cuadro;
												?></div>
												</td>
											</tr>
										</table>
									</fieldset>	
								</td>
							</tr>
							<tr align='center'>
								<td colspan="16">
									<table class="tablaBase">
										<tr>
											
											<td align="center">
												<? $tab1=isset($tab)?$tab:'';?>
												<input type='hidden' name='anio' value='<? echo $valor[2] ?>'>
												<input type='hidden' name='per' value='<? echo $valor[3] ?>'>
												<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
												<input type='hidden' name='tipo' value='<? echo $valor[13] ?>'>
												<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
												<input type='hidden' name='opcion' value='seleccionarProyecto'>
												<input value="Enviar" name="aceptar" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
											</td>
											<!--td align="center">
												<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
												<input type="submit" name="notdef" value="Calcular Acumulado">
											</td-->
											<td align="center">
												<input type='hidden' name='nivel' value='<? echo $valor[10]?>'>
												<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab1++ ?>'  /><br>
											</td>
										</tr>
										<tr class="bloquecentralcuerpo">
											<td colspan="3" rowspan="1">
												Los campos marcados con <font color="red">*</font> deben ser diligenciados obligatoriamente.<br><br>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>	
		<?	
	}
	
	//Valida que los campos no se envien vacíos, y redirecciona al formulario de registro de carga.
	function validaFormularioCurso($configuracion)
	{
		if(($_REQUEST['proyecto']==-1) || ($_REQUEST['curso']=="")||($_REQUEST['curso']==-1))
		{
			$valor[4]=1; //Mensaje 1
			$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
		}
		else
		{
			$valor[8]=isset($_REQUEST['identificacion'])?$_REQUEST['identificacion']:'';
			$valor[9]=isset($_REQUEST['nombres'])?$_REQUEST['nombres']:'';
			$valor[15]=isset($_REQUEST['apellidos'])?$_REQUEST['apellidos']:'';
			$valor[12]=isset($_REQUEST['consultaDocente'])?$_REQUEST['consultaDocente']:'';
			$variableProyecto=explode('#',$_REQUEST['proyecto']);
			$valor[10]=$variableProyecto[1];
			$valor[5]=$variableProyecto[0];
			$variableCurso=explode('#',$_REQUEST['curso']);
			$valor[6]=$variableCurso[0];
			$valor[7]=$variableCurso[2];
			$this->redireccionarInscripcion($configuracion,"mostrarGrilla",$valor);
		}
	}
	
	//Selecciona Docentes y Proyecto Curricular
	function verListaDocentes($configuracion)
	{
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		
		if($usuario=="")
		{
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}

		$this->opciones($configuracion);

		$this->opcionCursosDocentes($configuracion);
		
		$valor[13]="docentes";    
 
		$valor[10]=$_REQUEST['nivel'];
		$valor[1]=$usuario;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$valor[2]=$resultAnioPer[0][0];
		$valor[3]=$resultAnioPer[0][1];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carreras",$valor);
		$registrocarreras=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentaRegistros=count($registrocarreras);
		  
		$valor[5]= isset($_REQUEST['proyecto'])?$_REQUEST['proyecto']:'';
		$valor[6]= isset($_REQUEST['curso'])?$_REQUEST['curso']:'';
		$valor[7]= isset($_REQUEST['grupo'])?$_REQUEST['grupo']:'';
		$identificacion=isset($_REQUEST['identificacion'])?$_REQUEST['identificacion']:'';
		if(is_numeric($identificacion))
		{
			$valor[8]= $identificacion;
		}
		else
		{
			$valor[8]= "";
		}

		$nombres=isset($_REQUEST['nombres'])?$_REQUEST['nombres']:'';
		if($nombres=="")
		{
			$valor[9]= $nombres;
		}
		else
		{
			$valor[9]= "%".$nombres."%";
		}
		
		$apellidos=isset($_REQUEST['apellidos'])?$_REQUEST['apellidos']:'';  
		if($apellidos=="")
		{
			$valor[11]= $apellidos;
		}
		else
		{
			$valor[11]= "%".$apellidos."%";
		}  
		?>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown">
								<ul>
									<li>Seleccione el Docente, consult&aacute;ndolo mediante el formulario de consulta de docentes, o selecci&oacute;nelo de la lista en el formulario de asignar curso.</li>
									<li>Seleccione el tipo de vinculaci&oacute;n del Docente.</li>
									<li>Seleccione el Proyecto Curricular.</li>
								</ul>
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td class="" align="center">
								<p><span class="texto_negrita">REGISTRO CARGA ACAD&Eacute;MICA PERIODO <?echo $ano.'-'.$per;?></span></p>
							</td>
						</tr>
					</table>
					<table class="contenidotabla centrar">
						<tr>
							<td>
							<fieldset>
								<legend>
									Consulta de docentes
								</legend>
									<? $this->consultaDocentes($configuracion);?>
								</td>
							</fieldset>
						</tr>
					</table>
					<table class="contenidotabla centrar">
						<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
						<tr>
							<td align="center">
								<fieldset>
									<legend>
										Asignar curso
									</legend>
									<table class="formulario">
										<tr>
											<td>
												<font color="red">*</font>Docentes:
											</td>
											<td>
												<?
												$consultaDocente=isset($_REQUEST['consultaDocente'])?$_REQUEST['consultaDocente']:'';  
												if(!$consultaDocente || $consultaDocente=="")
												{
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"docentes","");
													$registroDocente=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													$html='<select name="docente">';
													$html.='<option value=""> </value>';	  
													$i=0;
													while(isset($registroDocente[$i][0]))
													{
														$html.='<option value='.$registroDocente[$i][0].'>'.$registroDocente[$i][0].' '.$registroDocente[$i][1].'</option>';
													$i++;  
													}
													$html.='</select>';
													echo $html;
												}
												else
												{
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"consultaDocentes",$valor);
													$registroDocente=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													$html='<select name="docente">';
													//$html.='<option value=""> </value>';	  
													$i=0;
													while(isset($registroDocente[$i][0]))
													{
														$html.='<option value='.$registroDocente[$i][0].'>'.$registroDocente[$i][0].' '.$registroDocente[$i][1].'</option>';
													$i++;  
													}
													$html.='</select>';
													echo $html;
												}
												?>
												
												
											</td>
										</tr>
										<tr>
											<td>
												  <font color="red">*</font>Tipo de Vinculaci&oacute;n:
											</td>
											<td>
												<?
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"tipoVinculacion","");
												$registroTipVin=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												$html='<select name="tipVin">';
												$html.='<option value=""> </value>';	  
												$i=0;
												while(isset($registroTipVin[$i][0]))
												{
													$html.='<option value='.$registroTipVin[$i][0].'>'.$registroTipVin[$i][0].' '.$registroTipVin[$i][1].'</option>';
												$i++;  
												}
												$html.='</select>';
												echo $html;
												?>
											</td>
										</tr>
										<tr>
											<td>
												<font color="red">*</font>Proyecto Curricular:
											</td>
											<td>
												<?
													include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
													$html=new html();
													$valor[10]=$_REQUEST['nivel'];
													$valor[2]=$ano;
													$valor[3]=$per;
														
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carreras",$valor);
													$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													
													for ($i=0; $i<count($resultado);$i++)
													{
														$registro[$i][0]=$resultado[$i][0];
														$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
													}
													$tab1=isset($tab)?$tab:'';
													$mi_cuadro=$html->cuadro_lista($registro,'proyecto',$configuracion,-1,3,FALSE,$tab1++,"proyecto",100);
													
													echo $mi_cuadro;
												
												?>
										</tr>
										<tr align='center'>
											<td colspan="16">
												<table class="tablaBase">
													<tr>
														
														<td align="center">
															<?
															$tab1=isset($tab)?$tab:'';
															$proyecto=isset($registroproyecto[0][0])?$registroproyecto[0][0]:'';
															$curso=isset($datoscurso[0][0])?$datoscurso[0][0]:'';
															$grupo=isset($datoscurso[0][2])?$datoscurso[0][2]:'';
															?>
															<input type='hidden' name='anio' value='<? echo $valor[2] ?>'>
															<input type='hidden' name='per' value='<? echo $valor[3] ?>'>
															<input type='hidden' name='carrera' value='<? echo $proyecto ?>'>
															<input type='hidden' name='curso' value='<? echo $curso ?>'>
															<input type='hidden' name='grupo' value='<? echo  $grupo ?>'>
															<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
															<input type='hidden' name='formulario' value='docentes'>  
															<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
															<input type='hidden' name='opcion' value='enviarDocentes'>
															<input value="Enviar" name="aceptar" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
														</td>
														<!--td align="center">
															<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
															<input type="submit" name="notdef" value="Calcular Acumulado">
														</td-->
														<td align="center">
															<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab1++ ?>'  /><br>
														</td>
													</tr>
													<tr class="bloquecentralcuerpo">
														<td colspan="3" rowspan="1">
															Los campos marcados con <font color="red">*</font> deben ser diligenciados obligatoriamente.<br><br>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</fieldset>	
							</td>
						</tr>
						</form>
						
						
						<tr>
							
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?
	}

	//Registra la carga seleccionando el docente
	function registrarCargaDocentes($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
						
		if($usuario=="")
		{
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$variable=isset($variable)?$variable:'';
		$calendario=$this->validaCalendario($variable,$configuracion);
		//$observaciones=$this->notasobservaciones();guardarNotas
		$valor[10]=$_REQUEST['nivel'];
		$valor[1]=$usuario;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
				
		$valor[2]=$ano;
		$valor[3]=$per;
		
		$valor[13]=isset($_REQUEST['tipo'])?$_REQUEST['tipo']:'';  
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
		$rowfechoy=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$fechahoy = $rowfechoy[0][0];
		
		$variable=explode('#',$_REQUEST['proyecto']);
		$valor[5]= $variable[0];
		$valor[11]= $_REQUEST['tipVin'];
		$valor[14]= $_REQUEST['nombre'];
		$valor[8]= $_REQUEST['docente'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"proyecto",$valor);
		$registroproyecto=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosDocente",$valor);
		$registroDocentes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		?>
		
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown">
								<ul>
									<li>Para registrarle carga a un Docente, seleccione el Curso.</li>
									<li>Digite el número de horas.</li>
								</ul>
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td class="" align="center">
								<p><span class="texto_negrita">REGISTRO CARGA ACAD&Eacute;MICA PERIODO <?echo $ano.'-'.$per;?></span></p>
							</td>
						</tr>
					</table>
					<table class="contenidotabla centrar">
						<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
						<tr>
							<td align="center">
								<fieldset>
									<legend>
										Asignar curso
									</legend>
									<table class="formulario">
										<tr>
											<td>
												<font color="red">*</font>Cursos:
											</td>
											<td><?
												include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
												$html=new html();
												$busqueda="SELECT ";
												//$busqueda.="ASI_COD, ";
												$busqueda.="ASI_COD||'#'||CUR_NRO||'#'||'".$valor[2]."'||'#'||'".$valor[3]."', ";
												$busqueda.="ASI_COD||' '|| ASI_NOMBRE||' '||CUR_NRO ";
												$busqueda.="FROM ";
												$busqueda.="ACASPERI, ACASI, ACCRA, ACCURSO ";
												$busqueda.="WHERE ";
												$busqueda.="CRA_COD =".$valor[5];
												$busqueda.=" AND ";
												$busqueda.="APE_ESTADO ='".$valor[10]."' ";
												$busqueda.="AND ";
												$busqueda.="APE_ANO = CUR_APE_ANO ";
												$busqueda.="AND ";
												$busqueda.="APE_PER = CUR_APE_PER ";
												$busqueda.="AND ";
												$busqueda.="ASI_COD = CUR_ASI_COD ";
												$busqueda.="AND ";
												$busqueda.="CRA_COD = CUR_CRA_COD ";
												$busqueda.="AND ";
												$busqueda.="CUR_ESTADO = 'A' ";
												$busqueda.="order by ASI_COD, CUR_NRO asc ";
												$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
												//echo $busqueda."<br>";
												//$configuracion["ajax_function"]="xajax_horaCurso";
												//$configuracion["ajax_control"]="cursos";
												for ($i=0; $i<count($resultado);$i++)
												{
													$registro[$i][0]=$resultado[$i][0];
													$registro[$i][1]=$resultado[$i][1];
                                                                                                        //$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
												}
												$tab1=isset($tab)?$tab:'';
												$mi_cuadro=$html->cuadro_lista($registro,'cursos',$configuracion,-1,3,FALSE,$tab1++,"cursos",100);
												
												echo $mi_cuadro;
											
											?></td>
										</tr>
										<tr>								
											<td>
												<font color="red">*</font>Horas:
											</td>
											<td>
												<div id="divHora"><?
												//$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cuentaHorarioCursoRegistrado",$valor);
												//$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												
												//$registro=$resultado[0][0];
												$mi_cuadro1=$html->cuadro_texto('numhoras',$configuracion,'','',0,'',5);
													
												echo $mi_cuadro1;
												?></div>
											</td>
										</tr>    
										
										<tr align='center'>
											<td colspan="2">
												<table class="tablaBase">
													<tr>
														
														<td align="center">
															<? $tab1=isset($tab)?$tab:'';?>
															<input type='hidden' name='usuario' value='<? echo $valor[1]?>'>
															<input type='hidden' name='anio' value='<? echo $valor[2] ?>'>
															<input type='hidden' name='per' value='<? echo $valor[3] ?>'>
															<input type='hidden' name='carrera' value='<? echo $valor[5] ?>'>
															<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
															<input type='hidden' name='docente' value='<? echo $valor[8] ?>'>
															<input type='hidden' name='tipVin' value='<? echo $valor[11] ?>'>
															<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
															<input type='hidden' name='opcion' value='registrarCursos'>
															<input value="Grabar" name="aceptar" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
														</td>
														<!--td align="center">
															<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
															<input type="submit" name="notdef" value="Calcular Acumulado">
														</td-->
														<td align="center">
															<input type='hidden' name='usuario' value='<? echo $valor[1]?>'>
															<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab1++ ?>'  /><br>
														</td>
													</tr>
													<tr class="bloquecentralcuerpo">
														<td colspan="3" rowspan="1">
															Los campos marcados con <font color="red">*</font> deben ser diligenciados obligatoriamente.<br><br>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</fieldset>	
							</td>
						</tr>
						
						</form>
						<tr>
							<td>
								<fieldset>
									<legend>
										Cursos registrados
									</legend>
									<table class="contenidotabla">
										<tr>
											<td colspan="">
												Proyecto Curricular:
											</td>
											<td colspan="">
												<? echo $registroproyecto[0][0].' - '.$registroproyecto[0][1];?>
											</td>
											<td width='10%'>
												No. de documento:
											</td>
											<td width='15%' align="left">
												<? echo $registroDocentes[0][0];?>
											</td>
										</tr>
										<tr>
											<td width='10%'>
												Docente:
											</td>
											<td width='40%' align="left">
												<? echo $registroDocentes[0][1];?>
											</td>
										</tr>
										<tr>
											<td colspan="5" class="cuadro_color">
												<?
																								
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cargaDocente",$valor);
												$registroCargaDocente=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

												//echo "mmmm".$registroTotalHoras[0][0];
												if(!is_array($registro))
												{
													include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
													$cadena='A este curso no se le han asignado docentes.';
													alerta::sin_registro($configuracion,$cadena);
												}
												else
												{
													echo	'<table  class="contenidotabla" border="1">
															<tr>
																<td class="cuadro_brown">
																	Cod. curso
																</td>
																<td class="cuadro_brown">
																	Curso
																</td>
																<td class="cuadro_brown">
																	Grupo
																</td>
																<td class="cuadro_brown">
																	No. horas
																</td>
																<td class="cuadro_brown">
																	Borrar Carga
																</td>  
															</tr>';
															$i=0;
															while(isset($registroCargaDocente[$i][0]))
															{
													echo		'<tr>	
																<td class="cuadro_color">'
																	.$registroCargaDocente[$i][0].  
																'</td>
															 	<td class="cuadro_color">'
																	.$registroCargaDocente[$i][1].  
																'</td>
																<td class="cuadro_color">'
																	.$registroCargaDocente[$i][2].  
																'</td>
																<td class="cuadro_color">'
																	.$registroCargaDocente[$i][3].  
																'</td>';
																include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
																include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																unset($valor);
																$valor[2]=$ano;
																$valor[3]=$per;
																$valor[5]= $registroproyecto[0][0];
																$valor[6]= $registroCargaDocente[$i][0];
																$valor[7]= $registroCargaDocente[$i][2];
																$valor[8]= $registroDocentes[0][0];
																$valor[9]= $registroDocentes[0][1];
																$valor[10]=$_REQUEST['nivel'];

																setlocale(LC_MONETARY, 'en_US');
																$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
																$cripto=new encriptar();
																echo "<td><a href='";
																$variable="pagina=registroCargaAcademica";
																$variable.="&opcion=borrar";
																$variable.="&docente=".$valor[8];
																$variable.="&ano=".$valor[2];
																$variable.="&per=".$valor[3];
																$variable.="&carrera=".$valor[5];
																$variable.="&curso=".$valor[6];
																$variable.="&grupo=".$valor[7];
																$variable.="&nombre=".$valor[9];
																$variable.="&nivel=".$valor[10];  
																$variable.="&nombreCurso=".$registroCargaDocente[$i][1];
																$variable.="&tipoFormulario=cursos";
																//$variable.="&no_pagina=true";
																$variable=$cripto->codificar_url($variable,$configuracion);
																echo $indice.$variable."'";
																echo "title='Haga Click aqu&iacute; para eliminar esta carga.'>";
																?>
																<center><img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/boton_borrar.png" border="0"></center>
																</a></td>
															</tr><?
																$i++;
															}
															$totalhoras=isset($registroTotalHoras[0][0])?$registroTotalHoras[0][0]:'';
															$cuentahorario=isset($registroCuentaHorario[0][0])?$registroCuentaHorario[0][0]:'';
															if($totalhoras>$cuentahorario)
															{
																echo '<tr><td colspan="4">';
																include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
																$cadena='Se&ntilde;or Coordinador las horas asignadas a los Docentes suman '.$registroTotalHoras[0][0].' horas, cuando el n&uacute;mero de horas programadas para el curso es de '.$registroCuentaHorario[0][0].' horas.';
																alerta::sin_registro($configuracion,$cadena);
																echo '</td></tr>';
															}
															$mensaje=isset($_REQUEST['mensaje'])?$_REQUEST['mensaje']:'';
															if($mensaje)
															{
																echo '<tr><td colspan="4">';
																include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
																$cadena=$_REQUEST['mensaje'];
																alerta::sin_registro($configuracion,$cadena);
																echo '</td></tr>';
															}  
													echo	'</table>';
												}
												?>
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
						
						
						<tr>
							
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?
	}


	//Confirma el número de horas
	function confirmaNumHoras($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
						
		if($usuario=="")
		{
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$variable=isset($variable)?$variable:'';
		$calendario=$this->validaCalendario($variable,$configuracion);
		//$observaciones=$this->notasobservaciones();guardarNotas
		$valor[10]=$_REQUEST['nivel'];
		$valor[1]=$usuario;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
				
		$valor[2]=$ano;
		$valor[3]=$per;
		
		$variable=explode('#',$_REQUEST['cursos']);
		$valor[6]= $variable[0];
		$valor[7]= $variable[1];  
  
		$valor[13]=isset($_REQUEST['tipo'])?$_REQUEST['tipo']:'';
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
		$rowfechoy=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$fechahoy = $rowfechoy[0][0];
		
		$valor[5]= $_REQUEST['carrera'];
		$valor[11]= $_REQUEST['tipVin'];
		$valor[14]= isset($_REQUEST['nombre'])?$_REQUEST['nombre']:'';
		$valor[8]= $_REQUEST['docente'];
		$valor[9]= $_REQUEST['numhoras'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso",$valor);
		$datoscurso=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cuentaHorarioCursoRegistrado",$valor);
		$resultadoCuenta=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		?>
		
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr class="texto_subtitulo">
							<td class="cuadro_azul">
								<p><span>El n&uacute;mero de horas programadas para el curso <b><? echo $datoscurso[0][1]?></b>, grupo <b><? echo $datoscurso[0][2]?></b>  es de <b><? echo $resultadoCuenta[0][0]?></b> horas, usted digit&oacute; <b><? echo $valor[9]?></b> horas, si es necesario las puede modificar y hacer click en "Continuar".</p>
							</td>
						</tr>
					</table>
					<table class="contenidotabla centrar">
						<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
						<tr>
							<td align="center">
								<fieldset>
									<legend>
										Datos del curso
									</legend>
									<table class="formulario">
										<tr>								
											<td>
												Nombre del curso:
											</td>
											<td>
												<? echo $datoscurso[0][1]?>
											</td>
										</tr>
										<tr>								
											<td>
												N&uacute;mero de grupo:
											</td>
											<td>
												<? echo $datoscurso[0][2];?>
											</td>
										</tr>
										<tr>								
											<td>
												N&uacute;mero de horas asignadas al Curso:
											</td>
											<td>
												<?echo $resultadoCuenta[0][0];?>
											</td>
										</tr>
										</tr>
											<td>
												<font color="red">*</font>N&uacute;mero de horas digitadas:
											</td>
											<td>
												<?
												include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
												$html=new html();
												//$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cuentaHorarioCursoRegistrado",$valor);
												//$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												
												//$registro=$resultado[0][0];
												$mi_cuadro1=$html->cuadro_texto('numhoras',$configuracion,$valor[9],'',0,'',5);
													
												echo $mi_cuadro1;
												?>
											</td>
										</tr>    
										
										<tr align='center'>
											<td colspan="2">
												<table class="tablaBase">
													<tr>
														
														<td align="center">
															<? $tab1=isset($tab)?$tab:'';?>
															<input type='hidden' name='usuario' value='<? echo $valor[1]?>'>
															<input type='hidden' name='anio' value='<? echo $valor[2] ?>'>
															<input type='hidden' name='per' value='<? echo $valor[3] ?>'>
															<input type='hidden' name='carrera' value='<? echo $valor[5] ?>'>
															<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
															<input type='hidden' name='docente' value='<? echo $valor[8] ?>'>
															<input type='hidden' name='tipVin' value='<? echo $valor[11] ?>'>
															<input type='hidden' name='curso' value='<? echo $valor[6] ?>'>
															<input type='hidden' name='grupo' value='<? echo $valor[7] ?>'>
															<input type='hidden' name='tipoFormulario' value='cursos'>    
															<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
															<input type='hidden' name='opcion' value='registrar'>
															<input value="Continuar" name="aceptar" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
														</td>
														<!--td align="center">
															<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
															<input type="submit" name="notdef" value="Calcular Acumulado">
														</td-->
														<td align="center">
															<input type='hidden' name='usuario' value='<? echo $valor[1]?>'>
															<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab1++ ?>'  /><br>
														</td>
													</tr>
													<tr class="bloquecentralcuerpo">
														<td colspan="3" rowspan="1">
															Los campos marcados con <font color="red">*</font> deben ser diligenciados obligatoriamente.<br><br>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</fieldset>	
							</td>
						</tr>
						
						</form>
					</table>
				</td>
			</tr>
		</table>
		<?
	}  

	//Duplicar la carga académica
	function duplicarCarga($configuracion)
	{
		echo "¡EN CONSTRUCCI&Oacute;N!",
			EXIT;
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		
		if($usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}

		$this->opciones($configuracion);
		$item=$_REQUEST['item'];
		if($item == "crear")
		{
			$str="REGISTRAR CARGA";
		}
		elseif($item == "copiar")
		{
			$str="COPIAR CARGA";
		}
		else
		{
			$str="REGISTRAR CARGA";
		}
		
		$valor[10]=$_REQUEST['nivel'];
		$valor[1]=$usuario;
		?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
			<table align="center" border="0" cellpadding="0" width="500" height="100" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
				<tr>
					<td style='text-align:center' align="center" colspan="2">
						<h4 class="bloquelateralcuerpo"><?echo $str?></h4>
						<hr>
					</td>
				</tr>
			</table>
			<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
								<td class="cuadro_brown" >
								<br>
									<ul>
										<li>
										</li>
									</ul>
								</td>
							</tr>
						</table>
						<br>
						<table class="formulario" align="center">
							<tr  class="bloquecentralencabezado">
								<td colspan="3" align="center">
									<p><span class="texto_negrita">Duplicar Carga Acad&eacute;mica</span></p>
								</td>
							</tr>
							<tr>
								<td>
									Proyecto Curricular
								</td>
								<td colspan="2">
									<?
										include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
										$html=new html();
										$valor[10]=$_REQUEST['nivel'];
										$valor[2]=$ano;
										$valor[3]=$per;
											
										$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carreras",$valor);
										$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
										
										$configuracion["ajax_function"]="xajax_nombreCurso";
										$configuracion["ajax_control"]="proyecto";
										for ($i=0; $i<count($resultado);$i++)
										{
											$registro[$i][0]=$resultado[$i][0];
											$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
										}
										$mi_cuadro=$html->cuadro_lista($registro,'proyecto',$configuracion,-1,3,FALSE,$tab++,"proyecto",100);
										
										echo $mi_cuadro;
									
									?>
								</td>
							</tr>
							<tr align='center'>
								<td colspan="3">
									<table class="tablaBase">
										<tr>
											
											<td align="center">
												<? $tab1=isset($tab)?$tab:'';?>
												<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
												<input type='hidden' name='duplicar' value='duplicar'>
												<input value="Duplicar Carga" name="duplicar" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>		
						
		<?
	}

	//Formulario para consultar docentes
	function consultaDocentes($configuracion)
	{	
		 ?>
		<script>
			function mostrar_div(elemento)
			{
				if(elemento.value=="ced") {
				    document.getElementById("campo_nombres").style.display = "none";
				    document.getElementById("campo_cedula").style.display = "block";
				    document.getElementById("campo_apellidos").style.display = "none";  
				    document.forms[0].palabraEA.value='';
				}else if(elemento.value=="nom") {
				    document.getElementById("campo_cedula").style.display = "none";
				    document.getElementById("campo_nombres").style.display = "none";
				    document.getElementById("campo_apellidos").style.display = "block";
				    document.forms[0].codigoEA.value='';
				}else if(elemento.value=="ape") {
				    document.getElementById("campo_cedula").style.display = "none";
				    document.getElementById("campo_nombres").style.display = "block";
				    document.getElementById("campo_apellidos").style.display = "none";
				    document.forms[0].codigoEA.value='';
				}else {
				    document.getElementById("campo_cedula").style.display = "block";
				}

			}
			</script>
		<?
		$valor[10]=$_REQUEST['nivel'];
		$valor[1]=isset($usuario)?$usuario:'';
		$valor[13]=isset($_REQUEST['tipo'])?$_REQUEST['tipo']:'';
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
				
		$valor[2]=$ano;
		$valor[3]=$per;

		$valor[5]=isset($_REQUEST['proyecto'])?$_REQUEST['proyecto']:'';
		$valor[6]=isset($_REQUEST['curso'])?$_REQUEST['curso']:''; 
		$valor[7]=isset($_REQUEST['grupo'])?$_REQUEST['grupo']:'';
		
		?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
			<div id="campo_documento">
				<table class="sigma_borde centrar" width="100%">
					<tr class="sigma">
						<td class="sigma derecha" width="20%">
							C&eacute;dula<br>
							Apellidos<br>
							Nombres
						</td>
						<td class="sigma centrar" width="2%">
							<input type="radio" name="codigorad" value="ced" checked onclick="javascript:mostrar_div(this)"><br>
							<input type="radio" name="codigorad" value="nom" onclick="javascript:mostrar_div(this)">
							<input type="radio" name="codigorad" value="ape" onclick="javascript:mostrar_div(this)">
						</td>
						<td  class="sigma centrar">
							<div align="center" id="campo_cedula">
								<table class="sigma centrar" width="80%" border="0">
									<tr>
										<td class="sigma centrar" colspan="3">
											<font size="1">Digite el n&uacute;mero de documento de identificaci&oacute;n.</font><br>
											<input type="text" name="identificacion" value="" size="25" maxlength="25">
										</td>
										<td class="sigma centrar" rowspan="2">
											<? $tab1=isset($tab)?$tab:''; ?>
											<input type='hidden' name='anio' value='<? echo $valor[2] ?>'>
											<input type='hidden' name='per' value='<? echo $valor[3] ?>'>
											<input type='hidden' name='proyecto' value='<? echo $valor[5] ?>'>
											<input type='hidden' name='curso' value='<? echo $valor[6] ?>'>
											<input type='hidden' name='grupo' value='<? echo $valor[7] ?>'>
											<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
											<input type='hidden' name='tipo' value='<? echo $valor[13] ?>'>
											<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
											<input type='hidden' name='opcion' value='consultaDocente'>
											<input value="Consultar" name="consultaDocente" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
										</td>
									</tr>
								</table>
							</div>
							<div align="center" id="campo_apellidos" style="display:none">
								<table class="sigma centrar"  width="80%" border="0" >
									<tr>
										<td class="sigma centrar" colspan="2">
											<font size="1">Digite el (los) apellido(s).</font><br>
											<input type="text" name="apellidos" value="" size="25" maxlength="25">
										</td>
										<td class="sigma centrar" rowspan="2">
											<? $tab1=isset($tab)?$tab:''; ?>
											<input type='hidden' name='anio' value='<? echo $valor[2] ?>'>
											<input type='hidden' name='per' value='<? echo $valor[3] ?>'>
											<input type='hidden' name='proyecto' value='<? echo $valor[5] ?>'>
											<input type='hidden' name='curso' value='<? echo $valor[6] ?>'>
											<input type='hidden' name='grupo' value='<? echo $valor[7] ?>'>
											<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
											<input type='hidden' name='tipo' value='<? echo $valor[13] ?>'>
											<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
											<input type='hidden' name='opcion' value='consultaDocente'>
											<input value="Consultar" name="consultaDocente" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
										</td>
									</tr>
								</table>
							</div>
							<div align="center" id="campo_nombres" style="display:none">
								<table class="sigma centrar"  width="80%" border="0" >
									<tr>
										<td class="sigma centrar" colspan="2">
											<font size="1">Digite el (los) nombre(s).</font><br>
											<input type="text" name="nombres" value="" size="25" maxlength="25">
										</td>
										<td class="sigma centrar" rowspan="2">
											<? $tab1=isset($tab)?$tab:''; ?>
											<input type='hidden' name='anio' value='<? echo $valor[2] ?>'>
											<input type='hidden' name='per' value='<? echo $valor[3] ?>'>
											<input type='hidden' name='proyecto' value='<? echo $valor[5] ?>'>
											<input type='hidden' name='curso' value='<? echo $valor[6] ?>'>
											<input type='hidden' name='grupo' value='<? echo $valor[7] ?>'>
											<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
											<input type='hidden' name='tipo' value='<? echo $valor[13] ?>'>
											<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
											<input type='hidden' name='opcion' value='consultaDocente'>
											<input value="Consultar" name="consultaDocente" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
				</table>   
			</div>
		    </form>
		<?
	}

	//Muestra el formulario de asignación de carga, los datos del curso y la grilla con el horario del curso.
	function verGrilla($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
						
		if($usuario=="")
		{
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$variable=isset($variable)?$variable:'';  
		$calendario=$this->validaCalendario($variable,$configuracion);
		//$observaciones=$this->notasobservaciones();guardarNotas
		$valor[10]=$_REQUEST['nivel'];
		$valor[1]=$usuario;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
				
		$valor[2]=$ano;
		$valor[3]=$per;
		$valor[13]=isset($_REQUEST['tipo'])?$_REQUEST['tipo']:'';	
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
		$rowfechoy=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$fechahoy = $rowfechoy[0][0];
		
		$valor[5]= $_REQUEST['proyecto'];
		$valor[6]= $_REQUEST['curso'];
		$valor[7]= $_REQUEST['grupo'];
		if(is_numeric($_REQUEST['identificacion']))
		{
			$valor[8]= $_REQUEST['identificacion'];
		}
		else
		{
			$valor[8]= "";
		}
		
		if($_REQUEST['nombres']=="")
		{
			$valor[9]= $_REQUEST['nombres'];
		}
		else
		{
			$valor[9]= "%".$_REQUEST['nombres']."%";
		}
		//echo "mmm".$_REQUEST['apellidos'];
		if($_REQUEST['apellidos']=="")
		{
			$valor[11]= $_REQUEST['apellidos'];
		}
		else
		{
			$valor[11]= "%".$_REQUEST['apellidos']."%";
		}  

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso",$valor);
		$datoscurso=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
				
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"dia",$valor);
		$registrodia=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentadias=count($registrodia);
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"hora",$valor);
		$registrohora=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentahoras=count($registrohora);
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"proyecto",$valor);
		$registroproyecto=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		?>
		
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown">
								<ul>
									<li>Para registrar una carga, seleccione el Docente, consult&aacute;ndolo mediante el formulario de consulta de docentes, o selecci&oacute;nelo de la lista en el formulario de asignar curso.</li>
									<li>Seleccione el tipo de vinculaci&oacute;n del Docente.</li>
									<li>El n&uacute;mero de horas corresponde al total de horas programadas para el curso y grupo seleccionado, puede ser modificado, si es necesario.</li>
								</ul>
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td class="" align="center">
								<p><span class="texto_negrita">REGISTRO CARGA ACAD&Eacute;MICA PERIODO <?echo $ano.'-'.$per;?></span></p>
							</td>
						</tr>
					</table>
					<table class="contenidotabla centrar">
						<tr>
							<td>
							<fieldset>
								<legend>
									Consulta de docentes
								</legend>
									<? $this->consultaDocentes($configuracion);?>
								</td>
							</fieldset>
						</tr>
					</table>
					<table class="contenidotabla centrar">
						<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
						<tr>
							<td align="center">
								<fieldset>
									<legend>
										Asignar curso
									</legend>
									<table class="formulario">
										<tr>
											<td>
												<font color="red">*</font>Docentes:
											</td>
											<td>
												<?
												if(!$_REQUEST['consultaDocente'] || $_REQUEST['consultaDocente']==" ")
												{
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"docentes","");
													$registroDocente=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													$html='<select name="docente">';
													$html.='<option value=""> </value>';	  
													$i=0;
													while(isset($registroDocente[$i][0]))
													{
														$html.='<option value='.$registroDocente[$i][0].'>'.$registroDocente[$i][0].' '.$registroDocente[$i][1].'</option>';
													$i++;  
													}
													$html.='</select>';
													echo $html;
												}
												else
												{
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"consultaDocentes",$valor);
													$registroDocente=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													$html='<select name="docente">';
													//$html.='<option value=""> </value>';	  
													$i=0;
													while(isset($registroDocente[$i][0]))
													{
														$html.='<option value='.$registroDocente[$i][0].'>'.$registroDocente[$i][0].' '.$registroDocente[$i][1].'</option>';
													$i++;  
													}
													$html.='</select>';
													echo $html;
												}
												?>
												
												
											</td>
										</tr>
										<tr>
											<td>
												  <font color="red">*</font>Tipo de Vinculaci&oacute;n:
											</td>
											<td>
												<?
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"tipoVinculacion","");
												$registroTipVin=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												$html='<select name="tipVin">';
												$html.='<option value=""> </value>';	  
												$i=0;
												while(isset($registroTipVin[$i][0]))
												{
													$html.='<option value='.$registroTipVin[$i][0].'>'.$registroTipVin[$i][0].' '.$registroTipVin[$i][1].'</option>';
												$i++;  
												}
												$html.='</select>';
												echo $html;
												?>
											</td>
										</tr>
										<tr>
											<td>
												<font color="red">*</font>No. de Horas:
											</td>
											<td>
												<?
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cuentaHorarioCursoRegistrado",$valor);
												$registro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												$tab1=isset($tab)?$tab:''; 
												?>
												<input maxlength="4" value="<? echo $registro[0][0] ?>" size="4" tabindex="<? echo $tab1++ ?>" name="numhoras"><br>
											</td>
										</tr>
										<tr align='center'>
											<td colspan="16">
												<table class="tablaBase">
													<tr>
														
														<td align="center">
															<? $tab1=isset($tab)?$tab:''; ?>
															<input type='hidden' name='usuario' value='<? echo $valor[1]?>'>
															<input type='hidden' name='anio' value='<? echo $valor[2] ?>'>
															<input type='hidden' name='per' value='<? echo $valor[3] ?>'>
															<input type='hidden' name='carrera' value='<? echo $registroproyecto[0][0] ?>'>
															<input type='hidden' name='curso' value='<? echo $datoscurso[0][0] ?>'>
															<input type='hidden' name='grupo' value='<? echo  $datoscurso[0][2] ?>'>
															<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
															<input type='hidden' name='tipoFormulario' value='docentes'>
															<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
															<input type='hidden' name='opcion' value='registrar'>
															<input value="Grabar" name="aceptar" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
														</td>
														<!--td align="center">
															<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
															<input type="submit" name="notdef" value="Calcular Acumulado">
														</td-->
														<td align="center">
															<input type='hidden' name='usuario' value='<? echo $valor[1]?>'>
															<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab1++ ?>'  /><br>
														</td>
													</tr>
													<tr class="bloquecentralcuerpo">
														<td colspan="3" rowspan="1">
															Los campos marcados con <font color="red">*</font> deben ser diligenciados obligatoriamente.<br><br>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</fieldset>	
							</td>
						</tr>
						</form>
						<tr>
							<td>
								<fieldset>
									<legend>
										Docentes registrados
									</legend>
									<table class="contenidotabla">
										<tr>
											<td colspan="">
												Proyecto Curricular:
											</td>
											<td colspan="">
												<? echo $registroproyecto[0][0].' - '.$registroproyecto[0][1];?>
											</td>
											<td width='10%'>
												Curso:
											</td>
											<td width='15%' align="left">
												<? echo $datoscurso[0][0];?>
											</td>
										</tr>
										<tr>
											<td width='10%'>
												Nombre:
											</td>
											<td width='40%' align="left">
												<? echo $datoscurso[0][1];?>
											</td>
											<td width='10%'>
												Grupo No.:
											</td>
											<td width='15%' align="left">
												<? echo $datoscurso[0][2];?>
											</td>
										</tr>
										<tr>
											<td cuadro_color class="cuadro_color">
												Docentes:
											</td>
											<td colspan="4" class="cuadro_color">
												<?
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cursoDocentes",$valor);
												$registro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cuentaHorarioCursoRegistrado",$valor);
												$registroCuentaHorario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"totalHorasCarga",$valor);
												$registroTotalHoras=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												//echo "mmmm".$registroTotalHoras[0][0];
												if(!is_array($registro))
												{
													include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
													$cadena='A este curso no se le han asignado docentes.';
													alerta::sin_registro($configuracion,$cadena);
												}
												else
												{
													echo	'<table  class="contenidotabla" border="1">
															<tr>
																<td class="cuadro_brown">
																	Documento No.
																</td>
																<td class="cuadro_brown">
																	Nombre
																</td>
																<td class="cuadro_brown">
																	No. horas
																</td>
																<td class="cuadro_brown">
																	Borrar Carga
																</td>  
															</tr>';
															$i=0;
															while(isset($registro[$i][0]))
															{
													echo		'<tr>	
															 	<td class="cuadro_color">'
																	.$registro[$i][0].  
																'</td>
																<td class="cuadro_color">'
																	.$registro[$i][1].  
																'</td>
																<td class="cuadro_color">'
																	.$registro[$i][2].  
																'</td>';
																include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
																include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																
																$valor[5]= $registroproyecto[0][0];
																$valor[6]= $datoscurso[0][0];
																$valor[7]= $datoscurso[0][2];
																$valor[8]= $registro[$i][0];
																$valor[9]= $registro[$i][1];
																$valor[10]=$_REQUEST['nivel'];

																setlocale(LC_MONETARY, 'en_US');
																$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
																$cripto=new encriptar();
																echo "<td><a href='";
																$variable="pagina=registroCargaAcademica";
																$variable.="&opcion=borrar";
																$variable.="&docente=".$valor[8];
																$variable.="&ano=".$valor[2];
																$variable.="&per=".$valor[3];
																$variable.="&carrera=".$valor[5];
																$variable.="&curso=".$valor[6];
																$variable.="&grupo=".$valor[7];
																$variable.="&nombre=".$valor[9];
																$variable.="&nivel=".$valor[10];
																$variable.="&nombreCurso=".$datoscurso[0][1];
																$variable.="&tipoFormulario=docentes";
																//$variable.="&no_pagina=true";
																$variable=$cripto->codificar_url($variable,$configuracion);
																echo $indice.$variable."'";
																echo "title='Haga Click aqu&iacute; para eliminar esta carga.'>";
																?>
																<center><img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/boton_borrar.png" border="0"></center>
																</a></td>
															</tr><?
																$i++;
															}
															if($registroTotalHoras[0][0]>$registroCuentaHorario[0][0])
															{
																echo '<tr><td colspan="4">';
																include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
																$cadena='Se&ntilde;or Coordinador las horas asignadas a los Docentes suman '.$registroTotalHoras[0][0].' horas, cuando el n&uacute;mero de horas programadas para el curso es de '.$registroCuentaHorario[0][0].' horas.';
																alerta::sin_registro($configuracion,$cadena);
																echo '</td></tr>';
															}
															$mensaje=isset($_REQUEST['mensaje'])?$_REQUEST['mensaje']:'';
															if($mensaje)
															{
																echo '<tr><td colspan="4">';
																include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
																$cadena=$_REQUEST['mensaje'];
																alerta::sin_registro($configuracion,$cadena);
																echo '</td></tr>';
															}  
													echo	'</table>';
												}
												?>
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
						<tr>
							<td align="center">
								<fieldset>
									<legend>
										Horario del curso
									</legend>
									<table class="contenidotabla">
										<thead class="cuadro_color">
											<td class="cuadro_plano centrar"></td>
											<?
											for ($i=0; $i<=$cuentadias-1; $i++)
											{
												echo '<td class="cuadro_plano centrar">'
												.$registrodia[$i][1].
												'</td>';
											}		
											?>
										</thead>
										<?
										
										for ($i=0; $i<=$cuentahoras-1; $i++)
										{
											echo '<tr>
												<td class="cuadro_plano centrar">'
													.$registrohora[$i][1].
												'</td>';
												$j=0;
												while(isset($registrodia[$j][0]))
												{
													$valor[0]=$usuario;
													$valor[1]=$ano;
													$valor[2]=$per;
													$valor[3]=$registrodia[$j][0];
													$valor[4]=$registrohora[$i][0];
													
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horarioCursoRegistrado",$valor);
													$registrocarga=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													
													$datos=explode(' - ',$registrocarga[0][0]);
												      
													$dato0=isset($datos[0])?$datos[0]:' - ';
													$dato1=isset($datos[1])?$datos[1]:' - ';
													$dato2=isset($datos[2])?$datos[2]:' - ';
                                                                                                        //nuevo
                                                                                                        $datosEx=explode('  ',$datos[3]);
													$dato3=isset($datosEx[0])?$datosEx[0]:' - ';
                                                                                                        $dato4=isset($datosEx[1])?$datosEx[1]:'';
                                                                                                        
													$title_carga= 'Curso:'. $dato0.'<br>Grupo: '.$dato1.'<br> Sal&oacute;n: '.$dato2.'<br>Sede: '.$dato3.'<br>Edif: '.$dato4;
													$title_celdaVacia= 'D&iacute;a: '. $registrodia[$j][1].'<br>Hora: '.$registrohora[$i][1];
													
													if(is_array($registrocarga))
													{
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																														
														setlocale(LC_MONETARY, 'en_US');
														$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
														$cripto=new encriptar();
														?>
														<td class="cuadro_plano centrar" onmouseover="toolTip('<BR><?echo $title_carga.'<br>'.$title_celdaVacia;?>&nbsp;&nbsp;&nbsp;',this)" >
														<div class="centrar">
															<span id="toolTipBox" width="300" ></span>
														</div>
														<?
															echo 'Sal&oacute;n: '.$datos[2].'<br>';
															echo 'Sede: '.$dato3.'<br>';
                                                                                                                        echo 'Edificio: '.$dato4.'<br>';
														?>
														</td><?
													}
													else
													{
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																														
														setlocale(LC_MONETARY, 'en_US');
														$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
														$cripto=new encriptar();
																												
														?>
														<td class="cuadro_planito centrar" onmouseover="toolTip('<BR><?echo $title_celdaVacia;?>&nbsp;&nbsp;&nbsp;',this)" >
															<div class="centrar">
																<span id="toolTipBox" width="300" ></span>
															</div>
														
														</td>
														<?
													}
												$j++;
												}
											echo '</tr>';
										}
											
										?>
									</table>
								</fieldset>	
							</td>
						</tr>
						
						<tr>
							
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?
	}

	//Registrar la carga académica.
	function registrarCarga($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
						
		if($usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		
		unset($valor);
		$curso=isset($_REQUEST['cursos'])?$_REQUEST['cursos']:'';
		if($curso)
		{
			//echo "mmm".$_REQUEST['cursos'];
			$variable=explode('#',$_REQUEST['cursos']);
			$valor[6]= $variable[0];
			$valor[7]= $variable[1];  
		}
		else
		{
			$valor[6]= $_REQUEST['curso'];
			$valor[7]= isset($_REQUEST['grupo'])?$_REQUEST['grupo']:'';
		}
		
		$valor[1]=$usuario;
		$valor[2]=$_REQUEST['anio'];
		$valor[3]=$_REQUEST['per'];
		$valor[5]= $_REQUEST['carrera'];
		//$valor[8]= $_REQUEST['docente'];
		$valor[8]= isset($_REQUEST['docente'])?$_REQUEST['docente']:'';
		$valor[16]= $_REQUEST['numhoras'];
		$valor[10]= $_REQUEST['nivel'];
		$valor[11]= $_REQUEST['tipVin'];
		$valor[9]=isset($_REQUEST['nombre'])?$_REQUEST['nombre']:'';
		$valor[15]=isset($_REQUEST['apellidos'])?$_REQUEST['apellidos']:'';
		$valor[11]=isset($_REQUEST['tipVin'])?$_REQUEST['tipVin']:'';
		$valor[12]=isset($_REQUEST['consultaDocente'])?$_REQUEST['consultaDocente']:'';
			
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horarioCurso",$valor);
		$regHorarioCurso=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if($_REQUEST['docente']=="")
		{
			$valor[8]=1;
		}
		//echo $regHorarioCurso[0][4]."<br>";
		//exit;
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horarioDocentes",$valor);
                //exit;
		$regHorarioDocente=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cierto=0;
		$j=0;
		while(isset($regHorarioCurso[$j][0]))
		{
			$curso=$regHorarioCurso[$j][2].''.$regHorarioCurso[$j][3];
			//echo "curso".$curso."<br>";
			$i=0;
			while(isset($regHorarioDocente[$i][0]))
			{  $docente=$regHorarioDocente[$i][0].''.$regHorarioDocente[$i][1];
		 	   if($regHorarioCurso[$j][4]!='N' && $regHorarioCurso[$j][2]==$regHorarioDocente[$i][0] && $regHorarioCurso[$j][3]==$regHorarioDocente[$i][1])
				{
					$cierto=1;
					$diaHora=$regHorarioDocente[$i][0].''.$regHorarioDocente[$i][1];
					//echo 'mmm'.$diaHora;
				}
			$i++;
			}
		$j++;
		}

		//echo "mmm".$_REQUEST['tipoFormulario']."<br>".$cierto;
		//exit;
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cuentaHorarioCursoRegistrado",$valor);
		$registro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$numhoras=$registro[0][0];

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaCatedra",$valor);
		$verificaCatedra=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if($_REQUEST['docente']=="" || $_REQUEST['tipVin']=="")
		{
			$valor[4]=1; //Mensaje 1
			$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
		}
		elseif($valor[9]>$registro[0][0] && $verificaCatedra[0][1]=='N')
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="El n&uacute;mero de horas asignadas al Docente, no puede ser mayor al n&uacute;mero de horas programadas para el curso.<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);
		}
		elseif($_REQUEST['numhoras']> $numhoras && $verificaCatedra[0][1]=='N')
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='El n&uacute;mero de horas no puede ser mayor al n&uacute;mero de horas programadas en el horario del curso.';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);
		}
		elseif($_REQUEST['numhoras']<=0)
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='El n&uacute;mero de horas debe ser diferente a 0 (cero).';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);
		}
		elseif($cierto==1 && $_REQUEST['tipoFormulario']=='docentes')
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='El horario del curso '.$valor[6].' grupo '.$valor[7].', presenta cruce con la carga lectiva registrada del docente '.$valor[8].'.';

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);
		}
		elseif($cierto==1 && $_REQUEST['tipoFormulario']=='cursos')
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='El horario del curso '.$valor[6].' grupo '.$valor[7].', presenta cruce con la carga lectiva registrada del docente '.$valor[8].'.';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "registrarCarga",$valor);
			if($_REQUEST['tipoFormulario']=='docentes')
			{
				$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			}
			else
			{
				$resultado3=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			}
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"consultarTipVin",$valor);
			$regTipVin=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

			if(!is_array($regTipVin))
			{
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "registrarTipVin",$valor);
				if($_REQUEST['tipoFormulario']=='docentes')
				{
					$resultado2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
				}
				else
				{
					$resultado4=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
				}
			}
			else
			{
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "modificarTipVin",$valor);
				if($_REQUEST['tipoFormulario']=='docentes')
				{
					$resultado2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
				}
				else
				{
					$resultado4=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
				}
			}

			$res1=isset($resultado1)?$resultado1:'';
			$res2=isset($resultado2)?$resultado2:'';
			$res3=isset($resultado3)?$resultado3:'';
			$res4=isset($resultado4)?$resultado4:'';  
			if($res1==TRUE || $res2==TRUE)
			{
				/*include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena="El registro se guard&oacute; exitosamente!!<br>";
				$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
				alerta::sin_registro($configuracion,$cadena);*/
				$this->redireccionarInscripcion($configuracion,"registroExitoso",$valor);
			}
			elseif($res3==TRUE || $res4==TRUE)
			{
				/*include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena="El registro se guard&oacute; exitosamente!!<br>";
				$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
				alerta::sin_registro($configuracion,$cadena);*/
				$this->redireccionarInscripcion($configuracion,"registroExitosoCursos",$valor);
			}
			else
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena='El registro no se pudo guardar, revise que que se hayan realizado todos los pasos correctamente e intente de nuevo.!!';
				$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
				alerta::sin_registro($configuracion,$cadena);
			}
		}

	}
	
	//Confirma el borrrado de una carga a un docente
	function borrarCarga($configuracion)
	{
		unset($valor);
		$valor[8]=$_REQUEST['docente'];
		$valor[2]=$_REQUEST['ano'];
		$valor[3]=$_REQUEST['per'];
		$valor[5]=$_REQUEST['carrera'];
		$valor[6]=$_REQUEST['curso'];
		$valor[7]=$_REQUEST['grupo'];
		$valor[9]=$_REQUEST['nombre'];
		$valor[10]=$_REQUEST['nivel'];
		$valor[21]=$_REQUEST['nombreCurso'];
		$valor[22]=$_REQUEST['tipoFormulario'];
		//echo 'mmm'.$valor[22].'<br>';
		?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
			<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
									<td class="cuadro_brown">
										<br>
										<ul>
											<li> Si est&aacute; seguro de borrar la carga asignada al docente relacionado a continuaci&oacute;n, haga click en el bot&oacute;n "Borrar".</li>
											
										</ul>
									</td>
									
								</tr>
							
							<tr class="texto_subtitulo">
								<td class="" colspan="5" align="center">
									<p><span class="texto_negrita">ESTA SEGURO DE BORRAR LA CARGA AL DOCENTE RELACIONADO A CONTINUACI&Oacute;N?</span></p>
								</td>
							</tr>
						</table>
						
						<table class="contenidotabla centrar">
							<tr>
								<td>
									<fieldset>
										<legend>
											Informaci&oacute;n de la carga
										</legend>
										<table class="contenidotabla">
											<tr>
												<td width='20%'>
													Docente:
												</td>
												<td width='30%' align="left">
													<? echo $valor[9];?>
												</td>
												<td width='20%'>
													Identificaci&oacute;n:
												</td>
												<td width='30%' align="left">
													<? echo  $valor[8];?>
												</td>
												<td width='25%'>
													Per&iacute;odo:
												</td>
												<td width='25%' align="left">
													<? echo  $valor[2].' - ' .$valor[3];?>
												</td>
											</tr>
											<tr>
												<td width='20%'>
													Curso
												</td>
												<td width='30%' colspan='3' align="left">
													<? echo  $valor[21];?>
												</td>
												<td width='25%'>
													Grupo
												</td>
												<td width='25%'>
													<? echo $valor[7];?>
												</td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>
							
							<tr align='center'>
								<td colspan="16">
									<table class="tablaBase">
										<tr>
											<td align="center">
												<? $tab1=isset($tab)?$tab:''; ?>
												<input type='hidden' name='docente' value='<? echo $valor[8]?>'>
												<input type='hidden' name='nombre' value='<? echo $valor[9]?>'>
												<input type='hidden' name='anio' value='<? echo $valor[2] ?>'>
												<input type='hidden' name='per' value='<? echo $valor[3] ?>'>
												<input type='hidden' name='carrera' value='<? echo $valor[5] ?>'>
												<input type='hidden' name='curso' value='<? echo $valor[6] ?>'>
												<input type='hidden' name='grupo' value='<? echo $valor[7] ?>'>
												<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
												<input type='hidden' name='nombreCurso' value='<? echo $valor[21] ?>'>
												<input type='hidden' name='tipoFormulario' value='<? echo $valor[22] ?>'>
												<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
												<input type='hidden' name='opcion' value='borrarCarga'>
												<input value="Borrar" name="borrar" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
											</td>
											<!--td align="center">
												<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
												<input type="submit" name="notdef" value="Calcular Acumulado">
											</td-->
											<td align="center">
												<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab1++ ?>'  /><br>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>	
		<?
	}
	
	function eliminarCarga($configuracion)
	{
		unset($valor);
		$valor[8]=$_REQUEST['docente'];
		$valor[2]=$_REQUEST['anio'];
		$valor[3]=$_REQUEST['per'];
		$valor[5]=$_REQUEST['carrera'];
		$valor[6]=$_REQUEST['curso'];
		$valor[7]=$_REQUEST['grupo'];
		$valor[10]=$_REQUEST['nivel'];
		$valor[21]=$_REQUEST['nombreCurso'];
		$valor[22]=$_REQUEST['tipoFormulario'];
		$valor[9]=isset($_REQUEST['nombre'])?$_REQUEST['nombre']:'';
		$valor[15]=isset($_REQUEST['apellidos'])?$_REQUEST['apellidos']:'';
		$valor[11]=isset($_REQUEST['tipVin'])?$_REQUEST['tipVin']:'';
		$valor[12]=isset($_REQUEST['consultaDocente'])?$_REQUEST['consultaDocente']:'';
		//echo $_REQUEST['tipoFormulario']."<br>";
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "borraCarga",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
		if($resultado==TRUE)
		{
			$cierto=1;
		}
		if($cierto==1 && $_REQUEST['tipoFormulario']=='docentes')
		{
			//unset($valor);
			$valor[0]='El docente '.$valor[9].', identificado con documento No. '.$valor[8].', fue borrado exitosamente.';
			$this->redireccionarInscripcion($configuracion,"registroExitoso",$valor);
		}
		if($cierto==1 && $_REQUEST['tipoFormulario']=='cursos')
		{
			$valor[0]='El curso '.$_REQUEST['nombreCurso'].', Grupo No. '.$valor[7].', fue borrado exitosamente.';
			$this->redireccionarInscripcion($configuracion,"registroExitosoCursos",$valor);
		}
	}
 
	//Muestra los mensajes de errores que se puedan presentar.	
	function mensajesErrores($configuracion)
	{
		?><table class="fondoImportante" align="center">
			<tr>
				<td class="cuadro_brown" >
				<br>
					<?
					/*foreach($_REQUEST as $clave=>$valor)
					{
						echo $clave.'--'.$valor."<br>";
						
					}*/
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
					$regresar="<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-1)' style='cursor:pointer;'>";
					$regresar.='<img src="';
					$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
					$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
					$regresar.= '<br>Regresar</a></center>';
					
					if($_REQUEST['mensaje']==1)
					{
						$valor[0]= $_REQUEST['mensaje'];
						$cadena="Todos los campos marcados con * son obligatorios.";
						alerta::sin_registro($configuracion,$regresar,$cadena);
						//echo $regresar;
					}
					
					?>	
				</td>
			</tr>
		</table><?
	}
	
	//Ejecutar el procedimiento que duplica la carga acad&eacute;mica
	function EjecutarDuplicadoCarga($configuracion)
	{
		unset($valor);
		$variable=explode('#',$_REQUEST['proyecto']);
		$valor[0]=$variable[0];
		echo "mmmmm".$variable[0]."<br>";

		$copiaHorario= "BEGIN pra_copiahorario(".$valor[0]."); END; ";
		echo "mmmmm".$copiaHorario;
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $copiaHorario, "copiaHorario");
	}

	function verReportes($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
						
		if($usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		//$calendario=$this->validaCalendario($variable,$configuracion);
		//$observaciones=$this->notasobservaciones();guardarNotas
		$valor[10]=$_REQUEST['nivel'];
		$valor[1]=$usuario;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
				
		$valor[2]=$ano;
		$valor[3]=$per;
			
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
		$rowfechoy=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$fechahoy = $rowfechoy[0][0];
		
		$valor[5]= $_REQUEST['proyecto'];
		$valor[6]= $_REQUEST['curso'];
		$valor[7]= $_REQUEST['grupo'];

		//echo "mmm".$_REQUEST['curso']."<br>";
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"curso",$valor);
		$datoscurso=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
				
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"dia",$valor);
		$registrodia=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentadias=count($registrodia);
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"hora",$valor);
		$registrohora=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentahoras=count($registrohora);
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"proyecto",$valor);
		$registroproyecto=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		?>
		
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr class="texto_subtitulo">
							<td class="" align="center">
								<p><span class="texto_negrita">CARGA ACAD&Eacute;MICA PERIODO <?echo $ano.'-'.$per;?></span></p>
							</td>
						</tr>
					</table>
					
					<table class="contenidotabla centrar">
						<tr>
							<td>
								<fieldset>
									<legend>
										Datos del Curso
									</legend>
									<table class="contenidotabla">
										<tr>
											<td colspan="">
												Proyecto Curricular:
											</td>
											<td colspan="">
												<? echo $registroproyecto[0][0].' - '.$registroproyecto[0][1];?>
											</td>
											<td width='10%'>
												Curso:
											</td>
											<td width='15%' align="left">
												<? echo $datoscurso[0][0];?>
											</td>
										</tr>
										<tr>
											<td width='10%'>
												Nombre:
											</td>
											<td width='40%' align="left">
												<? echo $datoscurso[0][1];?>
											</td>
											<td width='10%'>
												Grupo No.:
											</td>
											<td width='15%' align="left">
												<? echo $datoscurso[0][2];?>
											</td>
										</tr>
										<tr>
											<td cuadro_color class="cuadro_color">
												Docentes:
											</td>
											<td colspan="3" class="cuadro_color">
												<?
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cursoDocentes",$valor);
												$registro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												if(!is_array($registro))
												{
													echo "A este curso no se le han asignado docentes";
												}
												else
												{
													echo	'<table  class="contenidotabla" border="1">
															<tr>
																<td class="cuadro_brown">
																	C&eacute;dula
																</td>
																<td class="cuadro_brown">
																	Nombre
																</td>
																<td class="cuadro_brown">
																	No. horas
																</td>
															</tr>';
															$i=0;
															while(isset($registro[$i][0]))
															{
													echo		'<tr>	
															 	<td class="cuadro_color">'
																	.$registro[$i][0].  
																'</td>
																<td class="cuadro_color">'
																	.$registro[$i][1].  
																'</td>
																<td class="cuadro_color">'
																	.$registro[$i][2].  
																'</td>
															</tr>';
															$i++;
															}
													echo	'</table>';
												}
												?>
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
						<tr>
							<td align="center">
								<fieldset>
									<legend>
										Horario del curso
									</legend>
									<table class="contenidotabla">
										<thead class="cuadro_color">
											<td class="cuadro_plano centrar"></td>
											<?
											for ($i=0; $i<=$cuentadias-1; $i++)
											{
												echo '<td class="cuadro_plano centrar">'
												.$registrodia[$i][1].
												'</td>';
											}		
											?>
										</thead>
										<?
										
										for ($i=0; $i<=$cuentahoras-1; $i++)
										{
											echo '<tr>
												<td class="cuadro_plano centrar">'
													.$registrohora[$i][1].
												'</td>';
												$j=0;
												while(isset($registrodia[$j][0]))
												{
													//unset($valor);
													$valor[0]=$usuario;
													$valor[1]=$ano;
													$valor[2]=$per;
													$valor[3]=$registrodia[$j][0];
													$valor[4]=$registrohora[$i][0];
													
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"horarioCursoRegistrado",$valor);
													$registrocarga=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													
													$datos=explode(' - ',$registrocarga[0][0]);
													$datos[0]=isset($datos[0])?$datos[0]:'';
													$datos[1]=isset($datos[1])?$datos[1]:'';
													$datos[2]=isset($datos[2])?$datos[2]:'';
													$datos[3]=isset($datos[3])?$datos[3]:'';  
													$title_carga= 'Curso:'. $datos[0].'<br>Grupo: '.$datos[1].'<br> Sal&oacute;n: '.$datos[2].'<br>Sede: '.$datos[3];
													$title_celdaVacia= 'D&iacute;a: '. $registrodia[$j][1].'<br>Hora: '.$registrohora[$i][1];
													
													if(is_array($registrocarga))
													{
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																														
														setlocale(LC_MONETARY, 'en_US');
														$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
														$cripto=new encriptar();
														?>
														<td class="cuadro_plano centrar" onmouseover="toolTip('<BR><?echo $title_carga.'<br>'.$title_celdaVacia;?>&nbsp;&nbsp;&nbsp;',this)" >
														<div class="centrar">
															<span id="toolTipBox" width="300" ></span>
														</div>
														<?
															echo 'Sal&oacute;n: '.$datos[2].'<br>';
															echo 'Sede: '.$datos[3].'<br>';
														?>
														</td><?
													}
													else
													{
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																														
														setlocale(LC_MONETARY, 'en_US');
														$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
														$cripto=new encriptar();
																												
														?>
														<td class="cuadro_planito centrar" onmouseover="toolTip('<BR><?echo $title_celdaVacia;?>&nbsp;&nbsp;&nbsp;',this)" >
															<div class="centrar">
																<span id="toolTipBox" width="300" ></span>
															</div>
														
														</td>
														<?
													}
												$j++;
												}
											echo '</tr>';
										}
											
										?>
									</table>
								</fieldset>	
							</td>
						</tr>
						
						<tr>
							
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?
	}  
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
	
	//Rescata el usuario de la variable de sesion.
	function verificarUsuario()
	{
		//Verificar existencia del usuario 	
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosUsuario",$this->identificacion);
		@$unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if(is_array($unUsuario))
		{
			return $unUsuario;			
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "datosUsuario",$this->usuario);
			@$unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");	
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
	
	//Valida que el registro de la carga académica se haga dentro de las fechas establecidas.
	function validaCalendario($variable,$configuracion)
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

		$this->opciones($configuracion);    

		unset($valor);
		$valor[0]=$usuario;

		//echo "mmm".$_REQUEST['proyecto']."<br>";
		$proyecto=isset($_REQUEST['proyecto'])?$_REQUEST['proyecto']:'';
		if(is_numeric($proyecto))
		{
			$valor[5]=$proyecto;
		}
		else
		{
			$variable=explode('#',$proyecto);
			$valor[5]= $variable[0];
		}
		$carrera=isset($_REQUEST['carrera'])?$_REQUEST['carrera']:'';    
		if($carrera)
		{
			$valor[5]=$_REQUEST['carrera'];
		}
		$valor[6]=isset($_REQUEST['curso'])?$_REQUEST['curso']:'';
		$valor[7]=isset($_REQUEST['grupo'])?$_REQUEST['grupo']:'';
		$valor[8]=isset($_REQUEST['docente'])?$_REQUEST['docente']:'';
		$valor[10]=$_REQUEST['nivel'];

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$valor[2]=$ano;
		$valor[3]=$per;
				
		$confec = "SELECT TO_CHAR(SYSDATE, 'YYYYmmddhh24mmss') FROM dual";
		@$rows=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confec, "busqueda");
		$valor[9] =$rows[0][0];
		//echo $valor[9]."<br>";
		$qryFechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "validaFechas",$valor);
		@$calendario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryFechas, "busqueda");
		//echo $qryFechas;
		$FormFecIni = $calendario[0][0];
		$FormFecFin = $calendario[0][1];
				
		//echo "nnn".$FormFecIni."<br>";
		//echo "mmm".$FormFecFin."<br>";
			if(!is_array($calendario))
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
				//$total=count($resultado);
					
				setlocale(LC_MONETARY, 'en_US');
				$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
				$cripto=new encriptar();
				echo '<table width="60%" height="40%" border="0" cellpadding="5" cellspacing="0" align="center">
					<tr>
						<td><fieldset style="padding:20;">
							<table width="80%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
								<tr  class="bloquecentralcuerpo">
									<td valign="top">
									<div><h3><center>Aviso</center></h3></div>
									<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
										<p align="justify">&nbsp;</p>
										<p align="center"><font color="red"><b>Las fechas para registrar la CARGA ACAD&Eacute;MICA para el periodo acad&eacute;mico '.$ano.'-'.$per.', se encuentran cerradas.</p>
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
			case "msgErrores":
				$variable="pagina=registroCargaAcademica";
				$variable.="&opcion=mensajes";
				$variable.="&mensaje=".$valor[4];
				$variable.="&proyecto=".$valor[5];
				$variable.="&curso=".$valor[6];
				break;
			case "formgrado":
				$variable="pagina=registroCargaAcademica";
				$variable.="&nivel=".$valor[10];
				break;
			case "mostrarGrilla":
				$variable="pagina=registroCargaAcademica";
				$variable.="&opcion=mostrarGrilla";
				$variable.="&nivel=".$valor[10];
				$variable.="&proyecto=".$valor[5];
				$variable.="&curso=".$valor[6];
				$variable.="&grupo=".$valor[7];
				$variable.="&identificacion=".$valor[8];
				$variable.="&nombres=".$valor[9];
				$variable.="&apellidos=".$valor[15];
				$variable.="&consultaDocente=".$valor[12];
				break;
			case "verListaDocentes":
				$variable="pagina=registroCargaAcademica";
				$variable.="&opcion=verListaDocentes";
				$variable.="&nivel=".$valor[10];
				$variable.="&proyecto=".$valor[5];
				$variable.="&curso=".$valor[6];
				$variable.="&grupo=".$valor[7];
				$variable.="&identificacion=".$valor[8];
				$variable.="&nombres=".$valor[9];
				$variable.="&apellidos=".$valor[15];
				$variable.="&consultaDocente=".$valor[12];
				break;
			case "registroExitoso":
				$variable="pagina=registroCargaAcademica";
				$variable.="&opcion=mostrarGrilla";
				$variable.="&nivel=".$valor[10];
				$variable.="&nombre=".$valor[9];
				$variable.="&proyecto=".$valor[5];
				$variable.="&curso=".$valor[6];
				$variable.="&grupo=".$valor[7];
				//$variable.="&mensaje=".$valor[0];
				$variable.="&identificacion=".$valor[8];
				$variable.="&nombres=".$valor[9];
				$variable.="&apellidos=".$valor[15];
				$variable.="&tipVin=".$valor[11];
				$variable.="&consultaDocente=".$valor[12];      
				break;
			case "registroExitosoCursos":
				$variable="pagina=registroCargaAcademica";
				$variable.="&opcion=registroExitosoCursos";
				$variable.="&nivel=".$valor[10];
				$variable.="&nombre=".$valor[9];
				$variable.="&proyecto=".$valor[5];
				$variable.="&curso=".$valor[6];
				$variable.="&grupo=".$valor[7];
				//$variable.="&mensaje=".$valor[0];
				$variable.="&docente=".$valor[8];
				$variable.="&nombres=".$valor[9];
				$variable.="&tipVin=".$valor[11];
				$variable.="&consultaDocente=".$valor[12];      
				break;	  
			case "listasCursos":
				$variable="pagina=registroCargaAcademica";
				$variable.="&opcion=ListasCursos";
				$variable.="&nivel=".$valor[10];
				break;
					
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

