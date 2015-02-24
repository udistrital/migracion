<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminConsultarCalendarioEventos extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

    function __construct($configuracion) {
        $this->configuracion=$configuracion;
    }
  function cadena_sql($tipo, $variable="") {

    switch ($tipo) {

        case 'calendario_eventos':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" ace_anio,";
                $cadena_sql.=" ace_periodo,";
                $cadena_sql.=" cra_cod, ";
                $cadena_sql.=" cra_nombre, ";
                $cadena_sql.=" acd_cod_evento, ";
                $cadena_sql.=" acd_descripcion, ";
                $cadena_sql.=" TO_CHAR(ace_fec_ini,'YYYY/MM/DD HH24:MI:SS') ace_fec_ini, ";
                $cadena_sql.=" TO_CHAR(ace_fec_fin,'YYYY/MM/DD HH24:MI:SS') ace_fec_fin, ";
                $cadena_sql.=" ace_estado";
                $cadena_sql.=" FROM accaleventos";
                $cadena_sql.=" INNER JOIN accra ON ace_cra_cod=cra_cod";
                $cadena_sql.=" INNER JOIN acdeseventos ON acd_cod_evento=ace_cod_evento";
                $cadena_sql.=" INNER JOIN acasperi ON ace_anio=ape_ano AND ace_periodo=ape_per";
                $cadena_sql.=" WHERE ape_estado='A'";
                if($variable['coordinador']>0){
                    $cadena_sql.=" AND cra_emp_nro_iden='".$variable['coordinador']."'";
                }
                if($variable['decano']>0){
                    $cadena_sql.=" AND cra_dep_cod in (
                                    SELECT dec_dep_cod 
                                    FROM peemp 
                                    INNER JOIN acdecanos ON dec_cod=emp_cod 
                                    WHERE emp_nro_iden='".$variable['decano']."')";
                }
                $cadena_sql.=" ORDER BY acd_cod_evento,";
                $cadena_sql.=" ace_fec_fin DESC";
            break;
            
        case 'consultar_facultades':
                $cadena_sql=" SELECT dep_cod,";
                $cadena_sql.=" dep_nombre FACULTAD";
                $cadena_sql.=" FROM gedep";
                $cadena_sql.=" WHERE dep_estado='A'";
                $cadena_sql.=" AND dep_nombre like '%FACULTAD%'";

                if($variable['decano']>0){
                    $cadena_sql.=" AND dep_cod in (
                                    SELECT dec_dep_cod 
                                    FROM peemp 
                                    INNER JOIN acdecanos ON dec_cod=emp_cod 
                                    WHERE emp_nro_iden='".$variable['decano']."')";
                }
                $cadena_sql.=" ORDER BY dep_nombre";
            break;
            
        case 'consultar_proyectos':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" cra_cod, ";
                $cadena_sql.=" (cra_cod ||' - '||cra_nombre) PROYECTO ";
                $cadena_sql.=" FROM accra ";
                $cadena_sql.=" WHERE cra_estado='A'";
                $cadena_sql.=" AND cra_cod>0";
                $cadena_sql.=" AND cra_cod!=999";
              
                if($variable['decano']>0){
                    $cadena_sql.=" AND cra_dep_cod in (
                                    SELECT dec_dep_cod 
                                    FROM peemp 
                                    INNER JOIN acdecanos ON dec_cod=emp_cod 
                                    WHERE emp_nro_iden='".$variable['decano']."')";
                }
                $cadena_sql.=" ORDER BY cra_cod";
            break;
        
        case 'consultar_eventos':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" acd_cod_evento COD_EVENTO,";
                $cadena_sql.=" (acd_cod_evento||' - '||acd_descripcion) EVENTO";
                $cadena_sql.=" FROM acdeseventos";
                $cadena_sql.=" ORDER BY acd_cod_evento";

            break;

        
            default :
                $cadena_sql="";
                break;
    }#Cierre de switch

    return $cadena_sql;
  }

#Cierre de funcion cadena_sql
}

#Cierre de clase
?>