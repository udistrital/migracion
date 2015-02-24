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

class sql_admin_consultasAsesor extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "datosEstudiantes":
				$cadena_sql="SELECT trim(substr(EST_NOMBRE,1,INSTR(EST_NOMBRE,' ',1,2)-1)) APELLIDOS, ";
				$cadena_sql.="trim(SUBSTR(EST_NOMBRE,INSTR(EST_NOMBRE,' ',1,2)+1)) NOMBRES, ";
				$cadena_sql.="trim(EST_NRO_IDEN) IDENTIFICACION, ";
				$cadena_sql.="trim(DEP_NOMBRE) FACULTAD, ";
				$cadena_sql.="trim(CRA_NOMBRE) CARRERA, ";
				$cadena_sql.="trim(EST_COD) CODIGO, ";
				$cadena_sql.="trim(ESTADO_NOMBRE) ESTADO_ACADEMICO, ";
				$cadena_sql.="trim(EST_TELEFONO) TELEFONO_1, ";
				$cadena_sql.="trim(EOT_TEL_CEL) TELEFONO_2, ";
				$cadena_sql.="trim(EOT_EMAIL) EMAIL, ";
				$cadena_sql.="trim(EOT_EMAIL_INS) EMAIL_INSTITUCIONAL ";
				$cadena_sql.="FROM GEDEP, ACCRA, ACESTADO, ACEST, ACESTOTR ";
				$cadena_sql.="WHERE DEP_COD = CRA_DEP_COD ";
				$cadena_sql.="AND CRA_COD = EST_CRA_COD ";  
				$cadena_sql.="AND ESTADO_COD = EST_ESTADO_EST ";
				$cadena_sql.="AND EST_COD = EOT_COD ";
				$cadena_sql.="AND EST_ESTADO_EST <> 'N' ";
				$cadena_sql.="AND ((EST_COD = '".$variable[0]."') OR (EST_NRO_IDEN = '".$variable[1]."') ";
				$cadena_sql.="OR(EST_NOMBRE LIKE UPPER('".$variable[2]."'))) ";
				$cadena_sql.="ORDER BY 1,3 ASC";
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
