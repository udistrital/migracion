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

class sql_adminIntensidadHoraria extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "datosUsuarios":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="emp_cod, ";
				$cadena_sql.="emp_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="peemp ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="emp_nro_iden=".$variable."";
				break;
			
			case "actualizaNotas":
				//Oracle
				$cadena_sql="BEGIN ACTUALIZANOTAS_EST(".$variable[0]."); END; ";
				break;
			
			default:
				$cadena_sql="";
				break;
		}
		echo $cadena_sql."<br>";
		return $cadena_sql;
	}
	
	
}
?>
