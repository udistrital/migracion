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
class bloque_admin_actualizacionElectivas extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_admin_actualizacionElectivas($configuracion);
		$this->sql=new sql_admin_actualizacionElectivas();
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
			case "actualizarElectivas":
				$this->funcion->actualizarElectivas($configuracion);
				break;
			
			default:
				$this->funcion->nuevoRegistro($configuracion,$tema,$acceso_db);
				break;	
		}
	}
	
	
	function action($configuracion)
	{	switch($_REQUEST['opcion'])
		{
						
		}
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_admin_actualizacionElectivas($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>