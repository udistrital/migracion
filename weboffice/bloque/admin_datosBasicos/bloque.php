<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Karen Palacios
* @revision      Última revisión 22 de Febrero de 2010
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		admin_datosBasicos
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.1
* @author		Karen Palacios
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	
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
class bloqueCapacitacionFuncionario extends bloque
{

	 public function __construct($configuracion)
	{

 		$this->sql=new sql_adminSolicitud();
 		$this->funcion=new funciones_adminSolicitud($configuracion, $this->sql);

 		
	}
	
	
	function html($configuracion)
	{
	
				/*echo "<pre>";
				var_dump($_REQUEST);
				echo "</pre>";	*/
					
	
		if(!isset($_REQUEST["action"]))
		{
				if(isset($_REQUEST["opcion"])){
					switch($_REQUEST["opcion"]){
						case "ayuda":
							if(!isset($_REQUEST["submenu"])){						
								$this->funcion->consultarAyuda($configuracion,$_REQUEST["usuario"]);
							}
							else{
								$this->funcion->consultarContenidoAyuda($configuracion,$_REQUEST["submenu"]);
							}	
						break;	
					}	
				}
				else{
					$this->funcion->consultarEstudiante($configuracion);
				}
			
		}
		else
		{

		}		
	}

	
	function action($configuracion)
	{
		
			
			$this->funcion->redireccionarInscripcion($configuracion,'siguiente',$_REQUEST["pregunta"]);		
				

	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloqueCapacitacionFuncionario($configuracion);

if(!isset($_REQUEST['action']))
{
	//echo "1";
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);

}


?>














<?


