<?php
/**
 * SQL admin_consultarRecibosPecuniariosFuncionario
 *
 * Esta clase se encarga de crear las sentencias sql del bloque admin_consultarRecibosPecuniariosFuncionario
 *
 * @package recibos
 * @subpackage admin_consultarRecibosPecuniariosFuncionario
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 27/11/2014
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
 * Clase sql_adminConsultarRecibosPecuniariosFuncionario
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque admin_consultarRecibosPecuniariosFuncionario
 *
 * @package recibos
 * @subpackage Admin
 */
class sql_adminConsultarRecibosPecuniariosFuncionario extends sql {

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
             
            case 'datos_estudiante':

                $cadena_sql="SELECT";
                $cadena_sql.=" est_cod          CODIGO,";
                $cadena_sql.=" est_nro_iden     IDENTIFICACION,";
                $cadena_sql.=" est_nombre       NOMBRE,";
                $cadena_sql.=" est_cra_cod      COD_PROYECTO,";
                $cadena_sql.=" cra_nombre       PROYECTO,";
                $cadena_sql.=" est_pen_nro      PLANESTUDIOS,";
                $cadena_sql.=" estado_nombre   ESTADO,";
                $cadena_sql.=" trim(est_ind_cred)     MODALIDAD,";
                $cadena_sql.=" dep_nombre       FACULTAD";
                $cadena_sql.=" FROM";
                $cadena_sql.=" acest";
                $cadena_sql.=" INNER JOIN accra ON cra_cod=est_cra_cod";
                $cadena_sql.=" INNER JOIN gedep ON cra_dep_cod=dep_cod";
                $cadena_sql.=" INNER JOIN acestado ON est_estado_est=estado_cod";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" est_cod=".$variable;
                
            break;

            //Oracle
              case 'consultar_recibos_estudiante':
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
                $cadena_sql.=" FROM Acestmat";
                $cadena_sql.=" LEFT OUTER JOIN acrecban ON ema_secuencia=rba_secuencia AND rba_cod=ema_est_cod";
                $cadena_sql.=" LEFT OUTER JOIN acrefest ON ema_secuencia=aer_secuencia AND ema_ano=aer_ano ";
                $cadena_sql.=" LEFT OUTER JOIN acrefban ON aer_refcod=reb_refcod";
                $cadena_sql.=" WHERE Ema_Est_Cod =".$variable['codEstudiante'];
                $cadena_sql.=" AND aer_refcod in (".$variable['referencias'].")";
                $cadena_sql.=" ORDER BY 1 desc, 2 DESC, 9 desc";
                break;

             case 'consultar_codigo_estudiante_por_id':
                $cadena_sql=" SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO";
                $cadena_sql.=" FROM acest ";
                $cadena_sql.=" INNER JOIN accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_nro_iden= ".$variable;
                $cadena_sql.=" ORDER BY CODIGO desc";

                break;
           
            case 'consultar_codigo_estudiante_por_nombre':
                $cadena_sql=" SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO,";
                $cadena_sql.=" est_nombre NOMBRE";
                $cadena_sql.=" FROM acest ";
                $cadena_sql.=" INNER JOIN accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_nombre like ".$variable." ";
                $cadena_sql.=" ORDER BY CODIGO desc";

                break;
            
            case 'consultar_entrega_solicitud':
                $cadena_sql=" SELECT rpe_anio       ANIO,";
                $cadena_sql.=" rpe_per              PERIODO,";
                $cadena_sql.=" rpe_secuencia        SECUENCIA,";
                $cadena_sql.=" rpe_est_cod          COD_ESTUDIANTE,";
                $cadena_sql.=" rpe_valor            VALOR,";
                $cadena_sql.=" rpe_cod_concepto     COD_CONCEPTO,";
                $cadena_sql.=" rpe_concepto         CONCEPTO,";
                $cadena_sql.=" rpe_entregado        ENTREGADO,";
                $cadena_sql.=" rpe_fecha_entregado  FECHA_ENTREGADO,";
                $cadena_sql.=" rpe_fecha_registro   FECHA,";
                $cadena_sql.=" rpe_estado           ESTADO";
                $cadena_sql.=" FROM sga_recibos_der_pecuniarios ";
                $cadena_sql.=" WHERE rpe_anio =".$variable['ANIO']." ";
                $cadena_sql.=" AND rpe_per =".$variable['PERIODO']." ";
                $cadena_sql.=" AND rpe_secuencia =".$variable['SECUENCIA']." ";
                $cadena_sql.=" AND rpe_est_cod =".$variable['COD_ESTUDIANTE']." ";
                $cadena_sql.=" ORDER BY rpe_fecha_registro desc";

                break;
            	
            default :
                $cadena_sql='';
                break;
        }
        return $cadena_sql;
    }
}
?>
