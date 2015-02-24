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

class funciones_adminIntensidadHoraria extends funcionGeneral
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
		$this->accesoOracle=$this->conectarDB($configuracion,"administrador");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="admin_intensidadHoraria";
		$this->verificar="control_vacio(".$this->formulario.",'estudiante')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'rel_des')";
	}
			
	////Formulario para actualizar la intensidad horaria de un estudiante.
	function actualizaIntensidad($configuracion, $accesoOracle,$acceso_db)
	{
		//echo "mmmm".$_REQUEST['mensaje'];
		if(isset($_REQUEST['mensaje']))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='El proceso se ejectut&oacute; exitosamente, realice una nueva actualizaci&oacute;n<br> de la intensidad horaria de otro estudiante.';
			alerta::sin_registro($configuracion,$cadena);
		} 
		?>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<div style="position: absolute; display: none; left: 158px; top: 32px;" class="calendar"><table style="visibility: visible;" cellpadding="0" cellspacing="0"><thead><tr><td class="button" colspan="1"><div unselectable="on">?</div></td><td style="cursor: move;" class="title" colspan="6">Septiembre, 2009</td><td class="button" colspan="1"><div unselectable="on">×</div></td></tr><tr class="headrow"><td class="button nav" colspan="1"><div unselectable="on">«</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8249;</div></td><td class="button" colspan="4"><div unselectable="on">Hoy</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8250;</div></td><td class="button nav" colspan="1"><div unselectable="on">»</div></td></tr><tr class="daynames"><td class="name wn">sem</td><td class="day name">Lun</td><td class="day name">Mar</td><td class="day name">Mie</td><td class="day name">Jue</td><td class="day name">Vie</td><td class="name day weekend">S&aacute;b</td><td class="name day weekend">Dom</td></tr></thead><tbody><tr class="daysrow"><td class="day wn">36</td><td class="emptycell">&nbsp;</td><td class="day">1</td><td class="day">2</td><td class="day">3</td><td class="day">4</td><td class="day weekend">5</td><td class="day weekend">6</td></tr><tr class="daysrow"><td class="day wn">37</td><td class="day">7</td><td class="day">8</td><td class="day">9</td><td class="day">10</td><td class="day">11</td><td class="day weekend">12</td><td class="day weekend">13</td></tr><tr class="daysrow"><td class="day wn">38</td><td class="day">14</td><td class="day">15</td><td class="day">16</td><td class="day">17</td><td class="day">18</td><td class="day weekend">19</td><td class="day weekend">20</td></tr><tr class="daysrow"><td class="day wn">39</td><td class="day">21</td><td class="day">22</td><td class="day">23</td><td class="day">24</td><td class="day">25</td><td class="day selected today weekend">26</td><td class="day weekend">27</td></tr><tr class="daysrow"><td class="day wn">40</td><td class="day">28</td><td class="day">29</td><td class="day">30</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr><tr class="emptyrow"><td class="day wn">41</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr></tbody><tfoot><tr class="footrow"><td style="cursor: move;" class="ttip" colspan="8">Seleccionar fecha</td></tr></tfoot></table><div style="display: none;" class="combo"><div class="label">Ene</div><div class="label">Feb</div><div class="label">Mar</div><div class="label">Abr</div><div class="label">May</div><div class="label">Jun</div><div class="label">Jul</div><div class="label">Ago</div><div class="label">Sep</div><div class="label">Oct</div><div class="label">Nov</div><div class="label">Dic</div></div><div style="display: none;" class="combo"><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div></div></div>
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown">
								<ul>
									<li>Para actualizar la intensidad horaria de un estudiante, digite el c&oacute;digo del estudiante y haga click en "Actualizar".</li>
								</ul>
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td class="" align="center">
								<p><span class="texto_negrita">Actualizaci&oacute;n Intensidad Horaria.</span></p>
							</td>
						</tr>
					</table>
					<table class="contenidotabla centrar">
						<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
						<tr>
							<td align="center">
								<fieldset>
									<legend>
										Actualizaci&oacute;n de intensidad horaria
									</legend>
									<table class="formulario">
										<tr>
											<td>
												<font color="red">*</font>C&oacute;digo del estudiante
											</td>
											<td>
												<input maxlength="450" size="25" tabindex="<? echo $tab++ ?>" name="estudiante">
											</td>
										</tr>
										
										<tr align='center'>
											<td colspan="16">
												<table class="tablaBase">
													<tr>
														
														<td align="center">
															<input type='hidden' name='usuario' value='<? echo $valor[0] ?>'>
															<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
															<input type='hidden' name='actualizar' value='grabar'>
															<input value="Actualizar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
														</td>
														<!--td align="center">
															<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
															<input type="submit" name="notdef" value="Calcular Acumulado">
														</td>
														<td align="center">
															<input type='hidden' name='usuario' value='<? echo $valor[0]?>'>
															<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
														</td-->
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
	
	//Ejectuta el procedimiento para actualizar la intensidad horaria
	function ejecutarActualizarIntensidad($configuracion, $accesoOracle,$acceso_db)
	{
		$valor[0]=$_REQUEST['estudiante'];
		
		$busqueda="BEGIN ACTUALIZANOTAS_EST(".$valor[0]."); END; ";
		
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "");
		if($resultado==TRUE)
		{
			$valor[1]='mensaje'; 
			$this->redireccionarInscripcion($configuracion,"registroExitoso",$valor);
		}
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='El proceso no pudo ser ejecutado, revise el procedimiento e intente nuevamente';
			alerta::sin_registro($configuracion,$cadena);	
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
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "datosUsuarios",$this->usuario);
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
			
			case "principal":
				$variable="pagina=index";
				break;
				break;
			case "registroExitoso":
				$variable="pagina=adminIntensidadHoraria";
				$variable.="&opcion=actualizaIntensidad";
				$variable.="&mensaje=".$valor[1];
				break;
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

