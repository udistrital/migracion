<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registroAdicionarFechasCalendario extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroAdicionarFechasCalendario($configuracion);
    $this->funcion = new funcion_registroAdicionarFechasCalendario($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
            case "solicitarConfirmacion":
                $this->funcion->solicitarConfirmacion();
                break;
            
            case "adicionar":
                $this->funcion->validarRegistro();
                break;
        
            case "actualizar":
                $this->funcion->validarRegistroActualizar();
                break;
        
            
      }
    } 
  }

  function action() {
        switch ($_REQUEST['opcion']) {
                        
            case "confirmarInactivacion":
                        $this->funcion->inactivarRegistroFechas();
                        break;
                    
            case "cancelarConfirmacion":
                        $this->funcion->cancelarConfirmacion();
                        break;
                    
                  
            
        }
  }

}

// @ Crear un objeto bloque especifico
$esteBloque = new bloque_registroAdicionarFechasCalendario($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>