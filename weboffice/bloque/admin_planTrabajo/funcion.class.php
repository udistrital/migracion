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
		$this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="registro_PlanTrabajo";
		//$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'lugar')";
		
	}

	//Ve la lista de las Facultades
	function verFacultades($configuracion)
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
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
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

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaFacultades",$valor);
		$registroFacultades=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentaFacultades=count($registroFacultades);
		
		?>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown" colspan="5">
								<br>
								<ul>
									<li> Haga click sobre el nombre de la Facultad, para ver los Proyectos Curriculares.</li>
								</ul>
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td class="" colspan="5" align="center">
								<p><span class="texto_negrita">FACULTADES PERIODO ACAD&Eacute;MICO <?echo $valor[1].' - '.$valor[2];?></span></p>
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
							Facultades
						</legend>
						<table class="contenidotabla">
							<tr class="cuadro_color">
								<td>
									Cod. Facultad
								</td>
								<td>
									Facultad
								</td>
							</tr>  
							<? 
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																								
								setlocale(LC_MONETARY, 'en_US');
								$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
								$cripto=new encriptar();
								for($i=0; $i<=$cuentaFacultades-1; $i++)
								{
									$valor[3]=$registroFacultades[$i][0];
									$valor[4]=$registroFacultades[$i][1];  
									echo '<tr><td>'.$registroFacultades[$i][0].'</td>';
									echo "<td><a href='";
										$variable="pagina=adminPlanTrabajo";
										$variable.="&opcion=verProyectos";
										$variable.="&usuario=".$valor[0];
										$variable.="&ano=".$valor[1];
										$variable.="&per=".$valor[2];
										$variable.="&depCod=".$valor[3];
										$variable.="&nivel=".$valor[10];
										$variable.="&nomcra=".$valor[4];
										//$variable.="&no_pagina=true";
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para ver la lista de los Docentes vinculados a este Proyecto Curricular.'>";
										echo $registroFacultades[$i][1];
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
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
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
			if(is_array($registroDecanos))
			{
				$valor[3]=$registroDecanos[0][0];
			}
			elseif(isset($_REQUEST['depCod']))
			{
				$valor[3]=$_REQUEST['depCod'];
			}
			else
			{
				$valor[3]=9999;
			}
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaProyectosDecano",$valor);
			$registroProyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			$cuentaProyectos=count($registroProyectos);
			
			if(!is_array($registroProyectos))
			{
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaProyectosTodos",$valor);
				$registroProyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
				$cuentaProyectos=count($registroProyectos);
			}
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
									<li> Haga click sobre el nombre del Proyecto Curricular, para ver los Planes de Trabajo de los docentes.</li>
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
										$variable="pagina=adminPlanTrabajo";
										$variable.="&opcion=listaDocentes";
										$variable.="&usuario=".$valor[0];
										$variable.="&ano=".$valor[1];
										$variable.="&per=".$valor[2];
										$variable.="&carrera=".$valor[3];
										$variable.="&nivel=".$valor[10];
										$variable.="&nomcra=".$valor[4];
										//$variable.="&no_pagina=true";
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para ver la lista de los Docentes vinculados a este Proyecto Curricular.'>";
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
	
	//Muestra la lista de los docentes de un Proyecto curricular con el número de horas de actividades de la carga academica y el plan de trabajo
	function verListaDocentes($configuracion)
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

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaDocentes",$valor);
		$registroDocentes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentaDocentes=count($registroDocentes);
		if(!is_array($registroDocentes))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='No hay docentes registrados en '.$valor[4] .', para el Periodo Acad&eacute;mico '.$valor[1].' - '.$valor[2].'.';
			alerta::sin_registro($configuracion,$cadena);
		}
		else
		{
			?>
			<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
								<td class="cuadro_brown" colspan="5">
									<br>
									<ul>
										<li> Haga click sobre el nombre del Docente, para ver el Plan de Trabajo.</li>
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
									<p><span class="texto_negrita">DOCENTES VINCULADOS A <?echo $valor[4];?>, PERIODO ACAD&Eacute;MICO <?echo $valor[1].' - '.$valor[2];?></span></p>
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
								Lista de Docentes
							</legend>
							<table class="contenidotabla" border="1">
								<tr class="cuadro_color">
									<td class="cuadro_plano centrar">
										C&eacute;dula
									</td>
									<td class="cuadro_plano centrar">
										Nombre
									</td>
									<td class="cuadro_plano centrar">
										Horas Plan Trabajo
									</td>  
								</tr>  
								<? 
									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																									
									setlocale(LC_MONETARY, 'en_US');
									$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
									$cripto=new encriptar();
									for($i=0; $i<=$cuentaDocentes-1; $i++)
									{
										$valor[4]=$registroDocentes[$i][0];
										echo '<tr><td>'.$registroDocentes[$i][0].'</td>';
										echo "<td><a href='";
											$variable="pagina=registro_plan_trabajo";
											$variable.="&opcion=reportes";
											$variable.="&usuario=".$registroDocentes[$i][0];
											$variable.="&ano=".$valor[1];
											$variable.="&per=".$valor[2];
											$variable.="&carrera=".$valor[3];
											$variable.="&nivel=".$valor[10];
											//$variable.="&no_pagina=true";
											$variable=$cripto->codificar_url($variable,$configuracion);
											echo $indice.$variable."'";
											echo "title='Haga Click aqu&iacute; para ver las actividades y la carga del Docente.'>";
											echo $registroDocentes[$i][1];
											echo '</a>';
										echo '</td>';
										?>
										<td align="center" colspan="3">
											<?
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "cuentaActividad",$valor);
												$QryHor=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												$cuentaAct=count($QryHor);
												//echo "mmmm".$cuentaAct;
												echo '<table class="contenidotabla">';
												echo '<thead class="cuadro_color">
													<td class="cuadro_plano_medio centrar">
														TIPO DE VINCULACION
													</td><td class="cuadro_plano_medio centrar">
														ACTIVIDADES
													</td>
													<td class="cuadro_plano_medio centrar">
														CARGA
													</td>
													<td class="cuadro_plano_medio centrar">
														TOTAL HORAS
													</td>  
												</thead>';
												
												$j=0;
												while(isset($QryHor[$j][0]))
												{
													echo '<tr>';
														echo '<td class="cuadro_plano_medio centrar">';
															echo $QryHor[$j][0];
														echo '</td>';
														echo '<td class="cuadro_plano_medio centrar">';
															echo $QryHor[$j][1];
														echo '</td>';
														echo '<td class="cuadro_plano_medio centrar">';
															echo $QryHor[$j][2];
														echo '</td>';
														echo '<td class="cuadro_plano_medio centrar">';
															echo ((int)$QryHor[$j][1] + (int)$QryHor[$j][2]);
														echo '</td>'; 
													echo '</tr>';
												$j++;
												}
												echo '</table>';
												?>
										</td>  
										<?
										echo '</tr>';
									}  
								?>
								
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
						
			<?
		}
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

