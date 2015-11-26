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
 * Clase sql_adminPagoEnLinea
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque admin_consultarHistoricoRecibos
 *
 * @package recibos
 * @subpackage Admin
 */
class sql_adminPagoEnLinea extends sql {

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


            
            case 'consultar_recibo_estudiante':
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
                $cadena_sql.=" WHERE Ema_Est_Cod =".$variable['cod_estudiante'];
                $cadena_sql.=" AND Ema_secuencia=".$variable['factura'];
                
                $cadena_sql.=" ORDER BY 1 desc, 2 DESC, 7 asc";
      
                break;
            
             
            case 'datos_estudiante':

                $cadena_sql="SELECT";
                $cadena_sql.=" est_cod          CODIGO,";
                $cadena_sql.=" case when est_tipo_iden='C' then 'CC'";
                $cadena_sql.=" when est_tipo_iden='T' then 'TI'";
                $cadena_sql.=" when est_tipo_iden='E' then 'CE'";
                $cadena_sql.=" when est_tipo_iden='P' then 'PP'";
                $cadena_sql.=" when est_tipo_iden='NIT' then 'NIT'";
                $cadena_sql.=" else 'CC' end  TIPO_IDENTIFICACION,";
                $cadena_sql.=" est_nro_iden     IDENTIFICACION,";
                $cadena_sql.=" est_nombre       NOMBRE,";
                $cadena_sql.=" est_cra_cod      COD_PROYECTO,";
                $cadena_sql.=" cra_nombre       PROYECTO,";
                $cadena_sql.=" est_pen_nro      PLANESTUDIOS,";
                $cadena_sql.=" estado_nombre    ESTADO,";
                $cadena_sql.=" trim(est_ind_cred)     MODALIDAD,";
                $cadena_sql.=" dep_nombre       FACULTAD";
                $cadena_sql.=" FROM";
                $cadena_sql.=" ".$this->configuracion['esquema_academico']."acest";
                $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_academico']."accra ON cra_cod=est_cra_cod";
                $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_general']."gedep ON cra_dep_cod=dep_cod";
                $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_academico']."acestado ON est_estado_est=estado_cod";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" est_cod=".$variable;
                
            break;
        
            case "valorSeguro":
                $cadena_sql=" SELECT ";
                $cadena_sql.=" vlr_seguro ";
                $cadena_sql.=" FROM ";
                $cadena_sql.=" acvlrscs ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" vlr_ano= ".$variable['anio'];
                $cadena_sql.=" AND  vlr_per=".$variable['periodo'];
		break;     
               
            case "conceptosRecibo":
			$cadena_sql="SELECT DISTINCT ";
                        $cadena_sql.="IDENTIFICACION ,";
			$cadena_sql.="CODIGO, ";
			$cadena_sql.="SECUENCIA, ";
			$cadena_sql.="CUOTA, ";
			$cadena_sql.="ANO_PAGO, ";
			$cadena_sql.="PERIODO_PAGO, ";
			$cadena_sql.="CONCEPTO, ";
			$cadena_sql.="DESCRIPCION, ";
			$cadena_sql.="VALOR, ";
			$cadena_sql.="OBSERVACION ";
			$cadena_sql.="FROM v_recibos_pse ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="SECUENCIA=".$variable['SECUENCIA']." ";
			$cadena_sql.="AND ";
			$cadena_sql.="ANO_PAGO=".$variable['ANIO']." ";	
                        $cadena_sql.="ORDER BY CONCEPTO ASC ";	
                        
			break;
			
        }
        return $cadena_sql;
    }
}
?>
