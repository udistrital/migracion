<?

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloque_registroCambiarGrupoInscripcionesEstudiante extends bloque {

    private $configuracion;

    public function __construct($configuracion) {
        $this->configuracion = $configuracion;
        $this->sql = new sql_registroCambiarGrupoInscripcionesEstudiante($this->configuracion);
        $this->funcion = new funcion_registroCambiarGrupoInscripcionesEstudiante($this->configuracion, $this->sql);
    }

    function html() {
        if (isset($_REQUEST['opcion'])) {
            $accion = $_REQUEST['opcion'];

            switch ($accion) {
                case "cambiarDeGrupo":
                    $this->funcion->cambiarDeGrupo();
                    break;
            }
        } else {
            $accion = "nuevo";
            $this->funcion->nuevoRegistro();
        }
    }

    function action() {
        unset($_REQUEST['action']);
        switch ($_REQUEST['opcion']) {
            case "inscribirEspacio":

                $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                unset($variable);
                unset($_REQUEST['opcion']);
                unset($_REQUEST['pagina']);
                $variable = "pagina=registro_cambiarGrupoInscripcionesEstudiante";
                $variable.="&opcion=cambiarDeGrupo";
                foreach ($_REQUEST as $key => $value) {
                    $variable.="&" . $key . "=" . $value;
                }
                include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                $this->cripto = new encriptar();
                $variable = $this->cripto->codificar_url($variable, $this->configuracion);

                echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                break;
        }
    }

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_registroCambiarGrupoInscripcionesEstudiante($configuracion);
if (!isset($_REQUEST['action'])) {
    $esteBloque->html();
} else {
    if (!isset($_REQUEST['confirmar'])) {
        $esteBloque->action();
    }
}
?>