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
                                $cadena_sql=" SELECT DISTINCT(cra_cod),cra_nombre";
                                $cadena_sql.=" FROM ACINS";
                                $cadena_sql.=" INNER JOIN accra ON cra_cod = ins_cra_cod";
                                $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
                                $cadena_sql.=" INNER JOIN accursos ON cur_ape_ano=ins_ano";
                                $cadena_sql.=" AND cur_ape_per=ins_per";
                                $cadena_sql.=" AND cur_id=ins_gr";
                                $cadena_sql.=" INNER JOIN acasperi ON ape_ano=ins_ano";
                                $cadena_sql.=" AND ape_per=ins_per";
                                $cadena_sql.=" WHERE APE_ESTADO = 'A'";
                                $cadena_sql.=" AND ASI_IND_CATEDRA = 'S'";
                                $cadena_sql.=" AND INS_ESTADO = 'A'";
			break;

			case "consultaAsignaturas":
                                $cadena_sql=" SELECT DISTINCT(cur_grupo),cur_asi_cod,asi_nombre,cur_id";
                                $cadena_sql.=" FROM ACINS";
                                $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
                                $cadena_sql.=" INNER JOIN accursos ON cur_ape_ano=ins_ano";
                                $cadena_sql.=" AND cur_ape_per=ins_per";
                                $cadena_sql.=" AND cur_id=ins_gr";
                                $cadena_sql.=" INNER JOIN acasperi ON ape_ano=ins_ano";
                                $cadena_sql.=" AND ape_per=ins_per";
                                $cadena_sql.=" WHERE APE_ESTADO = 'A'";
                                $cadena_sql.=" AND ASI_IND_CATEDRA = 'S'";
                                $cadena_sql.=" AND INS_ESTADO = 'A'";
                                $cadena_sql.=" and ins_cra_cod='".$variable."'";
				$cadena_sql.=" ORDER BY cur_asi_cod ";
			break;

			case "consultaAsignaturasTotales":
                                $cadena_sql=" SELECT DISTINCT(asi_cod),asi_nombre";
                                $cadena_sql.=" FROM ACINS";
                                $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
                                $cadena_sql.=" INNER JOIN accursos ON cur_ape_ano=ins_ano";
                                $cadena_sql.=" AND cur_ape_per=ins_per";
                                $cadena_sql.=" AND cur_id=ins_gr";
                                $cadena_sql.=" INNER JOIN acasperi ON ape_ano=ins_ano";
                                $cadena_sql.=" AND ape_per=ins_per";
                                $cadena_sql.=" WHERE APE_ESTADO = 'A'";
                                $cadena_sql.=" AND ASI_IND_CATEDRA = 'S'";
                                $cadena_sql.=" AND INS_ESTADO = 'A'";
                                $cadena_sql.=" ORDER BY asi_cod";
			break;

			case "consultaInscritos":
                                $cadena_sql=" SELECT est_cod,";
                                $cadena_sql.=" substr(EST_NOMBRE,INSTR(EST_NOMBRE,' ',1,2)+1),";
                                $cadena_sql.=" substr(EST_NOMBRE,1,INSTR(EST_NOMBRE,' ',1,2)-1),";
                                $cadena_sql.=" eot_email_ins,";
                                $cadena_sql.=" cra_nombre,";
                                $cadena_sql.=" asi_cod,";
                                $cadena_sql.=" cur_grupo,";
                                $cadena_sql.=" asi_nombre,";
                                $cadena_sql.=" est_nro_iden";
                                $cadena_sql.=" FROM acins";
                                $cadena_sql.=" INNER JOIN acasperi ON ape_ano=ins_ano";
                                $cadena_sql.=" AND ape_per=ins_per";
                                $cadena_sql.=" INNER JOIN acest ON est_cod=ins_est_cod";
                                $cadena_sql.=" INNER JOIN acestotr ON eot_cod=est_cod";
                                $cadena_sql.=" INNER JOIN accra ON cra_cod = est_cra_cod";
                                $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
                                $cadena_sql.=" INNER JOIN accursos ON cur_asi_cod=ins_asi_cod";
                                $cadena_sql.=" AND cur_ape_ano=ins_ano";
                                $cadena_sql.=" AND cur_ape_per=ins_per";
                                $cadena_sql.=" AND cur_id=ins_gr";
                                $cadena_sql.=" where ape_estado = 'A'";
                                $cadena_sql.=" AND asi_ind_catedra = 'S'";
                                $cadena_sql.=" AND ins_estado = 'A'";
                                $cadena_sql.=" AND ins_cra_cod='".$variable[0]."' ";
				$cadena_sql.=" AND ins_asi_cod='".$variable[1]."' ";
				$cadena_sql.=" AND cur_id='".$variable[2]."' ";
				$cadena_sql.=" ORDER BY asi_cod ";
			break;
                    
			case "consultaInscritosAsig":
                                $cadena_sql=" SELECT est_cod,";
                                $cadena_sql.=" substr(EST_NOMBRE,INSTR(EST_NOMBRE,' ',1,2)+1),";
                                $cadena_sql.=" substr(EST_NOMBRE,1,INSTR(EST_NOMBRE,' ',1,2)-1),";
                                $cadena_sql.=" eot_email_ins,";
                                $cadena_sql.=" cra_nombre,";
                                $cadena_sql.=" asi_cod,";
                                $cadena_sql.=" cur_grupo,";
                                $cadena_sql.=" asi_nombre,";
                                $cadena_sql.=" est_nro_iden";
                                $cadena_sql.=" FROM acins";
                                $cadena_sql.=" INNER JOIN acasperi ON ape_ano=ins_ano";
                                $cadena_sql.=" AND ape_per=ins_per";
                                $cadena_sql.=" INNER JOIN acest ON est_cod=ins_est_cod";
                                $cadena_sql.=" INNER JOIN acestotr ON eot_cod=est_cod";
                                $cadena_sql.=" INNER JOIN accra ON cra_cod = est_cra_cod";
                                $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
                                $cadena_sql.=" INNER JOIN accursos ON cur_asi_cod=ins_asi_cod";
                                $cadena_sql.=" AND cur_ape_ano=ins_ano";
                                $cadena_sql.=" AND cur_ape_per=ins_per";
                                $cadena_sql.=" AND cur_id=ins_gr";
                                $cadena_sql.=" where ape_estado = 'A'";
                                $cadena_sql.=" AND asi_ind_catedra = 'S'";
                                $cadena_sql.=" AND ins_estado = 'A'";
                                $cadena_sql.=" AND ins_asi_cod='".$variable."' ";
				$cadena_sql.=" ORDER BY asi_cod";
			break;
                        default:
				$cadena_sql="";
				break;
                 }

                 return $cadena_sql;
        }
	
	
}
?>

