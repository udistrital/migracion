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

//Esta clase contiene la logica de negocio del bloque y extiende a la clase funcion general la cual encapsula los
//metodos mas utilizados en la aplicacion

//Para evitar redefiniciones de clases el nombre de la clase del archivo funcion debe corresponder al nombre del bloque
//en camel case precedido por la palabra Funcion

class FuncionarmarFormulariosEvaldocente
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
        
        function guardaFormatos()
	{
            include_once($this->ruta."/funcion/guardarFormatos.php");
	}
        function editaFormatos()
        {
            include_once($this->ruta."/funcion/procesarEditarFormatos.php");
        }
        function guardaEncabezados()
        {
            include_once($this->ruta."/funcion/guardarEncabezados.php");
        }
        function editaEncabezados()
        {
            include_once($this->ruta."/funcion/procesarEditarEncabezados.php");
        }
        function guardaPreguntas()
        {
            include_once($this->ruta."/funcion/guardarPreguntas.php");
        }
        function editaPreguntas()
        {
            include_once($this->ruta."/funcion/procesarEditarPreguntas.php");
        }
        function guardaFormulario()
        {
            include_once($this->ruta."/funcion/guardarFormularios.php");
        }
        function borraFormulario()
        {
            include_once($this->ruta."/funcion/borrarRegistroFormulario.php");
        }
	function guardaAsociacion()
        {
            include_once($this->ruta."/funcion/guardarAsociacion.php");
        }
        function editaAsociacion()
        {
            include_once($this->ruta."/funcion/editarAsociacion.php");
        }
        function redireccionar($opcion, $valor=""){ 
            include_once($this->ruta."/funcion/redireccionar.php");
	}
               

	function action() {
        
        
        
        if (isset($_REQUEST["procesarAjax"])) {
            $this->procesarAjax();
        } else{
                
               //var_dump($_REQUEST);   
                if(isset($_REQUEST['opcion'])){

			$accion=$_REQUEST['opcion'];
                            
                        switch($accion){
				case "guardarFormatos":
                                        $this->guardaFormatos();
				break;
                                case "editarFormatos":
                                        $this->editaFormatos();
				break;
                                case "guardarEncabezados":
                                        $this->guardaEncabezados();
				break;
                                case "editarEncabezados":
                                        $this->editaEncabezados();
				break;
                                case "guardarPreguntas":
                                        $this->guardaPreguntas();
				break;
                                case "editarPreguntas":
                                        $this->editaPreguntas();
				break;
                                case "guardarFormulario":
                                        $this->guardaFormulario();
				break;
                                case "borrarRegistroFormulario":
                                        $this->borraFormulario();
				break;
                                case "guardarAsociacion":
                                        $this->guardaAsociacion();
				break;
                                 case "editarAsociacion":
                                        $this->editaAsociacion();
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
		
		$conexion="aplicativo";
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
