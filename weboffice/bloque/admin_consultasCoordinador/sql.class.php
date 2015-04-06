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

class sql_admin_consultasCoordinador extends sql
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
				$cadena_sql.="TO_CHAR(SYSDATE, 'YYYYMMDD') ";
				//$cadena_sql.="FROM ";
				//$cadena_sql.="dual";
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

			case "controlnotas":
				$cadena_sql="SELECT DISTINCT ";
				$cadena_sql.="car_doc_nro, ";
				$cadena_sql.="doc_nombre ||' '||doc_apellido, ";
				$cadena_sql.="doc_nro_iden, ";
				$cadena_sql.="doc_telefono, ";
				$cadena_sql.="doc_celular, ";
				$cadena_sql.="doc_email, ";
				$cadena_sql.="INP_ASI_COD, "; //6
				$cadena_sql.="asi_nombre, ";
				$cadena_sql.="INP_NRO, ";
				$cadena_sql.="INP_NRO_INS, ";
				$cadena_sql.="INP_PAR1, ";//10
				$cadena_sql.="INP_PAR2, ";
				$cadena_sql.="INP_PAR3, ";
				$cadena_sql.="INP_PAR4, ";
				$cadena_sql.="INP_PAR5, ";
				$cadena_sql.="INP_PAR6, ";
				$cadena_sql.="INP_EXA, ";
				$cadena_sql.="INP_DEF, ";
				$cadena_sql.="doc_email_ins, ";
                                $cadena_sql.="(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)";
				$cadena_sql.="FROM v_acinsnotpar,acasi,accargas,accursos,achorarios,acdocente";
                                $cadena_sql.=" WHERE asi_cod = inp_asi_cod";
                                $cadena_sql.=" AND inp_asi_cod=cur_asi_cod";
                                $cadena_sql.=" AND inp_nro=cur_id";
                                $cadena_sql.=" AND car_doc_nro=doc_nro_iden";
                                $cadena_sql.=" AND cur_cra_cod=inp_cra_cod";
                                $cadena_sql.=" AND hor_id_curso=cur_id";
                                $cadena_sql.=" AND car_hor_id=hor_id";
				$cadena_sql.=" AND cur_ape_ano=".$variable[1]." ";
				$cadena_sql.="AND cur_ape_per=".$variable[2]." ";
				$cadena_sql.="AND inp_cra_cod = ".$variable[3]." ORDER BY 2,3 ";
			      break;

                            
			case "controlporcentaje":
				$cadena_sql="SELECT ";
				$cadena_sql.="car_doc_nro_iden, ";
				$cadena_sql.="doc_nombre ||' '||doc_apellido, ";
				$cadena_sql.="doc_nro_iden, ";
				$cadena_sql.="doc_telefono, ";
				$cadena_sql.="doc_celular, ";
				$cadena_sql.="doc_email, ";
				$cadena_sql.="INP_ASI_COD, "; //6
				$cadena_sql.="asi_nombre, ";
				$cadena_sql.="INP_NRO, ";
				$cadena_sql.="INP_NRO_INS, ";
				$cadena_sql.="INP_PAR1, ";//10
				$cadena_sql.="INP_PAR2, ";
				$cadena_sql.="INP_PAR3, ";
				$cadena_sql.="INP_PAR4, ";
				$cadena_sql.="INP_PAR5, ";
				$cadena_sql.="INP_PAR6, ";
				$cadena_sql.="INP_EXA, ";
				$cadena_sql.="INP_DEF, ";
				$cadena_sql.="doc_email_ins ";
				$cadena_sql.="FROM v_acinsnotpar,acasi,accarga,acdocente ";
				$cadena_sql.="WHERE asi_cod = inp_asi_cod ";
				$cadena_sql.="AND inp_asi_cod=car_cur_asi_cod ";
				$cadena_sql.="AND inp_nro=car_cur_nro ";
				$cadena_sql.="AND car_doc_nro_iden=doc_nro_iden ";
				$cadena_sql.="AND car_ape_ano=".$variable[1]." ";
				$cadena_sql.="AND car_ape_per=".$variable[2]." ";
				$cadena_sql.="AND inp_cra_cod = ".$variable[3]." ORDER BY 2,3 ";
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
