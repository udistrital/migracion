<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registro_preinscribirEspacioDemandaEstudiante extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registro_preinscribirEspacioDemandaEstudiante($configuracion);
    $this->funcion = new funcion_registro_preinscribirEspacioDemandaEstudiante($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
      
        case "verificarPreinscripcion":
          $this->funcion->verificarPreinscripcion();
          break;

        case "otrosGrupos":
          $this->funcion->buscarOtrosGrupos();
          break;

        case "inscribirEstudiante":
          $this->funcion->inscribirEstudiante($_REQUEST);
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

      case "verificarPreinscripcion":

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_preinscribirEspacioDemandaEstudiante";
        $variable.="&opcion=verificarPreinscripcion";
        $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
        $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
        $variable.="&codEstudiante=" . $_REQUEST["codEstudiante"];
        $variable.="&estado_est=" . $_REQUEST["estado_est"];
        $variable.="&tipoEstudiante=" . $_REQUEST["tipoEstudiante"];
        $a=0;
        foreach ($_REQUEST as $key => $value) {
            if (substr($key,0,7)=='espacio')
            {
                $variable.="&espacio".$a."=" . substr($key,7);
                $a++;
        }
        }

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

    default :
        break;
    }
  }

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_registro_preinscribirEspacioDemandaEstudiante($configuracion);
//echo $_REQUEST['action'];
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>