<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registroHomologacionTransferenciaInterna extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroHomologacionTransferenciaInterna($configuracion);
    $this->funcion = new funcion_registroHomologacionTransferenciaInterna($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
        case "adicionar":
            //echo "valida_reg bloque";
          $this->funcion->validarRegistroHomTransferenciaInterna();
          break;
        
      }
    } 
  }

  function action() {
            
        switch ($_REQUEST['opcion']) {
            case "registrar":
               // var_dump($_REQUEST);exit;
                $_REQUEST['codEstudiante']= isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'';
                $_REQUEST['codProyectoAnt']= isset($_REQUEST['codProyectoAnt'])?$_REQUEST['codProyectoAnt']:0;
                //echo "registrar bloque ";exit;
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = "pagina=registro_homologacionTransferenciaInterna";
                    $variable.="&opcion=adicionar";
                    //revisa el arreglo de los estudiantes
                    if(is_array($_REQUEST['codEstudiante']) && is_array($_REQUEST['codProyectoAnt'])){
                            if(count($_REQUEST['codEstudiante'])>0 && is_array($_REQUEST['codEstudiante'])){
                                foreach ($_REQUEST['codEstudiante'] as $key => $value) {
                                            $variable.="&codEstudiante[$key]=".$value;
                                        }
                            }
                            if(count($_REQUEST['codProyectoAnt'] )>0  && is_array($_REQUEST['codProyectoAnt'])){
                                foreach ($_REQUEST['codProyectoAnt'] as $key => $value) {
                                            $variable.="&codProyectoAnt[$key]=".$value;
                                        }
                            }
                            
                    }
                    //var_dump($variable);exit;

                    $variable.="&cod_proyecto=" . $_REQUEST['cod_proyecto'];
                     //var_dump($variable);exit;
                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    break;

            
        }
  }

}

// @ Crear un objeto bloque especifico
//var_dump($_REQUEST);exit;
$esteBloque = new bloque_registroHomologacionTransferenciaInterna($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>