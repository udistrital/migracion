<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminConsultarInscripcionCreditosEstudianteSop extends sql { //@ Método que crea las sentencias sql para el modulo admin_consultarInscripcionCreditosEstudiante
  private $configuracion;
  //@ Método costructor que crea el objeto
  function __construct($configuracion) {
    $this->configuracion = $configuracion;

  }

  function cadena_sql($tipo, $variable="") {

    switch ($tipo) {

      #consulta periodo activo
      case 'periodoActivo':

        $cadena_sql = "SELECT ape_ano ANO,";
        $cadena_sql.=" ape_per PERIODO";
        $cadena_sql.=" FROM acasperi";
        $cadena_sql.=" WHERE ape_estado like '%A%'";
        break;

      #consulta de datos del estudiante
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
        $cadena_sql.=" ins_gr GRUPO,";
        $cadena_sql.=" ins_cred CREDITOS, ";
        $cadena_sql.=" cea_abr CLASIFICACION ";
        $cadena_sql.=" FROM acins";
        $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
        $cadena_sql.=" LEFT outer join geclasificaespac on cea_cod=ins_cea_cod ";
        $cadena_sql.=" WHERE ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano=" . $variable['ano'];
        $cadena_sql.=" AND ins_per=" . $variable['periodo'];
        $cadena_sql.=" AND ins_estado LIKE '%A%'";
        $cadena_sql.=" AND ins_cra_cod=" . $variable['codProyectoEstudiante'];
        $cadena_sql.=" ORDER BY CODIGO";
        break;

    case 'horario_grupos':

        $cadena_sql = "SELECT DISTINCT hor_dia_nro DIA,";
        $cadena_sql.=" hor_hora HORA,";
        $cadena_sql.=" sed_abrev SEDE,";
        $cadena_sql.=" hor_sal_cod SALON";
        $cadena_sql.=" FROM achorario";
        $cadena_sql.=" INNER JOIN accurso ON achorario.hor_asi_cod=accurso.cur_asi_cod";
        $cadena_sql.="      AND achorario.hor_nro=accurso.cur_nro";
        $cadena_sql.="      AND achorario.hor_ape_ano=accurso.cur_ape_ano ";
        $cadena_sql.="      AND achorario.hor_ape_per=accurso.cur_ape_per";
        $cadena_sql.=" INNER JOIN gesede ON achorario.hor_sed_cod=gesede.sed_cod";
        $cadena_sql.=" WHERE cur_asi_cod =" . $variable['CODIGO']; //codigo del espacio
        $cadena_sql.=" AND hor_ape_ano =" . $variable['ano'];
        $cadena_sql.=" AND hor_ape_per =" . $variable['periodo'];
        $cadena_sql.=" AND hor_nro =" . $variable['GRUPO']; //numero de grupo
        $cadena_sql.=" ORDER BY DIA,HORA,SEDE"; //no cambiar el orden
        break;

    case 'fechas_activas_estudiante':

        $cadena_sql=" SELECT ";
        $cadena_sql.=" TO_CHAR (aac_fecha_ini,'dd-mon-YYYY\" a las \"HH24:MI:SS') FECHAINICIO,";
        $cadena_sql.=" TO_CHAR (aac_fecha_fin,'dd-mon-YYYY\" a las \"HH24:MI:SS') FECHAFIN";
        $cadena_sql.=" FROM acfranjasadican";
        $cadena_sql.=" WHERE aac_ano = ".$variable['ano'];
        $cadena_sql.=" AND aac_periodo = ".$variable['periodo'];
        $cadena_sql.=" AND aac_estado LIKE '%A%'";
        $cadena_sql.=" AND aac_cra_cod='".$variable['codProyecto']."'";
        $cadena_sql.=" ORDER BY aac_fecha_ini ";
        break;

    case 'contar_fechas':

        $cadena_sql=" SELECT count(*) FECHAS";
        $cadena_sql.=" FROM accaleventos";
        $cadena_sql.=" WHERE ace_anio = ".$variable['ano'];
        $cadena_sql.=" AND ace_periodo = ".$variable['periodo'];
        $cadena_sql.=" AND ace_estado LIKE '%A%'";
        $cadena_sql.=" AND ace_cra_cod='".$variable['codProyecto']."'";
        $cadena_sql.=" AND ace_cod_evento in (15,16)";
        break;

    case 'creditosPlan':

        $cadena_sql="SELECT parametro_creditosPlan CREDITOS_PLAN, ";
        $cadena_sql.=" parametro_maxCreditosNivel MAXIMO,";
        $cadena_sql.=" parametros_OB OB,";
        $cadena_sql.=" parametros_OC OC,";
        $cadena_sql.=" parametros_EI EI,";
        $cadena_sql.=" parametros_EE EE,";
        $cadena_sql.=" parametros_CP CP";
        $cadena_sql.=" FROM ".$this->configuracion["prefijo"]."parametro_plan_estudio";
        $cadena_sql.=" WHERE parametro_idPlanEstudio=".$variable;
        break;

    case 'espacios_cursados':

        $cadena_sql="SELECT not_asi_cod CODIGO,";
        $cadena_sql.=" not_nota NOTA,";
        $cadena_sql.=" not_cra_cod CARRERA,";
        $cadena_sql.=" not_cred CREDITOS,";
        $cadena_sql.=" not_cea_cod CLASIFICACION";
        $cadena_sql.=" FROM acnot_activos";
        $cadena_sql.=" WHERE not_est_cod =".$variable['codEstudiante'];
        //$cadena_sql.=" AND not_nota >= '30'";
        $cadena_sql.=" AND not_cra_cod=".$variable['codProyectoEstudiante'];
        $cadena_sql.=" AND not_est_reg LIKE '%A%'";
        break;

    case 'clasificacion':

        $cadena_sql =" SELECT id_clasificacion, ";
        $cadena_sql.=" clasificacion_abrev, ";
        $cadena_sql.=" clasificacion_nombre  ";
        $cadena_sql.=" FROM ".$this->configuracion['prefijo']."espacio_clasificacion ";
        break;
    
    case 'nota_aprobatoria':
        $cadena_sql="SELECT cra_nota_aprob from accra where cra_cod=". $variable['codProyectoEstudiante'];
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

    }#Cierre de switch

    return $cadena_sql;
  }

#Cierre de funcion cadena_sql
}

#Cierre de clase
?>