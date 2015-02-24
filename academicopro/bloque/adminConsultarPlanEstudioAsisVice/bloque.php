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
class bloque_adminConsultarPlanEstudioAsisVice extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_adminConsultarPlanEstudioAsisVice($configuracion);
		$this->sql=new sql_adminConsultarPlanEstudioAsisVice();
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
                        case "menuAsisVice":
				$this->funcion->menuAsisVice();
			break;

			case "mostrar":
				$this->funcion->mostrarRegistro($_REQUEST['planEstudio']);
			break;
			
			case "ver":
				$this->funcion->verRegistro();
			break;

			case "buscarPlan":
				$this->funcion->buscarEnfasis($_REQUEST['proyecto'],$_REQUEST['nombreProyecto']);
			break;

                        default:
				$this->funcion->verRegistro();
			break;
					
			
		}#Cierre de funcion html
	}
	
	
	function action($configuracion)
	{
//            echo $_REQUEST['opcion']."<br>";
//            echo $_REQUEST['action'];
//            exit;

            switch($_REQUEST['opcion'])
		{
                       


				
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_adminConsultarPlanEstudioAsisVice($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html($configuracion);
}
else
{
	$obj_aprobar->action($configuracion);
}


?>