<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminConsultarInscripcionEstudianteHoras extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

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
        $cadena_sql.=" est_ind_cred INDICA_CREDITOS,";
        $cadena_sql.=" est_acuerdo ACUERDO";
        $cadena_sql.=" FROM acest";
        $cadena_sql.=" INNER JOIN acestado ON estado_cod= est_estado_est";
        $cadena_sql.=" INNER JOIN accra ON cra_cod=est_cra_cod";
        $cadena_sql.=" WHERE est_cod=" . $variable;
        break;

      case 'consultaEspaciosInscritos':

        $cadena_sql = "SELECT ins_asi_cod CODIGO,";
        $cadena_sql.=" asi_nombre NOMBRE,";
        //$cadena_sql.=" pen_cre CREDITOS,";
        //$cadena_sql.=" pen_ind_ele ELECTIVA,";
        $cadena_sql.=" ins_gr GRUPO";
        $cadena_sql.=" FROM acins";
        //$cadena_sql.=" INNER JOIN acpen ON pen_asi_cod=ins_asi_cod";
        $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
        $cadena_sql.=" WHERE ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano=" . $variable['ano'];
        $cadena_sql.=" AND ins_per=" . $variable['periodo'];
        $cadena_sql.=" AND ins_estado LIKE '%A%'";
        $cadena_sql.=" AND ins_cra_cod=" . $variable['codProyectoEstudiante'];
        $cadena_sql.=" ORDER BY ins_asi_cod";
        break;

      case 'clasificacion':
        $cadena_sql="SELECT pen_ind_ele CLASIFICACION";
        $cadena_sql.=" FROM acpen";
        $cadena_sql.=" WHERE pen_asi_cod=".$variable['codEspacio'];
        $cadena_sql.=" AND pen_cra_cod=".$variable['codProyectoEstudiante'];
        break;

      case 'consultaNumeroEspaciosInscritos':

        $cadena_sql = "SELECT count(*)";
        $cadena_sql.=" FROM acins";
        $cadena_sql.=" WHERE ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano=" . $variable['ano'];
        $cadena_sql.=" AND ins_per=" . $variable['periodo'];
        $cadena_sql.=" AND ins_estado LIKE '%A%'";
        $cadena_sql.=" AND ins_cra_cod=" . $variable['codProyectoEstudiante'];
        break;

      case 'horario_grupos':

        $cadena_sql = "SELECT DISTINCT hor_dia_nro DIA,";
        $cadena_sql.=" hor_hora HORA,";
        $cadena_sql.=" sed_abrev SEDE,";
        $cadena_sql.=" hor_sal_cod SALON";
        $cadena_sql.=" FROM achorario";
        $cadena_sql.=" INNER JOIN accurso ON achorario.hor_asi_cod=accurso.cur_asi_cod AND achorario.hor_nro=accurso.cur_nro";
        $cadena_sql.=" INNER JOIN gesede ON achorario.hor_sed_cod=gesede.sed_cod";
        $cadena_sql.=" WHERE cur_asi_cod=" . $variable['CODIGO']; //codigo del espacio
        //$cadena_sql.=" AND CUR_CRA_COD=".$variable['codProyecto'];  //codigo del proyecto curricular
        $cadena_sql.=" AND hor_ape_ano=" . $variable['ano'];
        $cadena_sql.=" AND hor_ape_per=" . $variable['periodo'];
        $cadena_sql.=" AND hor_nro=" . $variable['GRUPO']; //numero de grupo
        $cadena_sql.=" ORDER BY hor_dia_nro,hor_hora,sed_abrev"; //no cambiar el orden
        break;

    case 'fechas_activas_estudiante':
        $cadena_sql=" SELECT ";
        $cadena_sql.=" TO_CHAR (aac_fecha_ini,'dd-mon-YYYY\" a las \"HH24:MI:SS') FECHAINICIO,";
        $cadena_sql.=" TO_CHAR (aac_fecha_fin,'dd-mon-YYYY\" a las \"HH24:MI:SS') FECHAFIN";
        $cadena_sql.=" FROM acfranjasadican";
        $cadena_sql.=" WHERE aac_ano = ".$variable['ano'];
        $cadena_sql.=" AND aac_periodo = ".$variable['periodo'];
        $cadena_sql.=" AND aac_estado LIKE '%A%'";
        $cadena_sql.=" AND aac_cra_cod='".$variable['codProyectoEstudiante']."'";
        $cadena_sql.=" ORDER BY aac_fecha_ini";
        break;

    case 'contar_fechas':
        $cadena_sql=" SELECT count(*) FECHAS";
        $cadena_sql.=" FROM accaleventos";
        $cadena_sql.=" WHERE ace_anio = ".$variable['ano'];
        $cadena_sql.=" AND ace_periodo = ".$variable['periodo'];
        $cadena_sql.=" AND ace_estado LIKE '%A%'";
        $cadena_sql.=" AND ace_cra_cod='".$variable['codProyectoEstudiante']."'";
        $cadena_sql.=" AND ace_cod_evento in (15,16)";
        break;

    case 'buscarParametrosHoras':
        $cadena_sql="SELECT api_maximo_asignaturas MAXIMO,";
        $cadena_sql.=" api_nro_semestres NUMERO_SEMESTRES,";
        $cadena_sql.=" api_consecutivos CONSECUTIVOS,";
        $cadena_sql.=" api_semestres_superiores SEMESTRES_SUPERIORES,";
        $cadena_sql.=" api_mas_asignaturas MAS_ASIGNATURAS";
        $cadena_sql.=" FROM acparins";
        $cadena_sql.=" WHERE api_ape_ano=".$variable['ano'];
        $cadena_sql.=" AND api_ape_per=".$variable['periodo'];
        $cadena_sql.=" AND api_cra_cod=".$variable['codProyectoEstudiante'];
        $cadena_sql.=" AND api_estado LIKE '%A%'";
        $cadena_sql.=" AND api_tipo LIKE '%C%'";
        break;
    
    

    }#Cierre de switch

    return $cadena_sql;
  }

#Cierre de funcion cadena_sql
}

#Cierre de clase
?>