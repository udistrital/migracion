<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 25 de agosto de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		admin_solicitudTerminacion
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.1
* @author		Karen Palacios
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar las solicitudes de recibos de pago
*			de los estudiantes de terminación de materias
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
class bloqueAdminSolicitud extends bloque
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

			if($_REQUEST["opcion"]=='exito')
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				
				$cadena="El registro fue Exitoso";
	
				alerta::sin_registro($configuracion,$cadena);

			}
			elseif($_REQUEST["opcion"]=='verPeriodos')
			{
				$this->funcion->consultarPeriodos($configuracion,$_REQUEST['estudiante']);
				//echo "a";		
			}
			elseif(isset($_REQUEST["periodos"]))
			{	
				$periodosaPagar=explode('#',$_REQUEST["periodos"]);
				$acta[0]=$_REQUEST["numacta"];
				$acta[1]=$_REQUEST["fecacta"];
				unset($periodosaPagar[count($periodosaPagar)-1]);
				$this->funcion->confirmarPeriodos($configuracion,$_REQUEST['estudiante'],$periodosaPagar,$acta);				
			

			}			
			else
			{	
				//echo "c";
				$this->funcion->consultarEstudiante($configuracion);
			}
			
		}
		else
		{
			if(!$_REQUEST["opcion"])
			{
				$this->funcion->redireccionarInscripcion($configuracion,'verPeriodos',$_REQUEST['estudiante']);
			}
		}		
	}

	
	function action($configuracion)
	{
		if(!$_REQUEST["periodos"]){
		
			$periodosaPagar="";
			//var_dump($_REQUEST);
			foreach($_REQUEST as $clave=>$valor)
			{	
				
				if((substr($clave,0,13))=='reciboperiodo'){
					
					$periodosaPagar.=$valor.'@';
					$periodosaPagar.=$_REQUEST[$valor];
					$periodosaPagar.='#';	
				}	
			}

			
			$variable[0]=$periodosaPagar;
			$variable[1]=$_REQUEST['estudiante'];
			$variable[2]=$_REQUEST['numacta'];
			$variable[3]=$_REQUEST['fecacta'];
			//echo $variable[0];
			
			if(!isset($_REQUEST["generar"])){
				$this->funcion->redireccionarInscripcion($configuracion,'confirmarPeriodos',$variable);	
				//$this->funcion->redireccionarInscripcion($configuracion,'confirmarPeriodos',$variable);
			}
			else{

	
				$this->funcion->insertarAprobacion($configuracion,$_REQUEST['estudiante'],$variable);

			}	
			
			
		}else{
		

			//$this->funcion->confirmarPeriodos($configuracion,$_REQUEST['estudiante'],$periodosaPagar);
		}	
					
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloqueAdminSolicitud($configuracion);

if(!isset($_REQUEST['action']))
{
	//echvaro "1";
	$esteBloque->html($configuracion);
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
	//	echo "2";
	//var_dump($_REQUEST);
		$esteBloque->html($configuracion);
		
	}
	else
	{
	//	echo "3";
		$esteBloque->action($configuracion);
		
	}
}


?>














<?



