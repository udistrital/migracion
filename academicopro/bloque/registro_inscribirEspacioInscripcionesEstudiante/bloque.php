<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registroInscribirEspacioInscripcionesEstudiante extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroInscribirEspacioInscripcionesEstudiante($configuracion);
    $this->funcion = new funcion_registroInscribirEspacioInscripcionesEstudiante($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
        case "inscribirEspacio":
            
          $this->funcion->inscribirEspacio();
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
    switch ($_REQUEST['opcion']) {

      case "inscribirEstudiante":

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_inscribirEspacioInscripcionesEstudiante";
        $variable.="&opcion=inscribirEstudiante";
        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
        $variable.="&planEstudio=" . $_REQUEST["planEstudio"];
        $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
        $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
        $variable.="&codEstudiante=" . $_REQUEST["codEstudiante"];
        $variable.="&codEspacio=" . $_REQUEST["codEspacio"];
        $variable.="&grupo=" . $_REQUEST["grupo"];
        $variable.="&cupo=" . $_REQUEST["cupo"];
        $variable.="&carrera=" . $_REQUEST["carrera"];
        $variable.="&retornoPagina=" . $_REQUEST["retornoPagina"];
        $variable.="&retornoOpcion=" . $_REQUEST["retornoOpcion"];
        foreach ($_REQUEST['retornoParametros'] as $key => $value) {
          $variable.="&retornoParametros[" . $key . "]=" . $value;
        }
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

      case "validar":
        exit;
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_inscribirEspacioInscripcionesEstudiante";
        $variable.="&opcion=validar";
        $variable.="&codEstudiante=" . $_REQUEST["codEstudiante"];
        $variable.="&planEstudio=" . $_REQUEST["planEstudio"];
        $variable.="&creditos=" . $_REQUEST["creditos"];
        $variable.="&estado_est=" . $_REQUEST['estado_est'];
        $variable.="&codEspacio=" . $_REQUEST["codEspacio"];
        $variable.="&nombre=" . $_REQUEST["nombre"];
        $variable.="&parametro=" . $_REQUEST["parametro"];

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

      case "inscribirEspacio":

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_inscribirEspacioInscripcionesEstudiante";
        $variable.="&opcion=inscribirEspacio";
        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
        $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
        $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
        $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
        $variable.="&codEstudiante=" . $_REQUEST["codEstudiante"];
        $variable.="&estado_est=" . $_REQUEST['estado_est'];
        $variable.="&codEspacio=" . $_REQUEST["codEspacio"];
        $variable.="&nombreEspacio=" . $_REQUEST["nombreEspacio"];
        $variable.="&creditos=" . $_REQUEST["creditos"];
        $variable.="&grupo=" . $_REQUEST["grupo"];
        $variable.="&cupo=" . $_REQUEST["cupo"];
        $variable.="&carrera=" . $_REQUEST["carrera"];
        $variable.="&grupoAnterior=" . $_REQUEST["grupoAnterior"];
        $variable.="&retorno=" . $_REQUEST["retorno"];
        $variable.="&opcionRetorno=" . $_REQUEST["opcionRetorno"];


        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;
    }
  }

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_registroInscribirEspacioInscripcionesEstudiante($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>