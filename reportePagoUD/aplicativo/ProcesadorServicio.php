<?php
require_once ("Transaccion.php");
require_once ("DatoConexion.php");
require_once ("../connection/FabricaDbConexion.class.php");
class ProcesadorServicio {



	var $transaccion;
	var $miFabricaConexiones;
	var $conexionOracle;
	var $conexionPostgresql;
	var $mensajeError;
	// Error 1: No se pudo conectar a ORACLE
	// Error 2: No se pudo conectar a POSTGRESQL
	// Error 3: No se pudo rescatar datos del recibo
	// Error 4: No se pudo cambiar el estado del pago en ORACLE
	// Error 5: No se pudo registrar el registro del pago
        // Error 6: No se pudo registrar el pago en acregbanc EN ORACLE
        // Error 7: No se pudo cambiar el estado de la transaccion en postres


	function __construct(){

		$this->miFabricaConexiones = new FabricaDBConexion ();
		$this->mensajeError='NINGUNO';
		$this->crearConexiones();

	}

	function creaTransaccion($objeto) {

		if($this->mensajeError=='NINGUNO'){
			
			$resultado = $this->setTransaccion ( $objeto );

			if ($resultado) {
				if ($this->registrarPago ()) {
				
					return array (
							"return" => "OK" 
					);
				} else {
				
					return array (
							"return" => $this->mensajeError
					);
				}
				;
			}
		}
		return array (
			"return" => $this->mensajeError
		);
	}
	function actualizarTransaccion() {
		return array (
				"return" => "error" 
		);
	}


	private function crearConexiones(){

		
		$datosConexion = new DatoConexion ();
		
		$resultado=true;
		
		// 1. Crear conexion a ORACLE:
		$datosConexion->setDatosConexion ( "oracle" );
		$this->miFabricaConexiones->setRecursoDB ( "oracle", $datosConexion );
		$this->conexionOracle = $this->miFabricaConexiones->getRecursoDB ( "oracle" );		
		if (! $this->conexionOracle) {
			error_log ('NO SE CONECTO A ORACLE' );
			$this->mensajeError='Error 1';
			return false;
		}
		
		// 2. Crear conexión a POSTGRESQL
		$datosConexion->setDatosConexion ( "postgresql" );
		$this->miFabricaConexiones->setRecursoDB ( "postgresql", $datosConexion );
		$this->conexionPostgresql = $this->miFabricaConexiones->getRecursoDB ( "postgresql" );
		
		if (! $this->conexionPostgresql) {
			error_log ( 'NO SE CONECTO A POSTGRESQL');
			$this->mensajeError='Error 2';
			return false;
		}		
		
		return true;	
	}
	private function registrarPago() {		
		
		$resultado=true;

		//0.Obtener detalles del recibo (secuencia)
		$datosRecibo=$this->rescatarSecuencia($this->transaccion->getReferencia());
		
		if($datosRecibo){		
			$this->transaccion->setValorOriginal ( $datosRecibo [0]['VALORORIGINAL'] );
			$this->transaccion->setAño ( $datosRecibo [0]['ANNO'] );
			$this->transaccion->setPeriodo( $datosRecibo [0]['PERIODO'] );	
                         /*
                        * Modificación 15/10/2014 jairo lavado
                        * si la variable identificacion o codigo usuario viene vacia recupera los datos
                        */
                        if(!$this->transaccion->getCodigoUsuario() || $this->transaccion->getCodigoUsuario()==0)
                            {  $this->transaccion->setCodigoUsuario($datosRecibo [0]['CODIGO']); }
                        if(!$this->transaccion->getIdentificacionUsuario() || $this->transaccion->getIdentificacionUsuario()==0)
                            { $this->transaccion->setIdentificacionUsuario($datosRecibo [0]['IDENTIFICACION']);  }
			
                        // 2. Registra datos en POSTGRESQL	
			$cadenaSql = $this->miFabricaConexiones->getCadenaSql ( "insertarTransaccion", $this->transaccion );
			$registro=$this->conexionPostgresql->ejecutarAcceso ( $cadenaSql, "insertar" );
                        if ($registro==0) 
                            {
				error_log ( $this->transaccion->getReferencia().': NO SE REGISTRO EL PAGO');
				$this->mensajeError='Error 5';
				return false;
                            }
			else {  
                                /*
                                * Modificación 15/10/2014 jairo lavado
                                * Se recicla el codigo de la funcion para sincronizaciones posteriores.
                                */
                                $this->registrarRecaudo ();
                                /*
                                * Modificación 15/10/2014 jairo lavado
                                * Sincroniza transacciones que no hayan sido totalmente procesadas.
                                */
                                $this->rescatarTransacciones();
                                return true;
                        }
                        
                    }
                else{
                    error_log ( $this->transaccion->getReferencia().': NO SE OBTUVO DATOS DEL RECIBO');
                    $this->mensajeError='Error 3';
                    return false;
                    }
               
	}


	private function rescatarSecuencia($secuencia){

		$cadenaSql = $this->miFabricaConexiones->getCadenaSql ( 'datosRecibo', $this->transaccion );
		$registro=$this->conexionOracle->ejecutarAcceso ( $cadenaSql, "busqueda" );
		return $registro;

	}


	private function registrarRecaudo() {		
					
                        // 1. Registra datos en ORACLE registro banco			
			$cadenaSql = $this->miFabricaConexiones->getCadenaSql ( "insertarPagoBanco", $this->transaccion );
                        $resultadoReg=$this->conexionOracle->ejecutarAcceso ( $cadenaSql, "insertar" );
			if ($resultadoReg==0) 
                            {
				error_log ( $this->transaccion->getReferencia().': NO SE REGISTRO PAGO DEL BANCO EN ORACLE');				
				$this->mensajeError='Error 6';
				return false;
                            }
                        else { // 2. Registra datos en ORACLE			
                                $cadenaSql = $this->miFabricaConexiones->getCadenaSql ( "actualizarPago", $this->transaccion );
                                if (!$this->conexionOracle->ejecutarAcceso ( $cadenaSql, "actualizar" )) {
                                        error_log ( $this->transaccion->getReferencia().': NO SE CAMBIO ESTADO EN ORACLE');				
                                        $this->mensajeError='Error 4';
                                        return false;
                                }                      
                                // 7. Actualiza estado en postgres 
                                $cadenaSql = $this->miFabricaConexiones->getCadenaSql ( "actualizarEstadoTransaccion", $this->transaccion );
                                if (!$this->conexionPostgresql->ejecutarAcceso ( $cadenaSql, "actualizar" )) {
                                        error_log ( $this->transaccion->getReferencia().': NO SE CAMBIO ESTADO DE LA TRANSACCION');				
                                        $this->mensajeError='Error 7';
                                        return false;
                                }

                            }
 	}        

        
	private function rescatarTransacciones(){

		$cadenaSql = $this->miFabricaConexiones->getCadenaSql ( 'rescatarTransaccion', $this->transaccion );
		$registroTransaccion=$this->conexionPostgresql->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
                if($registroTransaccion)
                    {                    
                    foreach ($registroTransaccion as $key => $value) 
                        { 
                             $this->transaccion->setBanco ($registroTransaccion[$key]['banco']);
                             $this->transaccion->setCus ($registroTransaccion[$key]['cus']);
                             $this->transaccion->setDescripcion ($registroTransaccion[$key]['descr']);
                             $this->transaccion->setEstado ($registroTransaccion[$key]['est']);
                             $this->transaccion->setFechaFin ($registroTransaccion[$key]['ffin']);
                             $this->transaccion->setFechaInicio ($registroTransaccion[$key]['finicio']);
                             $this->transaccion->setIdentificacionUsuario ($registroTransaccion[$key]['ident']);
                             $this->transaccion->setReferencia ($registroTransaccion[$key]['ref']);
                             $this->transaccion->setTipoUsuario ($registroTransaccion[$key]['tus']);
                             $this->transaccion->setValor ($registroTransaccion[$key]['vrpago']);
                             $this->transaccion->setValorOriginal ($registroTransaccion[$key]['vroriginal']);
                             $this->transaccion->setAño ($registroTransaccion[$key]['anno']);
                             $this->transaccion->setPeriodo ($registroTransaccion[$key]['per']);
                             $this->transaccion->setSucursal ($registroTransaccion[$key]['sucursal']);
                             $this->transaccion->setCodigoUsuario ($registroTransaccion[$key]['codigo']);
                             
                             $this->registrarRecaudo ();
                        }
                    
                    }
	}



	private function setTransaccion($objeto) {
		if (is_object ( $objeto )) {
			// Registrar el objeto Transaccion
			$datos = ( array ) $objeto;
			$datosTransaccion = ( array ) $datos ["objTransaccion"];
			
			$this->transaccion = new Transaccion ();
			$this->transaccion->setBanco ( $datosTransaccion ["banco"] );
			$this->transaccion->setCus ( $datosTransaccion ["cus"] );
			$this->transaccion->setDescripcion ( $datosTransaccion ["descripcion"] );
			$this->transaccion->setEstado ( $datosTransaccion ["estado"] );
			$this->transaccion->setFechaFin ( $datosTransaccion ["fechaFin"] );
			$this->transaccion->setFechaInicio ( $datosTransaccion ["fechaInicio"] );
			$this->transaccion->setIdentificacionUsuario ( $datosTransaccion ["identificacionUsuario"] );
			$this->transaccion->setReferencia ( $datosTransaccion ["referencia"] );
			$this->transaccion->setTipoUsuario ( $datosTransaccion ["tipoUsuario"] );
			$this->transaccion->setValor ( $datosTransaccion ["valor"] );
                        $this->transaccion->setSucursal ( $datosTransaccion ["sucursal"] );
			$this->transaccion->setCodigoUsuario ( $datosTransaccion ["codigoUsuario"] );
			return true;
		}
		
		return false;
	}
}

?>
