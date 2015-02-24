<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminConsultarInscripcionEstudianteCoorPosgrado extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

  function cadena_sql($tipo, $variable="") {

    switch ($tipo) {

      #consulta de datos del estudiante
      case 'periodoActivo':

        $cadena_sql = "SELECT ape_ano ANO,";
        $cadena_sql.=" ape_per PERIODO";
        $cadena_sql.=" FROM acasperi";
        $cadena_sql.=" WHERE ape_estado like '%A%'";
        break;

      case "consultaEstudiante":
        $cadena_sql = "SELECT est_cod CODIGO,";
        $cadena_sql.=" est_nombre NOMBRE,";
        $cadena_sql.=" est_estado_est LETRA_ESTADO,";
        $cadena_sql.=" estado_nombre ESTADO,";
        $cadena_sql.=" est_cra_cod CODIGO_CARRERA,";
        $cadena_sql.=" cra_nombre NOMBRE_CARRERA,";
        $cadena_sql.=" est_pen_nro PLAN_ESTUDIO,";
        $cadena_sql.=" est_ind_cred INDICA_CREDITOS";
        $cadena_sql.=" FROM acest";
        $cadena_sql.=" INNER JOIN acestado ON estado_cod= est_estado_est";
        $cadena_sql.=" INNER JOIN accra ON cra_cod=est_cra_cod";
        $cadena_sql.=" WHERE est_cod=" . $variable;
        break;

      case 'consultaEspaciosInscritos':

        $cadena_sql = "SELECT ins_asi_cod CODIGO,";
        $cadena_sql.=" asi_nombre NOMBRE,";
        $cadena_sql.=" pen_cre CREDITOS,";
        $cadena_sql.=" pen_ind_ele ELECTIVA,";
        $cadena_sql.=" ins_gr GRUPO";
        $cadena_sql.=" FROM ACINS";
        $cadena_sql.=" INNER JOIN acpen ON pen_asi_cod=ins_asi_cod";
        $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
        $cadena_sql.=" WHERE ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano=" . $variable['ano'];
        $cadena_sql.=" AND ins_per=" . $variable['periodo'];
        $cadena_sql.=" AND ins_estado LIKE '%A%'";
        $cadena_sql.=" AND pen_nro=" . $variable['planEstudioEstudiante'];
        $cadena_sql.=" AND pen_cra_cod=" . $variable['codProyectoEstudiante'];
        $cadena_sql.=" ORDER BY 1";
        break;

      case 'horario_grupos':

//        $cadena_sql = "SELECT DISTINCT hor_dia_nro DIA,";
//        $cadena_sql.=" hor_hora HORA,";
//        $cadena_sql.=" sed_abrev SEDE,";
//        $cadena_sql.=" hor_sal_cod SALON";
//        $cadena_sql.=" FROM achorario";
//        $cadena_sql.=" INNER JOIN accurso ON achorario.hor_asi_cod=accurso.cur_asi_cod AND achorario.hor_nro=accurso.cur_nro";
//        $cadena_sql.=" INNER JOIN gesede ON achorario.hor_sed_cod=gesede.sed_cod";
//        $cadena_sql.=" WHERE CUR_ASI_COD=" . $variable['CODIGO']; //codigo del espacio
//        //$cadena_sql.=" AND CUR_CRA_COD=".$variable['codProyecto'];  //codigo del proyecto curricular
//        $cadena_sql.=" AND HOR_APE_ANO=" . $variable['ano'];
//        $cadena_sql.=" AND HOR_APE_PER=" . $variable['periodo'];
//        $cadena_sql.=" AND HOR_NRO=" . $variable['GRUPO']; //numero de grupo
//        $cadena_sql.=" ORDER BY 1,2,3"; //no cambiar el orden
        
        $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO DIA, HOR_HORA HORA, SED_ID COD_SEDE, SALON.SAL_EDIFICIO ID_EDIFICIO,EDI.EDI_NOMBRE NOM_EDIFICIO,HOR_SAL_ID_ESPACIO ID_SALON,SALON.SAL_NOMBRE NOM_SALON ";
        $cadena_sql.=" FROM achorario_2012 horario";
        $cadena_sql.=" INNER JOIN accurso ON horario.hor_asi_cod=accurso.cur_asi_cod AND horario.hor_nro=accurso.cur_nro AND horario.hor_ape_ano=accurso.cur_ape_ano AND horario.hor_ape_per=accurso.cur_ape_per ";
        $cadena_sql.=" INNER JOIN GESALON_2012 SALON ON HOR_SAL_ID_ESPACIO=SAL_ID_ESPACIO";
        $cadena_sql.=" INNER JOIN GESEDE SEDE ON horario.HOR_SED_COD=SEDE.SED_COD";
        $cadena_sql.=" INNER JOIN GEEDIFICIO EDI ON SALON.SAL_EDIFICIO=EDI.EDI_COD";
        $cadena_sql.=" WHERE CUR_ASI_COD=".$variable['CODIGO'];
        //$cadena_sql.=" AND CUR_CRA_COD=".$variable['carrera'];
        $cadena_sql.=" AND HOR_APE_ANO=".$variable['ano'];
        $cadena_sql.=" AND HOR_APE_PER=".$variable['periodo'];
        $cadena_sql.=" AND HOR_NRO=".$variable['GRUPO'];
        $cadena_sql.=" ORDER BY 1,2,3";
        break;

    case 'fechas_activas_coordinador':
        $cadena_sql=" SELECT ace_cod_evento EVENTO,";
        $cadena_sql.=" ace_cra_cod CARRERA,";
        $cadena_sql.=" to_char(ace_fec_ini,'dd-mon-YYYY') FECHAINICIO,";
        $cadena_sql.=" to_char(ace_fec_fin,'dd-mon-YYYY') FECHAFIN";
        $cadena_sql.=" FROM accaleventos";
        $cadena_sql.=" WHERE ace_anio = ".$variable['ano'];
        $cadena_sql.=" AND ace_periodo = ".$variable['periodo'];
        $cadena_sql.=" AND ace_estado LIKE '%A%'";
        $cadena_sql.=" AND ace_cra_cod='".$variable['codProyecto']."'";
        $cadena_sql.=" AND ace_cod_evento=8";
        $cadena_sql.=" ORDER BY FECHAINICIO";
        break;

    case 'contar_fechas':
        $cadena_sql=" SELECT count(*) FECHAS";
        $cadena_sql.=" FROM accaleventos";
        $cadena_sql.=" WHERE ace_anio = ".$variable['ano'];
        $cadena_sql.=" AND ace_periodo = ".$variable['periodo'];
        $cadena_sql.=" AND ace_estado LIKE '%A%'";
        $cadena_sql.=" AND ace_cra_cod='".$variable['codProyectoEstudiante']."'";
        $cadena_sql.=" AND ace_cod_evento=8";
        break;

    }#Cierre de switch

    return $cadena_sql;
  }

#Cierre de funcion cadena_sql
}

#Cierre de clase
?>