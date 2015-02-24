<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminBuscarEspacioEstudianteHorasSop extends sql {

  function cadena_sql($opcion, $variable="") {

    switch ($opcion) {

      case 'periodoActivo':
        $cadena_sql = "SELECT ape_ano ANO,";
        $cadena_sql.=" ape_per PERIODO";
        $cadena_sql.=" FROM acasperi";
        $cadena_sql.=" WHERE";
        $cadena_sql.=" ape_estado LIKE '%A%'";
        break;

//      case 'espacios_plan_estudio':
//        $cadena_sql=" SELECT DISTINCT emi_asi_cod CODIGO,";
//        $cadena_sql.=" emi_asi_nombre NOMBRE,";
//        $cadena_sql.=" emi_nro_sem NIVEL,";
//        $cadena_sql.=" pen_ind_ele ELECTIVA,";
//        $cadena_sql.=" pen_cre CREDITOS";
//        $cadena_sql.=" FROM v_acestmatins V";
//        $cadena_sql.=" INNER JOIN acpen A ON A.pen_cra_cod=V.emi_cra_cod AND A.pen_asi_cod=V.emi_asi_cod AND A.pen_nro=V.emi_pen_nro";
//        $cadena_sql.=" where emi_est_cod=".$variable['codEstudiante'];
//        $cadena_sql.=" order by emi_nro_sem, emi_asi_cod" ;
//        break;

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
    
     case 'espacios_plan_estudio':
        $cadena_sql = "SELECT DISTINCT";
        $cadena_sql.=" pen_asi_cod CODIGO,";
        $cadena_sql.=" asi_nombre NOMBRE,";
        $cadena_sql.=" pen_sem NIVEL,";
        $cadena_sql.=" pen_ind_ele ELECTIVA,";
        $cadena_sql.=" pen_cre CREDITOS,";
        $cadena_sql.=" cea_abr CLASIFICACION,";
        $cadena_sql.=" cea_cod COD_CLASIFICACION";
        $cadena_sql.=" FROM acpen";
        $cadena_sql.=" INNER JOIN acasi ON acpen.pen_asi_cod=acasi.asi_cod";
        $cadena_sql.=" LEFT OUTER JOIN acclasificacpen ON acpen.pen_asi_cod=clp_asi_cod";
        $cadena_sql.=" AND acpen.pen_cra_cod=clp_cra_cod";
        $cadena_sql.=" AND acpen.pen_nro=clp_pen_nro";
        $cadena_sql.=" LEFT OUTER JOIN geclasificaespac ON cea_cod=clp_cea_cod";
        $cadena_sql.=" WHERE pen_nro=" . $variable['planEstudioEstudiante'];
        $cadena_sql.=" AND pen_cra_cod=" . $variable['codProyectoEstudiante'];
        $cadena_sql.=" AND pen_estado like '%A%'";
        $cadena_sql.=" AND (clp_cea_cod!=4 or clp_cea_cod is null)";
        $cadena_sql.=" ORDER BY pen_sem, pen_asi_cod";
        break;
        
      case 'nota_aprobatoria':
        $cadena_sql="SELECT fua_nota_aprobatoria(" . $variable['codProyectoEstudiante'] . ")";
        $cadena_sql.=" FROM dual";
        break;
    
      case 'espacios_cursados':
        $cadena_sql="SELECT not_asi_cod CODIGO,";
        $cadena_sql.=" not_nota NOTA,";
        $cadena_sql.=" not_obs OBSERVACION";
        $cadena_sql.=" FROM acnot_activos";
        $cadena_sql.=" WHERE not_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND not_est_reg like '%A%'";
        break;
    
     case 'espacios_inscritos':
        $cadena_sql="SELECT ins_asi_cod CODIGO ";
        $cadena_sql.=" FROM acins ";
        $cadena_sql.=" WHERE ins_est_cod = " . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano = " . $variable['ano'];
        $cadena_sql.=" AND ins_per = " . $variable['periodo'];
        break;
    
     case 'buscar_requisitos_plan':
        $cadena_sql=" SELECT req_cra_cod COD_PROYECTO,";
        $cadena_sql.=" req_asi_cod COD_ASIGNATURA,";
        $cadena_sql.=" req_cod COD_REQUISITO,";
        $cadena_sql.=" req_sem SEMESTRE,";
        $cadena_sql.=" req_ind_req,";
        $cadena_sql.=" req_estado ESTADO,";
        $cadena_sql.=" req_asi_corre CORREQUISITO,";
        $cadena_sql.=" req_pen_nro COD_PLAN";
        $cadena_sql.=" FROM acreq";
        $cadena_sql.=" WHERE req_cra_cod=" . $variable['codProyectoEstudiante'];
        $cadena_sql.=" AND req_pen_nro=" . $variable['planEstudioEstudiante'];
        break;
     case 'espacios_borrados':
        $cadena_sql=" SELECT";
        $cadena_sql.=" ins_cra_cod CARRERA,";
        $cadena_sql.=" ins_est_cod COD_ESTUDIANTE,";
        $cadena_sql.=" ins_asi_cod CODIGO";
        $cadena_sql.=" FROM acins_borradas";
        $cadena_sql.=" WHERE ins_ano=" . $variable['ano'];
        $cadena_sql.=" AND ins_per=" . $variable['periodo'];
        $cadena_sql.=" AND ins_est_cod=" . $variable['codEstudiante'];
        break;
     }
    return $cadena_sql;
  }

}

?>