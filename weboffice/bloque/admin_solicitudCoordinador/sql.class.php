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

class sql_adminSolicitudCoordinador extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
	
		$variable=$conexion->verificar_variables($variable);	
		
		switch($opcion)
		{
			case "totalSolicitud":
						
				$cadena_sql="SELECT ";
				$cadena_sql.="count(*) ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="estado=0 ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_carrera=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="anno=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="periodo=".$variable[2]." ";
				  
				break;
				
			case "solicitud":
				//MySQL
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
				$cadena_sql.="id_carrera=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="anno=".$variable[1]." "; 
				$cadena_sql.="AND ";
				$cadena_sql.="periodo=".$variable[2]." "; 				
				$cadena_sql.=" LIMIT ".(($variable[3]-1)*$configuracion['registro']).",".$configuracion['registro'];	
				break;
			
			case "datosSolicitud":
				//MySQL
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
				$cadena_sql.="id_solicitud_recibo= ".$variable." ";
				break;
				
			case "cuotasSolicitud":
				$cadena_sql="SELECT ";
				$cadena_sql.="`id_solicitud`, ";
				$cadena_sql.="`cuota`, ";
				$cadena_sql.="`porcentaje`, ";
				$cadena_sql.="`fecha_ordinaria`, ";
				$cadena_sql.="`fecha_extra` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudCuota "; 
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_solicitud=".$variable;				
				break;

			case "detalleDiferido":
				$cadena_sql="SELECT est_diferido, ";
				$cadena_sql.="mntac.";
				$cadena_sql.="fua_verifica_diferido(est_cod) ";
				$cadena_sql.="FROM acest ";
				$cadena_sql.="WHERE est_cod=".$variable;
				break;
				
			case "conceptoSolicitud":
				$cadena_sql="SELECT ";
				$cadena_sql.="`id_solicitud`, ";
				$cadena_sql.="`id_concepto`, ";
				$cadena_sql.="`etiqueta`, ";
				$cadena_sql.="`valor`, ";
				$cadena_sql.="`factor`, ";
				$cadena_sql.="`base` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudConcepto, ";
				$cadena_sql.=$configuracion["prefijo"]."referenciaPago ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_solicitud=".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_concepto=id_referencia ";
				
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
				$cadena_sql.="cra_dep_cod, ";
				$cadena_sql.="mntac.";
				$cadena_sql.="fua_verifica_diferido(est_cod) ";
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
				
			case "numeroCuotas":
				$cadena_sql="SELECT ";
				$cadena_sql.="rpa_numero_cuotas ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACRANGOSPAGO ";
				$cadena_sql.="WHERE ";
				$cadena_sql.=$variable." BETWEEN rpa_limite_inferior AND rpa_limite_superior";
				break;
				
			case "carreraCoordinador":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod, ";
				$cadena_sql.="cra_abrev ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA_EMP_NRO_IDEN=".$variable;
				break;
				
			case "secuencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="seq_matricula.NEXTVAL ";
				$cadena_sql.="FROM ";
				$cadena_sql.="dual ";
				break;
				
			case "actualizarSolicitud":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo "; 
				$cadena_sql.="SET "; 
				$cadena_sql.="`estado`='1', ";
				$cadena_sql.="`secuencia`=".$variable[0].", ";
				$cadena_sql.="`observacion`='".$variable[1]."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_solicitud_recibo=".$variable[2]." ";
				break;

			case "verificapago":
				$cadena_sql="SELECT ema_est_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACESTMAT "; 
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_est_cod=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_ano_pago=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_per_pago=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_pago='S' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_obs not like'%RECIBO%SABER%PRO%' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_cra_cod NOT IN (SELECT cra_cod FROM actipcra, accra WHERE tra_nivel <> 'PREGRADO' AND tra_cod = cra_tip_cra)";
				break;
	
			case "actualizaracestmat":
				$cadena_sql="UPDATE ";
				$cadena_sql.="ACESTMAT "; 
				$cadena_sql.="SET "; 
				$cadena_sql.="ema_estado='I' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_est_cod=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_ano=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_per=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_cuota=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_imp_recibo<>2 ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_pago<>'S' ";
				break;
			
			case "insertarCuota":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="ACESTMAT ";
				$cadena_sql.="(";
				$cadena_sql.="ema_est_cod, ";
				$cadena_sql.="ema_cra_cod, ";
				$cadena_sql.="ema_valor, ";
				$cadena_sql.="ema_ext, ";
				$cadena_sql.="ema_ano, ";
				$cadena_sql.="ema_per, ";
				$cadena_sql.="ema_cuota, ";
				$cadena_sql.="ema_fecha_ord, ";
				$cadena_sql.="ema_fecha_ext, ";
				$cadena_sql.="ema_fecha, ";
				$cadena_sql.="ema_estado, ";
				$cadena_sql.="ema_secuencia, ";	
				$cadena_sql.="ema_imp_recibo, ";
				$cadena_sql.="ema_ano_pago, ";
				$cadena_sql.="ema_per_pago, ";
				$cadena_sql.="ema_obs ";					
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable[0].", ";
				$cadena_sql.=$variable[1].", ";
				$cadena_sql.=$variable[2].", ";
				$cadena_sql.=$variable[3].", ";
				$cadena_sql.=$variable[4].", ";
				$cadena_sql.=$variable[5].", ";
				$cadena_sql.=$variable[6].", ";
				$cadena_sql.="TO_DATE(".$variable[7].",'yyyymmdd'), ";
				$cadena_sql.="TO_DATE(".$variable[8].",'yyyymmdd'), ";
				$cadena_sql.="SYSDATE, ";
				$cadena_sql.="'A', ";
				$cadena_sql.=$variable[9].", ";
				$cadena_sql.="1, ";
				$cadena_sql.=$variable[4].", ";
				$cadena_sql.=$variable[5].", ";	
				$cadena_sql.="'".$variable[11]."'";			
				$cadena_sql.=")";
				break;
				
			case "insertarConcepto":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="ACREFEST ";
				$cadena_sql.="(";
				$cadena_sql.="aer_ano, ";
				$cadena_sql.="aer_secuencia, ";
				$cadena_sql.="aer_bancod, ";
				$cadena_sql.="aer_refcod, ";
				$cadena_sql.="aer_valor ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable[0].", ";
				$cadena_sql.=$variable[1].", ";
				$cadena_sql.=$variable[2].", ";
				$cadena_sql.=$variable[3].", ";
				$cadena_sql.=$variable[4]." ";
				$cadena_sql.=")";
				break;
				
			case "insertarConsiga":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$configuracion["prefijo"]."consiga ";
				$cadena_sql.="(";
				$cadena_sql.="id_secuencia, ";
				$cadena_sql.="anno, ";
				$cadena_sql.="cadena, ";
				$cadena_sql.="id_usuario ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable[0].", ";
				$cadena_sql.=$variable[1].", ";
				$cadena_sql.="'".$variable[2]."', ";
				$cadena_sql.=$variable[3]." ";
				$cadena_sql.=")";
				break;
		
			case "clausulaInsertarSecuencia":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="ACESTMAT ";
				$cadena_sql.="(";
				$cadena_sql.="ema_est_cod, ";
				$cadena_sql.="ema_cra_cod, ";
				$cadena_sql.="ema_valor, ";
				$cadena_sql.="ema_ext, ";
				$cadena_sql.="ema_ano, ";
				$cadena_sql.="ema_per, ";
				$cadena_sql.="ema_cuota, ";
				$cadena_sql.="ema_fecha, ";
				$cadena_sql.="ema_estado, ";
				$cadena_sql.="ema_secuencia, ";
				$cadena_sql.="ema_fecha_ord, ";
				$cadena_sql.="ema_fecha_ext ";				
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable["referencia1"].", ";
				$cadena_sql.=$variable["idCarrera"].", ";
				$cadena_sql.=$variable["matricula"].", ";
				$cadena_sql.=$variable["matriculaExtra"].", ";
				$cadena_sql.=$variable["anno"].", ";
				$cadena_sql.=$variable["periodo"].", ";
				$cadena_sql.=$variable["cuota"].", ";
				$cadena_sql.="SYSDATE, ";
				$cadena_sql.="'A', ";
				$cadena_sql.="seq_matricula.NEXTVAL,";
				$cadena_sql.=$variable["fechaOrdinaria"].", ";
				$cadena_sql.=$variable["fechaExtraordinaria"]." ";
				$cadena_sql.=")";
				break;
				
			case "fechaPago":
			
				//MySQL
				$cadena_sql="SELECT ";
				$cadena_sql.="`cuota`, ";
				$cadena_sql.="`ordinaria`, ";
				$cadena_sql.="`extraordinaria` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."fechasPago ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cuota=".$variable;
				break;
			
			case "deudaEstudiante":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="deu_est_cod, ";
				$cadena_sql.="deu_cpto_cod, ";
				$cadena_sql.="deu_material, ";
				$cadena_sql.="deu_ano, ";
				$cadena_sql.="deu_per, ";
				$cadena_sql.="deu_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdeudores ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="deu_est_cod =".$variable." ";
				break;

			case "verificaCalendario":
				$cadena_sql="SELECT ";
				$cadena_sql.="fua_fecha_recibo(".$variable.") ";
				$cadena_sql.="FROM ";
				$cadena_sql.="DUAL ";
				break;	
                            
			case "reciboEstudiante":
				//MySQL
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
				$cadena_sql.="codigo_est= ".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado=0 ";
				break;

			case "verificaEstudianteCoordinador":
				//Oracle
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cod, ";
				$cadena_sql.="est_cra_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest,geusucra ";
				$cadena_sql.="WHERE est_cra_cod  = usucra_cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="est_cod= ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="usucra_nro_iden=".$variable[1];
				break;
				
			case "historicoEstudiante":
				$cadena_sql="SELECT ";
				$cadena_sql.="ema_valor, ";
				$cadena_sql.="ema_ext, ";
				$cadena_sql.="ema_ano, ";
				$cadena_sql.="ema_per, ";
				$cadena_sql.="ema_secuencia, ";
				$cadena_sql.="ema_cuota, ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(ema_fecha_ord,'YYYYMMDD')), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(ema_fecha_ext,'YYYYMMDD')), ";
				$cadena_sql.="ema_pago, ";
				$cadena_sql.="ema_ano_pago, ";
				$cadena_sql.="ema_per_pago, ";
				$cadena_sql.="ema_cra_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acestmat ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_est_cod= ".$variable[0]." ";
				//$cadena_sql.="AND ";
				//$cadena_sql.="ema_cra_cod= ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_estado='A' ";
				$cadena_sql.="ORDER BY ema_ano_pago DESC";
				break;

			default:
				$cadena_sql="";
				break;
		}
		return $cadena_sql;
	}
	
	
}
?>
