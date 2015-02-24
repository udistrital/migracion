<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminBuscarEspacioInscripcionesEstudiante extends sql {

  function cadena_sql($opcion, $variable="") {

    switch ($opcion) {

      case 'periodoActivo':
        $cadena_sql = "SELECT ape_ano ANO,";
        $cadena_sql.=" ape_per PERIODO";
        $cadena_sql.=" FROM acasperi";
        $cadena_sql.=" WHERE ape_estado like '%A%'";
        break;


        case 'espacios_equivalentes':
        $cadena_sql= "SELECT hom_asi_cod_ant ASI_COD_ANTERIOR,";
        $cadena_sql.= " hom_asi_cod_ant2 ASI_COD_ANTERIOR2,";
        $cadena_sql.= " hom_asi_cod_nue CODIGO,";
        $cadena_sql.= " asi_nombre NOMBRE";
        $cadena_sql.= " FROM achomcra";
        $cadena_sql.= " INNER JOIN acasi ON hom_asi_cod_nue=asi_cod";
        $cadena_sql.= " INNER JOIN acpen ON pen_asi_cod=hom_asi_cod_nue and pen_cra_cod=hom_cra_cod_nue";
        $cadena_sql.= " WHERE hom_cra_cod_ant=".$variable['codProyectoEstudiante'];
        $cadena_sql.= " AND hom_cra_cod_nue=".$variable['codProyectoEstudiante'];
        //$cadena_sql.= " AND hom_asi_cod_ant=".$variable['codEspacio'];
        $cadena_sql.= " AND hom_estado LIKE '%A%'";
        $cadena_sql.= " AND pen_estado LIKE '%A%'";
        break;
    

    

    
     case 'espacios_inscritos':
        $cadena_sql="SELECT ins_asi_cod CODIGO ";
        $cadena_sql.=" FROM acins ";
        $cadena_sql.=" WHERE ins_est_cod = " . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano = " . $variable['ano'];
        $cadena_sql.=" AND ins_per = " . $variable['periodo'];
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
        //echo $cadena_sql; exit;
        break;
     }
    return $cadena_sql;
  }

}

?>