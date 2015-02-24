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
class sql_adminBuscarGruposProyectoHoras extends sql {
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {

            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per      PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;

            case 'grupos_proyecto':

                $cadena_sql="SELECT DISTINCT ";
                $cadena_sql.=" hor_id_curso     ID_GRUPO,";
                $cadena_sql.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo)  GRUPO,";
                $cadena_sql.=" cur_cra_cod      CARRERA";
                $cadena_sql.=" FROM achorarios horario";
                $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND cur_cra_cod".$variable['parametro'].$variable['codProyecto'];
                $cadena_sql.=" AND hor_id_curso!=".$variable['id_grupo'];
                $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                $cadena_sql.=" ORDER BY 1";
                break;

            
                   
            case 'horario_grupos':

                    $cadena_sql="SELECT DISTINCT";
                    $cadena_sql.=" horario.hor_dia_nro          DIA,";
                    $cadena_sql.=" horario.hor_hora             HORA,";
                    $cadena_sql.=" sede.sed_id                  SEDE,";
                    $cadena_sql.=" horario.hor_sal_id_espacio   ID_SALON,";
                    $cadena_sql.=" salon.sal_nombre             SALON,";
                    $cadena_sql.=" salon.sal_edificio           ID_EDIFICIO,";
                    $cadena_sql.=" edi.edi_nombre               EDIFICIO,";
                    $cadena_sql.=" curso.cur_nro_cupo           CUPO, ";
                    $cadena_sql.=" hor_alternativa              HOR_ALTERNATIVA ";
                    $cadena_sql.=" FROM achorarios horario";
                    $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id ";
                    $cadena_sql.=" LEFT OUTER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
                    $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                    $cadena_sql.=" LEFT OUTER JOIN geedificio edi ON salon.sal_edificio=edi.edi_cod";
                    $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio']; //codigo del espacio
                    $cadena_sql.=" AND cur_cra_cod=".$variable['carrera'];
                    $cadena_sql.=" AND cur_ape_ano=" . $variable['ano'];
                    $cadena_sql.=" AND cur_ape_per=" . $variable['periodo'];
                    $cadena_sql.=" AND hor_id_curso=" . $variable['id_grupo']; //numero de grupo
                    $cadena_sql.=" ORDER BY 1,2,3";
                break;

                               
            case 'nombre_carrera':
              
                $cadena_sql="SELECT cra_nombre NOMBRE";
                $cadena_sql.=" FROM ACCRA";
                $cadena_sql.=" WHERE cra_cod=".$variable;
                break;

            case "consultaEstudiante":
                $cadena_sql = "SELECT est_cod   CODIGO,";
                $cadena_sql.=" est_nombre       NOMBRE,";
                $cadena_sql.=" est_estado_est   LETRA_ESTADO,";
                $cadena_sql.=" estado_nombre    ESTADO,";
                $cadena_sql.=" est_cra_cod      CODIGO_CARRERA,";
                $cadena_sql.=" cra_nombre       NOMBRE_CARRERA,";
                $cadena_sql.=" est_pen_nro      PLAN_ESTUDIO,";
                $cadena_sql.=" est_ind_cred     INDICA_CREDITOS";
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