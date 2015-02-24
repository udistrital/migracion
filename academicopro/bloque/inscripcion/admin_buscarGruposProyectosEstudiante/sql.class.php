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
class sql_adminBuscarGruposProyectosEstudiante extends sql {
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

                $cadena_sql=" SELECT DISTINCT lpad(cur_cra_cod,3,0)||'-'||cur_grupo GRUPO,";
                $cadena_sql.=" cur_id ID_GRUPO,";
                $cadena_sql.=" cur_cra_cod CARRERA,";
                $cadena_sql.=" cur_nro_cupo CUPO,";
                $cadena_sql.=" cur_nro_ins INSCRITOS";
                $cadena_sql.=" FROM accursos";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                //$cadena_sql.=" AND cur_cra_cod".$variable['parametro'].$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND cur_id!=".$variable['id_grupo'];
                $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                $cadena_sql.=" ORDER BY CARRERA,GRUPO";
                break;

            case 'horarios_proyecto':
                $cadena_sql=" SELECT DISTINCT hor_id_curso ID_GRUPO,";
                $cadena_sql.=" hor_dia_nro DIA,";
                $cadena_sql.=" hor_hora HORA,";
                $cadena_sql.=" hor_sede SEDE,";
                $cadena_sql.=" hor_edificio EDIFICIO,";
                $cadena_sql.=" hor_salon SALON,";
                $cadena_sql.=" hor_alternativa HOR_ALTERNATIVA";
                $cadena_sql.=" FROM sga_achorarios";
                $cadena_sql.=" WHERE ".$variable;
                $cadena_sql.=" ORDER BY ID_GRUPO,DIA,HORA,SEDE";
                break;

            case 'nombre_carrera':
              
                $cadena_sql="SELECT cra_nombre NOMBRE";
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" WHERE cra_cod=".$variable;
                break;

            case 'carga':
                $cadena_sql =" SELECT ins_est_cod CODIGO,";
                $cadena_sql.=" ins_est_nombre NOMBRE,";
                $cadena_sql.=" ins_est_estado ESTADO,";
                $cadena_sql.=" ins_estado_descripcion ESTADO_DESCRIPCION,";
                $cadena_sql.=" ins_est_pensum PLAN_ESTUDIO,";
                $cadena_sql.=" ins_est_cra_cod COD_CARRERA,";
                $cadena_sql.=" ins_cra_nombre NOMBRE_CARRERA,";
                $cadena_sql.=" ins_fac_cod NOMBRE_FACULTAD,";
                $cadena_sql.=" ins_est_tipo TIPO_ESTUDIANTE,";
                $cadena_sql.=" ins_est_acuerdo ACUERDO,";
                $cadena_sql.=" ins_espacios_por_cursar ESPACIOS_POR_CURSAR,";
                $cadena_sql.=" ins_equivalencias EQUIVALECIAS,";
                $cadena_sql.=" ins_requisitos_no_aprobados REQUISITOS_NO_APROBADOS,";
                $cadena_sql.=" ins_parametros_plan PARAMETROS,";
                $cadena_sql.=" ins_creditos_aprobados CREDITOS_APROBADOS,";
                $cadena_sql.=" ins_ano ANO,";
                $cadena_sql.=" ins_periodo PERIODO";
                $cadena_sql.=" FROM sga_carga_inscripciones";
                $cadena_sql.=" WHERE ins_est_cod =".$variable['codEstudiante']; 
                $cadena_sql.=" AND ins_ano =".$variable['ano'];
                $cadena_sql.=" AND ins_periodo =".$variable['periodo'];
                //echo $cadena_sql; exit;
                break;
            
        }
        return $cadena_sql;
    }


}
?>