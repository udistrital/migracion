<?

include_once("core/manager/Configurador.class.php");

class FronteraregistroEvaluacionDocente {

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

        if (isset($_REQUEST['opcion'])) {
            //var_dump($_REQUEST);    
            $accion = $_REQUEST['opcion'];

            switch ($accion) {
                case "listaDocentes":
                    include_once($this->ruta . "formulario/listaDocentes.php");
                    break;
                case "muestraMensaje":
                    include_once($this->ruta . "formulario/mensaje.php");
                    break;
                case "nuevo":
                    include_once($this->ruta . "/formulario/nuevo.php");
                    break;
                case "formularios":
                    include_once($this->ruta . "/formulario/formularios.php");
                    break;
                case "tiposEvaluacion":
                    include_once($this->ruta . "/formulario/tiposEvaluacion.php");
                    break;
                case "estadoEvaluacion":
                    include_once($this->ruta . "/formulario/estadoEvaluacion.php");
                    break;
                case "EstudiantesSinEvaluar":
                    include_once($this->ruta . "/formulario/estudiantesSinEvaluar.php");
                    break;
                case "observacionesEstudiantes":
                    include_once($this->ruta . "/formulario/observacionesEstudiantes.php");
                    break;
                case "enviaCorreos":
                    include_once($this->ruta . "/formulario/enviaCorreos.php");
                    break;
                case "resultados":
                    include_once($this->ruta . "/formulario/resultados.php");
                    break;
                case "resultadosCatedras":
                    include_once($this->ruta . "/formulario/resultadosCatedras.php");
                    break;
                case "listaFacultades":
                    include_once($this->ruta . "/formulario/listaFacultades.php");
                    break;
                //Para que desde el perfil Docente, se vean los resultados parciales de la evaluación docente 
                //y las observaciones realizadas por los estudiantes.  
                case "resultadosEvaluacion":
                    include_once($this->ruta . "/formulario/resultadosEvaluacion.php");
                    break;
                case "resultadosParciales":
                    include_once($this->ruta . "/formulario/resultados.php");
                    break;
                case "avanceEvaluacion":
                    include_once($this->ruta . "/formulario/avanceEvaluacion.php");
                    break;
                case "consultaObservacionesCoordinador":
                    include_once($this->ruta . "/formulario/consultaObservacionesCoordinador.php");
                    break;
                case "observacionesDocentesPorEstudiantes":
                    include_once($this->ruta . "/formulario/observacionesDocentesPorEstudiantes.php");
                    break;
            }
        } else {
            $accion = "nuevo";
            include_once($this->ruta . "/formulario/nuevo.php");
        }
    }

}

?>
