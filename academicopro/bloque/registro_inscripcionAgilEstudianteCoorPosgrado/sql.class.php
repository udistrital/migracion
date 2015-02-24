<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroInscripcionAgilEstudianteCoorPosgrado extends sql {
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
  }

    function cadena_sql($opcion,$variable="") {

        switch($opcion) {

            
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;

              case 'nombreEspacio':
                $cadena_sql="SELECT asi_nombre NOMBREESPACIO";
                $cadena_sql.=" FROM acasi";
                $cadena_sql.=" WHERE asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND asi_estado LIKE '%A%'";
                break;


            case 'nombre_carrera':

                $cadena_sql="SELECT cra_nombre NOMBRE";
                $cadena_sql.=" FROM ACCRA";
                $cadena_sql.=" WHERE cra_cod=".$variable;
                break;


        }

        return $cadena_sql;
    }


}
?>