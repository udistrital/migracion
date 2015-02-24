<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloque_admin_buscarEspacioPreinscripcionDemandaEstudiante extends bloque {

  public $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_admin_buscarEspacioPreinscripcionDemandaEstudiante();
    $this->funcion = new funcion_admin_buscarEspacioPreinscripcionDemandaEstudiante($this->configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];
  
      switch ($accion) {
        case "buscarEspacio":
          $this->funcion->buscarEspaciosPermitidos();
          break;

        case "buscarEspacioExtrinsecos":
          $this->funcion->buscarEspaciosExtrinsecos();
          break;
        
        default :
          break;
      }
    } else {
      $accion = "nuevo";
      $this->funcion->nuevoRegistro();
    }
  }

  function action() {
    switch ($_REQUEST['opcion']) {

      case "adicionar":

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=admin_buscarEspacioEstudianteCoorHoras";
        $variable.="&opcion=adicionar";
        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
        $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
        $variable.="&espacio=" . $_REQUEST["espacio"];
        $variable.="&clasificacion=" . $_REQUEST["clasificacion"];
        $variable.="&codEstudiante=" . $_REQUEST["codEstudiante"];
        $variable.="&codProyectoEstudiante=" . $_REQUEST["codProyectoEstudiante"];
        $variable.="&planEstudioEstudiante=" . $_REQUEST["planEstudioEstudiante"];
        $variable.="&creditos=" . $_REQUEST["creditos"];
        $variable.="&creditosInscritos=" . $_REQUEST['creditosInscritos'];
        $variable.="&nombreEspacio=" . $_REQUEST["nombreEspacio"];
        $variable.="&estado_est=" . $_REQUEST["estado_est"];

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

      case "validar":

        break;

      case "inscribir":

        break;
    }
  }

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_admin_buscarEspacioPreinscripcionDemandaEstudiante($configuracion);

if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>