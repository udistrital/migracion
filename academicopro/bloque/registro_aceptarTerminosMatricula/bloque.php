<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registroAceptarTerminosMatricula extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroAceptarTerminosMatricula($configuracion);
    $this->funcion = new funcion_registroAceptarTerminosMatricula($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
        case "confirmacion":
          $this->funcion->solicitarConfirmacion();
          break;
        
        case "verificar":
          $this->funcion->verificarYaAcepto();
          break;
        
      }
    } 
  }

  function action() {
        switch ($_REQUEST['opcion']) {
                                   
            case "consultar":
                        echo "consultar";exit;

                        break;
                    
            case "cancelarAceptaTerminos":
                                
                        $this->funcion->cancelarConfirmacion();
                        break;
                    
            case "confirmarAceptaTerminos":
                //echo "confirma aceptar terminos";exit;
                        $this->funcion->validarRegistroAceptacion();
                        break;

        }
  }

}

// @ Crear un objeto bloque especifico
//var_dump($_REQUEST);exit;
$esteBloque = new bloque_registroAceptarTerminosMatricula($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>