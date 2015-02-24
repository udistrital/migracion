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

class sql_admin_certIngresosRetenciones extends sql
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
			
			case "DatosUsuarios":
				$cadena_sql="SELECT ";
				$cadena_sql.="emp_nombre, ";
				$cadena_sql.="emp_nro_iden ";
				$cadena_sql.="FROM ";
				$cadena_sql.="peemp ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="emp_nro_iden=".$variable[0]." ";
				break;
				
			case "fechaactual":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(SYSDATE, 'YYYYMMDD')) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="dual";
				break;
				
			case "cesantias":
				$cadena_sql="select ";
				$cadena_sql.="vcp_valor, ";
				$cadena_sql.="vcp_fecha, ";
				$cadena_sql.="vcp_fondo ";
				$cadena_sql.="from ";
				$cadena_sql.="v_cesantias_pagadas ";
				$cadena_sql.="where ";
				$cadena_sql.="vcp_nro_iden = ".$variable[0]." ";
				$cadena_sql.="ORDER BY vcp_fecha ASC";
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
