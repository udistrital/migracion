<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_admin_buscarEspacioPreinscripcionDemandaEstudiante extends sql {

  function cadena_sql($opcion, $variable="") {

    switch ($opcion) {

      case 'periodoActivo':

        $cadena_sql = "SELECT ape_ano ANO,";
        $cadena_sql.=" ape_per PERIODO";
        $cadena_sql.=" FROM acasperipreinsdemanda";
        $cadena_sql.=" WHERE";
        $cadena_sql.=" ape_estado LIKE '%A%'";
        break;


      case 'carga':
        $cadena_sql =" SELECT ins_est_cod CODIGO,";
        $cadena_sql.=" ins_est_nombre NOMBRE,";
        $cadena_sql.=" ins_est_estado ESTADO,";
        $cadena_sql.=" ins_estado_descripcion ESTADO_DESCRIPCION,";
        $cadena_sql.=" ins_est_pensum PLAN_ESTUDIO,";
        $cadena_sql.=" ins_est_cra_cod COD_CARRERA,";
        $cadena_sql.=" ins_cra_nombre NOMBRE_CARRERA,";
        $cadena_sql.=" ins_fac_cod NOMBRE_FACULTAD,";
        $cadena_sql.=" ins_est_tipo TIPO_ESTUDIANTE,";
        $cadena_sql.=" ins_est_acuerdo ACUERDO,";
        $cadena_sql.=" ins_espacios_por_cursar ESPACIOS_POR_CURSAR,";
        $cadena_sql.=" ins_equivalencias EQUIVALECIAS,";
        $cadena_sql.=" ins_requisitos_no_aprobados REQUISITOS_NO_APROBADOS,";
        $cadena_sql.=" ins_parametros_plan PARAMETROS,";
        $cadena_sql.=" ins_creditos_aprobados CREDITOS_APROBADOS,";
        $cadena_sql.=" ins_espacios_cancelados CANCELADOS,";
        $cadena_sql.=" ins_ano ANO,";
        $cadena_sql.=" ins_periodo PERIODO";
        $cadena_sql.=" FROM sga_carga_inscripciones";
        $cadena_sql.=" WHERE ins_est_cod =".$variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano =".$variable['ano'];
        $cadena_sql.=" AND ins_periodo =".$variable['periodo'];
        break;
    

      case 'consultaPreinscripcionesCanceladasEstudiante':
        $cadena_sql = "SELECT insde_est_cod COD_ESTUDIANTE,";
        $cadena_sql .= " insde_asi_cod ASI_CODIGO";
        $cadena_sql .= " FROM acinsdemanda";
        $cadena_sql .= " WHERE insde_est_cod=".$variable['codEstudiante'];
        $cadena_sql .= " AND insde_ano=".$variable['ano'];
        $cadena_sql .= " AND insde_per=".$variable['periodo'];
        $cadena_sql .= " AND insde_estado LIKE '%I%'";
        $cadena_sql .= " AND insde_cra_cod=".$variable['codProyectoEstudiante'];
        //$cadena_sql .= " AND insde_asi_cod=".$variable['codEspacio'];
        break;

    }
    return $cadena_sql;
  }

}

?>