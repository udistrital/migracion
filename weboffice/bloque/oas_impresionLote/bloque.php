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

Última revisión 22 de diciembre de 2015

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.3
* @author      	
* @link		N/D
* @Actualización      	22/12/2015
* @author 		Milton Parra
* @description  Formulario para el registro de un archivo de bloques
* @usage        
*******************************************************************************/ 
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
if (is_resource($enlace))
{
	//Buscar las solicitudes que pertenecen a la carrera:
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosSolicitud.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosGenerales.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/imprimirSolicitud.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	
	
	
	$cripto=new encriptar();
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
	
	$conexion=new dbConexion($configuracion);
	$accesoOracle=$conexion->recursodb($configuracion,"oracle");
	$enlace=$accesoOracle->conectar_db();
	
	$datoBasico=new datosGenerales();
	$datosBasico["salarioMinimo"]=$datoBasico->rescatarDatoGeneral($configuracion, "salarioMinimo", "", $accesoOracle);
				
	
	$datoSolicitud=new datosSolicitud();
	
	$imprimirSolicitud=new imprimirSolicitud();
	
	//Buscar las solicitudes de la carrera
	$laSolicitud=$datoSolicitud->rescatarDatoSolicitud($configuracion, "solicitud", $_REQUEST["registro"], $acceso_db);
	
	$cadenaSecuencia="";
	$cadenaPlano="";
	$cadenaPlanoTitulos="";
	if($laSolicitud)
	{
		if($_REQUEST["accion"]==1)
		{
			$i=0;
			echo "<table class='Cuadricula Centrar'>";
		
			while(isset($laSolicitud[$i][0]))
			{
				//Determinar el nivel de la carrera
				
				$datosBasico["nivelCarrera"]=$datoBasico->rescatarDatoGeneral($configuracion, "nivelCarrera", $laSolicitud[0][3],  $accesoOracle);
			
							
				//Verificar el Codigo del estudiante
				$elEstudiante=$datoSolicitud->rescatarDatoSolicitud($configuracion, "datosEstudiante", $laSolicitud[$i][2], $accesoOracle);
				if($elEstudiante)
				{
					//Rescatar el valor bruto de la matricula
					$valorMatriculaBruto=$datoSolicitud->registroPagoBruto($laSolicitud[$i], $elEstudiante[0], $datosBasico);
					
					//Rescatar exenciones de la solicitud
					$laExencion=$datoSolicitud->rescatarDatoSolicitud($configuracion, "exencionSolicitud", $laSolicitud[$i][0], $acceso_db);
					
					//Aplicar exenciones
					$valorMatriculaNeto=$datoSolicitud->matriculaPagoNeto($valorMatriculaBruto,$laExencion);
					
///////////////// //////echo "<br>valorMatriculaNeto= ".$valorMatriculaNeto;						
					
					//Obtener las Observaciones
					if($laExencion)
					{
						$laObservacion=$datoSolicitud->observacionSolicitud($laExencion);
					}
					else
					{
						$laObservacion=" ";
					}
					
					//Obtener las cuotas
					$laCuota=$datoSolicitud->rescatarDatoSolicitud($configuracion, "cuotasSolicitud", $laSolicitud[$i][0], $acceso_db);
					
					//Rescatar conceptos de la solicitud
					$elConcepto=$datoSolicitud->rescatarDatoSolicitud($configuracion, "conceptoSolicitud", $laSolicitud[$i][0], $acceso_db);
					
					
					//Obtener listado y valor de los Conceptos
					if(is_array($elConcepto))
					{
						$laListaConcepto=$datoSolicitud->observacionSolicitud($elConcepto);
					}
					else
					{
						$laListaConcepto="";
					}
					
					//echo $laObservacion."<br>";
					
					//Armar archivo Plano
					
					
					
					
					$j=0;
					
					$totalCuotas=count($laCuota);
					
					while(isset($laCuota[$j]))
					{
						$archivoPlano["referencia1"]=$elEstudiante[0][0];
						$archivoPlano["nombre"]=$elEstudiante[0][2];
						$archivoPlano["identificacion"]=$elEstudiante[0][1];
						
						if($totalCuotas>1)
						{
							$archivoPlano["observacion"]="CUOTA ".($j+1)." DE ".$totalCuotas." ".$laObservacion;
						}
						else
						{
							$archivoPlano["observacion"]=$laObservacion;
						}
						
						$archivoPlano["idCarrera"]=$elEstudiante[0][3];
						$archivoPlano["nombreCarrera"]=$elEstudiante[0][7];
						//Para las cuotas diferentes a la primera no se cobran conceptos
						if($j>0)
						{
							$archivoPlano["sistematizacion"]=0;
							$archivoPlano["carnet"]=0;
							$archivoPlano["seguro"]=0;
						}
						else
						{
							//TO DO rescatar los valores desde la DB
							if(is_array($elConcepto))
							{
								$archivoPlano["seguro"]=$datoSolicitud->valorConcepto($elConcepto,1);
								$archivoPlano["carnet"]=$datoSolicitud->valorConcepto($elConcepto,42);
								$archivoPlano["sistematizacion"]=$datoSolicitud->valorConcepto($elConcepto,43);
							}
							else
							{
								$archivoPlano["sistematizacion"]=0;
								$archivoPlano["carnet"]=0;
								$archivoPlano["seguro"]=0;
							}
						
						}
						$archivoPlano["refPagoMatricula"]="1".$elEstudiante[0][3];
						
					
//////////////////////////echo "<br>laSolicitud[i][0]= ".$laSolicitud[$i][0];	

						if($laSolicitud[$i][0]==1)
						{
							//El valor neto de la matricula por el porcentaje correspondiente a la cuota
							$archivoPlano["matricula"]=round($valorMatriculaNeto*$laCuota[$j][2]/100);	
						}
						else
						{
						
///////////////////////////////echo "<br>j=".$j;						
							switch($j)
							{
							
								case 0:
								$archivoPlano["matricula"]=round($valorMatriculaNeto*$laCuota[$j][2]/100);	
								break;
								
								case 1:
								$archivoPlano["matricula"]=round(($valorMatriculaNeto*$laCuota[$j][2]/100)*1.016368);
								break;
								
								case 2:
								$archivoPlano["matricula"]=round((($valorMatriculaNeto*$laCuota[$j][2]/100)*1.016368)*1.016368);
								break;
								
								
							
							}
						}
						
						
						
						//El valor de la matricula + 20% de pago extraordinario
						$archivoPlano["matriculaExtra"]=round($archivoPlano["matricula"]*1.2);
						
						//Pago Neto= MatriculaNeta de la cuota + Otros Conceptos
						$archivoPlano["ordinario"]=$archivoPlano["matricula"]+$archivoPlano["sistematizacion"]+ $archivoPlano["carnet"]+$archivoPlano["seguro"];
						
						
//////////////////////////echo "<br>Matricula= ".$archivoPlano["matricula"]."-".$archivoPlano["sistematizacion"]."-".$archivoPlano["carnet"]."-".$archivoPlano["seguro"];
						
						
						$archivoPlano["extraordinario"]=$archivoPlano["matriculaExtra"]+$archivoPlano["sistematizacion"]+ $archivoPlano["carnet"]+$archivoPlano["seguro"];
						
						$archivoPlano["anno"]=$laSolicitud[$i][7];
						$archivoPlano["periodo"]=$laSolicitud[$i][8];
						
						$archivoPlano["fechaOrdinaria"]=date("d/m/y",$laCuota[$j][3]);
						$archivoPlano["fechaExtraordinaria"]=date("d/m/y",$laCuota[$j][4]);
						
						//To Do rescatar estos valores de la DB
						$archivoPlano["banco"]="OCCIDENTE";
						$archivoPlano["cuenta"]="230-81461-8";
						
						$archivoPlano["cuota"]=$j+1;
					
						//Insertar el registro de Impresion
						
						$estaSecuencia=$imprimirSolicitud->rescatarDatoImpresionSolicitud($configuracion, "secuencia", "", $accesoOracle);
						
						
						if(is_array($estaSecuencia))
						{
							$archivoPlano["secuencia"]=$estaSecuencia[0][0];
							
							$imprimirSolicitud->impresionSolicitud($configuracion, "actualizaracestmat", $archivoPlano, $accesoOracle);
							$insertarCuota=$imprimirSolicitud->impresionSolicitud($configuracion, "insertarCuota", $archivoPlano, $accesoOracle);
							$imprimirSolicitud->impresionSolicitud($configuracion, "actualizarSolicitud", $laSolicitud[$i][0], $acceso_db);
							
						
						}
						else
						{
							$cadenaSecuencia.="<tr><td>".$imprimirSolicitud->cadenaSQL_imprimirSolicitud($configuracion, "clausulaInsertarSecuencia", $archivoPlano).";</td></tr>";						
							
						}
						
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
						$cadenaPlano.="<tr>";
						if(isset($archivoPlano["secuencia"]))
						{
							$cadenaPlano.="<td>".$archivoPlano["secuencia"]."</td>";
						}
						$cadenaPlano.="<td>".$archivoPlano["referencia1"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["nombre"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["identificacion"]."</td>";
						/*$cadenaPlano.="<td>".$archivoPlano["observacion"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["idCarrera"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["nombreCarrera"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["carnet"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["sistematizacion"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["seguro"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["refPagoMatricula"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["matricula"]."</td>";*/
						$cadenaPlano.="<td>".$archivoPlano["ordinario"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["extraordinario"]."</td>";
						//$cadenaPlano.="<td>".$archivoPlano["anno"]."</td>";
						//$cadenaPlano.="<td>".$archivoPlano["periodo"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["fechaOrdinaria"]."</td>";
						$cadenaPlano.="<td>".$archivoPlano["fechaExtraordinaria"]."</td>";
						//$cadenaPlano.="<td>".$archivoPlano["banco"]."</td>";
						//$cadenaPlano.="<td>".$archivoPlano["cuenta"]."</td>";
						//$cadenaPlano.="<td>".$archivoPlano["cuota"]."</td>";
						$cadenaPlano.="</tr>";
						$j++;
						
					}
				
				}
				else
				{
					echo "No se encuentra el estudiante";
				
				}
				$i++;
			}
			$cadenaPlanoTitulos.="<td>Secuencia</td>";
			$cadenaPlanoTitulos.="<td>C&oacute;digo</td>";
			$cadenaPlanoTitulos.="<td>Nombre</td>";
			$cadenaPlanoTitulos.="<td>Documento</td>";
			$cadenaPlanoTitulos.="<td>Valor Pago Ordinario</td>";
			$cadenaPlanoTitulos.="<td>Valor Pago Extraordinario</td>";
			$cadenaPlanoTitulos.="<td>Fecha L&iacute;mite Ord.</td>";
			$cadenaPlanoTitulos.="<td>Fecha L&iacute;mite Ext.</td>";
			
			echo "<table class='tablaMarco'>";
			echo "<tr>";
			echo "<td>";
			if($cadenaPlano !="")
			{
				
				echo "<table class='Cuadricula'>";
				echo "<tr class=bloquecentralencabezado>";
				echo "<td>::.. Registros Archivo Plano</td>";
				echo "</tr>";
				echo "</table>";
				echo "<br>";
				echo "<table class='Cuadricula'>";
				echo "<tr class=bloquecentralencabezado>";
				echo $cadenaPlanoTitulos;
				echo "</tr>";
				echo $cadenaPlano."</table>";
			}
			
			if($cadenaSecuencia !="")
			{
				echo "<hr class='hr_subtitulo'>";
				echo "<table class='Cuadricula'>";
				echo "<tr class=bloquecentralencabezado>";
				echo "<td>::.. Clausulas para Ingresar Impresi&oacute;n</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>Algunos recibos no tienen una secuencia valida (ID recibo). A continuaci&oacute;n se tienen las clausulas SQL correspondientes.</td>";
				echo "</tr>";
				echo "</table>";
				echo "<br>";
				echo "<table class='Cuadricula'>".$cadenaSecuencia."</table>";
			}
			echo "</td>";
			echo "</tr>";
			echo "</table>";
		}
		else
		{
		
				$cadena_sql=cadenaSQL_impresionLote($configuracion, "actualizarImpresion", $laSolicitud[$i][3]);
				//echo $cadena_sql;
		
		}
	}
}
?>
