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

class sql_adminAdmisiones extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "carreras":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod, ";
				$cadena_sql.="cra_abrev ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gedep, actipcra, accra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra_estado = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="tra_nivel = 'PREGRADO' ";
				$cadena_sql.="AND ";
				$cadena_sql.="dep_cod = cra_dep_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="tra_cod = cra_tip_cra ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_se_ofrece='S' ";
				$cadena_sql.="ORDER BY cra_cod ASC ";
				break;
			
			case "datosUsuarios":
				$cadena_sql="SELECT ";
				$cadena_sql.="emp_nombre, ";
				$cadena_sql.="emp_nro_iden ";				
				$cadena_sql.="FROM ";
				$cadena_sql.="peemp ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="emp_nro_iden=".$variable." ";
				break;
			
			case "periodoacad":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano, ";
				$cadena_sql.="ape_per, ";
				$cadena_sql.="ape_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperiadm ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado='".$variable[3]."' "; 
				break;

			case "cuentaRecibos":
				$cadena_sql="SELECT ";
				$cadena_sql.="count(ama_cra_cod) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACADMMAT ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ama_cra_cod = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ama_estado='A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="AMA_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="AMA_PER = ".$variable[2]." ";
				break;

			case "recibosActual":	
				$cadena_sql="SELECT ";
				$cadena_sql.="ama_secuencia, ";
				$cadena_sql.="ama_codigo, ";
				$cadena_sql.="ama_cra_cod, ";
				$cadena_sql.="ama_valor, ";
				$cadena_sql.="ama_ext, ";
				$cadena_sql.="ama_ano, ";
				$cadena_sql.="ama_per, ";
				$cadena_sql.="ama_cuota, ";
				$cadena_sql.="TO_CHAR(AMA_FECHA, 'YYYYMMDD'), ";
				$cadena_sql.="ama_estado, ";
				$cadena_sql.="TO_CHAR(AMA_FECHA_ORD, 'YYYYMMDD'), ";
				$cadena_sql.="TO_CHAR(AMA_FECHA_EXT, 'YYYYMMDD'), ";
				$cadena_sql.="asp_nro_iden, ";
				$cadena_sql.="(LTRIM(RTRIM(ASP_APELLIDO)))||' '||(LTRIM(RTRIM(ASP_NOMBRE))) nombre, ";
				$cadena_sql.="cra_abrev, ";
				$cadena_sql.="ama_asp_cred ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACADMMAT, ";
				$cadena_sql.="ACASP, ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="AMA_ASP_CRED = ASP_CRED ";
				$cadena_sql.="AND ";
				$cadena_sql.="ASP_APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ASP_APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="AMA_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="AMA_PER = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="AMA_ESTADO='A' ";	
				$cadena_sql.="AND ";
				$cadena_sql.="ama_asp_cred = ".$variable[4]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ama_cra_cod = cra_cod";
				//$cadena_sql.="AND ";
				//$cadena_sql.="EMA_IMP_RECIBO=0";	
				//echo $cadena_sql;
				break;
			case "recibosActualFecha":
				$cadena_sql="SELECT COUNT (ama_secuencia) ";
				$cadena_sql.="FROM ACADMMAT ";
				$cadena_sql.="WHERE AMA_ANO = ".$variable[1]." "; 
				$cadena_sql.="AND AMA_PER = ".$variable[2]." ";
				$cadena_sql.="AND AMA_ESTADO='A' "; 
				$cadena_sql.="AND TO_CHAR(AMA_FECHA, 'DD/MM/YYYY') = '".$variable[5]."' ";
				break;
			case "recibosActualCredencial":
				$cadena_sql="SELECT ama_secuencia ";
				$cadena_sql.="FROM ACADMMAT ";
				$cadena_sql.="WHERE AMA_ANO = ".$variable[1]." "; 
				$cadena_sql.="AND AMA_PER = ".$variable[2]." ";
				$cadena_sql.="AND AMA_ESTADO='A' "; 
				$cadena_sql.="AND ama_asp_cred = ".$variable[4]."";
				break;
			case "consultaFechasPago":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_CHAR(ban_fecha_adm_ord,'DD/MM/YYYY'), ";
				$cadena_sql.="TO_CHAR(ban_fecha_adm_ext,'DD/MM/YYYY') ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acbanco ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ban_ano = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ban_per = ".$variable[2]." ";
				break;
			case "modificarFechasPago":
				$cadena_sql="UPDATE ";
				$cadena_sql.="acbanco ";
				$cadena_sql.="SET ";
				$cadena_sql.="ban_fecha_adm_ord=TO_DATE('".$variable[6]."','DD/MM/YYYY'), ";
				$cadena_sql.="ban_fecha_adm_ext=TO_DATE('".$variable[7]."','DD/MM/YYYY') ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ban_ano = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ban_per = ".$variable[2]." ";
				break;
			case "consultarAccaleventos":
				$cadena_sql="SELECT ";
				$cadena_sql.="ace_cod_evento, ";
				$cadena_sql.="acd_descripcion, ";
				$cadena_sql.="TO_CHAR(ace_fec_ini,'DD/MM/YYYY'), ";
				$cadena_sql.="TO_CHAR(ace_fec_fin,'DD/MM/YYYY') ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accaleventos,acdeseventos ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ace_anio = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_periodo = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_cod_evento = acd_cod_evento ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_cod_evento IN (19,20) ";
				break;
			case "modificarFechasEvento":
				$cadena_sql="UPDATE ";
				$cadena_sql.="accaleventos ";
				$cadena_sql.="SET ";
				$cadena_sql.="ace_fec_ini=TO_DATE('".$variable[6]."','DD/MM/YYYY'), ";
				$cadena_sql.="ace_fec_fin=TO_DATE('".$variable[7]."','DD/MM/YYYY') ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ace_anio = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_periodo = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_cod_evento = ".$variable[4]." ";
				break;
			case "insertarFechasEvento":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="accaleventos ";
				$cadena_sql.="(";
				$cadena_sql.="ace_cod_evento, ";
				$cadena_sql.="ace_cra_cod, ";
				$cadena_sql.="ace_fec_ini, ";
				$cadena_sql.="ace_fec_fin, ";
				$cadena_sql.="ace_tip_cra, ";
				$cadena_sql.="ace_dep_cod, ";
				$cadena_sql.="ace_anio, ";
				$cadena_sql.="ace_periodo, ";
				$cadena_sql.="ace_estado, ";
				$cadena_sql.="ace_habilitar_ex ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="$variable[4], ";
				$cadena_sql.="0, ";
				$cadena_sql.="TO_DATE('".$variable[6]."','DD/MM/YYYY'), ";
				$cadena_sql.="TO_DATE('".$variable[7]."','DD/MM/YYYY'), ";
				$cadena_sql.="0, ";
				$cadena_sql.="20, ";
				$cadena_sql.="$variable[1], ";
				$cadena_sql.="$variable[2], ";
				$cadena_sql.="'A', ";
				$cadena_sql.="'N' ";
				$cadena_sql.=")";
				break;
			default:
				$cadena_sql="";
				break;
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}
	
	
}
?>
