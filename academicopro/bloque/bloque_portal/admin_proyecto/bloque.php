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
class bloqueAdminProyecto extends bloque
{
	//@Método constructor donde se crea un objeto funcion de la clase admin_noticia y un objeto sql de la clase sql_adminNoticia 
	 public function __construct($configuracion)
	{
		$this->funcion=new admin_proyecto($configuracion);
		$this->sql=new sql_adminProyecto();
	}
	
	//@Método que permite la visualizacion de diferentes paginas segun el valor de la variable opcion
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
				$this->funcion->editarRegistro($configuracion,$tema,$_REQUEST['id_noticia'], $this->acceso_db, "");
				break;
				
			case "borrar":	
				$this->funcion->borrarRegistro($configuracion,$tema,$this->acceso_db);
				break;	
				
			case "corregir":
				$this->funcion->corregirRegistro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab);
				break;
			case "ver":
				$this->acceso_db=$this->funcion->conectarDB($configuracion);
				$this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar",$_REQUEST['id_proyecto']);
				//echo $this->cadena_sql; 
				$registro=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
				$this->funcion->ver_proyecto($configuracion,$registro,$tema,"");

				break;	
			
			default:
				$this->funcion->mostrarRegistro($configuracion,$tema,"", $this->acceso_db, "");

				break;	
		}
	}
	
	//@Método que permite ejecutar o invocar diferentes metodos//@Método que permite ejecutar o invocar diferentes metodos dependiendo la acción dependiendo la acción
	function action($configuracion)
	{
	switch($_REQUEST['opcion'])
		{
			case "editar":
			//	$this->funcion->procesarRegistro($configuracion);
				break;
				
			default:
				$this->funcion->mostrarRegistro($configuracion,$tema,$_REQUEST['tipo'], $this->acceso_db, "");
				break;	
		}
		
	}
	
	
}


// @ Crear un objeto bloque adminNoticia y dependiendo si existe una accion, la realiza o muestra el metodo html

$this->esteBloque=new bloqueAdminProyecto($configuracion);

if(isset($_REQUEST['cancelar']))
{
	unset($_REQUEST['action']);
	$_REQUEST['opcion']="mostrar";
}

if(!isset($_REQUEST['action']))
{ 
	$this->esteBloque->html($configuracion);
}
else
{ 	$this->esteBloque->action($configuracion);
}


?>