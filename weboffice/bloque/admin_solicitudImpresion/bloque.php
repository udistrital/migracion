<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    paulo_cesar@etb.net.co                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
index.php 

Oficina Asesora de Sistemas
Copyright (C) 2008

Última revisión 15 de julio de 2008

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	
* @link		N/D
* @description  Formulario para el registro de un archivo de bloques
* @usage        
*******************************************************************************/ 
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

setlocale(LC_MONETARY, 'en_US');

$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
if (is_resource($enlace))
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosGenerales.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
	
	$conexion=new dbConexion($configuracion);
	$accesoOracle=$conexion->recursodb($configuracion,"oracle");
	$enlace=$accesoOracle->conectar_db();
	
	$datoBasico=new datosGenerales();
	$datosBasico["salarioMinimo"]=$datoBasico->rescatarDatoGeneral($configuracion, "salarioMinimo", "", $accesoOracle);
	
	
	//Rescatar todas las solicitudes
	$cadena_sql=cadenaSQL_impresion($configuracion, "solicitud", "");		
	$registroSolicitud=acceso_db_impresion($cadena_sql,$acceso_db,"busqueda");
	if(is_array($registroSolicitud))
	{
		$i=0;
		?>
		<table class='tablaMarco'>
			<tr class='bloquecentralcuerpo'>
				<td>		
		<?
		while(isset($registroSolicitud[$i][0]))
		{
			unset($registroExencion);
			unset($registroConcepto);
			$porcentajeCertificado=0;
			$porcentaje=0;
			//Rescatar el nivel de la carrera
			$datosBasico["nivelCarrera"]=$datoBasico->rescatarDatoGeneral($configuracion, "nivelCarrera", $registroSolicitud[0][3], $accesoOracle);
			
				
			//Rescatar el valor de la matricula
			$cadena_sql=cadenaSQL_impresion($configuracion, "datosEstudiante", $registroSolicitud[$i][2]);		
			$registroEstudiante=acceso_db_impresion($cadena_sql,$accesoOracle,"busqueda");
			if(is_array($registroEstudiante))
			{
				
				$valorMatriculaBruto=registroPagoBruto($registroSolicitud[$i], $registroEstudiante[0], $datosBasico);
				
				//Rescatar los conceptos
				$seguro=false;
				$carnet=false;
				$sistematizacion=false;	
				
				$cadena_sql=cadenaSQL_impresion($configuracion, "conceptoSolicitud", $registroSolicitud[$i][0]);
				//echo $cadena_sql."<br>";		
				$registroConcepto=acceso_db_impresion($cadena_sql,$acceso_db,"busqueda");
				//Recorrer una matriz
	
				
				if(is_array($registroConcepto))
				{
					$j=0;
					
					while (isset($registroConcepto[$j][1])) 
					{
						switch($registroConcepto[$j][1])
						{
							case 1:
								
								$seguro=true;
								break;
							
							case 42:
								$carnet=true;
								break;
							case 43:
								$sistematizacion=true;
								break;
						}
						
						$j++;
					}
				}
				
				//Rescatar Exenciones
				$cadena_sql=cadenaSQL_impresion($configuracion, "exencionSolicitud", $registroSolicitud[$i][0]);		
				$registroExencion=acceso_db_impresion($cadena_sql,$acceso_db,"busqueda");
				$observacion="Efectivo ";
				if(is_array($registroExencion))
				{
					//Se deben seguir ciertas reglas para las exenciones
					//1.Maximo deben existir dos exenciones por estudiante
					//2. Una de esas exenciones debe ser el 10% (1)
					//3. Exenciones de 100% no aceptan otro tipo de exencion
					//4. Un estudiante de postgrado puede ser egresado(11) o monitor(10) (excluyente) 
					
					$j=0;
					while (isset($registroExencion[$j][1]) && $j<2) 
					{
						$observacion.=$registroExencion[$j][4]." ";
						
						if($registroExencion[$j][1]==1)
						{
							$porcentajeCertificado=$registroExencion[$j][3];
						}
						else
						{
							$porcentaje+=$registroExencion[$j][3];
						}
						$j++;
					}
				}
				
				$valorPagar=$valorMatriculaBruto-(($valorMatriculaBruto*$porcentajeCertificado)/100) - (($valorMatriculaBruto*$porcentaje)/100);
				
				?><table class='tablaMarco'>
					<tr class='bloquecentralcuerpo'>
						<td class='cuadro_plano cuadro_color' width='15%'>
						<span class='texto_negrita'><? echo $registroSolicitud[$i][2] ?></span>
					</td>
					<td class='cuadro_plano cuadro_color' width='45%'>
					<? echo $registroEstudiante[0][2] ?>
					</td>
					<td class='cuadro_plano' width='25%'>
					<? 
					echo "Pagar en <span class='texto_negrita'>";
					//Si es pregrados
					if($registroSolicitud[0][9]==1)
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
						echo $registroSolicitud[$i][4];
					}
					echo "</span> cuota(s)" ?>
					</td>
					<td align="center" width="10%" class="cuadro_plano">
					<a href="<?
						$variable="pagina=borrar_registro";
						$variable.="&opcion=solicitud";
						$variable.="&registro=".$registroSolicitud[$i][0];
						$redireccion="";		
						reset ($_REQUEST);
						while (list ($clave, $val) = each ($_REQUEST)) 
						{
							$redireccion.="&".$clave."=".$val;
							
						}
						
						$variable.="&redireccion=".$cripto->codificar_url($redireccion,$configuracion);
						
						$variable=$cripto->codificar_url($variable,$configuracion);
						
						echo $indice.$variable;	
					?>"><img width="24" heigth="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/boton_borrar.png"?>" alt="Borrar el registro" title="Borrar el registro" border="0" /></a>	
					</td>
					</tr>
					<tr class='bloquecentralcuerpo'>
					<td class='cuadro_plano' width='30%' colspan='2'>
					<? echo $observacion; ?>
					</td>
					<td class='cuadro_plano centrar fondoImportante'>
					<? echo money_format('$ %!.0i', $valorPagar);
						if(isset($seguro) && ($seguro==true))
						{
							echo " + Seguro ";
							
						}
						if(isset($sistematizacion) && ($sistematizacion==true))
						{
							echo " + Sistematizaci&oacute;n ";			
						}
						if(isset($carnet) && ($carnet==true))
						{
							echo " + Carnet ";
						
						} ?>
					
					</td>
					<td class='cuadro_plano centrar'>
					<? echo "";  ?>
					</td>
					</tr>
					</table>
					<br><?
				
			}
			$i++;
			
		}
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
	switch($unaSolicitud[9])
	{
		case 1:
		$valorMatriculaBruto=$unEstudiante[6];
		break;
		
		case 2://Postgrados por creditos
			//BORRAR
			
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


function matriculaPagoNeto()
{
	//Bruto - exenciones
}

function pagoNeto()
{
	//matriculaPagoNeta + Otros conceptos


/*

	elseif($codigoPlantilla==2)
	{SELECT rpa_numero_cuotas
	  INTO nro_cuotas
	  FROM ACRANGOSPAGO
	 WHERE valor_matricula BETWEEN rpa_limite_inferior AND rpa_limite_superior;
	
	
	}
	elseif($codigoPlantilla==3)
	{
	
	
	}		
				
*/
}



function cadenaSQL_impresion($configuracion, $tipo, $variable)
{
	$cadena_sql="";
	switch($tipo)
	{
		case "solicitud":
			
			$cadena_sql="SELECT ";
			$cadena_sql.="`id_solicitud_recibo`, ";
			$cadena_sql.="`id_usuario`, ";
			$cadena_sql.="`codigo_est`, ";
			$cadena_sql.="`id_carrera`, ";
			$cadena_sql.="`cuota`, ";
			$cadena_sql.="`estado`, ";
			$cadena_sql.="`fecha`, ";
			$cadena_sql.="`anno`, ";
			$cadena_sql.="`periodo`, ";
			$cadena_sql.="`tipoPlantilla`, ";
			$cadena_sql.="`unidad` ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="estado=0 ";
			$cadena_sql.="AND ";
			$cadena_sql.="id_carrera=".$_REQUEST["registro"];  
			break;
			
		case "conceptoSolicitud":
			$cadena_sql="SELECT ";
			$cadena_sql.="`id_solicitud`, ";
			$cadena_sql.="`id_concepto` ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudConcepto ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id_solicitud=".$variable;
			 
			break;
			
		case "exencionSolicitud":
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudExencion.id_solicitud, ";
			$cadena_sql.=$configuracion["prefijo"]."exencion.id_exencion, ";
			$cadena_sql.=$configuracion["prefijo"]."exencion.`nombre`, ";
			$cadena_sql.=$configuracion["prefijo"]."exencion.`porcentaje`, ";
			$cadena_sql.=$configuracion["prefijo"]."exencion.`etiqueta`, ";
			$cadena_sql.=$configuracion["prefijo"]."exencion.`tipo`, ";
			$cadena_sql.=$configuracion["prefijo"]."exencion.`soporte` ";	
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudExencion, ";
			$cadena_sql.=$configuracion["prefijo"]."exencion ";			
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudExencion.id_solicitud=".$variable." ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."exencion.id_exencion=".$configuracion["prefijo"]."solicitudExencion.id_exencion";
			//echo $cadena_sql;
			break;
			
	
		
		case "datosEstudiante":
			//En ORACLE
			$cadena_sql="SELECT ";
			$cadena_sql.="est_cod, ";
			$cadena_sql.="est_nro_iden, ";
			$cadena_sql.="est_nombre, ";
			$cadena_sql.="est_cra_cod, ";
			$cadena_sql.="est_diferido, ";
			$cadena_sql.="est_estado_est, ";
			$cadena_sql.="emb_valor_matricula vr_mat, ";
			$cadena_sql.="cra_abrev, ";
			$cadena_sql.="est_exento, ";
			$cadena_sql.="est_motivo_exento, ";
			$cadena_sql.="cra_dep_cod ";						
			$cadena_sql.="FROM ";
			$cadena_sql.="acest, ";
			$cadena_sql.="V_ACESTMATBRUTO, ";
			$cadena_sql.="ACCRA ";
			
			$cadena_sql.="WHERE ";
			$cadena_sql.="est_cod =".$variable." ";
			$cadena_sql.="AND ";
			$cadena_sql.="emb_est_cod = est_cod ";
			$cadena_sql.="AND ";
			$cadena_sql.="cra_cod = est_cra_cod";
			
			break;
		
		case "certificadoElectoral":
			//En ORACLE
			$cadena_sql="SELECT ";
			$cadena_sql.="cer_est_cod, ";
			$cadena_sql.="cer_fecha ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACCERELECTORAL ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="cer_est_cod =".$valor." ";
			$cadena_sql.="AND ";
			$cadena_sql.="cer_estado= 'A' ";
			break;
		
		case "diferidoPregrado":
			//En Oracle
			 $cadena_sql="SELECT ";
			 $cadena_sql.="EST_DIFERIDO ";
			 $cadena_sql.="FROM ";
			 $cadena_sql.="ACEST ";
			 $cadena_sql.="WHERE ";
			 $cadena_sql.="EST_COD =".$valor;
			 break;
		
		case "exencionActual":
			$cadena_sql="SELECT ";
			$cadena_sql.="`id_exencion`, ";
			$cadena_sql.="`nombre`, ";
			$cadena_sql.="`porcentaje`, ";
			$cadena_sql.="`etiqueta`, ";
			$cadena_sql.="`tipo`, ";
			$cadena_sql.="`soporte` ";		
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."exencion ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="tipo=".$valor." ";
			$cadena_sql.="OR ";
			$cadena_sql.="tipo=3 ";
			$cadena_sql.="ORDER BY ";
			$cadena_sql.="id_exencion ";
			break;
			
		case "exencionAnterior":
			$cadena_sql="SELECT ";
			$cadena_sql.="est_motivo_exento ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACEST ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="est_exento='S' ";
			$cadena_sql.="AND ";
			$cadena_sql.="est_cod=".$valor;
			break;
			 
		case "exencion":
			$cadena_sql="SELECT ";
			$cadena_sql.="id_exencion ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."exencion ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="EXE_COD=".$valor;
			break;
			
		case "numeroCuotas":
			$cadena_sql="SELECT ";
			$cadena_sql.="rpa_numero_cuotas ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACRANGOSPAGO ";
			$cadena_sql.="WHERE ";
			$cadena_sql.=$valor." BETWEEN rpa_limite_inferior AND rpa_limite_superior";
			break;
		
	
	}
	
	return $cadena_sql;



}

function acceso_db_impresion($cadena_sql,$acceso_db,$tipo)
{
	if($tipo=="busqueda")
	{
		$acceso_db->registro_db($cadena_sql,0);
		$registro=$acceso_db->obtener_registro_db();
		return $registro;
	}
	else
	{
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
		return $resultado;
	}
}

		
?>
