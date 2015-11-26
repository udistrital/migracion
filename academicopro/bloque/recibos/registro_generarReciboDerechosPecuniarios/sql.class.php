<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroGenerarReciboDerechosPecuniarios extends sql {
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
                $cadena_sql.=" WHERE est_estado_est NOT IN ('I')";
                $cadena_sql.=" AND est_cod=".$variable;
            break;
            
            case 'consultar_festivo':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" 'S' FESTIVO ";
                $cadena_sql.=" FROM pe_festivos";
                $cadena_sql.=" WHERE TO_CHAR(FECHA_FESTIVO,'YYYY-mm-DD') ='".$variable."'";
            break;
            
            
            case 'adicionar_recibo_pago':

                    $cadena_sql="INSERT INTO acestmat (";
                    $cadena_sql.="ema_est_cod, ";
                    $cadena_sql.="ema_cra_cod, ";
                    $cadena_sql.="ema_valor, ";
                    $cadena_sql.="ema_ext, ";
                    $cadena_sql.="ema_ano, ";
                    $cadena_sql.="ema_per, ";
                    $cadena_sql.="ema_cuota, ";
                    $cadena_sql.="ema_fecha_ord, ";
                    $cadena_sql.="ema_fecha_ext, ";
                    $cadena_sql.="ema_fecha, ";
                    $cadena_sql.="ema_estado, ";
                    $cadena_sql.="ema_secuencia, ";	
                    $cadena_sql.="ema_ano_pago, ";	
                    $cadena_sql.="ema_per_pago, ";
                    $cadena_sql.="ema_pago, ";														
                    $cadena_sql.="ema_imp_recibo, ";	
                    $cadena_sql.="ema_enviado_email ";	
                    $cadena_sql.=") ";
                    $cadena_sql.="VALUES (";
                    $cadena_sql.=" ".$variable['codEstudiante'].",";
                    $cadena_sql.=" ".$variable['codProyecto'].", ";
                    $cadena_sql.=" ".$variable['valorOrdinario']." ,";
                    $cadena_sql.=" ".$variable['valorExtraordinario']."  , ";
                    $cadena_sql.=" ".$variable['anio'].", ";
                    $cadena_sql.=" ".$variable['periodo'].", ";
                    $cadena_sql.=" ".$variable['cuota']."  , ";
                    $cadena_sql.=" TO_DATE('".$variable['fechaOrdinaria']." ','yyyy/mm/dd') ,";
                    $cadena_sql.=" TO_DATE('".$variable['fechaExtraordinaria']." ','yyyy/mm/dd') ,";
                    $cadena_sql.=" current_timestamp , ";
                    $cadena_sql.=" 'A' , ";
                    $cadena_sql.=" ".$variable['secuencia'].", ";
                    $cadena_sql.=" ".$variable['anioRecibo']."  , ";
                    $cadena_sql.=" ".$variable['perRecibo']."  , ";
                    $cadena_sql.=" 'N' , ";
                    $cadena_sql.=" 2,";
                    $cadena_sql.=" 'N' )";
                    
                break;
            
            case 'adicionar_referencia_matricula':

                    $cadena_sql="INSERT INTO acrefest ";
                    $cadena_sql.="(";
                    $cadena_sql.="aer_ano, ";
                    $cadena_sql.="aer_secuencia, ";
                    $cadena_sql.="aer_bancod, ";
                    $cadena_sql.="aer_refcod, ";
                    $cadena_sql.="aer_valor ";
                    $cadena_sql.=") ";
                    $cadena_sql.="VALUES (";
                    $cadena_sql.=" ".$variable['anioRecibo'].",";
                    $cadena_sql.=" ".$variable['secuencia'].",";
                    $cadena_sql.=" 23,";
                    $cadena_sql.=" 1,";
                    $cadena_sql.=" 0 )";
                    
                break;
            
            case 'adicionar_referencia_derecho_pecuniario':

                    $cadena_sql="INSERT INTO acrefest ";
                    $cadena_sql.="(";
                    $cadena_sql.="aer_ano, ";
                    $cadena_sql.="aer_secuencia, ";
                    $cadena_sql.="aer_bancod, ";
                    $cadena_sql.="aer_refcod, ";
                    $cadena_sql.="aer_valor ";
                    $cadena_sql.=") ";
                    $cadena_sql.="VALUES (";
                    $cadena_sql.=" ".$variable['anioRecibo'].",";
                    $cadena_sql.=" ".$variable['secuencia'].",";
                    $cadena_sql.=" 23,";
                    $cadena_sql.=" ".$variable['codConcepto'].",";
                    $cadena_sql.=" ".$variable['valorReferencia'].")";
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

            case "secuencia":
                    $cadena_sql="SELECT ";
                    $cadena_sql.="nextval('SEQ_MATRICULA') EMA_SECUENCIA ";
                break;
			
            case "cantidad_recibos":
                    $cadena_sql="SELECT ";
                    $cadena_sql.=" ema_ano,";
                    $cadena_sql.=" ema_per,";
                    $cadena_sql.=" ema_est_cod,";
                    $cadena_sql.=" aer_secuencia,";
                    $cadena_sql.=" aer_valor";
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
			
            default :
                $cadena_sql='';
                break;
     
        }
        //echo $cadena_sql;exit;
        return $cadena_sql;
    }


}
?>