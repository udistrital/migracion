<?
/**
 * Bloque registro_reporteError
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 06/02/2012
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
 * Clase funcion.class.php del bloque
 *
 * Clase que contiene toda la logica del bloque y muestra la interfaz al usuario
 */
include_once("funcion.class.php");
/**
 * Clase sql.class.php del bloque
 *
 * Clase que contiene las sentencias SQL del bloque
 */
include_once("sql.class.php");
/**
 * @package paqueteDeLa Clase
 * @subpackage SubpaqueteDeLaClase
 */
class bloque_registroReporteError extends bloque
{

  public $configuracion;

  /**
         * Funcion constructor del bloque adminConsultarInscripcionGrupoCoordinador
         *
         * Esta funcion permite instanciar las clases funcion y sql del bloque adminConsultarInscripcionGrupoCoordinador
         *
         * @access public
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	 public function __construct($configuracion)
	{	
                $this->configuracion=$configuracion;                
		//$this->tema=$tema;
		$this->funcion=new funcion_registroReporteError($configuracion);
		$this->sql=new sql_registroReporteError($configuracion);
                //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
	}

        /**
         * Funcion que redirije a las diferentes funciones de la clase funcion.class.php
         *
         * Esta funcion redirije a las funciones que contiene la clase funcion.class.php que se encuentra en el bloque adminConsultarInscripcionGrupoCoordinador,
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

		switch($_REQUEST['opcion'])
		{

			case "mensajeError":
				$this->funcion->nuevoMensajeError();
				break;

			case "enviarMensaje":
				$this->funcion->enviarMensaje();
				break;
                            
			case "formularioEditar":
				$this->funcion->mostrarFormularioEditar();
				break;
                            
			case "actualizar":
				$this->funcion->actualizarRegistro();
				break;

			default:
				$this->funcion->nombreMetodoDefecto();
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

                                case "enviarMensaje":

                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                    $variable="pagina=registro_reporteError";
                                    $variable.="&opcion=enviarMensaje";
                                    $variable.="&paginaRetorno=".$_REQUEST['paginaRetorno'];
                                    $variable.="&opcionRetorno=".$_REQUEST['opcionRetorno'];
                                    $variable.="&asunto=".$_REQUEST['asunto'];   
                                    $variable.="&contenido=".$_REQUEST['contenido'];   
                                    include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    break;
                                                                    
                                default:

				break;

		}

	}



}
/**
 * Crea un nuevo objeto de la clase bloque_adminInscripcionCoordinador
 *
 * Se instancia la clase bloque_adminInscripcionCoordinador y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$esteBloque=new bloque_registroReporteError($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>