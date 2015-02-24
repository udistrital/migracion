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
class bloqueConsultarCenso extends bloque
{

	 public function __construct($configuracion)
	{
		$this->funcion=new registro_consultaCenso($configuracion);
		$this->sql=new sql_consultaCenso();
	}
	
	
	function html($configuracion)
	{
		$this->acceso_db=$this->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion
		
		if(isset($_REQUEST['accion']))
		{
			if($_REQUEST['accion']=="reiniciar")
			{
				$cadena_sql="UPDATE ";
				$cadena_sql.=$configuracion["prefijo"]."consultiva ";
				$cadena_sql.="SET ";
				$cadena_sql.="eleccion=''";
				$this->acceso_db->ejecutar_acceso_db($cadena_sql);			
			}
		
		}
		
		
		
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
			case "mostrar":
				if(isset($_REQUEST['registro']))
				{
					$this->funcion->mostrarRegistro($configuracion,$tema,$_REQUEST['registro'], $acceso_db, $formulario);
				}
				break;
			
			case "nuevo":	
				$this->funcion->nuevoRegistro($configuracion,$this->acceso_db);
				break;
				
			case "editar":
				$this->funcion->editarRegistro($configuracion,$tema,$_REQUEST['registro'], $acceso_db, $formulario);
				break;
				
			case "corregir":
				$this->funcion->corregirRegistro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab);
				break;
			
			default:
				$this->funcion->nuevoRegistro($configuracion,$tema,$accion,$formulario,$verificar,1,1,$estilo,$acceso_db);
				break;	
		}
	}
	
	
	function action()
	{
		//Procesar el formulario
		
	
	
	}
	
	function conectarDB($configuracion)
	{
		$acceso_db=new dbms($configuracion);
		$enlace=$acceso_db->conectar_db();
		if (is_resource($enlace))
		{
				return $acceso_db;
		}
		else
		{
			die("Imposible conectarse a la base de datos");
		}
	}	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloqueConsultarCenso($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>