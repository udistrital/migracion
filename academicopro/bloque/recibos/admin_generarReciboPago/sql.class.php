<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminGenerarReciboPago extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

    function __construct($configuracion) {
        $this->configuracion=$configuracion;
    }
  function cadena_sql($tipo, $variable="") {

    switch ($tipo) {

        case 'consultar_periodos':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" (ape_ano ||'-'||ape_per) PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE ape_estado NOT IN ('X')";
                $cadena_sql.=" AND ape_per !=2";
                $cadena_sql.=" ORDER BY  PERIODO DESC";
            break;
            
        case 'consultar_estudiante':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" cra_cod COD_PROYECTO, ";
                $cadena_sql.=" cra_nombre PROYECTO, ";
                $cadena_sql.=" est_estado_est COD_ESTADO";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN accra ON est_cra_cod=cra_cod";
                $cadena_sql.=" WHERE est_estado_est NOT IN ('I','E')";
                $cadena_sql.=" AND est_cod=".$variable;
            break;
            
    
            default :
                $cadena_sql="";
                break;
    }#Cierre de switch

    return $cadena_sql;
  }

#Cierre de funcion cadena_sql
}

#Cierre de clase
?>