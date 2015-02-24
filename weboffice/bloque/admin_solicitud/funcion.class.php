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

class funciones_adminSolicitud extends funcionGeneral
{

	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->sql=$sql;
                $this->acceso_db=$this->conectarDB($configuracion,"");
		$this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
                
		//Conexion ORACLE
                if($this->nivel==4){
                    $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
                }elseif($this->nivel==110){
                    $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
                }elseif($this->nivel==114){
                    $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
                }else{
                    echo "NO TIENE PERMISOS PARA ESTE MODULO";
                }

	}
	
	
	function consultaBloqueadosest($configuracion)
	{
		
		unset($valor);
		$valor[0]=$_REQUEST['estudiante'];
		$valor[1]=$ano;
		$valor[2]=$per;
		$valor[3]=$_REQUEST['carrera'];
			
		$this->redireccionarInscripcion($configuracion, "bloqueadoEstudiante",$valor);
	}
	
   	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
   	{}
   	
    	function corregirRegistro()
    	{}
	
	function mostrarRegistro($configuracion,$registro, $total, $opcion="",$variable)
    	{
		
		switch($opcion)
		{
			case "multiplesCarreras":
				$this->multiplesCarreras($configuracion,$registro, $total, $variable);
				break;
				
			case "bloqueado":
				$this->reciboBloqueado($configuracion,$registro, $total);
		
		}
	}
	
		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
	function consultarCarrera($configuracion)
	{
		$conexion=$this->accesoOracle;
		
		$annoActual=$this->datosGenerales($configuracion,$conexion, "anno") ;
		$periodoActual=$this->datosGenerales($configuracion,$conexion, "per") ;
		if(isset($_REQUEST["carrera"]))
		{
			$variable[0]=$_REQUEST["carrera"];
		}
		else
		{
			$variable[0]=99999;
		}
		$variable[1]=$annoActual;
		$variable[2]=$periodoActual;
						
		if(isset($_REQUEST["opcion"]))
		{
			switch($_REQUEST["opcion"])
			{
				case "bloqueado":
                                    	//Se seleccionan los recibos bloqueados en ACESTMAT de acuerdo a los criterios de busqueda
					switch($_REQUEST["accion"])
					{
						case "listaCompleta":
							
							//Paginacion
							//Obtener el total de registros
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"totalBloqueado",$variable);						
							$registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
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
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"bloqueadoCompleto",$variable);
							
							
							break;					
					}
					break;
				case "bloqueadoEstudiante":
						$acceso_db=$this->conectarDB($configuracion,"");
						$conexion=$this->accesoOracle;
										
						$usuario=$this->rescatarValorSesion($configuracion, $acceso_db, "id_usuario");
						$id_usuario=$this->rescatarValorSesion($configuracion, $acceso_db, "identificacion");
												
						$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carreraCoordinador",$usuario);						
						$registroUsuario=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
						$cuenta=count($registroUsuario);
						
						for($i=0; $i<=$cuenta-1; $i++)
						{
							unset($variable);
							if(isset($registroUsuario))
							{
								$variable[0]=$registroUsuario[$i][0];
							}
							else
							{
								$variable[0]=99999;
							}
							$variable[1]=$annoActual;
							$variable[2]=$periodoActual;
							$variable[3]=$_REQUEST["estudiante"];
														
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"BloqueadoEst",$variable);
							$registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
														
							if(!is_array($registro))
							{
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
								$cadena="El estudiante ".$variable[3]." no tiene recibos bloqueados, o no pertenece a su Coordinaci&oacute;n.<br>";
					
								$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
								alerta::sin_registro($configuracion,$cadena);	
							}
						}
					break;
				
				default:
					break;
			}
			$registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
			if(is_array($registro))
			{
				//Obtener el total de registros
				$totalRegistros=$this->totalRegistros($configuracion, $conexion);
				$this->mostrarRegistro($configuracion,$registro, $totalRegistros, "bloqueado","");
				
				
			}
			else
			{
				
			}
		}
		else
		{
		
		}
		
	}
	
	function reciboBloqueado($configuracion,$registro, $total)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		$menu=new navegacion();
		$variableNavegacion["pagina"]="admin_solicitud";
		$variableNavegacion["opcion"]="bloqueado";
		$variableNavegacion["accion"]="listaCompleta";
		$variableNavegacion["carrera"]=$registro[0][2];
		?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<td>
								Recibos Bloqueados<br>
								<hr class="hr_subtitulo">
								</td>
							</tr>
							<tr>
								<td><? 
								if($this->totalPaginas>1)
								{
										$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
								}
								$formulario="admin_solicitud";
								echo "<form method='post' action='index.php' name=".$formulario.">"; ?>
									<table class='contenidotabla'>
										<tr class='cuadro_color'>
											<td class='cuadro_plano centrar'>Recibo</td>
											<td class='cuadro_plano centrar'>C&oacute;digo</td>
											<td class='cuadro_plano centrar'>Nombre</td>
											<td class='cuadro_plano centrar'>Estado</td>
											<td class='cuadro_plano centrar'>Deudor</td>
											<td class='cuadro_plano centrar'>Selecci&oacute;n</td>
										</tr><?
										for($contador=0;$contador<$total;$contador++)
										{
											echo "<tr>\n";
											echo "<td class='cuadro_plano centrar'>".$registro[$contador][0]."</td>\n";
											//echo "<td class='cuadro_plano'><a href='".$indice.$parametro."'>".$registro[$contador][1]."</a></td>\n";
											echo "<td class='cuadro_plano'>".$registro[$contador][1]."</td>\n";
											echo "<td class='cuadro_plano'>".$registro[$contador][13]."</td>\n";
											echo "<td class='cuadro_plano centrar'>".$registro[$contador][15]."</td>\n";
											//Rescatamos las deudas q tiene el estudiante
											if ($this->deudaEstudiante($configuracion,$this->accesoOracle,$registro[$contador][1])){
											
											echo "<td class='cuadro_plano centrar'>Si</td>\n";
											}
											else{
											echo "<td class='cuadro_plano centrar'></td>\n";
											}
											
											echo "<td class='cuadro_plano centrar'><input type='hidden' name='codBloqueo_".$contador."' id='codBloqueo_".$contador."' value='".$registro[$contador][1]."'><input type='checkbox' name='selBloqueo_".$contador."' id='selBloqueo_".$contador."' value='".$registro[$contador][0]."'></td>\n";
											echo "</tr>";
											
											
											
										}
											$mi_variable="pagina=admin_solicitud";
											$mi_variable.="&confirmar=1";
											$mi_variable=$cripto->codificar_url($mi_variable,$configuracion);
											echo "<tr><td colspan='6' class='centrar cuadro_plano'>";
											echo "<input type='hidden' name='formulario' value='".$mi_variable."'>\n";
											echo "<input type='submit' name='aceptar' value='Desbloquear' >
											</td></tr>";
																	
										
										?>
		</table>
								<? echo "</form>"; 
								if($this->totalPaginas>1)
								{
										$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
								}
								?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<p class="textoNivel0">La tabla anterior muestra el consolidado de recibos que requieren su autorizaci&oacute;n para descarga 
						por parte de los estudiantes.</p>
						<p class="textoNivel0">Por favor realice click sobre el nombre del proyecto curricular que desee trabajar.</p>
						<p class="textoNivel0">Los recibos se han bloqueado debido al estado del estudiante al momento de la generaci&oacute;n del lote
						principal. Las causas m&aacute;s comunes son: prueba acad&eacute;mica (J) y deudores de biblioteca o laboratorios. Tenga en cuenta
						que los recibos que usted genere, a trav&eacute;s de las plantillas, no se bloquean.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<?
	
	
	}
	
	//Funcion para mostrar el estado de la deuda del estudiante
	
	function deudaEstudiante($configuracion,$accesoOracle,$variable)
	{
		$cadena_sql=$this->sql->cadena_sql($configuracion, $accesoOracle, "deudaEstudiante", $variable);
		//echo $cadena_sql;
		$registro=$this->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql, "busqueda");
		if(is_array($registro))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		
		
	}
	
	function confirmarDesbloqueo($configuracion)
	{?>
			<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<td>
								Confirmar Desbloqueo de Recibos<br>
								<hr class="hr_subtitulo">
								</td>
							</tr>
							<tr>
								<td class="centrar">
									<table class="contenidotabla">
										<? 
										$formulario="admin_solicitud";
										$celdas=0;
										echo "<form method='post' action='index.php' name=".$formulario.">"; ?>
											<?
										$i=0;
										foreach($_REQUEST as $clave => $valor) 
										{
											if(substr($clave,0,strlen("selBloqueo"))=="selBloqueo")
											{
												if($celdas==0)
												{
													echo "<tr>";
												}
												echo "<td  class='cuadro_plano centrar'>\n";
												echo "<input type='hidden' name='codDesbloquear_".$i."' value='".$_REQUEST["codBloqueo_".substr($clave,(strlen("selBloqueo_")))]."'>";
												echo "<input type='hidden' name='recDesbloquear_".$i."' value='".$_REQUEST["selBloqueo_".substr($clave,(strlen("selBloqueo_")))]."'>";
												echo $_REQUEST["codBloqueo_".substr($clave,(strlen("selBloqueo_")))]."</td>";
												$i++;
												if($celdas==4)
												{
													echo "</tr>";
													$celdas=0;
												}
												else
												{
													$celdas++;
												}
												
												
											};
											
											
										} 	
										echo "<tr><td colspan='5' class='centrar'>";
										echo "<input type='hidden' name='action' value='".$formulario."' >";
										echo "<br> <input type='submit' name='aceptar' value='Desbloquear' ></td></tr>";
		
										?>
									</table>
								<? echo "</form>"; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>	
	<?
	
	}
	
	function multiplesCarreras($configuracion,$registro, $total, $variable)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		//Conexion ORACLE
		$conexion=$this->conectarDB($configuracion,"oracle");
		?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<td>
								Recibos Bloqueados por Proyecto Curricular<br>
								<hr class="hr_subtitulo">
								</td>
							</tr>
							<tr>
								<td>
									<table class='contenidotabla'>
										<tr class='cuadro_color'>
											<td class='cuadro_plano centrar ancho10'' rowspan='2'>Total</td>
											<td class='cuadro_plano centrar' colspan='2'>Proyecto Curricular</td>
										</tr>
										<tr class='cuadro_color'>
											<td class='cuadro_plano centrar ancho10' >C&oacute;digo</td>
											<td class='cuadro_plano centrar'>Nombre </td>
										</tr><?
										for($contador=0;$contador<$total;$contador++)
										{
											$variable[0]=$registro[$contador][0];
											$cadena_sql=$this->sql->cadena_sql($configuracion,$conexion, "totalCarreraBloqueado",$variable);
											$registroCarrera=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
											if(is_array($registroCarrera))
											{
												if($registroCarrera[0][0]>0)
												{
													//Con enlace a la busqueda
													$parametro="pagina=admin_solicitud";
													$parametro.="&hoja=1";
													$parametro.="&opcion=bloqueado";
													$parametro.="&accion=listaCompleta";
													$parametro.="&carrera=".$registro[$contador][0];
													$parametro=$cripto->codificar_url($parametro,$configuracion);
													echo "<tr><td class='cuadro_plano centrar'>".$registroCarrera[0][0]."</td><td class='cuadro_plano centrar'>".$registro[$contador][0]."</td><td class='cuadro_plano'><a href='".$indice.$parametro."'>".$registro[$contador][1]."</a></td></tr>";
												}
												else
												{
													echo "<tr><td class='cuadro_plano centrar'>".$registroCarrera[0][0]."</td><td class='cuadro_plano centrar'>".$registro[$contador][0]."</td><td class='cuadro_plano'>".$registro[$contador][1]."</td></tr>";
												}
											}
											
										}?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<p class="textoNivel0">La tabla anterior muestra el consolidado de recibos que requieren su autorizaci&oacute;n para descarga 
						por parte de los estudiantes.</p>
						<p class="textoNivel0">Por favor realice click sobre el nombre del proyecto curricular que desee trabajar.</p>
						<p class="textoNivel0">Los recibos se han bloqueado debido al estado del estudiante al momento de la generaci&oacute;n del lote
						principal. Las causas m&aacute;s comunes son: prueba acad&eacute;mica (J) y deudores de biblioteca o laboratorios. Tenga en cuenta
						que los recibos que usted genere, a trav&eacute;s de las plantillas, no se bloquean.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<?
	}
			
		function desbloquearRecibos($configuracion,$conexion)
		{
			foreach($_REQUEST as $clave => $valor) 
			{
				if(substr($clave,0,strlen("codDesbloquear"))=="codDesbloquear")
				{
					
					$variable[0]=$_REQUEST["codDesbloquear_".substr($clave,(strlen("codDesbloquear_")))];
					$variable[1]=$_REQUEST["recDesbloquear_".substr($clave,(strlen("recDesbloquear_")))];
					
					$cadena_sql=$this->sql->cadena_sql($configuracion,$conexion, "desbloquear",$variable);
					$registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "");
					//echo $cadena_sql."aw<br>";
					
					
				};
				
				
				
			}
				
			$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=admin_solicitud";
			$variable.="&opcion=bloqueado";
			$variable.="&accion=listaCompleta";
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$cripto=new encriptar();
			$variable=$cripto->codificar_url($variable,$configuracion);
			echo "<script>location.replace('".$pagina.$variable."')</script>"; 
		
		}
		
		function estadistica($configuracion,$contador)
		{
		
		//Estadisticas de recibos solicitados, impresos, en proceso de impresion, anulados y pagados
		//
		//
		
		//1. Rescatar los consolidados de recibos
		
		$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $valor,"estadistica");
		
		
		?><table style="text-align: left;" border="0"  cellpadding="5" cellspacing="0" class="bloquelateral" width="100%">
			<tr>
				<td >
					<table cellpadding="10" cellspacing="0" align="center">
						<tr class="bloquecentralcuerpo">
							<td valign="middle" align="right" width="10%">
								<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/info.png" border="0" />
							</td>
							<td align="left">
								Actualmente hay <b><? echo $contador ?> usuarios</b> registrados.
							</td>
						</tr>
						<tr class="bloquecentralcuerpo">
							<td align="right" colspan="2" >
								<a href="<?
								echo $configuracion["site"].'/index.php?page='.enlace('admin_dir_dedicacion').'&registro='.$_REQUEST['registro'].'&accion=1&hoja=0&opcion='.enlace("mostrar").'&admin='.enlace("lista"); 
								
								?>">Ver m&aacute;s informaci&oacute;n >></a>
							</td>
						</tr>
					</table> 
				</td>
			</tr>  
		</table>
		<?}
		
		
		
		function calcular_pago($configuracion,$acceso_db, $accesoOracle, $valores)
		{
			//1. Verificar pago inicial y reliquidado
			$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valores,"datosEstudiante");
			//echo $cadena_sql;
			$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle);	
			if(is_array($registro))
			{
				
				$valor_matricula=$registro[0][2];
				$valor_reliquidado=$valor_matricula;
				$valor_original=$registro[0][1];
				unset($registro);
				
				//2. Rescatar exenciones del estudiante
				$descripcion="";
				$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $valores,"exencionSolicitud");		
				$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);
				if(is_array($registro))
				{
					
					//3. Calcular el pago de acuerdo a las exenciones y construir las observaciones
					for($i=0;$i<count($registro);$i++)
					{
						$esta_exencion=(100-$registro[$i][7])/100;
						$valor_matricula=$valor_matricula*$esta_exencion;
						$descripcion=$descripcion." ".$registro[$i][8];
					}
					
				}
				$matricula[0]=$valor_matricula;
				$matricula[1]=$descripcion;
				$matricula[2]=$valor_original;
				$matricula[3]=$valor_reliquidado;
				
				//echo $matricula[1];
				return $matricula;
					
			}
			
		
		}
		
	function redireccionarInscripcion($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			
			unset($_REQUEST['action']);
			unset($_REQUEST['estudiante']);
			unset($_REQUEST['anno_per']);
			unset($_REQUEST['carrera']);			
			
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		switch($opcion)
		{
			case "bloqueadoEstudiante":
				$variable="pagina=admin_solicitud";
				$variable.="&hoja=1";
				$variable.="&opcion=bloqueadoEstudiante";
				$variable.="&estudiante=".$valor[0];
				$variable.="&carrera=".$valor[1];
				$variable.="&anno_per=".$valor[2];
				unset($valor);
				
			break;	
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);


		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();		
		
	}	
		
		
}

?>

