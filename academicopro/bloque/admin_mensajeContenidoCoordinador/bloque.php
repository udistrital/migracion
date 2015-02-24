<?
/**
 * Bloque mensajeContenido
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package Comunicaciones
 * @subpackage Coordinador
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 21/07/2011
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
class bloque_adminMensajeContenidoCoordinador extends bloque
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
		$this->tema=$tema;
		$this->funcion=new funcion_adminMensajeContenidoCoordinador($configuracion);
		$this->sql=new sql_adminMensajeContenidoCoordinador($configuracion);
                include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
	}

        /**
         * Funcion que redirige a las diferentes funciones de la clase funcion.class.php
         *
         * Esta funcion redirige a las funciones que contiene la clase funcion.class.php que se encuentra en el bloque adminConsultarInscripcionGrupoCoordinador,
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
			$_REQUEST['opcion']="verContenidoMensaje";
		}

		switch($_REQUEST['opcion'])
		{

			case "verContenidoMensajeEnviado":
				$this->funcion->mostrarContenidoMensajeEnviado();
				break;
                              
			case "verContenidoMensajeRecibido":
				$this->funcion->mostrarContenidoMensajeRecibido();
				break;

			default:
				$this->funcion->nombreMetodoDefecto();
				break;
		}
	}

        /**
         * Funcion action que se encarga de capturar las variables que vienen de un formulario html
         *
         * Esta funcion se encarga de capturar las variables enviadas por un formulario html, una vez la captura lo redirige a la funcion html del bloque que se especifica
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	function action()
	{
                switch($_REQUEST['opcion'])
		{
			case "grupoSeleccionado":
                            //var_dump($_REQUEST);exit;
                            switch (trim($_REQUEST['accionCoordinador']))
                            {
                                case "nombreOpcionAction":


                                    break;
                                                                    
                                default:


                                    break;
                            }
                            
				break;

		}

	}



}
/**
 * Crea un nuevo objeto de la clase bloque_adminInscripcionCoordinador
 *
 * Se instancia la clase bloque_adminInscripcionCoordinador y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$esteBloque=new bloque_adminMensajeContenidoCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>