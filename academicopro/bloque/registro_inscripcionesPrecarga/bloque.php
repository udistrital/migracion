<?
/**
 * Bloque nombreBloque
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 08/09/2011
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
class bloque_registroInscripcionesPrecarga extends bloque
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
		$this->funcion=new funcion_registroInscripcionesPrecarga($configuracion);
		$this->sql=new sql_registroInscripcionesPrecarga($configuracion);
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

			case "mostrarProyectos":
				$this->funcion->mostrarFormularioOpcionesCarga();
				break;

			case "cargarDatosFacultad":
				$this->funcion->mostrarFormularioCargaFacultad();
				break;

			case "cargarDatosProyecto":
				$this->funcion->mostrarFormularioCargaProyecto();
				break;

			case "cargarDatosEstudiante":
				$this->funcion->mostrarFormularioCargaEstudiante();
				break;

			case "cargarHorarios":                            
				$this->funcion->cargarHorarios();
				break;

			default:
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
            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();
            unset($_REQUEST['action']);

                switch($_REQUEST['opcion'])
		{
			case "cargarDatosFacultad":
                                    
                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                    $variable="pagina=registro_inscripcionesPrecarga";
                                    $variable.="&opcion=cargarDatosFacultad";   
                                    
                                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    break;
                            
			case "cargarDatosProyecto":
                                    
                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                    $variable="pagina=registro_inscripcionesPrecarga";
                                    $variable.="&opcion=cargarDatosProyecto";   
                                    
                                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    break;
                            
			case "cargarDatosEstudiante":
                                    
                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                    $variable="pagina=registro_inscripcionesPrecarga";
                                    $variable.="&opcion=cargarDatosEstudiante";   
                                    
                                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    break;
                            
			case "mostrarProyectos":
                                    
                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                    $variable="pagina=registro_inscripcionesPrecarga";
                                    $variable.="&opcion=mostrarProyectos";   
                                    
                                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    break;
                            
			case "cargarHorarios":
                                    
                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                    $variable="pagina=registro_inscripcionesPrecarga";
                                    $variable.="&opcion=cargarHorarios";
                                    
                                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    
				break;
                            default:
                                echo "Hola";
                                break;

		}

	}



}
/**
 * Crea un nuevo objeto de la clase bloque_adminInscripcionCoordinador
 *
 * Se instancia la clase bloque_adminInscripcionCoordinador y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$esteBloque=new bloque_registroInscripcionesPrecarga($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html();
}
else
{
	$esteBloque->action();
}


?>