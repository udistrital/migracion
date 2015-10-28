<?

include_once("core/manager/Configurador.class.php");

class Fronterainstructivo {

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
                case "instructivo":
                    include_once($this->ruta . "/formulario/instructivo.php");
                    break;
                case "mensajeAceptacion":
                    include_once($this->ruta . "/formulario/mensaje.php");
                    break;
                case "muestraMensaje":
                    include_once($this->ruta."formulario/mensaje.php");
                    break;
                case "seleccionarInscripcion":
                    include_once($this->ruta . "/formulario/seleccionarInscripcion.php");
                    break;
                case "carrerasOfrecidas":
                    include_once($this->ruta . "/formulario/carrerasOfrecidas.php");
                    break;
                case "formularioInscripcion":
                    include_once($this->ruta . "/formulario/formularioInscripcion.php");
                    break;
                case "verificaInscripcion":
                    include_once($this->ruta . "/formulario/formValidaInscripcion.php");
                    break;
                case "verificaInscripcionTransInt":
                    include_once($this->ruta . "/formulario/formValidaInscripcionTransInt.php");
                    break;
                case "verInscripcion":
                    include_once($this->ruta . "/formulario/verInscripcion.php");
                    break;
                case "verificaInscripcionTranExterna":
                    include_once($this->ruta . "/formulario/formValidaInscripcionTransExt.php");
                    break;
            }
        } else {
            $accion = "nuevo";
            include_once($this->ruta . "/formulario/nuevo.php");
        }
    }

}

?>