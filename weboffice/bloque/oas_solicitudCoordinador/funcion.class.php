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

class funciones_adminSolicitudCoordinador extends funcionGeneral
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
		$this->accion=$_REQUEST["accion"];
		//Conexion ORACLE
		$this->accesoOracle=$this->conectarDB($configuracion,"oracle");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		//Datos Generales
		$this->datosBasico["anno"]=$this->datosGenerales($configuracion,$this->accesoOracle, "anno") ;
		$this->datosBasico["periodo"]=$this->datosGenerales($configuracion,$this->accesoOracle, "per") ;
		$this->datosBasico["salarioMinimo"]=$this->datosGenerales($configuracion,$this->accesoOracle, "salarioMinimo") ;
	}
	
	
	function nuevoRegistro($configuracion,$acceso_db)
	{}
	
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
				
			case "solicitado":
				$this->reciboSolicitado($configuracion,$registro, $total);
		
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
		$variable="pagina=oas_recibosensolicitud";
		$variable.="&opcion=solicitado";
		$variable.="&accion=listaCompleta";
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();
		$variable=$cripto->codificar_url($variable,$configuracion);
		//echo "<script>location.replace('".$pagina.$variable."')</script>"; 
	
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
		//Rescatar los datos de la solicitud
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"datosSolicitud",$variable[1]);
		$registroSolicitud=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
		
		if(is_array($registroSolicitud))
		{
			if($registroSolicitud[0][9]==1||$registroSolicitud[0][9]==2||$registroSolicitud[0][9]==3){
				//0. Verificar Codigo
				//TODO 
				//Por seguridad debe volver a comprobarse la legitimidad del codigo de acuerdo a la carrera y el usuario
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiante",$variable[0]);
				$registroEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
				
				if(is_array($registroEstudiante))
				{
					
					//echo "<br>Encontro estudiante en v_acestmat y acest";
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
							echo "<br>Obtuvo la secuencia ".$registroSecuencia[0][0];
							$estaSecuencia=$registroSecuencia[0][0];
							
							//7.B. Actualizar ACESTMAT
							$parametro[0]=$registroEstudiante[0][0];
							$parametro[1]=$this->datosBasico["anno"];
							$parametro[2]=$this->datosBasico["periodo"];
							$parametro[3]=$contador+1;
							
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizaracestmat",$parametro);
							$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
							if($resultado){
								echo "<br>actualizo acestmat";
							}
							else{
								echo "<br>".$cadena_sql;
							}
							//7.C. Insertar Cuota en ACESTMAT						
							$parametro[1]=$registroEstudiante[0][3];
							$parametro[2]=$valorCuota;
							$parametro[3]=$valorCuotaExtra;
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
									//$registroCuotas[$contador][4] contiene la fecha extraordinaria
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
							
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarCuota",$parametro);
							$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
							if($resultado){
								echo "<br>inserto en acestmat";
							}
							else{
								echo "<br>".$cadena_sql;
							}					
	  					}
	  					else
	  					{
	  						return false;
	  					}
	 					
						
						//5. Calcular Conceptos
						$concepto= $this->conceptosPago($configuracion, $variable[1]);
						
						$j=0;
						
						//Datos para CONSIGA
						$sistematizacion=0;
						$carnet=0;
						$seguro=0;
						$valorOtrosConceptos=0;
				
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
							
							$parametro[0]=$this->datosBasico["anno"];
							$parametro[1]=$estaSecuencia;
							$parametro[2]="23"; //TODO Rescatar el codigo directamente de la base de datos
							$parametro[3]=$concepto[$j]["codigo"];
							
							
							if($concepto[$j]["codigo"]==1)
							{
								$parametro[4]=$valorCuota;
							}
							else
							{
								$parametro[4]=$concepto[$j]["valor"];
								if($j==1)
								{
									$valorOtrosConceptos+=$concepto[$j]["valor"];
								}
							
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
			elseif($registroSolicitud[0][9]==4||$registroSolicitud[0][9]==5){
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"solicitudReciboExtemporaneo",$variable[1]);
				$registroExtemporaneo=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
				
				
				//REscata Datos de recibo anterior en acestmat
				$reciboAnterior=$this->consultaReciboAnterior($configuracion,$registroExtemporaneo[0]);
				
				if(is_array($reciboAnterior)){
					$valorRecalculado=$this->calculoInteres($configuracion,$registroExtemporaneo[0],$reciboAnterior[0][0]);
					if(isset($valorRecalculado)){
						//insertar en acestmat
						$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"secuencia","");
	  					$registroSecuencia=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
	   					if(is_array($registroSecuencia))
						{
							
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"cuotasSolicitud",$variable[1]);
							$registroCuotas=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
							
							$parametro[0]=$registroSolicitud[0][2];
							$parametro[1]=$registroSolicitud[0][3];
							$parametro[2]=$valorRecalculado;
							$parametro[3]=$valorRecalculado;
							$parametro[4]=$this->datosBasico["anno"];
							$parametro[5]=$this->datosBasico["periodo"];
							$parametro[6]=$registroCuotas[0][1];
							
							//Fechas de Pago Ordinario
							$parametro[7]=date("Ymd",$registroCuotas[0][3]);
							
							//Fechas de Pago ExtraOrdinario	
							$parametro[8]=$parametro[7];
							$parametro[9]=$registroSecuencia[0][0];

							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarCuota",$parametro);
							$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
							
							//echo "<br>acestmat= ". $cadena_sql;
							
							$concepto= $this->conceptosPago($configuracion,$registroSolicitud[0][0]);

							for($i=0;$i<count($concepto);$i++)
							{
								$parametro[0]=$this->datosBasico["anno"];
								$parametro[1]=$registroSecuencia[0][0];
								$parametro[2]="23"; //TODO Rescatar el codigo directamente de la base de datos
								$parametro[3]=$concepto[$i]["codigo"];
								
								
								if($concepto[$i]["codigo"]==1)
								{
									$parametro[4]=$valorRecalculado;
								}
								else
								{
									$parametro[4]=$concepto[$i]["valor"];
									/*if($j==1)
									{
										$valorOtrosConceptos+=$concepto[$j]["valor"];
									}*/
								
								}
								unset($resultado);
								unset($cadena_sql);
								$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConcepto",$parametro);
								$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
								//echo "<br>ref $i = ".$cadena_sql;
							
							}							
							/*echo "<pre>";
							var_dump($concepto);
							echo "</pre>";
							echo "<br>tam concepto(0)=".count($concepto[0]);
							echo "<br>tam concepto=".count($concepto);*/

							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConcepto",$parametro);
	 						$resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");

							$parametro[0]=$$registroSecuencia[0][0];
							$parametro[1]=$registroSolicitud[0][0];

							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"actualizarSolicitudExtemporanea",$registroSolicitud[0][0]);
	 						$resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");
						
							
						}
					}

				}
				else{
					echo "<br>No se encontro un registro previo";
				}
			}
			else{
				echo "No se pudo rescatar el tipo de plantilla";			
			}
		}
		else
		{
			echo "Los recibos no se generaron";
		
		}
	}


	function consultaReciboAnterior($configuracion,$valor)
	{
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosReciboExtemporaneo",$valor);
 		$registroDeuda=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		//echo "<br>".$cadena_sql;
		return	$registroDeuda;
									
	}

	function calculoInteres($configuracion,$registro,$valor)
	{	
		$tasaInteresAnual=30.71;
		$tasaInteresMensual=$tasaInteresAnual/12/100;
		
		$nuevoValorA=(int)$valor;
		$nuevoValorB=1 + $tasaInteresMensual;
		$nuevoValorC=pow($nuevoValorB,(int)$registro[4]);			
			
		$nuevoValor=round($nuevoValorA*$nuevoValorC);

 		//$registroDeuda=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		/*echo "<br> value=( ".(int)$valor." ) ";
		echo "<br> newValue=( $nuevoValor ) ";
		echo "<br> numMeses=( ".(int)$registro[4]." ) ";
		echo "<br> tasaInteres=( ".$tasaInteresMensual." ) ";*/

		return	$nuevoValor;
									
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
								$formulario="oas_solicitudCoordinador";
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


		/*	echo "<br>REQUEST[carrera]: ".$variable[3]."ehh	 //funcion.class.php Line 571";
			echo "<br>A&ntilde;o: ".$variable[1]." //funcion.class.php Line 572";
			echo "<br>Periodo: ".$variable[2]."//funcion.class.php Line 573";	*/	


	
		if(isset($_REQUEST["opcion"]))
		{
			switch($_REQUEST["opcion"])
			{
				case "solicitado":
					//Se seleccionan los recibos solicitados de acuerdo a los criterios de busqueda
					switch($this->accion)
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
							
						case "listaExtemporaneos":
							
							//Paginacion
							//Obtener el total de registros
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"totalExtemporaneos",$variable);						
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
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"solicitudExtemporaneo",$variable);
						break;								
					}
					break;
				
				default:
					break;
			}
			
			$registro=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
			if(is_array($registro))
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
	
	function reciboSolicitado($configuracion,$registro, $total)
	{
		//echo "<br>Entro en recibo solicitado /funcion.class.php line 647";
			
			
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		$menu=new navegacion();
		$variableNavegacion["pagina"]="oas_recibosensolicitud";
		$variableNavegacion["opcion"]="solicitado";	
		$variableNavegacion["accion"]="listaCompleta";
		$variableNavegacion["carrera"]=$registro[0][3];
		
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
		setlocale(LC_MONETARY, 'en_US');
		
		
		
		//echo "<form>";
		$formulario="oas_solicitudCoordinador";
		$celdas=0;
		echo "<form method='post' action='index.php' name=".$formulario.">";
								
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
						$j++;
					}
					
					//Calcular descuentos y exenciones
					$porcentaje=$this->porcentajeExencion($configuracion,$registro[$i][0]);
					
					//Valor Neto a Pagar de matricula 
					$valorMatriculaNeto=round($valorMatriculaBruto*(1-($porcentaje["exencion"]+$porcentaje["certificado"])/100));
					
					
					
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
									echo $registro[$i][4];
								}
								echo "</span> cuota(s)" ?>
							</td>
							<td align="center" width="10%" class="cuadro_plano">
								<a href="<?
								$variable="pagina=oas_borrar_registro";
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
						<? echo money_format('$ %!.0i', $valorMatriculaNeto).$losConceptos;
							 
							 
							 ?>
						
						</td>
						<td class='cuadro_plano centrar'>
						<?
						//Numero de Solicitud y Codigo de Estudiante 
						echo "<input type='hidden' name='codGenerar_".$i."' id='codGenerar_".$i."' value='".$registro[$i][2]."'>";
						echo "<input type='checkbox' name='selGenerar_".$i."' id='selGenerar_".$i."' value='".$registro[$i][0]."'>";  ?>
						</td>
						</tr>
						</table>
						<br><?
					
				}
				
			}
			
			//$mi_variable="pagina=oas_recibosensolicitud";
			$mi_variable="pagina=oas_verificar_recibo";
			$mi_variable.="&confirmar=1";
			$mi_variable=$this->cripto->codificar_url($mi_variable,$configuracion);
			echo "<table  class='tablaMarco'>";
			echo "<tr><td colspan='6' class='centrar cuadro_plano'>";
			
			
			echo "<input type='hidden' name='formulario' value='".$mi_variable."'>\n";
			echo "<input type='submit' name='aceptar' value='::.Generar.::' ></td></tr>";	


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
	
	
	function registroPagoBruto($unaSolicitud, $unEstudiante, $datosBasicos)
	{
		//Total sin exenciones
/*		
		foreach($unaSolicitud as $clave=>$valor)
		{
			echo $clave."=>".$valor."<br>";
		
		}
		
		foreach($unEstudiante as $clave=>$valor)
		{
			echo $clave."=>".$valor."<br>";
		
		}
		
*/		
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
			
				//echo $datosBasicos["salarioMinimo"];
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
// 		foreach($porcentaje as $clave=>$valor)
// 		{
// 			echo $clave."--->".$valor;
// 		
// 		}
		return $porcentaje;
	}
	
	function conceptosPago($configuracion, $variable)
	{
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"conceptoSolicitud",$variable);						
		$registroConcepto=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");
		

		//echo "<br>sQL=".$cadena_sql;
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
					//Si el concepto es 2 (seguro) el valor se toma directamente
					if($registroConcepto[$j][1]==2)
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
		
		?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
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
					$parametro="pagina=oas_recibosensolicitud";
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

}

?>

