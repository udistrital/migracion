<?
/**
 * Bloque adminConsultarInscripcionGrupoCoordinador
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 22/03/2011
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
class bloque_estudiantesInscritosGrupoCoorHoras extends bloque
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
//                include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
//		$this->tema=$tema;
		$this->funcion=new funcion_estudiantesInscritosGrupoCoorHoras($configuracion);
		$this->sql=new sql_estudiantesInscritosGrupoCoorHoras($configuracion);
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
			$_REQUEST['opcion']="verGrupo";
		}

		switch($_REQUEST['opcion'])
		{

			case "verGrupo":                          
				$this->funcion->armarVistaGrupo();
				break;

			default:
				$this->funcion->armarVistaGrupo();
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
			case "grupoSeleccionado":
                            //var_dump($_REQUEST);exit;
                            switch (trim($_REQUEST['accionCoordinador']))
                            {
                                case "cambiar":
                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                    $variable="pagina=registroCambiarGrupoInscripcionGrupoCoordinador";
                                    $variable.="&opcion=variosEstudiantes";
                                    $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                    $variable.="&nroGrupo=".$_REQUEST["nroGrupo"];
                                    $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                    $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                    $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
                                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                    
                                    $total=$_REQUEST["total"]+1;
                                        for($i=0;$i<$total;$i++)
                                        {
                                            $variable.="&codEstudiante-".$i."=".$_REQUEST["codEstudiante-".$i];

                                        }
                                    $variable.="&total=".$total;
                                    
                                    include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    break;
                                    
                                case "cancelar":
                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                    $variable="pagina=registroCancelarInscripcionGrupoEstudCoordinador";
                                    $variable.="&opcion=variosEstudiantesConfirmacion";
                                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                                    $variable.="&nroGrupo=".$_REQUEST['nroGrupo'];
                                    $variable.="&codEspacio=".$_REQUEST['codEspacio'];
                                    $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                    $variable.="&proyecto=".$_REQUEST["proyecto"];
                                    $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
                                    $variable.="&clasificacion=".$_REQUEST["clasificacion"];

                                    $total=$_REQUEST["total"]+1;
                                     for($i=0;$i<$total;$i++)
                                        {
                                            $variable.="&codEstudiante-".$i."=".$_REQUEST['codEstudiante-'.$i];

                                        }
                                    $variable.="&total=".$total;

                                    include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    break;

                              default:

                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                    $variable="pagina=adminConsultarInscripcionGrupoCoordinador";
                                    $variable.="&opcion=verGrupo";
                                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                                    $variable.="&nroGrupo=".$_REQUEST['nroGrupo'];
                                    $variable.="&codEspacio=".$_REQUEST['codEspacio'];
                                    $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                    $variable.="&codProyecto=".$_REQUEST["proyecto"];
                                    $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];

                                    include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                                    $this->cripto=new encriptar();
                                    $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                    echo "<script>location.replace('".$pagina.$variable."')</script>";
                                    break;
                            }
                            
				break;

                              case "procesar":
                                unset ($_REQUEST['action']);
                                if (isset ($_REQUEST['cancelar_x']))
                                  {
//                                  foreach ($_REQUEST as $key => $value) {
//                                    echo $key."=>".$value."<br>";
//                                  }exit;
                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                    $variable="pagina=registro_cancelarEstudianteGrupoCoorHoras";
                                  foreach ($_REQUEST as $key => $value) {
                                    $variable.="&".$key."=".$value;
                                    }
//                                  echo $variable;exit;
//                                    $variable.="&opcion=validarCruce";
//                                    $variable.="&codProyecto=".$_REQUEST["codProyecto"];
//                                    $variable.="&planEstudio=".$_REQUEST['planEstudio'];
//                                    $variable.="&codEspacio=".$_REQUEST['codEspacio'];
//                                    $variable.="&grupo=".$_REQUEST['grupo'];
//                                    $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                  }
                                elseif (isset ($_REQUEST['cambiar_x']))
                                  {
                                    $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                    $variable="pagina=admin_BuscarGruposProyectoHoras";//registro_cambiarGrupoEstudianteGrupoCoorHoras";
                                  foreach ($_REQUEST as $key => $value) {
                                    $variable.="&".$key."=".$value;
                                    }
//                                  echo $variable;exit;
//                                  foreach ($_REQUEST as $key => $value) {
//                                    echo $key."=>".$value."<br>";
//                                  }exit;
                                  }
                                  else
                                    {
                                    exit;
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
 * Crea un nuevo objeto de la clase bloque_adminInscripcionCoordinador
 *
 * Se instancia la clase bloque_adminInscripcionCoordinador y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */
$esteBloque=new bloque_estudiantesInscritosGrupoCoorHoras($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>