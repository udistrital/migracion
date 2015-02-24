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


class funciones_admin_admisiones extends funcionGeneral
{
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				
		$this->cripto=new encriptar();
		$this->tema=$tema;
		$this->sql=$sql;
		
		//Conexion ORACLE
		$this->accesoOracle=$this->conectarDB($configuracion,"admisiones");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		 //echo  "mmm".$_REQUEST['usuario'];  
  
		$this->formulario="admin_admisiones";
		//$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'fecha')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'radicado')";
	}
	
	//Presentación módulo administración de contedido
	function presentacion($configuracion)
	{
	?>
	<table align="center" class="tablaMarcoGeneral">
		<tbody>
			<tr>
				<td >
					<table class="tablaMarco">
						<tbody>
							<tr class=texto_elegante >
								<td>
								<b>::::..</b>  Recibos de Pago
								<hr class=hr_subtitulo>
								</td>
							</tr>						
							<tr class="bloquecentralcuerpo">
								<td valign="top">
									<h3>Bienvenido al nuevo m&oacute;dulo de administraci&oacute;n de contedido del m&oacute;dulo de admisiones. </h3>
									<p>Desde el men&uacute; lateral derecho puede seleccionar las opciones que le permiten administras las fechas de inscripci&oacute;n, de publicaci&oacute;n de resultados,
									la edicil&oacute;n del instructivo, las colillas y las fechas para pago del recibo de matr&iacute;cula.</p>
									<p><br></p>
									<p class="texto_negrita">
									
									</p>
									
									</p>								
								</td>
							</tr>
						</tbody>
					</table>

				</td>
			</tr>
		</tbody>
	</table>
	<?  
	}

	//Adminstra las fechas de inscripción de aspirantes y de consulta de resultados.
	function administracionFechasInsRes($configuracion)
	{
		if($this->usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$registroUsuario=$this->verificarUsuario();

		$valor[3]=$_REQUEST['nivel'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "periodoacad",$valor);
		$resultadoPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$valor[1]=$resultadoPer[0][0];
		$valor[2]=$resultadoPer[0][1];
		//echo "mmmm".
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultarAccaleventos",$valor);
		$resultadoEventos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$total=count($resultadoEventos);
		//echo $resultadoFechas[0][0]."<br>";
		//echo $resultadoFechas[0][1]."<br>";
		$valor[4]=array(19,20);
		?>  
		<table class="formulario" align="center">
			<tr>
				<td class="cuadro_brown">
					<ul>
						<li>Para cambiar las fechas de pago, haga click en el cuadro de texto, cuando se abra la ventana con el calendario, haga click en el d&iacute;a.</li>
					</ul>
				</td>
			</tr>
			<tr class="texto_subtitulo">
				<td class="" align="center">
					<p><span class="texto_negrita">ACTUALIZACI&Oacute;N DE FECHAS PARA INSCRICI&Oacute;N DE ASPIRANTES Y PUBLICACI&Oacute;N DE RESULTADOS PERIODO <?echo $valor[1].'-'.$valor[2];?></span></p>
				</td>
			</tr>
		</table>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<div style="position: absolute; display: none; left: 158px; top: 32px;" class="calendar"><table style="visibility: visible;" cellpadding="0" cellspacing="0"><thead><tr><td class="button" colspan="1"><div unselectable="on">?</div></td><td style="cursor: move;" class="title" colspan="6">Septiembre, 2009</td><td class="button" colspan="1"><div unselectable="on">×</div></td></tr><tr class="headrow"><td class="button nav" colspan="1"><div unselectable="on">«</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8249;</div></td><td class="button" colspan="4"><div unselectable="on">Hoy</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8250;</div></td><td class="button nav" colspan="1"><div unselectable="on">»</div></td></tr><tr class="daynames"><td class="name wn">sem</td><td class="day name">Lun</td><td class="day name">Mar</td><td class="day name">Mie</td><td class="day name">Jue</td><td class="day name">Vie</td><td class="name day weekend">S&aacute;b</td><td class="name day weekend">Dom</td></tr></thead><tbody><tr class="daysrow"><td class="day wn">36</td><td class="emptycell">&nbsp;</td><td class="day">1</td><td class="day">2</td><td class="day">3</td><td class="day">4</td><td class="day weekend">5</td><td class="day weekend">6</td></tr><tr class="daysrow"><td class="day wn">37</td><td class="day">7</td><td class="day">8</td><td class="day">9</td><td class="day">10</td><td class="day">11</td><td class="day weekend">12</td><td class="day weekend">13</td></tr><tr class="daysrow"><td class="day wn">38</td><td class="day">14</td><td class="day">15</td><td class="day">16</td><td class="day">17</td><td class="day">18</td><td class="day weekend">19</td><td class="day weekend">20</td></tr><tr class="daysrow"><td class="day wn">39</td><td class="day">21</td><td class="day">22</td><td class="day">23</td><td class="day">24</td><td class="day">25</td><td class="day selected today weekend">26</td><td class="day weekend">27</td></tr><tr class="daysrow"><td class="day wn">40</td><td class="day">28</td><td class="day">29</td><td class="day">30</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr><tr class="emptyrow"><td class="day wn">41</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr></tbody><tfoot><tr class="footrow"><td style="cursor: move;" class="ttip" colspan="8">Seleccionar fecha</td></tr></tfoot></table><div style="display: none;" class="combo"><div class="label">Ene</div><div class="label">Feb</div><div class="label">Mar</div><div class="label">Abr</div><div class="label">May</div><div class="label">Jun</div><div class="label">Jul</div><div class="label">Ago</div><div class="label">Sep</div><div class="label">Oct</div><div class="label">Nov</div><div class="label">Dic</div></div><div style="display: none;" class="combo"><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div></div></div>
					
					<table class="contenidotabla centrar">
						<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
						<tr>
							<td align="center">
								<fieldset>
									<legend>
										Actualizar fechas de inscripci&oacute;n y publicaci&oacute;n de resultados 
									</legend>
									<table class="formulario">
										<tr>
											<td>
												Fecha Inicial
											</td>
											<td>
												<input type='text' size='8' value='' name='fechaIni' id='fechaIni'/>(dd/mm/aaaa)
											</td>
										</tr>
										<tr>
											<td>
												Fecha Final
											</td>
											<td>
												<input type='text' size='8' value='                                                         ' name='fechaFin' id='fechaFin'/>(dd/mm/aaaa)
											</td>
										</tr>
										<tr>
											<td>
												Evento
											</td>
											<td colspan="2">
												<select name="evento">
												<option value='19'>19 Inscripci&oacute;n de Aspirantes</option>
												<option value='20'>20 Publicaci&oacute;n de Resultados</option>
												</select>
											</td>
										</tr>
										<script type="text/javascript">
											Calendar.setup({
												inputField:"fechaIni",
												ifFormat:"%d/%m/%Y",
												button:"fechaIni"
											})
											Calendar.setup({
												inputField:"fechaFin",
												ifFormat:"%d/%m/%Y",
												button:"fechaFin"
											})
										</script>
										<tr align='center'>
											<td colspan="16">
												<table class="tablaBase">
													<tr>
														
														<td align="center">
															<input type='hidden' name='nivel' value='<? echo $valor[3] ?>'>
															<input type='hidden' name='anio' value='<? echo $valor[1] ?>'>
															<input type='hidden' name='periodo' value='<? echo $valor[2] ?>'>
															<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
															<input type='hidden' name='actualizarFechasInscipciones' value='grabar'>
															<input value="Actualizar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
														</td>
														<!--td align="center">
															<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
															<input type="submit" name="notdef" value="Calcular Acumulado">
														</td>
														<td align="center">
															<input type='hidden' name='nivel' value='<? echo $valor[3] ?>'>
															<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
														</td-->
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
					<table class="contenidotabla centrar">
						<tr>
							<td align="center">
								<fieldset>
									<legend>
										Fechas para inscripci&oacute;n y publicaci&oacute;n de resultados 
									</legend>
									<table class="formulario" align="center">
										<tr class="cuadro_color">
											<td class="cuadro_plano centrar">C&Oacute;D. EVENTO</td>
											<td class="cuadro_plano centrar">NOMBRE DEL EVENTO</td> 
											<td class="cuadro_plano centrar">FECHA INICIAL</td>
											<td class="cuadro_plano centrar">FECHA FINAL</td>
										</tr>
										<?
										for($i=0; $i<=$total-1; $i++)
										{
											echo '<tr>';
											echo "<td>".$resultadoEventos[$i][0]."</td>";
											echo "<td>".$resultadoEventos[$i][1]."</td>";
											echo "<td>".$resultadoEventos[$i][2]."</td>";
											echo "<td>".$resultadoEventos[$i][3]."</td>";
											echo '</tr>';
										}
										?>
									</table>
								</fieldset>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?
	}
	
	function actualizarFechasIns($configuracion)
	{
		unset($valor);
		$valor[3]=$_REQUEST['nivel'];
		$valor[4]=$_REQUEST['evento'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "periodoacad",$valor);
		$resultadoPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

		$valor[1]=$resultadoPer[0][0];
		$valor[2]=$resultadoPer[0][1];
		$valor[6]=$_REQUEST['fechaIni'];
		$valor[7]=$_REQUEST['fechaFin'];

		if($_REQUEST['fechaIni']=='' || $_REQUEST['fechaFin']=='')
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='Los campos de fecha inicial y fecha final, no deben estar vac&iacute;os.!!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
			exit;
		}
		//echo "MMM";
		$registro1=explode('/',$_REQUEST['fechaIni']);
		$dia1=$registro1[0];
		$mes1=$registro1[1];
		$anno1=$registro1[2];
		$esta_fecha1=strtotime($mes1."/".$dia1."/".$anno1)+($suma*24*60*60);

		$registro2=explode('/',$_REQUEST['fechaFin']);
		$dia2=$registro2[0];
		$mes2=$registro2[1];
		$anno2=$registro2[2];
		$esta_fecha2=strtotime($mes2."/".$dia2."/".$anno2)+($suma*24*60*60);

		$fechaIn=$esta_fecha1;
		$fechaFi=$esta_fecha2;
		
		$confec = "SELECT TO_CHAR(SYSDATE, 'dd/mm/yyyy') FROM dual";
		$rows=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confec, "busqueda");
		$registro3=explode('/',$rows[0][0]);
		$dia3=$registro3[0];
		$mes3=$registro3[1];
		$anno3=$registro3[2];
		$fechahoy =strtotime($mes3."/".$dia3."/".$anno3)+($suma*24*60*60);

		//$fechahoy =$rows[0][0];
		
		/*if($fechaIn<$fechahoy || $fechaFi<$fechahoy)
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='La fecha inicial o final no pueden ser menor a la fecha actual!!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
			exit;
		}*/
 
		if($fechaIn>$fechaFi)
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='La fecha inicial no puede ser mayor a la fecha final!!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
			exit;
		}
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultarAccaleventos",$valor);
		$resultadoEventos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$total=count($resultadoEventos);
		
		$cierto=0;
		for($i=0; $i<=$total; $i++)
		{
			if($resultadoEventos[$i][0]==$valor[4])
			{
				$cierto=1;
			}
		}
		if($cierto==1)
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "modificarFechasEvento",$valor);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			
			if($resultado==TRUE)
			{
				$this->redireccionarInscripcion($configuracion,"registroExitosoEventos",$valor);
			}
			else
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena='El registro no se pudo actualizar, revise que que se hayan realizado todos los pasos correctamente e intente de nuevo.!!';
				$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
				alerta::sin_registro($configuracion,$cadena);
			}  
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertarFechasEvento",$valor);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");

			if($resultado==TRUE)
			{
				//$this->redireccionarInscripcion($configuracion,"registroExitosoEventos",$valor);
			}
			else
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena='El registro no se pudo actualizar, revise que que se hayan realizado todos los pasos correctamente e intente de nuevo.!!';
				$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
				alerta::sin_registro($configuracion,$cadena);
			}  
		}
	}
	
 /*_________________________________________________________________________________________________
		
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
				$variable="pagina=registro_plan_trabajo";
				$variable.="&opcion=mensajes";
				$variable.="&mensaje=".$valor[9];
				$variable.="&valor=".$valor[1];
				$variable.="&clave=".$valor[2];
				$variable.="&nivel=".$valor[10];
				break;
			case "formgrado":
				$variable="pagina=registro_plan_trabajo";
				$variable.="&nivel=".$valor[10];
				break;
			case "registroExitosoEventos":
				$variable="pagina=adminAdmisiones";
				$variable.="&opcion=adminFechasInsRes";
				$variable.="&nivel=".$valor[3];
				break;
							
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

