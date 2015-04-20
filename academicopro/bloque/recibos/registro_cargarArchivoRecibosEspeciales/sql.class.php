<?php
/**
 * SQL registro_cargarArchivoRecibosEspeciales
 *
 * Esta clase se encarga de crear las sentencias sql del bloque registro_cargarArchivoRecibosEspeciales
 *
* @package recibos
 * @subpackage registro_cargarArchivoRecibosEspeciales
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 12/11/2014
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la funcion sql.class.php
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");
/**
 * Clase sql_registroCargarArchivoRecibosEspeciales
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque registro_cargarArchivoRecibosEspeciales
 *
 * @package recibos
 * @subpackage Admin
 */
class sql_registroCargarArchivoRecibosEspeciales extends sql {

  public $configuracion;

  function __construct($configuracion){
    $this->configuracion=$configuracion;
  }

  /**
     * Funcion que crea la cadena sql
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param string $tipo variable que contiene la opcion para seleccionar la cadena sql que se necesita crear
     * @param array $variable Esta variable puede ser de cualquier tipo array, string, int, double y se encarga de completar la sentencia sql
     * @return string Cadena sql creada
     */
    function cadena_sql($tipo,$variable="") {
        switch($tipo) {


              //Oracle
             
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;

            case 'proyecto_estudiante':

                $cadena_sql=" SELECT ";
                $cadena_sql.=" est_cra_cod, ";
                $cadena_sql.=" est_cod ";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" WHERE est_cod=".$variable;
                break;
            

            case 'adicionar_recibo_pago':

                    $cadena_sql="INSERT INTO acestmat (";
                    $cadena_sql.=" SELECT est_cod,";
                    $cadena_sql.=" est_cra_cod, ";
                    $cadena_sql.=" ".$variable['valorOrdinario']." EMA_VALOR,";
                    $cadena_sql.=" ".$variable['valorExtraordinario']."  EMA_EXT, ";
                    $cadena_sql.=" ape_ano EMA_ANO, ";
                    $cadena_sql.=" ape_per EMA_PER, ";
                    $cadena_sql.=" current_timestamp EMA_FECHA, ";
                    $cadena_sql.=" 'A' EMA_ESTADO, ";
                    $cadena_sql.=" SEQ_MATRICULA.nextval EMA_SECUENCIA, ";
                    $cadena_sql.=" ".$variable['cuota']."  EMA_CUOTA, ";
                    $cadena_sql.=" to_date ('".$variable['fechaOrdinaria']." ','dd/mm/yyyy') EMA_FECHA_ORD,";
                    $cadena_sql.=" to_date ('".$variable['fechaExtraordinaria']." ','dd/mm/yyyy') EMA_FECHA_EXT,";
                    $cadena_sql.=" 1 EMA_IMP_RECIBO, ";
                    $cadena_sql.=" 'N' EMA_PAGO, ";
                    $cadena_sql.=" ".$variable['anioRecibo']."  EMA_ANO_PAGO, ";
                    $cadena_sql.=" ".$variable['perRecibo']."  EMA_PER_PAGO, ";
                    $cadena_sql.=" '".$variable['observaciones']."' EMA_OBS, ";
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

        }
        return $cadena_sql;
    }
}
?>
