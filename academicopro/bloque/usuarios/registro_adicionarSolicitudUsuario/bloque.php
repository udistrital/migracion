<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registroAdicionarSolicitudUsuario extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroAdicionarSolicitudUsuario($configuracion);
    $this->funcion = new funcion_registroAdicionarSolicitudUsuario($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
        case "adicionar":
          $this->funcion->validarRegistro();
          break;
        
      }
    } 
  }

  function action() {
        switch ($_REQUEST['opcion']) {
                        
            case "inactivarSolicitud":
                        $this->funcion->inactivarRegistroSolicitud();
                        break;
                    
            case "cancelarConfirmacion":
                        $this->funcion->cancelarConfirmacion();
                        break;
                    
            case "confirmarCreacion":
                        $this->funcion->inactivarSolicitudAnterior();
                        break;
            
            case "cancelarActualizacion":
                        $this->funcion->cancelarConfirmacion();
                        break;
                    
            case "confirmarActualizacion":
                        $_REQUEST['confirmadoActualizaPerfil']='ok';
                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=registro_adicionarSolicitudUsuario";
                        $variable.="&opcion=adicionar";
                        foreach ($_REQUEST as $key => $value)
                        {
                            if($key!='opcion' && $key!='action' && $key!='pagina'){
                                $variable.="&".$key."=".$value;
                            }
                        }
                        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                        $this->cripto = new encriptar();
                    
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";                        //var_dump($_REQUEST);exit;
                        //$this->funcion->validarRegistro();
                        break;

        }
  }

}

// @ Crear un objeto bloque especifico
$esteBloque = new bloque_registroAdicionarSolicitudUsuario($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>