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

class FunciondetallesCatalogo {

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

    function procesarFormulario() {
        include_once($this->ruta . "/funcion/procesarFormulario.php");
    }

    function redireccionar($opcion, $valor = "") {
        include_once($this->ruta . "/funcion/redireccionar.php");
    }

    function pintarFormularioModificacion($label, $parametro, $datos_combo, $nombre_tabla, $accion, $tipo_campo, $longitud_campo, $valorModificar) {
        include_once($this->ruta . "/funcion/pintarFormularioModificacion.php");
    }

    function pintarCombo($nombre_combo, $lista_opciones, $valorSeleccionado) {
        include($this->ruta . "/funcion/pintarCombo.php");
    }

    function validarTipoCampo($tipo_campo) {
        include_once($this->ruta . "/funcion/validarTipoCampo.php");
    }

    function guardarParametro($datos, $nombre_tabla, $accion) {
        include_once($this->ruta . "/funcion/guardarParametro.php");
    }

    function mostrarMensaje($mensaje, $error) {
        include_once($this->ruta . "/funcion/mostrarMensaje.php");
    }

    function procesarConfirmar() {
        include_once($this->ruta . "/funcion/procesarConfirmar.php");
    }

    function action() {

        if ($_REQUEST["accion"] == "crearParametro") {
            $this->procesarFormulario();
        } else if ($_REQUEST["accion"] == "actualizarParametro") {
            $this->procesarFormulario();
        }

        switch ($_REQUEST["opcion"]) {

            case "confirmar":
                $this->procesarConfirmar();
                break; 

            default:
                break;
        }
    }

    function __construct() {
        
        include_once("core/builder/FormularioHtml.class.php");
        $this->miFormulario = new formularioHtml();
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
