<?php 

class Transaccion{
	
	private $banco=null;
	private $cus=null;
	private $descripcion=null;
	private $estado=null;
	private $fechaFin=null;
	private $fechaInicio=null;
	private $identificacionUsuario=null;	
	private $referencia=null;
	private $tipoUsuario=null;
	private $valor=null;
	private $valorOriginal=null;
	private $año=null;
	private $periodo=null;	
	private $sucursal=null;	
        private $codigoUsuario=null;	
	/**
	 * @return $this->the referencia
	 */
	public function getReferencia() {
		return $this->referencia;
	}
	
	/**
	 * @param referencia the referencia to set
	 */
	public function setReferencia($referencia) {
		$this->referencia = $referencia;
	}
	
	/**
	 * @return $this->the estado
	 */
	public function getEstado() {
		return $this->estado;
	}
	
	/**
	 * @param estado the estado to set
	 */
	public function setEstado($estado) {
		$this->estado = $estado;
	}
	
	/**
	 * @return $this->the valor
	 */
	public function getValor() {
		return $this->valor;
	}
	
	/**
	 * @param valor the valor to set
	 */
	public function setValor($valor) {
		$this->valor = $valor;
	}
	
	/**
	 * @return $this->the tipo_usauario
	 */
	public function getTipoUsuario() {
		return $this->tipoUsuario;
	}
	
	/**
	 * @param tipo_usauario the tipo_usauario to set
	 */
	public function setTipoUsuario($tipoUsuario) {
		$this->tipoUsuario = $tipoUsuario;
	}
	
	/**
	 * @return $this->the descripcion
	 */
	public function getDescripcion() {
		return $this->descripcion;
	}
	
	/**
	 * @param descripcion the descripcion to set
	 */
	public function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}
	
	/**
	 * @return $this->the cus
	 */
	public function getCus() {
		return $this->cus;
	}
	
	/**
	 * @param cus the cus to set
	 */
	public function setCus($cus) {
		$this->cus = $cus;
	}
	
	/**
	 * @return $this->the banco
	 */
	public function getBanco() {
		return $this->banco;
	}
	
	/**
	 * @param banco the banco to set
	 */
	public function setBanco($banco) {
		$this->banco = $banco;
	}
	
	/**
	 * @return $this->the fechaInicio
	 */
	public function getFechaInicio() {
		return $this->fechaInicio;
	}
	
	/**
	 * @param fechaInicio the fechaInicio to set
	 */
	public function setFechaInicio($fechaInicio) {
		$this->fechaInicio = $fechaInicio;
	}
	
	/**
	 * @return $this->the fechaFin
	 */
	public function getFechaFin() {
		return $this->fechaFin;
	}
	
	/**
	 * @param fechaFin the fechaFin to set
	 */
	public function setFechaFin($fechaFin) {
		$this->fechaFin = $fechaFin;
	}
	
	/**
	 * @return $this->the identificacionUsuario
	 */
	public function getIdentificacionUsuario() {
		return $this->identificacionUsuario;
	}
	
	/**
	 * @param identificacionUsuario the identificacionUsuario to set
	 */
	public function setIdentificacionUsuario($identificacionUsuario) {
		$this->identificacionUsuario = $identificacionUsuario;
	}

	/**
	 * @return $this->the año
	 */
	public function getAño() {
		return $this->año;
	}
	
	/**
	 * @param año the año to set
	 */
	public function setAño($año) {
		$this->año = $año;
	}

	/**
	 * @return $this->the periodo
	 */
	public function getPeriodo() {
		return $this->periodo;
	}
	
	/**
	 * @param año the periodo to set
	 */
	public function setPeriodo($periodo) {
		$this->periodo = $periodo;
	}

        
	/**
	 * @return $this->sucursal
	 */
	public function getSucursal() {
		return $this->sucursal;
	}
	
	/**
	 * @param sucursal to set
	 */
	public function setSucursal($sucursal) {
		$this->sucursal = $sucursal;
	}        
        
	/**
	 * @return $this->the identificacionUsuario
	 */
	public function getCodigoUsuario() {
            return $this->codigoUsuario;
	}
	
	/**
	 * @param identificacionUsuario the identificacionUsuario to set
	 */
	public function setCodigoUsuario($codigoUsuario) {
		$this->codigoUsuario = $codigoUsuario;
	}
        
	/**
	 * @return $this->the valorOriginal
	 */
	public function getValorOriginal() {
		return $this->valorOriginal;
	}
	
	/**
	 * @param año the periodo to set
	 */
	public function setValorOriginal($valorOriginal) {
		$this->valorOriginal = $valorOriginal;
	}
	
	
	private function limpiarEntrada($cadena){
		
		
	}
	
}


?>
