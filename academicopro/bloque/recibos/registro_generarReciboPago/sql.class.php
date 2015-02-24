<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroGenerarReciboPago extends sql {
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
            
            
            case 'adicionar_recibo_pago':

                    $cadena_sql="INSERT INTO acestmat (";
                    $cadena_sql.=" SELECT est_cod,";
                    $cadena_sql.=" est_cra_cod, ";
                    $cadena_sql.=" ".$variable['valorOrdinario']." EMA_VALOR,";
                    $cadena_sql.=" ".$variable['valorExtraordinario']."  EMA_EXT, ";
                    $cadena_sql.=" ape_ano EMA_ANO, ";
                    $cadena_sql.=" ape_per EMA_PER, ";
                    $cadena_sql.=" SYSDATE EMA_FECHA, ";
                    $cadena_sql.=" 'A' EMA_ESTADO, ";
                    $cadena_sql.=" SEQ_MATRICULA.nextval EMA_SECUENCIA, ";
                    $cadena_sql.=" ".$variable['cuota']."  EMA_CUOTA, ";
                    $cadena_sql.=" to_date ('".$variable['fechaOrdinaria']." ','yyyy/mm/dd') EMA_FECHA_ORD,";
                    $cadena_sql.=" to_date ('".$variable['fechaExtraordinaria']." ','yyyy/mm/dd') EMA_FECHA_EXT,";
                    $cadena_sql.=" 1 EMA_IMP_RECIBO, ";
                    $cadena_sql.=" 'N' EMA_PAGO, ";
                    $cadena_sql.=" ".$variable['anioRecibo']."  EMA_ANO_PAGO, ";
                    $cadena_sql.=" ".$variable['perRecibo']."  EMA_PER_PAGO, ";
                    $cadena_sql.=" '".$variable['observacion']."' EMA_OBS, ";
                    $cadena_sql.=" 'N' EMA_ENVIADO_EMAIL";
                    $cadena_sql.=" FROM acasperi, acest";
                    $cadena_sql.=" WHERE ape_estado = 'A'";
                    $cadena_sql.=" AND est_cod = ".$variable['codEstudiante']." ) ";
                    
                break;
            
            case 'adicionar_referencia_matricula':

                    $cadena_sql="INSERT INTO acrefest (";
                    $cadena_sql.=" SELECT EMA_ANO,";
                    $cadena_sql.=" EMA_SECUENCIA,";
                    $cadena_sql.=" 23,";
                    $cadena_sql.=" 1,";
                    $cadena_sql.=" EMA_VALOR";
                    $cadena_sql.=" FROM ACASPERI, ACESTMAT";
                    $cadena_sql.=" WHERE APE_ESTADO = 'A'";
                    $cadena_sql.=" AND APE_ANO = EMA_ANO";
                    $cadena_sql.=" AND APE_PER = EMA_PER";
                    $cadena_sql.=" AND EMA_EST_COD in (".$variable['codEstudiante'].") ";
                    $cadena_sql.=" and EMA_SECUENCIA NOT IN (SELECT AER_SECUENCIA ";
                    $cadena_sql.=" FROM ACREFEST";
                    $cadena_sql.=" WHERE ACESTMAT.EMA_ANO = AER_ANO";
                    $cadena_sql.=" AND ACESTMAT.EMA_SECUENCIA = AER_SECUENCIA";
                    $cadena_sql.=" AND AER_REFCOD = 1)) ";
                    
                break;
            
            case 'adicionar_referencia_seguro':

                    $cadena_sql="INSERT INTO ACREFEST";
                    $cadena_sql.=" (SELECT EMA_ANO,";
                    $cadena_sql.=" EMA_SECUENCIA,";
                    $cadena_sql.=" 23,";
                    $cadena_sql.=" 2,";
                    $cadena_sql.=" VLR_SEGURO";
                    $cadena_sql.=" FROM ACASPERI, ACVLRSCS, ACESTMAT";
                    $cadena_sql.=" WHERE APE_ESTADO = 'A'";
                    $cadena_sql.=" AND EMA_EST_COD in (".$variable['codEstudiante'].")";
                    $cadena_sql.=" AND APE_ANO = VLR_ANO";
                    $cadena_sql.=" AND APE_PER = VLR_PER";
                    $cadena_sql.=" AND APE_ANO = EMA_ANO";
                    $cadena_sql.=" AND APE_PER = EMA_PER";
                    $cadena_sql.=" AND EMA_CUOTA = 1";
                    $cadena_sql.=" AND EMA_ANO_PAGO=APE_ANO";
                    $cadena_sql.=" AND EMA_PER_PAGO=APE_PER";
                    $cadena_sql.=" AND EMA_SECUENCIA NOT IN (SELECT AER_SECUENCIA ";
                    $cadena_sql.=" FROM ACREFEST";
                    $cadena_sql.=" WHERE ACESTMAT.EMA_ANO = AER_ANO";
                    $cadena_sql.=" AND ACESTMAT.EMA_SECUENCIA = AER_SECUENCIA";
                    $cadena_sql.=" AND AER_REFCOD = 2))";
                    
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