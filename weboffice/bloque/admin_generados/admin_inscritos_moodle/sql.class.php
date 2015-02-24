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
// ini_set("memory_limit","520M");
class sql_adminInscritos_moodle extends sql
{
   
	function cadena_sql($configuracion,$opcion,$variable="")
	{
		switch($opcion)
		{
			case "consultaCra":
				$cadena_sql="SELECT ";
				$cadena_sql.="distinct (codigo_carrera), ";
				$cadena_sql.="carrera ";
				$cadena_sql.="FROM ";
				$cadena_sql.="v_oas_moodle ";
			break;

			case "consultaAsignaturas":
				$cadena_sql="SELECT ";
				$cadena_sql.="distinct(grupo), ";
				$cadena_sql.="asignatura, ";
				$cadena_sql.="asi_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="v_oas_moodle ";
                                $cadena_sql.="inner join acasi on asi_cod=asignatura ";
				$cadena_sql.="where codigo_carrera='".$variable[0]."' ";
				$cadena_sql.="order by asignatura ";
			break;

			case "consultaAsignaturasTotales":
				$cadena_sql="SELECT ";
				$cadena_sql.="distinct(asignatura), ";
				$cadena_sql.="asi_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="v_oas_moodle ";
                                $cadena_sql.="inner join acasi on asi_cod=asignatura ";
				$cadena_sql.="order by asignatura ";
			break;

			case "consultaInscritos":
				$cadena_sql="SELECT ";
				$cadena_sql.="codigo, ";
				$cadena_sql.="nombre, ";
				$cadena_sql.="apellido, ";
				$cadena_sql.="email, ";
				$cadena_sql.="carrera, ";
				$cadena_sql.="asignatura, ";
				$cadena_sql.="grupo ";
				$cadena_sql.="FROM ";
				$cadena_sql.="v_oas_moodle ";
                                $cadena_sql.="inner join acasi on asi_cod=asignatura ";
				$cadena_sql.="where codigo_carrera='".$variable[0]."' ";
				$cadena_sql.="and asignatura='".$variable[1]."' ";
				$cadena_sql.="and grupo='".$variable[2]."' ";
				$cadena_sql.="order by asignatura ";
			break;
                    
			case "consultaInscritosAsig":
				$cadena_sql="SELECT ";
				$cadena_sql.="codigo, ";
				$cadena_sql.="nombre, ";
				$cadena_sql.="apellido, ";
				$cadena_sql.="email, ";
				$cadena_sql.="carrera, ";
				$cadena_sql.="asignatura, ";
				$cadena_sql.="grupo, ";
				$cadena_sql.="asi_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="v_oas_moodle ";
                                $cadena_sql.="inner join acasi on asi_cod=asignatura ";
				$cadena_sql.="where asignatura='".$variable."' ";
				$cadena_sql.="order by asignatura ";
			break;
                        default:
				$cadena_sql="";
				break;
                 }

                 return $cadena_sql;
        }
	
	
}
?>

