<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloque_registroCargarDatosEstudiantesInscripciones extends bloque {

  public $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroCargarDatosEstudiantesInscripciones();
    $this->funcion = new funcion_registroCargarDatosEstudiantesInscripciones($this->configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
        case "cargarDatosFacultad":
          $this->funcion->ejecutarCargaPorFacultad();
          break;
        case "borrarDatosFacultad":
          $this->funcion->borrarDatosFacultad();
          break;
        case "cargarDatosProyecto":
          $this->funcion->cargarDatosProyecto();
          break;
        case "cargarDatosEstudiante":
          $this->funcion->cargarDatosEstudiante();
          break;
      }
    } else {
      $accion = "nuevo";
      $this->funcion->nuevoRegistro();
    }
  }

  function action() {
    switch ($_REQUEST['opcion']) {

      case "cargarDatosFacultad":
          echo "Un momento... estamos procesando datos";
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_cargarDatosEstudiantesInscripciones";
        $variable.="&opcion=".$_REQUEST['opcion'];
        $variable.="&facultad=" . $_REQUEST['facultad'];

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

      case "cargarDatosProyecto":
          echo "Un momento... estamos procesando datos";

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_cargarDatosEstudiantesInscripciones";
        $variable.="&opcion=".$_REQUEST['opcion'];
        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

      case "cargarDatosEstudiante":
          echo "Un momento... estamos procesando datos";

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_cargarDatosEstudiantesInscripciones";
        $variable.="&opcion=".$_REQUEST['opcion'];
        $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

      case "borrarDatosFacultad":
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_cargarDatosEstudiantesInscripciones";
        $variable.="&opcion=".$_REQUEST['opcion'];
        $variable.="&codFacultad=" . $_REQUEST['facultad'];

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

      case "inscribir":

        break;
    }
  }

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_registroCargarDatosEstudiantesInscripciones($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>