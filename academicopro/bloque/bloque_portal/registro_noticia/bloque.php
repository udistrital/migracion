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
class bloqueNoticia extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new registro_noticia($configuracion);
		$this->sql=new sql_noticia();
	}
	
	
	function html($configuracion)
	{
		$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion
		
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
			case "mostrar":
				if(isset($_REQUEST['registro']))
				{
					$this->funcion->mostrarRegistro($configuracion,$this->tema,$_REQUEST['registro'], $acceso_db, $formulario);
				}
				break;
			
			case "nuevo":	
				$this->funcion->nuevoRegistro($configuracion,$this->tema,$this->acceso_db);
				break;
				
			case "editar":
				$this->funcion->editarRegistro($configuracion,$tema,$_REQUEST['registro'], $acceso_db, $formulario);
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
	{
		$this->funcion->procesarRegistro($configuracion);
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloqueNoticia($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>