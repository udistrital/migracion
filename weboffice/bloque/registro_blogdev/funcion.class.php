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

class funciones_registroBlogdev extends funcionGeneral
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
		
		$this->formulario="registro_blogdev";
		$this->verificar="control_vacio(".$this->formulario.",'descripcion')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'rel_des')";
	}
	
	//Rescata los valores del formulario para guardarlos en la base de datos.
	function guardarRegistro ($configuracion, $accesoOracle,$acceso_db)
	{
	$unUsuario=$this->verificarUsuario();


		if(is_array($unUsuario))
		{
			$claseobjeto=explode('#',$_REQUEST['claseobjeto']);
			$valor[0]=$_REQUEST['id_usuario'];
			$valor[1]=$_REQUEST['nombre'];
			$valor[2]=$_REQUEST['apellido'];
			$valor[3]=$_REQUEST['objeto'];
			$valor[4]=$_REQUEST['accion'];
			$valor[5]=$_REQUEST['descripcion'];	
			$valor[6]=$_REQUEST['consecutivo'];
			$valor[7]=$_REQUEST['ore_obj'];		
			$valor[8]=$_REQUEST['tip_rel'];	
			$valor[9]=$_REQUEST['desc'];
			$valor[10]=$_REQUEST['aplicacion'];
			$valor[11]=$claseobjeto[1];
			$valor[12]=$_REQUEST['tipoobjeto'];
			//echo $valor[0];						
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertarRegistro",$valor);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			//echo "<br>->".$cadena_sql;
			
					foreach ($_REQUEST as $clave => $val){
					
						$_REQUEST[$clave]= strip_tags($val);
						
						if(stripos($clave,'ore_obj') !== false){
							$valores[$clave]=$val;
						}
						if(stripos($clave,'tip_rel') !== false){
							$valores[$clave]=$val;
						}
						if(stripos($clave,'rel_des') !== false){
							$valores[$clave]=$val;
						}
					}
			
					$i=0;
					$j=0;
					$k=0;
		
					foreach ($valores as $clave => $val){
		
						if(stripos($clave,'ore_obj') !== false){
							$regrelacion['ore_obj'][$i]=$val;
							$i++;
						}
						if(stripos($clave,'tip_rel') !== false){
							$regrelacion['tip_rel'][$j]=$val;
							$j++;
						}
						if(stripos($clave,'rel_des') !== false){
							$regrelacion['rel_des'][$k]=$val;
							$k++;
						}
					}
					
					for($l=0;$l<count($regrelacion['ore_obj']);$l++){
					
						unset($valor);
						$valor[0]=$_REQUEST['consecutivo'];
						$valor[1]=$regrelacion['ore_obj'][$l];
						$valor[2]=$regrelacion['tip_rel'][$l];
						$valor[3]=$regrelacion['rel_des'][$l];
						
						$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarObjrelacionados",$valor);
						$resultado2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");	
					
					}
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
	
	//Rescata los valores del formulario de edición, y modifica los valores en la base de datos.
	function guardarEdicion ($configuracion, $accesoOracle,$acceso_db)
	{
	$unUsuario=$this->verificarUsuario();
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
			$valor[0]=$_REQUEST['editarBitacora'];
			$valor[1]=$_REQUEST['accionm'];
			$valor[2]=$_REQUEST['aplicacionm'];
			$valor[3]=$_REQUEST['claseobjetom'];
			$valor[4]=$_REQUEST['tipoobjetom'];
			$valor[5]=$_REQUEST['objetom'];
			$valor[6]=$_REQUEST['descripcionm'];
			//echo $valor[0]."<br>";
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"editarBitacora",$valor);
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
	
	////Rescata los valores del formulario de edición de objetos relacionados, y los modifica en la base de datos.
	function guardarEdicionobjrel($configuracion, $accesoOracle,$acceso_db)
	{
	$unUsuario=$this->verificarUsuario();
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
			$valor[0]=$_REQUEST['guardaredicionObjrel'];
			$valor[1]=$_REQUEST['tip_relm'];
			$valor[2]=$_REQUEST['ore_objm'];
			$valor[3]=$_REQUEST['rel_desm'];
			$valor[4]=$_REQUEST['tiprelacion'];
			$valor[5]=$_REQUEST['objcod'];	
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"editarObjrel",$valor);
			$resultado2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			
			if(isset($resultado2))
			{
				$this->redireccionarInscripcion($configuracion,"registroeditadoobjrel",$valor);	
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
	
	//Función que arma el formulario de nuevos registors de bitácora.
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
							</td>
						</tr>
					</table>
					<br>
					<table class="formulario" align="center">
						<tr  class="bloquecentralencabezado">
							<td colspan="3" align="center">
								<p><span class="texto_negrita">INGRESAR BITACORA</span></p>
							</td>
						</tr>
						<tr>
							<td colspan="3" rowspan="1"><br>Ingreso de bit&aacute;cora<hr class="hr_subtitulo"></td>
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
								<font color="red">*</font>Accion
							</td>
							<td colspan="2">
								<select name="accion">
								<option value='A'>Actualizar</option>
								<option value='B'>Borrar</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<font color="red">*</font>Aplicaci&oacute;n
							</td>
							<td>
								<font color="red">*</font>Clase de objeto
							</td>
							<td>
								<font color="red">*</font>Tipo de objeto
							</td>
						</TR>
						<TR>
							<td><?
							include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
							$html=new html();
													
							$busqueda="SELECT ";
							$busqueda.="apl_cod, ";
							$busqueda.="apl_nom ";
							$busqueda.="FROM ";
							$busqueda.="aplicaciones ";
							$busqueda.="ORDER BY apl_nom";
							$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
							
							$configuracion["ajax_function"]="xajax_clasedeObjeto";
							$configuracion["ajax_control"]="aplicacion";
							for ($i=0; $i<count($resultado);$i++)
							{
								$registro[$i][0]=$resultado[$i][0];
								$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
							}
							$mi_cuadro=$html->cuadro_lista($registro,'aplicacion',$configuracion,-1,2,FALSE,$tab++,"aplicacion",100);
							
							echo $mi_cuadro;
							
							?></td>
														
							<td>
								<div id="divClaobjeto"><?
								$busqueda="SELECT ";
								$busqueda.="clo_cod, ";
								$busqueda.="clo_nom ";
								$busqueda.="FROM ";
								$busqueda.="claobj";
													
								$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
													
								$configuracion["ajax_function"]="xajax_tipodeObjeto";
								$configuracion["ajax_control"]="claseobjeto";
								
								$mi_cuadro=$html->cuadro_lista("",'claseobjeto',$configuracion,-1,2,FALSE,$tab++,"claseobjeto",100);
								
								echo $mi_cuadro;
										
								
								?></div>
							</td>
														
							<td>
								<div id="divTipobj"><?
								$busqueda="SELECT ";
								$busqueda.="tio_cod, ";
								$busqueda.="tio_nom ";
								$busqueda.="FROM ";
								$busqueda.="tipobj";
													
								$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
													
								$configuracion["ajax_function"]="xajax_Objeto";
								$configuracion["ajax_control"]="tipoobjeto";
								
								$mi_cuadro=$html->cuadro_lista("",'tipoobjeto',$configuracion,-1,2,FALSE,$tab++,"tipoobjeto",100);
								
								echo $mi_cuadro;
																	
								?></div>
							</td>
						</tr>
						<tr>
							<td>
								<font color="red">*</font>Objetos
							</td>
							<td colspan="2">
								<div id="divObjeto"><?
								$busqueda="SELECT ";
								$busqueda.="obj_cod, ";
								$busqueda.="obj_nombre ";
								$busqueda.="FROM ";
								$busqueda.="objetos";
													
								$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
																
								$mi_cuadro=$html->cuadro_lista($resultado,'objeto',$configuracion,-1,2,FALSE,$tab++,"objeto",100);
								
								echo $mi_cuadro;
							?></div>
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
						<tr>
							<td colspan="3" rowspan="1"><br>OBJETOS RELACIONADOS<hr class="hr_subtitulo"></td>
						</tr>
						<tr>
							<td colspan="3">
								<table class="formulario" align="center">
									<tr>
										<td>
											Objeto
										</td>
										<td>
											Tipo relaci&oacute;n
										</td>
										<td>
											Descripci&oacute;n
										</td>
									</tr>
									<tr id='obj'>
										<td>
											<?
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
											$busqueda.="obj_cod, ";
											$busqueda.="obj_nombre ";
											$busqueda.="FROM ";
											$busqueda.="objetos ";
											$busqueda.="ORDER BY obj_nombre";
											$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
																						
											$mi_cuadro=$html->cuadro_lista($resultado,'ore_obj',$configuracion,-1,2,FALSE,$tab++,"ore_obj",100);
														
											echo $mi_cuadro;
											?>
										</td>
										<td>
											<?
											include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
											$html=new html();
																	
											$busqueda="SELECT ";
											$busqueda.="tre_cod, ";
											$busqueda.="tre_des ";
											$busqueda.="FROM ";
											$busqueda.="tiprel ";
											$busqueda.="ORDER BY tre_cod";
											$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
																						
											$mi_cuadro=$html->cuadro_lista($resultado,'tip_rel',$configuracion,-1,2,FALSE,$tab++,"tip_rel",100);
														
											echo $mi_cuadro;
											?>
										</td>
										<td>
											<input maxlength="450" size="25" tabindex="<? echo $tab++ ?>" name="rel_des"><br>
										</td>
										<td>
											<input id='mas' type='button' name='action' value=' + ' onclick="addModulo('obj','')">
										</td>
									</tr>
									
								</table>
							</td>
						</tr>
												
						<tr align='center'>
							<td colspan="3">
								<table class="tablaBase">
									<tr>
										<td align="center">
											<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
											<input type='hidden' name='id_usuario' value='<? echo $id_usuario ?>'>
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
	
	//Función que muestra en pantalla un mensaje, si se guardó exitosamente, muestra el mensaje, adicionalmente rescata los datos guardados en la base de datos;
	//si no: imposible mostrar los datos de regstro. 
	function RegistroExitoso($configuracion,$registro, $total, $opcion="",$variable)
	{
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultarRegistro",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
					
		if(is_array($resultado))
		{
			if($resultado[0][7]=='C'){
				$accion="CREAR";
			}
			elseif($resultado[0][7]=='A'){
				$accion="Actualizar";
			}
			elseif($resultado[0][7]=='B'){
				$accion="Borrar";
			}
			echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
							<td colspan='4'>
								<p>El registro se guard&oacute; exitosamente con los siguientes datos:</p>
							</td>
						</tr>
					<tr  class='cuadro_color'>
							<td colspan='4' align='center'>
								<p><span class='texto_negrita'>BITACORA No.".$resultado[0][1]."</span></p>
							</td>
						</tr>
					<tr class='cuadro_color'>
						<td align='center'>
							Objeto
						</td>
						<td align='center'>
							Acci&oacute;n
						</td>
						<td align='center'>
							Descripci&oacute;n
						</td>
					</tr>
					<tr>
						<td align='center'>
							".$resultado[0][4]."
						</td>
						<td align='center'>
							".$accion."
						</td>
						<td align='center'>
							".$resultado[0][8]."
						</td>
					</tr>	
				 </table>";
		}
		else
		{
			echo "Imposible mostrar los datos de registro";
		}
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consutaTiprel",$valor);
		$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$totreg=count($resultado1);
		echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
		    			<td colspan='3' align='center'>
		    				
		    			</td>
					</tr>
					<tr  class='cuadro_color'>
		    			<td colspan='3' align='center'>
		    				<p><span class='texto_negrita'>Objetos relacionados</span></p>
		    			</td>
					</tr>
					<tr class='cuadro_color'>
						<td align='center'>
							Objeto
						</td>
						<td align='center'>
							Tipo relaci&oacute;n
						</td>
						<td align='center'>
							Descripci&oacute;n
						</td>
					</tr>";
					for($i=0;$i<$totreg;$i++)
					{
						if($resultado[0][1]==$resultado1[$i][0])
						{
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][6]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][7]."</td>";
						echo "</tr>";
						}
					
					}
		echo "</table>";
	}
	
	//Función que muestra en pantalla un mensaje, si se modificó exitosamente el registro, adicionalmente rescata los datos modificados en la base de datos.
	function EdicionExitosa($configuracion,$registro, $total, $opcion="",$variable)
	{
		$valor[1]=$_REQUEST['bitacora'];
		//echo "mmm".$valor[1];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaEdicion",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
					
		if(is_array($resultado))
		{
			if($resultado[0][6]=='C'){
				$accion="CREAR";
			}
			elseif($resultado[0][6]=='A'){
				$accion="Actualizar";
			}
			elseif($resultado[0][6]=='B'){
				$accion="Borrar";
			}
			echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
							<td colspan='4'>
								<p>El registro se modific&oacute; exitosamente con los siguientes datos:</p>
							</td>
						</tr>
					<tr  class='cuadro_color'>
							<td colspan='4' align='center'>
								<p><span class='texto_negrita'>BITACORA No.".$resultado[0][0]."</span></p>
							</td>
						</tr>
					<tr class='cuadro_color'>
						<td align='center'>
							Objeto
						</td>
						<td align='center'>
							Acci&oacute;n
						</td>
						<td align='center'>
							Descripci&oacute;n
						</td>
					</tr>
					<tr>
						<td align='center'>
							".$resultado[0][3]."
						</td>
						<td align='center'>
							".$accion."
						</td>
						<td align='center'>
							".$resultado[0][7]."
						</td>
					</tr>	
				 </table>";
		}
		else
		{
			echo "Imposible mostrar los datos de registro";
		}
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consutaTiprel",$valor);
		$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$totreg=count($resultado1);
		echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
		    			<td colspan='3' align='center'>
		    				
		    			</td>
					</tr>
					<tr  class='cuadro_color'>
		    			<td colspan='3' align='center'>
		    				<p><span class='texto_negrita'>Objetos relacionados</span></p>
		    			</td>
					</tr>
					<tr class='cuadro_color'>
						<td align='center'>
							Objeto
						</td>
						<td align='center'>
							Tipo relaci&oacute;n
						</td>
						<td align='center'>
							Descripci&oacute;n
						</td>
					</tr>";
					for($i=0;$i<$totreg;$i++)
					{
						if($resultado[0][0]==$resultado1[$i][0])
						{
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][6]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][7]."</td>";
						echo "</tr>";
						}
					
					}
		echo "</table>";
	}
	
	//Función que muestra en pantalla un mensaje, si se modificó exitosamente el registro de objetos relacionados, adicionalmente rescata los datos modificados en la base de datos.
	function EdicionExitosaobjrel($configuracion,$registro, $total, $opcion="",$variable)
	{
	$valor[0]=$_REQUEST['guardaredicionObjrel'];
	$valor[1]=$_REQUEST['tip_relm'];
	$valor[2]=$_REQUEST['ore_objm'];
	$valor[3]=$_REQUEST['rel_desm'];
	$valor[4]=$_REQUEST['tiprelacion'];
	$valor[5]=$_REQUEST['objcod'];
			
	$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consutaTipreleditados",$valor);
		$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$totreg=count($resultado1);
		echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
							<td colspan='3'>
								<p>El registro se modific&oacute; exitosamente con los siguientes datos:</p>
							</td>
					</tr>
					<tr  class='cuadro_color'>
		    			<td colspan='3' align='center'>
		    				<p><span class='texto_negrita'>Objetos relacionados</span></p>
		    			</td>
					</tr>
					<tr class='cuadro_color'>
						<td align='center'>
							Objeto
						</td>
						<td align='center'>
							Tipo relaci&oacute;n
						</td>
						<td align='center'>
							Descripci&oacute;n
						</td>
					</tr>";
					for($i=0;$i<$totreg;$i++)
					{
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][6]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][7]."</td>";
						echo "</tr>";
						
					}
		echo "</table>";
	}
		
	//Muestra los registros consultados de la base de datos.	
	function mostrarRegistro($configuracion,$registro, $total, $opcion="",$valor)
	{
		switch($_REQUEST["accion"])
		{
			case "listaCompleta":
				
				//Paginacion
				//Obtener el total de registros
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "totalBitacora","");
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
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "bloqueadoBitacora",$variable);
										
				break;					
		
		}
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "bloqueadoBitacora",$variable);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		$total=count($resultado);
			
		setlocale(LC_MONETARY, 'en_US');
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		$menu=new navegacion();
		$variableNavegacion["pagina"]="registro_blogdev";
		$variableNavegacion["opcion"]="mostrar";	
		$variableNavegacion["accion"]="listaCompleta";
		$variableNavegacion["carrera"]=$registro[0][2];
		
		if($this->totalPaginas>1)
		{
			$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
		}
			
			echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
							<td colspan='7'>
								
							</td>
						</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='7' align='center'>
								<p><span class='texto_negrita'>BITACORA </span></p>
							</td>
						</tr>
					<tr class='cuadro_color'>
						<td class='cuadro_plano centrar''>
							No.
						</td>
						<td class='cuadro_plano centrar'>
							Objeto
						</td>
						<td class='cuadro_plano centrar''>
							Acci&oacute;n
						</td>
						<td class='cuadro_plano centrar'>
							Descripci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Usuario
						</td>
						<td class='cuadro_plano centrar''>
							Objetos<br>relacionados
						</td>
						<td class='cuadro_plano centrar''>
							Fecha
						</td>
					</tr>";
					
					for($i=0;$i<$total;$i++)
					{	
						if($resultado[$i][6]=='C'){
							$accion="CREAR";
						}
						elseif($resultado[$i][6]=='A'){
							$accion="Actualizar";
						}
						elseif($resultado[$i][6]=='B'){
							$accion="Borrar";
						}
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$accion."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][7]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][8]."</td>";
						$valor[0]=$resultado[$i][0];
						$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaObjetos",$valor);
						$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
						$totreg=count($resultado1);
						for($j=0;$j<$totreg;$j++)
						{
							if($resultado1[$j][0]==$resultado[$i][0])
							{
								$hayobrel=$totreg;
								$ver=" -Ver-";
							}
							else
							{
								$hayobrel=0;
							}
						}
					echo "<td class='cuadro_plano centrar'>
					$hayobrel
					<a href='";
					$variable="pagina=registro_blogdev";
					$variable.="&opcion=objetos";
					//$variable.="&no_pagina=true";
					$variable.="&bitacora=".$resultado[$i][0]."";
					$variable=$cripto->codificar_url($variable,$configuracion);
					echo $indice.$variable."'";
					echo "title='Consultar objetos relacionados'>".$ver."</a></td>";
					
					/*echo "<td class='cuadro_plano centrar'>
					<a href='";		
					$variable="pagina=registro_blogdev";
					$variable.="&opcion=editarBitacora";
					//$variable.="&no_pagina=true";
					$variable.="&bitacora=".$resultado[$i][0]."";
					$variable=$cripto->codificar_url($variable,$configuracion);
					echo $indice.$variable."'";
					echo $indice.$variable."'";
					echo "title='Consultar objetos relacionados'>";
						?>
						<img width="20" height="20" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/editar.png" alt="Modificar registro" title="Modificar bit&aacute;cora" border="0" />
						<?
					echo "</a></td>";*/
					echo "<td class='cuadro_plano centrar'>".$resultado[$i][5]."</td>";
					echo "</tr>";	
						
					}
			echo "</table>";		
			if($this->totalPaginas>1)
			{
				$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
			}
		
		
	}
	
	//Muestra los registros consultados de la tabla objetos relacionados 
	function mostrarObjetos($configuracion,$registro, $total, $opcion="",$valor)
	{
		$valor[0]=$_REQUEST['bitacora'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaObjetos",$valor);
		$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		$totreg=count($resultado1);
			
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
							Objeto
						</td>
						<td class='cuadro_plano centrar''>
							Tipo relaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Descripci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Modificar
						</td>
					</tr>";
					
					for($i=0;$i<$totreg;$i++)
					{	
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][6]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado1[$i][7]."</td>";
						
						echo "<td class='cuadro_plano centrar'>
						<a href='";		
						$variable="pagina=registro_blogdev";
						$variable.="&opcion=editarObjrel";
						$variable.="&bitacora=".$resultado1[$i][0]."";
						$variable.="&tiprelacion=".$resultado1[$i][5]."";
						$variable.="&objcod=".$resultado1[$i][2]."";
						$variable=$cripto->codificar_url($variable,$configuracion);
						echo $indice.$variable."'";
						echo $indice.$variable."'";
						echo "title='Modificar objetos relacionados'>";
							?>
							<img width="20" height="20" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/editar.png" alt="Modificar registro" title="Modificar objetos relacionados" border="0" />
							<?
						echo "</a></td>";
					}
			echo "</table>";
			if($this->totalPaginas>1)
			{
				$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
			}
		
		
	}
	
	//Función para modificar un registro específico de la bitácora.
	function modificarBitacora($configuracion,$registro, $total, $opcion="",$variable)
	{
	$registroUsuario=$this->verificarUsuario();
	$contador=0;	
	$tab=0;
	$valor[1]=$_REQUEST['bitacora'];
	$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaBitacora",$valor);
	$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");	
		if(is_array($resultado))
		{
			$objetomod=$resultado[0][2];
			$bitdesmod=$resultado[0][7];
			$aplicamod=$resultado[0][9];
			$claobjmod=$resultado[0][10];
			$tipobjmod=$resultado[0][12];
			
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
										<p><span class="texto_negrita">MODIFICAR BITACORA</span></p>
									</td>
								</tr>
								<tr>
									<td colspan="3" rowspan="1"><br>Bit&aacute;cora No.<?echo $resultado[0][0]?><hr class="hr_subtitulo"></td>
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
										<font color="red">*</font>Accion
									</td>
									<td colspan="2">
										<select name="accionm">
										<option value='A'>Actualizar</option>
										<option value='B'>Borrar</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<font color="red">*</font>Aplicaci&oacute;n
									</td>
									<td>
										<font color="red">*</font>Clase de objeto
									</td>
									<td>
										<font color="red">*</font>Tipo de objeto
									</td>
								</TR>
								<TR>
									<td><?
										include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
										$html=new html();
																
										$busqueda="SELECT ";
										$busqueda.="apl_cod, ";
										$busqueda.="apl_nom ";
										$busqueda.="FROM ";
										$busqueda.="aplicaciones ";
										$busqueda.="ORDER BY apl_nom";
										$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
										
										$mi_cuadro=$html->cuadro_lista($resultado,'aplicacionm',$configuracion,$aplicmod,0,FALSE,$tab++,"aplicacionm",100);
										
										echo $mi_cuadro;
									?></td>
																
									<td>
										<div id="divClaobjeto"><?
										$busqueda="SELECT ";
										$busqueda.="clo_cod, ";
										$busqueda.="clo_nom ";
										$busqueda.="FROM ";
										$busqueda.="claobj";
															
										$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
															
										$configuracion["ajax_function"]="xajax_Objeto";
										$configuracion["ajax_control"]="tipoobjeto";
										
										$mi_cuadro=$html->cuadro_lista($resultado,'claseobjetom',$configuracion,$claobjmod,2,FALSE,$tab++,"claseobjetom",100);
										
										echo $mi_cuadro;
												
										
										?></div>
									</td>
																
									<td>
										<div id="divTipobj"><?
										$busqueda="SELECT ";
										$busqueda.="tio_cod, ";
										$busqueda.="tio_nom ";
										$busqueda.="FROM ";
										$busqueda.="tipobj";
															
										$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
															
										$configuracion["ajax_function"]="xajax_Objeto";
										$configuracion["ajax_control"]="tipoobjeto";
										
										$mi_cuadro=$html->cuadro_lista($resultado,'tipoobjetom',$configuracion,$tipobjmod,2,FALSE,$tab++,"tipoobjetom",100);
										
										echo $mi_cuadro;
																	
										?></div>
									</td>
								</tr>
								<tr>
									<td>
										<font color="red">*</font>Objetos
									</td>
									<td colspan="2">
										<div id="divObjeto"><?
										$busqueda="SELECT ";
										$busqueda.="obj_cod, ";
										$busqueda.="obj_nombre ";
										$busqueda.="FROM ";
										$busqueda.="objetos";
															
										$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
																		
										$mi_cuadro=$html->cuadro_lista($resultado,'objetom',$configuracion,$objetomod,0,FALSE,$tab++,"objetom",100);
										
										echo $mi_cuadro;
										?></div>
									</td>
								</tr>
																			
								<tr>
									<td>
										<font color="red">*</font>Descripci&oacute;n
									</td>
									<td colspan="2">
										<textarea id='descripcionm' name='descripcionm' cols='50' rows='2' value='<?echo $bitdesmod?>' tabindex='<? echo $tab++ ?>' ><?echo $bitdesmod?></textarea>
									</td>
								</tr>
								<tr align='center'>
									<td colspan="3">
										<table class="tablaBase">
											<tr>
												<td align="center">
													<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
													<input type='hidden' name='bitacora' value='<? echo $valor[1] ?>'>
													<input type='hidden' name='editarBitacora' value='<? echo $valor[1] ?>'>
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
			
			</div>	
		
							
			<?		
		}
		else
		{
			echo "Imposible mostrar los datos de registro";
		}
	}
			
	//Función para modificar un registro específico de los objetos relacionados.
	function modificarObjrel($configuracion,$registro, $total, $opcion="",$valor)
	{
		$registroUsuario=$this->verificarUsuario();
		$contador=0;	
		$tab=0;
		$valor[0]=$_REQUEST['bitacora'];
		$valor[1]=$_REQUEST['tiprelacion'];
		$valor[2]=$_REQUEST['objcod'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaObjetosrelacionados",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");	
			if(is_array($resultado))
			{
				$orebitcon=$resultado[0][0];
				$objcodmod=$resultado[0][2];
				$tredesmod=$resultado[0][5];
				$claobjmod=$resultado[0][7];
								
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
											<p><span class="texto_negrita">MODIFICAR OBJETOS RELACIONADOS</span></p>
										</td>
									</tr>
									<tr>
										<td colspan="3" rowspan="1"><br>Bit&aacute;cora No.<?echo $resultado[0][0]?><hr class="hr_subtitulo"></td>
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
											<font color="red">*</font>Objeto
										</td>
										<td>
											<font color="red">*</font>Tipo relaci&oacute;n
										</td>
										<td>
											<font color="red">*</font>Descripci&oacute;n
										</td>
										
									</tr>
									<TR>
										<td>
											<?
											$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultarConsecutivo",$variable);
											$resultado2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
											if(is_array($resultado1))
											{
												$consecutivo= $resultado2[0][0];
												$consecutivo;
											}
											else
											{
												echo " ";
											}	
											include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
											$html=new html();
																	
											$busqueda="SELECT ";
											$busqueda.="obj_cod, ";
											$busqueda.="obj_nombre ";
											$busqueda.="FROM ";
											$busqueda.="objetos ";
											$busqueda.="ORDER BY obj_cod";
											$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
																						
											$mi_cuadro=$html->cuadro_lista($resultado,'ore_objm',$configuracion,$objcodmod,0,FALSE,$tab++,"ore_objm",100);
														
											echo $mi_cuadro;
											?>
										</td>
										<td>
											<?
											include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
											$html=new html();
																	
											$busqueda="SELECT ";
											$busqueda.="tre_cod, ";
											$busqueda.="tre_des ";
											$busqueda.="FROM ";
											$busqueda.="tiprel ";
											$busqueda.="ORDER BY tre_cod";
											$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
																						
											$mi_cuadro=$html->cuadro_lista($resultado,'tip_relm',$configuracion,$tredesmod,0,FALSE,$tab++,"tip_relm",100);
														
											echo $mi_cuadro;
											?>
										</td>
										<td>
											<input maxlength="450" size="25" tabindex="<? echo $tab++ ?>" name="rel_desm" value='<?echo $claobjmod?>'><br>
										</td>
									</tr>
									<tr align='center'>
										<td colspan="3">
											<table class="tablaBase">
												<tr>
													<td align="center">
														<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
														<input type='hidden' name='bitacora' value='<? echo $valor[0] ?>'>
														<input type='hidden' name='tiprelacion' value='<? echo $valor[1] ?>'>
														<input type='hidden' name='objcod' value='<? echo $valor[2] ?>'>
														<input type='hidden' name='guardaredicionObjrel' value='<? echo $valor[0] ?>'>
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
				
				</div>	
			
								
				<?		
			}
			else
			{
				echo "Imposible mostrar los datos de registro";
			}
	}
	
	function generarReportes($configuracion,$registro, $total, $opcion="",$valor)
	{
		switch($_REQUEST["accion"])
		{
			case "buscarBitacora":
			//echo "Pagina en construccion";
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
										</td>
									</tr>
								</table>
								<br>
								<table class="formulario" align="center">
									<tr  class="bloquecentralencabezado">
										<td colspan="3" align="center">
											<p><span class="texto_negrita">CONSULTAR BITACORAS</span></p>
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
											</font>Buscar por:
										</td>
										<td colspan="2">
											<select name="buscar">
											<option value='objetos'>Objetos</option>
											<option value='descripcion'>Descripci&oacute;n</option>
											<option value='usuario'>Usuario</option>
											</select>
										</td>
									</tr>
									<TR>
										<td>
										Dgite una palabra	
										</td>
										<td>
											<input maxlength="450" size="25" tabindex="<? echo $tab++ ?>" name="consulta" value=''>
										</td>
									</tr>
									<tr align='center'>
										<td colspan="3">
											<table class="tablaBase">
												<tr>
													<td align="center">
														<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
														<input type='hidden' name='reportes' value='reportes'>
														<input value="Buscar" name="consultar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>								
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
	}
		
	function mostrarReporte($configuracion, $accesoOracle,$acceso_db)
	{
	$unUsuario=$this->verificarUsuario();
		if(is_array($unUsuario))
		{
			if($_REQUEST['buscar'])
			{
				unset($valor);
				$valor[0]=$_REQUEST['buscar'];
				$valor[1]=$_REQUEST['consulta'];
							
				$this->redireccionarInscripcion($configuracion,"mostrarreportes",$valor);	
				
			}
		}
		else
		{
			echo "<table align=center><tr><td><h3>IMPOSIBLE GUARDAR EL FORMULARIO</h3></td></tr></table>";	
		}		
	}
	
	function mostrarReportes($configuracion,$registro, $total, $opcion="",$valor)
	{
	$variable[0]= $_REQUEST['buscar'];
	$variable[1]= $_REQUEST['consulta'];
	$totalobj=$variable[0]."total";
	//echo $variable[0]."<br>";
	//echo $variable[1]."<br>";
	//echo $totalobj;
	echo "JJJ".$_REQUEST["accion"];
		switch($_REQUEST["accion"])
		{
			case "listaCompleta":
				
				//Paginacion
				//Obtener el total de registros
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, $totalobj,$variable);
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
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,$variable[0],$variable);
										
				break;					
		
		}
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,$variable[0],$variable);
		//echo "mmm".$cadena_sql;
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		if(is_array($resultado))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
			$total=count($resultado);
				
			setlocale(LC_MONETARY, 'en_US');
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$cripto=new encriptar();
			
			$menu=new navegacion();
			$variableNavegacion["pagina"]="registro_blogdev";
			$variableNavegacion["opcion"]="mostrarrep";	
			$variableNavegacion["accion"]="listaCompleta";
			$variableNavegacion["carrera"]=$registro[0][2];
			$variableNavegacion["buscar"]=$variable[0];
			$variableNavegacion["consulta"]=$variable[1];
							
			if($this->totalPaginas>1)
			{
				$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
			}
				
				echo "<table class='formulario' align='center'>
						<tr  class='bloquecentralencabezado'>
								<td colspan='7'>
									
								</td>
							</tr>
						<tr  class='bloquecentralencabezado'>
								<td colspan='7' align='center'>
									<p><span class='texto_negrita'>BITACORA </span></p>
								</td>
							</tr>
						<tr class='cuadro_color'>
							<td class='cuadro_plano centrar''>
								No.
							</td>
							<td class='cuadro_plano centrar''>
								Objeto
							</td>
							<td class='cuadro_plano centrar''>
								Acci&oacute;n
							</td>
							<td class='cuadro_plano centrar''>
								Descripci&oacute;n
							</td>
							<td class='cuadro_plano centrar''>
								Usuario
							</td>
							<td class='cuadro_plano centrar''>
								Fecha
							</td>
							<td class='cuadro_plano centrar''>
								Objetos<br>relacionados
							</td>
						</tr>";
						
						for($i=0;$i<$total;$i++)
						{	
							if($resultado[$i][6]=='C'){
								$accion="CREAR";
							}
							elseif($resultado[$i][6]=='A'){
								$accion="Actualizar";
							}
							elseif($resultado[$i][6]=='B'){
								$accion="Borrar";
							}
							echo "<tr>";
							echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
							echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
							echo "<td class='cuadro_plano centrar'>".$accion."</td>";
							echo "<td class='cuadro_plano centrar'>".$resultado[$i][7]."</td>";
							echo "<td class='cuadro_plano centrar'>".$resultado[$i][8]."</td>";
							echo "<td class='cuadro_plano centrar'>".$resultado[$i][5]."</td>";
							$valor[0]=$resultado[$i][0];
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaObjetos",$valor);
							$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
							$totreg=count($resultado1);
							for($j=0;$j<$totreg;$j++)
							{
								if($resultado1[$j][0]==$resultado[$i][0])
								{
									$hayobrel=$totreg;
								}
								else
								{
									$hayobrel=0;
								}
							}
							echo "<td class='cuadro_plano centrar'>
							<a href='";		
							$variable="pagina=registro_blogdev";
							$variable.="&opcion=objetos";
							//$variable.="&no_pagina=true";
							$variable.="&bitacora=".$resultado[$i][0]."";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable."'";
							echo "title='Consultar objetos relacionados'>".$hayobrel."  -Ver-</a></td>";
							echo "</tr>";	
							
						}
				echo "</table>";		
				if($this->totalPaginas>1)
				{
					$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
				}
		}
		else
		{
		echo "<p class='cuadro_brown'>Consulta inv&aacute;lida, intente de nuevo.</p>";
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
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "datosUsuario",$this->usuario);
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
				$variable="pagina=registro_blogdev";
				$variable.="&opcion=confirmar";
				$variable.="&identificador=".$valor;
				break;
				
			case "formgrado":
				$variable="pagina=registro_blogdev";
				//$variable.="&opcion=verificar";
				$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
				$variable.="&xajax_file=blogdev";
				break;
						
			case "principal":
				$variable="pagina=index";
				break;
				
			case "registroexitoso":
				$variable="pagina=registro_blogdev";
				$variable.="&opcion=exito";
				break;
							
			case "mostrarregistro":
				$variable="pagina=registro_blogdev";	
				$variable.="&opcion=generar";
				break;
				
			case "generarreportes":
				$variable="pagina=registro_blogdev";	
				$variable.="&opcion=mostrar";
				break;
			
			case "registroeditado":
				$variable="pagina=registro_blogdev";
				$variable.="&opcion=exitoEdicion";
				$variable.="&bitacora=".$valor[0];
				break;
			
			case "registroeditadoobjrel":		
				$variable="pagina=registro_blogdev";
				$variable.="&opcion=exitoEdicionobjrel";
				$variable.="&guardaredicionObjrel=".$valor[0];
				$variable.="&tip_relm=".$valor[1];
				$variable.="&ore_objm=".$valor[2];
				$variable.="&rel_desm=".$valor[3];
				break;
			
			case "mostrarreportes":
				$variable="pagina=registro_blogdev";	
				$variable.="&opcion=mostrarrep";
				$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
				$variable.="&xajax_file=blogdev";
				$variable.="&accion=listaCompleta";
				$variable.="&buscar=".$valor[0];
				$variable.="&consulta=".$valor[1];
				break;
							
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

