<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminBuscarEspacioEstudianteCoorHoras extends sql {

  function cadena_sql($opcion, $variable="") {

    switch ($opcion) {

      case 'periodoActivo':

        $cadena_sql = "SELECT ape_ano ANO,";
        $cadena_sql.=" ape_per PERIODO";
        $cadena_sql.=" FROM acasperi";
        $cadena_sql.=" WHERE";
        $cadena_sql.=" ape_estado LIKE '%A%'";
        break;

      case 'nota_aprobatoria':
        $cadena_sql="SELECT fua_nota_aprobatoria(" . $variable['codProyectoEstudiante'] . ")";
        $cadena_sql.=" FROM dual";
        break;

      case 'espacios_aprobados':
        $cadena_sql="SELECT not_asi_cod NOT_ASI_COD";
        $cadena_sql.=" FROM acnot";
        $cadena_sql.=" WHERE not_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND (not_nota>=".$variable['nota'];
        $cadena_sql.=" OR not_obs=19)";
        $cadena_sql.=" AND not_est_reg like '%A%'";
        break;

      case 'espacios_inscritos':
        $cadena_sql="SELECT ins_asi_cod INS_ASI_COD FROM acins ";
        $cadena_sql.=" WHERE ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano=" . $variable['ano'];
        $cadena_sql.=" AND ins_per=" . $variable['periodo'];
        break;
      
      case 'espacios_plan_estudio':

        $cadena_sql = "SELECT DISTINCT";
        $cadena_sql.=" pen_asi_cod ASICOD,";
        $cadena_sql.=" asi_nombre NOMBRE,";
        $cadena_sql.=" pen_sem NIVEL,";
        $cadena_sql.=" pen_ind_ele ELECTIVA,";
        $cadena_sql.=" pen_cre CREDITOS";
        $cadena_sql.=" FROM acpen";
        $cadena_sql.=" INNER JOIN acasi ON acpen.pen_asi_cod=acasi.asi_cod";
        $cadena_sql.=" WHERE pen_nro=" . $variable['planEstudioEstudiante'];
        $cadena_sql.=" AND pen_cra_cod=" . $variable['codProyectoEstudiante'];
        $cadena_sql.=" AND pen_estado like '%A%'";
        $cadena_sql.=" AND pen_asi_cod NOT IN";
        $cadena_sql.=" (".$variable['aprobados'].")";
        $cadena_sql.=" AND pen_asi_cod NOT IN";
        $cadena_sql.=" (".$variable['inscritos'].")";
        $cadena_sql.=" ORDER BY pen_sem, pen_asi_cod";
        break;

      case 'espacios_plan_estudios':

        $cadena_sql = "SELECT DISTINCT";
        $cadena_sql.=" pen_asi_cod ASICOD,";
        $cadena_sql.=" asi_nombre NOMBRE,";
        $cadena_sql.=" pen_sem NIVEL,";
        $cadena_sql.=" pen_ind_ele ELECTIVA,";
        $cadena_sql.=" pen_cre CREDITOS";
        $cadena_sql.=" FROM acpen";
        $cadena_sql.=" INNER JOIN acasi ON acpen.pen_asi_cod=acasi.asi_cod";
        $cadena_sql.=" WHERE pen_nro=" . $variable['planEstudioEstudiante'];
        $cadena_sql.=" AND pen_cra_cod=" . $variable['codProyectoEstudiante'];
        $cadena_sql.=" AND pen_estado like '%A%'";
        $cadena_sql.=" AND pen_asi_cod NOT IN";
        $cadena_sql.=" (SELECT not_asi_cod FROM acnot";
        $cadena_sql.=" WHERE not_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND (not_nota>=(SELECT fua_nota_aprobatoria(".$variable['codProyecto'].") FROM dual)";
        $cadena_sql.=" OR not_obs=19)";
//                    $cadena_sql.=" AND not_nota>=(SELECT fua_nota_aprobatoria(".$variable['codProyecto'].") FROM dual)";
        $cadena_sql.=" AND not_est_reg like '%A%')";
        $cadena_sql.=" AND pen_asi_cod NOT IN";
        $cadena_sql.=" (SELECT ins_asi_cod FROM acins ";
        $cadena_sql.=" WHERE ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano=" . $variable['ano'];
        $cadena_sql.=" AND ins_per=" . $variable['periodo'];
        $cadena_sql.=") ORDER BY pen_sem, pen_asi_cod ";
        break;

      case 'espacios_equivalentes':

        $cadena_sql= "SELECT distinct hom_asi_cod_ppal ASI_COD_ANTERIOR,";
        $cadena_sql.= " hom_asi_cod_hom ASICOD,";
        $cadena_sql.= " asi_nombre NOMBRE";
        $cadena_sql.= " FROM actablahomologacion";
        $cadena_sql.= " INNER JOIN acasi ON hom_asi_cod_hom=asi_cod";
        $cadena_sql.= " INNER JOIN acpen ON pen_asi_cod=hom_asi_cod_hom and pen_cra_cod=hom_cra_cod_ppal";
        $cadena_sql.= " WHERE hom_cra_cod_ppal=".$variable['codProyectoEstudiante'];
        $cadena_sql.= " AND hom_estado LIKE '%A%'";
        $cadena_sql.= " AND pen_estado LIKE '%A%'";
        $cadena_sql.= " AND hom_tipo_hom = 0";
        
        break;
    }
    return $cadena_sql;
  }

}

?>