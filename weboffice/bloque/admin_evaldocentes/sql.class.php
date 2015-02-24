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

class sql_admin_evaldocentes extends sql
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
				
			case "observaciones":
				$cadena_sql="SELECT ";
				$cadena_sql.="dep_cod CodFacultad, ";
				$cadena_sql.="dep_nombre NombreFacultad, ";
				$cadena_sql.="est_cra_cod Codcra, ";
				$cadena_sql.="cra_nombre Carrera, ";
				$cadena_sql.="epe_ape_ano Año, ";
				$cadena_sql.="epe_ape_per Periodo, ";
				$cadena_sql.="epe_doc_nro_iden Docente, ";//6
				$cadena_sql.="doc_nombre||' '||doc_apellido Nombre, ";
				$cadena_sql.="epe_observa Observacion, ";
				$cadena_sql.="epe_cur_asi_cod ,";
				$cadena_sql.="asi_nombre ";    
				$cadena_sql.="FROM ACEVAPROEST, ACEST, ACCRA, GEDEP, ACDOCENTE, ACASI ";
				$cadena_sql.="WHERE epe_est_cod=est_cod ";
				$cadena_sql.="AND est_cra_cod=cra_cod ";
				$cadena_sql.="AND cra_dep_cod=dep_cod ";
				$cadena_sql.="AND doc_nro_iden=epe_doc_nro_iden ";
				$cadena_sql.="AND epe_ape_ano=".$variable[1]." ";
				$cadena_sql.="AND epe_ape_per=".$variable[2]." ";
				$cadena_sql.="AND dep_cod=".$variable[4]." ";
				$cadena_sql.="AND epe_observa NOT IN ('NULL') ";
				$cadena_sql.="AND ASI_COD = EPE_CUR_ASI_COD ";
				$cadena_sql.="ORDER BY 1,3,7";
			      break;
			
			case "observacionesDocente":
				$cadena_sql="select dep_cod CodFacultad, ";
				$cadena_sql.="dep_nombre NombreFacultad, ";
				$cadena_sql.="est_cra_cod Codcra, ";
				$cadena_sql.="cra_nombre Carrera, ";
				$cadena_sql.="epe_ape_ano Año, ";
				$cadena_sql.="epe_ape_per Periodo, ";
				$cadena_sql.="epe_doc_nro_iden Docente, ";
				$cadena_sql.="doc_nombre||' '||doc_apellido Nombre, ";
				$cadena_sql.="epe_observa Observacion, ";
				$cadena_sql.="epe_cur_asi_cod ,";
				$cadena_sql.="asi_nombre ";
				$cadena_sql.="from ACEVAPROEST, ACEST, ACCRA, GEDEP, ACDOCENTE, ACASI ";
				$cadena_sql.="WHERE epe_doc_nro_iden=".$variable[0]." ";
				$cadena_sql.="AND ASI_COD = EPE_CUR_ASI_COD ";
				$cadena_sql.="AND epe_est_cod=est_cod ";
				$cadena_sql.="AND est_cra_cod=cra_cod ";
				$cadena_sql.="AND cra_dep_cod=dep_cod ";
				$cadena_sql.="AND doc_nro_iden=epe_doc_nro_iden ";
				$cadena_sql.="AND epe_observa NOT IN ('NULL') ";
				$cadena_sql.="ORDER BY 1,3,5 DESC,6 DESC ";
			      break;

			case "completoObservaciones":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="(";				
				$cadena_sql.="SELECT ";
				$cadena_sql.="dep_cod, ";
				$cadena_sql.="dep_nombre, ";
				$cadena_sql.="est_cra_cod, ";
				$cadena_sql.="cra_nombre, ";
				$cadena_sql.="epe_ape_ano, ";
				$cadena_sql.="epe_ape_per, ";
				$cadena_sql.="epe_doc_nro_iden, ";
				$cadena_sql.="doc_nombre||' '||doc_apellido Nombre, ";
				$cadena_sql.="epe_observa, ";
				$cadena_sql.="epe_cur_asi_cod ,";
				$cadena_sql.="asi_nombre, ";    
				$cadena_sql.="(ROW_NUMBER() OVER (ORDER BY epe_ape_ano)) R ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACEVAPROEST, ";
				$cadena_sql.="ACEST, ";
				$cadena_sql.="ACCRA, ";
				$cadena_sql.="GEDEP, ";
				$cadena_sql.="ACDOCENTE, ";
				$cadena_sql.="ACASI ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="epe_est_cod=est_cod ";
				$cadena_sql.="AND est_cra_cod=cra_cod ";
				$cadena_sql.="AND cra_dep_cod=dep_cod ";
				$cadena_sql.="AND ASI_COD = EPE_CUR_ASI_COD ";
				$cadena_sql.="AND doc_nro_iden=epe_doc_nro_iden ";
				$cadena_sql.="AND epe_ape_ano=".$variable[1]." ";
				$cadena_sql.="AND epe_ape_per=".$variable[2]." ";
				$cadena_sql.="AND dep_cod=".$variable[4]." ";
				$cadena_sql.="AND epe_observa NOT IN ('NULL') ";
				$cadena_sql.="ORDER BY 1,3,7 ";
				$cadena_sql.=") ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="R ";
				$cadena_sql.="BETWEEN ";
				$cadena_sql.=($variable[3]-1)*$configuracion['registro']+1; //Limite inferior
				$cadena_sql.=" AND ";
				$cadena_sql.=(($variable[3]-1)*$configuracion['registro'])+($configuracion['registro']); //Limite superior
				break;
					
			case "totalObservaciones":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="count(*) ";
				$cadena_sql.="FROM ACEVAPROEST, ACEST, ACCRA, GEDEP, ACDOCENTE ";
				$cadena_sql.="WHERE epe_est_cod=est_cod ";
				$cadena_sql.="AND est_cra_cod=cra_cod ";
				$cadena_sql.="AND cra_dep_cod=dep_cod ";
				$cadena_sql.="AND doc_nro_iden=epe_doc_nro_iden ";
				$cadena_sql.="AND epe_ape_ano=".$variable[1]." ";
				$cadena_sql.="AND epe_ape_per=".$variable[2]." ";
				$cadena_sql.="AND dep_cod=".$variable[4]." ";
				$cadena_sql.="AND epe_observa NOT IN ('NULL') ";
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
