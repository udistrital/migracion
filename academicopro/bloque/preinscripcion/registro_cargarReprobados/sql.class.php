<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_registroCargarReprobados extends sql {

  function cadena_sql($opcion, $variable="") {

    switch ($opcion) {

    case 'periodoActivo':

        $cadena_sql = "SELECT ape_ano ANO,";
        $cadena_sql.=" ape_per PERIODO";
        $cadena_sql.=" FROM acasperipreinsdemanda";
        $cadena_sql.=" WHERE";
        $cadena_sql.=" ape_estado LIKE '%A%'";
        break;

    case 'consultarDatosEstudiantes':
        $cadena_sql=" SELECT ins_est_cod CODIGO,";
        $cadena_sql.=" ins_est_cra_cod COD_CARRERA,";
        $cadena_sql.=" ins_espacios_por_cursar ESPACIOS_POR_CURSAR";
        $cadena_sql.=" FROM sga_carga_inscripciones";
        $cadena_sql.=" WHERE ins_est_cra_cod =".$variable['codProyecto'];
        $cadena_sql.=" ORDER BY CODIGO DESC"; 
        break; 
    
    case 'insertarRegistroDatosEstudiante':

        $cadena_sql="INSERT INTO ACINSDEMANDA ";
        $cadena_sql.="(INSDE_ANO,INSDE_PER,INSDE_EST_COD,INSDE_ASI_COD,INSDE_CRA_COD,INSDE_CRED,INSDE_HTD,INSDE_HTC,INSDE_HTA,INSDE_CEA_COD,INSDE_SEM,INSDE_PERDIDO,INSDE_ESTADO) ";
        $cadena_sql.="VALUES ('".$variable['ano']."',";
        $cadena_sql.="'".$variable['periodo']."',";
        $cadena_sql.="'".$variable['codEstudiante']."',";
        $cadena_sql.="'".$variable['codEspacio']."',";
        $cadena_sql.="'".$variable['codProyectoEstudiante']."',";
        $cadena_sql.="'".$variable['creditos']."',";
        $cadena_sql.="'".$variable['htd']."',";
        $cadena_sql.="'".$variable['htc']."',";
        $cadena_sql.="'".$variable['hta']."',";
        $cadena_sql.="'".$variable['cea']."',";
        $cadena_sql.="'".$variable['sem']."',";
        $cadena_sql.="'".$variable['perdido']."',";
        $cadena_sql.="'A')";
        //$cadena_sql.="'".$variable['equivalente']."')";
        break;
        
    case 'consultarDatosCarreras':
        $cadena_sql=" SELECT distinct ins_est_cra_cod COD_CARRERA,";
        $cadena_sql.=" ins_cra_nombre NOMBRE,";
        $cadena_sql.=" ins_fac_cod FACULTAD";
        $cadena_sql.=" FROM sga_carga_inscripciones";
        $cadena_sql.=" WHERE ins_fac_cod=".$variable['codFacultad'];
        //$cadena_sql.=" AND tra_cod_nivel=1";
        break;
    
    }
    return $cadena_sql;
 

  }

}

?>