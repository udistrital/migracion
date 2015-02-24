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

class funciones_adminAdmisiones extends funcionGeneral
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
		$this->accesoOracle=$this->conectarDB($configuracion,"admisiones");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		//echo "mmm".$this->usuario;
		
		$this->formulario="admin_reciboAdmisiones";
		//$this->verificar="control_vacio(".$this->formulario.",'descripcion')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'aplicacion')";
		//$this->verificar.="&& longitud_cadena(".$this->formulario.",'fecha',3)";
		//$this->verificar.="&& verificar_correo(".$this->formulario.",'descripcion')";
		
	}
	
	function nuevoRegistro($configuracion,$conexion)
	{
		if($this->usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$registroUsuario=$this->verificarUsuario();
		
		$contador=0;	
		$tab=0;
		$valor[3]=$_REQUEST['nivel'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "periodoacad",$valor);
		$resultadoPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$valor[1]=$resultadoPer[0][0];
		$valor[2]=$resultadoPer[0][1];
		
			?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
								<td class="cuadro_brown" >
									<lu>
										<li>
										Haga Click en la imagen del pdf para consultar los recibos de cada uno de los Proyectos Curriculares que aparecen en la lista.
										</li>
									</lu>
								</td>
							</tr>
						</table>
						<br>
						<table class="formulario" align="center">
							<tr  class="bloquecentralencabezado">
								<td colspan="2" align="center">
									<p><span class="texto_negrita">RECIBOS DE PAGO DE MATR&Iacute;CULA ADMISIONES PERIODO <? echo $valor[1].' - '.$valor[2]; ?></span></p>
								</td>
							</tr>
							<tr>
								<td colspan="2" rowspan="1"><br><hr class="hr_subtitulo"></td>
							</tr>
							<tr>
								<td colspan="2">
									
									<table class="contenidotabla" align="center">
										<?
										include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
										$cripto=new encriptar();
										$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
										setlocale(LC_MONETARY, 'en_US');
										  
										$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "carreras","");
										$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
										$total=count($resultado);
										echo "<tr>
										<td class='cuadro_color'>C&oacute;digo de la Carrera</td>
										<td class='cuadro_color'>Nombre de la Carrera</td>
										<td class='cuadro_color'>Consultar recibos</td>
										</tr>";
										for($i=0; $i<=$total-1; $i++)
										{
											echo '<tr>';
											echo "<td>".$resultado[$i][0]."</td>";
											echo "<td>".$resultado[$i][1]."</td>";
											echo "<td>";
												$valor[0]=$resultado[$i][0];
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "cuentaRecibos",$valor);
												$resultadoCuentaRecibos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												
												if($resultadoCuentaRecibos[0][0]==0)
												{
													echo "Recibos generados: ".$resultadoCuentaRecibos[0][0];
												}
												else
												{
													?><a href="<?
													$variable="pagina=imprimirFacturaAdm";
													//Codigo del Estudiante
													$variable.="&opcion=imprimir";
													$variable.="&no_pagina=true";
													$variable.="&carrera=".$resultado[$i][0];
													$variable.="&periodo=".$valor[3];
													$variable=$cripto->codificar_url($variable,$configuracion);
													echo $indice.$variable;		
													?>">
													<img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfito.png"?>" />
													Consultar PDF
													<?
													echo " (Recibos generados: ".$resultadoCuentaRecibos[0][0].") ";
													?></a><?
												}
											echo "</td>";
											echo '</tr>';
										}
										
										if(is_array($resultado))
										{
											//echo "Usuario: ".$resultado[0][1];
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
											<td  class="centrar texto_negrita" colspan="3">
												<?$fecha = time (); echo "Fecha: ". date ( "d/m/Y", $fecha ); ?>
											</td>
										</tr>
									</table>
								
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table>
		<?
	}
	
	//Consulta los recibos por Credencial
	function reciboCredencial($configuracion,$conexion)
	{
		$valor[3]=$_REQUEST['nivel'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "periodoacad",$valor);
		$resultadoPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$valor[1]=$resultadoPer[0][0];
		$valor[2]=$resultadoPer[0][1];
		//echo "mmmm".
		?>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown">
								<ul>
									<li>Para consultar un recibo, digite el n&uacute;mero de la Credencial y haga click en "Consultar".</li>
								</ul>
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td class="" align="center">
								<p><span class="texto_negrita">RECIBOS DE PAGO ADMISIONES PERIODO <?echo $valor[1].'-'.$valor[2];?></span></p>
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
												<font color="red">*</font>No. de Credencial:
											</td>
											<td>
												<input maxlength="10" value="" size="10" tabindex="<? echo $tab++ ?>" name="credencial"><br>
											</td>
										</tr>
										
										<tr align='center'>
											<td colspan="16">
												<table class="tablaBase">
													<tr>
														
														<td align="center">
															<input type='hidden' name='nivel' value='<? echo $valor[3] ?>'>
															<input type='hidden' name='anio' value='<? echo $valor[1] ?>'>
															<input type='hidden' name='periodo' value='<? echo $valor[2] ?>'>
															<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
															<input type='hidden' name='consultar' value='grabar'>
															<input value="Consultar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
														</td>
														<!--td align="center">
															<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
															<input type="submit" name="notdef" value="Calcular Acumulado">
														</td-->
														<td align="center">
															<input type='hidden' name='usuario' value='<? echo $valor[0]?>'>
															<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
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
	
	function reciboFecha($configuracion,$conexion)
	{  
		$valor[3]=$_REQUEST['nivel'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "periodoacad",$valor);
		$resultadoPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$valor[1]=$resultadoPer[0][0];
		$valor[2]=$resultadoPer[0][1];
		//echo "mmmm".
		?>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<div style="position: absolute; display: none; left: 158px; top: 32px;" class="calendar"><table style="visibility: visible;" cellpadding="0" cellspacing="0"><thead><tr><td class="button" colspan="1"><div unselectable="on">?</div></td><td style="cursor: move;" class="title" colspan="6">Septiembre, 2009</td><td class="button" colspan="1"><div unselectable="on">×</div></td></tr><tr class="headrow"><td class="button nav" colspan="1"><div unselectable="on">«</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8249;</div></td><td class="button" colspan="4"><div unselectable="on">Hoy</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8250;</div></td><td class="button nav" colspan="1"><div unselectable="on">»</div></td></tr><tr class="daynames"><td class="name wn">sem</td><td class="day name">Lun</td><td class="day name">Mar</td><td class="day name">Mie</td><td class="day name">Jue</td><td class="day name">Vie</td><td class="name day weekend">S&aacute;b</td><td class="name day weekend">Dom</td></tr></thead><tbody><tr class="daysrow"><td class="day wn">36</td><td class="emptycell">&nbsp;</td><td class="day">1</td><td class="day">2</td><td class="day">3</td><td class="day">4</td><td class="day weekend">5</td><td class="day weekend">6</td></tr><tr class="daysrow"><td class="day wn">37</td><td class="day">7</td><td class="day">8</td><td class="day">9</td><td class="day">10</td><td class="day">11</td><td class="day weekend">12</td><td class="day weekend">13</td></tr><tr class="daysrow"><td class="day wn">38</td><td class="day">14</td><td class="day">15</td><td class="day">16</td><td class="day">17</td><td class="day">18</td><td class="day weekend">19</td><td class="day weekend">20</td></tr><tr class="daysrow"><td class="day wn">39</td><td class="day">21</td><td class="day">22</td><td class="day">23</td><td class="day">24</td><td class="day">25</td><td class="day selected today weekend">26</td><td class="day weekend">27</td></tr><tr class="daysrow"><td class="day wn">40</td><td class="day">28</td><td class="day">29</td><td class="day">30</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr><tr class="emptyrow"><td class="day wn">41</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr></tbody><tfoot><tr class="footrow"><td style="cursor: move;" class="ttip" colspan="8">Seleccionar fecha</td></tr></tfoot></table><div style="display: none;" class="combo"><div class="label">Ene</div><div class="label">Feb</div><div class="label">Mar</div><div class="label">Abr</div><div class="label">May</div><div class="label">Jun</div><div class="label">Jul</div><div class="label">Ago</div><div class="label">Sep</div><div class="label">Oct</div><div class="label">Nov</div><div class="label">Dic</div></div><div style="display: none;" class="combo"><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div></div></div>
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown">
								<ul>
									<li>Para consultar un recibo, digite el n&uacute;mero de la Credencial y haga click en "Consultar".</li>
								</ul>
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td class="" align="center">
								<p><span class="texto_negrita">RECIBOS DE PAGO ADMISIONES PERIODO <?echo $valor[1].'-'.$valor[2];?></span></p>
							</td>
						</tr>
					</table>
					<table class="contenidotabla centrar">
						<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
						<tr>
							<td align="center">
								<fieldset>
									<legend>
										Consultar por fecha de generaci&oacute;n
									</legend>
									<table class="formulario">
										<tr>
											<td>
												<font color="red">*</font>Fecha
											</td>
											<td>
												<input type='text' size='8' value='' name='fecha' id='fecha'/>(dd/mm/aaaa)
											</td>
										</tr>
										<script type="text/javascript">
											Calendar.setup({
												inputField:"fecha",
												ifFormat:"%d/%m/%Y",
												button:"fecha"
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
															<input type='hidden' name='consultar' value='grabar'>
															<input value="Consultar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
														</td>
														<!--td align="center">
															<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
															<input type="submit" name="notdef" value="Calcular Acumulado">
														</td-->
														<td align="center">
															<input type='hidden' name='usuario' value='<? echo $valor[0]?>'>
															<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
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

	function consultarRegistro($configuracion)
	{
		if(isset($_REQUEST['fecha']))
		{
			$valor[1]=$_REQUEST['anio'];
			$valor[2]=$_REQUEST['periodo'];
			$valor[4]=$_REQUEST['credencial'];
			$valor[5]=$_REQUEST['fecha'];
			$valor[3]=$_REQUEST['nivel'];
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "recibosActualFecha",$valor);
			$resultadoFecha=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			
			if(is_array($resultadoFecha))
			{
				$valor[1]=$_REQUEST['anio'];
				$valor[2]=$_REQUEST['periodo'];
				$valor[4]=$_REQUEST['credencial'];
				$valor[5]=$_REQUEST['fecha'];
				$valor[6]=$resultadoFecha[0][0];
				$valor[3]=$_REQUEST['nivel'];
				$this->redireccionarInscripcion($configuracion,"consultarPDFporFecha",$valor);
			} 
		}
		if(isset($_REQUEST['credencial']))
		{
			$valor[1]=$_REQUEST['anio'];
			$valor[2]=$_REQUEST['periodo'];
			$valor[4]=$_REQUEST['credencial'];
			$valor[5]=$_REQUEST['fecha'];
			$valor[3]=$_REQUEST['nivel'];
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "recibosActualCredencial",$valor);
			$resultadoCred=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			
			if(is_array($resultadoCred))
			{
				$valor[1]=$_REQUEST['anio'];
				$valor[2]=$_REQUEST['periodo'];
				$valor[4]=$_REQUEST['credencial'];
				$valor[5]=$_REQUEST['fecha'];
				$valor[6]=$resultadoCred[0][0];
				$valor[3]=$_REQUEST['nivel'];
				$this->redireccionarInscripcion($configuracion,"consultarPDFporCredencial",$valor);
			}
			else
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$regresar="<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-1)' style='cursor:pointer;'>";
				$regresar.='<img src="';
				$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
				$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
				$regresar.= '<br>Regresar</a></center>';
				$cadena='El n&uacute;mero de Credencial digitado, no existe.';
				alerta::sin_registro($configuracion,$regresar,$cadena);
			}
		}
	}
	
	function consultarPDFporFecha($configuracion,$conexion)
	{
		if($this->usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$registroUsuario=$this->verificarUsuario();
		
		$valor[1]=$_REQUEST['anio'];
		$valor[2]=$_REQUEST['periodo'];
		$valor[4]=$_REQUEST['credencial'];
		$valor[5]=$_REQUEST['fecha'];
		$valor[6]=$_REQUEST['totalRegistros'];
		$valor[3]=$_REQUEST['nivel'];
		
			?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
								<td class="cuadro_brown" >
									<lu>
										<li>
										Haga Click en la imagen del pdf para consultar los recibos.
										</li>
									</lu>
								</td>
							</tr>
						</table>
						<br>
						<table class="formulario" align="center">
							<tr  class="bloquecentralencabezado">
								<td colspan="2" align="center">
									<p><span class="texto_negrita">RECIBOS DE PAGO DE MATR&Iacute;CULA ADMISIONES PERIODO <? echo $valor[1].' - '.$valor[2]; ?></span></p>
								</td>
							</tr>
							<tr>
								<td colspan="2" rowspan="1"><br><hr class="hr_subtitulo"></td>
							</tr>
							<tr>
								<td colspan="2">
									
									<table class="contenidotabla" align="center">
										<?
										include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
										$cripto=new encriptar();
										$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
										setlocale(LC_MONETARY, 'en_US');
										echo "<tr>
										<td class='cuadro_color'>Recibos generados el ".$valor[5]."</td>
										</tr>";
										echo '<tr>';
											echo "<td>";
												if(!isset($_REQUEST['totalRegistros']))
												{
													echo "Recibos generados: 0";
												}
												else
												{
													?><a href="<?
													$variable="pagina=imprimirFacturaAdm";
													//Codigo del Estudiante
													$variable.="&opcion=imprimir";
													$variable.="&no_pagina=true";
													$variable.="&fecha=".$valor[5];
													$variable.="&periodo=".$valor[3];
													$variable=$cripto->codificar_url($variable,$configuracion);
													echo $indice.$variable;		
													?>">
													<img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfito.png"?>" />
													Consultar PDF
													<?
													echo " (Recibos generados el " .$valor[5] .", No. de recibos: ".$_REQUEST['totalRegistros'].") ";
													?></a><?
												}
											echo "</td>";
											echo '</tr>';
											
										
										 //echo "Usuario :".$registroUsuario[0][1]
										?>
										</td>
											<td  class="centrar texto_negrita" colspan="3">
												<?$fecha = time (); echo "Fecha: ". date ( "d/m/Y", $fecha ); ?>
											</td>
										</tr>
									</table>
								
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table>
		<?
	}
	
	 //Administra las fechas para pago de matrícula de los aspirantes admitidos. 
	function administracionFechasRecibos($configuracion)
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
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaFechasPago",$valor);
		$resultadoFechas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		//echo $resultadoFechas[0][0]."<br>";
		//echo $resultadoFechas[0][1]."<br>";
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
					<p><span class="texto_negrita">FECHAS PARA PAGO DE MATR&Iacute;CULA ADMISIONES PERIODO <?echo $valor[1].'-'.$valor[2];?></span></p>
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
										Fechas para pago de matr&iacute;cula 
									</legend>
									<table class="formulario">
										<tr>
											<td>
												Fecha Ordinaria
											</td>
											<td>
												<input type='text' size='8' value='<? echo $resultadoFechas[0][0]?>' name='fechaOrd' id='fechaOrd'/>(dd/mm/aaaa)
											</td>
										</tr>
										<tr>
											<td>
												Fecha Extraordinaria
											</td>
											<td>
												<input type='text' size='8' value='<? echo $resultadoFechas[0][1]?>' name='fechaExt' id='fechaExt'/>(dd/mm/aaaa)
											</td>
										</tr>
										<script type="text/javascript">
											Calendar.setup({
												inputField:"fechaOrd",
												ifFormat:"%d/%m/%Y",
												button:"fechaOrd"
											})
											Calendar.setup({
												inputField:"fechaExt",
												ifFormat:"%d/%m/%Y",
												button:"fechaExt"
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
															<input type='hidden' name='modificar' value='grabar'>
															<input value="Modificar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
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
				</td>
			</tr>
		</table>
		<?
	}

	//Actualiza las fechas de pago de matrícula para admisiones
	function modificarFechasPago($configuracion)
	{
		if($this->usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$registroUsuario=$this->verificarUsuario();
		$valor[1]=$_REQUEST['anio'];
		$valor[2]=$_REQUEST['periodo'];
		$valor[3]=$_REQUEST['nivel'];
		$valor[6]=$_REQUEST['fechaOrd'];
		$valor[7]=$_REQUEST['fechaExt'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "modificarFechasPago",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
		
		if($resultado==TRUE)
		{
			$this->redireccionarInscripcion($configuracion,"registroExitoso",$valor);
		}
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$regresar="<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-1)' style='cursor:pointer;'>";
			$regresar.='<img src="';
			$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
			$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
			$regresar.= '<br>Regresar</a></center>';
			$cadena='El registro no se pudo guardar, revise que que se hayan realizado todos los pasos correctamente e intente de nuevo.!!';
			alerta::sin_registro($configuracion,$regresar,$cadena);	
		}
	}
	
	
/*________________________________________________________________________________________________
		
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
		$configuracion=$configuracion?$_REQUEST['item']:'';
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosUsuarios",$this->usuario);
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
			case "consultarPDFporFecha":
				$variable="pagina=admin_impresionAdm";	
				$variable.="&opcion=verPDFporFecha";
				$variable.="&anio=".$valor[1];
				$variable.="&periodo=".$valor[2];
				$variable.="&credencial=".$valor[4];
				$variable.="&fecha=".$valor[5];
				$variable.="&totalRegistros=".$valor[6];
				$variable.="&nivel=".$valor[3];
				break;
			case "consultarPDFporCredencial":
				$variable="pagina=imprimirFacturaAdm";	
				$variable.="&opcion=imprimir";
				$variable.="&credencial=".$valor[4];
				$variable.="&no_pagina=true";
				$variable.="&periodo=".$valor[3];
				break;
			case "registroExitoso":
				$variable="pagina=admin_impresionAdm";
				$variable.="&opcion=adminFechasRecibos";
				$variable.="&anio=".$valor[1];
				$variable.="&periodo=".$valor[2];
				$variable.="&nivel=".$valor[3];
				break;
			case "registroExitosoEventos":
				$variable="pagina=admin_impresionAdm";
				$variable.="&opcion=adminFechasInsRes";
				$variable.="&anio=".$valor[1];
				$variable.="&periodo=".$valor[2];
				$variable.="&nivel=".$valor[3];
				break;
			case "formgrado":
				$variable="pagina=admin_impresionAdm";
				$variable.="&opcion=adminFechasRecibos";
				$variable.="&anio=".$valor[1];
				$variable.="&periodo=".$valor[2];
				$variable.="&nivel=".$valor[3];
				break;
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

