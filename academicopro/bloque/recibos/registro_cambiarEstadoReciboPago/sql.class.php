<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroCambiarEstadoReciboPago extends sql {
  private $configuracion;
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
  }
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {
            
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;

            case 'consultar_recibo':
                $cadena_sql=" SELECT ema_est_cod    COD_ESTUDIANTE,";
                $cadena_sql.=" ema_ano    ANIO,";
                $cadena_sql.=" ema_per          PERIODO,";
                $cadena_sql.=" ema_valor        VALOR_ORD,";
                $cadena_sql.=" TO_CHAR(ema_fecha_ord,'DD/mm/YYYY')    FECHA_ORD,";
                $cadena_sql.=" ema_ext          VALOR_EXTRA,";
                $cadena_sql.=" TO_CHAR(ema_fecha_ext,'DD/MM/YYYY')    FECHA_EXTRA,";
                $cadena_sql.=" ema_cuota        CUOTA,";
                $cadena_sql.=" (CASE WHEN ema_pago='S' THEN 'SI' WHEN ema_pago='N' THEN 'NO' END) REALIZO_PAGO,";
                $cadena_sql.=" ema_secuencia    SECUENCIA,";
                $cadena_sql.=" CASE WHEN rba_dia IS NOT NULL THEN rba_dia||'/'||rba_mes||'/'||rba_ano ELSE '' END FECHA_PAGO,";
                $cadena_sql.=" rba_valor        VALOR_PAGADO,";
                $cadena_sql.=" Ema_Estado       ESTADO,";
                $cadena_sql.=" Ema_obs          OBSERVACIONES";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."Acestmat";
                $cadena_sql.=" LEFT OUTER JOIN ".$this->configuracion['esquema_academico']."acrecban ON ema_secuencia=rba_secuencia AND rba_cod=ema_est_cod";
                $cadena_sql.=" WHERE ema_secuencia =".$variable['secuencia'];
                $cadena_sql.=" AND ema_ano =".$variable['anio'];
                $cadena_sql.=" AND ema_est_cod =".$variable['codEstudiante'];
                $cadena_sql.=" ORDER BY 1 desc, 2 DESC, 7 asc";
                break;
         
            case 'actualizar_estado_recibo':
                $cadena_sql="UPDATE acestmat ";
                $cadena_sql.=" SET ema_estado='".$variable['nvo_estado']."'";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ema_secuencia =".$variable['secuencia'];
                $cadena_sql.=" AND ema_ano =".$variable['anio'];
                break;
            
            default :
                $cadena_sql='';
                break;
     
        }
        //echo $cadena_sql;exit;
        return $cadena_sql;
    }


}
?>