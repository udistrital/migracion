<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminEventoConsultarAdmon extends sql {
    function cadena_sql($configuracion,$conexion, $opcion,$variable="") {

        switch($opcion) {

            case "listarEventos":

                $cadena_sql="SELECT ";
                $cadena_sql.="id_evento, evento_nombre, evento_descripcion ";
                $cadena_sql.="FROM ".$configuracion["prefijo"];
                $cadena_sql.="calendario_evento ";
                $cadena_sql.="WHERE evento_estado=1";
                break;
            
        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>