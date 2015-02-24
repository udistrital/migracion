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

class funciones_blogdevClaobj extends funcionGeneral
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
		
		$this->formulario="admin_blogdevClaobj";
		$this->verificar="control_vacio(".$this->formulario.",'clo_nom')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'direccion')";
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
			$valor[3]=$_REQUEST['clo_nom'];
			$valor[4]=$_REQUEST['consecutivo'];
						
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertarRegistro",$valor);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			unset($valor);
			$valor[0]=$elUsuario;
			$valor[3]=$_REQUEST['clo_nom'];
			$valor[4]=$_REQUEST['consecutivo'];										
									
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
			$valor[1]=$_REQUEST['cla_claobjm'];
			
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
								<td colspan="2" align="center">
									<p><span class="texto_negrita">ADMINISTRACI&Oacute;N DEL FORMULARIO CLASE DE OBJETOS</span></p>
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
									<font color="red">*</font>Nombre
								</td>
								<td>
								<?$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultarConsecutivo",$variable);
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
								?>
								<input maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="clo_nom"><br>
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
	//Muestra todos los registros
   	function mostrarRegistro($configuracion,$registro, $total, $opcion="",$valor)
	{
		switch($_REQUEST["accion"])
		{
			case "listaCompleta":
				
				//Paginacion
				//Obtener el total de registros
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "totalClaobj","");
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
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertadosClaobj",$variable);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		$total=count($resultado);
			
		setlocale(LC_MONETARY, 'en_US');
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		$menu=new navegacion();
		$variableNavegacion["pagina"]="blog_clase_objetos";
		$variableNavegacion["opcion"]="mostrar";	
		$variableNavegacion["accion"]="listaCompleta";
		$variableNavegacion["carrera"]=$registro[0][2];
		
		if($this->totalPaginas>1)
		{
			$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
		}
			
			echo "<table class='formulario' align='center'>
				 	
					<tr  class='bloquecentralencabezado'>
							<td colspan='3' align='center'>
								<p><span class='texto_negrita'>CLASE DE OBJETOS </span></p>
							</td>
					</tr>
					<tr class='cuadro_color'>
						<td class='cuadro_plano centrar''>
							No.
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
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][1]."</td>";
						echo "<td class='cuadro_plano centrar'>
						<a href='";		
						$variable="pagina=blog_clase_objetos";
						$variable.="&opcion=editarClaobj";
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
	
	function RegistroExitoso($configuracion,$registro, $total, $opcion="",$variable)
	{
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultarRegistro",$variable);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
						
		if(is_array($resultado))
		{
			echo " ";
			echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
							<td colspan='2' align='center'>
								<p><span class='texto_negrita'>ADMINISTRACI&Oacute;N DEL FORMULARIO CLASE DE OBJETOS</span></p>
							</td>
						</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='2'>
								<p>El registro se guard&oacute; exitosamente con los siguientes datos:</p>
							</td>
						</tr>
					<tr>
						<td align='center'>
							C&oacute;digo
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
	
	function modificarClaobj($configuracion,$registro, $total, $opcion="",$variable)
	{
	$registroUsuario=$this->verificarUsuario();
	$contador=0;	
	$tab=0;
	$valor[1]=$_REQUEST['objeto'];
	$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaClaobjetos",$valor);
	$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");	
		if(is_array($resultado))
		{
			$claobjclocod=$resultado[0][0];
			$claobjclonom=$resultado[0][1];
									
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
										<p><span class="texto_negrita">MODIFICAR CLASE DE OBJETOS</span></p>
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
								</tr>
									<td>
										<font color="red">*</font>Clase de objeto
									</td>
									<td>
									<input maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="cla_claobjm" value="<? echo $claobjclonom?>"><br>
									</td>
								</tr>
																			
								<tr align='center'>
									<td colspan="3">
										<table class="tablaBase">
											<tr>
												<td align="center">
													<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
													<input type='hidden' name='claobj' value='<? echo $valor[1] ?>'>
													<input type='hidden' name='editarClaobj' value='<? echo $valor[1] ?>'>
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
		
	function registroEditado($configuracion,$registro, $total, $opcion="",$variable)
	{
		$valor[1]=$_REQUEST['editarTipobj'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaClaobjEditados",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
						
		if(is_array($resultado))
		{
			echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
							<td colspan='2' align='center'>
								<p><span class='texto_negrita'>ADMINISTRACI&Oacute;N DEL FORMULARIO CLASE DE OBJETOS</span></p>
							</td>
					</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='2'>
								<p>El registro se modific&oacute; exitosamente con los siguientes datos:</p>
							</td>
					</tr>
					<tr class='cuadro_color'>
						<td align='center'>
							No.
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
							".$resultado[0][1]."
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
				$variable="pagina=blog_clase_objetos";
				//$variable.="&opcion=verificar";
				$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
				$variable.="&xajax_file=blogdev";
				break;
			
			case "principal":
				$variable="pagina=index";
				break;
				
			case "registroexitoso":
				$variable="pagina=blog_clase_objetos";
				$variable.="&opcion=exito";
				break;
			
			case "registroeditado":
				$variable="pagina=blog_clase_objetos";
				$variable.="&opcion=exitoEditarClaobj";
				$variable.="&editarTipobj=".$valor[0];
				break;			
			
			
			
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
?>

