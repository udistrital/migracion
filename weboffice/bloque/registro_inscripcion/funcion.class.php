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

class funciones_registroInscripcionGrado extends funcionGeneral
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
		$this->accesoOracle=$this->conectarDB($configuracion,"estudiante");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="registro_inscripcion";
		$this->verificar="control_vacio(".$this->formulario.",'trabajo_grado')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'acta')";
		
	}
	
	//Rescata los valores del formulario para guardarlos en la base de datos.
	function guardarRegistro ($configuracion, $accesoOracle,$acceso_db)
	{		
		$unUsuario=$this->verificarUsuario($configuracion);
		if(is_array($unUsuario))
		{
			$valor[0]=$_REQUEST['codigo'];
			$verifica=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "verificaRegistro",$valor);
			@$rowVerifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $verifica, "busqueda");
			
			if(!is_array($rowVerifica))
			{
				$valor[1]=$_REQUEST['carrera'];
				$valor[2]=$_REQUEST['trabajo_grado'];
				$valor[3]=$_REQUEST['director'];
				$valor[4]=$_REQUEST['tip_trabajo'];
				$valor[7]=$_REQUEST['acta'];
												
				$consulta=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultarAnioPer",$usuario);
				@$resultConsulta=$this->ejecutarSQL($configuracion, $this->accesoOracle, $consulta, "busqueda");
	
				$valor[5]=$resultConsulta[0][0];
				$valor[6]=$resultConsulta[0][1];
										
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertarRegistro",$valor);
				@$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
				//echo "<br>->".$cadena_sql;
									
				if($resultado==TRUE)
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
				echo "<table align=center><tr><td><h3>Su c&oacute;digo presenta una inscripci&oacute;n de grado registrada actualmente en el sistema, favor contactar con la secretar&iacute;a acad&eacute;mica.</h3></td></tr></table>";
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
		$registroUsuario=$this->verificarUsuario($configuracion);
			
		$contador=0;	
		$tab=0;
		
		//unset($valor);
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		
		$valor[0]=$usuario;
		
		$verifica=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "verificaRegistro",$valor);
		@$rowVerifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $verifica, "busqueda");
		
		if(is_array($rowVerifica))
		{
			$this->redireccionarInscripcion($configuracion,"registroexitoso");
			exit;
		}
		else
		{
				
			$calendario=$this->validaCalendario($configuracion);
							
			?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
				<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
					<tr>
						<td>	
							<table class="formulario" align="center">
								<tr>
									<td class="cuadro_brown" >
									<br>
										<ul>
										<li>Para efectos de evitar errores ortogr&aacute;ficos al momento de generar el Diploma y Acta de 
										Grado es necesario que actualice la informaci&oacute;n, para esto debe ir al men&uacute;, en "Datos Personales"
										dar click en "Actualizar Datos" y diligenciar el formulario con los datos a actualizar o corregir.</li>
										<li>Se&ntilde;or estudiante, si no tiene acta de sustentaci&oacute;n, por favor no realice la inscripci&oacute;n.</li>
										<li>En caso de que necesite corregir los nombres, apellidos o n&uacute;mero documento de indentidad, debe acercarse a su Proyecto Curricular
										con una fotocopia de su documento para que le realicen la respectiva correcci&oacute;n.</li>
										<li>Recuerde revisar que la informaci&oacute;n est&eacute; actualizada, pues es la que se usar&aacute; para contactarlo en caso de alg&uacute;n inconveniente o futuros eventos.</li>
										</ul>
									</td>
								</tr>
							</table>
							<br>
							<table class="formulario" align="center">
								<tr  class="bloquecentralencabezado">
									<td colspan="3" align="center">
										<p><span class="texto_negrita">Formulario de Inscripci&oacute;n para Grado</span></p>
									</td>
								</tr>
								<tr>
									<td colspan="3" rowspan="1"><b><br>Datos de suscripci&oacute;n<hr class="hr_subtitulo"></b></td>
								</tr>
								
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
									echo '<tr>
										<td>
											Nombre:
										</td>
										<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][1].'
										</td>
									</tr>
									<tr>
										<td>
											Identificaci&oacute;n:
										</td>
										<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][2].'
										</td>
									</tr>
									<tr>
										<td>
											Tipo de identificaci&oacute;n:
										</td>
										<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][3].'
										</td>
									</tr>
									<tr>
										<td>
											Lugar de expdedici&oacute;n:
										</td>
										<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][9].'';
											
											include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
											$total=count($resultado);			
											setlocale(LC_MONETARY, 'en_US');
											$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
											$cripto=new encriptar();
											
											echo '<a href="';
											$variable="pagina=registro_inscripcionGrado";
											$variable.="&opcion=edicionLugExp";
											//$variable.="&no_pagina=true";
											$variable.="&lugexp=".$resultado[0][12]."";
											$variable=$cripto->codificar_url($variable,$configuracion);
											echo $indice.$variable.'"';
											//echo 'target="_blank"';
											echo 'title="Editar el lugar de expdedici&oacute;n de su documento de identidad">
											<br><font color="red">(Editar)</font>
											</a>';
										echo '</td>
									</tr>';
                                                                        if($resultado[0][15]=='M'){ echo '                                                                     
                                                                            <tr>                                                                        									<tr>
                                                                            <td>
                                                                                    Distrito Militar:
                                                                            </td>
                                                                            <td class="izquierda texto_negrita" colspan="2">
                                                                                    '.$resultado[0][14].'
                                                                            </td>
									</tr>
									<tr>
										<td>
											Libreta Militar:
										</td>
										<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][13].'';
											
											include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
											$total=count($resultado);			
											setlocale(LC_MONETARY, 'en_US');
											$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
											$cripto=new encriptar();
											
											echo '<a href="';
											$variable="pagina=registro_inscripcionGrado";
											$variable.="&opcion=edicionLibMilitar";
											//$variable.="&no_pagina=true";
											$variable.="&lugexp=".$resultado[0][13]."";
											$variable=$cripto->codificar_url($variable,$configuracion);
											echo $indice.$variable.'"';
											//echo 'target="_blank"';
											echo 'title="Editar el lugar de expdedici&oacute;n de su documento de identidad">
											<br><font color="red">(Editar)</font>
											</a>';
										echo '</td>
									</tr>
                                                                       ';};echo'
									<tr>
										<td>
											Carrera:
										</td>
										<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][5].'
										</td>
									</tr>
									<tr>
										<td colspan="3" rowspan="1"><br><b>Datos de contacto<hr class="hr_subtitulo"></b></td>
									</tr>
									<tr>
										<td>
											Direcci&oacute;n:
										</td>
										<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][6].'
										</td>
									</tr>
									<tr>
										<td>
											Ciudad de residencia:
										</td>
										<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][10].'
										</td>
									</tr>
									<tr>
										<td>
											Tel&eacute;fono:
										</td>
										<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][7].'
										</td>
									</tr>
									<tr>
										<td>
											Tel&eacute;fono celular:
										</td>
										<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][11].'
										</td>
									</tr>
									<tr>
										<td>
											Correo electr&oacute;nico:
										</td>
										<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][8].'
										</td>
									</tr>';
									
									$valor[0]=$resultado[0][0];
									$codigo=$resultado[0][0];
									$carrera=$resultado[0][4];
								}
								else
								{
									echo "Imposible mostrar los datos de registro";
								
								}
									//echo "Usuario :".$registroUsuario[0][1]
								?>
																			
								<tr>
									<td colspan="3" rowspan="1"><br><b>
										Información trabajo de grado / pasant&iacute;a<hr class="hr_subtitulo"></b>
									</td>
								</tr>
								<tr>
									<td>
										<font color="red">*</font>Trabajo de grado:
									</td>
									<td colspan="2">
										<textarea id='trabajo_grado' name='trabajo_grado' cols='50' rows='2' tabindex='<? echo $tab++ ?>' ></textarea>
										<font color="red"><br>Si no tiene trabajo de grado, escriba N/A.</font>
									</td>
								</tr>
								<tr>
									<td>
										<font color="red">*</font>Director:
									</td>
									<td colspan="2">
										<?
										include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
										$html=new html();
																
										$busqueda="SELECT DISTINCT ";
										$busqueda.="dir_nro_iden, ";
										$busqueda.="dir_nombre ||' '|| dir_apellido as nombre ";
										$busqueda.="FROM ";
										$busqueda.="acdirectorgrado ";
										$busqueda.="WHERE ";
										$busqueda.="dir_estado='A' ";
										$busqueda.="ORDER BY nombre";
										
										$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
                                                                                foreach ($resultado as $key => $value) {
                                                                                    $lista_director[$key][0]=$value[0];
                                                                                    $lista_director[$key][1]=$value[1];
                                                                                }
										$mi_cuadro=$html->cuadro_lista($lista_director,'director',$configuracion,1,0,FALSE,$tab++,"director",300);
													
										echo $mi_cuadro;
										echo "<font color='red'><br>En caso de que no tenga director de grado, seleccione el Coordinador de su Proyecto Curricular.</font>";
										?>
									</td>
								</tr>
								<tr>
									<td>
										<font color="red">*</font>Tipo de trabajo:
									</td>
									<td colspan="2">
										<select name="tip_trabajo">
										<option value='Grado'>Grado</option>
										<option value='Pasantia'>Pasant&iacute;a</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<font color="red">*</font>No. de acta de sustentaci&oacute;n:
									</td>
									<td>
										<input type='text' name='acta' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' >
										<font color='red'><br>Si no tiene acta de sustentaci&oacute;n,  escriba N/A.</font> 
									</td>
								</tr>
								<tr align='center'>
									<td colspan="3">
										<table class="tablaBase">
											<tr>
												<td align="center">
													<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
													<input type='hidden' name='codigo' value='<? echo $codigo ?>'>
													<input type='hidden' name='carrera' value='<? echo $carrera ?>'>
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
									<td colspan="3" rowspan="1">
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
	}
	
	//Muestra el formulario con toda la información del estudiante y de la inscripción a grado..	
	function mostrarRegistro($configuracion,$registro, $total, $opcion="",$valor)
	{
		//$calendario=$this->validaCalendario();
		$registroUsuario=$this->verificarUsuario($configuracion);
		
		$contador=0;	
		$tab=0;
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");	
		?>
		<script language="Javascript">
		function imprSelec(nombre)
		{
		var ficha = document.getElementById(nombre);
		var ventimp = window.open(' ', 'popimpr');
		ventimp.document.write( ficha.innerHTML );
		ventimp.document.close();
		ventimp.print( );
		ventimp.close();
		}
		</script>
		<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						
					</table>
					<br>
					<DIV ID="seleccion">
						<table class="formulario" align="center">
							<tr  class="bloquecentralencabezado">
								<td colspan="3" align="center">
									<p><span class="texto_negrita">Inscripci&oacute;n para Grado</span></p>
								</td>
							</tr>
							<tr>
								<td colspan="3" rowspan="1"><br>Datos de suscripci&oacute;n<hr class="hr_subtitulo"></td>
							</tr>
							
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
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "muestraInscripcion",$usuario);
							@$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
								
							if(is_array($resultado))
							{
								echo '<tr>
									<td>
										Nombre:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][1].'
									</td>
								</tr>
								<tr>
									<td>
										C&oacute;digo:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][0].'
									</td>
								</tr>
								<tr>
									<td>
										Identificaci&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][2].'
									</td>
								</tr>
								<tr>
									<td>
										Tipo de identificaci&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][3].'
									</td>
								</tr>
								<tr>
									<td>
										Lugar de expedici&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
											'.$resultado[0][9].'';
											
											include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
											$total=count($resultado);			
											setlocale(LC_MONETARY, 'en_US');
											$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
											$cripto=new encriptar();
											
											echo '<a href="';
											$variable="pagina=registro_inscripcionGrado";
											$variable.="&opcion=edicionLugExp";
											//$variable.="&no_pagina=true";
											$variable.="&lugexp=".$resultado[0][12]."";
											$variable=$cripto->codificar_url($variable,$configuracion);
											echo $indice.$variable.'"';
											//echo 'target="_blank"';
											echo 'title="Editar el lugar de expdedici&oacute;n de su documento de identidad">
											<br><font color="red">(Editar)</font>
											</a>';
										echo '</td>
								</tr>
								<tr>
									<td>
										Libreta Militar:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][18].'
									</td>
								</tr>
								<tr>
									<td>
										Carrera:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][5].'
									</td>
								</tr>
								<tr>
									<td colspan="3" rowspan="1"><br>Datos de contacto<hr class="hr_subtitulo"></td>
								</tr>
								<tr>
									<td>
										Direcci&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][6].'
									</td>
								</tr>
								<tr>
									<td>
										Ciudad de residencia:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][10].'
									</td>
								</tr>
								<tr>
									<td>
										Tel&eacute;fono:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][7].'
									</td>
								</tr>
								<tr>
									<td>
										Tel&eacute;fono celular:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][11].'
									</td>
								</tr>
								<tr>
									<td>
										Correo electr&oacute;nico:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][8].'
									</td>
								</tr>
								<tr>
									<td colspan="3" rowspan="1"><br>
										Informacion trabajo de grado / pasant&iacute;a<hr class="hr_subtitulo">
									</td>
								</tr>
								<tr>
									<td>
										Trabajo de grado:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][12].'
									</td>
								</tr>
								<tr>
									<td>
										Director:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][13].'
									</td>
								</tr>
								<tr>
									<td>
										Tipo de trabajo:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][14].'
									</td>
								</tr>
								<tr>
									<td>
										No. de acta de sustentaci&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][17].'
									</td>
								</tr>
								<tr>
									<td>
										Fecha de inscripci&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][16].'
									</td>
								</tr>';
								
								$valor[0]=$resultado[0][0];
								$codigo=$resultado[0][0];
								$carrera=$resultado[0][4];
							}
							else
							{
								echo "Imposible mostrar los datos de registro";
							
							}
								//echo "Usuario :".$registroUsuario[0][1]
							?>
							<tr align='center'>
								<td colspan="3">
									
								</td>
							</tr>
						</table>
					</DIV>
			</tr>
			<tr>
				</td>
				<td class="tabla_alerta">
				<a href="javascript:imprSelec('seleccion')" >
				<center><img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/impresora.gif" border="0"/><br>
				Imprimir la inscripci&oacute;n a grado.</center><br><br>
				</a>
				</td>
			</tr>
		</table>
		<?
	}
	
	//Funcion que permite editar el lugar de expedción del documento de identidad.
	function editarLugExp($configuracion,$registro, $total, $opcion="",$valor)
	{
		$registroUsuario=$this->verificarUsuario($configuracion);
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
								<p><span class="texto_negrita">Editar el lugar de expedici&oacute;n del documento de identidad.</span></p>
							</td>
						</tr>
						<tr >
							<td>
								Lugar actual:
							</td>
							<td class="texto_negrita">
								<? echo $resultado[0][9] ?>
							</td>
						</tr>
						<tr>
							<td>
								Lugar nuevo:
							</td>
							<td class="texto_negrita">
								<?
									$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "municipios",$usuario);
									$datosmun=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
									$total=count($datosmun);
									echo '<select name="lugar">';
									for($i=0;$i<$total;$i++)
									{
										
										echo '<option value="'.$datosmun[$i][0].'">'.$datosmun[$i][1].' ('.$datosmun[$i][3].')</option>';
									}
									echo '</select>';
								?>
								
							</td>
						</tr>
						<tr align='center'>
							<td colspan="3">
								<table class="tablaBase">
									<tr>
										<td align="center">
											<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
											<input type='hidden' name='usuario' value='<? echo $usuario ?>'>
											<input type='hidden' name='editar' value='editar'>
											<input value="Modificar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>								
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
					
	<?
	}
	//Funcion que permite editar el lugar de expedción del documento de identidad.
	function editarLibMilitar($configuracion,$registro, $total, $opcion="",$valor)
	{
		$registroUsuario=$this->verificarUsuario($configuracion);
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
								<p><span class="texto_negrita">Editar el número de libreta militar.</span></p>
							</td>
						</tr>
						<tr >
							<td>
								Número actual:
							</td>
							<td class="texto_negrita">
								<? echo $resultado[0][13] ?>
							</td>
						</tr>
						<tr>
							<td>
								Número nuevo:
							</td>
							<td class="texto_negrita">
                                                            
                                                        <input type='text' name='libreta' size='40<input' maxlength='50' tabindex='<? echo $tab++ ?>' >
								
							</td>
						</tr>
						<tr>
							<td>
								Distrito Militar:
							</td>
							<td class="texto_negrita">
								
                                                                    <input type='text' name='distrito' size='40<input' maxlength='50' tabindex='<? echo $tab++ ?>' >								
								
							</td>
						</tr>
						<tr align='center'>
							<td colspan="3">
								<table class="tablaBase">
									<tr>
										<td align="center">
											<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
											<input type='hidden' name='usuario' value='<? echo $usuario ?>'>
											<input type='hidden' name='editarLibMilitar' value='editarLibMilitar'>
											<input value="Modificar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>								
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
					
	<?
	}
	
	//Funcion que guarda la edición de lugar de expedición de documento de identidad
	function guardarEdicion($configuracion, $accesoOracle,$acceso_db)
	{
		$unUsuario=$this->verificarUsuario($configuracion);               
		if(is_array($unUsuario))
		{
			
			if(isset($_REQUEST['codigo']))
			{
				$elUsuario=$_REQUEST['codigo'];
				
			}
			else
			{
				$elUsuario=$_REQUEST['registro'];
				
			}
			unset($valor);
			$valor[0]=$_REQUEST['lugar'];
			$valor[1]=$_REQUEST['usuario'];
			
			//echo $valor[0]."<br>";
			//echo $valor[1]."<br>";exit;
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"editarMunicipioExp",$valor); 
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
			echo "<table align=center><tr><td><h3>IMPOSIBLEs GUARDAR EL FORMULARIO</h3></td></tr></table>";	
		}
	}
        
        
	//Funcion que guarda la edición de lugar de expedición de documento de identidad
	function guardarEdicionLibreta($configuracion, $accesoOracle,$acceso_db)
	{ 
		$unUsuario=$this->verificarUsuario($configuracion);       
		if(is_array($unUsuario))
		{
			    
			if(isset($_REQUEST['libreta']))
			{
				$elUsuario=$_REQUEST['libreta'];
				
			}
			else
			{
				$elUsuario=$_REQUEST['registro'];
				
			}
			unset($valor);
			$valor[0]=$_REQUEST['libreta'];
			$valor[1]=$_REQUEST['distrito'];
			$valor[2]=$_REQUEST['registro'];
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"editarLibretaMilitar",$valor); 
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
			echo "<table align=center><tr><td><h3>IMPOSIBLEs GUARDAR EL FORMULARIO</h3></td></tr></table>";	
		}
	}
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
	//Rescata los datos registrados del usuario.
	function datosUsuario()
	{
		$registro=$this->verificarUsuario($configuracion);
		if(is_array($registro))
		{
			?><table class="formulario" align="center">
						<tr  class="bloquecentralencabezado">
							<td colspan="2">
								<p><span class="texto_negrita">Datos Registrados del Usuario</span></p>
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
			</table>
			<?
		}
		else
		{
			return false;
		
		}
	
	}	
	
	//Verifica los datos registrados del usuario.
	function verificarUsuario($configuracion)
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
	
	//Valida que la inscripcion para grados se pueda realizar dentro de las fechas establecidas.
	function validaCalendario($configuracion)
	{
		//Valida las fechas del calendario
		
		$registroUsuario=$this->verificarUsuario($configuracion);
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosUsuario",$usuario);
		@$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$valor[1]=$resultado[0][4];
		$confec = "SELECT TO_CHAR(CURRENT_TIMESTAMP, 'yyyymmdd') ";
		@$rows=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confec, "busqueda");
		$fechahoy =$rows[0][0];
				
		$qryFechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "validaFechas",$valor);
		@$calendario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryFechas, "busqueda");
		$FormFecIni = $calendario[0][2];
		$FormFecFin = $calendario[0][3];
			if( $calendario[0][0] == "" ||  $calendario[0][1] == "")
			{
				die('<br><br><p align="center"><b><font color="#0000FF" size="3">No se han programado fechas para inscripci&oacute;n de aspirantes.</font></p>');
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
										<p align="center"><font color="red"><b>El proceso de inscripci&oacute;n de grados ser&aacute; del: <br>'.$FormFecIni.' al '.$FormFecFin.'</b></font></p>
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
										<p align="center"><font color="red"><b>El proceso de inscripci&oacute;n de grados  termin&oacute; el: '.$FormFecFin.'</b></font></p>
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
				$variable="pagina=registro_inscripcionGrado";
				$variable.="&opcion=confirmar";
				$variable.="&identificador=".$valor;
				break;
				
			case "formgrado":
				$variable="pagina=registro_inscripcionGrado";
				//$variable.="&opcion=verificar";
				$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
				$variable.="&xajax_file=blogdev";
				break;
						
			case "principal":
				$variable="pagina=index";
				break;
				
			case "registroexitoso":
				$variable="pagina=registro_inscripcionGrado";
				$variable.="&opcion=exito";
				break;
							
			case "mostrarregistro":
				$variable="pagina=registro_inscripcion";	
				$variable.="&opcion=generar";
				break;
				
			case "registroeditado":
				$variable="pagina=registro_inscripcionGrado";	
				$variable.="&opcion=exitoEdicion";
				$variable.="&bitacora=".$valor[0];
				break;
		
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>
