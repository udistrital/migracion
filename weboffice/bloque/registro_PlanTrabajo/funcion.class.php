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

class funciones_registro_PlanTrabajo extends funcionGeneral
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
		$this->accesoOracle=$this->conectarDB($configuracion,"docente");
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="registro_PlanTrabajo";
		//$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'lugar')";
		
	}
	
	//Arma la matriz con los días de la semana y las horas.
	function registrarPlanTrabajo($configuracion)
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
		$calendario=$this->validaCalendario('',$configuracion);
		//$observaciones=$this->notasobservaciones();guardarNotas
		
		$estado=$_REQUEST['nivel'];
		$valor[10]=$estado;

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
				
		$valor[0]=$usuario;
		$valor[1]=$ano;
		$valor[2]=$per;
					
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
		$rowfechoy=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$fechahoy = $rowfechoy[0][0];
				
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosUsuario",$valor);
		$datosusuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"dia",$valor);
		$registrodia=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentadias=count($registrodia);
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"hora",$valor);
		$registrohora=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentahoras=count($registrohora);
		?>
		
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown" colspan="5">
								<br>
								<ul>
									<li> Para registrar una actividad haga clik en un cuadro disponible, debe tener el siguiente mensaje "Click aqu&iacute; para registrar".</li>
									<li> Para borrar una actividad, haga click sobre el bot&oacute;n <img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/boton_borrar.png" border="0">.</li>
									<li> La carga acad&eacute;mica no se podr&aacute; eliminar.</li>
									<li> PL (Planta), VE (Vinculación especial)</li>
								</ul>
							</td>
						</tr>
						<tr>
							<td>
								<p><a href="https://condor.udistrital.edu.co/appserv/manual/plan_trabajo.pdf">
								<img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfito.png"?>" />
								Ver Manual de Usuario.</a></p>
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td class="" colspan="5" align="center">
								<p><span class="texto_negrita">PLAN DE TRABAJO</span></p>
							</td>
						</tr>
					</table>
					
					<table class="contenidotabla centrar">
						<tr>
							<td>
								<fieldset>
									<legend>
										Datos del Docente
									</legend>
									<table class="contenidotabla">
										<tr>
											<td width='20%'>
												Docente:
											</td>
											<td width='30%' align="left">
												<? echo $datosusuario[0][1].' '.$datosusuario[0][0];?>
											</td>
											<td width='25%'>
												<font size="4">Per&iacute;odo:</font>
											</td>
											<td width='25%' align="left">
												<? echo '<font size="4">'.$ano.' - ' .$per.'</font>';?>
											</td>
										</tr>
										<tr>
											<td width='20%'>
												Identificaci&oacute;n:
											</td>
											<td width='30%' align="left">
												<? echo $datosusuario[0][2];?>
											</td>
											<td width='25%'>
												
											</td>
											<td width='25%'>
												
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
						<tr>
							<td colspan="15" align="center">
								<fieldset>
									<legend>
										Inserci&oacute;n de actividades
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
													$valor[10]=$estado;
													
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cargalectiva",$valor);
													$registrocarga=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cargaactividades",$valor);
													$registroactividad=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													
													$title_cargaLectiva= 'Lectiva: '. $registrocarga[0][5].'<br>Sede: '.$registrocarga[0][8].'<br> Sal&oacute;n: '.$registrocarga[0][10].'<br> Vinculaci&oacute;n: '.$registrocarga[0][11];
													$title_actividad= 'Actividad: '. $registroactividad[0][3].'<br>Sede: '.$registroactividad[0][8].'<br> Sal&oacute;n: '.$registroactividad[0][9].'<br> Vinculaci&oacute;n: '.$registroactividad[0][17];
													$title_celdaVacia= 'D&iacute;a: '. $registrodia[$j][1].'<br>Hora: '.$registrohora[$i][1];
													
													if(is_array($registrocarga))
													{
														?>
														<td class="cuadro_planito centrar" onmouseover="toolTip('<BR><?echo $title_cargaLectiva;?>&nbsp;&nbsp;&nbsp;',this)" >
														<div class="centrar">
															<span id="toolTipBox" width="300" ></span>
														</div>
														<?
														echo '* '.$registrocarga[0][4].'<br>
														* <b>'.$registrocarga[0][9].'</b><br>
														* '.$registrocarga[0][10].'<br>
														* '.$registrocarga[0][12].'
														</td>';
														
													}
													elseif(is_array($registroactividad))
													{
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																														
														setlocale(LC_MONETARY, 'en_US');
														$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
														$cripto=new encriptar();
														?>
														<td class="cuadro_planito centrar" onmouseover="toolTip('<BR><?echo $title_actividad;?>&nbsp;&nbsp;&nbsp;',this)" >
														<div class="centrar">
															<span id="toolTipBox" width="300" ></span>
														</div>
														<?
														echo '* '.$registroactividad[0][4].'<br>
														* <strong>'.$registroactividad[0][7].'</strong><br>
														* '.$registroactividad[0][9].'<br>
														* '.$registroactividad[0][18];
															echo "<a href='";
															$variable="pagina=registro_plan_trabajo";
															$variable.="&opcion=borrar";
															$variable.="&usuario=".$valor[0];
															$variable.="&ano=".$valor[1];
															$variable.="&per=".$valor[2];
															$variable.="&dia=".$valor[3];
															$variable.="&hora=".$valor[4];
															$variable.="&nivel=".$valor[10];
															//$variable.="&no_pagina=true";
															$variable=$cripto->codificar_url($variable,$configuracion);
															echo $indice.$variable."'";
															echo "title='Haga Click aqu&iacute; para eliminar esta actividad.'>";
															?>
															<center><img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/boton_borrar.png" border="0"></center>
															</a>
														
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
														<?
															
															echo "<a href='";
															$variable="pagina=registro_plan_trabajo";
															$variable.="&opcion=actividades";
															$variable.="&usuario=".$valor[0];
															$variable.="&ano=".$valor[1];
															$variable.="&per=".$valor[2];
															$variable.="&dia=".$valor[3];
															$variable.="&hora=".$valor[4];
															$variable.="&nivel=".$valor[10];
															//$variable.="&no_pagina=true";
															$variable=$cripto->codificar_url($variable,$configuracion);
															echo $indice.$variable."'";
															echo "title='".(isset($texto_ayuda)?$texto_ayuda:'')."'>";
															?>
															<center><img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/registrar.png" border="0"></center>
															</a>
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
						<tr align='center'>
							<td colspan="1">
								<table class="contenidotabla">
									
									<tr>
										<td align="center" colspan="3">
										<fieldset>
											<legend>
												Carga Lectiva
											</legend>
											<?
											$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "cuentaActividad",$valor);
											$QryHor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													
											$i=0;
											while(isset($QryHor[$i][0]))
											{
												$tvi_cod=$QryHor[$i][4];
												$tipoVinculacion[(int)$tvi_cod]=$QryHor[$i][0];
												$NroAct[(int)$tvi_cod] = $QryHor[$i][1];
												$NroLec[(int)$tvi_cod] = $QryHor[$i][2];
												$NroTip[(int)$tvi_cod] = $QryHor[$i][3];
											$i++;
											}
											?>
											<table class="contenidotabla">
											<thead><td class="cuadro_plano centrar"><b>TIPO DE VINCULACION</b> </td><td class="cuadro_plano centrar"><b>TOTAL HORAS LECTIVAS</b></td><td class="cuadro_plano centrar">.</td></thead>
											<?
											$contarTipVin = "SELECT count(*) FROM actipvin";
											$registroTipVin=$this->ejecutarSQL($configuracion, $this->accesoOracle, $contarTipVin, "busqueda");
											$totalTipVin=$registroTipVin[0][0];
											
											for($k=0;$k<$totalTipVin;$k++)
											{
												if(!is_null((isset($tipoVinculacion[$k])?$tipoVinculacion[$k]:null)))
												{
													echo '<tr><td class="cuadro_plano centrar"> '.$tipoVinculacion[$k].'</td><td class="cuadro_plano centrar"> '.$NroLec[$k].'</td></td><td class="cuadro_plano centrar"><b>'.$NroTip[$k].'</b></td></tr>';
												}
											}
											?>
											</table>
										</fieldset>
										</td>
									</tr>
									<tr>
										<td>
										<fieldset>
											<legend>
												Actividades
											</legend>
											
											<table class="contenidotabla">
											<tr><td class="cuadro_plano centrar"><b>TIPO DE VINCULACION</b> </td><td class="cuadro_plano centrar"><b>TOTAL HORAS ACTIVIDADES</b></td><td class="cuadro_plano centrar"><b>TOTAL (LECTIVAS + ACTIVIDADES)</b></td><td class="cuadro_plano centrar">.</td></tr>
											<?
											for($k=0;$k<$totalTipVin;$k++){
												if(!is_null((isset($tipoVinculacion[$k])?$tipoVinculacion[$k]:null))){
													echo '<tr><td class="cuadro_plano centrar"> '.$tipoVinculacion[$k].'</td><td class="cuadro_plano centrar"> '.$NroAct[$k].'</td><td class="cuadro_plano centrar">'.((int)$NroAct[$k] + (int)$NroLec[$k]).'</td></td><td class="cuadro_plano centrar"><b>'.$NroTip[$k].'</b></td></tr>';
												}
											}
											?>
											</table>
										</fieldset>
										</td>
									</tr>
									<tr>
										<td>
										<fieldset>
											<legend>
												Observaciones:
											</legend>
											<?
											$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"consultaObservacion",$valor);
											$registroObs=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                                                                                        $tab=0;
											?>
											<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
											<table class="contenidotabla">
												<tr>
													<td>
														<textarea id='observacion' name='observacion' cols='98' rows='2' tabindex='<? echo $tab++ ?>' ><? echo $registroObs[0][3]; ?></textarea>
													</td>
												</tr>
												<tr align='center'>
													<td colspan="3">
														<table class="tablaBase">
															<tr>
																<td align="center">
																	<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
																	<input type='hidden' name='usuario' value='<? echo $valor[0] ?>'>
																	<input type='hidden' name='anio' value='<? echo $valor[1] ?>'>
																	<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
																	<input type='hidden' name='per' value='<? echo $valor[2] ?>'>
																	<?
																	if(!is_array($registroObs))
																	{
																		?>
																		<input type='hidden' name='grabobs' value='nuevo'>
																		<input value="Grabar observaci&oacute;n" name="aceptar" type="submit"/><br>								
																		<?
																	}
																	else
																	{
																	?>
																		<input type='hidden' name='modbobs' value='nuevo'>
																		<input value="Modificar observaci&oacute;n" name="aceptar" type="submit"/><br>								
																	<?
																	}
																	?>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</fieldset>
										</td>
									</tr>
									<tr>
										<td align="center" colspan="3">
											<?
											include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
											include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
											$total=count((isset($resultado)?$resultado:''));
												
											setlocale(LC_MONETARY, 'en_US');
											$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
											$cripto=new encriptar();
											
											echo "<a href='";
											$variable="pagina=registro_plan_trabajo";
											$variable.="&opcion=reportes";
											$variable.="&nivel=".$valor[10];
											$variable.="&usuario=".$valor[0];
											//$variable.="&no_pagina=true";
											$variable=$cripto->codificar_url($variable,$configuracion);
											echo $indice.$variable."'";
											echo "title='Haga Click aqu&iacute; para imprimir el reporte del plan trabajo'>";
											?>
											<center><img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/reporte.png" border="0"></center>
											Ir al reporte del plan trabajo
											</a>
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
		<?
	}
	
	//Muestra el formulario donde se seleccionan las actividades, sedes, salones y tipo de vinculación.
	function registroActividades($configuracion)
	{
            ?>
<head>	
<script languaje="javascript">
function limpiarform(){
document.<? echo $this->formulario?>.reset();
}
</script>
</head>
<body onLoad="limpiarform()"></body>
	
            <?
            
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
		$estado=$_REQUEST['nivel'];
		$valor[10]=$estado;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$valor[0]=$usuario;
		$valor[1]=$ano;
		$valor[2]=$per;
			
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
		$rowfechoy=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$fechahoy = $rowfechoy[0][0];
				
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosUsuario",$valor);
		$datosusuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actividades",$usuario);
		$registroact=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentaact=count($registroact);
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"hora",$usuario);
		$registrohora=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentahoras=count($registrohora);
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
											<li> Seleccione el tipo de vinculaci&oacute;n (si tiene m&aacute;s de una vinculaci&oacute;n), la actividad, la sede, el edificio y el sal&oacute;n y haga click en el bot&oacute;n "Grabar".</li>
                                                                                        <li> Si al seleccionar una sede y en la lista de salones no aparece alg&uacute;n sal&oacute;n, puede ser porque &eacute;ste ya ha sido ocupado.</li>
											<li> Recuerde que todos los campos son obligatorios; luego de seleccionar una sede se despliega la lista de edificios de esa sede y al seleccionar un edificio aparece la lista de sus salones.</li>
										</ul>
									</td>
								</tr>
							
							<tr class="texto_subtitulo">
								<td class="" colspan="5" align="center">
									<p><span class="texto_negrita">PLAN DE TRABAJO</span></p>
								</td>
							</tr>
						</table>
						
						<table class="contenidotabla centrar">
							<tr>
								<td>
									<fieldset>
										<legend>
											Datos del Docente
										</legend>
										<table class="contenidotabla">
											<tr>
												<td width='20%'>
													Docente:
												</td>
												<td width='30%' align="left">
													<? echo $datosusuario[0][1].' '.$datosusuario[0][0];?>
												</td>
												<td width='25%'>
													Per&iacute;odo:
												</td>
												<td width='25%' align="left">
													<? echo $ano.' - ' .$per;?>
												</td>
											</tr>
											<tr>
												<td width='20%'>
													Identificaci&oacute;n:
												</td>
												<td width='30%' align="left">
													<? echo $datosusuario[0][2];?>
												</td>
												<td width='25%'>
													
												</td>
												<td width='25%'>
													
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
											Grabar actividades
										</legend>
										<table class="formulario">
											<tr>	
												<td>
													Tipo de vinculaci&oacute;n:
												</td>
												<td>
													<?
													include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
													$html=new html();
													$tab=0;
                                                                                                        $registro=array();
                                                                                                        
													$busqueda="SELECT distinct car_tip_vin,tvi_nombre";
													$busqueda.=" FROM acasperi ";
													$busqueda.=" INNER JOIN accursos ON cur_ape_ano=ape_ano AND cur_ape_per=ape_per";
													$busqueda.=" INNER JOIN achorarios ON cur_id=hor_id_curso";
													$busqueda.=" INNER JOIN accargas ON hor_id=car_hor_id";
													$busqueda.=" INNER JOIN actipvin ON car_tip_vin=tvi_cod";
													$busqueda.=" WHERE car_estado = 'A'";
													$busqueda.=" AND ape_estado='".$valor[10]."' ";
													$busqueda.=" AND car_doc_nro = ".$valor[0]." ";
													$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
                                                                                                        if(!is_array($resultado1)||empty($resultado1))
                                                                                                        {
                                                                                                            $busqueda="SELECT tvi_cod,tvi_nombre";
                                                                                                            $busqueda.=" FROM actipvin";
                                                                                                            $busqueda.=" WHERE tvi_estado = 'A'";
                                                                                                            $resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
                                                                                                        }
                                                                                                        if(is_array($resultado1)){
                                                                                                            foreach ($resultado1 as $key => $value) 
                                                                                                            { $registro1[$key][0]=$resultado1[$key][0];
													      $registro1[$key][1]=$resultado1[$key][1];
                                                                                                            }
                                                                                                        }
													$mi_cuadro=$html->cuadro_lista((isset($registro1)?$registro1:''),'vinculacion',$configuracion,1,3,FALSE,$tab++,"vinculacion",100);
																
													echo $mi_cuadro;
													?>
												</td>
											</tr>
                                                                                        <tr>
												<td>
													Actividad:
												</td>
												<td>
													<?
													include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
													$html=new html();
																			
													foreach ($registroact as $key => $value) 
													{ 
														$registro[$key][0]=$registroact[$key][0];
														$registro[$key][1]=$registroact[$key][1];
													}
													$mi_cuadro=$html->cuadro_lista($registro,'actividad',$configuracion,-1,3,FALSE,$tab++,"actividad",100);
																
													echo $mi_cuadro;
													?>
												</td>
											</tr>
											<tr>
												<td>
													Sede:
												</td>
												<td><?
													include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
													$html=new html();
													$valor[0]=$usuario;
													$valor[1]=$ano;
													$valor[2]=$per;
													$valor[3]=$_REQUEST['dia'];
													$valor[4]=$_REQUEST['hora'];
													
													$busqueda="SELECT ";
													$busqueda.="sed_id||'#'||'".$valor[1]."'||'#'||'".$valor[2]."'||'#'||'".$valor[3]."'||'#'||'".$valor[4]."', ";
													$busqueda.="sed_nombre ";
													$busqueda.="FROM ";
													$busqueda.="gesede ";
													$busqueda.="WHERE ";
													$busqueda.="sed_estado='A' ";
													$busqueda.="AND  ";
													$busqueda.="sed_id is not null  ";
													$busqueda.="ORDER BY sed_nombre";
													
													$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
													
													$configuracion["ajax_function"]="xajax_rescatarEdificio";
													$configuracion["ajax_control"]="sede";
													$registro=array();
													foreach ($resultado as $key => $value){ 
														 $registro[$key][0]=$resultado[$key][0];
													         $registro[$key][1]=$resultado[$key][1];
                                                                                                                 }
													$mi_cuadro=$html->cuadro_lista($registro,'sede',$configuracion,-1,3,FALSE,$tab++,"sede",100);
													
													echo $mi_cuadro;
												
												?></td>
											</tr>
											<tr>								
												<td>
													Edificio:
												</td>
												<td>
													<div id="divEdificio">Seleccione la Sede<?
//													$busqueda="SELECT ";
//													$busqueda.="edi_cod, ";
//													$busqueda.="edi_nombre ";
//													$busqueda.="FROM ";
//													$busqueda.="geedificio ";
//													$busqueda.="ORDER BY edi_cod DESC";
//													$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
//																					
//													$mi_cuadro=$html->cuadro_lista("",'edificio',$configuracion,-1,3,FALSE,$tab++,"edificio",100);
//													
//													echo $mi_cuadro;
												?></div>
												</td>
											</tr>
											<tr>								
												<td>
													Sal&oacute;n:
												</td>
												<td>
													<div id="divSalon"><?
								                                                                                                        
//                                                                                                        $busqueda="SELECT ";
//                                                                                                        $busqueda.="sal_id_espacio, ";
//                                                                                                        $busqueda.="sal_id_espacio||' '||sal_nombre ";
//                                                                                                        $busqueda.="FROM ";
//                                                                                                        $busqueda.="gesalones x ";
//                                                                                                        $busqueda.="WHERE ";
//                                                                                                        $busqueda.=" sal_id_espacio ='".(isset($_REQUEST['salon'])?$_REQUEST['salon']:'')."'";
//                                                                                                        					
//													$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
//																					
//													$mi_cuadro=$html->cuadro_lista($resultado,'salon',$configuracion,-1,3,FALSE,$tab++,"salon",200);
//													
//													echo $mi_cuadro;
												?></div>
												</td>
											</tr>											

											<?
											
											
												
											?>
										</table>
									</fieldset>	
								</td>
							</tr>
							<tr align='center'>
								<td colspan="16">
									<table class="tablaBase">
										<tr>
											
											<td align="center">
												<input type='hidden' name='usuario' value='<? echo $valor[0]?>'>
												<input type='hidden' name='anio' value='<? echo $valor[1] ?>'>
												<input type='hidden' name='per' value='<? echo $valor[2] ?>'>
												<input type='hidden' name='dia' value='<? echo $valor[3] ?>'>
												<input type='hidden' name='hora' value='<? echo $valor[4] ?>'>
												<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
												<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
												<input type='hidden' name='opcion' value='grabar'>
												<input value="Grabar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" /><br>
											</td>
											<td align="center">
												<input type='hidden' name='usuario' value='<? echo $valor[0]?>'>
												<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
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
	
	//Guarda los registros enviados desde el formulario en la base de datos.
	function guardarPlanTrabajo($configuracion)
	{
		$sede=explode('#',$_REQUEST['sede']);
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "codigoSede",$sede[0]);
		$QrySede=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$sede[0]=$QrySede[0][0];
		
		$valor[0]=$_REQUEST['usuario'];
		$valor[1]=$_REQUEST['anio'];
		$valor[2]=$_REQUEST['per'];
		$valor[3]=$_REQUEST['dia'];
		$valor[4]=$_REQUEST['hora'];
		$valor[5]=$_REQUEST['actividad'];
		$valor[6]=$sede[0];
		$valor[7]=$_REQUEST['salon'];
		$valor[8]=$_REQUEST['vinculacion'];
		$valor[10]=$_REQUEST['nivel'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "cuentaActividad",$valor);
		$QryHor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
				
		$i=0;
		while(isset($QryHor[$i][0]))
		{
			$tvi_cod=$QryHor[$i][4];
			$tipoVinculacion[(int)$tvi_cod]=$QryHor[$i][0];
			$NroAct[(int)$tvi_cod] = $QryHor[$i][1];
			$NroLec[(int)$tvi_cod] = $QryHor[$i][2];
			$NroTip[(int)$tvi_cod] = $QryHor[$i][3];
		$i++;
		}
		
		$NroLec=$NroLec[(int)$tvi_cod];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "totalPorActividad",$valor);
		$totalAct=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$NroCAct = $totalAct[0][0];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "intensidadActividad",$valor);
		$intensidad=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$IntAct = $intensidad[0][0];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "cuentaIntensidad",$valor);
		$cuentaIntensidad=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$TotHorAct = $cuentaIntensidad[0][0];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "cruceCarga",$valor);
		$RowCruCarLec=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$CruceCL = $RowCruCarLec[0][0];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "cruceActividad",$valor);
		$RowCruActividad=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$Cruce = $RowCruActividad[0][0];
		
		//Valida que no superen el 50% de horas lectivas
		if((int)$NroLec[(int)$_REQUEST['vinculacion']] > 0 && $_REQUEST['actividad'] == 2 && $_REQUEST['vinculacion'] <> "")
		{  //2 = preparacion de clase
			$PreClas =  $totalAct[0][0];
			$MitHorLec = $NroLec/2;
			
			if($PreClas >= $MitHorLec)
			{
				$valor[9]=2; //Mensaje 2
				//$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
		}
		
		//Valida que no lleguen variables vacías.	
		if($_REQUEST['usuario']=="" || 
		$_REQUEST['anio']=="" || 
		$_REQUEST['per']=="" || 
		$_REQUEST['actividad']== -1 || 
                $_REQUEST['sede']== "" ||
		$_REQUEST['sede']== -1 ||
                $_REQUEST['edificio']=="" ||
		$_REQUEST['edificio']==-1 ||                        
		$_REQUEST['salon']=="" ||
		$_REQUEST['salon']==-1 ||
		$_REQUEST['vinculacion']=="" ||
		$_REQUEST['vinculacion']==-1)
		{
			$valor[9]=1; //Mensaje 1
			$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
		}
		
		//Valida Intensidad actividad
		/*elseif(($TotHorAct > 0) && ($IntAct == $TotHorAct))
		{
			$valor[9]=3; //Mensaje 3
			$valor[10]=$TotHorAct;
			echo $TotHorAct."<br>";
			echo $IntAct."<br>";exit;
			//$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
		}*/
		//Valida cruce con la carga lectiva
		elseif($CruceCL=="S")
		{ 
			$valor[9]=4; //Mensaje 3
			$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
		}
		if($Cruce=="S")
		{ 
			$valor[9]=5; //Mensaje 3
			$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
		}
		else
		{
			//Fecha actual
			$busqueda = "SELECT CURRENT_DATE ";
			$resultfecha=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
			unset($valor);
			
			$sede=explode('#',$_REQUEST['sede']);
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "codigoSede",$sede[0]);
			$QrySede=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			$sede[0]=$QrySede[0][0];
		
			$valor[0]=$_REQUEST['usuario'];
			$valor[1]=$_REQUEST['anio'];
			$valor[2]=$_REQUEST['per'];
			$valor[3]=$_REQUEST['dia'];
			$valor[4]=$_REQUEST['hora'];
			$valor[5]=$_REQUEST['actividad'];
			$valor[6]=$sede[0];
			$valor[7]=$_REQUEST['salon'];
			$valor[8]=$_REQUEST['vinculacion'];
			$valor[9]=$resultfecha[0][0];
			$valor[10]=$_REQUEST['nivel'];
			
			//Inserta los registros en la base de datos
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertarRegistro",$valor);
                        $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			
			if(isset($resultado))
			{
				$cierto=2;
			}
			if($cierto==2){
				$this->redireccionarInscripcion($configuracion,"registroExitoso",$valor);
			}
		}
	}
	
	//Muestra la información de la actividad que se va a borrar.
	function borrarActividades($configuracion)
	{
            $tab=0;
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
						
		$valor[10]=$_REQUEST['nivel'];
		//$calendario=$this->validaCalendario($variable,$configuracion);
		//$observaciones=$this->notasobservaciones();guardarNotas
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$valor[0]=$usuario;
		$valor[1]=$ano;
		$valor[2]=$per;
		$valor[3]=$_REQUEST['dia'];
		$valor[4]=$_REQUEST['hora'];
		
		
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cargaactividades",$valor);
		$registroactividad=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
		$rowfechoy=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$fechahoy = $rowfechoy[0][0];
				
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosUsuario",$valor);
		$datosusuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actividades",$valor);
		$registroact=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentaact=count($registroact);
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"hora",$valor);
		$registrohora=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentahoras=count($registrohora);
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
											<li> Si est&aacute; seguro de borrar una actividad, haga click en el bot&oacute;n "Borrar".</li>
											
										</ul>
									</td>
									
								</tr>
							
							<tr class="texto_subtitulo">
								<td class="" colspan="5" align="center">
									<p><span class="texto_negrita">¿DESEA BORRAR ESTA ACTIVIDAD DEL PLAN DE TRABAJO?</span></p>
								</td>
							</tr>
						</table>
						
						<table class="contenidotabla centrar">
							<tr>
								<td>
									<fieldset>
										<legend>
											Datos del Docente
										</legend>
										<table class="contenidotabla">
											<tr>
												<td width='20%'>
													Docente:
												</td>
												<td width='30%' align="left">
													<? echo $datosusuario[0][1].' '.$datosusuario[0][0];?>
												</td>
												<td width='25%'>
													Per&iacute;odo:
												</td>
												<td width='25%' align="left">
													<? echo $ano.' - ' .$per;?>
												</td>
											</tr>
											<tr>
												<td width='20%'>
													Identificaci&oacute;n:
												</td>
												<td width='30%' align="left">
													<? echo $datosusuario[0][2];?>
												</td>
												<td width='25%'>
													
												</td>
												<td width='25%'>
													
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
											Borrar actividad
										</legend>
										<table class="formulario">
											<tr>
												<td>
													Actividad:
												</td>
												<td>
													<?echo  $registroactividad[0][3]; ?>
												</td>
											</tr>
											<tr>
												<td>
													Sede:
												</td>
												<td>
													<?echo  $registroactividad[0][8]; ?>
												</td>
											</tr>
											<tr>								
												<td>
													Sal&oacute;n:
												</td>
												<td>
													<?echo  $registroactividad[0][9]; ?>
												</td>
											</tr>
											<tr>	
												<td>
													Tipo de vinculaci&oacute;n:
												</td>
												<td>
													<?
													if($registroactividad[0][18]=='PL')
													{
														echo  "PLANTA";
													}
													else
													{
														echo  "VINCULACI&Oacute;N ESPECIAL";
													}
													 ?>
												</td>
											</tr>
											<tr>	
												<td>
													D&iacute;a:
												</td>
												<td>
													<?echo  $registroactividad[0][5]; ?>
												</td>
											</tr>
											<tr>	
												<td>
													Hora:
												</td>
												<td>
													<?echo  $registroactividad[0][6]; ?>
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
												<input type='hidden' name='usuario' value='<? echo $valor[0]?>'>
												<input type='hidden' name='anio' value='<? echo $valor[1] ?>'>
												<input type='hidden' name='per' value='<? echo $valor[2] ?>'>
												<input type='hidden' name='dia' value='<? echo $valor[3] ?>'>
												<input type='hidden' name='hora' value='<? echo $valor[4] ?>'>
												<input type='hidden' name='nivel' value='<? echo $valor[10] ?>'>
												<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
												<input type='hidden' name='borrar' value='borrar'>
												<input value="Borrar" name="borrar" tabindex='<? echo $tab++ ?>' type="submit" /><br>
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
	
	//Elimina la actividad de la base de datos.
	function eliminarActividad($configuracion)
	{
		$valor[0]=$_REQUEST['usuario'];
		$valor[1]=$_REQUEST['anio'];
		$valor[2]=$_REQUEST['per'];
		$valor[3]=$_REQUEST['dia'];
		$valor[4]=$_REQUEST['hora'];
		$valor[10]=$_REQUEST['nivel'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "borraActividad",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
		
		if(isset($resultado))
		{
			$cierto=3;
		}
		if($cierto==3)
		{
			$this->redireccionarInscripcion($configuracion,"registroExitoso",$valor);
		}
		
	}
	
	//Graba la observación en la base de datos.
	function grabarObservacion($configuracion)
	{
		unset($valor);
		$valor[0]=$_REQUEST['usuario'];
		$valor[1]=$_REQUEST['anio'];
		$valor[2]=$_REQUEST['per'];
		$valor[3]=$_REQUEST['observacion'];
		$valor[10]=$_REQUEST['nivel'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "insertaObservacion",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
		
		if(isset($resultado))
		{
			$cierto=4;
		}
		if($cierto==4)
		{
			$this->redireccionarInscripcion($configuracion,"registroExitoso",$valor);
		}
	}
	
	//Modifica la observación que está registrada en la base de datos.
	function ModificarObservacion($configuracion)
	{
		unset($valor);
		$valor[0]=$_REQUEST['usuario'];
		$valor[1]=$_REQUEST['anio'];
		$valor[2]=$_REQUEST['per'];
		$valor[3]=$_REQUEST['observacion'];
		$valor[10]=$_REQUEST['nivel'];
				
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "modificaObservacion",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
		
		if(isset($resultado))
		{
			$cierto=4;
		}
		if($cierto==4)
		{
			$this->redireccionarInscripcion($configuracion,"registroExitoso",$valor);
		}
	}
	
	//Muestra el reporte del plan de trabajo docente.
	function reportes($configuracion)
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
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$valor[0]=$_REQUEST['usuario'];
		$valor[1]=$ano;
		$valor[2]=$per;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
		$rowfechoy=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$fechahoy = $rowfechoy[0][0];
				
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosUsuario",$valor);
		$datosusuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"dia",$valor);
		$registrodia=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentadias=count($registrodia);
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"hora",$valor);
		$registrohora=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentahoras=count($registrohora);
		?>
		
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
								<td class="cuadro_brown" colspan="5">
									<br>
									<ul>
										<li> Para imprimir el reporte, haga clik en el bot&oacute;n con la imagen de la impresora.</li>
									</ul>
								</td>
								
							</tr>
						
						<tr class="texto_subtitulo">
							<td class="" align="center">
								<p><span class="texto_negrita">PLAN DE TRABAJO</span></p>
							</td>
						</tr>
					</table>
					
					<table class="contenidotabla centrar">
						<tr>
							<td>
								<fieldset>
									<legend>
										Datos del Docente
									</legend>
									<table class="contenidotabla">
										<tr>
											<td width='20%'>
												Doente:
											</td>
											<td width='30%' align="left">
												<? echo $datosusuario[0][1].' '.$datosusuario[0][0];?>
											</td>
											<td width='25%'>
												Per&iacute;odo:
											</td>
											<td width='25%' align="left">
												<? echo $ano.' - ' .$per;?>
											</td>
										</tr>
										<tr>
											<td width='20%'>
												Identificaci&oacute;n:
											</td>
											<td width='30%' align="left">
												<? echo $datosusuario[0][2];?>
											</td>
											<td width='25%'>
												
											</td>
											<td width='25%'>
												
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
						<tr>
							<td align="center">
								
									<legend>
										Carga Aced&eacute;mica y Actividades
									</legend>
									<table class="contenidotabla">
										<tr class="cuadro_color">
											<td class="cuadro_plano centrar"></td>
											<?
											for ($i=0; $i<=$cuentadias-1; $i++)
											{
												echo '<td class="cuadro_plano centrar">'
												.$registrodia[$i][1].
												'</td>';
											}		
											?>
										</tr>
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
													$valor[0]=$_REQUEST['usuario'];
													$valor[1]=$ano;
													$valor[2]=$per;
													$valor[3]=$registrodia[$j][0];
													$valor[4]=$registrohora[$i][0];
													$valo[10]=$_REQUEST['nivel'];
													
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cargalectiva",$valor);
													$registrocarga=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													$texto_ayuda=$registrodia[$j][1].' '.$registrohora[$i][1];
													
													$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"cargaactividades",$valor);
													$registroactividad=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
													
													$title_cargaLectiva= '* Lectiva: '. $registrocarga[0][5].'<br>* Sede: '.$registrocarga[0][8].'<br>* Sal&oacute;n: '.$registrocarga[0][10].'<br>* Vinculaci&oacute;n: '.$registrocarga[0][11];
													$title_actividad= '* Actividad: '. $registroactividad[0][3].'<br>* Sede: '.$registroactividad[0][8].'<br>* Sal&oacute;n: '.$registroactividad[0][9].'<br>* Vinculaci&oacute;n: '.$registroactividad[0][17];
													$title_celdaVacia= '* D&iacute;a: '. $registrodia[$j][1].'<br>* Hora: '.$registrohora[$i][1];
													
													if(is_array($registrocarga))
													{
														?>
														<td class="cuadro_planito centrar" style="background-color:LemonChiffon" onmouseover="toolTip('<BR><?echo $title_cargaLectiva;?>&nbsp;&nbsp;&nbsp;',this)" >
														<div class="centrar">
															<span id="toolTipBox" width="300" ></span>
														</div>
														<?
														echo '* '.$registrocarga[0][4].'<br>
														* <b>'.$registrocarga[0][9].'</b><br>
														* '.$registrocarga[0][10].'<br>
														* '.$registrocarga[0][12].'
														</td>';
														
													}
													elseif(is_array($registroactividad))
													{
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
														include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																														
														setlocale(LC_MONETARY, 'en_US');
														$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
														$cripto=new encriptar();
														
														?>
														<td class="cuadro_planito centrar" style="background-color:#e0f5ff" onmouseover="toolTip('<BR><?echo $title_actividad;?>&nbsp;&nbsp;&nbsp;',this)" >
														<div class="centrar">
															<span id="toolTipBox" width="300" ></span>
														</div>
														<?
														echo '* '.$registroactividad[0][3].'<br>
														* <strong>'.$registroactividad[0][7].'</strong><br>
														* '.$registroactividad[0][9]. '<br>
														* '.$registroactividad[0][18];
														echo '</td>';
													}
													else
													{
														echo '<td class="cuadro_plano centrar">
														</td>';
													}
												$j++;
												}
											echo '</tr>';
										}
											
										?>
									</table>
								
							</td>
						</tr>
						<tr align='center'>
							<td colspan="1">
								<table class="contenidotabla">
									
									<tr>
										<td align="center" colspan="3">
										<fieldset>
											<legend>
												Carga Lectiva
											</legend>
											<?
											$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "cuentaActividad",$valor);
											$QryHor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
											$i=0;
											while(isset($QryHor[$i][0]))
											{
												$tvi_cod=$QryHor[$i][4];
												$tipoVinculacion[(int)$tvi_cod]=$QryHor[$i][0];
												$NroAct[(int)$tvi_cod] = $QryHor[$i][1];
												$NroLec[(int)$tvi_cod] = $QryHor[$i][2];
												$NroTip[(int)$tvi_cod] = $QryHor[$i][3];
											$i++;
											}
											?>
											<table class="contenidotabla">
											<thead><td class="cuadro_plano centrar"><b>TIPO DE VINCULACION</b </td><td class="cuadro_plano centrar"><b>TOTAL HORAS LECTIVAS</b></td><td class="cuadro_plano centrar">.</td></thead>
											<?
											$contarTipVin = "SELECT count(*) FROM actipvin";
											$registroTipVin=$this->ejecutarSQL($configuracion, $this->accesoOracle, $contarTipVin, "busqueda");
											$totalTipVin=$registroTipVin[0][0];
											
											for($k=0;$k<$totalTipVin;$k++)
											{
												$tipo=isset($tipoVinculacion[$k])?$tipoVinculacion[$k]:null;
												
												if(!is_null($tipo))
												{
													echo '<tr><td class="cuadro_plano centrar"> '.$tipo.'</td><td class="cuadro_plano centrar"> '.$NroLec[$k].'</td></td><td class="cuadro_plano centrar"><b>'.$NroTip[$k].'</b></td></tr>';
												}
											}
											?>
											</table>
										</fieldset>
										</td>
									</tr>
									<tr>
										<td>
										<fieldset>
											<legend>
												Actividades
											</legend>
											
											<table class="contenidotabla">
											<tr><td class="cuadro_plano centrar"><b>TIPO DE VINCULACION</b </td><td class="cuadro_plano centrar"><b>TOTAL HORAS ACTIVIDADES</b></td><td class="cuadro_plano centrar"><b>TOTAL (LECTIVAS + ACTIVIDADES)</b></td><td class="cuadro_plano centrar">.</td></tr>
											<?
											for($k=0;$k<$totalTipVin;$k++){

												$tipo=isset($tipoVinculacion[$k])?$tipoVinculacion[$k]:null;
												if(!is_null($tipo)){
													echo '<tr><td class="cuadro_plano centrar"> '.$tipo.'</td><td class="cuadro_plano centrar"> '.$NroAct[$k].'</td><td class="cuadro_plano centrar">'.((int)$NroAct[$k] + (int)$NroLec[$k]).'</td></td><td class="cuadro_plano centrar"><b>'.$NroTip[$k].'</b></td></tr>';
												}
											}
											?>
											</table>
										</fieldset>
										</td>
									</tr>
									<tr>
										<td>
										<fieldset>
											<legend>
												Observaciones:
											</legend>
											<?
											$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"consultaObservacion",$valor);
											$registroObs=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda"); 
											?>
											<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
											<table class="contenidotabla">
												<tr>
													<td>
														<? echo $registroObs[0][3]; ?>
													</td>
												</tr>
											</table>
										</fieldset>
										</td>
									</tr>
									<tr align='center'>
										<td>
											<table class="tablaBase">
												<tr>
													<td colspan="2">
													</td>
												</tr>
												<tr>
													<td align="center">
														<p align="center">_________________________</p>
													</td>
													<td align="center">
													<p align="center">_________________________</p>
													</td>
												</tr>
												<tr>
													<td align="center"><p align="center"><font size="2" face="Tahoma">Firma del Docente</td>
													<td align="center"><p align="center"><font size="2" face="Tahoma">Recibido</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<table class="formulario">
									<tr>
										<td colspan="14" class="tabla_alerta">
											<center><input name="button" type="image" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/impresora.gif" border="0" onClick="javascript:window.print();return false" value="Imprimir" style="cursor:pointer;" title="Click par imprimir el reporte"></center>
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
		<?
	}
	
	
	
	//Muestra los mensajes de errores que se puedan presentar.	
	function mensajesErrores($configuracion)
	{
		?><table class="fondoImportante" align="center">
			<tr>
				<td class="cuadro_brown" >
				<br>
					<?
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
					$regresar="<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-1)' style='cursor:pointer;'>";
					$regresar.='<img src="';
					$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
					$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
					$regresar.= '<br>Regresar</a></center>';
					$accion=$_REQUEST['mensaje'];
					switch($accion)
					{
						case "1":
						$valor[0]= $_REQUEST['mensaje'];
						$cadena="Todos los campos son obligatorios.";
						alerta::sin_registro($configuracion,$cadena,$regresar);
						break;

						case "2":
						$cadena='<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
						<p align="center"><strong>DECRETOS, ACUERDOS Y RESOLUCIONES DEL R&Eacute;GIMEN DOCENTE </strong></p>
						<p align="justify">ART&Iacute;CULO 52, Literal c. -En lo concerniente al plan de trabajo, el tiempo de preparaci&oacute;n de clase debe 
						fijarse de acuerdo con el n&uacute;mero de asignaturas y no puede ser superior al 
						cincuenta por ciento (50%) de las horas lectivas.</p>
						</fieldset>';
						alerta::sin_registro($configuracion,$cadena,$regresar);
						break;

						case "3":
						$cadena='<p>La actividad que intenta ingresar tiene una intensidad de '.$_REQUEST['valor'].' horas semanales.<br>
						<br>CONSEJO ACAD&Eacute;MICO, CIRCULAR No. 003 de MAYO 18 DE 2004.</p>';
						alerta::sin_registro($configuracion,$cadena,$regresar);
						break;

						case "4":
						$cadena='<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
						<p align="justify">La actividad que intenta ingresar presenta cruce con el horario de su carga lectiva.</p>
						</fieldset>';
						alerta::sin_registro($configuracion,$cadena,$regresar);
						break;

						case "5":
						$cadena='<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
						<p align="justify">La actividad que intenta ingresar presenta cruce con otra actividad de su plan de trabajo.</p>
						</fieldset>';
						alerta::sin_registro($configuracion,$cadena,$regresar);
						break;
					}
					?>	
				</td>
			</tr>
		</table><?
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
                $calendario='';
								
		$confec = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual";
		@$rows=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confec, "busqueda");
		$valor[9] =$rows[0][0];
		$valor[10]=$_REQUEST['nivel'];
                						
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		//consulta las fechas de accalevento
                $qryFechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "validaFechas",$valor);
                @$fechas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryFechas, "busqueda");
                
                //valida si estan abiertas las fechas para planes de trabajo
				//echo $valor[9]."-".$fechas[0][0]."-".$valor[9]."-".$fechas[0][1];

                if($valor[9]>=$fechas[0][0] && $valor[9]<=$fechas[0][1]){
				
					$calendario=array();
					$calendario[0][0]=$fechas[0][0];
					$calendario[0][1]=$fechas[0][1];
					$calendario[0][2]=$fechas[0][2];
                                                     
                }
                elseif($fechas[0][3]=='S'){//verifica si estan permitidas las exepciones
                        //lineas que validan las execpciones para registrar planes de trabajo
                        $qryFechasPer=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "validaFechasPersonalizada",$valor);
                        @$calendario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryFechasPer, "busqueda");
                }else
                    {
                        $qryFechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "validaFechasDocentePlanta",$valor);
                        $fechas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryFechas, "busqueda");
                        if($valor[9]>=$fechas[0][0] && $valor[9]<=$fechas[0][1]){

                                                $calendario=array();
                                                $calendario[0][0]=$fechas[0][0];
                                                $calendario[0][1]=$fechas[0][1];
                                                $calendario[0][2]=$fechas[0][2];

                        }
                    }
			if(!is_array($calendario))
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
					
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
										$variable.="&usuario=".$valor[0];
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
										$variable.="&usuario=".$valor[0];
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
			case "registroExitoso":
				$variable="pagina=registro_plan_trabajo";
				$variable.="&opcion=nuevoRegistro";
				$variable.="&nivel=".$valor[10];
				break;
							
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

