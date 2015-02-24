<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroEventoBorrarFechaAdmon extends sql {
    function cadena_sql($configuracion,$conexion, $opcion,$variable="") {

        switch($opcion) {

            case "verificarFecha":
                $cadena_sql="SELECT * FROM ".$configuracion["prefijo"];
                $cadena_sql.="calendario_fecha_evento ";
                $cadena_sql.=" WHERE id_evento=".$variable[0];
                $cadena_sql.=" AND fecha_estado!=3";
                
                break;

            case "borrarFecha":
                $cadena_sql="update ".$configuracion["prefijo"];
                $cadena_sql.="calendario_fecha_evento ";
                $cadena_sql.="SET fecha_estado=3";
                $cadena_sql.=" WHERE id_fecha_evento=".$variable[0];
                
                break;

            case "logEventos":
                $cadena_sql="INSERT INTO ".$configuracion["prefijo"];
                $cadena_sql.="log_eventos (log_usuarioProceso, log_fecha, log_evento, log_descripcion, log_registro, log_usuarioAfectado) ";
                $cadena_sql.="VALUES ('".$variable[0]."',";
                $cadena_sql.=" '".$variable[1]."',";
                $cadena_sql.=" '".$variable[2]."',";
                $cadena_sql.=" '".$variable[3]."',";
                $cadena_sql.=" '".$variable[4]."',";
                $cadena_sql.=" '".$variable[5]."')";

                break;

            case "buscarCobertura":

                $cadena_sql="SELECT cobertura_evento_descripcion FROM ".$configuracion["prefijo"]."calendario_cobertura_evento ";
                $cadena_sql.="WHERE id_cobertura_evento=".$variable;

                break;
            
             

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>