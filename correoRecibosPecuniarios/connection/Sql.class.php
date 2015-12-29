<?php
class Sql {
	
	var $cadenaSql;
	
	function __construct() {
	}
	
	function sql($opcion, $variable) {
		
		switch ($opcion) {
			
                    case 'consultar_recibo':
                                $cadenaSql=" SELECT ema_ano        ANIO,";
                                $cadenaSql.=" ema_per              PERIODO,";
                                $cadenaSql.=" TO_CHAR(ema_fecha,'DD/mm/YYYY')    FECHA,";
                                $cadenaSql.=" aer_valor            VALOR,";
                                $cadenaSql.=" aer_refcod           COD_CONCEPTO,";
                                $cadenaSql.=" reb_refdes           CONCEPTO,";
                                $cadenaSql.=" TO_CHAR(ema_fecha_ord,'DD/mm/YYYY')    FECHA_ORD,";
                                $cadenaSql.=" (CASE WHEN ema_pago='S' THEN 'SI' WHEN ema_pago='N' THEN 'NO' END) REALIZO_PAGO,";
                                $cadenaSql.=" ema_secuencia        SECUENCIA,";
                                $cadenaSql.=" CASE WHEN rba_dia IS NOT NULL THEN rba_dia||'/'||rba_mes||'/'||rba_ano ELSE '' END FECHA_PAGO,";
                                $cadenaSql.=" rba_valor            VALOR_PAGADO,";
                                $cadenaSql.=" Ema_Estado           ESTADO,";
                                $cadenaSql.=" Ema_obs              OBSERVACIONES,";
                                $cadenaSql.=" est_cra_cod      COD_PROYECTO,";
                                $cadenaSql.=" cra_nombre       PROYECTO,";
                                $cadenaSql.=" dep_cod       	COD_FACULTAD,";
                                $cadenaSql.=" dep_nombre       FACULTAD, ";
                                $cadenaSql.=" est_cod      COD_ESTUDIANTE, ";
                                $cadenaSql.=" est_nombre   ESTUDIANTE";
                                $cadenaSql.=" FROM Acestmat";
                                $cadenaSql.=" INNER JOIN acest ON ema_est_cod=est_cod AND ema_cra_cod=est_cra_cod";
                                $cadenaSql.=" INNER JOIN accra ON cra_cod=est_cra_cod";
                                $cadenaSql.=" LEFT OUTER JOIN gedep ON cra_dep_cod=dep_cod";
                                $cadenaSql.=" LEFT OUTER JOIN acrecban ON ema_secuencia=rba_secuencia AND rba_cod=ema_est_cod";
                                $cadenaSql.=" LEFT OUTER JOIN acrefest ON ema_secuencia=aer_secuencia AND ema_ano=aer_ano ";
                                $cadenaSql.=" LEFT OUTER JOIN acrefban ON aer_refcod=reb_refcod";
                                $cadenaSql.=" WHERE ema_ano =".$variable['anioRecibo'];
                                $cadenaSql.=" AND ema_secuencia =".$variable['secuencia'];
                                $cadenaSql.=" AND aer_refcod in (5,6,8,9,10,13)";
                                $cadenaSql.=" ORDER BY 1 desc, 2 DESC, 9 desc";
                                $this->cadenaSql=$cadenaSql;
				break;
                
                    case 'consultar_correo_proyecto':
                                $cadenaSql=" SELECT cra_email  CORREO,";
                                $cadenaSql.=" cra_cod          COD_PROYECTO";
                                $cadenaSql.=" FROM accra";
                                $cadenaSql.=" WHERE cra_cod =".$variable;
                                $this->cadenaSql=$cadenaSql;
				break;
                
                    case 'consultar_correo_dependencia':
                                $cadenaSql=" SELECT dep_email  CORREO,";
                                $cadenaSql.=" dep_cod          COD_DEPENDENCIA";
                                $cadenaSql.=" FROM gedep";
                                $cadenaSql.=" WHERE dep_cod =".$variable;
                                $this->cadenaSql=$cadenaSql;
				break;
                                               
				
		}
		error_log($this->cadenaSql);
		return true;
	}
	
	function getCadenaSql(){
		return $this->cadenaSql;
	}
}
?>
