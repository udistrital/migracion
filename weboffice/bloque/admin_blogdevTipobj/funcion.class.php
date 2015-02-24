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

class funciones_blogdevTipobj extends funcionGeneral
{

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
		$this->accesoOracle=$this->conectarDB($configuracion,"bitacora");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="admin_blogdevTipobj";
		$this->verificar="control_vacio(".$this->formulario.",'aplicacion')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'claseobjeto')";
		//$this->verificar.="&& longitud_cadena(".$this->formulario.",'fecha',3)";
		//$this->verificar.="&& verificar_correo(".$this->formulario.",'descripcion')";
		
	}
	
	function guardarRegistro ($configuracion, $accesoOracle,$acceso_db)
	{
	$unUsuario=$this->verificarUsuario();
		if(is_array($unUsuario))
		{
			
			//Valores a ingresar
			if(isset($_REQUEST['codigo']))
			{
				$elUsuario=$_REQUEST['codigo'];
			}
			else
			{
				$elUsuario=$_REQUEST['registro'];
			}
						
			$valor[0]=$elUsuario;
			$valor[1]=$_REQUEST['nombre'];
			$valor[2]=$_REQUEST['apellido'];
			$valor[3]=$_REQUEST['aplicacion'];
			$valor[4]=$_REQUEST['claseobjeto'];
			$valor[5]=$_REQUEST['tio_nom'];
			$valor[6]=$_REQUEST['consecutivo'];
						
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertarRegistro",$valor);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			unset($valor);
			$valor[0]=$elUsuario;
			$valor[3]=$_REQUEST['aplicacion'];
			$valor[4]=$_REQUEST['claseobjeto'];
			$valor[5]=$_REQUEST['tio_nom'];
			$valor[6]=$_REQUEST['consecutivo'];
												
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
			echo "<table align=center><tr><td><h3>IMPOSIBLE GUARDAR EL FORMULARIO</h3></td></tr></table>";	
		}
		
		
	}
	
	function guardarEdicion ($configuracion, $accesoOracle,$acceso_db)
	{
	$unUsuario=$this->verificarUsuario();
		if(is_array($unUsuario))
		{
					
			//Valores a ingresar
			unset($valor);
			$valor[0]=$_REQUEST['editarClaobj'];
			$valor[1]=$_REQUEST['cla_tipobjm'];
			//echo $valor[0];
			//echo $valor[1]."<br>";
			//echo $valor[2]."<br>";
			//echo $valor[3]."<br>";		
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"editarClaobj",$valor);
			$resultado2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			if(isset($resultado2))
			{
				$this->redireccionarInscripcion($configuracion,"registroeditado",$valor);	
			}
			else
			{
				exit;
			}
		}
		else
		{
			echo "<table align=center><tr><td><h3>IMPOSIBLE GUARDAR EL FORMULARIO</h3></td></tr></table>";	
		}
		
		
	}
	
	function nuevoRegistro($configuracion,$conexion)
	{
		$registroUsuario=$this->verificarUsuario();
		
		$contador=0;	
		$tab=0;
		
		?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
			<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
								<td class="cuadro_brown" >
									<br>
								</td>
							</tr>
						</table>
						<br>
						<table class="formulario" align="center">
							<tr  class="bloquecentralencabezado">
								<td colspan="2">
									<p><span class="texto_negrita">ADMINISTRACI&Oacute;N DEL FORMULARIO TIPO DE OBJETOS</span></p>
								</td>
							</tr>
							<tr>
								<td colspan="2" rowspan="1"><br><hr class="hr_subtitulo"></td>
							</tr>
							<tr>
								<td colspan="2">
									<table class="formulario" align="center">
										<tr>
											<td  class="centrar texto_negrita" colspan="2">
										<?
										//unset($valor);
										if($this->usuario)
										{
											$usuario=$this->usuario;
										}
										else
										{
											$usuario=$this->identificacion;
										}
										$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosUsuario",$usuario);
										$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
										if(is_array($resultado))
										{
											echo "Usuario: ".$resultado[0][1];
											$valor[0]=$resultado[0][0];
											$id_usuario=$resultado[0][0];
										}
										else
										{
											echo "Imposible mostrar los datos de registro";
										
										}
										 //echo "Usuario :".$registroUsuario[0][1]
										?>
										</td>
											<td  class="centrar texto_negrita" colspan="2">
												<?$fecha = time (); echo "Fecha: ". date ( "d/m/Y", $fecha ); ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<font color="red">*</font>Aplicaci&oacute;n
								</td>
								<td><?
								$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultarConsecutivo",$variable);
								$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
								if(is_array($resultado1))
								{
									$consecutivo= $resultado1[0][0];
									$consecutivo;
								}
								else
								{
								echo " ";
								}
								
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
								$html=new html();
														
								$busqueda="SELECT ";
								$busqueda.="apl_cod, ";
								$busqueda.="apl_nom ";
								$busqueda.="FROM ";
								$busqueda.="aplicaciones ";
								$busqueda.="ORDER BY apl_nom";
								$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
									
								$mi_cuadro=$html->cuadro_lista($resultado,'aplicacion',$configuracion,1,0,FALSE,$tab++,"aplicacion");
											
								echo $mi_cuadro;
								
								/*$mi_cuadro=$html->cuadro_lista($busqueda,"aplicacion",$configuracion,170,2,FALSE,$tab++,"aplicacion");
								echo $mi_cuadro;*/
								
								?></td>
							</tr>
							<tr>
								<td>
									<font color="red">*</font>Clase de objeto
								</td>
								<td>
									<div id="divClaobjeto"><?
											$busqueda="SELECT ";
											$busqueda.="clo_cod, ";
											$busqueda.="clo_nom ";
											$busqueda.="FROM ";
											$busqueda.="claobj";
																
											$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
																					
											$mi_cuadro=$html->cuadro_lista($resultado,'claseobjeto',$configuracion,1,2,FALSE,$tab++,"claseobjeto");
											
											echo $mi_cuadro;
											
									
									?></div>
								</td>
							</tr>
							<tr>
								<td>
									<font color="red">*</font>Nombre
								</td>
								<td>
								<input maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="tio_nom"><br>
								</td>
							</tr>							
							<tr align='center'>
								<td colspan="2">
									<table class="tablaBase">
										<tr>
											<td align="center">
												<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
												<input type='hidden' name='consecutivo' value='<? echo $consecutivo ?>'>
												<input type='hidden' name='opcion' value='nuevo'>
												<input value="Enviar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>								
											</td>
											<td align="center">
												<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="bloquecentralcuerpo">
								<td colspan="2" rowspan="1">
									Los campos marcados con <font color="red">*</font> deben ser diligenciados obligatoriamente.<br><br>
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table>
		</form>		
		
							
		<?
	}
	
   	function mostrarRegistro($configuracion,$registro, $total, $opcion="",$valor)
	{
		switch($_REQUEST["accion"])
		{
			case "listaCompleta":
				
				//Paginacion
				//Obtener el total de registros
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "totalTipobj","");
				$registro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
				
				if(is_array($registro))
				{
					$totalRegistros=$registro[0][0];
				}
				else
				{
					$totalRegistros=0;
				}
				
				//Obtener el numero de pagina
				$this->totalPaginas=ceil($totalRegistros/$configuracion['registro']);
				
				//Obtener el numero de la pagina
				if(!isset($_REQUEST["hoja"]))
				{
					$this->paginaActual=1;
				}
				else
				{
					$this->paginaActual=$_REQUEST["hoja"];
				}
					$variable[3]=$this->paginaActual;
							
					//Obtener la pagina especifica
					//$cadena_sql=$this->sql->cadena_sql($configuracion,$accesoOracle,"bloqueadoBitacora",$valor);
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertadosObjetos",$variable);
										
				break;					
		
		}
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertadosTipobj",$variable);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		$total=count($resultado);
			
		setlocale(LC_MONETARY, 'en_US');
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		$menu=new navegacion();
		$variableNavegacion["pagina"]="blog_tipo_objeto";
		$variableNavegacion["opcion"]="mostrar";	
		$variableNavegacion["accion"]="listaCompleta";
		$variableNavegacion["carrera"]=$registro[0][2];
		
		if($this->totalPaginas>1)
		{
			$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
		}
			
			echo "<table class='formulario' align='center'>
				 	
					<tr  class='bloquecentralencabezado'>
							<td colspan='5' align='center'>
								<p><span class='texto_negrita'>TIPO DE OBJETOS </span></p>
							</td>
					</tr>
					<tr class='cuadro_color'>
						<td class='cuadro_plano centrar''>
							No.
						</td>
						<td class='cuadro_plano centrar''>
							Tipo de objeto
						</td>
						<td class='cuadro_plano centrar''>
							Aplicaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Clase de objeto
						</td>
						<td class='cuadro_plano centrar''>
							Modificar
						</td>
					</tr>";
					
					for($i=0;$i<$total;$i++)
					{	
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][5]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][7]."</td>";
						echo "<td class='cuadro_plano centrar'>
						<a href='";		
						$variable="pagina=blog_tipo_objeto";
						$variable.="&opcion=editarTipobj";
						//$variable.="&no_pagina=true";
						$variable.="&objeto=".$resultado[$i][0]."";
						$variable=$cripto->codificar_url($variable,$configuracion);
						echo $indice.$variable."'";
						echo $indice.$variable."'";
						echo "title='Consultar tipo de objetos'>";
							?>
							<img width="20" height="20" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/editar.png" alt="Modificar registro" title="Modificar tipo de objetos" border="0" />
							<?
						echo "</a></td>";
					}
			echo "</table>";
			if($this->totalPaginas>1)
			{
				$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
			}
		
		
	}
   	
   	function modificarTipobj($configuracion,$registro, $total, $opcion="",$variable)
	{
	$registroUsuario=$this->verificarUsuario();
	$contador=0;	
	$tab=0;
	$valor[1]=$_REQUEST['objeto'];
	$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaTipobjetos",$valor);
	$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");	
		if(is_array($resultado))
		{
			$tipobjetocod=$resultado[0][0];
			$tipobjclocod=$resultado[0][2];
			$tipobjaplcod=$resultado[0][1];
			$tipobjnombre=$resultado[0][3];
						
			?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
				<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
					<tr>
						<td>	
							<table class="formulario" align="center">
								<tr>
									<td class="cuadro_brown" >
									</td>
								</tr>
							</table>
							<br>
							<table class="formulario" align="center">
								<tr  class="bloquecentralencabezado">
									<td colspan="3" align="center">
										<p><span class="texto_negrita">MODIFICAR TIPO DE OBJETOS</span></p>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<table class="formulario" align="center">
											<tr>
												<td  class="centrar texto_negrita" colspan="2">
												<?
												//unset($valor);
												if($this->usuario)
												{
													$usuario=$this->usuario;
												}
												else
												{
													$usuario=$this->identificacion;
												}
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosUsuario",$usuario);
												$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												if(is_array($resultado1))
												{
													echo "Usuario: ".$resultado1[0][1];
												}
												else
												{
													echo "Imposible mostrar los datos de registro";
												
												}
												//echo "Usuario :".$registroUsuario[0][1]
												?>
												</td>
												<td  class="centrar texto_negrita" colspan="2">
													<?$fecha = time (); echo "Fecha: ". date ( "d/m/Y", $fecha ); ?>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<font color="red">*</font>Aplicaci&oacute;n
									</td>
									<td><?
										include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
										$html=new html();
																
										$busqueda="SELECT ";
										$busqueda.="apl_cod, ";
										$busqueda.="apl_nom ";
										$busqueda.="FROM ";
										$busqueda.="aplicaciones ";
										$busqueda.="ORDER BY apl_cod";
										$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
											
										$mi_cuadro=$html->cuadro_lista($resultado,'apl_tipobjm',$configuracion,$tipobjaplcod,0,FALSE,$tab++,"apl_tipobjm");
													
										echo $mi_cuadro;
										
									?></td>
									
								</tr>
								<tr>
									<td>
										<font color="red">*</font>Clase de Objeto
									</td>
										
									<td><?
										include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
										$html=new html();
																
										$busqueda="SELECT ";
										$busqueda.="clo_cod, ";
										$busqueda.="clo_nom ";
										$busqueda.="FROM ";
										$busqueda.="claobj ";
										$busqueda.="ORDER BY clo_cod";
										$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
											
										$mi_cuadro=$html->cuadro_lista($resultado,'cla_tipobjm',$configuracion,$tipobjclocod,0,FALSE,$tab++,"cla_tipobjm");
													
										echo $mi_cuadro;
										
									?></td>
								</tr>
								</tr>
									<td>
										<font color="red">*</font>Nombre
									</td>
									<td>
									<input maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="tipobj_nomm" value="<? echo $tipobjnombre?>"><br>
									</td>
								</tr>
																			
								<tr align='center'>
									<td colspan="3">
										<table class="tablaBase">
											<tr>
												<td align="center">
													<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
													<input type='hidden' name='tipobj' value='<? echo $valor[1] ?>'>
													<input type='hidden' name='editarTipobj' value='<? echo $valor[1] ?>'>
													<input value="Actualizar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>								
												</td>
												<td align="center">
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
			
			</div>	
		
							
			<?		
		}
		else
		{
			echo "Imposible mostrar los datos de registro";
		}
	}
    		
	function RegistroExitoso($configuracion,$registro, $total, $opcion="",$variable)
	{
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultarRegistro",$variable);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
						
		if(is_array($resultado))
		{
			echo " ";
			echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
							<td colspan='4' align='center'>
								<p><span class='texto_negrita'>ADMINISTRACI&Oacute;N DEL FORMULARIO TIPO DE OBJETOS</span></p>
							</td>
						</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='4'>
								<p>El registro se guard&oacute; exitosamente con los siguientes datos:</p>
							</td>
						</tr>
					<tr>
						<td align='center'>
							C&oacute;digo
						</td>
						<td align='center'>
							Aplicaci&oacute;n
						</td>
						<td align='center'>
							Clase de objeto
						</td>
						<td align='center'>
							Nombre
						</td>
					</tr>
					<tr>
						<td align='center'>
							".$resultado[0][1]."
						</td>
						<td align='center'>
							".$resultado[0][8]."
						</td>
						<td align='center'>
							".$resultado[0][7]."
						</td>
						<td align='center'>
							".$resultado[0][6]."
						</td>
					</tr>	
				 </table>";
		}
		else
		{
			echo "Imposible mostrar los datos de registro";
		
		}
	}
	function registroEditado($configuracion,$registro, $total, $opcion="",$variable)
	{
		$valor[1]=$_REQUEST['editarTipobj'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaTipobjEditados",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
						
		if(is_array($resultado))
		{
			echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
							<td colspan='4' align='center'>
								<p><span class='texto_negrita'>ADMINISTRACI&Oacute;N DEL FORMULARIO TIPO DE OBJETOS</span></p>
							</td>
					</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='4'>
								<p>El registro se modific&oacute; exitosamente con los siguientes datos:</p>
							</td>
					</tr>
					<tr class='cuadro_color'>
						<td align='center'>
							No.
						</td>
						<td align='center'>
							Tipo objeto
						</td>
						<td align='center'>
							Aplicación 
						</td>
						<td align='center'>
							Clase de objeto
						</td>
						
					</tr>
					<tr>
						<td align='center'>
							".$resultado[0][0]."
						</td>
						<td align='center'>
							".$resultado[0][3]."
						</td>
						<td align='center'>
							".$resultado[0][7]."
						</td>
						<td align='center'>
							".$resultado[0][5]."
						</td>
					</tr>	
				 </table>";
		}
		else
		{
			echo "Imposible mostrar los datos de registro";
		
		}
	}
			
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
	function datosUsuario()
	{
		$registro=$this->verificarUsuario();
		if(is_array($registro))
		{
			?><table class="formulario" align="center">
						<tr  class="bloquecentralencabezado">
							<td colspan="2">
								<p><span class="texto_negrita">Datos Registrados del Estudiante</span></p>
							</td>
						</tr>
						<tr >
							<td>
								Nombre:
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][1] ?>
							</td>
						</tr>
						<tr >
							<td>
								identificaci&oacute;n:
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][0] ?>
							</td>
						</tr>
						<tr >
							<td>
								Tipo de Documento:
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][6] ?>
							</td>
						</tr>
						<tr >
							<td>
								G&eacute;nero:
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][7] ?>
							</td>
						</tr>
			</table>
			<?
		}
		else
		{
			return false;
		
		}
	
	}	
		
		
	
	function confirmarRegistro($configuracion,$accion)
	{
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "inscripcionBlogdev","");
		$registro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
				
		if(is_array($registro))
		{
			$this->htmlConfirmar($configuracion,$accion,$registro);	
		}
		else
		{
			echo "Imposible mostrar los datos de Inscripci&oacute;n";
		}
		
	}
		
	function verificarUsuario()
	{
		//Verificar existencia del usuario 
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosUsuarios",$this->identificacion);
		$unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if(is_array($unUsuario))
		{
			return $unUsuario;			
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "datosEstudiante",$this->usuario);
			$unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");	
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
				$variable="pagina=confirmacionInscripcionGrado";
				$variable.="&opcion=confirmar";
				$variable.="&identificador=".$valor;
				break;
				
			case "formgrado":
				$variable="pagina=blog_tipo_objeto";
				//$variable.="&opcion=verificar";
				$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
				$variable.="&xajax_file=blogdev";
				break;
			
			case "confirmacionCoordinador":
				$variable="pagina=confirmacionInscripcionCoordinador";
				$variable.="&opcion=confirmar";
				$variable.="&sinCodigo=1";
				$variable.="&identificador=".$valor;
				break;
				
			case "corregirUsuario":			
				$variable="pagina=registroInscripcionCorregir";
				$variable.="&opcion=corregir";
				$variable.="&identificador=".$valor;
				break;
				
			case "principal":
				$variable="pagina=index";
				break;
				
			case "registroexitoso":
				$variable="pagina=blog_tipo_objeto";
				$variable.="&opcion=exito";
				break;				
			
			case "registroeditado":
				$variable="pagina=blog_tipo_objeto";
				$variable.="&opcion=exitoEditarTipobj";
				$variable.="&editarTipobj=".$valor[0];
				break;
			
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
	
	function usuarioAntiguo($configuracion,$acceso_db)
	{
		$valor=$_REQUEST['solicitud'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "inscripcionGrado",$valor);
		$registro=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
		if(is_array($registro))
		{
		
			
			unset($valor);
			if($resultado==TRUE)
			{
				if(!isset($_REQUEST["admin"]))
				{
					enviar_correo($configuracion);
					reset($_REQUEST);
					while(list($clave,$valor)=each($_REQUEST))
					{
						unset($_REQUEST[$clave]);
							
					}
					
					$this->redireccionarInscripcion($configuracion, "indice");
					
				}
				else
				{
					$this->redireccionarInscripcion($configuracion,"administracion");
				}
			}
			else
			{
				
			}
							
							
		}
		else
		{
			echo "<h1>Error de Acceso</h1>Por favor contacte con el administrador del sistema.";				
		}
	}
}
	

?>

