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

class funciones_admin_gestionHorarios extends funcionGeneral
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
		
		$this->formulario="admin_gestionHorarios";
		//$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'lugar')";
		
	}

	//Funcion que contiene las opciones para la gestión de horarios.
	function encabezado($configuracion){
		?>
		<table class="contenidotabla centrar">
		    <tr>
			<td colspan="3" class="cuadro_plano centrar"><h4 >GESTI&Oacute;N DE HORARIOS</h4></td>
		    </tr>
		    <?
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$ruta="pagina=adminHorarios";
			$ruta.="&opcion=proyectos";
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$rutaadd=$this->cripto->codificar_url($ruta,$configuracion);


			$ruta="pagina=adminGestionHorarios";
			$ruta.="&opcion=gestionHorarios";
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$rutacopia=$this->cripto->codificar_url($ruta,$configuracion);

			$ruta="pagina=adminHorarios";
			$ruta.="&opcion=consultaHorarios";
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$rutaconsulta=$this->cripto->codificar_url($ruta,$configuracion);

		    ?>
		    <tr>
			<td class="cuadro_plano centrar"><a href="<?echo $indice.$rutacopia?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/copiarHorario.PNG" alt="copiar" border="0"><br>Copiar Horario</a></td>
			<td class="cuadro_plano centrar"><a href="<?echo $indice.$rutaadd?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/addHorario.PNG" alt="add" border="0"><br>Crear Horario</a></td>
			<td class="cuadro_plano centrar"><a href="<?echo $indice.$rutaconsulta?>"><img src="<?echo $configuracion['site'].$configuracion['grafico']?>/verHorario.PNG" alt="add" border="0"><br>Consultar Horario</a></td>
		    </tr>
		    <tr>
			<td colspan="3" align="center">
				<table class="formulario" align="center">
						
					<tr>
						<td align="center">
							<p><a href="https://condor.udistrital.edu.co/appserv/manual/gestion_de_horarios.pdf">
							<img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfito.png"?>" />
							Ver Manual de Usuario.</a></p>
						</td>
					</tr>
				</table>
			</td>
		    </tr>
		    <tr>
			<td colspan="3"></td>
		    </tr>
		</table>

		<?
	}

	//Contiene el formulario para seleccionar el periodo académico del cual se va a copiar el horario y el Proyecto Curriular
	//al cual se le va a realizar la copia.
	function gestioHorarios($configuracion)
	{$this->encabezado($configuracion);
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
		$valor[1]=$usuario;
		$_REQUEST['proyecto']=isset($_REQUEST['proyecto'])?$_REQUEST['proyecto']:'';
		$valor[0]=$_REQUEST['proyecto'];
		
		if($_REQUEST['proyecto']!="")
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaRegistro",$valor);
			$resultadoVerifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			
			if(is_array($resultadoVerifica))
			{
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"borrarPeriodo",$valor);
				$resultadoborrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			}
		}   
		  
		?>
		
	      <?
		//$this->encabezado($configuracion);
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		?>
                <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
                    <table align="center" border="0" cellpadding="0" width="500" height="100" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr>
                            <td style='text-align:center' align="center" colspan="2">
                                <h4 class="bloquelateralcuerpo">COPIAR H0RARIOS</h4>
                                <hr>
                            </td>
                        </tr>
                    </table>
		   
                    <table style="width: 100%; text-align: left;" border="0" cellpadding="5" cellspacing="1">
                        <th align=center class="bloquelateralcuerpo" colspan="2">Seleccione el peridodo acad&eacute;mico y el Proyecto Curricular.</th>
                        <tr>
                                <td class="sigma derecha">
                                        Periodo acad&eacute;mico anterior:
                                </td>
                                <td class="sigma">
                                        <?
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaPeriodos",$valor);
                                            $resultadoPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
                                            $html=new html();

                                            $configuracion["ajax_function"]="xajax_nombreCurso";
                                            $configuracion["ajax_control"]="periodoAnterior";
                                            for ($i=0; $i<4;$i++)
                                            {
                                                    $registro[$i][0]=$resultadoPer[$i][0];
                                                    $registro[$i][1]=UTF8_DECODE($resultadoPer[$i][1]);
                                            }
                                            $mi_cuadro=$html->cuadro_lista($registro,'periodoAnterior',$configuracion,0,3,FALSE,'periodoAnterior',100);

                                            echo $mi_cuadro;

                                    ?>
                                </td>
                        </tr>
                        <tr>
                                <td class="sigma derecha">
                                        Periodo acad&eacute;mico nuevo:
                                </td>
                                <td class="sigma">
                                        <?
                                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"periodoParaReporte",$valor);
                                            $resultadoPerNuevo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                                           for ($i=0; $i<2;$i++)
                                            {
                                                    $registro1[$i][0]=$resultadoPerNuevo[$i][0];
                                                    $registro1[$i][1]=UTF8_DECODE($resultadoPerNuevo[$i][1]);
                                            }
                                            $mi_cuadro=$html->cuadro_lista($registro1,'periodoNuevo',$configuracion,0,3,FALSE,'periodoNuevo',100);

                                            echo $mi_cuadro;
                                        ?>
                                        
                                </td>
                        </tr>
                        <tr>
                                <td class="sigma derecha">
                                        Proyecto Curricular:
                                </td>
                                <td class="sigma">

                                    <?
                                          $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaProyectos",$valor);
                                          $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                          //$cuentaRegistros=count($registrocarreras);
                                          include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
                                            $html=new html();

                                            $configuracion["ajax_function"]="xajax_nombreCurso";
                                            $configuracion["ajax_control"]="proyecto";
                                            for ($i=0; $i<count($resultado);$i++)
                                            {
                                                    $registro1[$i][0]=$resultado[$i][0];
                                                    $registro1[$i][1]=UTF8_DECODE($resultado[$i][1]);
                                            }
                                            $mi_cuadro=$html->cuadro_lista($registro1,'proyecto',$configuracion,0,3,FALSE,"proyecto",100);

                                            echo $mi_cuadro;

                                    ?>
                                </td>
                        </tr>
                        <tr>
                            <td class="sigma centrar" colspan="2">

                                <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                                <input type='hidden' name='opcion' value='seleccionar'>
                                <input value="Seleccionar Periodo" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
                            </td>
                        </tr>
                    </table>
                    </form>
					
					
					
				    </div>
				    <div align="center" id="campo_reporte" style="display:none">
				    <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
					    <table class="sigma centrar" width="80%" border="0">
							<tr>
								<td class="sigma centrar" colspan="2">
									<font size="2">Seleccione el peridodo acad&eacute;mico y el Proyecto Curricular.</font><br><br>
								</td>
							</tr>
							<tr>
								<td class="sigma derecha">
									Periodo acad&eacute;mico :
								</td>
								<td class="sigma centrar">
								<?
									$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"periodoParaReporte",$valor);
									$resultadoPerRep=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
									$html=new html();
									
									$configuracion["ajax_function"]="xajax_nombreCurso";
									$configuracion["ajax_control"]="periodo";
									for ($i=0; $i<count($resultadoPerRep);$i++)
									{
										$registro2[$i][0]=$resultadoPerRep[$i][0];
										$registro2[$i][1]=UTF8_DECODE($resultadoPerRep[$i][1]);
									}
									$mi_cuadro=$html->cuadro_lista($registro2,'periodo',$configuracion,0,3,FALSE,$tab++,"periodo",100);
									
									echo $mi_cuadro;
													
								?>
								</td>  
							</tr>
							<tr>
								<td class="sigma derecha">
									Proyecto Curricular:
								</td>  
								<td class="sigma centrar">
								    
								    <?
									  $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaProyectos",$valor);
									  $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
									  $cuentaRegistros=count($registrocarreras);  
									  include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
									    $html=new html();
									    
									    $configuracion["ajax_function"]="xajax_nombreCurso";
									    $configuracion["ajax_control"]="proyecto";
									    for ($i=0; $i<count($resultado);$i++)
									    {
										    $registro1[$i][0]=$resultado[$i][0];
										    $registro1[$i][1]=UTF8_DECODE($resultado[$i][1]);
									    }
									    $mi_cuadro=$html->cuadro_lista($registro1,'proyecto',$configuracion,0,3,FALSE,$tab++,"proyecto",100);
									    
									    echo $mi_cuadro;
													    
								    ?>
								</td>
							</tr>
							<tr>
							    <td class="sigma centrar" colspan="2">
								
								<input type="hidden" name="action" value="<? echo $this->formulario ?>">
								<input type='hidden' name='opcion' value='verreporte'>
								<input value="Ver reporte" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
							    </td>
							</tr>
						</table>
				    </form>
				    </div>
				</td>

			</tr>
			</table>   
		
		<?
        }
	    
	
	//Confirma el periodo académico, el proyecto curricualr seleccionados, y el formulario para realizar la copia de los horarios.
	function duplicarHorario($configuracion)
	{
		unset($valor);
		$valor[0]=$_REQUEST['proyecto'];
		$valor[1]=$_REQUEST['periodoanterior'];
		$valor[2]=$_REQUEST['periodonuevo'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carrera",$valor);
		$resultadoCarrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

                $this->encabezado($configuracion);

		?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
                        <table align="center" border="0" cellpadding="0" width="500" height="100" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                                <tr>
                                        <td style='text-align:center' align="center" colspan="2">
                                        <h4 class="bloquelateralcuerpo">COPIAR H0RARIOS</h4>
                                        <hr>
                                </td>
                        </tr>
                        </table>
			<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
                                                        <tr class="sigma">
                                                                <td width="20%">
                                                                        <br><font size="2">Se&ntilde;or Coordinador, usted ha Seleccionado la carrera <b><? echo $resultadoCarrera[0][1];?></b> para duplicar el horario del periodo acad&eacute;mico <b><? echo $valor[1];?></b> al peridodo acad&eacute;mico <b><? echo $valor[2];?></b>.</font><br>
                                                                </td>
                                                        </tr>
							<tr  class="bloquecentralencabezado">
                                                                <td colspan="3" align="center">
									<p><span class="texto_negrita"> Haga click en "Continuar" para duplicar el horario y el curso, en caso contrario haga click en "Cancelar".</span></p>
								</td>
							</tr>
							<tr align='center'>
								<td colspan="3">
									<table class="tablaBase">
										<tr>
											
											<td align="center">
												<input type='hidden' name='proyecto' value='<? echo $valor[0] ?>'>
												<input type='hidden' name='periodonuevo' value='<? echo $valor[2] ?>'>
												<input type='hidden' name='periodoanterior' value='<? echo $valor[1] ?>'>
												<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
												<input type='hidden' name='opcion' value='duplicar'>
												<input value="Continuar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
											</td>
											<td align="center">
												<input type='hidden' name='proyecto' value='<? echo $valor[0] ?>'>
												<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
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
	
	//Verifica año y periodo académico, si no hay registros, inserta año y perido.
	function selecconarPeriodo($configuracion)
	{
		unset($valor);
		$valor[0]=$_REQUEST['proyecto'];
		$valor[1]=$_REQUEST['periodoAnterior'];
		$valor[2]=$_REQUEST['periodoNuevo'];
		$periodoAnterior=explode('-',$valor[1]);
		$valor[3]=$periodoAnterior[0];
		$valor[4]=$periodoAnterior[1];
		$periodoNuevo=explode('-',$valor[2]);
		$valor[5]=$periodoNuevo[0];
		$valor[6]=$periodoNuevo[1];

		//echo "mmm".$_REQUEST['periodoNuevo']."<br>";
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carrera",$valor);
		$resultadoCarrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaRegistro",$valor);
		$resultadoVerifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if(is_array($resultadoVerifica))
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"borrarPeriodo",$valor);
			$resultadoborrar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
    
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="Para la carrera: ".$resultadoCarrera[0][1]. ", ya existe un registro con el peridodo acad&eacute;mico ".$valor[2].". Cont&aacute;ctese con el administrador del sistema .<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"insertarAnioPer",$valor);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			
			if($resultado==TRUE)
			{
				$this->redireccionarInscripcion($configuracion,"duplicarHorario",$valor);
			}
			else
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena="El registro no se pudo guardar con el a&ntilde;o y el periodo acad&eacute;mico seleccionados, por favor intentelo nuevamente o cont&aacute;ctese con el administrador del sistema.<br>";

				$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
				alerta::sin_registro($configuracion,$cadena);
			}
		}
	}

	//Ejecuta el copiado de los horarios.
	function ejecutarDuplicarHorario($configuracion)
	{
		unset($valor);
		$valor[0]=$_REQUEST['proyecto'];
		$valor[1]=$_REQUEST['periodonuevo'];
		$valor[2]=$_REQUEST['periodoanterior'];
		$copiaHorario= "BEGIN pra_copiahorario(".$valor[0]."); END; ";
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $copiaHorario, "copiaHorario");

		if($resultado==TRUE)
		{
			$this->redireccionarInscripcion($configuracion,"reportes",$valor);
		}
	}
	
	//Diercciona a la función ver reportes.
	function direccionaraVerReportes($configuracion)
	{
		unset($valor);
		$valor[0]=$_REQUEST['proyecto'];
		$valor[1]=$_REQUEST['periodo'];
		
		$this->redireccionarInscripcion($configuracion,"reportes",$valor);
	}
  
	//Muestra el resumen de los horarios y cusrsos copiados.
	function verReportes($configuracion)
	{
                $this->encabezado($configuracion);
		unset($valor);
		$valor[0]=$_REQUEST['proyecto'];
		$valor[1]=$_REQUEST['periodonuevo'];
		$valor[4]=$_REQUEST['periodoanterior'];
		$periodoNuevo=explode('-',$valor[1]);
		$valor[2]=$periodoNuevo[0];
		$valor[3]=$periodoNuevo[1];
				
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"resumenHorarioCurso",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuenta=count($resultado);
		if(!is_array($resultado))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="No existen horarios ni cursos para copiar del periodo académico ".$valor[4]."";
			$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
			alerta::sin_registro($configuracion,$cadena);	
		}
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
			$total=count($resultado);			
			setlocale(LC_MONETARY, 'en_US');
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$cripto=new encriptar();
			echo "<center><table><tr><td class='cuadro_plano centrar'>
			<a href='";
			$variable="pagina=adminReportesExcelCoordinador";
			$variable.="&opcion=horarios";
			$variable.="&no_pagina=true";
			$variable.="&proyecto=".$valor[0]."";
			$variable.="&periodonuevo=".$valor[1]."";
			$variable=$cripto->codificar_url($variable,$configuracion);
			echo $indice.$variable."'";
			echo "target='_blank'";
			echo "title='Generar reporte en Excel'>";
			?>
			<img width="30" height="30" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/excel.jpg" alt="Modificar registro" title="Modificar objetos relacionados" border="0" />
			<br>
			<?
			echo "Generar reporte en hoja de c&aacute;lculo";
			echo "</a></td></tr>";
			echo "</table></center>";
			?>
			<table class="sigma_borde centrar" width="100%">
				<caption class="sigma centrar">
					RES&Uacute;MEN HORARIOS Y CURSOS
				</caption>
				
				<tr class="cuadro_azul">
					<td class="cuadro_plano centrar">
						A&ntilde;o
					</td>
					<td class="cuadro_plano centrar">
						Periodo
					</td>
					<td class="cuadro_plano centrar">
						Carrera
					</td>
					<td class="cuadro_plano centrar">
						C&oacute;digo asignatura
					</td>
					<td class="cuadro_plano centrar">
						Asignatura
					</td>
					<td class="cuadro_plano centrar">
						Grupo
					</td>
					<td class="cuadro_plano centrar">
						Inscritos
					</td>
					<td class="cuadro_plano centrar">
						Lunes
					</td>
					<td class="cuadro_plano centrar">
						Martes
					</td>
					<td class="cuadro_plano centrar">
						Mi&eacute;rcoles
					</td>
					<td class="cuadro_plano centrar">
						Jueves
					</td>
					<td class="cuadro_plano centrar">
						Viernes
					</td>
					<td class="cuadro_plano centrar">
						S&aacute;bado
					</td>
					<td class="cuadro_plano centrar">
						Domingo
					</td>
					<td class="cuadro_plano centrar">
						Semana
					</td>  
				</tr>
				<?
				for($i=0;$i<=$cuenta-1;$i++)
				{
				?>
					<tr class="cuadro_color">
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][0];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][1];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][2];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][3];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][4];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][5];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][6];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][7];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][8];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][9];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][10];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][11];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][12];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][13];?>
						</td>
						<td class="cuadro_plano centrar">
							<? echo $resultado[$i][14];?>
						</td>
						
					</tr>
				<?
				}
				?>
			</table>
			<?
		}
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
	
	//Valida que la fechas estén habilitadas para el registro de activides del plan docente.
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
		$valor[0]=$usuario;
								
		$confec = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual";
		@$rows=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confec, "busqueda");
		$valor[9] =$rows[0][0];
		$valor[10]=$_REQUEST['nivel'];
						
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$qryFechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "validaFechas",$valor);
		@$calendario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryFechas, "busqueda");
		
		//echo $qryFechas;
			if(!is_array($calendario))
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
				$total=count($resultado);
					
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
										<p align="center"><font color="red"><b>Las fechas para digitar los PLANES DE TRABAJO DOCENTES para el periodo acad&eacute;mico '.$ano.'-'.$per.', est&aacute;n cerradas, solo podr&aacute; ';
										 echo "<a href='";
										$variable="pagina=registro_plan_trabajo";
										$variable.="&opcion=reportes";
										$variable.="&nivel=".$valor[10];
										//$variable.="&no_pagina=true";
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para imprimir el reporte'><b>IMPRIMIR</b></a>";
										echo ' el reporte.</b></font></p>
										<p align="justify">&nbsp;</p>
									</fieldset>
									NOTA: Para imprimir el reporte de notas, haga Click en '; 
									echo "<a href='";
										$variable="pagina=registro_plan_trabajo";
										$variable.="&opcion=reportes";
										$variable.="&nivel=".$valor[10];
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para imprimir el reporte'><b>IMPRIMIR</b></a>";
									echo '</td>
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
				$variable="pagina=adminGestionHorarios";
				$variable.="&opcion=mensajes";
				$variable.="&mensaje=".$valor[9];
				$variable.="&valor=".$valor[1];
				$variable.="&clave=".$valor[2];
				$variable.="&nivel=".$valor[10];
				break;
			case "formgrado":
				$variable="pagina=adminGestionHorarios";
				$variable.="&proyecto=".$valor[0];
				break;
			case "duplicarHorario":
				$variable="pagina=adminGestionHorarios";
				$variable.="&opcion=duplicarHorario";
				$variable.="&proyecto=".$valor[0];
				$variable.="&periodoanterior=".$valor[1];
				$variable.="&periodonuevo=".$valor[2];
				break;
			case "registroExitoso":
				$variable="pagina=adminGestionHorarios";
				$variable.="&opcion=gestioHorarios";
				$variable.="&nivel=".$valor[10];
				break;
			case "reportes":
				$variable="pagina=adminGestionHorarios";
				$variable.="&opcion=verReporte";
				$variable.="&proyecto=".$valor[0];
				$variable.="&periodonuevo=".$valor[1];
				$variable.="&periodoanterior=".$valor[2];
				break;
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

