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
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion_usu_wo.class.php");

class funciones_adminSolicitudCoordinador extends funcionGeneral
{

	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include_once($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		
		$this->cripto=new encriptar();
		$this->tema=$tema;
		$this->sql=$sql;
			
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
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

                $this->validacion=new validarUsu();

		//Datos Generales
		$this->datosBasico["anno"]=$this->datosGenerales($configuracion,$this->accesoOracle, "anno") ;
		$this->datosBasico["periodo"]=$this->datosGenerales($configuracion,$this->accesoOracle, "per") ;

		$this->datosBasico["salarioMinimo"]=$this->datosGenerales($configuracion,$this->accesoOracle, "salarioMinimo") ;
		
		//Nombre del formulario y control de los campos que lo componen.
		$this->formulario="admin_solicitudCoordinador";
		$this->verificar="control_vacio(".$this->formulario.",'estudiante')";
	}
	
	
	function nuevoRegistro($configuracion,$acceso_db)
	{}
	
   	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
   	{}
   	
    	function corregirRegistro()
    	{}

    	function verificaCalendario($configuracion,$carrera){
	  
	  $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaCalendario",$carrera);
	  $verifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");


    
	return $verifica[0][0];
	}	

	function mostrarRegistro($configuracion,$registro, $total, $opcion="",$variable)
    	{
		switch($opcion)
		{
			case "multiplesCarreras":
				$this->multiplesCarreras($configuracion,$registro, $total, $variable);
				break;
				
			case "solicitado":
				$this->reciboSolicitado($configuracion,$registro, $total);
				break;
			case "historico":
				$this->historicoEstudiante($configuracion,$registro, $total);
				break;
		}
		
	}
	
		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
	function generarLoteRecibos($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		
		//Recorrer la matriz REQUEST de solicitudes de generacion
		foreach($_REQUEST as $clave => $valor) 
		{
			if(substr($clave,0,strlen("codGenerar"))=="codGenerar")
			{
				
				$variable[0]=$_REQUEST["codGenerar_".substr($clave,(strlen("codGenerar_")))];
				$variable[1]=$_REQUEST["recGenerar_".substr($clave,(strlen("recGenerar_")))];
				
				$this->generarRecibo($configuracion, $variable);
				
				
			};
			
		}
			
		$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
		$variable="pagina=admin_recibo";
		$variable.="&opcion=solicitado";
		$variable.="&accion=listaCompleta";
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$pagina.$variable."')</script>"; 
	
	}
	
	//IMPORTANTE
	//Esta es la funcion que efectivamente inserta los recibos en la base de datos ORACLE
	//Aqui se vuelven a recalcular los valores. De esto se desprende que cualquier cambio
	//en formularios que muestren información del recibo deben estar referidos a las funciones
	//de esta pagina 
	//TO REVIEW - TODO
	//Se recomienda utilizar en el futuro una clasde denominada datosSolicitud que integra
	//todas estas funciones y sea llamada desde todas las paginas que brinden informacion del Recibo 
		
	
	function generarRecibo($configuracion, $variable)
	{
		//Verifica diferifo de matrícula
		
  
		//Rescatar los datos de la solicitud
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"datosSolicitud",$variable[1]);
		$registroSolicitud=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
		
		if(is_array($registroSolicitud))
		{
		
			//0. Verificar Codigo
			//TODO 
			//Por seguridad debe volver a comprobarse la legitimidad del codigo de acuerdo a la carrera y el usuario
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiante",$variable[0]);
			$registroEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			
			if(is_array($registroEstudiante))
			{
			
				
				$this->datosBasico["nivelCarrera"]=$this->datosGenerales($configuracion,$this->accesoOracle, "nivelCarrera", $registroEstudiante[0][3]);
				//1. Calcular Valor Bruto
				$valorMatriculaBruto=$this->registroPagoBruto($registroSolicitud[0], $registroEstudiante[0], $this->datosBasico);
				
				//2. Calcular Descuentos y Exenciones
				$porcentaje=$this->porcentajeExencion($configuracion,$registroSolicitud[0][0]);
				
				//3. Calcular Valor Neto
				$valorMatriculaNeto=round($valorMatriculaBruto*(1-($porcentaje["exencion"]+$porcentaje["certificado"])/100));
				
				//2. Seleccionar las cuotas de la solicitud
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"cuotasSolicitud",$variable[1]);
				$registroCuotas=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
				
				$totalCuotas=$this->totalRegistros($configuracion, $this->acceso_db);
				
				
				for($contador=0;$contador<$totalCuotas;$contador++)
				{
					//Valor de la Cuota
					
					//Segun el tipo de plantilla
					if($registroSolicitud[0][9]==1)
					{
						//Pregrados
						//El valor neto de la matricula por el porcentaje correspondiente a la cuota
						$valorCuota=round($valorMatriculaNeto*$registroCuotas[$contador][2]/100);	
						
						//Valor Extraordinario
					
						if($totalCuotas==1)
						{
							//El valor de la matricula + 20% de pago extraordinario
							$valorCuotaExtra=round($valorCuota*1.2);
						}
						else
						{
							//No hay posibilidad de pago extraordinario
							$valorCuotaExtra=$valorCuota;
						}
					}
					else
					{
						//Posgrados
                        //verificamos si tiene exencion de la secretaria de educacion 
                        if($porcentaje["sed"]>0){
                               $valorMatriculaNeto=round($valorMatriculaNeto*(1-($porcentaje["sed"])/100));
                         }
						//Aplicar intereses
						switch($contador)
						{
							case 0:
								$valorCuota=round($valorMatriculaNeto*$registroCuotas[$contador][2]/100);	
								break;
							
							case 1:
								$valorCuota=round(($valorMatriculaNeto*$registroCuotas[$contador][2]/100)*1.016368);
								break;
							
							case 2:
								$valorCuota=round((($valorMatriculaNeto*$registroCuotas[$contador][2]/100)*1.016368)*1.016368);
								break;
						
						//Valor Extraordinario
						
						
						
						}
						//El valor de la matricula + 20% de pago extraordinario
						$valorCuotaExtra=round($valorCuota*1.2);
						
						
					}
					
								
					
					//7. Insertar Datos en ACESTMAT
					//7.A Rescatar secuencia
 					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"secuencia","");
  					$registroSecuencia=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  					
   					if(is_array($registroSecuencia))
   					{
						$estaSecuencia=$registroSecuencia[0][0];
						
						
						//7.B. Actualizar ACESTMAT
						$parametro[0]=$registroEstudiante[0][0];
						$parametro[1]=$this->datosBasico["anno"];
						$parametro[2]=$this->datosBasico["periodo"];
						$parametro[3]=$contador+1;
						
						$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizaracestmat",$parametro);
						$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
						
						//7.C. Insertar Cuota en ACESTMAT						
						$parametro[1]=$registroEstudiante[0][3];
						
						$concepto=$this->conceptosPago($configuracion, $variable[1]);
							
						$i=0;
					
						while (isset($concepto[$i]["codigo"]))
						{
							if($concepto[$i]["codigo"]==13)
							{
								$codConcepto=$concepto[$i]["codigo"];
							}
							$i++;
						}
						if(isset($codConcepto)==13)
						{	
							$parametro[2]=0;
							$parametro[3]=0;
						}
						else 
						{
							$parametro[2]=$valorCuota;
							$parametro[3]=$valorCuotaExtra;
						}	
						
						$parametro[4]=$this->datosBasico["anno"];
						$parametro[5]=$this->datosBasico["periodo"];
						$parametro[6]=$contador+1;
						//Fechas de Pago
						//Ordinario
						$parametro[7]=date("Ymd",$registroCuotas[$contador][3]);
						
						//Extraordinario
						if($registroSolicitud[0][9]==1)
						{
							//Pregrado
							
							if($totalCuotas==1)
							{
								// $registroCuotas[$contador][4]."contiene la fecha extraordinaria"; exit;
								$parametro[8]=date("Ymd",$registroCuotas[$contador][4]);
							}
							else
							{
								//Para diferidos las fechas ordinarias y extrordinarias son iguales
								$parametro[8]=date("Ymd",$registroCuotas[$contador][3]);
							}
						}
						else
						{
							//Postgrados
							$parametro[8]=date("Ymd",$registroCuotas[$contador][4]);
						}
						
						
						
						
						$parametro[9]=$estaSecuencia;
						
						//Si el estudiante tiene registradas deudas el recibo sale bloqueado
						
						$desbloqueado=$this->verificarEstado($configuracion,$registroEstudiante[0][0]);
						
						if($desbloqueado)
						{
							$parametro[10]=0;
						}
						else
						{
							$parametro[10]=1;
						}
						$parametro[11]=$porcentaje["observacion"];
						$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarCuota",$parametro);
						$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
						
						
  					}
  					else
  					{
  						return false;
  					}
 					unset($parametro);
  					//5. Calcular Conceptos
					$concepto=$this->conceptosPago($configuracion, $variable[1]);
					
					$j=0;
					
					//Datos para CONSIGA
					$sistematizacion=0;
					$carnet=0;
					$seguro=0;
					$valorOtrosConceptos=0;
					$vacacional=0;
					
					while (isset($concepto[$j]["codigo"])) 
					{	
						if($concepto[$j]["codigo"]==2)
						{
							$seguro=$concepto[$j]["valor"];
						}
						elseif($concepto[$j]["codigo"]==3)
						{
							$carnet=$concepto[$j]["valor"];
						}
						elseif($concepto[$j]["codigo"]==4)
						{
							$sistematizacion=$concepto[$j]["valor"];
						}
						elseif($concepto[$j]["codigo"]==13)
						{
							$vacacional=$concepto[$j]["valor"]; 
							$codConcepto=$concepto[$j]["codigo"];
						}
						
						$parametro[0]=$this->datosBasico["anno"];
						$parametro[1]=$estaSecuencia;
						$parametro[2]="23"; //TODO Rescatar el codigo directamente de la base de datos
						$parametro[3]=$concepto[$j]["codigo"];
						
						if($concepto[$j]["codigo"]==1)
						{
							if($codConcepto==13)
							{
								$parametro[4]=0;
							}
							else 
							{
								$parametro[4]=$valorCuota;
							}
							
						}
						else
						{
							$parametro[4]=$concepto[$j]["valor"];
							
							
							/*if($j==1)
							{
								$parametro[4]=$concepto[$j]["valor"];
								//$valorOtrosConceptos+=$concepto[$j]["valor"];
							}*/
							
						}
						//8. Insertar datos en ACREFEST
						
						if($contador==0)
						{
							//Si es la primera cuota se ingresan todos los conceptos
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConcepto",$parametro);	 						
						}
						elseif($concepto[$j]["codigo"]==1)
						{
							//Para las otras cuotas solo se inserta en concepto correspondiente al valor de la matricula
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConcepto",$parametro);
						
						}
						$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
						$j++;
					}
					//9. Crear cadena para CONSIGA
					//9.A. Crear Cadena
					//Crear una entrada al archivo plano
					/*
						FACTURA REFERENCIA 1
						NOMBRE
						IDENTIFICACION
						OBSERVACION
						ID CARRERA
						NOMBRE CARRERA
						SISTEMATIZACION
						CARNET
						SEGURO
						REF PAGO MATRICULA
						MATRICULA
						TOTAL ORDINARIO
						TOTAL EXTRAORDINARIO
						AÑO
						PERIODO
						FECHA ORDINARIO
						FECHA EXTRAORDINARIO
						BANCO
						CUENTA
						CUOTA
					*/
					$consiga[0]=$registroEstudiante[0][0];
					$consiga[1]=$registroEstudiante[0][2];
					$consiga[2]=$registroEstudiante[0][1];
					$consiga[3]=$porcentaje["observacion"];
					$consiga[4]=$registroEstudiante[0][3];
					$consiga[5]=$registroEstudiante[0][7];
					$consiga[6]=$sistematizacion;
					$consiga[7]=$carnet;
					$consiga[8]=$seguro;
					$consiga[9]="1".$registroEstudiante[0][3];
					$consiga[10]=$valorCuota;					
					$consiga[11]=$valorCuota+$valorOtrosConceptos;
					$consiga[12]=$valorCuotaExtra+$valorOtrosConceptos;
					$consiga[13]=$this->datosBasico["anno"];
					$consiga[14]=$this->datosBasico["periodo"];
					$consiga[15]=date("m/d/y",$registroCuotas[$contador][3]);
					$consiga[16]=date("m/d/y",$registroCuotas[$contador][4]);
					$consiga[17]="OCCIDENTE";
					$consiga[18]="230-81461-8";
					$consiga[19]=$contador+1;
					$consiga[20]=$vacacional;
						
					$cadenaConsiga="";	
					foreach($consiga as $clave => $valor)
					{
						$cadenaConsiga.="&".$valor;
					}
					
					//9.B. Guardar cadena
					$parametro[0]=$estaSecuencia;
					$parametro[1]=$this->datosBasico["anno"];
					$parametro[2]=$cadenaConsiga;
					$parametro[3]=$this->usuario;
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"insertarConsiga",$parametro);
 					$resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");
					
					//9.C. Actualizar el estado de la solicitud
					unset($parametro);
					$parametro[0]=$estaSecuencia;
					$parametro[1]=$porcentaje["observacion"];
					$parametro[2]=$registroSolicitud[0][0];
					
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"actualizarSolicitud",$parametro);
 					$resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");
 					
 			 					
					
				}
				return true; 
			}
		}
		else
		{
			echo "Los recibos no se generaron";
		
		}
	}
	
	function verificarEstado($configuracion,$valor)
	{
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"deudaEstudiante",$valor);
 		$registroDeuda=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		/*
		if(is_array($registroEstudiante))
		{
			if($registroEstudiante[0][5]=="J")
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		
		}		*/
				
		if(is_array($registroDeuda))
		{
			return FALSE;
		}
		return TRUE;
										
	}

	function confirmarGeneracion($configuracion)
	{?>
			<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<td>
								Confirmar la generaci&oacute;n  de Recibos <br>
								<hr class="hr_subtitulo">
								</td>
							</tr>
							<tr>
								<td class="centrar">
									<table class="contenidotabla">
										<? 
								$formulario="admin_solicitudCoordinador";
								$celdas=0;
								echo "<form method='post' action='index.php' name=".$formulario.">"; ?>
									<?
								$i=0;
								foreach($_REQUEST as $clave => $valor) 
								{
									if(substr($clave,0,strlen("selGenerar"))=="selGenerar")
									{	
										
										if($celdas==0)
										{
											echo "<tr>";
										}
										echo "<td  class='cuadro_plano centrar'>\n";
										echo "<input type='hidden' name='codGenerar_".$i."' value='".$_REQUEST["codGenerar_".substr($clave,(strlen("selGenerar_")))]."'>";
										echo "<input type='hidden' name='recGenerar_".$i."' value='".$_REQUEST["selGenerar_".substr($clave,(strlen("selGenerar_")))]."'>";
										echo $_REQUEST["codGenerar_".substr($clave,(strlen("selGenerar_")))]."</td>";
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
								if($i>0)
								{
									echo "<tr><td colspan='5' class='centrar'>";
									echo "<input type='hidden' name='action' value='".$formulario."' >";
									echo "<br> <input type='submit' name='aceptar' value='Generar' ></td></tr>";
									echo "<tr><td colspan='5' class='texto_elegante'>";
									echo "<p>Apreciado(a) Coordinador(a):</p>";
									echo "<p>Tenga presente que al momento de oprimir el bot&oacute;n <span class='texto_negrita'>Generar</span> ";
									echo "se realizan AUTOMATICAMENTE las siguientes actividades :</p>";
									echo "<p><ul><li> Las solicitudes se convierten en recibos que <span class='texto_negrita'>inmediatamente</span> pueden ser consultados y descargados por los estudiantes.</li>";
									echo "<li>Las solicitudes cambian de estado y se pueden consultar en el men&uacute;: <span class='texto_negrita'>Recibos->Generados</span> </li>";
									echo "<li>Los recibos generados sobrescribir&aacute;n los que haya generado anteriormente para el mismo c&oacute;digo.</li>";
									echo "<li>UNA VEZ GENERADOS LOS RECIBOS NO SE PUEDEN BLOQUEAR O BORRAR.</li>";
									echo "</ul></p>";
									echo "</td></tr>";
								}
								else
								{
									echo "<tr><td colspan='5' class='centrar'>";
									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
									$cadena="No seleccion&oacute; solicitudes para generaci&oacute;n.";
									alerta::sin_registro($configuracion,$cadena);	
									echo "</td></tr>";
									
								
								
								}
		
		
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
	
	function consultarCarrera($configuracion)
	{
		if(isset($_REQUEST["carrera"]))
		{
			$variable[0]=$_REQUEST["carrera"];
		}
		else
		{
			$variable[0]=99999;
		}
		
		$variable[1]=$this->datosBasico["anno"];
		$variable[2]=$this->datosBasico["periodo"];
		$verifica=$this->verificaCalendario($configuracion,$_REQUEST["carrera"]);

		      if($verifica=='N'){
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="En la actualidad las fechas del calendario acad&eacute;mico no estan hablilitadas para generar recibos de pago .";
			alerta::sin_registro($configuracion,$cadena);	
		      }

		if(isset($_REQUEST["opcion"]))
		{
			switch($_REQUEST["opcion"])
			{
				case "solicitado":
					//Se seleccionan los recibos solicitados de acuerdo a los criterios de busqueda
					switch($_REQUEST["accion"])
					{
						case "listaCompleta":
							
							//Paginacion
							//Obtener el total de registros
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"totalSolicitud",$variable);
							$registro=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
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
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"solicitud",$variable);
							break;					
					}
					break;
				
				default:
					break;
			}
			if(isset($cadena_sql))
                        {
                            $registro=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
                        }
			if((isset($registro))&&is_array($registro))
			{
				//Obtener el total de registros
				$totalRegistros=$this->totalRegistros($configuracion, $this->acceso_db);
				$this->mostrarRegistro($configuracion,$registro, $totalRegistros, "solicitado","");
				
			}
			else
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena="En la actualidad no tiene ning&uacute;n recibo registrado para generaci&oacute;n.";
      				alerta::sin_registro($configuracion,$cadena);	
				
			}
		}
		else
		{
		
		}
		
	}
	
	//Recibos solicitados por coordinación
	function reciboSolicitado($configuracion,$registro, $total)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		$menu=new navegacion();
		$variableNavegacion["pagina"]="admin_recibo";
		$variableNavegacion["opcion"]="solicitado";	
		$variableNavegacion["accion"]="listaCompleta";
		$variableNavegacion["carrera"]=$registro[0][3];
		$parametro[1]=$this->datosBasico["anno"];
                $parametro[2]=$this->datosBasico["periodo"];
		
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
		setlocale(LC_MONETARY, 'en_US');
		
		
		
		echo "<form>";
		?><table class='tablaMarco'>
				<tr class='bloquecentralcuerpo'>
					<td>		
			<?if($this->totalPaginas>1)
				{
			?>
							<table class='tablaMarco'>
							<tr>
								<td>
									<? 
										$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
									?>
								</td>
							</tr>
							</table><?
				}			
			
			for($i=0;$i<$total;$i++)
			{
				$parametro[0]=$registro[$i][2];
				
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificapago",$parametro);
				$registroVerificaPago=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  
				unset($registroExencion);
				unset($registroConcepto);
				//Rescatar el nivel de la carrera
				$this->datosBasico["nivelCarrera"]=$this->datosGenerales($configuracion,$this->accesoOracle, "nivelCarrera", $registro[0][3]);
				//Rescatar el valor de la matricula
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiante",$registro[$i][2]);
				$registroEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
				
				if(is_array($registroEstudiante))
				{
					
					$valorMatriculaBruto=$this->registroPagoBruto($registro[$i], $registroEstudiante[0], $this->datosBasico);
					
					
					//Rescatar los conceptos
					
					$concepto= $this->conceptosPago($configuracion, $registro[$i][0]);
					
					$j=0;
					$losConceptos="";					
					while (isset($concepto[$j]["codigo"])) 
					{
						if($concepto[$j]["codigo"]>1)
						{
							$losConceptos.="+".$concepto[$j]["nombre"];
						}
						if($concepto[$j]["codigo"]==13)
						{
								$codConcepto=$concepto[$j]["codigo"];
						}
						$j++;
					}
					
					//Calcular descuentos y exenciones
					$porcentaje=$this->porcentajeExencion($configuracion,$registro[$i][0]);
					
					//Valor Neto a Pagar de matricula 
					$valorMatriculaNeto=round($valorMatriculaBruto*(1-($porcentaje["exencion"]+$porcentaje["certificado"])/100));
					
                    //verificamos si tiene descuento por pago de la secretaria de educacion
                     if($porcentaje["sed"]>0){
                     $valorMatriculaNeto=round($valorMatriculaNeto*(1-($porcentaje["sed"])/100));
					}
					if(is_array($registroVerificaPago) && $losConceptos!='+VACACIONAL')
					{
						?><table class='tablaMarco'>
							<tr class='bloquecentralcuerpo'>
								<td class='cuadro_plano cuadro_color' width='15%'>
								<span class='texto_negrita'><? echo $registro[$i][2] ?></span>
							</td>
							<td class='cuadro_plano cuadro_color' width='45%'>
							<? echo $registroEstudiante[0][2] ?>
							</td>
							<td class='cuadro_plano' width='25%'>
							<? 
							echo "Pagar en <span class='texto_negrita'>";
							//Si es pregrados
							if($registro[0][9]==1)
							{

								if($registroEstudiante[0][4]=='S' && $registroEstudiante[0][11]=='S')
								{
									echo "2";
								}
								else
								{
								
									echo "1";
								}
							
							}
							else
							{
								echo $registro[$i][4];
							}
							echo "</span> cuota(s)" ?>
							</td>
							<td align="center" width="10%" class="cuadro_plano">
							<a href="<?
								$variable="pagina=borrar_registro";
								$variable.="&opcion=solicitud";
								$variable.="&registro=".$registro[$i][0];
								$redireccion="";		
								reset ($_REQUEST);
								while (list ($clave, $val) = each ($_REQUEST)) 
								{
									$redireccion.="&".$clave."=".$val;
									
								}
								
								$variable.="&redireccion=".$this->cripto->codificar_url($redireccion,$configuracion);
								
								$variable=$this->cripto->codificar_url($variable,$configuracion);
								
								echo $indice.$variable;	
							?>"><img width="24" heigth="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/boton_borrar.png"?>" alt="Borrar el registro" title="Borrar el registro" border="0" /></a>	
							</td>
							</tr>
							<tr class='bloquecentralcuerpo'>
							<td class='cuadro_plano' width='30%' colspan='2'>
							<? echo $porcentaje["observacion"]; ?>
							</td>
							<td class='cuadro_plano centrar fondoImportante' colspan='2'>
							<? echo money_format('$ %!.0i', $valorMatriculaNeto).$losConceptos;
								
								
								?>
							
							</td>
							</tr>
							<tr class='bloquecentralcuerpo'>
								<td class='cuadro_plano centrar fondoImportante'  colspan='4'>
									El estudiante tiene un recibo <b>PAGO</b> para el periodo acad&eacute;mico <? echo $parametro[1]?> -  <? echo $parametro[2]?> , si el estudiante debe una cuota o m&aacute;s, la reexpedici&oacute;n del recibo se debe realizar por la opci&oacute;n <b>"Extempor&aacute;neos"</b>. 
								</td>
							</tr>
							</table>
							<br>
						<?
					}
					else
					{
						?><table class='tablaMarco'>
							<tr class='bloquecentralcuerpo'>
								<td class='cuadro_plano cuadro_color' width='15%'>
								<span class='texto_negrita'><? echo $registro[$i][2] ?></span>
							</td>
							<td class='cuadro_plano cuadro_color' width='45%'>
							<? echo $registroEstudiante[0][2] ?>
							</td>
							<td class='cuadro_plano' width='25%'>
							<? 
							echo "Pagar en <span class='texto_negrita'>";
							//Si es pregrados
							if($registro[0][9]==1)
							{

								if($registroEstudiante[0][4]=='S' && $registroEstudiante[0][11]=='S')
								{
									echo "2";
								}
								else
								{
								
									echo "1";
								}
							
							}
							else
							{
								echo $registro[$i][4];
							}
							echo "</span> cuota(s)" ?>
							</td>
							<td align="center" width="10%" class="cuadro_plano">
							<a href="<?
								$variable="pagina=borrar_registro";
								$variable.="&opcion=solicitud";
								$variable.="&registro=".$registro[$i][0];
								$redireccion="";		
								reset ($_REQUEST);
								while (list ($clave, $val) = each ($_REQUEST)) 
								{
									$redireccion.="&".$clave."=".$val;
									
								}
								
								$variable.="&redireccion=".$this->cripto->codificar_url($redireccion,$configuracion);
								
								$variable=$this->cripto->codificar_url($variable,$configuracion);
								
								echo $indice.$variable;	
							?>"><img width="24" heigth="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/boton_borrar.png"?>" alt="Borrar el registro" title="Borrar el registro" border="0" /></a>	
							</td>
							</tr>
							<tr class='bloquecentralcuerpo'>
							<td class='cuadro_plano' width='30%' colspan='2'>
							<? echo $porcentaje["observacion"]; ?>
							</td>
							<td class='cuadro_plano centrar fondoImportante'>
							<?
							//Verificamos que el concepto no sea curso vacacional, si es, le pone valor matrícula 0
							//ECHO $codConcepto;
							if($losConceptos!='+VACACIONAL')
							{	 
								echo money_format('$ %!.0i', $valorMatriculaNeto).$losConceptos;
							}
							else 
							{
								echo money_format('$ %!.0i', 0).$losConceptos;
							}
							?>
							
							</td>
							<td class='cuadro_plano centrar'>
							<?
							//Numero de Solicitud y Codigo de Estudiante 
							echo "<input type='hidden' name='codGenerar_".$i."' id='codGenerar_".$i."' value='".$registro[$i][2]."'><input type='checkbox' name='selGenerar_".$i."' id='selGenerar_".$i."' value='".$registro[$i][0]."'>";  ?>
							</td>
							</tr>
							</table>
							<br>
						<?
					}
					
				}
				
			}
			
			$mi_variable="pagina=admin_recibo";
			$mi_variable.="&confirmar=1";
			$mi_variable=$this->cripto->codificar_url($mi_variable,$configuracion);
			//$mi_variable=$this->cripto->codificar($mi_variable,$configuracion);
			echo "<table  class='tablaMarco'>";
			echo "<tr><td colspan='6' class='centrar cuadro_plano'>";

			$verifica=$this->verificaCalendario($configuracion,$variableNavegacion["carrera"]);
			if($verifica!='N'){
				//echo '<input type="hidden" name="confirmar" value="true">';
				echo "<input type='hidden' name='formulario' value='".$mi_variable."'><br>";
				//echo "<input type='hidden' name='action' value='$this->formulario' >";  
				echo "<input type='submit' name='aceptar' value='::.Generar.::' ></td></tr>";	
			}
			echo "</table>";
			echo "</form>";
			
									
			if($this->totalPaginas>1)
			{
		?>
						<table class='tablaMarco'>
						<tr>
							<td>
								<? 
									$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
								?>
							</td>
						</tr>
						</table><?
			}			
		?>	</td>
			</tr>
			</table>
			<?
	}
	
	//Recibo solicitado por estudiante.
	function reciboSolicitadoest($configuracion,$codigo)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"reciboEstudiante",$codigo);
	  	$registro=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql,"busqueda");

		$menu=new navegacion();
		$variableNavegacion["pagina"]="admin_recibo";
		$variableNavegacion["opcion"]="solicitado";	
		$variableNavegacion["accion"]="listaCompleta";
		$variableNavegacion["carrera"]=$_REQUEST["carrera"];
		$variableNavegacion["estudiante"]=$codigo;
		
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
		setlocale(LC_MONETARY, 'en_US');
		
		echo "<form>";

			$contadorRegistros=0;	

			if(!is_array($registro))
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena="El estudiante ".$codigo." no tiene recibos pendientes para generar.<br>";
	
				$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";	
				alerta::sin_registro($configuracion,$cadena);
			}
			else{
                            $this->totalPaginas=0;
			?><table class='tablaMarco'>
					<tr class='bloquecentralcuerpo'>
						<td>		
				<?if($this->totalPaginas>1) //$this->totalPaginas>1
					{
				?>
								<table class='tablaMarco'>
								<tr>
									<td>
										<? 
											$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
										?>
									</td>
								</tr>
								</table><?
					}			
				
		
					while(isset($registro[$contadorRegistros][0]))
					{
						
						
						unset($registroExencion);
						unset($registroConcepto);
						//Rescatar el nivel de la carrera
						$this->datosBasico["nivelCarrera"]=$this->datosGenerales($configuracion,$this->accesoOracle, "nivelCarrera", $registro[0][3]);
						//Rescatar el valor de la matricula
						$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiante",$registro[$contadorRegistros][2]);
						$registroEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
						
						if(is_array($registroEstudiante))
						{
							
							$valorMatriculaBruto=$this->registroPagoBruto($registro[$contadorRegistros], $registroEstudiante[0], $this->datosBasico);
							
							
							//Rescatar los conceptos
							
							$concepto= $this->conceptosPago($configuracion, $registro[$contadorRegistros][0]);
                            $i=0;
							$j=0;
							$losConceptos="";					
							while (isset($concepto[$j]["codigo"])) 
							{
								if($concepto[$j]["codigo"]>1)
								{
									$losConceptos.="+".$concepto[$j]["nombre"];
								}
								$j++;
							}
							
							//Calcular descuentos y exenciones
							$porcentaje=$this->porcentajeExencion($configuracion,$registro[$i][0]);
							
							//Valor Neto a Pagar de matricula 
							$valorMatriculaNeto=round($valorMatriculaBruto*(1-($porcentaje["exencion"]+$porcentaje["certificado"])/100));
							
							//verificamos si tiene descuento por pago de la secretaria de educacion
                            if($porcentaje["sed"]>0){
                                 $valorMatriculaNeto=round($valorMatriculaNeto*(1-($porcentaje["sed"])/100));
                            }
							
							?><table class='tablaMarco'>
								<tr class='bloquecentralcuerpo'>
									<td class='cuadro_plano cuadro_color' width='15%'>
									<span class='texto_negrita'><? echo $registro[$contadorRegistros][2] ?></span>
								</td>
								<td class='cuadro_plano cuadro_color' width='45%'>
								<? echo $registroEstudiante[0][2] ?>
								</td>
								<td class='cuadro_plano' width='25%'>
								<? 
								echo "Pagar en <span class='texto_negrita'>";
								//Si es pregrados
								if($registro[0][9]==1)
								{
									if($registroEstudiante[0][4]=='S')
									{
										echo "2";
									}
									else
									{
									
										echo "1";
									}
								
								}
								else
								{
									echo $registro[$contadorRegistros][4];
								}
								echo "</span> cuota(s)" ?>
								</td>
								<td align="center" width="10%" class="cuadro_plano">
								<a href="<?
									$variable="pagina=borrar_registro";
									$variable.="&opcion=solicitud";
									$variable.="&registro=".$registro[$contadorRegistros][0];
									$redireccion="";		
									reset ($_REQUEST);
									while (list ($clave, $val) = each ($_REQUEST)) 
									{
										$redireccion.="&".$clave."=".$val;
										
									}
									
									$variable.="&redireccion=".$this->cripto->codificar_url($redireccion,$configuracion);
									
									$variable=$this->cripto->codificar_url($variable,$configuracion);
									
									echo $indice.$variable;	
								?>"><img width="24" heigth="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/boton_borrar.png"?>" alt="Borrar el registro" title="Borrar el registro" border="0" /></a>	
								</td>
								</tr>
								<tr class='bloquecentralcuerpo'>
								<td class='cuadro_plano' width='30%' colspan='2'>
								<? echo $porcentaje["observacion"]; ?>
								</td>
								<td class='cuadro_plano centrar fondoImportante'>
								<? echo money_format('$ %!.0i', $valorMatriculaNeto).$losConceptos;
								?>
								
								</td>
								<td class='cuadro_plano centrar'>
								<?
								//Numero de Solicitud y Codigo de Estudiante 
								echo "<input type='hidden' name='codGenerar_".$contadorRegistros."' id='codGenerar_".$contadorRegistros."' value='".$registro[$contadorRegistros][2]."'><input type='checkbox' name='selGenerar_".$contadorRegistros."' id='selGenerar_".$contadorRegistros."' value='".$registro[$contadorRegistros][0]."'>";  ?>
								</td>
								</tr>
								</table>
								<br><?
							
						}
						$contadorRegistros++;
						
					}
					
					
					
					$mi_variable="pagina=admin_recibo";
					$mi_variable.="&confirmar=1";
					$mi_variable=$this->cripto->codificar($mi_variable,$configuracion);
					echo "<table  class='tablaMarco'>";
					echo "<tr><td colspan='6' class='centrar cuadro_plano'>";
		

		
					
					$verifica=$this->verificaCalendario($configuracion,$variableNavegacion["carrera"]);
					if($verifica!='N'){
						echo "<input type='hidden' name='formulario' value='".$mi_variable."'>\n";
						echo "<input type='submit' name='aceptar' value='::.Generar.::' ></td></tr>";	
					}	
					echo "</table>";

			}

			echo "</form>";			
									
			if($this->totalPaginas>1) //$this->totalPaginas>1
			{
		?>
						<table class='tablaMarco'>
						<tr>
							<td>
								<? 
									$menu->menu_navegacion($configuracion,$this->paginaActual, $this->totalPaginas, $variableNavegacion);
								?>
							</td>
						</tr>
						</table>
		<?
			}			

	}
	
	function registroPagoBruto($unaSolicitud, $unEstudiante, $datosBasicos)
	{
		//Total sin exenciones
		
		$valorMatriculaBruto=0;
		
		//Buscar el tipo de plantilla
		switch($unaSolicitud[9])
		{
			case 1:
				$valorMatriculaBruto=$unEstudiante[6];
			break;
			
			case 2://Postgrados por creditos
				
				switch($unEstudiante[10])
				{
					case 33://Ingenieria
						if($datosBasicos["nivelCarrera"]=='POSGRADO' || $datosBasicos["nivelCarrera"]=='ESPECIALIZACION')
						{
							$valorMatriculaBruto=0.5*$datosBasicos["salarioMinimo"]*$unaSolicitud[10];
						}
						elseif($datosBasicos["nivelCarrera"]=='MAESTRIA')
						{
							$valorMatriculaBruto=0.55*$datosBasicos["salarioMinimo"]*$unaSolicitud[10];
						}
						elseif($datosBasicos["nivelCarrera"]=='DOCTORADO')
						{
							$valorMatriculaBruto=0.55*$datosBasicos["salarioMinimo"]*$unaSolicitud[10];
						}
						break;
					
					case 23:
					case 24:
					case 32:
					case 101:
					
						if($datosBasicos["nivelCarrera"]=='POSGRADO' || $datosBasicos["nivelCarrera"]=='ESPECIALIZACION')
						{
							$valorMatriculaBruto=0.35*$datosBasicos["salarioMinimo"]*$unaSolicitud[10];
						}
						elseif($datosBasicos["nivelCarrera"]=='MAESTRIA')
						{
							$valorMatriculaBruto=0.5*$datosBasicos["salarioMinimo"]*$unaSolicitud[10];
						}
						break;
				}
				
			break;
			
			case 3:
			
				$valorMatriculaBruto=$datosBasicos["salarioMinimo"]*$unaSolicitud[10];			
			break;
			
		}
		
		return $valorMatriculaBruto;
	}
	
	
	function porcentajeExencion($configuracion,$variable)
	{
		//Rescatar Exenciones
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"exencionSolicitud",$variable);						
		$registroExencion=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
		
		$porcentaje["observacion"]=" ";
		$porcentaje["certificado"]=0;
		$porcentaje["exencion"]=0;
		$porcentaje["sed"]=0;
		
		if(is_array($registroExencion))
		{
			//Se deben seguir ciertas reglas para las exenciones
			//1.Maximo deben existir dos exenciones por estudiante (en realidad un descuento y una exencion)
			//2. Una de esas exenciones debe ser el 10% (1)
			//3. Exenciones de 100% no aceptan otro tipo de exencion
			//4. Un estudiante de postgrado puede ser egresado(11) o monitor(10) (excluyente) 
			
			$j=0;
			while (isset($registroExencion[$j][1]) && $j<2) 
			{
				if($registroExencion[$j][1]!=16){
				$porcentaje["observacion"].=$registroExencion[$j][4]." ";
				
				if($registroExencion[$j][1]==1)
				{
					$porcentaje["certificado"]=$registroExencion[$j][3];
				}
				else
				{
					$porcentaje["exencion"]=$registroExencion[$j][3];
				}
                                }
				$j++;
                                
			}
                        //verificacion de descuento para pagar por la secretaria de educacion
                        $j=0;
                        while (isset($registroExencion[$j][1]) ) 
			{
				if($registroExencion[$j][1]==16){
                                            $porcentaje["observacion"].=$registroExencion[$j][4]." ";
                                            $porcentaje["sed"]=$registroExencion[$j][3];
		}
                                $j++;
                                
			}
		}
		return $porcentaje;
	}
	
	function conceptosPago($configuracion, $variable)
	{
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"conceptoSolicitud",$variable);						
		$registroConcepto=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
		
		if(is_array($registroConcepto))
		{
			$j=0;
			
			while (isset($registroConcepto[$j][1])) 
			{
				$concepto[$j]["codigo"]=$registroConcepto[$j][1];
				$concepto[$j]["nombre"]=$registroConcepto[$j][2];
				
				//Si el concepto es 1 (matricula) se descarta
				if($registroConcepto[$j][1]>1)
				{
					//Si el concepto es 2 (seguro) el valor se toma directamente de la tabla referenciaPago de weboffice
					if($registroConcepto[$j][1]==2)
					{
						$concepto[$j]["valor"]=$registroConcepto[$j][3];
					}
					//Si el concepto es 13 (vacacional) el valor se toma directamente
					elseif($registroConcepto[$j][1]==13)
					{
						$concepto[$j]["valor"]=$registroConcepto[$j][3];
					}
					else
					{
						//En todos los demas casos se calcula
						if($registroConcepto[$j][5]=="SMLV")
						{
							$concepto[$j]["valor"]=round(($registroConcepto[$j][4]*$this->datosBasico["salarioMinimo"])/100)*100;
						}
						elseif($registroConcepto[$j][5]=="SMDLV")
						{
							$concepto[$j]["valor"]=round(($registroConcepto[$j][4]*$this->datosBasico["salarioMinimo"]/30)/100)*100;
						}
					}
				}
				
				$j++;
			}
		}
		
		return $concepto;
	
	}
	
	
	
	function multiplesCarreras($configuracion,$registro, $total, $variable)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
                $tab=0;
		?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
		<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown" >
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td>
							Consultar recibo solicitado por estudiante<br>
							<hr class="hr_subtitulo">
							</td>
						</tr>
					</table>
					
					<table class="formulario" align="center">
						<tr align='center'>
							<td>
							C&oacute;digo estudiante:
							<input type='text' name='estudiante' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' >
							<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
							<input type='hidden' name='opcion' value='solicitadoEstudiante'>
							<input value="Consultar" name="consultar" tabindex='<? echo $tab++ ?>' onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>								

							</td>

						</tr>						



					</table>
				</td>
			</tr>
		</table>
		</form>		
		
		
		<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<td>
								Recibos Solicitados por Proyecto Curricular<br>
								<hr class="hr_subtitulo">
								</td>
							</tr>
							<tr>
								<td>
									<table class='contenidotabla'>
										<tr class='cuadro_color'>
											<td class='cuadro_plano centrar ancho10'' rowspan='2'>Recibos Solicitados</td>
											<td class='cuadro_plano centrar' colspan='2'>Proyecto Curricular</td>
										</tr>
										<tr class='cuadro_color'>
											<td class='cuadro_plano centrar ancho10' >C&oacute;digo</td>
											<td class='cuadro_plano centrar'>Nombre </td>
										</tr><?
											for($contador=0;$contador<$total;$contador++)
											{
												
												$parametroSolicitud[0]=$registro[$contador][0];
												$parametroSolicitud[1]=$this->datosBasico["anno"];
												$parametroSolicitud[2]=$this->datosBasico["periodo"];
												
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "totalSolicitud",$parametroSolicitud);
												$registroCarrera=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
												if(is_array($registroCarrera))
												{
													if($registroCarrera[0][0]>0)
													{
														//Con enlace a la busqueda
														$parametro="pagina=admin_recibo";
														$parametro.="&hoja=1";
														$parametro.="&opcion=solicitado";
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
					<td class='cuadro_plano cuadro_brown'>
						<p class="textoNivel0">Por favor realice click sobre el nombre del proyecto curricular que desee trabajar.</p>
					</td>
				</tr>
			</tbody>
		</table>
		<?
	}
	
	function historicoEstudiante($configuracion,$registro, $total)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
                $tab=0;
		?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
		<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown" >
								<lu>
									<li>
										Para consultar el hist&oacute;rico de recibos de pago, digite el c&oacute;digo del estudiante y haga click en "Consultar"
									</li>
								</lu>
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td>
							Consultar hist&oacute;rico por estudiante<br>
							<hr class="hr_subtitulo">
							</td>
						</tr>
					</table>
					
					<table class="formulario" align="center">
						<tr align='center'>
							<td>
							C&oacute;digo estudiante:
							<input type='text' name='estudiante' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' >
							<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
							<input type='hidden' name='opcion' value='historicoEstudiante'>
							<input value="Consultar" name="consultar" tabindex='<? echo $tab++ ?>' style="cursor:pointer;" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>								

							</td>

						</tr>						



					</table>
				</td>
			</tr>
		</table>
		</form>
		<?
	}
	
	function consultarHistoricoEstudiante($configuracion)
	{
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		
		if($this->nivel==4){
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "carreraCoordinador",$this->usuario);
                    $registroCarreras=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda"); 
		
		}elseif($this->nivel==110 || $this->nivel==114){
                        $proyectos =$this->validacion->consultarProyectosAsistente($this->usuario,  $this->nivel, $this->accesoOracle,$configuracion, $this->acceso_db);
                         foreach ($proyectos as $key => $proyecto) {
                            $registroCarreras[$key][0]= $proyecto[0];
                            $registroCarreras[$key][1]= $proyecto[4];
                         }
                }
		
		$i=0;
		while(isset($registroCarreras[$i][0]))
		{
			$valor[0]=$_REQUEST['estudiante'];
			$valor[1]=$registroCarreras[$i][0];
						
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "historicoEstudiante",$valor);
			$registroHistorico=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
			if(is_array($registroHistorico) && $registroCarreras[$i][0]==$registroHistorico[0][11])
			{
				$this->redireccionarInscripcion($configuracion, "mostarRegistroHistorico",$valor);
			}
			else
			{
				$cierto=2;
			}
		$i++;
		}
		if($cierto==2)
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="El estudiante ".$valor[0]." no tiene registros hist&oacute;ricos de recibos de pago, o no pertenece a su Coordinaci&oacute;n.<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);	
		}
	}
	
	function registrosHistorico($configuracion,$registro, $total)
	{
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		
		if($this->nivel==4){
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "carreraCoordinador",$this->usuario);
                    $registroCarreras=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                
		}elseif($this->nivel==110 || $this->nivel==114){
                        $proyectos =$this->validacion->consultarProyectosAsistente($this->usuario,  $this->nivel, $this->accesoOracle,$configuracion, $this->acceso_db);
                         foreach ($proyectos as $key => $proyecto) {
                            $registroCarreras[$key][0]= $proyecto[0];
                            $registroCarreras[$key][1]= $proyecto[4];
                         }
                }
		                
		$i=0;
		while(isset($registroCarreras[$i][0]))
		{
			$valor[0]=$_REQUEST['estudiante'];
			$valor[1]=$registroCarreras[$i][0];
					
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "historicoEstudiante",$valor);
			$registroHistorico=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			
			$usuario=$_REQUEST['estudiante'];
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosEstudiante",$usuario);
			$registroEstudiantes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			
			
			if($registroHistorico[0][11]==$registroCarreras[$i][0])
			{
				if(!is_array($registroHistorico))
				{
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
					$cadena="El estudiante ".$codigo." no tiene recibos generados para este periodo.<br>";
					$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";	
					alerta::sin_registro($configuracion,$cadena);
				}
				else
				{	
					$html='<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >';
					$html='		<tr class="texto_subtitulo">';
					$html.='		<td>';
					$html.='			Hist&oacute;rico de Recibos Generados para el estudiante:<br><br>';
					$html.='			C&oacute;digo:'.$registroEstudiantes[0][0].'<br>';
					$html.='			Nombre:'.$registroEstudiantes[0][2].'<br>';
					$html.='		</td>';
					$html.='	</tr>';
					$html.='</table>';
					
					$html.='<table class="formulario">';
					$html.='	<tr class="texto_negrita">';
					$html.='		<td rowspan="2">';
					$html.='			Secuencia';
					$html.='		</td>';
					$html.='		<td rowspan="2">';
					$html.='			Cuota';
					$html.='		</td>';
					$html.='		<td colspan="2">';
					$html.='			Ordinario';
					$html.='		</td>';
					$html.='		<td colspan="2">';
					$html.='			Extraordinario';
					$html.='		</td>';
					$html.='		<td colspan="2">';
					$html.='			Periodo';
					$html.='		</td>';	
					$html.='		<td rowspan="2">';
					$html.='			Pago';
					$html.='		</td>';	
					$html.='	</tr>';	
					$html.='	<tr class="texto_negrita">';
					$html.='		<td>';
					$html.='			Fecha';
					$html.='		</td>';
					$html.='		<td>';
					$html.='			Valor';
					$html.='		</td>';
					$html.='		<td>';
					$html.='			Fecha';
					$html.='		</td>';	
					$html.='		<td>';
					$html.='			Valor';
					$html.='		</td>';	
					$html.='		<td>';
					$html.='			Ano';
					$html.='		</td>';	
					$html.='		<td>';
					$html.='			Per';
					$html.='		</td>';	
					$html.='	</tr>';	
					
					$i=0;
					while(isset($registroHistorico[$i][0])){
						if($registroHistorico[$i][8]=='S'){
						$html.='<tr class="texto_subtitulo_verde">';
						}
						if($registroHistorico[$i][8]=='N'){
						$html.='<tr class="texto_subtitulo_rojo">';
						}					
						$html.='		<td>';
						$html.=				$registroHistorico[$i][4];
						$html.='		</td>';
						$html.='		<td>';
						$html.=				$registroHistorico[$i][5];
						$html.='		</td>';	
						$html.='		<td>';
						$html.=				$registroHistorico[$i][6];
						$html.='		</td>';
						$html.='		<td>';
						$html.=				$registroHistorico[$i][0];
						$html.='		</td>';
						$html.='		<td>';
						$html.=				$registroHistorico[$i][7];
						$html.='		</td>';	
						$html.='		<td>';
						$html.=				$registroHistorico[$i][1];
						$html.='		</td>';
						$html.='		<td>';
						$html.=				$registroHistorico[$i][9];
						$html.='		</td>';	
						$html.='		<td>';
						$html.=				$registroHistorico[$i][10];
						$html.='		</td>';	
						$html.='		<td>';
						$html.=				$registroHistorico[$i][8];
						$html.='		</td>';	
						$html.='	</tr>';	
					$i++;	
					}	
									
					$html.='</table>';
				echo $html;
				}
			}
		$i++;
		}
		?>
		<table class="formulario">
				<tr>
				<td colspan="14" class="tabla_alerta">
					<center><input name="button" type="image" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/impresora.gif" border="0" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer;" title="Click par imprimir el reporte"></center>
				</td>
			</tr>
		</table>
		<?
		EXIT;
	}
		
	//La idea es que genere un registro con las solicitudes de un estudiante
	function consultarSolicitudEstudiante($configuracion)
	{
		$valor[0]=$_REQUEST["estudiante"];
		$valor[1]=$this->usuario;
                $conexion=  $this->accesoOracle;
                if($this->nivel==4){
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaEstudianteCoordinador",$valor);
	  	$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
                }elseif($this->nivel==110 ||$this->nivel==114){
                    $valida=  $this->validacion->validarProyectoAsistente($valor[0], $valor[1],$conexion,$configuracion,  $this->accesoOracle,  $this->nivel);
                    if($valida=='ok'){
                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiante",$valor[0]);
                        $registro2=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");

                        $registro[0][0]=$valor[0];
			$registro[0][1]=$registro2[0][0];
                    }
                }
		
		if(is_array($registro)){
			$valor[0]=$registro[0][0];
			$valor[1]=$registro[0][1];
			$this->redireccionarInscripcion($configuracion, "solicitadoEstudiante",$valor);
		}else{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="El estudiante ".$valor[0]." no pertenece a su Coordinaci&oacute;n.<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);	
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
			case "solicitadoEstudiante":
				$variable="pagina=admin_recibo";
				$variable.="&hoja=1";
				$variable.="&opcion=solicitadoEstudiante";
				$variable.="&estudiante=".$valor[0];
				$variable.="&carrera=".$valor[1];
			break;
			case "mostarRegistroHistorico":
				$variable="pagina=admin_recibo";
				$variable.="&hoja=1";
				$variable.="&opcion=mostrarRegistros";
				$variable.="&estudiante=".$valor[0];
				$variable.="&carrera=".$valor[1];
			break;	
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);


		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();		
		
	}	

}

?>

