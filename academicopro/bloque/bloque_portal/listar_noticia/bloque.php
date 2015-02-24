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
class bloqueListaNoticia extends bloque
{
	//@Método constructor donde se crea un objeto funcion de la clase lista_noticia y un objeto sql de la clase sql_listanoticia 
	 public function __construct($configuracion)
	{
		$this->funcion=new lista_noticia($configuracion);
		$this->sql=new sql_listaNoticia();
	}
	
//@Método que permite la visualizacion de diferentespaginas segun el valor de la variable opcion
	function html($configuracion)
	{	
		$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;	
		
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="mostrar";
		}
		if(!isset($_REQUEST['tipo']))
		{
			$_REQUEST['tipo']=0;
		}
		//echo $_REQUEST['opcion'].''.$_REQUEST['tipo'];
		switch($_REQUEST['opcion'])
		{
			case "mostrar":
				$this->funcion->mostrarRegistro($configuracion,$tema,$_REQUEST['tipo'], $this->acceso_db, "");
				break;
			
			case "nuevo":	
				$this->funcion->nuevoRegistro($configuracion,$tema,$this->acceso_db);
				break;
				
			case "editar":
				$this->funcion->editarRegistro($configuracion,$tema,$_REQUEST['registro'], $acceso_db, $formulario);
				break;
				
			case "corregir":
				$this->funcion->corregirRegistro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab);
				break;
			
			default:
				$this->funcion->mostrarRegistro($configuracion,$tema,$_REQUEST['tipo'], $this->acceso_db, "");
				break;	
		}
	}
	
//@Método que permite ejecutar o invocar diferentes metodos dependiendo la acción	
	function action($configuracion)
	{
	}
	
	
}


// @ Crear un objeto bloque listaNoticia y dependiendo si existe una accion, la realiza o muestra el metodo html

$this->esteBloque=new bloqueListaNoticia($configuracion);

if(!isset($_REQUEST['action']))
{
	$this->esteBloque->html($configuracion);
}
else
{
	$this->esteBloque->action($configuracion);
}


?>
