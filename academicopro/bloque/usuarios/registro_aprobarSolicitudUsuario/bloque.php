<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registroaprobarSolicitudUsuario extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroaprobarSolicitudUsuario($configuracion);
    $this->funcion = new funcion_registroaprobarSolicitudUsuario($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
        case "validar":
            $this->funcion->validarRegistro();
            break;
        case "enlace":
            $this->funcion->enviaEnlace();
            break;
      }
    } 
  }

  function action() {
        switch ($_REQUEST['opcion']) {
            

            case "aprobar":
                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $_REQUEST['idSolicitud'] = (isset($_REQUEST['idSolicitud'])?$_REQUEST['idSolicitud']:'');
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    if(is_numeric($_REQUEST['idSolicitud'])){
                        $variable = "pagina=registro_aprobarSolicitudUsuario";
                        $variable.="&opcion=validar";
                        $variable.="&idSolicitud=" . $_REQUEST['idSolicitud'];
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        
                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    }
                    else{
                        $mensaje="El código de la solicitud debe ser numérico";
                        $variable = "pagina=admin_consultarSolicitudesUsuario";
                        $variable.="&opcion=consultar";
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        $this->funcion->retornar($pagina,$variable,'',$mensaje);
                                 //exit;
                    }
                    
                    break;


        }
  }

}

// @ Crear un objeto bloque especifico
//var_dump($_REQUEST);exit;
$esteBloque = new bloque_registroaprobarSolicitudUsuario($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>