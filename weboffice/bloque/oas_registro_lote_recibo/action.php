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


$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
if (is_resource($enlace))
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
	
	$sesion=new sesiones($configuracion);
	$sesion->especificar_enlace($enlace);
	$registro=$sesion->rescatar_valor_sesion($configuracion,"id_usuario");
	
	if(is_array($registro))
	{
		$usuario=$registro[0][0];
	}
	
	$registro=$sesion->rescatar_valor_sesion($configuracion,"identificacion");
	
	if(is_array($registro))
	{
		$identificacion=$registro[0][0];
	}
	
	
	
	$resultado=cargarArchivoLote($configuracion, $enlace, $acceso_db);
	
	if(is_array($resultado))
	{
		$valor[1]=$resultado["mi_archivo"][0];
		$valor[0]=$resultado["nombre_archivo"][0];
		$datos=leerArchivo($configuracion, $resultado["mi_archivo"][0]);
		
		if($datos!=false)
		{
			$columnas=$datos->sheets[0]['numCols']; 
		
			//if($columnas==4||$columnas==20||$columnas==14)
			if($columnas==20||$columnas==14)
			{
				
				$resultado=verificarLote($valor, $datos, $datos->sheets[0]['cells'][1][1], $configuracion, $identificacion,$acceso_db);
				if($resultado==false)
				{
					mensajeErrorCarga($configuracion, "inconsistencia", $valor);
				}
				
			}
			else
			{
				
				
				mensajeErrorCarga($configuracion, "inconsistencia", $valor);
			
			}
		}
		else
		{
			mensajeErrorCarga($configuracion, "sinLeer", $valor);
		}
		
		
	}
	else
	{
		mensajeErrorCarga($configuracion, "sinArchivo");
	}
	
}
	
//print_r($data);
//print_r($data->formatRecords);

function isUTF8($string)
{
    return (utf8_encode(utf8_decode($string)) == $string);
}

function verificarLote($valor, $data, $tipo="", $configuracion, $usuario, $acceso_db)
{
	
	
	switch($tipo)
	{
		case "PLANTILLA GENERAL PREGRADOS":
			$filaInicio=4;			
			$codigoPlantilla=1;
			break;
		
		
		case "PLANTILLA GENERAL POSTGRADOS CREDITOS":
			$filaInicio=4;			
			$codigoPlantilla=2;
			break;
			
		case "PLANTILLA GENERAL POSTGRADOS SALARIOS MINIMOS LEGALES":
			$filaInicio=4;			
			$codigoPlantilla=3;
			break;
		
		case "PLANTILLA SIMPLE":
			$filaInicio=3;
			$codigoPlantilla=4;			
			break;
		default:
			return false;
			break;
	}
	
	$filas=$data->sheets[0]['numRows'];
	$columnas=$data->sheets[0]['numCols'];
	
	$registroProcesar=0; 
	$indiceNoProcesado=0;
	$indiceProcesado=0;
	
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
	$conexion=new dbConexion($configuracion);
	$accesoOracle=$conexion->recursodb($configuracion,"oracle");
	$enlace=$accesoOracle->conectar_db();
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosGenerales.class.php");
	$datoBasico=new datosGenerales();
	
	$solicitud["anno"]=$datoBasico->rescatarDatoGeneral($configuracion, "anno", "", $accesoOracle);
	$solicitud["periodo"]=$datoBasico->rescatarDatoGeneral($configuracion, "per", "", $accesoOracle);
	
	$carrerasCoordinador=carrerasCoordinador($configuracion, $usuario, $accesoOracle);
	if(is_array($carrerasCoordinador))
	{
		//Recorrer los valores obtenidos de la PLantilla
		for ($i = $filaInicio; $i <= $filas; $i++) 
		{
			unset($exencion);
			
			if(isset($data->sheets[0]['cells'][$i][1]))
			{
				$solicitud["estudiante"]=$data->sheets[0]['cells'][$i][1];
				$pagador=verificarCodigo($configuracion, $solicitud["estudiante"],$carrerasCoordinador,$accesoOracle);
				if(is_array($pagador))
				{
					if($codigoPlantilla==2||$codigoPlantilla==3)
					{
						$solicitud["unidad"]=$data->sheets[0]['cells'][$i][2];;
					
					}
					$solicitud["plantilla"]=$codigoPlantilla;				
					$solicitud["usuario"]=$usuario;
					$solicitud["carrera"]=$pagador[0][3];
					$solicitud["diferido"]=$pagador[0][4];
					$resultado=procesarRegistro($configuracion, $data->sheets[0]['cells'][$i], $solicitud, $acceso_db,  $accesoOracle);
					if($resultado==false)
					{
						$codigoNoProcesado[$indiceNoProcesado++]=$data->sheets[0]['cells'][$i][1];
					
					}
					else
					{
						
						$registroProcesar++;
						$codigoProcesado[$indiceProcesado++]=$data->sheets[0]['cells'][$i][1];
					}
				}
				else
				{
					$codigoNoProcesado[$indiceNoProcesado++]=$data->sheets[0]['cells'][$i][1];
				}
			}	
			
		}
		echo "<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/basico/estilo.php' />\n";
		if($indiceNoProcesado>0)
		{
			mensajeErrorCarga($configuracion, "parcial", $valor);			
			echo "<table class='tablaMarco bloquecentralcuerpo'>";
			echo "<tr><td colspan='5'>Los siguientes C&oacute;digos no se han procesado. Por favor contacte a la Oficina Asesora de Sistemas o Solicitelos mediante oficio.</td></tr>";
			echo "<tr><td colspan='5'>Las razones principales de este rechazo pueden ser: ";
			echo "<ul><li>El estado actual del estudiante no permiten la generaci&oacute;n del recibo.</li>";
			echo "<li>El estudiante no pertenece al (los) proyecto(s) curricular(es) del Coordinador.</li>";
			echo "<li>Los datos de fecha no se ingresaron correctamente. (En el caso de postgrados)</li>";
			echo "<li>El sistema no puede determinar la carrera al cual pertenece el estudiante</li></ul></td></tr>";
			echo "<tr class='bloquecentralencabezado'><td colspan='5'>C&oacute;digos no procesados (".$indiceNoProcesado.")</td></tr>";
			for ($i=0;$i<$indiceNoProcesado;$i++) 
			{
			
				if(is_int($i/5))
				{
					echo "<tr>";
				}
				echo "<td>";
				echo $codigoNoProcesado[$i];
				echo "</td>";
				if($i>1 && is_int(($i+1)/5))
				{	
					echo "</tr>\n";
				}	
			}
			echo "</table>";
		
		}
		
		if($indiceProcesado>0)
		{	
			
 			echo "<table class='tablaMarco bloquecentralcuerpo'>";
			echo "<tr class='bloquecentralencabezado'><td colspan='5'>C&oacute;digos Exitosamente Procesados (".$indiceProcesado.")</td></tr>";
			for ($i=0;$i<$indiceProcesado;$i++) 
			{
			
				if(is_int($i/5))
				{
					echo "<tr>";
				}
				echo "<td class='cuadro_plano'>";
				echo $codigoProcesado[$i];
				echo "</td>";
				if($i>1 && is_int(($i+1)/5))
				{	
					echo "</tr>\n";
				}	
			}
			echo "</table>";
		
		}
		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		?><hr class="hr_subtitulo">
		<table class='tablaMarco bloquecentralcuerpo'>
		<tr>
		<td>
		<p>N&uacute;mero de registros a verificar:<? echo $registroProcesar?></p>
		<p>N&uacute;mero de registros no procesados:<? echo $indiceNoProcesado?></p>
		<hr class="hr_subtitulo">
		</td>	
		</tr>
		<tr class="bloquelateralcuerpo">
			<td>
			<a href="<?		
				$variable="pagina=admin_solicitud";
				$variable.="&accion=1";
				$variable.="&hoja=1";
				$variable.="&opcion=lista";
				$variable=$cripto->codificar_url($variable,$configuracion);
				echo $indice.$variable;		
				?>"> Revisar Solicitudes >>></a>
				
			</td>
		</tr>
		</table><?
		return true;
	}
	else
	{
		return false;
	
	}
	

}

function verificarCodigo($configuracion, $codigoEstudiante,$carrera,$acceso_db)
{
			
	//Buscar el codigo en la base de datos
	
	$cadena_sql=cadenaSQL_lote_recibo($configuracion, "datosEstudiante", $codigoEstudiante);	
	$pagador=acceso_db_lote_recibo($cadena_sql,$acceso_db,"busqueda");
	
	if(is_array($pagador))
	{
		$carreraEstudiante=$pagador[0][3];
		
		$i=0;
		while(isset($carrera[$i])) 
		{
			if($carreraEstudiante==$carrera[$i][0])
			{
				return $pagador;		
			}
		
			$i++;
		}
		
	}
	
	return false;
	

}

function guardarExencion($configuracion, $solicitud, $exencion,  $acceso_db)
{
	
	switch($solicitud["plantilla"])
	{
	
		case 1:
			//Pregrados General
			$idExencion = array(6 => 1, 7 => 2, 8 => 3, 9 => 4, 10 => 5 , 11 => 6, 12 => 7, 13 => 8, 14 => 9);
			break;
			
		case 2: //Postgrados Creditos
			$idExencion = array(7 => 1, 8 => 10, 9 => 11, 10 => 12 , 11 => 5, 12 => 13, 13 => 14, 14 => 15);			
			break;
			
		case 3://Postgrados SMLV
			$idExencion = array(7 => 1, 8 => 10, 9 => 11, 10 => 12 , 11 => 5, 12 => 13, 13 => 14, 14 => 15);			
			break;
	}
	
	$resultado=true;
	foreach($exencion as $columna => $valor) 
	{
		//echo $columna."=>".$valor."<br>";
		unset($variable);
		if (array_key_exists($columna, $idExencion)) 
		{
			//echo "Se va a guardar:".$solicitud["id_usuario"].": ".$columna."=>".$idExencion[$columna]."<br>";
			$solicitud["id_exencion"]=$idExencion[$columna];
			$cadena_sql=cadenaSQL_lote_recibo($configuracion, "insertarExencion", $solicitud);			
			$resultado=acceso_db_lote_recibo($cadena_sql,$acceso_db,"");
		}			
	}
	return $resultado;
	
	

}

function guardarSolicitud($configuracion, $solicitud, $acceso_db, $accesoOracle)
{
	
	
	
	$cadena_sql=cadenaSQL_lote_recibo($configuracion, "insertarSolicitud", $solicitud);
	$resultado=acceso_db_lote_recibo($cadena_sql,$acceso_db,"");
	if($resultado==true)
	{
		
		return $acceso_db->ultimo_insertado();
	
	}
	else
	{
		return false;
	
	}
}


function guardarConcepto($configuracion, $solicitud, $concepto,$acceso_db)
{
	
	$resultado=true;
	if(isset($concepto[0]))
	{
		$solicitud["id_concepto"]=1;
		$cadena_sql=cadenaSQL_lote_recibo($configuracion, "insertarConcepto", $solicitud);	
		//echo $cadena_sql;
		$resultado=acceso_db_lote_recibo($cadena_sql,$acceso_db,"");
	}
	
	if(isset($concepto[1]))
	{
		$solicitud["id_concepto"]=42;
		$cadena_sql=cadenaSQL_lote_recibo($configuracion, "insertarConcepto", $solicitud);	
		//echo $cadena_sql;
		$resultado=acceso_db_lote_recibo($cadena_sql,$acceso_db,"");
	}
	
	if(isset($concepto[2]))
	{
		$solicitud["id_concepto"]=43;
		$cadena_sql=cadenaSQL_lote_recibo($configuracion, "insertarConcepto", $solicitud);	
		//echo $cadena_sql;
		$resultado=acceso_db_lote_recibo($cadena_sql,$acceso_db,"");
	}
	
	if($resultado==true)
	{
		
		return true;
	
	}
	else
	{
		return false;
	
	}


}



function acceso_db_lote_recibo($cadena_sql,$acceso_db,$tipo)
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

function conceptoPlantilla($registro, $tipo)
{
	switch($tipo)
	{
		case 1:
		$columnaInicial=3;
		break;
		
		case 2:
		case 3:
		$columnaInicial=4;
		break;
	
	}
	for($j=$columnaInicial;$j<=($columnaInicial+2);$j++)
	{
		if(isset($registro[$j]))
		{
			$concepto[($j-$columnaInicial)]=$registro[$j];
		}
	
	}
	if(isset($concepto))
	{
		return $concepto;
	}
	else
	{
		return false;
	}
}

function exencionPlantilla($registro, $tipo)
{
	/*foreach ($registro as $key => $value) 
	{
	
		echo $key."=>".$value."<br>";
	
	}
	*/
	switch($tipo)
	{
		case 1:
		$columnaInicial=6;
		$columnaFinal=14;
		break;
		
		case 2:
		case 3:
		$columnaInicial=7;
		$columnaFinal=14;
		break;
			
	
	}
	
	for($j=$columnaInicial;$j<=$columnaFinal;$j++)
	{
		if(isset($registro[$j]))
		{
			$exencion[$j]=$registro[$j];
			
		}
	
	}
	if(isset($exencion))
	{
		return $exencion;	
	}
	else
	{
		return false;
	}
}

function cuotaPlantilla($registro, $tipo)
{
	switch($tipo)
	{
		case 1:
			if(isset($registro[2]))
			{
				
				$cuota=$registro[2];
			}
			else
			{
				$cuota="0";
			}
		
		break;
		
		case 2:
		case 3:
			if(isset($registro[3]))
			{
				$cuota=$registro[3];
			}
			else
			{
				$cuota="1";
			}
		
		break;
		
			
	
	}
	
	return $cuota;
}

function guardarCuota($configuracion, $solicitud, $acceso_db)
{

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosSolicitud.class.php");
$datoSolicitud=new datosSolicitud();
	
	switch($solicitud["plantilla"])
	{
		case 1:
			$laFechaPago=$datoSolicitud->rescatarDatoSolicitud($configuracion, "fechaPago", $solicitud["cuotaGuardar"], $acceso_db);
			$fecha=explode("/",$laFechaPago[0][1]);				
			$dia=$fecha[0];
			$mes=$fecha[1];
			$anno=$fecha[2];
			$solicitud["fechaOrdinaria"]=strtotime($mes."/".$dia."/".$anno);
			$fecha=explode("/",$laFechaPago[0][2]);				
			$dia=$fecha[0];
			$mes=$fecha[1];
			$anno=$fecha[2];
			$solicitud["fechaExtraordinaria"]=strtotime($mes."/".$dia."/".$anno);
			$cadena_sql=cadenaSQL_lote_recibo($configuracion, "insertarCuota", $solicitud);	
			$resultado=acceso_db_lote_recibo($cadena_sql,$acceso_db,"");
			break;
		
		case 2:
		case 3:
			$cadena_sql=cadenaSQL_lote_recibo($configuracion, "insertarCuota", $solicitud);	
			$resultado=acceso_db_lote_recibo($cadena_sql,$acceso_db,"");
			break;
	}
	
	return $resultado;
	

}






function guardarCuotas($configuracion, $registro, $solicitud, $acceso_db, $accesoOracle)
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/calendario.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosSolicitud.class.php");
	
	$miCalendario=new calendario();
	$datoSolicitud=new datosSolicitud();
	
	/*
	if($miCalendario->buscar_festivo(3,11,2008,$configuracion))
	{
		echo "es festivo";
	
	}
	else
	{
		echo "no es festivo";
	}
	exit;
	*/
	
	
	
	$resultado=true;
	switch($solicitud["plantilla"])
	{
		case 1:
			//Verificar si el estudiante ha solicitado pago diferido
			/*foreach ($solicitud as $key => $value) 
			{
			
				echo $key."=>".$value."<br>";
			
			}
			
			*/
			if($solicitud["diferido"]=='S')
			{
				$solicitud["porcentajeCuota"]=50;
				//Puede ser la 1, la 2 o las dos cuotas
				switch($solicitud["cuota"])
				{
					case 0:
						for($i=1;$i<3;$i++)
						{
							$solicitud["cuotaGuardar"]=$i;
							guardarCuota($configuracion, $solicitud,$acceso_db);	
						
						}
						break;
						
						
					case 1:
					//La primera cuota
						$solicitud["cuotaGuardar"]=1;
						guardarCuota($configuracion, $solicitud,$acceso_db);
						break;
					
					
					case 2:
					//La segunda cuota
						$solicitud["cuotaGuardar"]=2;
						guardarCuota($configuracion, $solicitud,$acceso_db);
						break;
					
				}
				
			}
			else
			{
				$solicitud["porcentajeCuota"]=100;
				$solicitud["cuotaGuardar"]=1;
				guardarCuota($configuracion, $solicitud, $acceso_db);
				
			}
			
			
			break;
		
		case 2:
		case 3:
			
			for($i=1;$i<($solicitud["cuota"]+1);$i++)
			{
				$solicitud["cuotaGuardar"]=$i;
				
				
				$fecha=explode("/",$registro[14+$i]);
				
				$dia=$fecha[0];
				$mes=$fecha[1];
				$anno=$fecha[2];
				$solicitud["fechaOrdinaria"]=strtotime($mes."/".$dia."/".$anno);
				
				//8 dias despues del pago ordinario
				$suma=8;
				$esta_fecha=strtotime($mes."/".$dia."/".$anno)+($suma*24*60*60);
				$extradia=date("d",$esta_fecha);
				$extrames=date("n",$esta_fecha);
				$extraanno=date("Y",$esta_fecha);
				
				
				while($miCalendario->buscar_festivo($extradia,$extrames,$extraanno,$configuracion))
				{
					$esta_fecha=strtotime($extrames."/".$extradia."/".$extraanno)+(24*60*60);
					$extradia=date("d",$esta_fecha);
					$extrames=date("n",$esta_fecha);
					$extraanno=date("Y",$esta_fecha);
				}
				
				$solicitud["fechaExtraordinaria"]=strtotime($extrames."/".$extradia."/".$extraanno);
				
				if(isset($registro[17+$i]) && is_numeric($registro[17+$i]) && ($registro[17+$i]<100))
				{
					$solicitud["porcentajeCuota"]=$registro[17+$i];
				}
				else
				{
					switch($solicitud["cuota"])
					{
						case 1:
							$solicitud["porcentajeCuota"]=100;
							break;
						case 2:
							if($i==1)
							{
								$solicitud["porcentajeCuota"]=60;
							}
							elseif($i==2)
							{
								$solicitud["porcentajeCuota"]=40;
							}
							break;
						case 3:
							if($i==1)
							{
								$solicitud["porcentajeCuota"]=40;
							}
							elseif( $i==2 || $i==3 )
							{
								$solicitud["porcentajeCuota"]=30;
							}
							break;
					}	
				
				}
				
				guardarCuota($configuracion, $solicitud,$acceso_db);
			}
			
	}
	
	
	if($resultado==true)
	{
		
		return true;
	
	}
	else
	{
		return false;
	
	}


}

function verificarSolicitudCodigo($configuracion, $codigoPagador, $acceso_db)
{
	$cadena_sql=cadenaSQL_lote_recibo($configuracion, "datosSolicitud", $codigoPagador);	
	
	$solicitud=acceso_db_lote_recibo($cadena_sql,$acceso_db,"busqueda");
	
	if(is_array($solicitud))
	{
		//Colocar esa(s) solicitud(es) en estado (2) cancelada
		$cadena_sql=cadenaSQL_lote_recibo($configuracion, "cancelarSolicitud", $codigoPagador);	
		$solicitud=acceso_db_lote_recibo($cadena_sql,$acceso_db,"");
	}
	
	return true;

}

function procesarRegistro($configuracion, $registro, $solicitud, $acceso_db,  $accesoOracle )
{
		
	//Verificar que no exista una solicitud del mismo estudiante sin imprimir.
	//En ese caso la ultima peticion sobreescribe la anterior.
	
	verificarSolicitudCodigo($configuracion, $solicitud["estudiante"],  $acceso_db);
	
	
	$concepto=conceptoPlantilla($registro, $solicitud["plantilla"]);
	$exencion=exencionPlantilla($registro, $solicitud["plantilla"]);
			
	$solicitud["cuota"]=cuotaPlantilla($registro, $solicitud["plantilla"]);	
	
	//Para postgrados se verifica que hayan colocado las fechas de pago de cada cuota y tengan un formato valido
	if($solicitud["plantilla"]==2 || $solicitud["plantilla"]==3)
	{			
		if(is_numeric($solicitud["cuota"]))
		{
			for($k=15; $k<(15+$solicitud["cuota"]); $k++)
			{
				if(!isset($registro[$k]))
				{
					return false;
				}
				else
				{
					$fecha=explode("/",$registro[$k]);
					if((count($fecha) != 3) || ($fecha[0]>31||$fecha[1]>12||$fecha[2] !=2008))
					{
						return false;
					}
				
				}
			
			}
		}
	
	}
	elseif($solicitud["plantilla"]==1)
	{
	
		if(is_numeric($solicitud["cuota"]))
		{
			if($solicitud["cuota"]!=0 && $solicitud["cuota"]!=1 && $solicitud["cuota"]!=2)
			{
				$solicitud["cuota"]=0;
			
			}
			
		}
		else
		{
			$solicitud["cuota"]=0;
		}
	
	}
	
	$solicitud["solicitud"]=guardarSolicitud($configuracion, $solicitud, $acceso_db, $accesoOracle );
	
	if(is_numeric($solicitud["solicitud"]))
	{
		
		$resultado=true;
		
		//Guardar informacion de las cuotas
		$resultado=guardarCuotas($configuracion, $registro, $solicitud, $acceso_db, $accesoOracle);
	
		
		
		//Guardar exenciones
		if(is_array($exencion))
		{
			$resultado=guardarExencion($configuracion, $solicitud, $exencion,  $acceso_db);
		}
		
		//***************************************************************
		
		
		//Guardar Concepto
				
		if(is_array($concepto))
		{
			$resultado =guardarConcepto($configuracion, $solicitud, $concepto,$acceso_db);
		}
		
		return $resultado;
	}		
	else
	{	
		return false;
	}



}

function carrerasCoordinador($configuracion, $variable, $accesoOracle)
{
	$cadena_sql=cadenaSQL_lote_recibo($configuracion, "carrerasCoordinador", $variable);	
	//echo $cadena_sql;
	$registroCarrera=acceso_db_lote_recibo($cadena_sql,$accesoOracle,"busqueda");
	
	if(is_array($registroCarrera))
	{
		return $registroCarrera;
	}
	else
	{
		return false;
		//return false;
	}

}

function cadenaSQL_lote_recibo($configuracion, $tipo, $variable)
{
	$cadena_sql="";
	switch($tipo)
	{
		case "insertarSolicitud":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo "; 
			$cadena_sql.="( ";
			$cadena_sql.="`id_usuario`, ";
			$cadena_sql.="`codigo_est`, ";
			$cadena_sql.="`estado`, ";
			$cadena_sql.="`anno`, ";
			$cadena_sql.="`periodo`, ";
			$cadena_sql.="`fecha`, ";
			$cadena_sql.="`cuota`, ";
			$cadena_sql.="`id_carrera`, ";
			$cadena_sql.="`tipoPlantilla`, ";
			$cadena_sql.="`unidad` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$variable['usuario']."', ";
			$cadena_sql.="'".$variable['estudiante']."', ";
			$cadena_sql.="'0', ";
			$cadena_sql.="'".$variable['anno']."', ";
			$cadena_sql.="'".$variable['periodo']."', ";
			$cadena_sql.="'".time()."', ";
			$cadena_sql.="'".$variable['cuota']."', ";
			$cadena_sql.="'".$variable['carrera']."', ";
			$cadena_sql.="'".$variable['plantilla']."', ";
			$cadena_sql.="'".$variable['unidad']."' ";
			$cadena_sql.=")";
			break;
		
		case "insertarConcepto":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudConcepto "; 
			$cadena_sql.="( ";
			$cadena_sql.="`id_solicitud`, ";
			$cadena_sql.="`id_concepto` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$variable['solicitud']."', ";
			$cadena_sql.="'".$variable['id_concepto']."' ";
			$cadena_sql.=")";
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
			$cadena_sql.="cra_abrev ";
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
			
		case "datosSolicitud":
			
			$cadena_sql="SELECT ";
			$cadena_sql.="id_solicitud_recibo ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="codigo_est=".$variable." ";
			$cadena_sql.="AND ";
			$cadena_sql.="estado=0 "; //Solicitud no impresa
			break;
			
		case "cancelarSolicitud":
			$cadena_sql="UPDATE ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo "; 
			$cadena_sql.="SET "; 
			$cadena_sql.="`estado`='2' ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="estado=0 ";
			$cadena_sql.="AND ";
			$cadena_sql.="codigo_est=".$variable." ";
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
			
		case "insertarExencion":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudExencion "; 
			$cadena_sql.="( ";
			$cadena_sql.="`id_solicitud`, ";
			$cadena_sql.="`id_exencion` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$variable['solicitud']."', ";
			$cadena_sql.="'".$variable['id_exencion']."' ";
			$cadena_sql.=")";
			break;
			
		case "insertarLote":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudLote "; 
			$cadena_sql.="( ";
			$cadena_sql.="`id_usuario`, ";
			$cadena_sql.="`nombreOriginal`, ";
			$cadena_sql.="`nombreInterno`, ";
			$cadena_sql.="`ip`, ";
			$cadena_sql.="`fecha` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$variable['id_usuario']."', ";
			$cadena_sql.="'".$variable['nombreArchivo']."', ";
			$cadena_sql.="'".$variable['nombreInterno']."', ";
			$cadena_sql.="'".$variable['ip']."', ";
			$cadena_sql.="'".time()."' ";
			$cadena_sql.=")";
			break;
		
		case "carrerasCoordinador":
			$cadena_sql="SELECT ";
			$cadena_sql.="cra_cod ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACCRA ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="CRA_EMP_NRO_IDEN=".$variable;
			break;
			
			
		case "insertarCuota":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudCuota "; 
			$cadena_sql.="( ";
			$cadena_sql.="`id_solicitud`, ";
			$cadena_sql.="`cuota`, ";
			$cadena_sql.="`porcentaje`, ";
			$cadena_sql.="`fecha_ordinaria`, ";
			$cadena_sql.="`fecha_extra` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$variable['solicitud']."', ";
			$cadena_sql.="'".$variable['cuotaGuardar']."', ";
			$cadena_sql.="'".$variable['porcentajeCuota']."', ";
			$cadena_sql.="'".$variable['fechaOrdinaria']."', ";
			$cadena_sql.="'".$variable['fechaExtraordinaria']."' ";
			$cadena_sql.=")";
			
			break;
	
	}
	//echo $cadena_sql."<br>";
	return $cadena_sql;
}


function cargarArchivoLote($configuracion, $enlace,$acceso_db)
{
	@set_time_limit (0);
	//Cargar el documento en el servidor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/subir_archivo.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
	
	$sesion=new sesiones($configuracion);
	$sesion->especificar_enlace($enlace);
	$registro=$sesion->rescatar_valor_sesion($configuracion,"id_usuario");
	
	if(is_array($registro))
	{
		$usuario=$registro[0][0];
	}
	
	$registro=$sesion->rescatar_valor_sesion($configuracion,"identificacion");
	
	if(is_array($registro))
	{
		$identificacion=$registro[0][0];
	}
	
	$subir = new subir_archivo();
		
	$subir->directorio_carga= $configuracion['raiz_documento']."/documento/";
	
	
	$subir->nombre_campo="archivo";
	$subir->tipos_permitidos= array("xls");
		
	// Maximo tamanno permitido
	//$subir->tamanno_maximo=5000000;
		
	$subir->especial= "[[:space:]]|[\"\*\\\'\%\$\&\@\<\>]";
			
	$subir->unico=TRUE;
	$subir->permisos=0777;
	$resultado=$subir->cargar();
		
	if($resultado==false)
	{
		return false;
	}
	else
	{
		//guardar datos de la carga
		if(isset($subir->log["nombre_archivo"][0]))
		{
			$cargar["nombreArchivo"]=$subir->log["nombre_archivo"][0];
		}
		
		if(isset($subir->log["mi_archivo"][0]))
		{
			$cargar["nombreInterno"]=$subir->log["mi_archivo"][0];
		}
		
		$cargar["id_usuario"]=$usuario;
		
		//Obtener direccion IP
		$fuentes_ip = array(
            	"HTTP_X_FORWARDED_FOR",
            	"HTTP_X_FORWARDED",
            	"HTTP_FORWARDED_FOR",
            	"HTTP_FORWARDED",
            	"HTTP_X_COMING_FROM",
            	"HTTP_COMING_FROM",
            	"REMOTE_ADDR",
        	);

        	foreach ($fuentes_ip as $fuentes_ip) {
            		// Si la fuente existe captura la IP
            		if (isset($_SERVER[$fuentes_ip])) {
            		    	$proxy_ip = $_SERVER[$fuentes_ip];
            		    	break;
            		}
        	}

        	$cargar["ip"] = (isset($proxy_ip)) ? $proxy_ip : @getenv("REMOTE_ADDR");
        	
		$cadena_sql=cadenaSQL_lote_recibo($configuracion, "insertarLote", $cargar);			
		acceso_db_lote_recibo($cadena_sql,$acceso_db,"");
		
		return $resultado;
	}
	
	/* Para tareas de depuracion
	
	if(isset($subir->log["resultado"]))
	{
		$matriz=$subir->log["resultado"];
		while (list($key, $val) = each($matriz) )
		{
			if(substr($val,0,5) == "ERROR")
			{
				echo $val;
				
			}
			
		}
	}
	*/
	
	return $resultado;

}

function leerArchivo($configuracion,$archivo)
{
	require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/reader.class.php");
	$data=new Spreadsheet_Excel_Reader();
	$data->read($configuracion['raiz_documento']."/documento/".$archivo);
	
	if(isset($data->sheets))
	{
		
		return $data;
	}
	else
	{
		return false;
	}
	
}


function mensajeErrorCarga($configuracion, $tipo, $valor="")
{

	$encabezado="";
	$cadena="";
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
	switch($tipo)
	{
		case "inconsistencia":
			$encabezado="ARCHIVO GUARDADO PERO NO SE PUDO PROCESAR";
			$cadena="<p>El archivo <span class='texto_negrita'>".$valor[0]."</span> SE HA GUARDADO EXITOSAMENTE EN NUESTRO SERVIDOR.</p>";
			$cadena.="<p> Sin embargo, debido a inconsistencias en su formato no se ha podido procesar autom&aacute;ticamente.</p>";
			$cadena.="<hr class='hr_subtitulo'>";
			$cadena.="<p>Por favor contacte a la Oficina Asesora de Sistemas indicando este n&uacute;mero de solicitud:<span class='texto_negrita'> ".substr($valor[1],0,(strlen($valor[1])-4))."</span></p>";
			break;
		
		case "noArchivo":
			$encabezado="ERROR EN LA CARGA DEL ARCHIVO";
			$cadena="Por favor contacte a la Oficina Asesora de Sistemas para soporte.";
			break;
			
		case "sinLeer":
			$encabezado="ERROR EN LA LECTURA DEL ARCHIVO";
			$cadena="<p>El archivo <span class='texto_negrita'>".$valor[0]."</span> SE HA GUARDADO EXITOSAMENTE EN NUESTRO SERVIDOR.</p";
			$cadena.="<p> Sin embargo, debido a inconsistencias en su formato no se ha podido leer los datos.</p>";
			$cadena.="<hr class='hr_subtitulo'>";
			$cadena.="<p>Por favor contacte a la Oficina Asesora de Sistemas indicando este n&uacute;mero de solicitud:<span class='texto_negrita'> ".substr($valor[1],0,(strlen($valor[1])-4))."</span></p>";
			
			break;
			
		case "parcial":
			$encabezado="ARCHIVO PROCESADO PARCIALMENTE";
			$cadena="<p>El archivo <span class='texto_negrita'>".$valor[0]."</span> SE HA GUARDADO EXITOSAMENTE EN NUESTRO SERVIDOR.</p>";
			$cadena.="<p> Sin embargo no se ha podido procesar totalmente.</p>";
			$cadena.="<hr class='hr_subtitulo'>";
			$cadena.="<p>Por favor contacte a la Oficina Asesora de Sistemas indicando este n&uacute;mero de solicitud:<span class='texto_negrita'> ".substr($valor[1],0,(strlen($valor[1])-4))."</span></p>";
			break;
	}
	
	echo "<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/basico/estilo.php' />\n";
		
	alerta::sin_registro($configuracion,$cadena, $encabezado);


}

		
?>
