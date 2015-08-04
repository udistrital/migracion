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

class sql_admin_consultasAdmisiones extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "datosAspirantes":
				$cadena_sql="SELECT a.ASP_APE_ANO ANO, ";
				$cadena_sql.="a.ASP_APE_PER PERIODO, ";
				$cadena_sql.="a.ASP_CRED CREDENCIAL, ";
				$cadena_sql.="B.ASP_APELLIDO ||' '||B.ASP_NOMBRE, ";
				$cadena_sql.="a.ASP_EMAIL , ";
				$cadena_sql.="a.ASP_TELEFONO , ";
				$cadena_sql.="TI_NOMBRE INSCRIPCION, ";
				$cadena_sql.="a.ASP_SNP SNP, ";
				$cadena_sql.="a.ASP_LOCALIDAD LOCALIDAD, ";
				$cadena_sql.="a.ASP_ESTRATO ESTRATO, ";
				$cadena_sql.="B.ASP_BIO BIOLOGIA, ";
				$cadena_sql.="B.ASP_QUI QUIMICA, ";
				$cadena_sql.="B.ASP_FIS FISICA, ";
				$cadena_sql.="B.ASP_SOC SOCIALES, ";
				$cadena_sql.="B.ASP_APT_VERBAL APT_VERBAL, ";
				$cadena_sql.="B.ASP_ESP_Y_LIT ESP_Y_LIT, ";
				$cadena_sql.="B.ASP_APT_MAT APT_MAT, ";
				$cadena_sql.="B.ASP_CON_MAT CON_MAT, ";
				$cadena_sql.="B.ASP_FIL FILOSOFIA, ";
				$cadena_sql.="B.ASP_HIS HISTORIA, ";
				$cadena_sql.="B.ASP_GEO GEOGRAFIA, ";
				$cadena_sql.="B.ASP_IDIOMA IDIOMA, ";
				$cadena_sql.="B.ASP_INTERDIS INTERDIS, ";
				$cadena_sql.="B.ASP_CIE_SOC CIENCIAS_SOCIALES ";
				$cadena_sql.="FROM ACASPERIADM, ACCRA, ACTIPINS, ACASPW a, ACASP B ";
				$cadena_sql.="WHERE APE_ESTADO = 'X' ";
				$cadena_sql.="AND CRA_COD = a.ASP_CRA_COD ";  
				$cadena_sql.="AND TI_COD = CASE WHEN B.ASP_TI_COD is NULL THEN a.ASP_TI_COD ELSE B.ASP_TI_COD END ";				
				$cadena_sql.="AND a.ASP_CRED = '".$variable[0]."' ";
				$cadena_sql.="AND APE_ANO = a.ASP_APE_ANO ";
				$cadena_sql.="AND APE_PER = a.ASP_APE_PER ";
				$cadena_sql.="AND a.ASP_APE_ANO = B.ASP_APE_ANO ";
				$cadena_sql.="AND a.ASP_APE_PER = B.ASP_APE_PER ";
				$cadena_sql.="and a.ASP_CRED = B.ASP_CRED ";
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
