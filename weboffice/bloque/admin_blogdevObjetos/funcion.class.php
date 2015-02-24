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

class funciones_blogdevObjetos extends funcionGeneral
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
		$this->accesoOracle=$this->conectarDB($configuracion,"bitacora");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="admin_blogdevObjetos";
		$this->verificar="control_vacio(".$this->formulario.",'tip_obj')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'obj_nom')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'descripcion')";
		//$this->verificar.="&& longitud_cadena(".$this->formulario.",'fecha',3)";
		//$this->verificar.="&& verificar_correo(".$this->formulario.",'descripcion')";
		
	}
	
	//Rescata los valores enviados desde el formulario para guardarlos en la base de datos.
	function guardarRegistro ($configuracion, $accesoOracle,$acceso_db)
	{
	$unUsuario=$this->verificarUsuario();
		if(is_array($unUsuario))
		{
			
			//Valores a ingresar
			$valor[0]=$_REQUEST['id_usuario'];
			$valor[1]=$_REQUEST['nombre'];
			$valor[2]=$_REQUEST['apellido'];
			$valor[3]=$_REQUEST['tip_obj'];
			$valor[4]=$_REQUEST['obj_nom'];
			$valor[5]=$_REQUEST['consecutivo'];
			$valor[6]=$_REQUEST['accion'];
			$valor[7]=$_REQUEST['descripcion'];
						
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertarRegistro",$valor);
			$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
				
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertarBitacora",$valor);
			$resultado2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
				
			if(isset($resultado1))
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
	
	//Rescata los valores enviados desde formulario de edición, y modifica los valores en la base de datos.
	function guardarEdicion ($configuracion, $accesoOracle,$acceso_db)
	{
	$unUsuario=$this->verificarUsuario();
		if(is_array($unUsuario))
		{
			
			//Valores a ingresar
			unset($valor);
			$valor[0]=$_REQUEST['editarObjetos'];
			$valor[1]=$_REQUEST['tip_objm'];
			$valor[2]=$_REQUEST['obj_nomm'];
			//echo $valor[0];		
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"editarObjetos",$valor);
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
	
	//Administración del formulario de creación de objetos
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
									<p><span class="texto_negrita">ADMINISTRACI&Oacute;N DEL FORMULARIO OBJETOS</span></p>
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
									<font color="red">*</font>Tipo de objetos
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
								$busqueda.="tio_cod, ";
								$busqueda.="tio_nom ";
								$busqueda.="FROM ";
								$busqueda.="tipobj ";
								$busqueda.="ORDER BY tio_cod";
								$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
									
								$mi_cuadro=$html->cuadro_lista($resultado,'tip_obj',$configuracion,1,0,FALSE,$tab++,"tip_obj");
											
								echo $mi_cuadro;
								
								/*$mi_cuadro=$html->cuadro_lista($busqueda,"aplicacion",$configuracion,170,2,FALSE,$tab++,"aplicacion");
								echo $mi_cuadro;*/
								
								?></td>
							</tr>
							<tr>
								<td>
									<font color="red">*</font>Nombre
								</td>
								<td>
								<input maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="obj_nom"><br>
								</td>
							</tr>							
							<tr>
							<td>
								<font color="red">*</font>Descripci&oacute;n
							</td>
							<td colspan="2">
								<textarea id='descripcion' name='descripcion' cols='50' rows='2' tabindex='<? echo $tab++ ?>' ></textarea>
							</td>
						</tr>
							<tr align='center'>
								<td colspan="2">
									<table class="tablaBase">
										<tr>
											<td align="center">
												<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
												<input type='hidden' name='id_usuario' value='<? echo $id_usuario ?>'>
												<input type='hidden' name='consecutivo' value='<? echo $consecutivo ?>'>
												<input type='hidden' name='accion' value='C'>
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
	
   	//Función que muestra en pantalla un mensaje, si se guardó exitosamente el registro, muestra los datos del registro guardado en la base de datos.
   	function RegistroExitoso($configuracion,$registro, $total, $opcion="",$variable)
	{
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultarRegistro",$variable);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
						
		if(is_array($resultado))
		{
			echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
							<td colspan='4' align='center'>
								<p><span class='texto_negrita'>ADMINISTRACI&Oacute;N DEL FORMULARIO OBJETOS</span></p>
							</td>
					</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='4'>
								<p>El registro se guard&oacute; exitosamente con los siguientes datos:</p>
							</td>
					</tr>
					<tr class='cuadro_color'>
						<td align='center'>
							C&oacute;digo
						</td>
						<td align='center'>
							Tipo objeto
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
							".$resultado[0][5]."
						</td>
						<td align='center'>
							".$resultado[0][3]."
						</td>
					</tr>	
				 </table>";
		}
		else
		{
			echo "Imposible mostrar los datos de registro";
		
		}
	}
	
	//Administra el formulario de edición de ombjetos, y rescata los datos del objeto seleccionado.
	function registroEditado($configuracion,$registro, $total, $opcion="",$variable)
	{
		$valor[1]=$_REQUEST['objeto'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaObjetosEditados",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
						
		if(is_array($resultado))
		{
			echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
							<td colspan='4' align='center'>
								<p><span class='texto_negrita'>ADMINISTRACI&Oacute;N DEL FORMULARIO OBJETOS</span></p>
							</td>
					</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='4'>
								<p>El registro se modific&oacute; exitosamente con los siguientes datos:</p>
							</td>
					</tr>
					<tr class='cuadro_color'>
						<td align='center'>
							C&oacute;digo
						</td>
						<td align='center'>
							Tipo objeto
						</td>
						<td align='center'>
							Nombre
						</td>
					</tr>
					<tr>
						<td align='center'>
							".$resultado[0][0]."
						</td>
						<td align='center'>
							".$resultado[0][4]."
						</td>
						<td align='center'>
							".$resultado[0][2]."
						</td>
					</tr>	
				 </table>";
		}
		else
		{
			echo "Imposible mostrar los datos de registro";
		
		}
	}
	
	//Muestra los datos del registro que se guardó en la base de datos.
	function mostrarRegistro($configuracion,$registro, $total, $opcion="",$valor)
	{
		switch($_REQUEST["accion"])
		{
			case "listaCompleta":
				
				//Paginacion
				//Obtener el total de registros
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "totalObjetos","");
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
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertadosObjetos",$variable);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		$total=count($resultado);
			
		setlocale(LC_MONETARY, 'en_US');
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		$menu=new navegacion();
		$variableNavegacion["pagina"]="blog_objeto";
		$variableNavegacion["opcion"]="mostrar";	
		$variableNavegacion["accion"]="listaCompleta";
		$variableNavegacion["carrera"]=$registro[0][2];
		
		if($this->totalPaginas>1)
		{
			$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
		}
			
			echo "<table class='formulario' align='center'>
				 	
					<tr  class='bloquecentralencabezado'>
							<td colspan='4' align='center'>
								<p><span class='texto_negrita'>OBJETOS </span></p>
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
							Nombre bjeto
						</td>
						<td class='cuadro_plano centrar''>
							Modificar
						</td>
					</tr>";
					
					for($i=0;$i<$total;$i++)
					{	
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][4]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][2]."</td>";
						
						echo "<td class='cuadro_plano centrar'>
						<a href='";		
						$variable="pagina=blog_objeto";
						$variable.="&opcion=editarObjetos";
						//$variable.="&no_pagina=true";
						$variable.="&objeto=".$resultado[$i][0]."";
						$variable=$cripto->codificar_url($variable,$configuracion);
						echo $indice.$variable."'";
						echo $indice.$variable."'";
						echo "title='Consultar objetos relacionados'>";
							?>
							<img width="20" height="20" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/editar.png" alt="Modificar registro" title="Modificar objetos" border="0" />
							<?
						echo "</a></td>";
					}
			echo "</table>";
			if($this->totalPaginas>1)
			{
				$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
			}
		
		
	}
	
	
	function modificarObjetos($configuracion,$registro, $total, $opcion="",$variable)
	{
	$registroUsuario=$this->verificarUsuario();
	$contador=0;	
	$tab=0;
	$valor[1]=$_REQUEST['objeto'];
	$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaObjetos",$valor);
	$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");	
		if(is_array($resultado))
		{
			$objetocod=$resultado[0][0];
			$objtiocod=$resultado[0][1];
			$objnombre=$resultado[0][2];
						
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
										<p><span class="texto_negrita">MODIFICAR OBJETOS</span></p>
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
										<font color="red">*</font>Tipo de objetos
									</td>
										
									<td><?
										include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
										$html=new html();
																
										$busqueda="SELECT ";
										$busqueda.="tio_cod, ";
										$busqueda.="tio_nom ";
										$busqueda.="FROM ";
										$busqueda.="tipobj ";
										$busqueda.="ORDER BY tio_cod";
										$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
											
										$mi_cuadro=$html->cuadro_lista($resultado,'tip_objm',$configuracion,$objtiocod,0,FALSE,$tab++,"tip_objm");
													
										echo $mi_cuadro;
										
									?></td>
									
								</tr>
								</tr>
									<td>
										<font color="red">*</font>Nombre
									</td>
									<td>
									<input maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="obj_nomm" value="<? echo $objnombre?>"><br>
									</td>
								</tr>
																			
								<tr align='center'>
									<td colspan="3">
										<table class="tablaBase">
											<tr>
												<td align="center">
													<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
													<input type='hidden' name='objeto' value='<? echo $valor[1] ?>'>
													<input type='hidden' name='editarObjetos' value='<? echo $valor[1] ?>'>
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
				
			case "formgrado":
				$variable="pagina=blog_objeto";
				//$variable.="&opcion=verificar";
				$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
				$variable.="&xajax_file=blogdev";
				break;
			
			case "principal":
				$variable="pagina=index";
				break;
				
			case "registroexitoso":
				$variable="pagina=blog_objeto";
				$variable.="&opcion=exito";
				break;
				
			case "registroeditado":
				$variable="pagina=blog_objeto";
				$variable.="&opcion=exitoEditarTipobj";
				$variable.="&objeto=".$valor[0];
				break;
				
			case "mostrarregistro":
				$variable="pagina=blog_objeto";
				$variable.="&opcion=mostrar";
				break;		
			
			
			
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}

}
	

?>

