<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");
//Clase
class bloque_adminConsultarInscripcionEstudianteCoordinador extends bloque
{

	 public function __construct($configuracion)
	{	
//                include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
//		$this->tema=$tema;
		$this->funcion=new funcion_adminConsultarInscripcionEstudianteCoordinador($configuracion);
		$this->sql=new sql_adminConsultarInscripcionEstudianteCoordinador();
	}


	function html($configuracion)
	{
		//$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion


		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}

		switch($_REQUEST['opcion'])
		{

			case "mostrarConsulta":
				$this->funcion->mostrarHorarioEstudiante($configuracion);
				break;

			default:
				$this->funcion->nuevoRegistro($configuracion,$tema,$acceso_db);
				break;
		}
	}


	function action($configuracion)
	{
                switch($_REQUEST['opcion'])
		{
			case "nuevo":
				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminInscripcionCreditos";
				$variable.="&opcion=mostrarConsulta";
                                $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "registroAgil":
                            
				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroInscripcionAgilEstudianteCoordinador";
				$variable.="&opcion=ejecutarValidaciones";
                                $variable.="&codEspacio=".$_REQUEST['codEspacioAgil'];
                                $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
                                $variable.="&planEstudio=".$_REQUEST['planEstudio'];
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudio'];
                                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                $variable.="&grupo=".$_REQUEST['grupo'];
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

			default:

				unset($_REQUEST['action']);

				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminIncripcionCreditos";
				$variable.="&opcion=nuevo";
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
		}

	}


}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminConsultarInscripcionEstudianteCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>