<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.0.1    Maritza Callejas    05/09/2013
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");
class sql_registroCalculoModelosBienestar extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($tipo,$variable="") {
        switch($tipo) {
            
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano, ape_per FROM acasperi ";
                $cadena_sql.="WHERE ape_estado like '%P%'";
                break;
            
            case 'consultar_reglamento_estudiante':

                $cadena_sql=" SELECT ";
                $cadena_sql.=" REG_ANO,";
                $cadena_sql.=" REG_PER,";
                $cadena_sql.=" REG_MOTIVO,";
                $cadena_sql.=" REG_PROMEDIO,";
                $cadena_sql.=" REG_CAUSAL_EXCLUSION,";
                $cadena_sql.=" REG_REGLAMENTO";
                $cadena_sql.=" FROM reglamento ";
                $cadena_sql.=" WHERE REG_EST_COD= ".$variable;
                $cadena_sql.=" ORDER BY reg_ano,reg_per";

                break;
            
            case "datos_estudiante":
                $cadena_sql=" SELECT";
                $cadena_sql.=" est_cod CODIGO,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" est_cra_cod CARRERA,";
                $cadena_sql.=" est_pen_nro PENSUM,";
                $cadena_sql.=" est_nro_iden DOCUMENTO,";
                $cadena_sql.=" TRIM(est_ind_cred) IND_CRED,";
                $cadena_sql.=" est_estado_est ESTADO,";
                $cadena_sql.=" estado_activo ESTADO_ACTIVO,";
                $cadena_sql.=" fa_promedio_nota(est_cod) PROMEDIO, " ;
                $cadena_sql.=" est_acuerdo ACUERDO, " ;
                $cadena_sql.=" TO_CHAR(eot_fecha_nac, 'yyyymmdd') FEC_NACIMIENTO, ";
                $cadena_sql.=" TO_CHAR(eot_fecha_grado_secundaria, 'yyyymmdd') FEC_GRADO ";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN acestado ON est_estado_est=estado_cod";
                $cadena_sql.=" INNER JOIN acestotr on EOT_COD= EST_COD ";
                $cadena_sql.=" WHERE est_cod =".$variable." ";
                
                break;
           
              
            case 'consultarEspaciosCursados':

                $cadena_sql="SELECT DISTINCT not_asi_cod CODIGO, ";
                $cadena_sql.="asi_nombre NOMBRE, ";
                $cadena_sql.="not_nota NOTA, ";
                $cadena_sql.="not_cred CREDITOS,";
                $cadena_sql.="not_sem NIVEL, ";
                $cadena_sql.="not_ano ANO, ";
                $cadena_sql.="not_per PERIODO, ";
                $cadena_sql.="nob_cod CODIGO_OBSERVACION, ";
                $cadena_sql.="nob_nombre NOTA_OBSERVACIONES, ";
                $cadena_sql.="not_nro_ht HTD, ";
                $cadena_sql.="not_nro_hp HTC";
                $cadena_sql.=" FROM acnot ";
                $cadena_sql.=" INNER JOIN acasi ON acnot.not_asi_cod = acasi.asi_cod  ";
                //$cadena_sql.=" INNER JOIN acpen ON acnot.not_cra_cod = acpen.pen_cra_cod AND acnot.not_asi_cod = acpen.pen_asi_cod ";
                $cadena_sql.=" INNER JOIN acest ON est_cod=not_est_cod and not_cra_cod=est_cra_cod ";
                $cadena_sql.=" INNER JOIN acnotobs ON acnot.not_obs = acnotobs.nob_cod ";
                $cadena_sql.=" where not_est_cod = '".$variable."' ";
                //$cadena_sql.=" AND NOT_OBS != 19  ";
                //$cadena_sql.=" AND NOT_OBS != 20  ";
                $cadena_sql.=" AND not_est_reg like '%A%' ";
                $cadena_sql.=" ORDER BY not_asi_cod, not_ano DESC, not_per DESC";
                
                break;            
            
            case 'consultar_espacios_plan_estudio_estudiante':
                $cadena_sql=" select pen_asi_cod CODIGO, pen_sem SEMESTRE, pen_ind_ele ELECTIVA, pen_cre CREDITOS from acpen";
                $cadena_sql.=" where pen_nro=".$variable['plan'];
                $cadena_sql.=" and pen_cra_cod=".$variable['proyecto'];
                $cadena_sql.=" and pen_estado='A'";
                $cadena_sql.=" and pen_sem>0";
                $cadena_sql.=" and pen_asi_cod not in";
                $cadena_sql.=" (select clp_asi_cod from acclasificacpen where clp_cea_cod =4)";
                $cadena_sql.=" order by pen_sem";
                break;
             case 'nota_aprobatoria':

                $cadena_sql="SELECT cra_nota_aprob";
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" WHERE CRA_COD=".$variable['codProyectoEstudiante'];
                break;            

            case 'matriculas':

                $cadena_sql=" SELECT ";
                $cadena_sql.=" est_cod, ";
                $cadena_sql.=" est_cra_cod, ";
                $cadena_sql.=" est_estado, ";
                $cadena_sql.=" est_ano, ";
                $cadena_sql.=" est_per  ";
                $cadena_sql.=" FROM acesthis ";
                $cadena_sql.=" WHERE est_estado in ('A','B','H') ";
                $cadena_sql.=" AND est_reg='A' ";
                $cadena_sql.=" and est_cod= ".$variable;
                $cadena_sql.=" ORDER BY est_ano,est_per";
                break;
       
            case 'consultarEstudiantes':

                $cadena_sql=" SELECT ";
                $cadena_sql.=" est_cod COD_ESTUDIANTE ";
                $cadena_sql.=" FROM acesthis ";
                $cadena_sql.=" WHERE est_estado in ('A','B','H') ";
                $cadena_sql.=" AND est_reg='A' ";
                $cadena_sql.=" and est_cra_cod= ".$variable['codProyecto'];
                $cadena_sql.=" and est_ano= ".$variable['ano'];
                $cadena_sql.=" and est_per= ".$variable['periodo'];
                $cadena_sql.=" ORDER BY est_cod";
                break;
       
            case 'registrarRiesgoYRendimiento':
                
                $cadena_sql=" UPDATE reglamento";
                $cadena_sql.=" SET REG_NUMERO_PRUEBAS_AC='".$variable['pruebas']."'";
                $cadena_sql.=", REG_INDICE_REPITENCIA=".$variable['indRepitencia'];
                $cadena_sql.=", REG_INDICE_PERMANENCIA=".$variable['indPermanencia'];
                $cadena_sql.=", REG_INDICE_NIVELACION=".$variable['indNivelacion'];
                $cadena_sql.=", REG_RENDIMIENTO_AC=".$variable['rendimiento'];
                $cadena_sql.=", REG_INDICE_ATRASO=".$variable['indAtraso'];
                $cadena_sql.=", REG_EDAD_INGRESO=".$variable['edad'];
                $cadena_sql.=", REG_NUM_SEMESTRES_INGRESO=".$variable['semestres'];
                $cadena_sql.=", REG_INDICE_RIESGO=".$variable['indRiesgo'];
                $cadena_sql.=" WHERE REG_EST_COD=".$variable['codEstudiante'];
                $cadena_sql.=" AND REG_ANO=".$variable['ano'];
                $cadena_sql.=" AND REG_PER=".$variable['periodo'];
                break;
  
            case 'datos_facultades':
                $cadena_sql="SELECT DISTINCT cra_dep_cod COD_FACULTAD,";
                $cadena_sql.=" dep_nombre NOMBRE_FACULTAD";
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" INNER JOIN gedep ON cra_dep_cod=dep_cod";
                $cadena_sql.=" INNER JOIN actipcra ON cra_tip_cra=tra_cod";
                $cadena_sql.=" WHERE tra_nivel ='PREGRADO'";
                break;

            case 'datos_proyectos':
                $cadena_sql="SELECT DISTINCT cra_cod COD_PROYECTO,";
                $cadena_sql.=" cra_nombre NOMBRE_PROYECTO";
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" INNER JOIN actipcra ON cra_tip_cra=tra_cod";
                $cadena_sql.=" WHERE cra_dep_cod =".$variable;
                $cadena_sql.=" AND tra_nivel ='PREGRADO'";
                break;
            
            default :
              $cadena_sql='';
              break;
        }
        return $cadena_sql;

    }
}
?>