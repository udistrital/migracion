<?

include_once("core/manager/Configurador.class.php");

class FronteraarmarFormulariosEvaldocente {

    var $ruta;
    var $sql;
    var $funcion;
    var $lenguaje;
    var $formulario;
    var $miConfigurador;

    function __construct() {

        $this->miConfigurador = Configurador::singleton();
		
		
    }

    public function setRuta($unaRuta) {
        $this->ruta = $unaRuta;
    }

    public function setLenguaje($lenguaje) {
        $this->lenguaje = $lenguaje;
    }

    public function setFormulario($formulario) {
        $this->formulario = $formulario;
    }

    function frontera() {
        $this->html();
    }

    function setSql($a) {
        $this->sql = $a;
    }

    function setFuncion($funcion) {
        $this->funcion = $funcion;
    }

    function html() {

        include_once("core/builder/FormularioHtml.class.php");

        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");


        $this->miFormulario = new formularioHtml();
        if(isset($_REQUEST['opcion'])){
                        //var_dump($_REQUEST);    
			$accion=$_REQUEST['opcion'];
                        
                        switch($accion){
				case "nuevo":
					include_once($this->ruta."formulario/nuevo.php");
					break;
				case "formatos":
                                        include_once($this->ruta."/formulario/formatos.php");
                                        break;
                                case "asociarFormatos":
                                        include_once($this->ruta."/formulario/asociarFormatos.php");
                                        break;    
				case "encabezados":
                                        include_once($this->ruta."/formulario/encabezados.php");
                                        break;
                                case "preguntas":
                                        include_once($this->ruta."/formulario/preguntas.php");
                                        break;    
                                case "muestraMensaje":
					include_once($this->ruta."formulario/mensaje.php");
					break;
                                case "editarFormatos":
                                        include_once($this->ruta."formulario/editarFormatos.php");
                                        break;
                                case "editarEncabezados":
                                        include_once($this->ruta."formulario/editarEncabezados.php");
				break;
                                case "editarPreguntas":
                                        include_once($this->ruta."formulario/editarPreguntas.php");
                                break;
                                case "armarFormulario":
                                        include_once($this->ruta."formulario/armarFormularios.php");
                                break;
                        }
		}else{
			$accion="nuevo";
                        include_once($this->ruta."/formulario/nuevo.php");
		}
    }

}

?>
