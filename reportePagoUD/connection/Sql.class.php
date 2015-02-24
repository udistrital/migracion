<?php
class Sql {
	
	var $cadenaSql;
	
	function __construct() {
	}
	
	function sql($opcion, $transaccion) {
		
		switch ($opcion) {
			
			case "insertarTransaccion" :
				
				$cadenaSql = "INSERT INTO ";
				$cadenaSql .= "pago ";
				$cadenaSql .= "(";
				$cadenaSql .= "banco, ";
				$cadenaSql .= "cus, ";
				$cadenaSql .= "descripcion, ";
				$cadenaSql .= "estado, ";
				$cadenaSql .= "fechaFin, ";
				$cadenaSql .= "fechaInicio, ";
				$cadenaSql .= "identificacionUsuario, ";
				$cadenaSql .= "referencia, ";
				$cadenaSql .= "tipoUsuario, ";
                                $cadenaSql .= "sincronizacion, ";
				$cadenaSql .= "valor, ";
				$cadenaSql .= "valorOriginal, ";
				$cadenaSql .= "anno, ";
				$cadenaSql .= "periodo, ";
                                $cadenaSql .= "sucursal_pago, ";
                                $cadenaSql .= "codigoUsuario ";
				$cadenaSql .= ") ";
				$cadenaSql .= "VALUES ";
				$cadenaSql .= "(";
				$cadenaSql .= "'".$transaccion->getBanco()."', ";
				$cadenaSql .= "'".$transaccion->getCus()."', ";
				$cadenaSql .= "'".$transaccion->getDescripcion()."', ";
				$cadenaSql .= "'".$transaccion->getEstado()."', ";
				$cadenaSql .= "'".$transaccion->getFechaFin()."', ";
				$cadenaSql .= "'".$transaccion->getFechaInicio()."', ";
				$cadenaSql .= "'".$transaccion->getIdentificacionUsuario()."', ";
				$cadenaSql .= "'".$transaccion->getReferencia()."', ";
				$cadenaSql .= "'".$transaccion->getTipoUsuario()."', ";
                                $cadenaSql .= "'PARCIAL', ";
                                $cadenaSql .= $transaccion->getValor().", ";
				$cadenaSql .= $transaccion->getValorOriginal().", ";
				$cadenaSql .= $transaccion->getAño().", ";
				$cadenaSql .= $transaccion->getPeriodo().", ";
                                $cadenaSql .= $transaccion->getSucursal().", ";
                                $cadenaSql .= "'".$transaccion->getCodigoUsuario()."' ";
				$cadenaSql .= ")";
				$this->cadenaSql=$cadenaSql;
				break;
				
                        case "actualizarPago" :
                                $cadenaSql = 'UPDATE ';
                                $cadenaSql .= 'MNTAC.ACESTMAT ';
                                $cadenaSql .= 'SET ';
                                $cadenaSql .= "EMA_PAGO='S' ";
                                $cadenaSql .= 'WHERE ';
                                $cadenaSql .= 'EMA_SECUENCIA= ';
                                $cadenaSql .= $transaccion->getReferencia().' ';
                                //$cadenaSql .= "AND ";
                                //$cadenaSql .= "EMA_ESTADO= 'A' ";
                                $cadenaSql .= "AND ";
                                $cadenaSql .= 'EMA_ANO=  ';
                                $cadenaSql .= "( ";
                                $cadenaSql .= 'SELECT ';
                                $cadenaSql .= "MAX(EMA_ANO) ";
                                $cadenaSql .= 'FROM ';
                                $cadenaSql .= 'MNTAC.ACESTMAT ';
                                $cadenaSql .= 'WHERE ';
                                $cadenaSql .= 'EMA_SECUENCIA= ';
                                $cadenaSql .= $transaccion->getReferencia().' ';
                                $cadenaSql .= ") ";
                                $this->cadenaSql=$cadenaSql;
                                break;
                            
                        case "insertarPagoBanco" :
		
				$cadenaSql = "INSERT INTO ";
				$cadenaSql .= "MNTAC.ACRECBAN ";
				$cadenaSql .= "(";
				$cadenaSql .= "RBA_BAN_COD, ";
                                $cadenaSql .= "RBA_OFICINA , ";
                                $cadenaSql .= "RBA_ANO, ";
                                $cadenaSql .= "RBA_MES, ";
                                $cadenaSql .= "RBA_DIA , ";
                                $cadenaSql .= "RBA_VALOR, ";
                                $cadenaSql .= "RBA_COD, ";
                                $cadenaSql .= "RBA_SECUENCIA, ";
                                $cadenaSql .= "RBA_IDENTIFICACION, ";
                                $cadenaSql .= "RBA_ANO_SECUENCIA ";
				$cadenaSql .= ") ";
				$cadenaSql .= "VALUES";
				$cadenaSql .= "(";
				$cadenaSql .= "23, ";
                                $cadenaSql .= "".trim($transaccion->getSucursal()).", ";
                                $cadenaSql .= "".substr(trim($transaccion->getFechaInicio()),0,4).", ";
                                $cadenaSql .= "".substr(trim($transaccion->getFechaInicio()),5,2).", ";
                                $cadenaSql .= "".substr(trim($transaccion->getFechaInicio()),8,2).", ";
                                $cadenaSql .= trim($transaccion->getValor()).", ";
                                $cadenaSql .= "".trim($transaccion->getCodigoUsuario()).", ";
                                $cadenaSql .= "".trim($transaccion->getReferencia()).", ";
                                $cadenaSql .= "".trim($transaccion->getIdentificacionUsuario()).",";
                                $cadenaSql .= "".trim($transaccion->getAño())." ";
				$cadenaSql .= ") ";
				$this->cadenaSql=$cadenaSql;
				break;
                            
                        /*modificado 15-10-2014 jairo lavado
                         * se incluye rescatar datos del estudiante
                         */ 
			case 'datosRecibo':
				$cadenaSql = "SELECT ";
				$cadenaSql .= "ema_per as periodo, ";
				$cadenaSql .= "ema_ano as anno, ";
				$cadenaSql .= "ema_valor as valororiginal, ";
                                $cadenaSql .= "est_nro_iden as identificacion, ";
                                $cadenaSql .= "est_cod as codigo ";
				$cadenaSql .= "FROM ";
				$cadenaSql .= "MNTAC.ACESTMAT ";
                                $cadenaSql .= "INNER JOIN MNTAC.ACEST ON est_cod=ema_est_cod ";
                                $cadenaSql .= "WHERE ";
                                $cadenaSql .= "ema_secuencia= ";
				$cadenaSql .= $transaccion->getReferencia()." ";
				$cadenaSql .= "AND ";
				$cadenaSql .= 'ema_ano=  ';
				$cadenaSql .= "( ";
				$cadenaSql .= "SELECT ";
				$cadenaSql .= "MAX(EMA_ANO) ";
				$cadenaSql .= "FROM ";
				$cadenaSql .= "MNTAC.ACESTMAT ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "EMA_SECUENCIA= ";
				$cadenaSql .= $transaccion->getReferencia()." ";
				$cadenaSql .= ") ";
				$this->cadenaSql=$cadenaSql;
				break;
                            
                        /*Modificado 15 octubre 2014 jairo lavado 
                         * culminar sincronización
                         */    
                        case "actualizarEstadoTransaccion" :
                                $cadenaSql = "UPDATE ";
                                $cadenaSql .= "pago ";
				$cadenaSql .= "SET ";
                                $cadenaSql .= "sincronizacion='OK' ";
                                $cadenaSql .= 'WHERE ';
                                $cadenaSql .= "referencia= ";
                                $cadenaSql .= "'".$transaccion->getReferencia()."'";
				$cadenaSql .= "AND ";
                                $cadenaSql .= "anno= ";
                                $cadenaSql .= "'".$transaccion->getAño()."' ";
				$cadenaSql .= "AND ";
                                $cadenaSql .= "periodo= ";
                                $cadenaSql .= "'".$transaccion->getPeriodo()."' ";
                                
                                $this->cadenaSql=$cadenaSql;
                                break;   

                            
                     case "rescatarTransaccion" :
				
				$cadenaSql = "SELECT DISTINCT ";
				$cadenaSql .= "banco as banco, ";
				$cadenaSql .= "cus as cus, ";
				$cadenaSql .= "descripcion as descr, ";
				$cadenaSql .= "estado as est, ";
				$cadenaSql .= "fechaFin as ffin, ";
				$cadenaSql .= "fechaInicio as finicio , ";
				$cadenaSql .= "identificacionUsuario as ident, ";
				$cadenaSql .= "referencia as ref, ";
				$cadenaSql .= "tipoUsuario as tus, ";
                               	$cadenaSql .= "valor as vrpago, ";
				$cadenaSql .= "valorOriginal as vroriginal, ";
				$cadenaSql .= "anno as anno, ";
				$cadenaSql .= "periodo as per, ";
                                $cadenaSql .= "sucursal_pago as sucursal, ";
                                $cadenaSql .= "codigoUsuario as codigo ";
				$cadenaSql .= "FROM  ";
                                $cadenaSql .= "pago ";
				$cadenaSql .= "WHERE ";
                                $cadenaSql .= "sincronizacion= ";
			        $cadenaSql .= "'PARCIAL' ";
                                
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
