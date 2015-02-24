<?
/**
 * Bloque registroCambiarGrupoInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage CambiarGrupo
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
 * Clase funcion.class.php del bloque registroCambiarGrupoInscripcionGrupoCoordinador
 *
 * Clase que contiene toda la logica del bloque y muestra la interfaz al usuario
 */
include_once("funcion.class.php");
/**
 * Clase sql.class.php del bloque registroCambiarGrupoInscripcionGrupoCoordinador
 *
 * Clase que contiene las sentencias SQL del bloque
 */
include_once("sql.class.php");
/**
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 */
class bloque_registroCambiarGrupoInscripcionGrupoCoordinador extends bloque
{
        /**
         * Funcion constructor del bloque registroCambiarGrupoInscripcionGrupoCoordinador
         *
         * Esta funcion permite instanciar las clases funcion y sql del bloque registroCambiarGrupoInscripcionGrupoCoordinador
         *
         * @access public
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroCambiarGrupoInscripcionGrupoCoordinador();
 		$this->funcion=new funciones_registroCambiarGrupoInscripcionGrupoCoordinador($configuracion, $this->sql);
	}


        /**
         * Funcion que redirije a las diferentes funciones de la clase funcion.class.php
         *
         * Esta funcion redirije a las funciones que contiene la clase funcion.class.php que se encuentra en el bloque registroCambiarGrupoInscripcionGrupoCoordinador,
           dependiendo de la opcion seleccionada.
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "estudiante":
						$this->funcion->buscarGrupo($configuracion);
						break;

					case "cambiarGrupoEstudiante":
						$this->funcion->cambiarGrupoEstudiante($configuracion);
						break;

					case "variosEstudiantes":
						$this->funcion->buscarGrupoVariosEstudiantes($configuracion);
						break;

                                        case "cambiarGrupoVarios":
						$this->funcion->cambiarGrupoVarios($configuracion);
						break;
                                }
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

                        case "cambiar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCambiarGrupoInscripcionGrupoCoordinador";
				$variable.="&opcion=cambiarGrupoEstudiante";
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&proyecto=".$_REQUEST["proyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nroGrupoAnt=".$_REQUEST["nroGrupoAnt"];
                                $variable.="&nroGrupoNue=".$_REQUEST["nroGrupoNue"];
                                $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
                                
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "cambiarVarios":
                            
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCambiarGrupoInscripcionGrupoCoordinador";
				$variable.="&opcion=cambiarGrupoVarios";
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&proyecto=".$_REQUEST["proyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nroGrupoAnt=".$_REQUEST["nroGrupoAnt"];
                                $variable.="&nroGrupoNue=".$_REQUEST["nroGrupoNue"];
                                $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
                                $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                $variable.="&totalEstudiantes=".$_REQUEST["totalEstudiantes"];

                                $total=$_REQUEST['totalEstudiantes']+1;
                                        for($i=1;$i<$total;$i++)
                                        {
                                            $variable.="&codEstudiante-".$i."=".$_REQUEST['codEstudiante-'.$i];

                                        }

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                }
	}
}
/**
 * Crea un nuevo objeto de la clase bloque_registroCambiarGrupoInscripcionGrupoCoordinador
 *
 * Se instancia la clase bloque_adminInscripcionCoordinador y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$esteBloque=new bloque_registroCambiarGrupoInscripcionGrupoCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		$esteBloque->action($configuracion);
	}
}


?>