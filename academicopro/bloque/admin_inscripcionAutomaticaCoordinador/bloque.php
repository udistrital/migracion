<?
/**
 * Bloque adminInscripcionAutomaticaCoordinador
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package InscripcionAutomaticaCoordinadorPorGrupo
 * @subpackage Admin
 * @author Milton Parra 
 * @version 0.0.0.1
 * Fecha: 14/01/2013
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
 * Clase funcion.class.php del bloque adminInscripcionAutomaticaCoordinador
 *
 * Clase que contiene toda la logica del bloque y muestra la interfaz al usuario
 */
include_once("funcion.class.php");
/**
 * Clase sql.class.php del bloque adminInscripcionAutomaticaCoordinador
 *
 * Clase que contiene las sentencias SQL del bloque
 */
include_once("sql.class.php");
/**
 * @package InscripcionAutomaticaCoordinador
 * @subpackage Admin
 */
class bloque_adminInscripcionAutomaticaCoordinador extends bloque
{
/**
 * Se crean atributos de la clase
 */
private $configuracion;

/**
         * Funcion constructor del bloque adminInscripcionAutomaticaCoordinador
         *
         * Esta funcion permite instanciar las clases funcion y sql del bloque adminInscripcionAutomaticaCoordinador
         *
         * @access public
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	 public function __construct($configuracion)
	{
             $this->configuracion=$configuracion;
            $this->funcion=new funcion_adminInscripcionAutomaticaCoordinador($configuracion);
            $this->sql=new sql_adminInscripcionAutomaticaCoordinador($configuracion);
	}
	
	/**
         * Funcion que redirije a las diferentes funciones de la clase funcion.class.php
         *
         * Esta funcion redirije a las funciones que contiene la clase funcion.class.php que se encuentra en el bloque adminInscripcionAutomaticaCoordinador,
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
			$_REQUEST['opcion']="mostrar";
		}
		
		switch($_REQUEST['opcion'])
		{
                        case "parametrosInscripcionAuto":
                                $this->funcion->verProyectos();
                        break;
                    
			case "mostrarReporte":
				$this->funcion->mostrarRegistro();
			break;
			
			default:
				$this->funcion->mostrarRegistro();
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
                        case "proyectos":

                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarPlanEstudioCoordinador";
                            $variable.="&opcion=mostrar";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;

                        break;

		}
		
	}
}
/**
 * Crea un nuevo objeto de la clase bloque_adminInscripcionAutomaticaCoordinador
 *
 * Se instancia la clase bloque_adminInscripcionAutomaticaCoordinador y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$obj_aprobar=new bloque_adminInscripcionAutomaticaCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>