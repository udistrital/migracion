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

class sql_admin_listaCursos extends sql
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
				$cadena_sql.="ape_estado='".$variable[10]."'";	
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
			
			case "listaCursos":
				$cadena_sql="SELECT cur_asi_cod COD_ESPACIO, ";
				$cadena_sql.="asi_nombre        ESPACIO, ";
				$cadena_sql.="cur_id            ID_GRUPO, ";
				$cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo) GRUPO,";
				$cadena_sql.="cur_nro_cupo      CUPO, ";
				$cadena_sql.="cur_nro_ins       INSCRITOS, ";
				$cadena_sql.="(cur_nro_cupo - cur_nro_ins) ";
				$cadena_sql.="FROM accursos,acasi,acasperi ";
				$cadena_sql.="WHERE ape_ano = cur_ape_ano ";
				$cadena_sql.="AND ape_per = cur_ape_per ";
				$cadena_sql.="AND ape_estado = 'A' ";
				$cadena_sql.="AND cur_estado = 'A' ";
				$cadena_sql.="AND cur_asi_cod = asi_cod ";
				$cadena_sql.="AND asi_estado = 'A' ";
				$cadena_sql.="AND cur_cra_cod = ".$variable[3]." ";
				$cadena_sql.="ORDER BY ESPACIO,GRUPO";
				break;

			case "carreraEstudiante":
				$cadena_sql="SELECT est_cra_cod, ";
				$cadena_sql.="est_nombre ";
				$cadena_sql.="FROM acest ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_cod = ".$variable[4]."";
				break;
			
			case "verificaRegistroAcuerdo":
				$cadena_sql="SELECT acc_est_cod ";
				$cadena_sql.="FROM acacuerdo ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="acc_est_cod = ".$variable[4]."";
				break;
			
			case "registrarEstudianteAcuerdo":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="acacuerdo ";
				$cadena_sql.="(";
				$cadena_sql.="acc_num_acuerdo, ";
				$cadena_sql.="acc_cra_cod, ";
				$cadena_sql.="acc_est_cod, ";
				$cadena_sql.="acc_fecha_colicitud, ";
				$cadena_sql.="acc_numero_radicado, ";
				$cadena_sql.="acc_observaciones, ";
				$cadena_sql.="acc_usuario ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="$variable[9], ";
				$cadena_sql.="$variable[8], ";
				$cadena_sql.="$variable[4], ";
				$cadena_sql.="TO_DATE('".$variable[5]."','dd/mm/yy'), ";
				$cadena_sql.="'$variable[6]', ";
				$cadena_sql.="'$variable[7]', ";
				$cadena_sql.="$variable[0] ";
				$cadena_sql.=")";
				break;
			case "verificaEstudianteCoordinador":
				//Oracle
				$cadena_sql="SELECT ";
				$cadena_sql.="'S' ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest,geusucra ";
				$cadena_sql.="WHERE (est_cra_cod = usucra_cra_cod AND est_cod=".$variable[4]." AND usucra_nro_iden=".$variable[0].")";
				$cadena_sql.="OR ";
				$cadena_sql.="(est_cra_cod = usucra_cra_cod AND usucra_cra_cod=999 AND usucra_nro_iden=".$variable[0].") ";
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
