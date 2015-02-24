<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroEventoEditarFechaAdmon extends sql {
    function cadena_sql($configuracion,$conexion, $opcion,$variable="") {

        switch($opcion) {

            case "buscarEventosActivos":
                $cadena_sql="SELECT id_evento, evento_nombre, evento_descripcion FROM ".$configuracion["prefijo"];
                $cadena_sql.="calendario_evento ";
                $cadena_sql.=" WHERE evento_estado=1";

                break;

            case "buscarEvento":
                $cadena_sql="SELECT id_evento, evento_nombre, evento_descripcion FROM ".$configuracion["prefijo"];
                $cadena_sql.="calendario_evento ";
                $cadena_sql.=" WHERE evento_estado=1 AND id_evento=".$variable;

                break;

            case "buscarUsuariosAfectados":
                $cadena_sql="SELECT id_cobertura_evento, cobertura_evento_descripcion, cobertura_evento_estado FROM ".$configuracion["prefijo"];
                $cadena_sql.="calendario_cobertura_evento ";
                $cadena_sql.=" WHERE cobertura_evento_estado=1";

                break;

            case "periodosAcademicos":
                $cadena_sql="select ape_ano, ape_per from acasperi ";
                $cadena_sql.="where ape_estado!='I' ";
                $cadena_sql.="and ape_estado!='P' ";
                $cadena_sql.="ORDER BY ape_estado";

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

            case "logEventosEdito":
                $cadena_sql="INSERT INTO ".$configuracion["prefijo"];
                $cadena_sql.="log_eventos (log_usuarioProceso, log_fecha, log_evento, log_descripcion, log_registro, log_usuarioAfectado) ";
                $cadena_sql.="VALUES ('".$variable[0]."',";
                $cadena_sql.=" '".$variable[1]."',";
                $cadena_sql.=" '".$variable[2]."',";
                $cadena_sql.=" '".$variable[3]."',";
                $cadena_sql.=" '".$variable[4]."',";
                $cadena_sql.=" '".$variable[5]."')";

                break;            

            case "buscarIDFecha":
                $cadena_sql="SELECT id_fecha_evento FROM ".$configuracion["prefijo"];
                $cadena_sql.="calendario_fecha_evento ";
                $cadena_sql.="WHERE  fecha_ano='".$variable[0]."' AND ";
                $cadena_sql.=" fecha_periodo='".$variable[1]."' AND ";
                $cadena_sql.=" id_evento='".$variable[2]."' AND ";
                $cadena_sql.=" fecha_inicio='".$variable[3]."' AND ";
                $cadena_sql.=" fecha_fin='".$variable[4]."' AND ";
                $cadena_sql.=" id_cobertura_evento='".$variable[5]."'";

                break;

             case "buscarUnaFecha":
                $cadena_sql="SELECT fecha_inicio, fecha_fin, id_evento FROM ".$configuracion["prefijo"];
                $cadena_sql.="calendario_fecha_evento ";
                $cadena_sql.="WHERE id_fecha_evento=".$variable;
                $cadena_sql.=" AND fecha_estado=1";

                break;

            case "borrarFecha":
                $cadena_sql="UPDATE ".$configuracion["prefijo"];
                $cadena_sql.="calendario_fecha_evento SET fecha_estado=".$variable[6];
                $cadena_sql.="WHERE  fecha_ano='".$variable[0]."' AND ";
                $cadena_sql.=" fecha_periodo='".$variable[1]."' AND ";
                $cadena_sql.=" id_evento='".$variable[2]."' AND ";
                $cadena_sql.=" fecha_inicio='".$variable[3]."' AND ";
                $cadena_sql.=" fecha_fin='".$variable[4]."' AND ";
                $cadena_sql.=" id_cobertura_evento='".$variable[5]."'";
                
                break;

            case "insertarUsuario":
                $cadena_sql="INSERT INTO ".$configuracion["prefijo"];
                $cadena_sql.="calendario_usuario_afectado (id_fecha_evento,id_cobertura_evento, id_usuario_afectado,usuario_afectado_descripcion) ";
                $cadena_sql.="VALUES ('".$variable[0]."',";
                $cadena_sql.=" '".$variable[1]."',";
                $cadena_sql.=" '".$variable[2]."',";
                $cadena_sql.=" '".$variable[3]."')";

                break;

            case "insertarFecha":
                $cadena_sql="INSERT INTO ".$configuracion["prefijo"];
                $cadena_sql.="calendario_fecha_evento (fecha_ano,fecha_periodo,id_evento,fecha_inicio, fecha_fin, id_cobertura_evento, fecha_estado) ";
                $cadena_sql.="VALUES ('".$variable[0]."',";
                $cadena_sql.=" '".$variable[1]."',";
                $cadena_sql.=" '".$variable[2]."',";
                $cadena_sql.=" '".$variable[3]."',";
                $cadena_sql.=" '".$variable[4]."',";
                $cadena_sql.=" '".$variable[5]."',";
                $cadena_sql.=" '".$variable[6]."')";

                break;
            
            case "modificarFecha":
                $cadena_sql="UPDATE ".$configuracion["prefijo"];
                $cadena_sql.="calendario_fecha_evento ";
                $cadena_sql.="SET fecha_estado = '2' ";
                $cadena_sql.="WHERE id_fecha_evento = '".$variable."'";

                break;

            case "buscarUsuario":
                $cadena_sql="SELECT id_cobertura_evento, id_usuario_afectado, usuario_afectado_descripcion FROM ".$configuracion["prefijo"];
                $cadena_sql.="calendario_usuario_afectado ";
                $cadena_sql.="WHERE id_fecha_evento=".$variable;

                break;
             

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>