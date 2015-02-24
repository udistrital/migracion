<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroEventoCrearAdmon extends sql {
    function cadena_sql($configuracion,$conexion, $opcion,$variable="") {

        switch($opcion) {

            case 'crearEvento':
         
               $cadena_sql="INSERT INTO ".$configuracion['prefijo']."calendario_evento ";
               $cadena_sql.="(id_evento, evento_nombre, evento_descripcion, evento_estado) ";
               $cadena_sql.="VALUES ('', '".$variable[0]."', '".$variable[1]."', '1')";
               //echo $cadena_sql;
               //exit;

               break;

           case 'crearEventoExiste':

               $cadena_sql="INSERT INTO ".$configuracion['prefijo']."calendario_evento ";
               $cadena_sql.="(id_evento, evento_nombre, evento_descripcion, evento_estado) ";
               $cadena_sql.="VALUES ('', '".$variable[0]."', '".$variable[1]."', '1')";
               //echo $cadena_sql;
               //exit;

               break;

           case 'crearEventoCien':

               $cadena_sql="INSERT INTO ".$configuracion['prefijo']."calendario_evento ";
               $cadena_sql.="(id_evento, evento_nombre, evento_descripcion, evento_estado) ";
               $cadena_sql.="VALUES ('100', '".$variable[0]."', '".$variable[1]."', '1')";
               //echo $cadena_sql;
               //exit;

               break;

           case 'buscarEvento':
               $cadena_sql="SELECT id_evento, evento_nombre, evento_descripcion ";
               $cadena_sql.="FROM ".$configuracion['prefijo']."calendario_evento ";
               $cadena_sql.="WHERE evento_nombre ='".$variable[0]."'";
               $cadena_sql.=" AND evento_descripcion ='".$variable[1]."'";

               break;

           case 'buscarEventoMayor':
               $cadena_sql="SELECT id_evento ";
               $cadena_sql.="FROM ".$configuracion['prefijo']."calendario_evento ";
               $cadena_sql.="ORDER BY id_evento desc limit 0, 1";
              // $cadena_sql.="WHERE evento_nombre ='".$variable[0]."'";
              // $cadena_sql.=" AND evento_descripcion ='".$variable[1]."'";

               break;

               //select * from acdeseventos where acd_descripcion=

            case 'buscarEventoOracle':
               $cadena_sql="SELECT acd_cod_evento, acd_descripcion ";
               $cadena_sql.="FROM acdeseventos ";
               $cadena_sql.="WHERE acd_descripcion ='".$variable."'";
              // $cadena_sql.=" AND evento_descripcion ='".$variable[1]."'";

               break;

            case 'buscarEventoNombre':
               $cadena_sql="SELECT id_evento, evento_nombre, evento_descripcion ";
               $cadena_sql.="FROM ".$configuracion['prefijo']."calendario_evento ";
               $cadena_sql.="WHERE evento_nombre ='".$variable."'";
               //$cadena_sql.=" AND evento_descripcion ='".$variable[1]."'";

               break;

            case 'registroLogEvento':

                $cadena_sql="INSERT INTO ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES('', '".$variable[0]."', ";
                $cadena_sql.="'".$variable[1]."', ";
                $cadena_sql.="'25', ";
                $cadena_sql.="'Creo evento para el calendario académico', ";
                $cadena_sql.="'".$variable[2].", ".$variable[3].", ".$variable[4]."',";
                $cadena_sql.="'".$variable[2]."')";

                break;
            
        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>