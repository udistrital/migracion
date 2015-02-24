<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminConsultarInscripcionesEstudiante extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

    function __construct($configuracion) {
        $this->configuracion=$configuracion;
    }
  function cadena_sql($tipo, $variable="") {

    switch ($tipo) {

      #consulta de datos del estudiante
      case 'periodoActivo':
        $cadena_sql = "SELECT ape_ano ANO,";
        $cadena_sql.=" ape_per PERIODO";
        $cadena_sql.=" FROM acasperi";
        $cadena_sql.=" WHERE ape_estado like '%A%'";
        break;

      case 'consultaEspaciosInscritos':
        $cadena_sql = "SELECT ins_asi_cod CODIGO,";
        $cadena_sql.=" asi_nombre NOMBRE,";
        $cadena_sql.=" ins_gr GRUPO,";
        $cadena_sql.=" ins_cred CREDITOS,";
        $cadena_sql.=" ins_cea_cod CLASIFICACION";
        //$cadena_sql.=" pen_ind_ele ELECTIVA,";
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

      case 'horario_grupos':
//se actualiza parar la nueva estructura de tablas 04/06/2013          
        $cadena_sql=" SELECT DISTINCT hor_dia_nro DIA,";
        $cadena_sql.=" hor_hora HORA,";
        $cadena_sql.=" hor_sede SEDE,";
        $cadena_sql.=" hor_edificio EDIFICIO,";
        $cadena_sql.=" hor_salon SALON";
        $cadena_sql.=" FROM sga_achorarios";
        $cadena_sql.=" WHERE hor_id_curso=".$variable['ID_GRUPO'];
        $cadena_sql.=" AND hor_alternativa=".$variable['HOR_ALTERNATIVO'];
        $cadena_sql.=" ORDER BY DIA,HORA,SEDE";
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
        $cadena_sql.=" ins_ano ANO,";
        $cadena_sql.=" ins_periodo PERIODO";
        $cadena_sql.=" FROM sga_carga_inscripciones";
        $cadena_sql.=" WHERE ins_est_cod =".$variable['codEstudiante']; 
        $cadena_sql.=" AND ins_ano =".$variable['ano'];
        $cadena_sql.=" AND ins_periodo =".$variable['periodo'];
        break;

        case 'clasificacion':
    
        $cadena_sql =" SELECT id_clasificacion CODIGO_CLASIF, ";
        $cadena_sql.=" clasificacion_abrev ABREV_CLASIF, ";
        $cadena_sql.=" clasificacion_nombre NOMBRE_CLASIF ";
        $cadena_sql.=" FROM ".$this->configuracion['prefijo']."espacio_clasificacion ";
        break;
    
    }#Cierre de switch

    return $cadena_sql;
  }

#Cierre de funcion cadena_sql
}

#Cierre de clase
?>