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

class FuncionadminAdmisiones {

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

    function confirmar() {
        include_once($this->ruta . "/funcion/procesarConfirmar.php");
    }

    function editar() {
        include_once($this->ruta . "/funcion/procesarEditar.php");
    }

    function editarPeriodo() {
        include_once($this->ruta . "/funcion/editarPeriodo.php");
    }

    function guardaEventos() {
        include_once($this->ruta . "/funcion/guardarEventos.php");
    }

    function guardaMedio() {
        include_once($this->ruta . "/funcion/guardarMedio.php");
    }

    function procesaEditarMedios() {
        include_once($this->ruta . "/funcion/ProcesarEditarMedio.php");
    }

    function guardaSalarioMin() {
        include_once ($this->ruta . "/funcion/guardarSalarioMin.php");
    }

    function procesaEditarSalMin() {
        include_once ($this->ruta . "/funcion/procesarEditarSalMin.php");
    }

    function guardaLocalidades() {
        include_once ($this->ruta . "/funcion/guardarLocalidades.php");
    }

    function guardaEstratos() {
        include_once ($this->ruta . "/funcion/guardarEstratos.php");
    }

    function procesaEditarLocalidad() {
        include_once ($this->ruta . "/funcion/procesarEditarLocalidad.php");
    }

    function procesaEditarEstratos() {
        include_once ($this->ruta . "/funcion/procesarEditarEstratos.php");
    }

    function guardaInstructivo() {
        include_once ($this->ruta . "/funcion/guardarInstructivo.php");
    }

    function guardaColilla() {
        include_once ($this->ruta . "/funcion/guardarColilla.php");
    }

    function editaColilla() {
        include_once ($this->ruta . "/funcion/procesarEditarColillas.php");
    }

    function guardaPines() {
        include_once ($this->ruta . "/funcion/guardarPines.php");
    }

    function editaCarrera() {
        include_once ($this->ruta . "/funcion/editarCarrera.php");
    }

    function guardaTipInscripcion() {
        include_once ($this->ruta . "/funcion/guardarTipInscripcion.php");
    }

    function procesaEditarTipIns() {
        include_once ($this->ruta . "/funcion/procesarEditarTipIns.php");
    }

    function guardaDiscapacidad() {
        include_once ($this->ruta . "/funcion/guardarDiscapacidad.php");
    }

    function procesaEditarDiscapacidad() {
        include_once ($this->ruta . "/funcion/procesarEditarDiscapacidad.php");
    }

    function copiaEstratos() {
        include_once ($this->ruta . "/funcion/copiarEstratos.php");
    }

    function copiaLocalidades() {
        include_once ($this->ruta . "/funcion/copiarLocalidades.php");
    }

    function guardaPreguntas() {
        include_once($this->ruta . "/funcion/guardarPreguntas.php");
    }

    function editaPreguntas() {
        include_once($this->ruta . "/funcion/procesarEditarPreguntas.php");
    }
    
    function guardaEncabezados(){
        include_once($this->ruta . "/funcion/guardarEncabezados.php");
    }
    
    function editaEncabezado(){
        include_once($this->ruta . "/funcion/procesarEditarEncabezados.php");
    }
    
    function procesaEditarSnp(){
        include_once($this->ruta . "/funcion/procesarEditarSnp.php");
    }
    
    function procesaConsultaRefBancaria()
    {
        include_once($this->ruta . "/funcion/procesarConsultaRefBancaria.php");
    }
    
    function exportarSnpTexto()
    {
        include_once($this->ruta . "/funcion/exportarSnpTexto.php");
    }
    
    function guardarIcfes()
    {
        include_once ($this->ruta . "/funcion/guardarIcfes.php");
    }
    
    function guardaDocumentacion()
    {
        include_once ($this->ruta . "/funcion/guardarDocumentacion.php");
    }
    
    function procesaEditarDocumentacion()
    {
        include_once ($this->ruta . "/funcion/procesarEditarDocumentacion.php");
    }
    
    function verArchivos()
    {
        include_once ($this->ruta."/funcion/verArchivos.php");
    }
    
    function consultaCredencial()
    {
        include_once ($this->ruta."/funcion/procesarConsultarCredencial.php");
    }
    
    function procesarEditarInscripcion()
    {
        include_once ($this->ruta."/funcion/procesarEditarInscripcion.php");
    }
            
    function redireccionar($opcion, $valor = "") {
        include_once($this->ruta . "/funcion/redireccionar.php");
    }
    
    function procesaCopiarInscripciones(){
        include_once($this->ruta . "/funcion/procesarCopiarInscripciones.php");
    }
    
    function procesaguardarPdfResultados(){
    	include_once($this->ruta . "/funcion/procesarguardarPdfResultados.php");
    }
    
    function procesaguardarPdfResultadosEspeciales(){
    	include_once($this->ruta . "/funcion/procesarguardarPdfResultadosEspeciales.php");
    }
    
    function procesaCalculoResultados(){
    	include_once($this->ruta . "/funcion/procesarCalculoResultados.php");
    }
    
    function cargaAdmitidos(){
        include_once($this->ruta . "/funcion/procesarCargarAdmitidos.php");
    }
    
    function marcaAdmitidos(){
        include_once($this->ruta . "/funcion/procesarMarcarAdmitidos.php");
    }
    
    function marcaAdmitidosCredencial(){
        include_once($this->ruta . "/funcion/procesarMarcarAdmitidosCredencial.php");
    }
    
    function consultaInscripcionCarrera(){
        include_once($this->ruta . "/funcion/consultarInscripcionCarrera.php");
    }
    
    function consultaInscritosFacultad(){
        include_once($this->ruta . "/funcion/consultarInscritosFacultad.php");
    }
    
    function consultaInsEspecialesCarrera(){
        include_once($this->ruta . "/funcion/consultarInsEspecialesCarrera.php");
    }
    
    function procesarAjax(){
        include_once($this->ruta . "/funcion/procesarAjax.php");
    }

    function action() {

        //var_dump($_REQUEST["procesarAjax"]);

        if (isset($_REQUEST["procesarAjax"])) {
            $this->procesarAjax();
        } else {
            //var_dump($_REQUEST);   
            if (isset($_REQUEST['opcion'])) {

                $accion = $_REQUEST['opcion'];

                switch ($accion) {
                    case "guardar":
                        $this->guardarPeridoAcademico();
                        break;
                    case "confirmacionPeriodo":
                        $this->guardarPeridoAcademico();
                        break;
                    case "guardarEventos":
                        $this->guardaEventos();
                        break;
                    case "guardarEventosCarrera":
                        $this->guardaEventosCarrera();
                        break;
                    case "editarPeriodo":
                        $this->editarPeriodo();
                        break;
                    case "guardarMedio":
                        $this->guardaMedio();
                        break;
                    case "procesarEditarMedios":
                        $this->procesaEditarMedios();
                        break;
                    case "guardarSalarioMin":
                        $this->guardaSalarioMin();
                        break;
                    case "procesarEditarSalMin":
                        $this->procesaEditarSalMin();
                        break;
                    case "guardarLocalidades":
                        $this->guardaLocalidades();
                        break;
                    case "copiarLocalidades":
                        $this->copiaLocalidades();
                        break;
                    case "procesarEditarLocalidad":
                        $this->procesaEditarLocalidad();
                        break;
                    case "guardarEstratos":
                        $this->guardaEstratos();
                        break;
                    case "copiarEstratos":
                        $this->copiaEstratos();
                        break;
                    case "procesarEditarEstratos":
                        $this->procesaEditarEstratos();
                        break;
                    case "guardarInstructivo":
                        $this->guardaInstructivo();
                        break;
                    case "guardarColillas":
                        $this->guardaColilla();
                        break;
                    case "editarColilla":
                        $this->editaColilla();
                        break;
                    case "guardarPines":
                        $this->guardaPines();
                        break;
                    case "editarCarrera":
                        $this->editaCarrera();
                        break;
                    case "guardarTipInscripcion":
                        $this->guardaTipInscripcion();
                        break;
                    case "procesarEditarTipIns":
                        $this->procesaEditarTipIns();
                        break;
                    case "guardarDiscapacidad":
                        $this->guardaDiscapacidad();
                        break;
                    case "procesarEditarDiscapacidad":
                        $this->procesaEditarDiscapacidad();
                        break;
                    case "guardarPreguntas":
                        $this->guardaPreguntas();
                        break;
                    case "editarPreguntas":
                        $this->editaPreguntas();
                        break;
                    case "guardarEncabezados":
                        $this->guardaEncabezados();
                        break;
                    case "editarEncabezado":
                        $this->editaEncabezado();
                        break;
                    case "procesarEditarSnp":
                        $this->procesaEditarSnp();
                        break;
                    case "consultarReferenciaBancaria";
                        $this->procesaConsultaRefBancaria();
                        break;
                    case "exportarSnp":
                        $this->exportarSnpTexto();
                        break;
                    case "guardarIcfes":
                        $this->guardarIcfes();
                        break;
                    case "guardarDocumentacion":
                        $this->guardaDocumentacion();
                        break;
                    case "procesarEditarDocumentacion":
                        $this->procesaEditarDocumentacion();
                        break;
                    case "verArchivo":
                        $this->verArchivos();
                        break;
                    case "consultarInscripcion":
                        $this->consultaCredencial();
                        break;
                    case "procesarEditarInscripcion":
                        $this->procesarEditarInscripcion();
                        break;
                    case "procesarCopiarInscripciones":
                        $this->procesaCopiarInscripciones();
                        break;
                    case "guardarPdfResultados":
                    	$this->procesaguardarPdfResultados();
                    	break;
                    case "guardarPdfResultadosEspeciales":
                    	$this->procesaguardarPdfResultadosEspeciales();
                    	break;
                    case "procesarCalculoResultados":
                        $this->procesaCalculoResultados();
                        break;
                    case "cargarAdmitidos":
                        $this->cargaAdmitidos();
                        break;
                    case "marcarAdmitidos":
                        $this->marcaAdmitidos();
                        break;
                    case "marcarAdmitidosCredencial":
                        $this->marcaAdmitidosCredencial();
                        break;
                    case "consultarInscripcionCarrera":
                        $this->consultaInscripcionCarrera();
                        break;
                    case "consultarInscritosFacultades":
                        $this->consultaInscritosFacultad();
                        break;
                    case "consultarInsEspecialesCarrera":
                        $this->consultaInsEspecialesCarrera();
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
