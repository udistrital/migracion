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


class funciones_admin_evaldocentes extends funcionGeneral
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
		$this->accesoOracle=$this->conectarDB($configuracion,"autoevaluadoc");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		 //echo  "mmm".$_REQUEST['usuario'];  
  
		$this->formulario="admin_evaldocentes";
		$this->verificar="control_vacio(".$this->formulario.",'docente')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'fecha')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'radicado')";
	}
	
	//Ve la lista de Proyectos Curriculares que tiene a cargo el Coordinador
	function ObservacionesEst($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$_REQUEST['usuario'];
		}
						
		if($usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$estado=$_REQUEST['nivel'];
		$valor[10]='A';
		//echo "mmm".$_REQUEST['tipoConsulta']; 
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"periodoacademico",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		?>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown" colspan="5">
								<br>
								<ul>
									<li> Seleccione el periodo acad&eacute;mico, para consultar las observaciones realizadas por los estudiantes en la evaluaci&oacute;n docente.</li>
								</ul>
							</td>
						</tr>
						<!--tr>
							<td>
								<p><a href="https://condor.udistrital.edu.co/appserv/manual/plan_trabajo.pdf">
								<img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfito.png"?>" />
								Ver Manual de Usuario.</a></p>
							</td>
						</tr-->
						<tr class="texto_subtitulo">
							<td class="" colspan="5" align="center">
								<p><span class="texto_negrita"></span></p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table class="contenidotabla centrar">
			<tr>
				<td>
					<fieldset>
						<legend>
							Periodo acad&eacute;mico
						</legend>
						<table class="formulario">
							<tr class="cuadro_color">
								<td align='center'>
									
								</td>
							</tr>  
							<? 
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																								
								setlocale(LC_MONETARY, 'en_US');
								$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
								$cripto=new encriptar();
								for($i=0; $i<4; $i++)
								{
									$valor[3]=$registroProyectos[$i][0];
									$valor[4]=$registroProyectos[$i][1];  
									//echo '<tr><td>'.$resultado[$i][0].'</td>';

									echo "<tr><td align='center'><a href='";
										$variable="pagina=adminConsultasCoordinador";
										$variable.="&opcion=controlNotas";
										$variable.="&ano=".$resultado[$i][0];
										$variable.="&per=".$resultado[$i][1];
										//$variable.="&no_pagina=true";
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para consultar las observaciones realizadas por los estudiantes en la evaluaci&oacute;n docente.'>";
										echo 'Periodo acad&eacute;mico: '. $resultado[$i][0].' - '.$resultado[$i][1];
										echo '</a>';
									echo '</td></tr>';
									
								}  
							?>
							
						</table>
					</fieldset>
				</td>
			</tr>
		</table>
					
		<?
		
	}
	
	
	//Selecciona el el periodo actual o prximo al que le va a asignar la carga
	function seleccionarPeriodo($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$_REQUEST['usuario'];;
		}
		
		if($usuario=="")
		{
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
    
		//echo "nnn".$usuario;
		$carrera=isset($_REQUEST['carrera'])?$_REQUEST['carrera']:'';
		 
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
										<li> Seleccione el Periodo acad&eacute;mico y la Facultad para generar el reporte de observaciones realizada por los estudiantes en la evaluaci&oacute;n docente.</li>
									</ul>
								</td>
								
							</tr>
													
							<tr class="texto_subtitulo">
								<td class="" colspan="5" align="center">
									<p><span class="texto_negrita">SELECCIONE EL PERIODO ACAD&Eacute;MICO y LA FACULTAD</span></p>
								</td>
							</tr>
						</table>
						
						<table class="contenidotabla centrar">
							<tr>
								<td align="center">
									<fieldset>
										<legend>
											Seleccionar periodo acad&eacute;mico y Facultad
										</legend>
										<table class="formulario">
											<tr>								
												<td>
													<font color="red">*</font>Periodo acad&eacute;mico:
												</td>
												<td>
													<?
													include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
													$html=new html();
  
													$busqueda="SELECT ";
													$busqueda.="ape_ano||'-'||ape_per, ";
													$busqueda.="ape_ano||'-'||ape_per ";
													$busqueda.="FROM ";
													$busqueda.="acasperi ";
													$busqueda.="WHERE ";
													$busqueda.="ape_estado IN ('A','P','I') ";
													$busqueda.="AND ";
													$busqueda.="ape_per NOT IN (2) ";
													$busqueda.="order by ape_ano DESC ";
													//echo $busqueda.'<br>';					
													$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
																										
													for ($i=0; $i<count($resultado);$i++)
													{
														$registro[$i][0]=$resultado[$i][0];
														$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
													}								
													$mi_cuadro=$html->cuadro_lista($registro,'periodo',$configuracion,1,3,FALSE,"periodo",100);
													
													echo $mi_cuadro;
												?>
												</td>
											</tr>
											<tr>								
												<td>
													<font color="red">*</font>Facultad:
												</td>
												<td>
													<?
													include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
													$html=new html();
  
													$busqueda="SELECT ";
													$busqueda.="dep_cod, ";
													$busqueda.="dep_nombre ";
													$busqueda.="FROM ";
													$busqueda.="geclaves,acdocente,gedep,peemp ";
													$busqueda.="WHERE ";
													$busqueda.="cla_tipo_usu = 16 ";
													$busqueda.="AND ";
													$busqueda.="cla_codigo = doc_nro_iden ";
													$busqueda.="AND ";
													$busqueda.="cla_estado = 'A' ";
													$busqueda.="AND ";
													$busqueda.="dep_emp_cod = emp_cod ";
													$busqueda.="AND ";
													$busqueda.="emp_nro_iden = cla_codigo ";
													$busqueda.="order by 1";
													//echo $busqueda.'<br>';					
													$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
																										
													for ($i=0; $i<count($resultado1);$i++)
													{
														$registro1[$i][0]=$resultado1[$i][0];
														$registro1[$i][1]=UTF8_DECODE($resultado1[$i][1]);
													}								
													$mi_cuadro=$html->cuadro_lista($registro1,'facultad',$configuracion,1,3,FALSE,"facultad",100);
													
													echo $mi_cuadro;


												?>
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
												<? $tab1=isset($tab)?$tab:''; ?>
                                                                                                <? $this->verificar=isset($this->verificar)?$this->verificar:''; ?>
												<input type='hidden' name='usuario' value='<? echo $usuario ?>'>
												<input type='hidden' name='carrera' value='<? echo $carrera ?>'>
												<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
												<input type='hidden' name='opcion' value='seleccionarPeriodo'>
												<input value="Seleccionar periodo" name="aceptar" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
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
	
	//Arma el reporte de observaciones para el periodo académico seleccionado
	function reporteObservaciones($configuracion,$registro, $total, $opcion="",$valor)
	{
                $periodo=isset($_REQUEST['periodo'])?$_REQUEST['periodo']:'';
		if(!$periodo)
		{
			$ano=$_REQUEST['anio'];
			$per=$_REQUEST['per'];
			$variable[1]=$_REQUEST['anio'];
			$variable[2]=$_REQUEST['per'];
		}
		else
		{
		$periodo=explode('-',$_REQUEST['periodo']);
		$ano=$periodo[0];
		$per=$periodo[1];
		
		$variable[1]=$ano;
		$variable[2]=$per;
		}
		//echo "<br>MMM".$_REQUEST['facultad'];
		$variable[4]=$_REQUEST['facultad'];

		switch($_REQUEST["accion"])
		{
			case "listaCompleta":
		      
				//Paginacion
				//Obtener el total de registros
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "totalObservaciones",$variable);
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
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "completoObservaciones",$variable);
										
				break;					
		
		}
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "completoObservaciones",$variable);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		$total=count($resultado);
			
		setlocale(LC_MONETARY, 'en_US');
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		$menu=new navegacion();
		$variableNavegacion["pagina"]="adminEvaldocentes";
		$variableNavegacion["opcion"]="observaciones";	
		$variableNavegacion["accion"]="listaCompleta";
		$variableNavegacion["anio"]=$variable[1];
		$variableNavegacion["per"]=$variable[2];
		$variableNavegacion["facultad"]=$variable[4];  
		//$variableNavegacion["per"]=$valor[2];

		echo "<center><table><tr><td class='cuadro_plano centrar'>
		<a href='";
		$variable="pagina=adminReportesExcelCoordinador";
		$variable.="&opcion=observacionesEvaldocentes";
		$variable.="&no_pagina=true";
		$variable.="&ano=".$ano."";
		$variable.="&periodo=".$per."";
		$variable.="&facultad=".$_REQUEST['facultad']."";
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
								<p><span class='texto_negrita'>OBSERVACIONES DE ESTUDIANTES EVALUACI&Oacute;N DOCENTE<br> PERIODO ACAD&Eacute;MICO ".$ano." - ".$per."</span></p>
							</td>
						</tr>
					<tr class='cuadro_color'>
						<td class='cuadro_plano centrar''>
							Cod. Fac
						</td>
						<td class='cuadro_plano centrar'>
							Facultad
						</td>
						<td class='cuadro_plano centrar''>
							Cod. Cra
						</td>
						<td class='cuadro_plano centrar'>
							Carrera
						</td>
						<td class='cuadro_plano centrar''>
							Id. Docente
						</td>
						<td class='cuadro_plano centrar''>
							Docente
						</td>
						<td class='cuadro_plano centrar''>
							Observaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Cod. curso
						</td>
						<td class='cuadro_plano centrar''>
							Curso
						</td>
					</tr>";
					
					for($i=0;$i<$total;$i++)
					{	
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][1]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][2]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][6]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][7]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][8]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][9]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][10]."</td>";
						echo "</tr>";	
						
					}
			echo "</table>";		
		if($this->totalPaginas>1)
		{
			$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
		}
	}
	
	//Consultar observaciones realizadas por los estudiantes a los docentes
	function consultaDocente($configuracion)
	{
		
		?>  
		<table class="formulario" align="center">
			<tr>
				<td class="cuadro_brown">
					<ul>
						<li>Para consultar las observaciones realizadas por los estudiantes de un Docnete, digite el número del documente de identidad y haga click en "Consultar".</li>
					</ul>
					
				</td>
			</tr>
			<tr class="texto_subtitulo">
				<td class="" align="center">
					<p><span class="texto_negrita">CONSULTA DE OBSERVACIONES DE DE ESTUDIANTES</span></p>
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
										Consulta de observaciones de Docentes
									</legend>
									<table class="formulario">
										<tr class="cuadro_plano">
											<td>
												No. de identificaci&oacute;n del Docente
											</td>
											<td>
												<input type="text" id="docente" name="docente" value="" size="25">
											 </td> 
										<tr align='center'>
											<td colspan="16">
												<table class="tablaBase">
													<tr>
														
														<td align="center">
															<? $tab1=isset($tab)?$tab:''; ?>
															<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
															<input type='hidden' name='opcion' value='consultaObservacionesDocente'>
															<input value="Consultar" name="aceptar" tabindex='<? echo $tab1++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
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
	
	function consultaObservaciones($configuracion)
	{
		unset($valor);
		$valor[0]=$_REQUEST['docente'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "observacionesDocente",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$total=count($resultado);  
		//echo "MMM".$valor[0];  
		if(is_array($resultado))
		{
			echo "<table class='formulario' align='center'>
				 	<tr  class='bloquecentralencabezado'>
							<td colspan='9'>
								
							</td>
						</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='9' align='center'>
								<p><span class='texto_negrita'>OBSERVACIONES DE ESTUDIANTES REALIZADAS AL (LA) DOCENTE: <br>  ".$resultado[0][7].", CON DOCUMENTO DE IDENTIDAD No. ".$resultado[0][6]."</span></p>
							</td>
						</tr>
					<tr class='cuadro_color'>
						<td class='cuadro_plano centrar''>
							Cod. Fac
						</td>
						<td class='cuadro_plano centrar'>
							Facultad
						</td>
						<td class='cuadro_plano centrar''>
							Cod. Cra
						</td>
						<td class='cuadro_plano centrar'>
							Carrera
						</td>
						<td class='cuadro_plano centrar''>
							A&ntilde;o
						</td>
						<td class='cuadro_plano centrar''>
							Periodo
						</td>
						<td class='cuadro_plano centrar''>
							Observaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Cod. curso
						</td>
						<td class='cuadro_plano centrar''>
							Curso
						</td>
					</tr>";
					
					for($i=0;$i<$total;$i++)
					{	
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][1]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][2]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][4]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][5]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][8]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][9]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][10]."</td>";
						echo "</tr>";	
						
					}
			echo "</table>";	
		}
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='No se encontraron registros de observaciones para el (la) docente '.$valor[0].'.!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
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
			case "registroExitoso":
				$variable="pagina=adminListaCursos";
				$variable.="&opcion=registroAcuerdo";
				$variable.="&mensaje=".$valor[1];
				break;
										
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

