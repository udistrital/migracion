<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/builder/InspectorHTML.class.php");
include_once("core/builder/Mensaje.class.php");
include_once("core/crypto/Encriptador.class.php");
include_once("core/auth/Sesion.class.php");

//Esta clase contiene la logica de negocio del bloque y extiende a la clase funcion general la cual encapsula los
//metodos mas utilizados en la aplicacion

//Para evitar redefiniciones de clases el nombre de la clase del archivo funcion debe corresponder al nombre del bloque
//en camel case precedido por la palabra Funcion

class FuncionvalidarEstudiantesActivos
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

	function nuevoServicio()
	{
		include_once($this->ruta."/funcion/nuevoServicio.php");
	}
	
	function obtenerServicios()
	{
		include_once($this->ruta."/funcion/obtenerServicios.php");
	}
	
	function crearServicio(){
		include_once($this->ruta."/funcion/crearServicio.php");
	}

	function desactivarServicio(){
		include_once($this->ruta."/funcion/desactivarServicio.php");
	}
	
	function editarServicio(){
		include_once($this->ruta."/funcion/editarServicio.php");
	}
	
	function actualizarServicio(){
		include_once($this->ruta."/funcion/actualizarServicio.php");
	}
	
	function procesarListado(){
		include_once($this->ruta."/funcion/procesarListado.php");
	}
	
	

	

	function action()
	{
		

		//Evitar que se ingrese codigo HTML y PHP en los campos de texto
		//Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir="";
		$_REQUEST=$this->miInspectorHTML->limpiarPHPHTML($_REQUEST);

		//Aquí se coloca el código que procesará los diferentes formularios que pertenecen al bloque
		//aunque el código fuente puede ir directamente en este script, para facilitar el mantenimiento
		//se recomienda que aqui solo sea el punto de entrada para incluir otros scripts que estarán
		//en la carpeta funcion
		
		//Valida si la sesion esta permitida
		include_once($this->ruta."/funcion/validarSession.php");
		
		//Importante: Es adecuado que sea una variable llamada opcion o action la que guie el procesamiento:
		if(isset($_REQUEST["procesarAjax"])&&$_REQUEST["procesarAjax"]==true&&isset($_REQUEST["funcion"])){

			//Realizar una validación específica para los campos de este formulario:
			//la siguiente linea no deber�a estar comentada, en esta plantilla no se verifica lo enviado 
			//pero siempre se deben validar los campos. 
			$validacion=$this->verificarCampos();
			//$validacion=true;
			
			if($validacion==false){
				//Instanciar a la clase pagina con mensaje de correcion de datos
				echo "Datos Incorrectos";

			}else{
				//Validar las variables para evitar un tipo  insercion de SQL
				$_REQUEST=$this->miInspectorHTML->limpiarSQL($_REQUEST);
				switch($_REQUEST["funcion"]){
					case "procesarListado":
						$this->procesarListado();
						break;
					case "obtenerServicios":
						$this->obtenerServicios();
						break;
					case "crearServicio":
							$this->crearServicio();
							break;
					case "desactivarServicio":
							$this->desactivarServicio();
							break;
					case "editarServicio":
								$this->editarServicio();
								break;
					case "actualizarServicio":
								$this->actualizarServicio();
								break;
					case "consultarServicio":
								$this->consultarServicio();
								break;
				}			
					
				
			}
		}
	
	}


	function __construct()
	{

		$this->miConfigurador=Configurador::singleton();

		$this->miInspectorHTML=InspectorHTML::singleton();
			
		$this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");		
		
		$this->miMensaje=Mensaje::singleton();
		
		$this->miSesion=Sesion::singleton();
		
		$conexion="aplicativo";
		$this->miRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		if(!$this->miRecursoDB){
		
			$this->miConfigurador->fabricaConexiones->setRecursoDB($conexion,"tabla");
			$this->miRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);			
		}
		
		
	}

	public function setRuta($unaRuta){
		$this->ruta=$unaRuta;
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
