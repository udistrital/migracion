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

class funciones_admin_listaCursos extends funcionGeneral
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
		$this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="admin_listaCursos";
		$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'fecha')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'radicado')";
	}
	
	//Ve la lista de Proyectos Curriculares que tiene a cargo el Coordinador
	function verProyectos($configuracion)
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
		$estado=$_REQUEST['nivel'];
		$valor[10]=$estado;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$valor[0]=$usuario;
		$valor[1]=$ano;
		$valor[2]=$per;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaProyectos",$valor);
		$registroProyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentaProyectos=count($registroProyectos);
		if(!is_array($registroProyectos))
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaDecanos",$valor);
			$registroDecanos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			$valor[3]=$registroDecanos[0][0];
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaProyectosDecano",$valor);
			$registroProyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			$cuentaProyectos=count($registroProyectos);  
		}
		?>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown" colspan="5">
								<br>
								<ul>
									<li> Haga click sobre el nombre del Proyecto Curricular, para ver la lista de los Cursos programados y la disponibilidad de cupos.</li>
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
								<p><span class="texto_negrita">PROYECTOS CURRICULARES PERIODO ACAD&Eacute;MICO <?echo $valor[1].' - '.$valor[2];?></span></p>
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
							Proyectos Curriculares
						</legend>
						<table class="contenidotabla">
							<tr class="cuadro_color">
								<td>
									Cod. Carrera
								</td>
								<td>
									Carrera
								</td>
							</tr>  
							<? 
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																								
								setlocale(LC_MONETARY, 'en_US');
								$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
								$cripto=new encriptar();
								for($i=0; $i<=$cuentaProyectos-1; $i++)
								{
									$valor[3]=$registroProyectos[$i][0];
									$valor[4]=$registroProyectos[$i][1];  
									echo '<tr><td>'.$registroProyectos[$i][0].'</td>';
									echo "<td><a href='";
										$variable="pagina=adminListaCursos";
										$variable.="&opcion=listaCursos";
										$variable.="&usuario=".$valor[0];
										$variable.="&ano=".$valor[1];
										$variable.="&per=".$valor[2];
										$variable.="&carrera=".$valor[3];
										$variable.="&nivel=".$valor[10];
										$variable.="&nomcra=".$valor[4];
										//$variable.="&no_pagina=true";
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para ver la lista de los Cursos programados y la disponibilidad de cupos.'>";
										echo $registroProyectos[$i][1];
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
	
	//Muestra la lista de los cursos programados con la disponibilidad de cupos.
	function verListaCursos($configuracion)
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
		$valor[0]=$usuario;
		$valor[10]=$_REQUEST['nivel'];
		$valor[3]=$_REQUEST['carrera'];
		$valor[4]=$_REQUEST['nomcra'];
		$valor[1]=$_REQUEST['ano'];
		$valor[2]=$_REQUEST['per'];

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaCursos",$valor);
		$registroCursos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentaCursos=count($registroCursos);
		if(!is_array($registroCursos))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='No hay cursos disponibles en '.$valor[4] .', para el Periodo Acad&eacute;mico '.$valor[1].' - '.$valor[2].'.';
			alerta::sin_registro($configuracion,$cadena);
		}
		else
		{
			?>
                <script type="text/javascript" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"];?>/datatables/js/jquery.js"></script>
                <script type="text/javascript" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"];?>/datatables/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#tabla').dataTable();
                    })
                </script>
                <link type="text/css" href="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"];?>/datatables/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
                			
                <table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
								<td class="cuadro_brown" colspan="5">
									<br>
									<ul>
										<!--li> Haga click sobre el nombre del Docente, para ver el Plan de Trabajo.</li-->
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
									<p><span class="texto_negrita">CURSOS PROGRAMADOS Y DISPONIBILIDAD DE CUPOS DE <?echo $valor[4];?><br> PERIODO ACAD&Eacute;MICO <?echo $valor[1].' - '.$valor[2];?></span></p>
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
								Lista de Cursos y cupos disponibles
							</legend>
							<table class="contenidotabla" border="1" id="tabla">
								<thead>                                                                    
                                                                <tr class="cuadro_color">
									<td class="cuadro_plano centrar">
										C&oacute;digo
									</td>
									<td class="cuadro_plano centrar">
										Asignatura
									</td>
									<td class="cuadro_plano centrar">
										Grupo
									</td>
									<td class="cuadro_plano centrar">
										Cupo
									</td>
									<td class="cuadro_plano centrar">
										Inscritos
									</td>
									<td class="cuadro_plano centrar">
										Disponible
									</td>
								</tr>  
                                                                </thead>
								<tbody>
								<? 
									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																									
									setlocale(LC_MONETARY, 'en_US');
									$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
									$cripto=new encriptar();
									for($i=0; $i<=$cuentaCursos-1; $i++)
									{
										$valor[4]=$registroCursos[$i][0];
										echo '<tr>';
										echo '<td align="center">'.$registroCursos[$i][0].'</td>';
										echo '<td>'.$registroCursos[$i][1].'</td>';
										echo '<td align="center">'.$registroCursos[$i][3].'</td>';
										echo '<td align="center">'.$registroCursos[$i][4].'</td>';
										echo '<td align="center">'.$registroCursos[$i][5].'</td>';
										echo '<td align="center">'.$registroCursos[$i][6].'</td>';
										echo '</tr>';    
									}  
								?>
								</tbody>
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
			<table class="formulario">
				<tr>
					<td colspan="14" class="tabla_alerta">
						<center><input name="button" type="image" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/impresora.gif" border="0" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer;" title="Click par imprimir el reporte"></center>
					</td>
				</tr>
			</table>	
			<?
		}
	}
	
	//Registrar estudiantes que se acogen al acuerdo 004/2011
	function registrarEstudiantes($configuracion)
	{
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		$valor[0]=$usuario;				
		if($usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		if(isset($_REQUEST['mensaje']))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='El registro se guard&oacute; exitosamente.';
			alerta::sin_registro($configuracion,$cadena);
		} 

		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		//$total=count($resultado);			
		setlocale(LC_MONETARY, 'en_US');
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		echo "<table><tr><td>
		<a href='";
		$variable="pagina=adminReporteGrados";
		$variable.="&opcion=acuerdo";
		$variable.="&no_pagina=true";
		$variable.="&usuario=".$valor[0]."";
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo $indice.$variable."'";
		echo "target='_blank'";
		echo "title='Consultar formulario de inscripci&oacute;n'>Generar reporte de registrados en Excel";
		echo "</a></td></tr>";
		echo "</table>";

		?>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<div style="position: absolute; display: none; left: 158px; top: 32px;" class="calendar"><table style="visibility: visible;" cellpadding="0" cellspacing="0"><thead><tr><td class="button" colspan="1"><div unselectable="on">?</div></td><td style="cursor: move;" class="title" colspan="6">Septiembre, 2009</td><td class="button" colspan="1"><div unselectable="on">×</div></td></tr><tr class="headrow"><td class="button nav" colspan="1"><div unselectable="on">«</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8249;</div></td><td class="button" colspan="4"><div unselectable="on">Hoy</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8250;</div></td><td class="button nav" colspan="1"><div unselectable="on">»</div></td></tr><tr class="daynames"><td class="name wn">sem</td><td class="day name">Lun</td><td class="day name">Mar</td><td class="day name">Mie</td><td class="day name">Jue</td><td class="day name">Vie</td><td class="name day weekend">S&aacute;b</td><td class="name day weekend">Dom</td></tr></thead><tbody><tr class="daysrow"><td class="day wn">36</td><td class="emptycell">&nbsp;</td><td class="day">1</td><td class="day">2</td><td class="day">3</td><td class="day">4</td><td class="day weekend">5</td><td class="day weekend">6</td></tr><tr class="daysrow"><td class="day wn">37</td><td class="day">7</td><td class="day">8</td><td class="day">9</td><td class="day">10</td><td class="day">11</td><td class="day weekend">12</td><td class="day weekend">13</td></tr><tr class="daysrow"><td class="day wn">38</td><td class="day">14</td><td class="day">15</td><td class="day">16</td><td class="day">17</td><td class="day">18</td><td class="day weekend">19</td><td class="day weekend">20</td></tr><tr class="daysrow"><td class="day wn">39</td><td class="day">21</td><td class="day">22</td><td class="day">23</td><td class="day">24</td><td class="day">25</td><td class="day selected today weekend">26</td><td class="day weekend">27</td></tr><tr class="daysrow"><td class="day wn">40</td><td class="day">28</td><td class="day">29</td><td class="day">30</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr><tr class="emptyrow"><td class="day wn">41</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr></tbody><tfoot><tr class="footrow"><td style="cursor: move;" class="ttip" colspan="8">Seleccionar fecha</td></tr></tfoot></table><div style="display: none;" class="combo"><div class="label">Ene</div><div class="label">Feb</div><div class="label">Mar</div><div class="label">Abr</div><div class="label">May</div><div class="label">Jun</div><div class="label">Jul</div><div class="label">Ago</div><div class="label">Sep</div><div class="label">Oct</div><div class="label">Nov</div><div class="label">Dic</div></div><div style="display: none;" class="combo"><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div></div></div>
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown">
								<ul>
									Para registrar un estudiante que se haya acogido al acuerdo 004 de 2011, realice los siguientes pasos:
									<li>Digite el c&oacute;digo del estudiante.</li>
									<li>Para registrar la fecha en la cu&aacute;l el estudiante radic&oacute; la solicitud ante el Proyecto Curricular, haga clic en el campo de la fecha, una vez se abra una ventana con el calendario, seleccione el dia y el mes.</li>
									<li>Digite el n&uacute;mero de radicado.</li>
									<li>Digite las observaciones.</li>  
									<li>Para finalizar, haga clic en "Guardar".</li>  
								</ul>
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td class="" align="center">
								<p><span class="texto_negrita">Registro de estudiantes que se acogen al acuerdo 004 de 2011.</span></p>
							</td>
						</tr>
					</table>
					<table class="contenidotabla centrar">
						<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
						<!--tr>
							<td align="center">
								<fieldset>
									<legend>
										Registro acuerdo 004/2011
									</legend>
									<table class="formulario">
										<tr>
											<td>
												<font color="red">*</font>C&oacute;digo del estudiante
											</td>
											<td>
												<input maxlength="450" size="25" tabindex="<? echo $tab++ ?>" name="codigo">
											</td>
										</tr>
										<tr>
											<td>
												<font color="red">*</font>Fecha de radicado
											</td>
											<td>
												<input type='text' size='8' value='' name='fecha' id='fecha' readonly>(dd/mm/aaaa)
											</td>
										</tr>
										<tr>
											<td>
												<font color="red">*</font>N&uacute;mero de radicado
											</td>
											<td>
												<input maxlength="450" size="25" tabindex="<? echo $tab++ ?>" name="radicado">
											</td>
										</tr>
										<tr>
											<td>
												Observaciones
											</td>
											<td colspan="2">
												<textarea id='descripcion' name='observaciones' cols='50' rows='2' tabindex='<? echo $tab++ ?>' ></textarea>
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
															<input type='hidden' name='usuario' value='<? echo $valor[0] ?>'>
															<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
															<input type='hidden' name='guardar' value='grabar'>
															<input value="Guardar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
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
						</tr-->
						</form>
					</table>
				</td>
			</tr>
		</table>
		<?
	}
	
	//Guarda los registros de los estudiantes que se acogen al acuerdo 004 de 2011
	function guardarRegistroAcuerdo($configuracion, $accesoOracle,$acceso_db)
	{
		$valor[0]=$_REQUEST['usuario'];
		$valor[4]=$_REQUEST['codigo'];
		$valor[5]=$_REQUEST['fecha'];
		$valor[6]=$_REQUEST['radicado'];
		$valor[7]=$_REQUEST['observaciones'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaRegistroAcuerdo",$valor);
		$registroVerifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		if(is_array($registroVerifica))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="El estudiante ".$valor[4]." ya presenta un registro previo.<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carreraEstudiante",$valor);
			$registro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

			$valor[8]=$registro[0][0];
			$valor[9]='2011004'; //Número del acuerdo
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaEstudianteCoordinador",$valor);
			$verifica=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
			
			if(is_array($verifica))
			{
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "registrarEstudianteAcuerdo",$valor);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
				if($resultado==TRUE)
				{
					$valor[1]='mensaje'; 
					$this->redireccionarInscripcion($configuracion,"registroExitoso",$valor);
				}
				else
				{
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
					$cadena='El registro no se pudo guardar, revise que el procedimiento e intente de nuevo.';
					alerta::sin_registro($configuracion,$cadena);	
				}
			}
			else
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena="El estudiante ".$valor[4]." no pertenece a su Coordinaci&oacute;n.<br>";

				$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
				alerta::sin_registro($configuracion,$cadena);
			}
		}
		

		/*$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaProyectos",$valor);
		$registroProyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentaProyectos=count($registroProyectos);
		echo$cuentaProyectos."<br>";
		echo $valor[8]."<br>";
		for($i=0; $i<=$cuentaProyectos; $i++)
		{
			echo $registroProyectos[$i][0]."<br>";
			if($registroProyectos[$i][0]==$valor[8])
			{
				echo "cierto";//$cierto=1;
			}  
		}
		if($cierto==1)
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$regresar="<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-1)' style='cursor:pointer;'>";
			$regresar.='<img src="';
			$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
			$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
			$regresar.= '<br>Regresar</a></center>';
					
			$cadena='El estudiante no pertenece a su Coordinaci&oacute;n.';
			alerta::sin_registro($configuracion,$cadena,$regresar);
		}
		else
		{    
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "registrarEstudianteAcuerdo",$valor);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			if($resultado==TRUE)
			{
				$valor[1]='mensaje'; 
				$this->redireccionarInscripcion($configuracion,"registroExitoso",$valor);
			}
			else
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena='El registro no se pudo guardar, revise que el procedimiento e intente de nuevo.';
				alerta::sin_registro($configuracion,$cadena);	
			}
		}*/
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
								
		$confec = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual";
		@$rows=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confec, "busqueda");
		$valor[9] =$rows[0][0];
		$valor[10]=$_REQUEST['nivel'];
						
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$qryFechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "validaFechas",$valor);
		@$calendario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryFechas, "busqueda");
		
		//echo $qryFechas;
			if(!is_array($calendario))
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
				$total=count($resultado);
					
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

