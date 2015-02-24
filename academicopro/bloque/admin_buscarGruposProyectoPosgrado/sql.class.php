<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

/*
 * @ Clase que contruye cadena para busquedas de grupos de espacios academicos en los proyectos curriculares
 */
class sql_adminBuscarGruposProyectoPosgrado extends sql {
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {

            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;

            case 'grupos_proyecto':

                $cadena_sql="SELECT DISTINCT hor_nro GRUPO,";
                $cadena_sql.=" cur_cra_cod CARRERA";
                $cadena_sql.=" FROM achorario_2012";
                $cadena_sql.=" INNER JOIN accurso ON achorario_2012.hor_asi_cod=accurso.cur_asi_cod AND achorario_2012.hor_nro=accurso.cur_nro";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND cur_cra_cod".$variable['parametro'].$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND hor_nro!=".$variable['grupo'];
                $cadena_sql.=" AND hor_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND hor_ape_per=".$variable['periodo'];
                $cadena_sql.=" ORDER BY 1"; //echo $cadena_sql;
                break;

            case 'horario_grupos':

                $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO DIA, HOR_HORA HORA, SED_ID COD_SEDE, SALON.SAL_EDIFICIO ID_EDIFICIO,EDI.EDI_NOMBRE NOM_EDIFICIO,HOR_SAL_ID_ESPACIO ID_SALON,SALON.SAL_NOMBRE NOM_SALON ";
                $cadena_sql.=" FROM achorario_2012 horario";
                $cadena_sql.=" INNER JOIN accurso ON horario.hor_asi_cod=accurso.cur_asi_cod AND horario.hor_nro=accurso.cur_nro AND horario.hor_ape_ano=accurso.cur_ape_ano AND horario.hor_ape_per=accurso.cur_ape_per ";
                $cadena_sql.=" INNER JOIN GESALON_2012 SALON ON HOR_SAL_ID_ESPACIO=SAL_ID_ESPACIO";
                $cadena_sql.=" INNER JOIN GESEDE SEDE ON horario.HOR_SED_COD=SEDE.SED_COD";
                $cadena_sql.=" INNER JOIN GEEDIFICIO EDI ON SALON.SAL_EDIFICIO=EDI.EDI_COD";
                $cadena_sql.=" WHERE CUR_ASI_COD=".$variable['codEspacio'];
                $cadena_sql.=" AND CUR_CRA_COD=".$variable['carrera'];
                $cadena_sql.=" AND HOR_APE_ANO=".$variable['ano'];
                $cadena_sql.=" AND HOR_APE_PER=".$variable['periodo'];
                $cadena_sql.=" AND HOR_NRO=".$variable['grupo'];
                $cadena_sql.=" ORDER BY 1,2,3"; 
                
                break;

            case 'nombre_carrera':
              
                $cadena_sql="SELECT cra_nombre NOMBRE";
                $cadena_sql.=" FROM ACCRA";
                $cadena_sql.=" WHERE cra_cod=".$variable;
                break;

            case "consultaEstudiante":
                $cadena_sql = "SELECT est_cod CODIGO,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" est_estado_est LETRA_ESTADO,";
                $cadena_sql.=" estado_nombre ESTADO,";
                $cadena_sql.=" est_cra_cod CODIGO_CARRERA,";
                $cadena_sql.=" cra_nombre NOMBRE_CARRERA,";
                $cadena_sql.=" est_pen_nro PLAN_ESTUDIO,";
                $cadena_sql.=" est_ind_cred INDICA_CREDITOS";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN acestado ON estado_cod= est_estado_est";
                $cadena_sql.=" INNER JOIN accra ON cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_cod=" . $variable;
                break;

        }
        return $cadena_sql;
    }


}
?>