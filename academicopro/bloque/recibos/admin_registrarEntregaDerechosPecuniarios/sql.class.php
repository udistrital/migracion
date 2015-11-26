<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
 * * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 28/11/2014
 
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminRegistrarEntregaDerechosPecuniarios extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

    function __construct($configuracion) {
        $this->configuracion=$configuracion;
    }
  function cadena_sql($tipo, $variable="") {

    switch ($tipo) {

                    
        case 'consultar_estudiante':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" cra_cod COD_PROYECTO, ";
                $cadena_sql.=" cra_nombre PROYECTO, ";
                $cadena_sql.=" est_estado_est COD_ESTADO";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN accra ON est_cra_cod=cra_cod";
                $cadena_sql.=" WHERE est_estado_est NOT IN ('I','E')";
                $cadena_sql.=" AND est_cod=".$variable;
            break;
            
        case 'consultar_recibo_derecho_pecuniario':
                $cadena_sql=" SELECT ema_ano        ANIO,";
                $cadena_sql.=" ema_per              PERIODO,";
                $cadena_sql.=" TO_CHAR(ema_fecha,'DD/mm/YYYY')    FECHA,";
                $cadena_sql.=" aer_valor            VALOR,";
                $cadena_sql.=" aer_refcod           COD_CONCEPTO,";
                $cadena_sql.=" reb_refdes           CONCEPTO,";
                $cadena_sql.=" TO_CHAR(ema_fecha_ord,'DD/mm/YYYY')    FECHA_ORD,";
                $cadena_sql.=" (CASE WHEN ema_pago='S' THEN 'SI' WHEN ema_pago='N' THEN 'NO' END) REALIZO_PAGO,";
                $cadena_sql.=" ema_secuencia        SECUENCIA,";
                $cadena_sql.=" Ema_Est_Cod          COD_ESTUDIANTE,";
                $cadena_sql.=" CASE WHEN rba_dia IS NOT NULL THEN rba_dia||'/'||rba_mes||'/'||rba_ano ELSE '' END FECHA_PAGO,";
                $cadena_sql.=" rba_valor            VALOR_PAGADO,";
                $cadena_sql.=" Ema_Estado           ESTADO,";
                $cadena_sql.=" Ema_obs              OBSERVACIONES";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."Acestmat";
                $cadena_sql.=" LEFT OUTER JOIN acrecban ON ema_secuencia=rba_secuencia AND rba_cod=ema_est_cod";
                $cadena_sql.=" LEFT OUTER JOIN acrefest ON ema_secuencia=aer_secuencia AND ema_ano=aer_ano ";
                $cadena_sql.=" LEFT OUTER JOIN acrefban ON aer_refcod=reb_refcod";
                $cadena_sql.=" WHERE Ema_Est_Cod =".$variable['codEstudiante'];
                $cadena_sql.=" AND ema_ano =".$variable['anioRecibo'];
                $cadena_sql.=" AND ema_per =".$variable['periodoRecibo'];
                $cadena_sql.=" AND ema_secuencia =".$variable['secuencia'];
                $cadena_sql.=" AND aer_refcod in (5,6,8,9,10,13)";
                $cadena_sql.=" ORDER BY 1 desc, 2 DESC, 9 desc";
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