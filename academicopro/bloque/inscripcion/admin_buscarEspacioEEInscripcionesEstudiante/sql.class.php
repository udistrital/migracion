<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminBuscarEspacioEEInscripcionesEstudiante extends sql {

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
        $cadena_sql="SELECT fua_nota_aprobatoria('" . $variable['codProyectoEstudiante'] . "')";
//        $cadena_sql.=" FROM dual";
        break;

      case 'espacios_estudiante':

        $cadena_sql="SELECT not_asi_cod NOT_ASI_COD,";
        $cadena_sql.=" not_nota NOTA,";
        $cadena_sql.=" not_obs  NOT_OBS";
        $cadena_sql.=" FROM acnot";
        $cadena_sql.=" WHERE not_est_cod =" . $variable['codEstudiante'];
        $cadena_sql.=" AND not_est_reg like '%A%'"; 
        break;


      case 'espacios_inscritos':
        $cadena_sql="SELECT ins_asi_cod INS_ASI_COD ";
        $cadena_sql.=" FROM acins ";
        $cadena_sql.=" WHERE ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano=" . $variable['ano'];
        $cadena_sql.=" AND ins_per=" . $variable['periodo'];
        break;

     case 'electivas_extrinsecas':

        $cadena_sql=" SELECT DISTINCT espacio.asi_cod CODIGO,";
        $cadena_sql.=" espacio.asi_nombre NOMBRE,";
        $cadena_sql.=" clasificacion.cea_cod CEA_COD,";
        $cadena_sql.=" clasificacion.cea_abr CLASIFICACION,";
        $cadena_sql.=" plan_espacio.pen_cre CREDITOS,";
        $cadena_sql.=" plan_espacio.pen_nro PENSUM,";
        $cadena_sql.=" plan_espacio.pen_estado ESTADO,";
        $cadena_sql.=" clasifica.clp_estado ESTADO_CLP,";
        $cadena_sql.=" plan_espacio.pen_cra_cod PEN_CRA_COD,";
        $cadena_sql.=" proyecto.cra_nombre CRA_NOMBRE,";
        $cadena_sql.=" facultad.dep_cod DEP_COD,";
        $cadena_sql.=" facultad.dep_nombre DEP_NOMBRE";
        $cadena_sql.=" FROM acasi espacio";
        $cadena_sql.=" INNER JOIN acpen plan_espacio";
        $cadena_sql.=" ON plan_espacio.pen_asi_cod = espacio.asi_cod";
        $cadena_sql.=" INNER JOIN acclasificacpen clasifica";
        $cadena_sql.=" ON clasifica.clp_asi_cod=plan_espacio.pen_asi_cod AND clasifica.clp_cra_cod=plan_espacio.pen_cra_cod AND clasifica.clp_pen_nro=plan_espacio.pen_nro";
        $cadena_sql.=" INNER JOIN geclasificaespac clasificacion";
        $cadena_sql.=" ON clasificacion.cea_cod = clasifica.clp_cea_cod";
        $cadena_sql.=" INNER JOIN accra proyecto";
        $cadena_sql.=" ON plan_espacio.pen_cra_cod=proyecto.cra_cod";
        $cadena_sql.=" INNER JOIN gedep facultad";
        $cadena_sql.=" ON facultad.dep_cod=proyecto.cra_dep_cod";
        $cadena_sql.=" INNER JOIN accursos";
        $cadena_sql.=" ON plan_espacio.pen_asi_cod = cur_asi_cod";
        $cadena_sql.=" WHERE plan_espacio.pen_estado LIKE '%A%'";
        $cadena_sql.=" AND clasifica.clp_estado LIKE '%A%'";
        $cadena_sql.=" AND clasificacion.cea_cod=4";
//        $cadena_sql.=" AND clasifica.clp_asi_cod NOT IN";
//        $cadena_sql.=" (SELECT clp_asi_cod";
//        $cadena_sql.=" FROM acclasificacpen";
//        $cadena_sql.=" WHERE clp_cea_cod !=4";
//        $cadena_sql.=" AND clp_pen_nro =".$variable['planEstudioEstudiante']." ) ";
        $cadena_sql.=" AND cur_ape_ano =".$variable['ano']." ";
        $cadena_sql.=" AND cur_ape_per =".$variable['periodo']." ";
        $cadena_sql.=" ORDER BY espacio.asi_cod";
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
        $cadena_sql.="where parametro_idPlanEstudio = ".$variable;
        break;

    case 'valorCreditosPlan':

        $cadena_sql =" SELECT espacio_nroCreditos, ";
        $cadena_sql.=" id_clasificacion ";
        $cadena_sql.=" FROM sga_espacio_academico ";
        $cadena_sql.=" JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
        $cadena_sql.=" WHERE sga_espacio_academico.id_espacio= ".$variable[0];
        $cadena_sql.=" AND id_planEstudio=".$variable[1];
        break;

    case 'valorCreditos':

        $cadena_sql="SELECT espacio_nroCreditos, ";
        $cadena_sql.=" id_clasificacion ";
        $cadena_sql.=" FROM sga_espacio_academico ";
        $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
        $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[0];
        break;

    case 'estudianteCancelo':

        $cadena_sql="SELECT horario_codEstudiante,";
        $cadena_sql.=" horario_idEspacio,";
        $cadena_sql.=" horario_grupo";
        $cadena_sql.=" FROM sga_horario_estudiante";
        $cadena_sql.=" WHERE horario_codEstudiante = ".$variable[0];
        $cadena_sql.=" AND horario_estado = 3";
        $cadena_sql.=" AND horario_ano = ".$variable[1];
        $cadena_sql.=" AND horario_periodo = ".$variable[2];
        //$cadena_sql.=" AND horario_idEspacio = ".$variable[1];
        break;

    case 'consultarNombreEstado':
        $cadena_sql = "SELECT estado_cod LETRA_ESTADO,";
        $cadena_sql.=" estado_nombre ESTADO";
        $cadena_sql.=" FROM acestado ";
        $cadena_sql.=" WHERE estado_cod='" . $variable."'";
        break;
    
    case 'espacios_cursados':

        $cadena_sql="SELECT not_asi_cod CODIGO,";
        $cadena_sql.=" not_nota NOTA,";
        $cadena_sql.=" not_cra_cod CARRERA,";
        $cadena_sql.=" not_cred CREDITOS,";
        $cadena_sql.=" not_cea_cod CLASIFICACION";
        $cadena_sql.=" FROM acnot";
        $cadena_sql.=" WHERE not_est_cod =".$variable;
        //$cadena_sql.=" AND not_nota >= '30'";
        $cadena_sql.=" AND not_est_reg LIKE '%A%'";
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
        //echo $cadena_sql;exit;
        break;

    }
    //echo $cadena_sql."<br>";
    return $cadena_sql;
  }

}

?>