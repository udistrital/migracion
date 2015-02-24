<?
/**
 * Bloque admin_pagoEnLinea
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package recibos
 * @subpackage admin_pagoEnLinea
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 16/09/2013
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
 * Clase funcion.class.php del admin_pagoEnLinea
 *
 * Clase que contiene toda la logica del bloque y muestra la interfaz al usuario
 */
include_once("funcion.class.php");
/**
 * Clase sql.class.php del bloque admin_pagoEnLinea
 *
 * Clase que contiene las sentencias SQL del bloque
 */
include_once("sql.class.php");
/**
 * @package InscripcionCI
 * @subpackage Admin
 */
class bloque_adminPagoEnLinea extends bloque
{
  private  $configuracion;
        /**
         * Funcion constructor del bloque bloque_adminPagoEnLinea
         *
         * Esta funcion permite instanciar las clases funcion y sql del bloque bloque_adminPagoEnLinea
         *
         * @access public
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
        public function __construct($configuracion)
	{
           $this->configuracion=$configuracion;
           $this->funcion=new funcion_adminPagoEnLinea($configuracion);
           $this->sql=new sql_adminPagoEnLinea($configuracion);

	}

	/**
         * Funcion que redirije a las diferentes funciones de la clase funcion.class.php
         *
         * Esta funcion redirije a las funciones que contiene la clase funcion.class.php que se encuentra en el bloque bloque_adminPagoEnLinea,
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
                       
                        default:
                                $this->funcion->verificarTipoPago();
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
				$variable="pagina=admin_consultarHistoricoRecibos";
				$variable.="&opcion=consultarEstudiante";
                                $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
                                
				include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;
		}
	}
}
/**
 * Crea un nuevo objeto de la clase bloque_adminPagoEnLinea
 *
 * Se instancia la clase bloque_adminPagoEnLinea y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$obj_aprobar=new bloque_adminPagoEnLinea($configuracion);
//var_dump($_REQUEST);exit;
if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>