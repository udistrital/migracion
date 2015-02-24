<?php
//echo "Por funcion";
//var_dump($_REQUEST);exit;
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/builder/InspectorHTML.class.php");
include_once("core/builder/Mensaje.class.php");
include_once("core/crypto/Encriptador.class.php");

//Esta clase contiene la logica de negocio del bloque y extiende a la clase funcion general la cual encapsula los
//metodos mas utilizados en la aplicacion

//Para evitar redefiniciones de clases el nombre de la clase del archivo funcion debe corresponder al nombre del bloque
//en camel case precedido por la palabra Funcion

class FunciongenerarReporte
{

	var $sql;
	var $funcion;
	var $lenguaje;
	var $ruta;
	var $miConfigurador;
	var $miInspectorHTML;
	var $error;
	var $miRecursoDB;
	var $crypto;



	function verificarCampos(){
		include_once($this->ruta."/funcion/verificarCampos.php");
		if($this->error==true){
			return false;
		}else{
			return true;
		}


	}

	function calcularFecha($fecha)
	{		
		$fecha = explode("/", $fecha);
		
		$fecha = $fecha[0]." de ".$this->nombreMes(ucfirst(strtolower($fecha[1])))." de ".$fecha[2];

		return $fecha;
	}

	/****************************************************************/
	
	function calcularFecha2($fecha1)
	{
		$fecha1 = explode("/", $fecha1);
	
		$fecha1 = $fecha1[0]." de ".$this->nombreMes(ucfirst(strtolower($fecha1[1])))." de ".$fecha1[2];

		return $fecha1;
	}
	/****************************************************************/

	function calcularFecha3($fecha2)
	{
		$fecha2 = explode("/", $fecha2);
	
		$fecha2 = $fecha2[0]." de ".$this->nombreMes(ucfirst(strtolower($fecha2[1])))." de ".$fecha2[2];

		return $fecha2;
	}
	/****************************************************************/

	function nombreMes($mes){
		if ($mes=="January") $mes="Enero";
		if ($mes=="February") $mes="Febrero";
		if ($mes=="March") $mes="Marzo";
		if ($mes=="April") $mes="Abril";
		if ($mes=="May") $mes="Mayo";
		if ($mes=="June") $mes="Junio";
		if ($mes=="July") $mes="Julio";
		if ($mes=="August") $mes="Agosto";
		if ($mes=="September") $mes="Septiembre";
		if ($mes=="October") $mes="Octubre";
		if ($mes=="November") $mes="Noviembre";
		if ($mes=="December") $mes="Diciembre";
		
		return $mes;
	}
	
	function nuevo(){
		include_once($this->ruta."/funcion/procesarNuevo.php");
	}
	
	function procesarAjax(){
		include_once($this->ruta."/funcion/procesarAjax.php");
	}

	
	function redireccionar($opcion, $datos){
		include_once($this->ruta."funcion/redireccionar.php");
	}

	function generarReporte(){
		include_once($this->ruta."funcion/generarReporte.php");
	}

	function generarPDF(){
		include_once($this->ruta."funcion/certificados/generador_uno.php");
		$this->redireccionar("mostrarMensaje");
	}

	function generarPDF2(){
		//echo "ingreso aqui";exit;
		include_once($this->ruta."funcion/certificados/generador_dos.php");
		$this->redireccionar("inicial");
	}

	function generarPDF3(){
		include_once($this->ruta."funcion/certificados/generador_tres.php");
		$this->redireccionar("inicial");
	}

	function generarPDF4(){
		include_once($this->ruta."funcion/certificados/generador_cuatro.php");
		$this->redireccionar("inicial");
	}
	
	function generarPDF5(){
		//echo "llego aqui";exit;
		include_once($this->ruta . "funcion/certificados/generador_cinco.php");
		$this->redireccionar("inicial");
	}
	
	function generarPDF6(){
		//echo "llego aqui";exit;
		include_once($this->ruta . "funcion/certificados/generador_seis.php");
		$this->redireccionar("inicial");
	}
	
	function generarPDF7(){
		//echo "llego aqui";exit;
		include_once($this->ruta . "funcion/certificados/generador_siete.php");
		$this->redireccionar("inicial");
	}
	function generarPDF8(){
		//echo "llego aqui";exit;
		include_once($this->ruta . "funcion/certificados/generador_ocho.php");
		$this->redireccionar("inicial");
	}
	function generarPDF9(){
		//echo "llego aqui";exit;
		include_once($this->ruta . "funcion/certificados/generador_nueve.php");
		$this->redireccionar("inicial");
	}
	
	function generarPDF10(){
		//echo "llego aqui";exit;
		include_once($this->ruta . "funcion/certificados/generador_diez.php");
		$this->redireccionar("inicial");
	}
	
	function generarPDF11(){
		//echo "llego aqui";exit;
		include_once($this->ruta . "funcion/certificados/generador_once.php");
		$this->redireccionar("inicial");
	}
	
	function generarPDF12(){
		//echo "llego aqui";exit;
		include_once($this->ruta . "funcion/certificados/generador_doce.php");
		$this->redireccionar("inicial");
	}
	
	function generarPDF13(){
		//echo "llego aqui";exit;
		include_once($this->ruta . "funcion/certificados/generador_trece.php");
		$this->redireccionar("inicial");
	}
	
	function generarPDF14(){
		//echo "llego aqui";exit;
		include_once($this->ruta . "funcion/certificados/generador_catorce.php");
		$this->redireccionar("inicial");
	}
	
	function generarPDF15(){
		//echo "llego aqui";exit;
		include_once($this->ruta . "funcion/certificados/generador_quince.php");
		$this->redireccionar("inicial");
	}
	
	function action()
	{

		//Evitar que se ingrese codigo HTML y PHP en los campos de texto
		//Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir="";
		$_REQUEST=$this->miInspectorHTML->limpiarPHPHTML($_REQUEST);

		//Validar las variables para evitar un tipo  insercion de SQL
		$_REQUEST=$this->miInspectorHTML->limpiarSQL($_REQUEST);


		if(isset($_REQUEST["solicitud"]) && $_REQUEST["solicitud"]=="generarReporte"){
			//var_dump($_REQUEST);
			$this->generarReporte();
		}else{
			
			$this->procesarAjax();
				
		}
	}


function __construct()
{

	$this->miConfigurador=Configurador::singleton();

	$this->miInspectorHTML=InspectorHTML::singleton();
		
	$this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");

	$this->miMensaje=Mensaje::singleton();

	$conexion="aplicativo";//parametro original "configuracion"
	$this->miRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

	if(!$this->miRecursoDB){

		$this->miConfigurador->fabricaConexiones->setRecursoDB($conexion,"tabla");
		$this->miRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
	}


}

public function setRuta($unaRuta){
	$this->ruta=$unaRuta;
	//Incluir las funciones
}

function setSql($a)
{
	$this->sql=$a;
}

function setFuncion($funcion)
{
	$this->funcion=$funcion;
}

public function setLenguaje($lenguaje)
{
	$this->lenguaje=$lenguaje;
}

public function setFormulario($formulario){
	$this->formulario=$formulario;
}
}
?>
