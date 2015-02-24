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

class sql_admin_gestionHorarios extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "listaPeriodos":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano||'-'||ape_per, ";
				$cadena_sql.="ape_ano||'-'||ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ape_estado NOT IN ('X','V') ";  
				$cadena_sql.="ORDER BY ape_ano DESC ";
				break;

			case "periodoParaReporte":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano||'-'||ape_per, ";
				$cadena_sql.="ape_ano||'-'||ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ape_estado IN ('A','X') ";  
				$cadena_sql.="ORDER BY ape_ano DESC ";
				break;

			case "periodoNuevo":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano||'-'||ape_per, ";
				$cadena_sql.="ape_ano||'-'||ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ape_estado IN ('A','X') ";  
				$cadena_sql.="ORDER BY ape_ano DESC ";
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
				$cadena_sql.="cra_emp_nro_iden = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_estado = 'A' ";
				$cadena_sql.="ORDER BY cra_cod ASC ";
				break;
			
			case "verificaRegistro":
				$cadena_sql="SELECT * ";
				$cadena_sql.="FROM ";
				$cadena_sql.="achorper ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="hpe_cra_cod = ".$variable[0]." ";
				//$cadena_sql.="AND ";
				//$cadena_sql.="hpe_ape_ano_nvo = ".$variable[5]." ";
				//$cadena_sql.="AND ";
				//$cadena_sql.="hpe_ape_per_nvo = ".$variable[6]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="hpe_estado = 'A'";
				break;

			case "insertarAnioPer":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="achorper ";
				$cadena_sql.="(";
				$cadena_sql.="hpe_cra_cod, ";
				$cadena_sql.="hpe_ape_ano_ant, ";
				$cadena_sql.="hpe_ape_per_ant, ";
				$cadena_sql.="hpe_ape_ano_nvo, ";
				$cadena_sql.="hpe_ape_per_nvo, ";
				$cadena_sql.="hpe_estado ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable[0].", ";
				$cadena_sql.=$variable[3].", ";
				$cadena_sql.=$variable[4].", ";
				$cadena_sql.=$variable[5].", ";
				$cadena_sql.=$variable[6].", ";
				$cadena_sql.="'A'";
				$cadena_sql.=")";
				break;

			case "carrera":
				$cadena_sql="SELECT cra_cod, cra_abrev ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra_cod = ".$variable[0]." ";
				break;

			case "borrarPeriodo":
				$cadena_sql="DELETE ";
				$cadena_sql.="achorper ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="hpe_cra_cod=".$variable[0]."";
				break;

			case "resumenHorarioCurso":
				$cadena_sql="SELECT * ";
				$cadena_sql.="FROM ";
				$cadena_sql.="v_curso_horario_resumen ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="carrera = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ano = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="periodo = ".$variable[3]." ";
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
