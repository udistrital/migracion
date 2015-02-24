<?
/**
 * Bloque registro_mensajeCoordinador
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package Comunicaciones
 * @subpackage Perfil Coordinador
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
 * Clase funcion.class.php del bloque adminInscripcionCoordinador
 *
 * Clase que contiene toda la logica del bloque y muestra la interfaz al usuario
 */
include_once("funcion.class.php");
/**
 * Clase sql.class.php del bloque adminInscripcionCoordinador
 *
 * Clase que contiene las sentencias SQL del bloque
 */
include_once("sql.class.php");
/**
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 */
class bloque_registroMensajeCoordinador extends bloque
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
                include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_registroMensajeCoordinador($configuracion);
		$this->sql=new sql_registroMensajeCoordinador($configuracion);
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
                if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}

		switch($_REQUEST['opcion'])
		{

			case "nuevo":
				$this->funcion->mostrarFormularioMensaje();
				break;

			case "destinatarios":
				$this->funcion->seleccionarDestinatarios();
				break;

			case "enviarDestinatarios":
				$this->funcion->enviarDestinatarios();
				break;
                              
			case "enviarMesaje":
				$this->funcion->registrarMensaje();
				break;

			case "verReporteEnvioMensajesEnviados":
				$this->funcion->reporteEnvio();
				break;

			default:
                              { };
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

          //var_dump($_REQUEST);exit;
                switch($_REQUEST['opcion'])
		{
                        case "pasarDestinatario":

                          //se arma un solo arreglo de destinatarios, los dos arreglos deben existir para realizar el merge (combinar arreglos)
                            if(is_array($_REQUEST['docenteConsejero']) and is_array($_REQUEST['estudiante']))
                                {                                       
                                    $destinatario=array_merge($_REQUEST['docenteConsejero'],$_REQUEST['estudiante']);                                                                                                            
                                }
                                
                            if(is_array($_REQUEST['docenteConsejero']) and !is_array($_REQUEST['estudiante']))
                                {                                       
                                    $destinatario=$_REQUEST['docenteConsejero'];                                                                                                            
                                }
                                
                            if(!is_array($_REQUEST['docenteConsejero']) and is_array($_REQUEST['estudiante']))
                                {                                       
                                    $destinatario=$_REQUEST['docenteConsejero'];                                                                                                            
                                }

                                                                
                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=registro_mensajeCoordinador";
				$variable.="&opcion=enviarDestinatarios";
                                //ajustar los codigos de los destinatarios aqui
                                if(is_array($destinatario)){
                                foreach ($destinatario as $key => $value) {
				$variable.="&destinatario[".$key."]=".$destinatario[$key];
                                }
                                }
                                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				//echo $variable;exit;
                                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;

                          case "enviarMensaje":

                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=registro_mensajeCoordinador";
				$variable.="&opcion=enviarMesaje";
				$variable.="&receptor=".$_REQUEST['receptor'];
				$variable.="&asunto=".$_REQUEST['asunto'];
				$variable.="&contenido=".$_REQUEST['contenido'];
				$variable.="&codProyecto=".$_REQUEST['codProyecto'];

                                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";



                          break;

		}

	}


}
/**
 * Crea un nuevo objeto de la clase bloque_adminInscripcionCoordinador
 *
 * Se instancia la clase bloque_adminInscripcionCoordinador y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$esteBloque=new bloque_registroMensajeCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>