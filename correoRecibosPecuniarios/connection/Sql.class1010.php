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
                                $cadenaSql .= "AND ";
                                $cadenaSql .= "EMA_ESTADO= 'A' ";
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
                                $cadenaSql .= "RBA_IDENTIFICACION ";
				$cadenaSql .= ") ";
				$cadenaSql .= "VALUES ";
				$cadenaSql .= "(";
				$cadenaSql .= "23, ";
                                $cadenaSql .= "NVL('".$transaccion->getSucursal()."',0), ";
                                $cadenaSql .= "'".substr($transaccion->getFechaInicio(),0,4)."', ";
                                $cadenaSql .= "'".substr($transaccion->getFechaInicio(),5,2)."', ";
                                $cadenaSql .= "'".substr($transaccion->getFechaInicio(),8,2)."', ";
                                $cadenaSql .= $transaccion->getValor().", ";
                                $cadenaSql .= "NVL('".$transaccion->getCodigoUsuario()."',0), ";
                                $cadenaSql .= "".$transaccion->getReferencia().", ";
                                $cadenaSql .= "NVL('".$transaccion->getIdentificacionUsuario()."',0) ";
				$cadenaSql .= ")";
				$this->cadenaSql=$cadenaSql;
				break;
                            
                            
			case 'datosRecibo':
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'ema_per as periodo, ';
				$cadenaSql .= 'ema_ano as anno, ';
				$cadenaSql .= 'ema_valor as valororiginal ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'MNTAC.ACESTMAT ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'ema_secuencia= ';
				$cadenaSql .= $transaccion->getReferencia()." ";
				$cadenaSql .= "AND ";
				$cadenaSql .= 'ema_ano=  ';
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
				
		}
		error_log($this->cadenaSql);
		return true;
	}
	
	function getCadenaSql(){
		return $this->cadenaSql;
	}
}
?>
