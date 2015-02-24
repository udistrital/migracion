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
class bloque_adminIntensidadHoraria extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminIntensidadHoraria();
 		$this->funcion=new funciones_adminIntensidadHoraria($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
		{
			$accion=$_REQUEST['opcion'];
			
			switch($accion)
			{
				case "actualizaIntensidad":
					$this->funcion->actualizaIntensidad($configuracion, $accesoOracle,$acceso_db);
					break;
			}
		}
		else
		{
			$accion="listaCompleta";
			$this->funcion->mostrarRegistro($configuracion,$conexion);
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
		
		if(isset($_REQUEST["actualizar"])||!isset($_REQUEST["consultar"]))
		{
			$this->funcion->ejecutarActualizarIntensidad($configuracion,$this->funcion->accesoOracle,$this->funcion->acceso_db);
		}
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminIntensidadHoraria($configuracion);
//echo $_REQUEST['action'];
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
