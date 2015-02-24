<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registroCambiarEstadoReciboPago extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroCambiarEstadoReciboPago($configuracion);
    $this->funcion = new funcion_registroCambiarEstadoReciboPago($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
            
            case "adicionar":
                $this->funcion->validarRegistroRecibo();
                break;
        
        }
    } 
  }

  function action() {
        switch ($_REQUEST['opcion']) {
                        
            case "confirmarCambio":
                        $this->funcion->cambiarEstado();
                        break;
                    
            case "cancelarConfirmacion":
                        $this->funcion->cancelarConfirmacion();
                        break;
                    
                  
            
        }
  }

}
//var_dump($_REQUEST);exit;
// @ Crear un objeto bloque especifico
$esteBloque = new bloque_registroCambiarEstadoReciboPago($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>