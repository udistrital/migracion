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
class bloque_admin_consultarPreinscripcionDemandaEstudiante extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    $this->tema = $tema;
    $this->funcion = new funcion_admin_consultarPreinscripcionDemandaEstudiante($configuracion);
    $this->sql = new sql_admin_consultarPreinscripcionDemandaEstudiante($configuracion);
  }

  function html() {
    //$this->acceso_db=$this->funcion->conectarDB($configuracion);
    // @ Crear un objeto de la clase funcion


    if (!isset($_REQUEST['opcion'])) {
      $_REQUEST['opcion'] = "nuevo";
    }

    switch ($_REQUEST['opcion']) {

      case "consultar":
        $this->funcion->consultar();
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
        $variable = "pagina=admin_consultarPreinscripcionDemandaEstudiante";
        $variable.="&opcion=consultar";
        $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

      case "registroAgil":
        unset($_REQUEST['action']);
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_inscripcionAgilEstudianteCoorHoras";
        $variable.="&opcion=validar";
        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
        $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
        $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];
        $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
        $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
        $variable.="&codEspacio=" . $_REQUEST['codEspacioAgil'];
        $variable.="&estado_est=" . $_REQUEST['estado_est'];
        $variable.="&grupo=" . $_REQUEST['grupo'];
        $variable.="&carrera=" . $_REQUEST['carrera'];
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

      default:

        unset($_REQUEST['action']);

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=admin_incripcionCreditos";
        $variable.="&opcion=nuevo";
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;
    }
  }

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_admin_consultarPreinscripcionDemandaEstudiante($configuracion);

if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  $esteBloque->action();
}
?>