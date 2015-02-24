<?

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloque_registroActualizarPreinscripcionesSoporte extends bloque {

    private $configuracion;

    public function __construct($configuracion) {
        $this->configuracion = $configuracion;
        //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        //$this->tema = $tema;
        $this->funcion = new funcion_registroActualizarPreinscripcionesSoporte($configuracion);
        $this->sql = new sql_registroActualizarPreinscripcionesSoporte($configuracion);
    }

    function html() {
        // @ Crear un objeto de la clase funcion

        if (!isset($_REQUEST['opcion'])) {
            $_REQUEST['opcion'] = "nuevo";
        }

        switch ($_REQUEST['opcion']) {

            case "consultar":
                $this->funcion->consultar();
                break;

            case "facultad":
                $this->funcion->mostrarFormularioFacultad();
                break;

            default:
                $this->funcion->nuevoRegistro();
                break;
        }
    }

    function action() {
        switch ($_REQUEST['opcion']) {
            case "consultar":
                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                $variable = "pagina=registro_actualizarPreinscripcionesSoporte";
                $variable.="&opcion=consultar";
                $variable.="&codFacultad=" . $_REQUEST['codFacultad'];
                include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                $this->cripto = new encriptar();
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                break;

            default:

                unset($_REQUEST['action']);

                break;
        }
    }

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_registroActualizarPreinscripcionesSoporte($configuracion);

if (!isset($_REQUEST['action'])) {
    $esteBloque->html();
} else {
    $esteBloque->action();
}
?>
