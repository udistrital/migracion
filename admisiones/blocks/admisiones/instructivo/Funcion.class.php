<?php

if (!isset($GLOBALS["autorizado"])) {
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

class Funcioninstructivo {

    var $sql;
    var $funcion;
    var $lenguaje;
    var $ruta;
    var $miConfigurador;
    var $miInspectorHTML;
    var $error;
    var $miRecursoDB;
    var $crypto;

    function verificarCampos() {
        include_once($this->ruta . "/funcion/verificarCampos.php");
        if ($this->error == true) {
            return false;
        } else {
            return true;
        }
    }

    function guardarPeridoAcademico() {
        include_once($this->ruta . "/funcion/guardarPeriodoAcademico.php");
    }

    function nuevo() {
        include_once($this->ruta . "/formulario/nuevo.php");
    }
    function consultaCarreras()
    {
        include_once($this->ruta . "/funcion/consultarCarreras.php");
    }
    function validaFechasInscripcion()
    {
        include_once ($this->ruta."/funcion/validarFechasInscripcion.php");
    }
    function guardaInscripcion()
    {
        include_once ($this->ruta."/funcion/guardarInscripcion.php");
    }
    function verificarInscripcion()
    {
        include_once ($this->ruta."/funcion/verificarInscripcion.php");
    }
    function verArchivos()
    {
        include_once ($this->ruta."/funcion/verArchivos.php");
    }
    function redireccionar($opcion, $valor = "") {
        include_once($this->ruta . "/funcion/redireccionar.php");
    }
    
    function procesarAjax(){
        include_once($this->ruta . "/funcion/procesarAjax.php");
    }
    
    function action() {


        //var_dump($_REQUEST);   
        if (isset($_REQUEST["procesarAjax"])) {
            $this->procesarAjax();
        } else {
            
            if (isset($_REQUEST['opcion'])) {

                $accion = $_REQUEST['opcion'];

                switch ($accion) {
                    
                    case "validarFechasInscripcion":
                        $this->validaFechasInscripcion();
                        break;
                    case "consultarCarreras":
                        $this->consultaCarreras();
                        break;
                    case "guardarInscripcion":
                        $this->guardaInscripcion();
                        break;
                    case "verificaInscripcion":
                        $this->verificarInscripcion();
                        break;
                    case "verArchivo":
                        $this->verArchivos();
                        break;
                   
                }
            }
        }
    }

    function __construct() {

        $this->miConfigurador = Configurador::singleton();

        $this->miInspectorHTML = InspectorHTML::singleton();

        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

        $this->miMensaje = Mensaje::singleton();

        $conexion = "aplicativo";
        $this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        if (!$this->miRecursoDB) {

            $this->miConfigurador->fabricaConexiones->setRecursoDB($conexion, "tabla");
            $this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        }
    }

    public function setRuta($unaRuta) {
        $this->ruta = $unaRuta;
        //Incluir las funciones
    }

    function setSql($a) {
        $this->sql = $a;
    }

    function setFuncion($funcion) {
        $this->funcion = $funcion;
    }

    public function setLenguaje($lenguaje) {
        $this->lenguaje = $lenguaje;
    }

    public function setFormulario($formulario) {
        $this->formulario = $formulario;
    }

}

?>
