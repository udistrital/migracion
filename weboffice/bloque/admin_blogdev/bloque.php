<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 05 de febrero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		registro_inscripcion
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
* @author			Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	
*
/*--------------------------------------------------------------------------------------------------------------------------*/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloque_adminBlogdev extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminBlogdev();
 		$this->funcion=new funciones_adminBlogdev($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
					case "mostrar":
						$this->funcion->mostrarRegistro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$this->funcion->acceso_db);
						break;
					case "nuevo":
						$this->funcion->nuevoRegistro($configuracion,$conexion);
						break;
					case "editarAplicaciones":
						$this->funcion->modificarAplicaciones($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$this->funcion->acceso_db);
						break;
					case "editar":
						$this->funcion->editarRegistro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$this->funcion->acceso_db);
						break;
					case "exito":
						$this->funcion->RegistroExitoso($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$this->funcion->acceso_db);
						break;
					case "exitoEditarAplic":
						$this->funcion->registroEditado($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$this->funcion->acceso_db);
					break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->nuevoRegistro($configuracion,$conexion);
			}
		}
		else
		{
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado");	
		}
	}
	
	function action($configuracion)
	{
		$this->funcion->revisarFormulario();
		
		$tipo="busqueda";
			
		//Rescatar datos de sesion
		$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
		$_REQUEST["registro"]=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "identificacion");
		if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST["opcion"]))
			{
			
				$this->funcion->guardarRegistro($configuracion,$this->funcion->accesoOracle,$this->funcion->acceso_db);
							
			}
			if(isset($_REQUEST["editarAplicaciones"]))
			{
				$this->funcion->guardarEdicion($configuracion,$this->funcion->accesoOracle,$this->funcion->acceso_db);
				
			}
		}
		else
		{
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado");	
		}
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminBlogdev($configuracion);
echo $_REQUEST['action'];
if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		$esteBloque->action($configuracion);
	}
}


?>
