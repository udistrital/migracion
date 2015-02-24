<?

include_once("core/manager/Configurador.class.php");

class FronteraInterfazVoto {

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

    function redireccionar($opcion, $datos ) {
        include_once($this->ruta . "formulario/redireccionar.php");
    }
    
    function html() {
        include_once("core/builder/FormularioHtml.class.php");

        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");


        $this->miFormulario = new formularioHtml();

        if (isset($_REQUEST['opcion'])) {

            $accion = $_REQUEST['opcion'];

            switch ($accion) {

                case "nuevo":
                    include_once($this->ruta . "formulario/nuevo.php");
                    break;

                case "crearSalida":

                    if (strlen($_REQUEST["entrada"]) != 0 && strlen($_REQUEST["elemento"]) != 0) {
                        $idEntrada = $_REQUEST["entrada"];
                        $idElemento = $_REQUEST["elemento"];
                    } else {
                        echo "...Error debe seleccionar una entrada y un elemento...";
                        exit;
                    }

                    $conexion = "inventario";
                    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
                    $cadena_sql = $this->sql->cadena_sql("consultarEntrada", $idEntrada);
                    $entrada = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                    $entrada = $entrada[0];

                    $cadena_sql = $this->sql->cadena_sql("consultarElemento", $idElemento);
                    $elemento = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                    $elemento = $elemento[0];

                    $cadena_sql = $this->sql->cadena_sql("obtenerIdSalida", $elemento["tbi_descripcion"]);
                    $idSalida = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                    $idSalida = $idSalida[0][0] + 1; //Toma el último valor de la ConsultaCenso generada según el tipo de bien

                    include_once($this->ruta . "formulario/crearSalida.php");

                    break;

                case "confirmar":
                    include_once($this->ruta . "formulario/confirmar.php");
                    break;
                case "mostrarMensaje":
                    include_once($this->ruta . "formulario/mostrarMensaje.php");
                    break;
            }
        } else {
            $accion = "nuevo";
            include_once($this->ruta . "/formulario/nuevo.php");
        }
    }

}

?>