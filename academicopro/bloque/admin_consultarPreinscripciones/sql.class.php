<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_admin_consultarPreinscripcionDemandaEstudiante extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

  function cadena_sql($tipo, $variable="") {

    switch ($tipo) {

      #consulta de datos del estudiante
      case 'periodoPreinscripciones':

        $cadena_sql = "SELECT ape_ano ANO,";
        $cadena_sql.=" ape_per PERIODO";
        $cadena_sql.=" FROM acasperipreinsdemanda";
        $cadena_sql.=" WHERE ape_estado like '%A%'";
        break;

      case "consultarEstudiantesPreinscritos":
        $cadena_sql = "SELECT count(distinct insde_est_cod)";
        $cadena_sql.=" FROM acinsdemanda";
        break;

      case 'consultarPreinscripciones':

        $cadena_sql = "SELECT count(*)";
        $cadena_sql .= " FROM acinsdemanda";
        break;

   }#Cierre de switch

    return $cadena_sql;
  }

#Cierre de funcion cadena_sql
}

#Cierre de clase
?>