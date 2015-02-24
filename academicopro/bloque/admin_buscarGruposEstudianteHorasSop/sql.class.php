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
class sql_adminBuscarGruposEstudianteHorasSop extends sql {
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

                $cadena_sql=" SELECT DISTINCT hor_nro GRUPO,";
                $cadena_sql.=" cur_cra_cod CARRERA,";
                $cadena_sql.=" cur_nro_cupo CUPO,";
                $cadena_sql.=" cur_nro_ins INSCRITOS,";
                $cadena_sql.=" hor_dia_nro DIA,";
                $cadena_sql.=" hor_hora HORA,";
                $cadena_sql.=" sed_abrev SEDE,";
                $cadena_sql.=" hor_sal_cod SALON";
                $cadena_sql.=" FROM achorario";
                $cadena_sql.=" INNER JOIN accurso ON achorario.hor_asi_cod=accurso.cur_asi_cod";
                $cadena_sql.=" AND achorario.hor_nro=accurso.cur_nro";
                $cadena_sql.=" AND accurso.cur_ape_ano=achorario.hor_ape_ano";
                $cadena_sql.=" AND accurso.cur_ape_per= achorario.hor_ape_per";
                $cadena_sql.=" INNER JOIN gesede ON achorario.hor_sed_cod=gesede.sed_cod";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND cur_cra_cod".$variable['parametro'].$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND hor_nro!=".$variable['grupo'];
                $cadena_sql.=" AND hor_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND hor_ape_per=".$variable['periodo'];
                $cadena_sql.=" ORDER BY  GRUPO,DIA,HORA,SEDE";
                break;

            case 'nombre_carrera':
              
                $cadena_sql="SELECT cra_nombre NOMBRE";
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" WHERE cra_cod=".$variable;
                break;

        }
        return $cadena_sql;
    }


}
?>