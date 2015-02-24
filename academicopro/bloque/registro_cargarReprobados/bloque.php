<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloque_registroCargarReprobados extends bloque {

  public $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroCargarReprobados();
    $this->funcion = new funcion_registroCargarReprobados($this->configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
        case "cargarPreinscripciones":
          $this->funcion->consultarEspaciosPermitidos();
          break;
      case 'espacios':
          $this->funcion->consultarEspaciosPermitidos();
          break;
      }
    } else {
      $accion = "nuevo";
      $this->funcion->nuevoRegistro();
    }
  }

  function action() {
    switch ($_REQUEST['opcion']) {

      case "espacios":
          echo "Un momento... estamos procesando datos";

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_cargarReprobados";
        $variable.="&opcion=".$_REQUEST['opcion'];
        $variable.="&facultad=" . $_REQUEST['facultad'];        

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

      case "cargarPreinscripciones":
          echo "Un momento... estamos procesando datos";

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_cargarReprobados";
        $variable.="&opcion=".$_REQUEST['opcion'];
        $variable.="&facultad=" . $_REQUEST['facultad'];        

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

        break;

      case "inscribir":

        break;
    }
  }

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_registroCargarReprobados($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>