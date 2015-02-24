<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroLoteRecibo extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
// 		if(is_array($variable))
// 		{
// 		foreach($variable as $clave=>$valor)
// 		{
// 			echo $clave."=>".$valor."<br>";
// 		}
// 		}
		
		$variable=$conexion->verificar_variables($variable);		
		
		$cadena_sql="";
		switch($opcion)
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
				$cadena_sql.="`unidad`, ";
				$cadena_sql.="`observacion` ";
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
				$cadena_sql.="'".$variable['unidad']."', ";
				$cadena_sql.="'".$variable['observacion']."' ";
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
			case "insertarInteres":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudInteres "; 
				$cadena_sql.="( ";
				$cadena_sql.="`id_solicitud`, ";
				$cadena_sql.="`id_num_meses`, ";
				$cadena_sql.="`anno`, ";
				$cadena_sql.="`periodo` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.=$variable['solicitud'].", ";
				$cadena_sql.=$variable['id_interes'].", ";
				$cadena_sql.=$variable['anno_interes'].", ";
				$cadena_sql.=$variable['periodo_interes']." ";
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
				
			case "datosBasicosEstudiante":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cod, ";
				$cadena_sql.="est_nro_iden, ";
				$cadena_sql.="est_nombre, ";
				$cadena_sql.="est_cra_cod, ";
				$cadena_sql.="est_diferido, ";
				$cadena_sql.="est_estado_est ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_cod =".$variable." ";
				break;
			
			case "datosPagoEstudiante":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="emb_valor_matricula vr_mat ";
				$cadena_sql.="FROM ";
				$cadena_sql.="V_ACESTMATBRUTO ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="emb_est_cod =".$variable." ";
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
				$cadena_sql.="cer_est_cod =".$variable." ";
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
				$cadena_sql.="EST_COD =".$variable;
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
				$cadena_sql.="tipo=".$variable." ";
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
				$cadena_sql.="est_cod=".$variable;
				break;
				
			case "exencion":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_exencion ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."exencion ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="EXE_COD=".$variable;
				break;
				
			case "porcentajeExencion":
				//MySQL
				$cadena_sql="SELECT ";
				$cadena_sql.="porcentaje ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."exencion ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_exencion=".$variable." ";
				$cadena_sql.="LIMIT 1 ";
				break;
					
			case "numeroCuotas":
				$cadena_sql="SELECT ";
				$cadena_sql.="rpa_numero_cuotas ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACRANGOSPAGO ";
				$cadena_sql.="WHERE ";
				$cadena_sql.=$variable." BETWEEN rpa_limite_inferior AND rpa_limite_superior";
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
				//$cadena_sql.="WHERE ";
				//$cadena_sql.="CRA_EMP_NRO_IDEN=".$variable;
				break;
				

			case "verificaCalendario":
				$cadena_sql="SELECT ";
				$cadena_sql.="fua_verifica_fecha(".$variable["carrera"].",".$variable["evento"].")";
				$cadena_sql.="FROM ";
				$cadena_sql.="DUAL ";
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
	
	
}
?>
