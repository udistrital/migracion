<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminEventoConsultarFechaAdmon extends sql {
    function cadena_sql($configuracion,$conexion, $opcion,$variable="") {

        switch($opcion) {

            case "listarFechas":

                $cadena_sql="SELECT evento_nombre, evento_descripcion, fecha_inicio, fecha_fin, date_format( fecha_inicio, '%Y/%m/%d %l:%i%p' ) , ";
                $cadena_sql.="date_format( fecha_fin, '%Y/%m/%d %l:%i%p' ) , CFE.id_cobertura_evento, id_usuario_afectado, CFE.id_fecha_evento ";
                $cadena_sql.="FROM ".$configuracion["prefijo"];
                $cadena_sql.="calendario_fecha_evento CFE ";
                $cadena_sql.="INNER JOIN sga_calendario_evento CE ON CFE.id_evento = CE.id_evento ";
                $cadena_sql.="INNER JOIN sga_calendario_usuario_afectado CUA ON CFE.id_fecha_evento = CUA.id_fecha_evento ";
                $cadena_sql.="WHERE fecha_ano =".$variable[0];
                $cadena_sql.=" AND fecha_periodo =".$variable[1];
                $cadena_sql.=" AND fecha_estado =1";
                $cadena_sql.=" ORDER BY CFE.id_cobertura_evento, id_usuario_afectado, fecha_inicio";
                break;

            case "buscarCobertura":

                $cadena_sql="SELECT cobertura_evento_descripcion FROM ".$configuracion["prefijo"]."calendario_cobertura_evento ";
                $cadena_sql.="WHERE id_cobertura_evento=".$variable;

                break;

            case "buscarFacultad":

                $cadena_sql="SELECT nombre_facultad FROM ".$configuracion["prefijo"]."facultad ";
                $cadena_sql.="WHERE id_facultad =".$variable ;

                break;

            case "buscarProyecto":

                $cadena_sql="SELECT id_proyectoAcademica, proyecto_nombre FROM ".$configuracion["prefijo"]."proyectoCurricular ";
                $cadena_sql.="WHERE id_proyectoAcademica =".$variable ;

                break;

            case "buscarPlanEstudio":

                $cadena_sql="SELECT id_planEstudio, planEstudio_nombre FROM ".$configuracion["prefijo"]."planEstudio ";
                $cadena_sql.="WHERE id_planEstudio =".$variable ;

                break;

            case "periodosAcademicos":

                $cadena_sql="SELECT * FROM ACASPERI ";
                $cadena_sql.="WHERE ape_estado != 'I' ";
                $cadena_sql.="ORDER BY ape_ano, ape_per ";

                break;
            
        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>