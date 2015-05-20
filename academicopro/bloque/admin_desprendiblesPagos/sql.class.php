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
				$cadena_sql.="TO_CHAR(vcp_fecha,'DD/MM/YYYY'), ";
				$cadena_sql.="vcp_fondo, ";
				$cadena_sql.="TO_CHAR(vcp_fecha,'YYYY') VIGENCIA ";
				$cadena_sql.="from ";
				$cadena_sql.="v_cesantias_pagadas ";
				$cadena_sql.="where ";
				$cadena_sql.="vcp_nro_iden = ".$variable[0]." ";
				$cadena_sql.="ORDER BY vcp_fecha ASC";
				break;
						
						
			case "secciones_documento":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" sec_id           AS ID_SECCION,";
                                $cadena_sql.=" sec_nombre       AS SECCION,";
                                $cadena_sql.=" dos_posicion     AS POSICION,";
                                $cadena_sql.=" dos_contenido    AS CONTENIDO";
                                $cadena_sql.=" FROM cer_documento_seccion ";
                                $cadena_sql.=" INNER JOIN cer_seccion ON dos_id_seccion=sec_id";
                                $cadena_sql.=" WHERE dos_id_documento=".$variable;
                                $cadena_sql.=" AND dos_estado='A'";
                                $cadena_sql.=" ORDER BY POSICION";
                                break;
						
			case "parametros_documento":
                                $cadena_sql=" SELECT ";
                                $cadena_sql.=" pad_id_parametro AS ID_PARAMETRO,";
                                $cadena_sql.=" par_nombre       AS NOMBRE, ";
                                $cadena_sql.=" par_sql          AS SENTENCIA_SQL, ";
                                $cadena_sql.=" par_dbms         AS DBMS";
                                $cadena_sql.=" FROM cer_documento_parametro ";
                                $cadena_sql.=" INNER JOIN cer_parametro ON pad_id_parametro=par_id";
                                $cadena_sql.=" WHERE pad_estado='A'";
                                $cadena_sql.=" AND par_estado='A'";
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
