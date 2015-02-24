<?
/**
 * Bloque admin_cambiarEstadoRecibo
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package recibos
 * @subpackage admin_cambiarEstadoRecibo
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 25/06/2013
*/
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * Incluye la clase abstracta bloque.class.php
 *
 * Esta clase crea las funciones _construct, html y action
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
/**
 * Clase funcion.class.php del bloque_adminCambiarEstadoRecibo
 *
 * Clase que contiene toda la logica del bloque y muestra la interfaz al usuario
 */
include_once("funcion.class.php");
/**
 * Clase sql.class.php del bloque admin_cambiarEstadoRecibo
 *
 * Clase que contiene las sentencias SQL del bloque
 */
include_once("sql.class.php");
/**
 * @package InscripcionCI
 * @subpackage Admin
 */
class bloque_adminCambiarEstadoRecibo extends bloque
{
  private  $configuracion;
        /**
         * Funcion constructor del bloque bloque_adminCambiarEstadoRecibo
         *
         * Esta funcion permite instanciar las clases funcion y sql del bloque bloque_adminCambiarEstadoRecibo
         *
         * @access public
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
        public function __construct($configuracion)
	{
           $this->configuracion=$configuracion;
           $this->funcion=new funcion_adminCambiarEstadoRecibo($configuracion);
           $this->sql=new sql_adminCambiarEstadoRecibo($configuracion);

	}

	/**
         * Funcion que redirije a las diferentes funciones de la clase funcion.class.php
         *
         * Esta funcion redirije a las funciones que contiene la clase funcion.class.php que se encuentra en el bloque bloque_adminCambiarEstadoRecibo,
           dependiendo de la opcion seleccionada.
         *
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
        function html()
	{
                /**
                 * @global string $_REQUEST['opcion'] variable que contiene la opcion para ser dirijido a una funcion especifica
                 */
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="consultar";
		}

		switch($_REQUEST['opcion'])
		{
                        case "consultarEstudiante":
                                $this->funcion->mostrarFormularioConsulta();
                        break;

                        case "solicitarConfirmacion":
                                $this->funcion->solicitarConfirmacionCambioEstado();
                        break;
                        default:
                                $this->funcion->mostrarFormularioConsulta();
                        break;

		}
	}

	/**
         * Funcion action que se encarga de capturar las variables que vienen de un formulario html
         *
         * Esta funcion se encarga de capturar las variables enviadas por un formulario html, una vez la captura lo redirije a la funcion html del bloque que se especifica
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
        function action()
	{

            switch($_REQUEST['opcion'])
		{

                        case "consultar":
                          //unset ($_REQUEST['action']);
                          
                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=admin_cambiarEstadoRecibo";
				$variable.="&opcion=consultarEstudiante";
                                $variable.="&datoBusqueda=".$_REQUEST['datoBusqueda'];
                                $variable.="&tipoBusqueda=".$_REQUEST['tipoBusqueda'];
                                
				include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;
                      
                          
                        case "confirmarCambio":

                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=registro_cambiarEstadoReciboPago";
				$variable.="&action=recibos/registro_cambiarEstadoReciboPago";
				$variable.="&opcion=confirmarCambio";
                                foreach ($_REQUEST as $key => $value) {
                                    if($key!='opcion' && $key!='action'){
                                        $variable.="&".$key."=".$value;
                                    }
                                }
				include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;
                          
                        case "cancelarConfirmacion":
                            $this->funcion->cancelarConfirmacion();
                        break;
            
		}
	}
}
/**
 * Crea un nuevo objeto de la clase bloque_adminCambiarEstadoRecibo
 *
 * Se instancia la clase bloque_adminCambiarEstadoRecibo y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$obj_aprobar=new bloque_adminCambiarEstadoRecibo($configuracion);
if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>