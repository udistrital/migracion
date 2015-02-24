<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAceptarTerminosMatricula extends sql {
  private $configuracion;
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
  }
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {
            
            
            case 'registrarAceptacion':
                    $cadena_sql="INSERT INTO ".$this->configuracion['prefijo']."acepta_condiciones ";
                    $cadena_sql.=" (con_anio,
                                    con_per,
                                    con_codigo_usuario,
                                    con_tpc_id,
                                    con_usutipo_cod,
                                    con_aceptacion,
                                    con_fecha,
                                    con_estado ) ";
                    $cadena_sql.="VALUES ('".$variable['anio']."',";
                    $cadena_sql.="'".$variable['periodo']."',";
                    $cadena_sql.="'".$variable['usuario']."',";
                    $cadena_sql.="'".$variable['tipo_terminos']."',";
                    $cadena_sql.="'".$variable['tipo_usuario']."',";
                    $cadena_sql.="'".$variable['aceptacion']."',";
                    $cadena_sql.="'".$variable['fecha']."',";
                    $cadena_sql.="'".$variable['estado']."')"; 
                
                break;
            
            case 'terminosYCondiciones':
                    $cadena_sql="SELECT tpc_id,";
                    $cadena_sql.=" tpc_nombre,";
                    $cadena_sql.=" tpc_descripcion,";
                    $cadena_sql.=" tpc_contenido,";
                    $cadena_sql.=" tpc_estado";
                    $cadena_sql.=" FROM  ".$this->configuracion['prefijo']."terminos_y_condiciones";
                    $cadena_sql.=" WHERE tpc_id='".$variable."'";
                
                break;
            
            case 'AceptacionDeTerminos':
                    $cadena_sql="SELECT ";
                    $cadena_sql.=" con_anio,";
                    $cadena_sql.=" con_per,";
                    $cadena_sql.=" con_codigo_usuario,";
                    $cadena_sql.=" con_tpc_id,";
                    $cadena_sql.=" con_usutipo_cod,";
                    $cadena_sql.=" con_aceptacion,";
                    $cadena_sql.=" con_fecha,";
                    $cadena_sql.=" con_estado";
                    $cadena_sql.=" FROM  ".$this->configuracion['prefijo']."acepta_condiciones";
                    $cadena_sql.=" WHERE con_anio='".$variable['anio']."'";
                    $cadena_sql.=" AND con_per='".$variable['periodo']."'";
                    $cadena_sql.=" AND con_codigo_usuario='".$variable['usuario']."'";
                    $cadena_sql.=" AND con_usutipo_cod='".$variable['tipo_usuario']."'";
                    $cadena_sql.=" AND con_tpc_id='".$variable['tipo_terminos']."'";
                
                break;
            
         
        }
        //echo $cadena_sql;exit;
        return $cadena_sql;
    }


}
?>