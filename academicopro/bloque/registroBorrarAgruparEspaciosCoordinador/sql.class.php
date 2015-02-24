<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroBorrarAgruparEspaciosCoordinador extends sql {
    function cadena_sql($configuracion,$conexion, $opcion,$variable="") {

        switch($opcion) {

            case 'buscarEspacioAsociados':
               $cadena_sql="SELECT ".$configuracion['prefijo']."espacioEncabezado.id_espacio, espacio_nombre FROM ".$configuracion['prefijo']."espacioEncabezado ";
               $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico ON ".$configuracion['prefijo'];
               $cadena_sql.="espacio_academico.id_espacio = ".$configuracion['prefijo'];
               $cadena_sql.="espacioEncabezado.id_espacio WHERE ";
               $cadena_sql.="id_encabezado=".$variable;
               $cadena_sql.=" and ".$configuracion['prefijo']."espacioEncabezado.id_estado=1";

               break;

             case "bimestreActual":
                $cadena_sql="select ape_ano, ape_per " ;
                $cadena_sql.="from acasperi ";
                $cadena_sql.="where ape_estado like '%A%'";
               
                break;

             case 'modificarEstadoEncabezado':

               $cadena_sql="UPDATE ".$configuracion['prefijo']."encabezado ";
               $cadena_sql.="SET id_estado='0' ";
               $cadena_sql.="WHERE sga_encabezado.id_encabezado =".$variable;

               break;

            case 'registroBorrarEncabezado':

                $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES(0, '".$variable[0]."', ";
                $cadena_sql.="'".$variable[1]."', ";
                $cadena_sql.="'22', ";
                $cadena_sql.="'Borrar Nombre General', ";
                $cadena_sql.="'".$variable[2]."-".$variable[3].",";
                $cadena_sql.=" ".$variable[7]."-".$variable[4].", ".$variable[5].", ".$variable[6]."', ";
                $cadena_sql.="'".$variable[5]."')";

                break;      

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>