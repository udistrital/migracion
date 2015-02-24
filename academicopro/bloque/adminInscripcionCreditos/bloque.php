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
class bloque_adminInscripcionCreditos extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_adminInscripcionCreditos($configuracion);
		$this->sql=new sql_adminInscripcionCreditos();
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

			case "mostrar":
				$this->funcion->consultaPorEstudiante($configuracion,$this->tema,$this->acceso_db);
				break;

			case "nuevo":
				$this->funcion->nuevoRegistro($configuracion,$this->tema,$this->acceso_db);
				break;

			case "editar":
				$this->funcion->editarRegistro($configuracion,$this->tema,$_REQUEST['id_espacio'], $this->acceso_db, "");
				break;
			case "ver":
				$this->funcion->verRegistro($configuracion,$this->tema,$this->acceso_db,"");
				break;

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

$esteBloque=new bloque_adminInscripcionCreditos($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>