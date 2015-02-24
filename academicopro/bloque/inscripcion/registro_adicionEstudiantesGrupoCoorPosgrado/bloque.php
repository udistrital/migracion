<?
/**
 * Bloque adminInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package InscripcionesPosgrado
 * @subpackage Admin
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 25/03/2011
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
 * @package InscripcionPosgrado
 * @subpackage Admin
 */
class bloque_registroAdicionEstudiantesGrupoCoorPosgrado extends bloque
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
           $this->funcion=new funcion_registroAdicionEstudiantesGrupoCoorPosgrado($configuracion);
           $this->sql=new sql_registroAdicionEstudiantesGrupoCoorPosgrado($configuracion);

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
                        case "verGrupo":
                                $this->funcion->validarFechas();
                        break;

                        case "enviarEstudiantes":
                                $this->funcion->adicionarEstudiantes();
                                //valida fechas y muestra formulario
                                $this->funcion->validarFechas();
                        break;

                        default:
                            $this->funcion-> validarFechas();
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
                        case "registrarEstudiante":
                          
                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=admin_estudiantesInscritosGrupoCoorPosgrado";
				$variable.="&opcion=enviarEstudiantes";
                                foreach ($_REQUEST['codEstudiante'] as $key => $value) {
				$variable.="&codEstudiante[$key]=".$value;
                                }
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
				$variable.="&codEspacio=".$_REQUEST["codEspacio"];
				$variable.="&grupo=".$_REQUEST["grupo"];
                                $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                                $variable.="&nivel=".$_REQUEST["nivel"];
                                if (isset($_REQUEST["validarEspacioPlan"]))
                                  {
                                    $variable.="&validarEspacioPlan=".$_REQUEST["validarEspacioPlan"];
                                  }
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
$obj_aprobar=new bloque_registroAdicionEstudiantesGrupoCoorPosgrado($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>