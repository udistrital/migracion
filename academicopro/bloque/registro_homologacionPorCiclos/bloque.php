<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registroHomologacionPorCiclos extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroHomologacionPorCiclos($configuracion);
    $this->funcion = new funcion_registroHomologacionPorCiclos($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
        case "adicionar":
          $this->funcion->validarRegistroHomPorCiclos();
          break;
        
      }
    } 
  }

  function action() {
        switch ($_REQUEST['opcion']) {
            case "registrar":
                $_REQUEST['codEstudiante']= (isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'');
                $_REQUEST['codEstudianteAnt']= (isset($_REQUEST['codEstudianteAnt'])?$_REQUEST['codEstudianteAnt']:'');
                $_REQUEST['cohorte']= (isset($_REQUEST['cohorte'])?$_REQUEST['cohorte']:0);
                $_REQUEST['cod_proyectoAnt']= (isset($_REQUEST['cod_proyectoAnt'])?$_REQUEST['cod_proyectoAnt']:0);
                $_REQUEST['cadenaEstudiante']= (isset($_REQUEST['cadenaEstudiante'])?$_REQUEST['cadenaEstudiante']:'');
                $_REQUEST['cadenaEstudianteAnt']= (isset($_REQUEST['cadenaEstudianteAnt'])?$_REQUEST['cadenaEstudianteAnt']:'');
                $cadenaEstudiante=  explode(';', $_REQUEST['cadenaEstudiante']);
                $cadenaEstudianteAnt=  explode(';', $_REQUEST['cadenaEstudianteAnt']);
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = "pagina=registro_homologacionPorCiclos";
                    $variable.="&opcion=adicionar";
                    if(count($_REQUEST['codEstudiante'])>0 && is_array($_REQUEST['codEstudiante'])){
                        foreach ($_REQUEST['codEstudiante'] as $key => $value) {
                                    $variable.="&codEstudiante[$key]=".$value;
                                }
                                
                    }
                    if(count($_REQUEST['codEstudianteAnt'] )>0  && is_array($_REQUEST['codEstudianteAnt'])){
                        foreach ($_REQUEST['codEstudianteAnt'] as $key => $value) {
                                    $variable.="&codEstudianteAnt[$key]=".$value;
                                }
                    }
                    if(count($cadenaEstudiante)>0 && is_array($cadenaEstudiante)&&(is_numeric($cadenaEstudiante[0]))){
                                foreach ($cadenaEstudiante as $key => $value) {
                                            $variable.="&codEstudiante[$key]=".$value;
                                        }
                            }
                            if(count($cadenaEstudianteAnt)>0  && is_array($cadenaEstudianteAnt) &&(is_numeric($cadenaEstudianteAnt[0]))){
                                foreach ($cadenaEstudianteAnt as $key => $value) {
                                            $variable.="&codEstudianteAnt[$key]=".$value;
                                        }
                            }
                    $variable.="&cod_proyecto=" . $_REQUEST['cod_proyecto'];
                    $variable.="&tipo_homologacion=" . $_REQUEST['tipo_homologacion'];
                    if($_REQUEST['cod_proyectoAnt'])
                        $variable.="&cod_proyectoAnt=" . $_REQUEST['cod_proyectoAnt'];
                    if($_REQUEST['cohorte'])
                        $variable.="&cohorte=" . $_REQUEST['cohorte'];
                    
                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    break;

            
        }
  }

}

// @ Crear un objeto bloque especifico
$esteBloque = new bloque_registroHomologacionPorCiclos($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>