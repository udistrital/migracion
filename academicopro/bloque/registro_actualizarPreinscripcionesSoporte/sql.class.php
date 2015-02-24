<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_registroActualizarPreinscripcionesSoporte extends sql { //@ Método que crea las sentencias sql para el modulo
    private $configuracion;


    public function __construct($configuracion) {
        $this->configuracion=$configuracion;
    }
    
    function cadena_sql($tipo, $variable="") {

    switch ($tipo) {

      case 'periodoPreinscripciones':

        $cadena_sql = "SELECT ape_ano ANO,";
        $cadena_sql.=" ape_per PERIODO";
        $cadena_sql.=" FROM acasperipreinsdemanda";
        $cadena_sql.=" WHERE ape_estado like '%A%'";
        break;
    
     case 'carga':
        $cadena_sql =" SELECT ins_est_cod CODIGO,";
        $cadena_sql.=" ins_est_nombre NOMBRE,";
        $cadena_sql.=" ins_est_cra_cod COD_CARRERA,";
        $cadena_sql.=" ins_cra_nombre NOMBRE_CARRERA,";
        $cadena_sql.=" ins_fac_cod NOMBRE_FACULTAD,";
        $cadena_sql.=" ins_espacios_por_cursar ESPACIOS_POR_CURSAR";
        $cadena_sql.=" FROM sga_carga_inscripciones";
        $cadena_sql.=" WHERE ins_est_cod =".$variable['codEstudiante']; 
        $cadena_sql.=" AND ins_ano =".$variable['ano'];
        $cadena_sql.=" AND ins_periodo =".$variable['periodo'];
        break;
    
     case 'proyectosFacultad':
        $cadena_sql =" SELECT distinct ins_est_cra_cod CODIGO_CARRERA";
        $cadena_sql.=" FROM sga_carga_inscripciones";
        $cadena_sql.=" WHERE ins_fac_cod =".$variable;
        break;
    
      case 'clasificacion':   
        $cadena_sql =" SELECT id_clasificacion CODIGO_CLASIF, ";
        $cadena_sql.=" clasificacion_abrev ABREV_CLASIF, ";
        $cadena_sql.=" clasificacion_nombre NOMBRE_CLASIF ";
        $cadena_sql.=" FROM ".$this->configuracion['prefijo']."espacio_clasificacion ";
        break;

      case 'consultaPreinscripcionesCanceladasEstudiante':

        $cadena_sql = "SELECT insde_asi_cod ASI_CODIGO,";
        $cadena_sql .= " asi_nombre NOMBRE,";
        $cadena_sql .= " insde_cred CREDITOS,";
        $cadena_sql .= " insde_htd HTD,";
        $cadena_sql .= " insde_htc HTC,";
        $cadena_sql .= " insde_hta HTA,";
        $cadena_sql .= " insde_cea_cod CLASIFICACION,";
        $cadena_sql .= " insde_perdido PERDIDO,";
        $cadena_sql .= " insde_estado ESTADO,";
        $cadena_sql .= " insde_equivalente EQUIVALENTE";
        $cadena_sql .= " FROM acinsdemanda";
        $cadena_sql .= " INNER JOIN acasi ON asi_cod=insde_asi_cod";
        $cadena_sql .= " WHERE insde_est_cod=".$variable['codEstudiante'];
        $cadena_sql .= " AND insde_ano=".$variable['ano'];
        $cadena_sql .= " AND insde_per=".$variable['periodo'];
        $cadena_sql .= " AND insde_estado LIKE '%I%'";
        $cadena_sql .= " AND insde_cra_cod=".$variable['codProyectoEstudiante'];
        $cadena_sql .= " ORDER BY insde_asi_cod";

        break;

    case 'consultaPreinscripcionesEstudiante':

        $cadena_sql = "SELECT insde_est_cod COD_ESTUDIANTE,";
        $cadena_sql .= " insde_asi_cod ASI_CODIGO,";
        $cadena_sql .= " asi_nombre NOMBRE,";
        $cadena_sql .= " insde_cra_cod CARRERA,";
        $cadena_sql .= " insde_cred CREDITOS,";
        $cadena_sql .= " insde_htd HTD,";
        $cadena_sql .= " insde_htc HTC,";
        $cadena_sql .= " insde_hta HTA,";
        $cadena_sql .= " insde_cea_cod CLASIFICACION,";
        $cadena_sql .= " insde_perdido PERDIDO,";
        $cadena_sql .= " insde_estado ESTADO,";
        $cadena_sql .= " insde_ano ANO,";
        $cadena_sql .= " insde_per PERIODO";
        $cadena_sql .= " FROM acinsdemanda";
        $cadena_sql .= " INNER JOIN acasi ON asi_cod=insde_asi_cod";
        $cadena_sql .= " where insde_ano=".$variable['ano'];
        $cadena_sql .= " AND insde_per=".$variable['periodo'];
        $cadena_sql .= " AND insde_estado LIKE '%A%'";
        $cadena_sql .= " AND insde_cra_cod=".$variable['codProyecto'];
        $cadena_sql .= " ORDER BY insde_est_cod";
        break;
    
    case "inactivarPreinscripcion":

        $cadena_sql=" UPDATE acinsdemanda";
        $cadena_sql.=" SET insde_estado='I'";
        $cadena_sql.=" WHERE insde_ano=".$variable['ANO'];
        $cadena_sql.=" AND insde_per=".$variable['PERIODO'];
        $cadena_sql.=" AND insde_est_cod=".$variable['COD_ESTUDIANTE'];
        $cadena_sql.=" AND insde_asi_cod=".$variable['ASI_CODIGO'];
        $cadena_sql.=" AND insde_cra_cod=".$variable['CARRERA'];
        $cadena_sql.=" AND insde_perdido='".$variable['PERDIDO']."'";
        $cadena_sql.=" AND insde_estado='".$variable['ESTADO']."'";
        break;
    
    }

    return $cadena_sql;
  }

}

?>