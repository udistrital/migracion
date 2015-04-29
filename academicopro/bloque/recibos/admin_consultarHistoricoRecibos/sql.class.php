
<?php
/**
 * SQL admin_consultarHistoricoRecibos
 *
 * Esta clase se encarga de crear las sentencias sql del bloque admin_consultarHistoricoRecibos
 *
* @package recibos
 * @subpackage admin_consultarHistoricoRecibos
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 25/06/2013
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
 * Clase sql_adminConsultarHistoricoRecibos
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque admin_consultarHistoricoRecibos
 *
 * @package recibos
 * @subpackage Admin
 */
class sql_adminConsultarHistoricoRecibos extends sql {

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
                $cadena_sql=" SELECT ema_ano    ANIO,";
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
                $cadena_sql.=" FROM Acestmat";
                $cadena_sql.=" LEFT OUTER JOIN acrecban ON ema_secuencia=rba_secuencia AND rba_cod=ema_est_cod";
                $cadena_sql.=" WHERE Ema_Est_Cod =".$variable;
                $cadena_sql.=" UNION";
                $cadena_sql.=" SELECT AMA_ANO,";
                $cadena_sql.=" AMA_PER,";
                $cadena_sql.=" AMA_VALOR,";
                $cadena_sql.=" to_char(AMA_FECHA_ORD,'DD/mm/YYYY')    FECHA_ORD,";
                $cadena_sql.=" AMA_EXT,";
                $cadena_sql.=" to_char(AMA_FECHA_EXT,'DD/MM/YYYY')    FECHA_EXTRA,";
                $cadena_sql.=" (CASE WHEN AMA_CUOTA is null THEN 1 ELSE AMA_CUOTA END),";
                $cadena_sql.=" (CASE WHEN AMA_PAGO ='S' THEN 'SI' WHEN AMA_PAGO ='N' THEN 'NO' END),";
                $cadena_sql.=" AMA_SECUENCIA,";
                $cadena_sql.=" (RBA_DIA||'/'||lpad( CAST(RBA_MES AS TEXT),2,'0')||'/'||RBA_ANO),";
                $cadena_sql.=" AMA_VALOR,";
                $cadena_sql.=" AMA_ESTADO,";
                $cadena_sql.=" AMA_OBS";
                $cadena_sql.=" FROM ACADMMAT";
                $cadena_sql.=" INNER JOIN ACESTADM on AMA_ANO = EAD_ASP_ANO and AMA_PER = EAD_ASP_PER and AMA_ASP_CRED = EAD_ASP_CRED";
                $cadena_sql.=" LEFT OUTER JOIN ACRECBAN on AMA_ANO = RBA_ANO and AMA_SECUENCIA = RBA_SECUENCIA";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" EAD_COD = ".$variable;
                $cadena_sql.=" ORDER BY 1 desc, 2 DESC, 7 asc";
                  
//                $cadena_sql=" SELECT ema_ano    ANIO,";
//                $cadena_sql.=" ema_per          PERIODO,";
//                $cadena_sql.=" ema_valor        VALOR_ORD,";
//                $cadena_sql.=" TO_CHAR(ema_fecha_ord,'DD/mm/YYYY')    FECHA_ORD,";
//                $cadena_sql.=" ema_ext          VALOR_EXTRA,";
//                $cadena_sql.=" TO_CHAR(ema_fecha_ext,'DD/MM/YYYY')    FECHA_EXTRA,";
//                $cadena_sql.=" ema_cuota        CUOTA,";
//                $cadena_sql.=" (CASE WHEN ema_pago='S' THEN 'SI' WHEN ema_pago='N' THEN 'NO' END) REALIZO_PAGO,";
//                $cadena_sql.=" ema_secuencia    SECUENCIA,";
//                $cadena_sql.=" CASE WHEN rba_dia IS NOT NULL THEN rba_dia||'/'||rba_mes||'/'||rba_ano ELSE '' END FECHA_PAGO,";
//                $cadena_sql.=" rba_valor        VALOR_PAGADO,";
//                $cadena_sql.=" Ema_Estado       ESTADO,";
//                $cadena_sql.=" Ema_obs          OBSERVACIONES";
//                $cadena_sql.=" FROM Acestmat";
//                $cadena_sql.=" LEFT OUTER JOIN acrecban ON ema_secuencia=rba_secuencia AND rba_cod=ema_est_cod";
//                $cadena_sql.=" WHERE Ema_Est_Cod =".$variable;
//                $cadena_sql.=" ORDER BY ema_ano DESC,";
//                $cadena_sql.=" ema_per DESC,";
//                $cadena_sql.=" ema_cuota ";
                break;

             case 'consultar_conceptos_recibo':
                $cadena_sql=" SELECT aer_ano, ";
                $cadena_sql.=" aer_secuencia,";
                $cadena_sql.=" aer_bancod, ";
                $cadena_sql.=" aer_refcod, ";
                $cadena_sql.=" aer_valor";
                $cadena_sql.=" FROM acrefest ";
                $cadena_sql.=" WHERE aer_secuencia =".$variable['secuencia'];
                $cadena_sql.=" AND aer_ano=".$variable['anio'];
                 break;
            
             case 'consultar_valor_seguro':
                $cadena_sql=" SELECT vlr_seguro ";
                $cadena_sql.=" FROM acvlrscs ";
                $cadena_sql.=" WHERE vlr_ano= ".$variable['anio'];
                $cadena_sql.=" AND vlr_per=".$variable['periodo'];
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
            

        }
        return $cadena_sql;
    }
}
?>
