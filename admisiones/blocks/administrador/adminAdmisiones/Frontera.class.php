<?PHP

include_once("core/manager/Configurador.class.php");

class FronteraadminAdmisiones {

    var $ruta;
    var $sql;
    var $funcion;
    var $lenguaje;
    var $formulario;
    var $miConfigurador;

    function __construct() {

        $this->miConfigurador = Configurador::singleton();
    }

    public function setRuta($unaRuta) {
        $this->ruta = $unaRuta;
    }

    public function setLenguaje($lenguaje) {
        $this->lenguaje = $lenguaje;
    }

    public function setFormulario($formulario) {
        $this->formulario = $formulario;
    }

    function frontera() {
        $this->html();
    }

    function setSql($a) {
        $this->sql = $a;
    }

    function setFuncion($funcion) {
        $this->funcion = $funcion;
    }

    function html() {

        include_once("core/builder/FormularioHtml.class.php");

        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");


        $this->miFormulario = new formularioHtml();
        //var_dump($_REQUEST);
        if (isset($_REQUEST['opcion'])) {

            $accion = $_REQUEST['opcion'];

            switch ($accion) {
                case "nuevo":
                    include_once($this->ruta . "formulario/nuevo.php");
                    break;
                case "formEditarPeriodo":
                    include_once($this->ruta . "/formulario/formEditarPeriodo.php");
                    break;
                case "eventos":
                    include_once($this->ruta . "/formulario/abrirFechasEventos.php");
                    break;
                case "muestraMensaje":
                    include_once($this->ruta . "formulario/mensaje.php");
                    break;
                case "editarEventoCarrera":
                    include_once($this->ruta . "/formulario/editarEventoCarrera.php");
                    break;
                case "medios":
                    include_once($this->ruta . "/formulario/actualizarMedios.php");
                    break;
                case "editarMedio":
                    include_once($this->ruta . "/formulario/editarMedios.php");
                    break;
                case "salmin":
                    include_once($this->ruta . "/formulario/salarioMinimo.php");
                    break;
                case "editarSalMin":
                    include_once($this->ruta . "/formulario/editarSalMin.php");
                    break;
                case "localidades":
                    include_once($this->ruta . "/formulario/localidades.php");
                    break;
                case "editarLocalidad":
                    include_once($this->ruta . "/formulario/editarLocalidad.php");
                    break;
                case "estratos":
                    include_once($this->ruta . "/formulario/estratos.php");
                    break;
                case "editarEstrato":
                    include_once($this->ruta . "/formulario/editarEstratos.php");
                    break;
                case "instructivo":
                    include_once($this->ruta . "/formulario/instructivo.php");
                    break;
                case "colillas":
                    include_once($this->ruta . "/formulario/colillas.php");
                    break;
                case "formEditarColilla":
                    include_once($this->ruta . "/formulario/formEditarColilla.php");
                    break;
                case "registrarPines":
                    include_once($this->ruta . "/formulario/registrarPines.php");
                    break;
                case "habilitarCarreras":
                    include_once($this->ruta . "/formulario/habilitarCarreras.php");
                    break;
                case "registarTipInscripcion":
                    include_once($this->ruta . "/formulario/registarTipInscripcion.php");
                    break;
                case "editarTipInscripcion":
                    include_once($this->ruta . "/formulario/editarTipInscripcion.php");
                    break;
                case "registrarTipDiscapacidad":
                    include_once($this->ruta . "/formulario/registrarTipDiscapacidad.php");
                    break;
                case "editarDiscapacidad":
                    include_once($this->ruta . "/formulario/editarDiscapacidad.php");
                    break;
                case "registrarPreguntas":
                    include_once($this->ruta . "/formulario/registrarPreguntas.php");
                    break;
                case "editarPreguntas":
                    include_once($this->ruta . "/formulario/formEditarPreguntas.php");
                    break;
                case "registrarEncabezados":
                    include_once($this->ruta . "/formulario/registrarEncabezados.php");
                    break;
                case "editarEncabezados":
                    include_once($this->ruta . "/formulario/formEditarEncabezados.php");
                    break;
                case "snpAspirantes":
                    include_once($this->ruta . "/formulario/snpAspirantes.php");
                    break;
                case "editarSnp":
                    include_once($this->ruta . "/formulario/formEditarSnp.php");
                    break;
                case "referenciaBancaria":
                    include_once($this->ruta . "/formulario/consultaReferenciaBancaria.php");
                    break;
                case "registrarIcfes":
                    include_once($this->ruta . "/formulario/registrarIcfes.php");
                    break;
                case "registrarDocumentacion":
                    include_once($this->ruta . "/formulario/registrarDocumentacion.php");
                    break;
                case "formEditarDocumentacion":
                    include_once($this->ruta . "/formulario/formEditarDocumentacion.php");
                    break;
                case "detalleAcasp":
                    include_once($this->ruta . "/formulario/detalleAcasp.php");
                    break;
                case "editarInscripcion":
                    include_once($this->ruta . "/formulario/editarInscripcion.php");
                    break;
                case "formEditarInscripcion":
                    include_once($this->ruta . "/formulario/formEditarInscripcion.php");
                    break;
                case "copiarInscripciones":
                    include_once($this->ruta . "/formulario/copiarInscripciones.php");
                    break;
                case "consultaInscripciones":
                    include_once($this->ruta . "/formulario/consultaInscripciones.php");
                    break;
                case "subirPdf":
                    include_once($this->ruta . "/formulario/subirPdf.php");
                    break;
                case "subirPdfEspeciales":
                    include_once($this->ruta . "/formulario/subirPdfEspeciales.php");
                    break;
                case "calcularResultados":
                    include_once($this->ruta . "/formulario/calcularResultados.php");
                    break;
                case "cargarAdmitidos":
                    include_once($this->ruta . "/formulario/cargarAdmitidos.php");
                    break;
                case "marcarAdmitidosRangos":
                    include_once($this->ruta . "/formulario/marcarAdmitidos.php");
                    break;
                case "marcarAdmitidosCredencial":
                    include_once($this->ruta . "/formulario/marcarAdmitidosCredencial.php");
                    break;
                case "consultaxcarrera":
                    include_once($this->ruta . "/formulario/consultarxcarreras.php");
                    break;
                case "formIncritosCarrera":
                    include_once($this->ruta . "/formulario/formIncritosCarrera.php");
                    break;
                case "inscritosxFacultad":
                    include_once($this->ruta . "/formulario/formInscritosxFacultad.php");
                    break;
                case "consultaEspecialesxcarrera":
                    include_once($this->ruta . "/formulario/consultarEspecialesxcarrera.php");    
                    break;
                case "formInsEspCarrera":
                    include_once($this->ruta . "/formulario/formInsEspCarrera.php");
                    break;
            }
        } else {
            $accion = "nuevo";
            include_once($this->ruta . "/formulario/nuevo.php");
        }
    }

}

?>