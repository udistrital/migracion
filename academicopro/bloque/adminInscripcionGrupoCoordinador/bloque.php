<?
/**
 * Bloque adminInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Edwin Sanchez
 * @version 0.0.0.1
 * Fecha: 19/11/2010
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
 * Clase funcion.class.php del bloque_adminInscripcionGrupoCoordinador
 *
 * Clase que contiene toda la logica del bloque y muestra la interfaz al usuario
 */
include_once("funcion.class.php");
/**
 * Clase sql.class.php del bloque adminInscripcionGrupoCoordinador
 *
 * Clase que contiene las sentencias SQL del bloque
 */
include_once("sql.class.php");
/**
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class bloque_adminInscripcionGrupoCoordinador extends bloque
{
        /**
         * Funcion constructor del bloque adminInscripcionCoordinador
         *
         * Esta funcion permite instanciar las clases funcion y sql del bloque adminInscripcionGrupoCoordinador
         *
         * @access public
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	 public function __construct($configuracion)
	{
            $this->funcion=new funcion_adminInscripcionGrupoCoordinador($configuracion);
            $this->sql=new sql_adminInscripcionGrupoCoordinador();
	}
	
	/**
         * Funcion que redirije a las diferentes funciones de la clase funcion.class.php
         *
         * Esta funcion redirije a las funciones que contiene la clase funcion.class.php que se encuentra en el bloque adminInscripcionGrupoCoordinador,
           dependiendo de la opcion seleccionada.
         *
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	function html($configuracion)
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
                        case "consultar":
                                $this->funcion->consultarGrupos($configuracion);
                        break;

                        case "validar":
                                $this->funcion->validarEstudiante($configuracion);
                        break;

                        case "seleccionado":
                                $this->funcion->consultarGruposSeleccionado($configuracion);
                        break;

                        default:
                            $this->funcion->consultarGrupos($configuracion);
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
	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{
                        case "validar":

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminInscripcionEstudianteCoordinador";
                            $variable.="&opcion=validar";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;


                        break;
                    
                        case "buscador":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminInscripcionGrupoCoordinador";
				$variable.="&opcion=seleccionado";
				$variable.="&codEspacio=".$_REQUEST["codigoEA"];
				$variable.="&palabraEA=".$_REQUEST["palabraEA"];
				$variable.="&planEstudioCoor=".$_REQUEST["planEstudioCoor"];
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        

				
		}
		
	}
}
/**
 * Crea un nuevo objeto de la clase bloque_adminInscripcionGrupoCoordinador
 *
 * Se instancia la clase bloque_adminInscripcionGrupoCoordinador y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$obj_aprobar=new bloque_adminInscripcionGrupoCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html($configuracion);
}
else
{
	$obj_aprobar->action($configuracion);
}


?>