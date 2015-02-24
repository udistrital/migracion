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
    public $configuracion;
    public function __construct($configuracion)
	{

 		$this->sql=new sql_adminSolicitud();
 		$this->funcion=new funciones_adminSolicitud($configuracion, $this->sql);
                 $this->configuracion=$configuracion;

 		
	}
	
	
	function html()
	{
	
		if(!isset($_REQUEST["action"]))
		{
			$opcion=isset($_REQUEST["opcion"])?$_REQUEST["opcion"]:"";
			
			if($opcion=='exito')
			{
				include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");
				
				$cadena="Los recibos se generaron correctamente";
	
				alerta::sin_registro($this->configuracion,$cadena);

			}
			elseif($opcion	=='verPeriodos')
			{
				$this->funcion->consultarPeriodos($this->configuracion,$_REQUEST['estudiante']);
			}
			elseif(isset($_REQUEST["periodos"]))
			{	
				$periodosaPagar=explode('#',$_REQUEST["periodos"]);
				unset($periodosaPagar[count($periodosaPagar)-1]);
				$this->funcion->confirmarPeriodos($this->configuracion,$_REQUEST['estudiante'],$periodosaPagar);				
			

			}			
			else
			{	
				$this->funcion->consultarEstudiante($this->configuracion);
			}
			
		}
		else
		{
			if(!isset($_REQUEST["opcion"]))
			{
				$this->funcion->redireccionarInscripcion($this->configuracion,'verPeriodos',$_REQUEST['estudiante']);
			}
		}		
	}

	
	function action()
	{
		if(!isset($_REQUEST["periodos"])){
		
			$periodosaPagar="";
			
			foreach($_REQUEST as $clave=>$valor)
			{
				if((substr($clave,0,13))=='reciboperiodo'){
					$periodosaPagar.=$valor.'#';	
				}	
			}

			$variable[0]=$periodosaPagar;
			$variable[1]=$_REQUEST['estudiante'];
			
			
			if(!isset($_REQUEST["generar"])){
				$this->funcion->redireccionarInscripcion($this->configuracion,'confirmarPeriodos',$variable);	
				//$this->funcion->redireccionarInscripcion($configuracion,'confirmarPeriodos',$variable);
			}
			else{
				$valor=explode('#',$periodosaPagar);
				unset($valor[count($valor)-1]);
				
					
				$this->funcion->generarRecibosTerminacion($this->configuracion,$_REQUEST['estudiante'],$valor);

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
	$esteBloque->html();
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		$esteBloque->html();
		
	}
	else
	{
		$esteBloque->action();
		
	}
}


?>














<?



