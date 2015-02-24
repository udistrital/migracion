<?
/**
 * Bloque registro_actualizarDatosGraduando
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package recibos
 * @subpackage registro_actualizarDatosGraduando
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 01/11/2013
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
 * Clase funcion.class.php del bloque_registroCargarDatosEgresado
 *
 * Clase que contiene toda la logica del bloque y muestra la interfaz al usuario
 */
include_once("funcion.class.php");
/**
 * Clase sql.class.php del bloque registro_actualizarDatosGraduando
 *
 * Clase que contiene las sentencias SQL del bloque
 */
include_once("sql.class.php");
/**
 * @package InscripcionCI
 * @subpackage Admin
 */
class bloque_registroCargarDatosEgresado extends bloque
{
  private  $configuracion;
        /**
         * Funcion constructor del bloque bloque_registroCargarDatosEgresado
         *
         * Esta funcion permite instanciar las clases funcion y sql del bloque bloque_registroCargarDatosEgresado
         *
         * @access public
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
        public function __construct($configuracion)
	{
           $this->configuracion=$configuracion;
           $this->funcion=new funcion_registroCargarDatosEgresado($configuracion);
           $this->sql=new sql_registroCargarDatosEgresado($configuracion);

	}

	/**
         * Funcion que redirije a las diferentes funciones de la clase funcion.class.php
         *
         * Esta funcion redirije a las funciones que contiene la clase funcion.class.php que se encuentra en el bloque bloque_registroCargarDatosEgresado,
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
                       
                        case "cargarArchivoDatos":
                                $this->funcion->cargarArchivo();
                        break;

                        case "actualizarDatosContacto":
                                $this->funcion->verificarActualizacionDatos('contacto');
                        break;

                        case "actualizarDatosTrabajoGrado":
                                $this->funcion->verificarActualizacionDatos('trabajoGrado');
                        break;
                    
                        case "actualizarDatosGrado":
                                $this->funcion->verificarActualizacionDatos('grado');
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

                      case "cargarArchivo":
                                $this->funcion->cargarArchivo();
                          break;
                      
                      default:
                          
                          break;
		}
	}
}

/**
 * Crea un nuevo objeto de la clase bloque_registroCargarDatosEgresado
 *
 * Se instancia la clase bloque_registroCargarDatosEgresado y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$obj_aprobar=new bloque_registroCargarDatosEgresado($configuracion);
if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>