<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Karen Palacios
* @revision      Última revisión 04 de Marzo de 2010
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		admin_solicitudIndividual
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.1
* @author		Karen Palacios
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar las solicitudes de recibos de pago
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

			if((isset($_REQUEST["opcion"]))&&$_REQUEST["opcion"]=='exito')
			{
				include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");
				
				$cadena="Los recibos se generaron correctamente";
	
				alerta::sin_registro($this->configuracion,$cadena);

			}
			elseif((isset($_REQUEST["opcion"]))&&$_REQUEST["opcion"]=='verPeriodos')
			{
				$this->funcion->consultarPeriodos($this->configuracion,$_REQUEST['estudiante']);
			}
			elseif(isset($_REQUEST["confirmar"]))
			{			
				
					$valor[1]=$_REQUEST["conceptoSeguro"];
					$valor[2]=$_REQUEST["conceptoCarnet"];
					$valor[3]=$_REQUEST["conceptoSistem"];
					$valor[4]=$_REQUEST["ordinaria"];
								
				$this->funcion->confirmaRecibo($this->configuracion,$_REQUEST['estudiante'],$valor);				
			

			}			
			else
			{	
				$this->funcion->consultarEstudiante($this->configuracion);
			}
			
		}
		else
		{
			if(!(isset($_REQUEST["opcion"])))
			{
				$this->funcion->redireccionarInscripcion($this->configuracion,'verPeriodos',$_REQUEST['estudiante']);
			}
		}		
	}

	
	function action()
	{
		if(!$_REQUEST["periodos"]){
		
			$periodosaPagar="";
			
				
				//1=Tiene el concepto 0= No lo tiene
				
				$conceptos[1]=0;
				$conceptos[2]=0;
				$conceptos[3]=0;
				
				if(isset($_REQUEST["conceptoSeguro"])){
					$conceptos[1]=1;
				}
				if(isset($_REQUEST["conceptoCarnet"])){
					$conceptos[2]=1;
				}
				if(isset($_REQUEST["conceptoSistem"])){
					$conceptos[3]=1;
					
				}								
					
					$valor[0]=$_REQUEST["estudiante"];
					$valor[1]=$conceptos[1];
					$valor[2]=$conceptos[2];
					$valor[3]=$conceptos[3];
					$valor[4]=$_REQUEST["ordinaria"];
							
			if(!isset($_REQUEST["generar"])){
				$this->funcion->redireccionarInscripcion($this->configuracion,'confirmarRecibo',$valor);	
				//$this->funcion->redireccionarInscripcion($configuracion,'confirmarPeriodos',$variable);
			}
			else{
				
				$this->funcion->generarRecibosIndividual($this->configuracion,$_REQUEST["id"]);
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



