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

class sql_admin_admisiones extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		//$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "anioper":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano, ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado='".$variable[10]."'";	
				break;
			
			case "periodoacademico":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano, ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado IN ('A','P','I')" ;
				$cadena_sql.="AND ";
				$cadena_sql.="ape_per NOT IN (2) ";
				$cadena_sql.="ORDER BY ape_ano DESC ";    
				break;
			
			case "fechaactual":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(SYSDATE, 'YYYYMMDD')) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="dual";
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
		//echo "<br>".$cadena_sql."<br>";
		return $cadena_sql;
	}
	
	
}
?>
