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
class bloque_registroMenuFlotanteVinculacion extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    //$this->tema = $tema;
    $this->funcion = new funcion_registroMenuFlotanteVinculacion($configuracion);
    $this->sql = new sql_registroMenuFlotanteVinculacion();
  }

  function html() {
    //$this->acceso_db=$this->funcion->conectarDB($configuracion);
    // @ Crear un objeto de la clase funcion


    if (!isset($_REQUEST['opcion'])) {
      $_REQUEST['opcion'] = "nuevo";
    }

    switch ($_REQUEST['opcion']) {

      case "mostrarConsulta":
        $this->funcion->mostrarMenuFlotante();
        break;

      default:
        $this->funcion->mostrarMenuFlotante();
        break;
    }
  }

  function action() {
    switch ($_REQUEST['opcion']) {
      case "nuevo":
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=admin_homologaciones";
        $variable.="&opcion=crearTablaHomologacion";
        $variable.="&cod_proyecto=" . $_REQUEST['cod_proyecto'];
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

$esteBloque = new bloque_registroMenuFlotanteVinculacion($configuracion);

if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  $esteBloque->action();
}
?>