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
class bloque_adminActualizarEstudiante extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_adminActualizarEstudiante($configuracion);
		$this->sql=new sql_adminActualizarEstudiante();
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
			case "actualizarEstudiante":
				$this->funcion->buscarEstudianteOracle($configuracion,$this->tema,$_REQUEST['id_malla'], $this->acceso_db,"");
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
					
			case "corregir":
				$this->funcion->corregirRegistro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab);
				break;
			
			default:
				$this->funcion->nuevoRegistro($configuracion,$tema,$acceso_db);
				break;	
		}
	}
	
	
	function action($configuracion)
	{	switch($_REQUEST['opcion'])
		{
			case "nuevo":	
				$this->funcion->guardarMalla($configuracion);
				break;
				
			case "editar":
				$this->funcion->editarMalla($configuracion);
				break;
			default:
				
				unset($_REQUEST['action']);	
					
				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminMallas";
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

$esteBloque=new bloque_adminActualizarEstudiante($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>