<?

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase de ingreso al bloque de inscripcion de espacio para estudiante
class bloque_registroAdicionarTablaHomologaciones extends bloque {

  private $configuracion;

  public function __construct($configuracion) {
    $this->configuracion = $configuracion;
    $this->sql = new sql_registroAdicionarTablaHomologaciones($configuracion);
    $this->funcion = new funcion_registroAdicionarTablaHomologaciones($configuracion, $this->sql);
  }

  function html() {
    if (isset($_REQUEST['opcion'])) {
      $accion = $_REQUEST['opcion'];

      switch ($accion) {
        case "adicionar":
          $this->funcion->validarRegistro();
          break;
        case "adicionarUnion":
          $this->funcion->validarRegistroUnion();
          break;
        case "adicionarBifurcacion":
          $this->funcion->validarRegistroBifurcacion();
          break;
        case "deshabilitar":
          $this->funcion->deshabilitar();
          break;
      }
    } 
  }

  function action() {
        switch ($_REQUEST['opcion']) {
            

            case "registrar":
                //echo "registrar bloque ";exit;
                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    if(is_numeric($_REQUEST['cod_padre1']) && is_numeric($_REQUEST['cod_hijo1'])){
                        $variable = "pagina=registro_adicionarTablaHomologacion";
                        $variable.="&opcion=adicionar";
                        $variable.="&cod_proyecto=" . $_REQUEST['cod_proyecto'];
                        $variable.="&cod_padre1=" . $_REQUEST['cod_padre1'];
                        $variable.="&cod_proyecto_hom=" . $_REQUEST['cod_proyecto_hom'];
                        $variable.="&cod_hijo1=" . $_REQUEST['cod_hijo1'];
                        $variable.="&tipo_hom=normal";
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    }
                    else{
                        $mensaje="Código de espacio academico debe ser numérico";
                        $variable = "pagina=admin_homologaciones";
                        $variable.="&opcion=crearTablaHomologacion";
                        $variable.="&tipo_hom=normal";
                        $variable.="&cod_proyecto=".$_REQUEST['cod_proyecto'];
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        $this->funcion->retornar($pagina,$variable,'',$mensaje);
                                 //exit;
                    }
                    
                    break;

            case "registrarUnion":
                //echo "registrar union bloque ";exit;
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                        
                    if(is_numeric($_REQUEST['cod_padre2']) && is_numeric($_REQUEST['cod_hijo2']) && is_numeric($_REQUEST['cod_hijo3'])){
                        $variable = "pagina=registro_adicionarTablaHomologacion";
                        $variable.="&opcion=adicionarUnion";
                        $variable.="&cod_proyecto=" . $_REQUEST['cod_proyecto'];
                        $variable.="&cod_padre2=" . $_REQUEST['cod_padre2'];
                        $variable.="&cod_proyecto_hom=" . $_REQUEST['cod_proyecto_hom'];
                        $variable.="&cod_hijo2=" . $_REQUEST['cod_hijo2'];
                        $variable.="&cod_hijo3=" . $_REQUEST['cod_hijo3'];
                        $variable.="&porc_hijo2=" . $_REQUEST['porc_hijo2'];
                        $variable.="&porc_hijo3=" . $_REQUEST['porc_hijo3'];
                        $variable.="&req_hijo2=" . $_REQUEST['req_hijo2'];
                        $variable.="&req_hijo3=" . $_REQUEST['req_hijo3'];
                        $variable.="&tipo_hom=union";
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    }else{
                        $mensaje="Código de espacio academico debe ser numérico";
                        $variable = "pagina=admin_homologaciones";
                        $variable.="&opcion=crearTablaHomologacion";
                        $variable.="&tipo_hom=union";
                        $variable.="&cod_proyecto=".$_REQUEST['cod_proyecto'];
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        $this->funcion->retornar($pagina,$variable,'',$mensaje);
                                 //exit;
                    }
                    break;

            case "registrarBifurcacion": 
                //echo "registrar union bloque ";exit;
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                    $this->cripto = new encriptar();
                        
                    if(is_numeric($_REQUEST['cod_padre3']) && is_numeric($_REQUEST['cod_padre4']) && is_numeric($_REQUEST['cod_hijo4'])){
                        $variable = "pagina=registro_adicionarTablaHomologacion";
                        $variable.="&opcion=adicionarBifurcacion";
                        $variable.="&cod_proyecto=" . $_REQUEST['cod_proyecto'];
                        $variable.="&cod_hijo4=" . $_REQUEST['cod_hijo4'];
                        $variable.="&cod_proyecto_hom=" . $_REQUEST['cod_proyecto_hom'];                   
                        $variable.="&cod_padre3=" . $_REQUEST['cod_padre3'];
                        $variable.="&cod_padre4=" . $_REQUEST['cod_padre4'];
                        $variable.="&tipo_hom=bifurcacion";
                        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
                        $this->cripto = new encriptar();
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);

                        echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    }else{
                        $mensaje="Código de espacio academico debe ser numérico";
                        $variable = "pagina=admin_homologaciones";
                        $variable.="&opcion=crearTablaHomologacion";
                        $variable.="&tipo_hom=bifurcacion";
                        $variable.="&cod_proyecto=".$_REQUEST['cod_proyecto'];
                        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                        $this->funcion->retornar($pagina,$variable,'',$mensaje);
                                 //exit;
                    }
                    break;
            case "deshabilitar":
               
                    $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
                    $variable = "pagina=registro_adicionarTablaHomologacion";
                    $variable.="&opcion=deshabilitar";
                    $variable.="&estado=" . $_REQUEST['estado'];
                    $variable.="&codHomologa=" . $_REQUEST['codHomologa'];
                    $variable.="&codPpal=" . $_REQUEST['codPpal'];

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
$esteBloque = new bloque_registroAdicionarTablaHomologaciones($configuracion);
if (!isset($_REQUEST['action'])) {
  $esteBloque->html();
} else {
  if (!isset($_REQUEST['confirmar'])) {
    $esteBloque->action();
  }
}
?>