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

class sql_admin_consultaRecibos extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "anioper":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano, ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado='A'";	
				break;

                        case "seleccionarPeriodo":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano ANO, ape_per PER ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado in ('A','X') ";	
				$cadena_sql.="ORDER BY ape_ano, ape_per";	
				break;
			
			case "fechaactual":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(SYSDATE, 'YYYYMMDD')) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="dual";
				break;
				
			case "listaProyectos":
				$cadena_sql="SELECT cra_cod, cra_abrev ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra_emp_nro_iden = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_estado = 'A' ";
				$cadena_sql.="ORDER BY cra_cod ASC ";
				break;
			 
			case "listaDecanos":
				$cadena_sql="SELECT dep_cod, dep_nombre, emp_nro_iden, emp_nombre ";
				$cadena_sql.="FROM gedep, peemp ";
				$cadena_sql.="WHERE dep_emp_cod = emp_cod ";
				$cadena_sql.="AND emp_nro_iden = ".$variable[0]." ";
				$cadena_sql.="AND emp_car_cod = 218 ";
				$cadena_sql.="AND emp_estado_e <> 'R'";
				break;
			
			case "listaProyectosDecano":
				$cadena_sql="SELECT cra_cod, cra_abrev ";
				$cadena_sql.="FROM gedep, accra, acdocente ";
				$cadena_sql.="WHERE dep_cod = ".$variable[3]." ";
				$cadena_sql.="AND dep_cod = cra_dep_cod ";
				$cadena_sql.="AND cra_estado = 'A' ";
				$cadena_sql.="AND cra_emp_nro_iden = doc_nro_iden ";
				$cadena_sql.="AND doc_estado = 'A' ";
				$cadena_sql.="ORDER BY 1 ASC"; 
				break;
			
			case "listaDocentes":
				$cadena_sql="SELECT DISTINCT(doc_nro_iden) cedula, ";
				$cadena_sql.="(trim(doc_apellido)||' '||trim(doc_nombre)) nombre, ";
				$cadena_sql.="fua_doc_digito_pt(doc_nro_iden) digito, ";
				$cadena_sql.="mntac.fua_horas_plan_trabajo(".$variable[3].",doc_nro_iden) total, ";
				$cadena_sql.="doc_celular celular, ";
				$cadena_sql.="doc_email email ";
				$cadena_sql.="FROM acasperi, accra, acdocente, accarga ";
				$cadena_sql.="WHERE ape_estado = '".$variable[10]."' ";
				$cadena_sql.="AND cra_cod = ".$variable[3]." ";
				$cadena_sql.="AND ape_ano = car_ape_ano ";
				$cadena_sql.="AND ape_per = car_ape_per ";
				$cadena_sql.="AND cra_cod = car_cra_cod ";
				$cadena_sql.="AND car_estado = 'A' ";
				$cadena_sql.="AND car_doc_nro_iden = doc_nro_iden ";
				$cadena_sql.="ORDER BY 2 ASC ";
				break;
				
			case "listaProyectosPregrado":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod, ";
				$cadena_sql.="cra_abrev ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA_TIP_CRA IN (SELECT DISTINCT(TRA_COD) FROM ";
				$cadena_sql.="MNTAC.";
				$cadena_sql.="ACTIPCRA WHERE tra_nivel='PREGRADO') ";
				$cadena_sql.="AND ";
				$cadena_sql.="CRA_ESTADO='A' ";
				break;

			case "totalGenerado":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="count(*) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACESTMAT ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_cra_cod=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ANO= ".$variable[1]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_PER=".$variable[2]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ESTADO='A' ";	//Recibo Activo no impreso
				$cadena_sql.="AND ";
				$cadena_sql.="ema_cuota=1 ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_enviado_email='N' ";  //Recibo Bloqueado
				$cadena_sql.="AND ";
				$cadena_sql.="ema_est_cod NOT IN (SELECT deu_est_cod FROM acdeudores) ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_est_cod NOT IN (SELECT est_cod FROM acest WHERE est_estado_est IN ('J','B','W','Z','U') and est_cra_cod=".$variable[3].") ";
				break;

			case "pendientePorEnviar":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="count(*) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACESTMAT ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_cra_cod=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ANO= ".$variable[1]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_PER=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_cuota=1 ";
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ESTADO='A' ";	//Recibo Activo no impreso
				$cadena_sql.="AND ";
				$cadena_sql.="ema_enviado_email='P' ";  //Recibo Bloqueado
				break;

			case "generadoCompleto":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="ema_secuencia, ";
				$cadena_sql.="ema_est_cod, ";
				$cadena_sql.="ema_cra_cod, ";
				$cadena_sql.="ema_valor, ";
				$cadena_sql.="ema_ext, ";
				$cadena_sql.="ema_ano, ";
				$cadena_sql.="ema_per, ";
				$cadena_sql.="ema_cuota, ";
				$cadena_sql.="ema_fecha, ";
				$cadena_sql.="ema_estado, ";
				$cadena_sql.="TO_CHAR(EMA_FECHA_ORD, 'YYYYMMDD'), ";
				$cadena_sql.="TO_CHAR(EMA_FECHA_EXT, 'YYYYMMDD'), ";
				$cadena_sql.="est_nro_iden, ";
				$cadena_sql.="est_nombre, ";
				$cadena_sql.="cra_abrev, ";
				$cadena_sql.="est_estado_est, ";
				$cadena_sql.="eot_email, ";
				$cadena_sql.="eot_email_ins, ";
				$cadena_sql.="ema_enviado_email ";//18
				$cadena_sql.="FROM ";
				$cadena_sql.="ACESTMAT, ";
				$cadena_sql.="ACEST, ";
				$cadena_sql.="ACCRA, ";
				$cadena_sql.="ACESTOTR ";    
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_cra_cod=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ANO= ".$variable[1]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_PER=".$variable[2]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ESTADO='A' ";	
				$cadena_sql.="AND ";
				$cadena_sql.="ema_cra_cod = cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_est_cod = est_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_est_cod = eot_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_cuota = 1 "; 
				$cadena_sql.="AND ";
				$cadena_sql.="ema_enviado_email='P'";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_est_cod NOT IN (SELECT deu_est_cod FROM acdeudores) ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_est_cod NOT IN (SELECT est_cod FROM acest WHERE est_estado_est IN ('J','B','W','Z','U') and est_cra_cod=".$variable[3].") ";
				$cadena_sql.="ORDER BY ema_secuencia DESC ";
				/*$cadena_sql.=" WHERE ";
				$cadena_sql.="R ";
				$cadena_sql.="BETWEEN ";
				$cadena_sql.=($variable[3]-1)*$configuracion['registro']+1; //Limite inferior
				$cadena_sql.="AND ";
				$cadena_sql.=(($variable[3]-1)*$configuracion['registro'])+($configuracion['registro']); //Limite superior*/
				break;

			/*case "generadoCompleto":
				$cadena_sql="SELECT EST_COD, ";
				$cadena_sql.="EOT_EMAIL, ";
				$cadena_sql.="EOT_EMAIL_INS ";
				$cadena_sql.="from GEDEP, ACCRA, ACEST, ACESTOTR ";
				$cadena_sql.="where DEP_COD = CRA_DEP_COD ";
				$cadena_sql.="and CRA_COD = EST_CRA_COD ";
				//$cadena_sql.="AND cra_cod=".$variable[3]." ";
				$cadena_sql.="and EST_ACUERDO = 1993027 ";
				$cadena_sql.="and EST_ESTADO_EST in ('J') ";
				$cadena_sql.="and EST_COD = EOT_COD ";
				$cadena_sql.="and EST_COD in (select INS_EST_COD from ACINS where INS_ANO = 2012 and INS_PER = 1 and INS_EST_COD = EST_COD) ";
				$cadena_sql.="order by DEP_COD, ";
				$cadena_sql.="CRA_COD, ";
				$cadena_sql.="EST_COD asc ";
				break;*/

			case "infoEstudiante":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cod, ";
				$cadena_sql.="est_nro_iden, ";
				$cadena_sql.="est_nombre, ";
				$cadena_sql.="est_cra_cod, ";
				$cadena_sql.="est_diferido, ";
				$cadena_sql.="est_estado_est, ";
				$cadena_sql.="estado_descripcion ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest, ";
				$cadena_sql.="acestado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_cod =".$variable[4]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado_cod = est_estado_est ";
				$cadena_sql.="AND ";
				$cadena_sql.="est_estado_est IN ('J','B') ";
				break;
			
			case "deudaEstudiante":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="deu_est_cod, ";
				$cadena_sql.="deu_cpto_cod, ";
				$cadena_sql.="deu_material, ";
				$cadena_sql.="deu_ano, ";
				$cadena_sql.="deu_per, ";
				$cadena_sql.="deu_estado, ";
				$cadena_sql.="deu_estado, ";
				$cadena_sql.="cpto_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acconcepto, ";
				$cadena_sql.="acdeudores ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="deu_est_cod =".$variable[4]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cpto_cod = deu_cpto_cod";
				break;

			case "modificaEstadoEnvio":
				//En ORACLE
				$cadena_sql="UPDATE ";
				$cadena_sql.="acestmat ";
				$cadena_sql.="SET ";
				$cadena_sql.="ema_enviado_email='S' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_est_cod =".$variable[4]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_cra_cod=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ANO= ".$variable[1]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_PER=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_enviado_email='P' ";  
				//$cadena_sql.="AND ";
				//$cadena_sql.="EMA_ESTADO='A' ";
				//$cadena_sql.="AND ";
				//$cadena_sql.="ema_est_cod NOT IN (SELECT deu_est_cod FROM acdeudores WHERE deu_est_cod =".$variable[4].") ";
				//$cadena_sql.="AND ";
				//$cadena_sql.="ema_est_cod NOT IN (SELECT est_cod FROM acest WHERE est_cod =".$variable[4]." AND est_estado_est IN ('B')) ";
				break;

			 case "renovar":

				$cadena_sql="UPDATE ";
				$cadena_sql.="dbms_condor.dbms_valor_sesion ";//linea para servidor de pruebas*/
				//$cadena_sql.="dbms.dbms_valor_sesion ";
				$cadena_sql.="SET ";
				$cadena_sql.="valor=".$variable['vl']." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_sesion='".$variable['id_sesion']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="variable='expiracion'";
				break;
				
			default:
				$cadena_sql="";
				break;
		}
		//echo "<br>".$cadena_sql."<br>"; 
		return $cadena_sql;
	}
	
	
}
?>
