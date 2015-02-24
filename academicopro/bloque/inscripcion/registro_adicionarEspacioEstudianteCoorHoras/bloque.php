<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registroAdicionarEspacioEstudianteCoorHoras extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroAdicionarEspacioEstudianteCoorHoras($configuracion);
    $this->funcion = new funcion_registroAdicionarEspacioEstudianteCoorHoras($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
        case "inscribir":
          $this->funcion->validarInscripcionEstudiante();
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
        $variable = "pagina=registro_adicionarEspacioEstudianteCoorHoras";
        $variable.="&opcion=inscribirEstudiante";
        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
        $variable.="&planEstudio=" . $_REQUEST["planEstudio"];
        $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
        $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
        $variable.="&codEstudiante=" . $_REQUEST["codEstudiante"];
        $variable.="&codEspacio=" . $_REQUEST["codEspacio"];
        $variable.="&nivel=" . (isset($_REQUEST["nivel"])?$_REQUEST["nivel"]:'');
        $variable.="&grupo=" . (isset($_REQUEST["grupo"])?$_REQUEST["grupo"]:'');
        $variable.="&id_grupo=" . $_REQUEST["id_grupo"];
        $variable.="&hor_alternativo=" . $_REQUEST["hor_alternativo"];
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

//                        case "adicionar":
//
//                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
//				$variable="pagina=registro_adicionarEspacioEstudianteCoorHoras";
//				$variable.="&opcion=adicionar";
//                                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
//                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
//                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
//                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
//                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
//                                $variable.="&carrera=".$_REQUEST["carrera"];
//                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
//                                $variable.="&creditos=".$_REQUEST["creditos"];
//                                $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
//                                $variable.="&nombre=".$_REQUEST["nombre"];
//                                $variable.="&estado_est=".$_REQUEST["estado_est"];
//
//                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
//				$this->cripto=new encriptar();
//				$variable=$this->cripto->codificar_url($variable,$this->configuracion);
//
//				echo "<script>location.replace('".$pagina.$variable."')</script>";
//				break;

      case "validar":

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_adicionarEspacioEstudianteCoorHoras";
        $variable.="&opcion=validar";
        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
        $variable.="&planEstudioGeneral=" . $_REQUEST['planEstudioGeneral'];
        $variable.="&codEstudiante=" . $_REQUEST["codEstudiante"];
        $variable.="&planEstudio=" . $_REQUEST["planEstudio"];
        $variable.="&creditos=" . $_REQUEST["creditos"];
        $variable.="&estado_est=" . $_REQUEST['estado_est'];
        $variable.="&codEspacio=" . $_REQUEST["codEspacio"];
        $variable.="&nivel=" . $_REQUEST["nivel"];
        $variable.="&grupo=" . $_REQUEST["grupo"];
        $variable.="&id_grupo=" . $_REQUEST["id_grupo"];
        $variable.="&hor_alternativo=" . $_REQUEST["hor_alternativo"];
        $variable.="&nombre=" . $_REQUEST["nombre"];
        $variable.="&parametro=" . $_REQUEST["parametro"];

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;

      case "inscribir":

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=registro_adicionarEspacioEstudianteCoorHoras";
        $variable.="&opcion=inscribir";
        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
        $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
        $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
        $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
        $variable.="&codEstudiante=" . $_REQUEST["codEstudiante"];
        $variable.="&estado_est=" . $_REQUEST['estado_est'];
        $variable.="&codEspacio=" . $_REQUEST["codEspacio"];
        $variable.="&nivel=" . $_REQUEST["nivel"];
        $variable.="&grupo=" . $_REQUEST["grupo"];
        $variable.="&id_grupo=" . $_REQUEST["id_grupo"];
        $variable.="&hor_alternativo=" . $_REQUEST["hor_alternativo"];
        $variable.="&creditos=" . $_REQUEST["creditos"];
        $variable.="&grupo=" . $_REQUEST["grupo"];
        $variable.="&carrera=" . $_REQUEST["carrera"];


        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;
    }
  }

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_registroAdicionarEspacioEstudianteCoorHoras($configuracion);
//echo $_REQUEST['action'];
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>