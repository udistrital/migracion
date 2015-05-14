<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminBuscarEspacioCreditosEstudianteSop extends sql {

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

        $cadena_sql="SELECT cra_nota_aprob from accra where cra_cod=". $variable['codProyectoEstudiante'];
        break;

      case 'espacios_estudiante':

        $cadena_sql="SELECT not_asi_cod NOT_ASI_COD,";
        $cadena_sql.=" not_nota NOTA,";
        $cadena_sql.=" not_cra_cod CARRERA,";
        $cadena_sql.=" not_cred CREDITOS,";
        $cadena_sql.=" not_cea_cod CLASIFICACION";
        $cadena_sql.=" FROM acnot_activos";
        $cadena_sql.=" WHERE not_est_cod =" . $variable['codEstudiante'];
        $cadena_sql.=" AND not_est_reg like '%A%'";
         break;

      case 'espacios_inscritos':
        $cadena_sql="SELECT ins_asi_cod INS_ASI_COD ";
        $cadena_sql.=" FROM acins ";
        $cadena_sql.=" WHERE ins_est_cod = " . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano = " . $variable['ano'];
        $cadena_sql.=" AND ins_per = " . $variable['periodo'];
        break;
    
      case 'espacios_plan_estudio':

        $cadena_sql = "SELECT DISTINCT";
        $cadena_sql.=" pen_asi_cod CODIGO,";
        $cadena_sql.=" asi_nombre NOMBRE,";
        $cadena_sql.=" pen_sem NIVEL,";
        //$cadena_sql.=" pen_ind_ele ELECTIVA,";
        $cadena_sql.=" pen_cre CREDITOS,";
        $cadena_sql.=" cea_abr CLASIFICACION ";
        $cadena_sql.=" FROM acpen";
        $cadena_sql.=" INNER JOIN acasi ON acpen.pen_asi_cod = acasi.asi_cod";
        $cadena_sql.=" INNER JOIN acclasificacpen ON clp_asi_cod = pen_asi_cod AND clp_cra_cod = pen_cra_cod AND clp_pen_nro = pen_nro ";
        $cadena_sql.=" LEFT OUTER JOIN geclasificaespac ON cea_cod = clp_cea_cod ";
        $cadena_sql.=" WHERE pen_nro =" . $variable['planEstudioEstudiante'];
        $cadena_sql.=" AND pen_cra_cod =" . $variable['codProyectoEstudiante'];
        $cadena_sql.=" AND pen_estado LIKE '%A%'";
       // $cadena_sql.=" AND pen_asi_cod NOT IN";
        //$cadena_sql.=" (".$variable['aprobados'].")";
        //$cadena_sql.=" AND pen_asi_cod NOT IN";
       // $cadena_sql.=" (".$variable['inscritos'].")";
        $cadena_sql.=" AND clp_cea_cod != 4 ";
        $cadena_sql.=" ORDER BY pen_sem, pen_asi_cod";
        break;

      case 'espacios_plan_estudio_prueba':

        $cadena_sql=" SELECT DISTINCT pen_asi_cod, ";
        $cadena_sql.=" asi_nombre, ";
        $cadena_sql.=" pen_sem ";
        $cadena_sql.=" FROM acpen ";
        $cadena_sql.=" INNER JOIN acasi ON acpen.pen_asi_cod = acasi.asi_cod  ";
        $cadena_sql.=" WHERE pen_nro = ".$variable['planEstudioEstudiante'];
        $cadena_sql.=" AND pen_estado LIKE '%A%'";
        $cadena_sql.=" AND pen_asi_cod IN (";
        $cadena_sql.=" ".$variable['reprobados']." ) ";
        $cadena_sql.=" and pen_asi_cod NOT IN (";
        $cadena_sql.=" ".$variable['aprobados']." ) ";
        $cadena_sql.=" and pen_asi_cod NOT IN (";
        $cadena_sql.=" ".$variable['inscritos']." ) ";
        $cadena_sql.=" ORDER BY pen_sem, pen_asi_cod ";
        break;

      case 'espacios_equivalentes':

        $cadena_sql= "SELECT hom_asi_cod_ant ASI_COD_ANTERIOR,";
        $cadena_sql.= " hom_asi_cod_nue CODIGO,";
        $cadena_sql.= " asi_nombre NOMBRE";
        $cadena_sql.= " FROM achomcra";
        $cadena_sql.= " INNER JOIN acasi ON hom_asi_cod_nue = asi_cod";
        $cadena_sql.= " WHERE hom_cra_cod_ant = ".$variable['codProyectoEstudiante'];
        $cadena_sql.= " AND hom_cra_cod_nue = ".$variable['codProyectoEstudiante'];
        $cadena_sql.= " AND hom_asi_cod_ant = ".$variable['codEspacio'];
        $cadena_sql.= " AND hom_estado LIKE '%A%'";
        $cadena_sql.= " AND asi_ind_cred = 'S' ";
        break;

      case 'otroRequisito':
        $cadena_sql="SELECT COUNT(requisitos_previoAprobado) ";
        $cadena_sql.=" FROM sga_requisitos_espacio_plan_estudio ";
        $cadena_sql.=" WHERE requisitos_idPlanEstudio = '".$variable[0]."' ";
        $cadena_sql.=" AND requisitos_idEspacioPosterior = '".$variable[1]."' ";
        break;

      case 'requisitos':

        $cadena_sql=" SELECT requisitos_previoAprobado, ";
        $cadena_sql.=" requisitos_idEspacioPrevio, ";
        $cadena_sql.=" requisitos_idEspacioPosterior,";
        $cadena_sql.=" requisitos_idPlanEstudio ";
        $cadena_sql.=" FROM sga_requisitos_espacio_plan_estudio ";
        $cadena_sql.=" WHERE requisitos_idPlanEstudio = '".$variable[0]."' ";
        $cadena_sql.=" AND requisitos_idEspacioPosterior = '".$variable[1]."'";
        break;

      case 'espaciosCancelados':

        $cadena_sql="SELECT horario_codEstudiante, ";
        $cadena_sql.=" horario_idEspacio,";
        $cadena_sql.=" horario_grupo";
        $cadena_sql.=" FROM sga_horario_estudiante ";
        $cadena_sql.=" WHERE horario_codEstudiante = ".$variable[0];
        $cadena_sql.=" AND horario_estado = 3";
        $cadena_sql.=" AND horario_ano = ".$variable[1];
        $cadena_sql.=" AND horario_periodo = ".$variable[2];
        //$cadena_sql.=" AND horario_idEspacio = ".$variable[1];
        break;

      case 'parametros_plan':

        $cadena_sql=" SELECT parametro_idPlanEstudio, ";
        $cadena_sql.=" parametro_creditosPlan, ";
        $cadena_sql.=" parametro_promedioMinimo, ";
        $cadena_sql.=" parametro_maxCreditosNivel, ";
        $cadena_sql.=" parametro_minCreditosNivel, ";
        $cadena_sql.=" parametros_OB, ";
        $cadena_sql.=" parametros_OC, ";
        $cadena_sql.=" parametros_EI, ";
        $cadena_sql.=" parametros_EE, ";
        $cadena_sql.=" parametros_CP, ";
        $cadena_sql.=" parametros_aprobado ";
        $cadena_sql.=" FROM sga_parametro_plan_estudio ";
        $cadena_sql.=" WHERE parametro_idPlanEstudio = ".$variable;
        break;

    case 'valorCreditosPlan':

        $cadena_sql ="SELECT espacio_nroCreditos, ";
        $cadena_sql.=" id_clasificacion ";
        $cadena_sql.=" FROM sga_espacio_academico ";
        $cadena_sql.=" JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
        $cadena_sql.=" WHERE sga_espacio_academico.id_espacio= ".$variable[0];
        $cadena_sql.=" AND id_planEstudio=".$variable[1];
        break;

    case 'valorCreditos':

        $cadena_sql="SELECT espacio_nroCreditos,";
        $cadena_sql.=" id_clasificacion";
        $cadena_sql.=" FROM sga_espacio_academico ";
        $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
        $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[0];
        break;
    
    case 'espacios_cursados':

        $cadena_sql="SELECT not_asi_cod CODIGO,";
        $cadena_sql.=" not_nota NOTA,";
        $cadena_sql.=" not_cra_cod CARRERA,";
        $cadena_sql.=" not_cred CREDITOS,";
        $cadena_sql.=" not_cea_cod CLASIFICACION";
        $cadena_sql.=" FROM acnot_activos";
        $cadena_sql.=" WHERE not_est_cod =".$variable['codEstudiante'];
        $cadena_sql.=" AND not_cra_cod=".$variable['codProyectoEstudiante'];
        $cadena_sql.=" AND not_est_reg LIKE '%A%'";
        break;

      case 'buscar_requisitos_plan':

        $cadena_sql=" SELECT requisitos_previoAprobado APROBADO, ";
        $cadena_sql.=" requisitos_idEspacioPrevio COD_REQUISITO, ";
        $cadena_sql.=" requisitos_idEspacioPosterior COD_ASIGNATURA,";
        $cadena_sql.=" requisitos_idPlanEstudio COD_PLAN";
        $cadena_sql.=" FROM sga_requisitos_espacio_plan_estudio ";
        $cadena_sql.=" WHERE requisitos_idPlanEstudio=".$variable['planEstudioEstudiante'];
        break;
    

    }
    return $cadena_sql;
  }

}

?>