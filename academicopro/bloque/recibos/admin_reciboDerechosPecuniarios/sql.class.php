<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminGenerarReciboDerechosPecuniarios extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

    function __construct($configuracion) {
        $this->configuracion=$configuracion;
    }
  function cadena_sql($tipo, $variable="") {

    switch ($tipo) {

        case 'consultar_derechos_pecuniarios':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" reb_refcod CODIGO, ";
                $cadena_sql.=" reb_refdes DESCRIPCION";
                $cadena_sql.=" FROM acrefban";
                $cadena_sql.=" WHERE reb_refcod IN (5,6,8,10,13)";
                $cadena_sql.=" ORDER BY reb_refdes DESC";
            break;
        
        case 'consultar_derechos_pecuniarios_egr':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" reb_refcod CODIGO, ";
                $cadena_sql.=" reb_refdes DESCRIPCION";
                $cadena_sql.=" FROM acrefban";
                $cadena_sql.=" WHERE reb_refcod IN (5,8,9)";
                $cadena_sql.=" ORDER BY reb_refdes DESC";
            break;
        
        case 'consultar_valor_certificado_notas':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" vlr_cert_notas VALOR, ";
                $cadena_sql.=" vlr_ano ANIO, ";
                $cadena_sql.=" vlr_per PERIODO";
                $cadena_sql.=" FROM acvlrscs , acasperi";
                $cadena_sql.=" WHERE ape_ano= vlr_ano";
                $cadena_sql.=" AND ape_per= vlr_per";
                $cadena_sql.=" AND ape_estado='A'";
            break;
            
        case 'consultar_valor_constancia_estudios':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" vlr_cons_estudios VALOR, ";
                $cadena_sql.=" vlr_ano ANIO, ";
                $cadena_sql.=" vlr_per PERIODO";
                $cadena_sql.=" FROM acvlrscs , acasperi";
                $cadena_sql.=" WHERE ape_ano= vlr_ano";
                $cadena_sql.=" AND ape_per= vlr_per";
                $cadena_sql.=" AND ape_estado='A'";
            break;
            
        case 'consultar_valor_derechos_grado':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" vlr_der_grado VALOR, ";
                $cadena_sql.=" vlr_ano ANIO, ";
                $cadena_sql.=" vlr_per PERIODO";
                $cadena_sql.=" FROM acvlrscs , acasperi";
                $cadena_sql.=" WHERE ape_ano= vlr_ano";
                $cadena_sql.=" AND ape_per= vlr_per";
                $cadena_sql.=" AND ape_estado='A'";
            break;
            
        case 'consultar_valor_duplicado_carnet':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" vlr_dup_carnet VALOR, ";
                $cadena_sql.=" vlr_ano ANIO, ";
                $cadena_sql.=" vlr_per PERIODO";
                $cadena_sql.=" FROM acvlrscs , acasperi";
                $cadena_sql.=" WHERE ape_ano= vlr_ano";
                $cadena_sql.=" AND ape_per= vlr_per";
                $cadena_sql.=" AND ape_estado='A'";
            break;
        
        case 'consultar_valor_duplicado_diploma':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" vlr_dup_diploma VALOR, ";
                $cadena_sql.=" vlr_ano ANIO, ";
                $cadena_sql.=" vlr_per PERIODO";
                $cadena_sql.=" FROM acvlrscs , acasperi";
                $cadena_sql.=" WHERE ape_ano= vlr_ano";
                $cadena_sql.=" AND ape_per= vlr_per";
                $cadena_sql.=" AND ape_estado='A'";
            break;
            
        case 'consultar_valor_curso_vacacional':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" vlr_vacacional VALOR, ";
                $cadena_sql.=" vlr_ano ANIO, ";
                $cadena_sql.=" vlr_per PERIODO";
                $cadena_sql.=" FROM acvlrscs , acasperi";
                $cadena_sql.=" WHERE ape_ano= vlr_ano";
                $cadena_sql.=" AND ape_per= vlr_per";
                $cadena_sql.=" AND ape_estado='A'";
            break;

        case 'consultar_estudiante':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" cra_cod COD_PROYECTO, ";
                $cadena_sql.=" cra_nombre PROYECTO, ";
                $cadena_sql.=" est_estado_est COD_ESTADO";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN accra ON est_cra_cod=cra_cod";
                $cadena_sql.=" WHERE est_estado_est NOT IN ('I')";
                $cadena_sql.=" AND est_cod=".$variable;
            break;
            
            
            case "cantidad_recibos":
                    $cadena_sql="SELECT ";
                    $cadena_sql.=" ema_ano,";
                    $cadena_sql.=" ema_per,";
                    $cadena_sql.=" ema_est_cod,";
                    $cadena_sql.=" aer_secuencia,";
                    $cadena_sql.=" aer_valor,";
                    $cadena_sql.=" TO_CHAR(ema_fecha_ord,'yyyymmdd')";
                    $cadena_sql.=" FROM ACASPERI, ACESTMAT, acrefest";
                    $cadena_sql.=" WHERE ";
                    $cadena_sql.=" APE_ESTADO = 'A'";
                    $cadena_sql.=" AND APE_ANO = EMA_ANO";
                    $cadena_sql.=" AND APE_PER = EMA_PER";
                    $cadena_sql.=" AND EMA_EST_COD =".$variable['codEstudiante'];
                    $cadena_sql.=" AND ema_secuencia=aer_secuencia";
                    $cadena_sql.=" AND ema_ano=aer_ano";
                    $cadena_sql.=" AND ema_pago='N'";
                    $cadena_sql.=" AND ema_estado='A'";
                    $cadena_sql.=" AND aer_refcod=".$variable['tipoRecibo'];
                break;
			
            case "inactivarReciboVencidoPecuniario":
                    $cadena_sql="UPDATE ";
                    $cadena_sql.="ACESTMAT "; 
                    $cadena_sql.="SET "; 
                    $cadena_sql.="ema_estado='I' ";
                    $cadena_sql.="WHERE ";
                    $cadena_sql.="ema_est_cod=".$variable['codEstudiante']." ";
                    $cadena_sql.="AND ema_ano=".$variable['ano']." ";
                    $cadena_sql.="AND ema_per=".$variable['periodo']." ";
                    $cadena_sql.="AND ema_secuencia=".$variable['secuencia']." ";
                    $cadena_sql.="AND ema_pago<>'S'";
                break;
			
            case 'consultar_codigo_egresado_por_id':
                $cadena_sql=" SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO";
                $cadena_sql.=" FROM acest ";
                $cadena_sql.=" INNER JOIN accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_nro_iden= ".$variable;
                $cadena_sql.=" AND est_estado_est='E' ";
                $cadena_sql.=" ORDER BY CODIGO desc";

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