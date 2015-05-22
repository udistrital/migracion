<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Karen Palacios
* @revision      Última revisión 05 de abril de 2010
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		admin_recibosECAES
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.1
* @author		Karen Palacios
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar las solicitudes de recibos de pago
*			de los estudiantes de ECAES
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
                        $opcion=isset($_REQUEST["opcion"])?$_REQUEST["opcion"]:'';
                        if($opcion=='exito')
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				
				$cadena="Los recibos se generaron correctamente";
	
				alerta::sin_registro($configuracion,$cadena);

			}
			elseif(isset($_REQUEST["estudiantes"]))
			{	
				$estudiantes=explode('#',$_REQUEST["estudiantes"]);
				unset($estudiantes[count($estudiantes)-1]);
				$this->funcion->confirmarPeriodos($configuracion,$estudiantes);				
			

			}			
			else
			{	
				$this->funcion->consultarPeriodos($configuracion);
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
		if(isset($_REQUEST["confirmar"])){
		
			$estudiantes="";
			
			//AQUI  ARREGLAR PERIODO X CODIGO
			
			foreach($_REQUEST as $clave=>$valor)
			{
			//echo substr($clave,0,6);
				if((substr($clave,0,7))=='codigos'){
					$estudiantes.=$valor.'#';	
				}	
			}

			$variable[0]=$estudiantes;
	
			
			if(!isset($_REQUEST["generar"])){
				$this->funcion->redireccionarInscripcion($configuracion,'confirmarPeriodos',$variable);	
				//$this->funcion->redireccionarInscripcion($configuracion,'confirmarPeriodos',$variable);
			}
			else{
			//echo "aaa";
				$valor=explode('#',$_REQUEST['estudiantes']);
				unset($valor[count($valor)-1]);
			
				$this->funcion->generarRecibosECAES($configuracion,$valor);

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
	//echo "1";
	$esteBloque->html($configuracion);
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		//echo "2";
	    //var_dump($_REQUEST);
		$esteBloque->html($configuracion);
		
	}
	else
	{
		//echo "3";
		$esteBloque->action($configuracion);
		
	}
}


?>














<?



