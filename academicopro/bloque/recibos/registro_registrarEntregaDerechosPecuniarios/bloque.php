<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registroRegistrarEntregaDerechosPecuniarios extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroRegistrarEntregaDerechosPecuniarios($configuracion);
    $this->funcion = new funcion_registroRegistrarEntregaDerechosPecuniarios($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
            
            case "adicionar":
                $this->funcion->validarRegistroReciboEntrega();
                break;
        
        }
    } 
  }

  function action() {

  }

}

// @ Crear un objeto bloque especifico
$esteBloque = new bloque_registroRegistrarEntregaDerechosPecuniarios($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>