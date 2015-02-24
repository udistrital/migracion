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
class sql_adminBuscarGruposProyectoCI extends sql {
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {

            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%V%'";
                break;

            case 'grupos_proyecto':

                $cadena_sql="SELECT DISTINCT cur_id ID_GRUPO,";
                $cadena_sql.=" cur_cra_cod CARRERA,";
                $cadena_sql.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo) GRUPO";
                $cadena_sql.=" FROM achorarios";
                $cadena_sql.=" INNER JOIN ACCURSOS ON achorarios.HOR_ID_CURSO=ACCURSOS.CUR_ID";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND cur_cra_cod".$variable['parametro'].$variable['codProyecto'];
                $cadena_sql.=" AND cur_id!=".$variable['idGrupo'];
                $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                $cadena_sql.=" ORDER BY 1";
                break;

            case 'horario_grupos':

                $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO DIA, HOR_HORA HORA, SED_ID COD_SEDE, SALON.SAL_EDIFICIO ID_EDIFICIO,EDI.EDI_NOMBRE NOM_EDIFICIO,HOR_SAL_ID_ESPACIO ID_SALON,SALON.SAL_NOMBRE NOM_SALON,cur_nro_cupo CUPO ";
                $cadena_sql.=" FROM achorarios";
                $cadena_sql.=" INNER JOIN ACCURSOS ON achorarios.HOR_ID_CURSO=ACCURSOS.CUR_ID";
                $cadena_sql.=" INNER JOIN GESALONES SALON ON achorarios.HOR_SAL_ID_ESPACIO=SALON.SAL_ID_ESPACIO";
                $cadena_sql.=" INNER JOIN GESEDE  SEDE ON SAL_SED_COD=SEDE.SED_COD";
                $cadena_sql.=" INNER JOIN GEEDIFICIO EDI ON SALON.SAL_EDIFICIO=EDI.EDI_COD";
                $cadena_sql.=" WHERE CUR_ASI_COD=".$variable['codEspacio']; //codigo del espacio
                $cadena_sql.=" AND CUR_CRA_COD=".$variable['carrera'];
                $cadena_sql.=" AND CUR_APE_ANO=" . $variable['ano'];
                $cadena_sql.=" AND CUR_APE_PER=" . $variable['periodo'];
                $cadena_sql.=" AND cur_id=" . $variable['idGrupo']; //numero de grupo
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