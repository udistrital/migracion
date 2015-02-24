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
class bloque_adminConsultarPlanesEstudiosHoras extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    //$this->tema = $tema;
    $this->funcion = new funcion_adminConsultarPlanesEstudiosHoras($configuracion);
    $this->sql = new sql_adminConsultarPlanesEstudiosHoras($configuracion);
  }

  function html() {
    //$this->acceso_db=$this->funcion->conectarDB($configuracion);
    // @ Crear un objeto de la clase funcion


    if (!isset($_REQUEST['opcion'])) {
      $_REQUEST['opcion'] = "consultar";
    }

    switch ($_REQUEST['opcion']) {

      case "consultar":
        $this->funcion->mostrarFormulario();
        break;
      
      case "adicionar":
        $this->funcion->mostrarFormularioRegistro();
        break;
      
      default:
        $this->funcion->mostrarFormulario();
        break;
    }
  }

  function action() {
    switch ($_REQUEST['opcion']) {
      
        case "parametros":
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_consultarPlanEstudiosHoras";
            $variable.="&opcion=adicionar";
            foreach ($_REQUEST as $key => $value) {
                if($key!='opcion' && $key!='action'){
                    $variable.="&".$key."=".$value;
                }
            }
            include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
            $this->cripto = new encriptar();
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            unset($_REQUEST['action']);

            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            break;
            
        case "registrar":
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=registro_adicionarDatosPlanEstudiosHoras";
            $variable.="&opcion=adicionar";
            foreach ($_REQUEST as $key => $value) {
                if($key!='opcion' && $key!='action'){
                    $variable.="&".$key."=".$value;
                }
            }
            include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
            $this->cripto = new encriptar();
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            unset($_REQUEST['action']);

            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            break;
            
        case "consultarPlanEstudios":
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_consultarPlanEstudiosHoras";
            $variable.="&opcion=consultar";
            $variable.="&idProyecto=".$_REQUEST['idProyecto'];
            $variable.="&idPlanEstudios=".$_REQUEST['idPlanEstudios'];
            $variable.="&usuario=".$_REQUEST['usuario'];
                            
            include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
            $this->cripto = new encriptar();
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            unset($_REQUEST['action']);

            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            break;
        
      default:

        unset($_REQUEST['action']);

        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=admin_consultarPlanEstudiosHoras";
        $variable.="&opcion=consultar";
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        break;
    }
  }

}

// @ Crear un objeto bloque especifico

$esteBloque = new bloque_adminConsultarPlanesEstudiosHoras($configuracion);

if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  $esteBloque->action();
}
?>