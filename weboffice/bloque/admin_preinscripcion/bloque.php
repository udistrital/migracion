<?
/**
 * Bloque adminInscripcionCoordinador
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
 * @subpackage Admin
 */
class bloque_admin_preinscripcion extends bloque
{

        /**
         * Funcion constructor del bloque adminInscripcionCoordinador
         *
         * Esta funcion permite instanciar las clases funcion y sql del bloque adminInscripcionCoordinador
         *
         * @access public
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	 public function __construct($configuracion)
	{
            $this->funcion=new funcion_admin_preinscripcion($configuracion);
            $this->sql=new sql_admin_preinscripcion();
	}
	
	/**
         * Funcion que redirije a las diferentes funciones de la clase funcion.class.php
         *
         * Esta funcion redirije a las funciones que contiene la clase funcion.class.php que se encuentra en el bloque adminInscripcionCoordinador,
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
			$_REQUEST['opcion']="mostrar";
		}
		
		switch($_REQUEST['opcion'])
		{
			case "mostrar":
				$this->funcion->verProyectos($configuracion);
			break;
			case "pintarFormulario":
				$this->funcion->vista_preinscripcion($configuracion);
			break;
			case "parametros":
				$this->funcion->registrar_parametros($configuracion);
			break;
			default:
				$this->funcion->verProyectos($configuracion);
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
                        case "parametros_preinscripcion":

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=admin_preinscripcion";
                            $variable.="&opcion=parametros";
                            $variable.="&semestresSuperiores=".$_REQUEST["semestresSuperiores"];
                            $variable.="&semestresConsecutivos=".$_REQUEST["semestresConsecutivos"];
                            $variable.="&nro_max_semestres=".$_REQUEST["nro_max_semestres"];
                            $variable.="&masAsignaturas=".$_REQUEST["masAsignaturas"];
                            $variable.="&nro_max_asignaturas=".$_REQUEST["nro_max_asignaturas"];
                            $variable.="&verificar_requisito=".$_REQUEST["verificar_requisito"];
                            $variable.="&prioridad=".$_REQUEST["prioridad"];
                            $variable.="&prioridadPerdidas=".$_REQUEST["prioridadPerdidas"];
                            $variable.="&creditosPlan=".$_REQUEST["creditosPlan"];
                            $variable.="&promedioMinimo=".$_REQUEST["promedioMinimo"];
                            $variable.="&maxCreditosNivel=".$_REQUEST["maxCreditosNivel"];
                            $variable.="&minCreditosNivel=".$_REQUEST["minCreditosNivel"];
                            $variable.="&creditosOB=".$_REQUEST["creditosOB"];
                            $variable.="&creditosOC=".$_REQUEST["creditosOC"];
                            $variable.="&creditosEI=".$_REQUEST["creditosEI"];
                            $variable.="&creditosEE=".$_REQUEST["creditosEE"];
                            $variable.="&eaAprobado=".$_REQUEST["eaAprobado"];
                            $variable.="&nroPensum=".$_REQUEST["nroPensum"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&annio=".$_REQUEST["annio"];
                            $variable.="&periodo=".$_REQUEST["periodo"];
                            $variable.="&parametros=".$_REQUEST["parametros"];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";

                        break;

                        case "parametros_condor":

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=admin_preinscripcion";
                            $variable.="&opcion=parametros";
                            $variable.="&semestresSuperioresCondor=".$_REQUEST["semestresSuperioresCondor"];
                            $variable.="&semestresConsecutivosCondor=".$_REQUEST["semestresConsecutivosCondor"];
                            $variable.="&nro_max_semestresCondor=".$_REQUEST["nro_max_semestresCondor"];
                            $variable.="&masAsignaturasCondor=".$_REQUEST["masAsignaturasCondor"];
                            $variable.="&nro_max_asignaturasCondor=".$_REQUEST["nro_max_asignaturasCondor"];
                            $variable.="&verificar_requisitoCondor=".$_REQUEST["verificar_requisitoCondor"];
                            $variable.="&prioridadCondor=".$_REQUEST["prioridadCondor"];
                            $variable.="&prioridadPerdidasCondor=".$_REQUEST["prioridadPerdidasCondor"];
                            $variable.="&creditosPlanCondor=".$_REQUEST["creditosPlanCondor"];
                            $variable.="&promedioMinimoCondor=".$_REQUEST["promedioMinimoCondor"];
                            $variable.="&maxCreditosNivelCondor=".$_REQUEST["maxCreditosNivelCondor"];
                            $variable.="&minCreditosNivelCondor=".$_REQUEST["minCreditosNivelCondor"];
                            $variable.="&creditosOBCondor=".$_REQUEST["creditosOBCondor"];
                            $variable.="&creditosOCCondor=".$_REQUEST["creditosOCCondor"];
                            $variable.="&creditosEICondor=".$_REQUEST["creditosEICondor"];
                            $variable.="&creditosEECondor=".$_REQUEST["creditosEECondor"];
                            $variable.="&eaAprobadoCondor=".$_REQUEST["eaAprobadoCondor"];
                            $variable.="&nroPensum=".$_REQUEST["nroPensum"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&annio=".$_REQUEST["annio"];
                            $variable.="&periodo=".$_REQUEST["periodo"];
                            $variable.="&parametros=".$_REQUEST["parametros"];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";

                        break;

                        case "guardarAsignaturaNoIns":

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=admin_preinscripcion";
                            $variable.="&opcion=guardarAsignaturaNoIns";
                            $variable.="&nroPensum=".$_REQUEST["nroPensum"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&annio=".$_REQUEST["annio"];
                            $variable.="&periodo=".$_REQUEST["periodo"];
                            $variable.="&codAsignaturaNoIns=".$_REQUEST["codAsignaturaNoIns"];
                            $variable.="&nombreAsignaturaNoIns=".$_REQUEST["nombreAsignaturaNoIns"];
                            $variable.="&nroPensumNoIns=".$_REQUEST["nroPensumNoIns"];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";

                        break;
                    
                        case "guardarEstudianteNoIns":

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=admin_preinscripcion";
                            $variable.="&opcion=guardarEstudianteNoIns";
                            $variable.="&nroPensum=".$_REQUEST["nroPensum"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&annio=".$_REQUEST["annio"];
                            $variable.="&periodo=".$_REQUEST["periodo"];
                            $variable.="&codEstudianteNoIns=".$_REQUEST["codEstudianteNoIns"];
                            $variable.="&nombreEstudianteNoIns=".$_REQUEST["nombreEstudianteNoIns"];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

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
$obj_aprobar=new bloque_admin_preinscripcion($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html($configuracion);
}
else
{
	$obj_aprobar->action($configuracion);
}


?>