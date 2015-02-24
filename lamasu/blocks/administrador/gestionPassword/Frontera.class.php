<?

include_once("core/manager/Configurador.class.php");

class FronteragestionPassword {

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
        //var_dump($_REQUEST); exit;
        if(isset($_REQUEST['opcion'])){

			$accion=$_REQUEST['opcion'];
                        
                        switch($accion){
				case "nuevo":
					include_once($this->ruta."formulario/nuevo.php");
					break;
                                case "recuperacionContasena":
					include_once($this->ruta."formulario/recuperacionPassword.php");
					break;
                                case "validacionDatos":
					include_once($this->ruta."formulario/validacionDatos.php");
					break;    
                                case "muestraMensaje":
                                        include_once($this->ruta."formulario/mensaje.php");
                                        break;
                                case "muestraMensajeCambioPassword":
                                        include_once($this->ruta."formulario/mensajeCambioPassword.php");
                                        break;    
                        }
		}else{
			$accion="nuevo";
                        include_once($this->ruta."/formulario/nuevo.php");
		}
    }

}

?>