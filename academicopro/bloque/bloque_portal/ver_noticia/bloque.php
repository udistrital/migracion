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
class bloqueverNoticia extends bloque
{
	//@Método constructor donde se crea un objeto funcion de la clase ver_noticia y un objeto sql de la clase sql_verNoticia 
	 public function __construct($configuracion)
	{
		$this->funcion=new ver_noticia($configuracion);
		$this->sql=new sql_verNoticia();
	}
	
	//@Método que permite la visualizacion de diferentes paginas segun el valor de la variable opcion
	function html($configuracion)
	{	
		$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear una variable para el tema de presentacion
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;	
		
		if(!isset($_REQUEST['id']))
		{
			if(!isset($_REQUEST['opcion']))
			{
				$_REQUEST['opcion']=" ";
			}
		}
		//echo $_REQUEST['opcion'].''.$_REQUEST['tipo'];
		switch($_REQUEST['opcion'])
		{
			case "mostrar":
			
				$this->cadena_sql=$this->sql->cadena_sql($configuracion,"ver",$_REQUEST['id']);
				//echo $this->cadena_sql;
				$formulario=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
				$this->funcion->mostrarRegistro($configuracion,$tema,$_REQUEST['id'], $this->acceso_db,$formulario);
				break;
			
			case "nuevo":	
				$this->funcion->nuevoRegistro($configuracion,$tema,$this->acceso_db);
				break;
				
			case "editar":
				$this->funcion->editarRegistro($configuracion,$tema,$_REQUEST['id'], $this->acceso_db,"");
				break;
				
			case "corregir":
				$this->funcion->corregirRegistro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab);
				break;
			
			default:
				
				break;	
		}
	}
	
	//@Método que permite ejecutar o invocar diferentes metodos dependiendo la acción dependiendo la acción
	function action($configuracion)
	{
	}
	
	
}

// @ Crear un objeto bloque verNoticia y dependiendo si existe una accion, la realiza o muestra el metodo html

$this->esteBloque=new bloqueverNoticia($configuracion);

if(!isset($_REQUEST['action']))
{
	$this->esteBloque->html($configuracion);
}
else
{
	$this->esteBloque->action($configuracion);
}


?>
