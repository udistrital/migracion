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
class sql_adminBuscarGruposEstudianteCreditosSop extends sql {
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

                $cadena_sql=" SELECT DISTINCT (lpad(curso.cur_cra_cod::text,3,'0')||'-'||curso.cur_grupo) GRUPO, ";
                $cadena_sql.=" curso.cur_cra_cod CARRERA, ";
                $cadena_sql.=" curso.cur_nro_cupo CUPO, ";
                $cadena_sql.=" curso.cur_nro_ins INSCRITOS, ";
                $cadena_sql.=" horario.hor_dia_nro DIA, ";
                $cadena_sql.=" horario.hor_hora HORA, ";
                $cadena_sql.=" sede.sed_id SEDE, ";
                $cadena_sql.=" salon.sal_nombre SALON, ";
                $cadena_sql.=" curso.cur_id ID_GRUPO ";
                $cadena_sql.=" FROM achorarios horario";
                $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id";
                $cadena_sql.=" LEFT OUTER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
                $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id";
                $cadena_sql.=" LEFT OUTER JOIN geedificio edi ON salon.sal_edificio=edi.edi_cod";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND cur_cra_cod".$variable['parametro'].$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND cur_grupo!=".$variable['grupo'];
                $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                $cadena_sql.=" ORDER BY GRUPO,DIA,HORA,SEDE";
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