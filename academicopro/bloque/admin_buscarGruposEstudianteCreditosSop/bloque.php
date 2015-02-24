<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de busqueda de grupos de espacios en los proyectos
class bloque_adminBuscarGruposEstudianteCreditosSop extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_adminBuscarGruposEstudianteCreditosSop();
    $this->funcion = new funcion_adminBuscarGruposEstudianteCreditosSop($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
        case "adicionar":
          $this->funcion->buscarGrupo();
          break;

        case "cambiar":
          $this->funcion->cambiar();
          break;

        case "cambiarGrupo":
          $this->funcion->cambiarGrupo();
          break;

        case "validar":
          $this->funcion->adicionar();
          break;

        case "procesar":
          $this->funcion->validarCruce();
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
        break;

      case "validar":
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=admin_buscarGruposEstudianteCreditosSop";
        $variable.="&opcion=validar";
        $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
        $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
        $variable.="&planEstudio=" . (isset($_REQUEST['planEstudio'])?$_REQUEST['planEstudio']:'');
        $variable.="&codProyecto=" . (isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'');
        $variable.="&codEstudiante=" . $_REQUEST["codEstudiante"];
        $variable.="&codEspacio=" . $_REQUEST["codEspacio"];
        $variable.="&nombreEspacio=" . $_REQUEST["nombreEspacio"];
        $variable.="&creditos=" . $_REQUEST["creditos"];
        $variable.="&creditosInscritos=" . $_REQUEST['creditosInscritos'];
        $variable.="&estado_est=" . $_REQUEST['estado_est'];
        $variable.="&tipoEstudiante=" . $_REQUEST['tipoEstudiante'];
        $variable.="&parametro=" . $_REQUEST["parametro"];
        $variable.="&destino=" . $_REQUEST["destino"];
        $variable.="&retorno=" . $_REQUEST["retorno"];

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;
    }
  }

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_adminBuscarGruposEstudianteCreditosSop($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>