<?
/**
 * Bloque adminInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 10/03/2011
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
 * Clase funcion.class.php del bloque_adminInscripcionGrupoCoordinadorPosgrado
 *
 * Clase que contiene toda la logica del bloque y muestra la interfaz al usuario
 */
include_once("funcion.class.php");
/**
 * Clase sql.class.php del bloque adminInscripcionGrupoCoordinadorPosgrado
 *
 * Clase que contiene las sentencias SQL del bloque
 */
include_once("sql.class.php");
/**
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class bloque_adminInscripcionGrupoCoordinadorPosgrado extends bloque
{
  private  $configuracion;
        /**
         * Funcion constructor del bloque adminInscripcionCoordinador
         *
         * Esta funcion permite instanciar las clases funcion y sql del bloque adminInscripcionGrupoCoordinadorPosgrado
         *
         * @access public
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	 public function __construct($configuracion)
	{
           $this->configuracion=$configuracion;
           $this->funcion=new funcion_adminInscripcionGrupoCoordinadorPosgrado($configuracion);
           $this->sql=new sql_adminInscripcionGrupoCoordinadorPosgrado($configuracion);
            
	}
	
	/**
         * Funcion que redirije a las diferentes funciones de la clase funcion.class.php
         *
         * Esta funcion redirije a las funciones que contiene la clase funcion.class.php que se encuentra en el bloque adminInscripcionGrupoCoordinadorPosgrado,
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
                        case "consultarPorNivel":
                                $this->funcion->mostrarEspaciosOpcionNivel();
                        break;

                        case "consultarPorCodigo":
                                $this->funcion->mostrarEspaciosOpcionCodigo();
                        break;

                        case "consultarPorNombre":
                                $this->funcion->mostrarEspaciosOpcionNombre();
                        break;

                        case "verProyectos":
                        break;

                        default:
                            $this->funcion->mostrarEspaciosOpcionNivel();
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
            switch($_REQUEST['opcionBusqueda'])
		{
                    
                        case "buscarCodigo":
                        
                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=admin_inscripcionGrupoCoorPosgrado";
				$variable.="&opcion=consultarPorCodigo";
				$variable.="&codEspacio=".$_REQUEST["datosBusqueda"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];

                                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;

                       case "buscarNombre":

                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=admin_inscripcionGrupoCoorPosgrado";
				$variable.="&opcion=consultarPorNombre";
				$variable.="&nombreEspacio=".$_REQUEST["datosBusqueda"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];

                                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;

                        

				
		}
		
	}
}
/**
 * Crea un nuevo objeto de la clase bloque_adminInscripcionGrupoCoordinadorPosgrado
 *
 * Se instancia la clase bloque_adminInscripcionGrupoCoordinadorPosgrado y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$obj_aprobar=new bloque_adminInscripcionGrupoCoordinadorPosgrado($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html($configuracion);
}
else
{
	$obj_aprobar->action($configuracion);
}


?>