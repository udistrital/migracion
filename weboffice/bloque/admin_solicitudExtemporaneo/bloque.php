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
                $this->configuracion=$configuracion;

 		
	}
	
	
	function html()
	{
                $_REQUEST["opcion"]=(isset($_REQUEST["opcion"])?$_REQUEST["opcion"]:'');
                $_REQUEST['estudiante']=(isset($_REQUEST["estudiante"])?$_REQUEST["estudiante"]:'');
                $secuencias=(isset($secuencias)?$secuencias:'');
		if(!isset($_REQUEST["action"]))
		{

			if($_REQUEST["opcion"]=='exito')
			{
				include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");
				
                                $cadena=$_REQUEST['totalGenerados']." recibo(s) se generaron correctamente";
	
				alerta::sin_registro($this->configuracion,$cadena);

			}
                        elseif($_REQUEST["opcion"]=='noGenerados')
			{
				include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");
				
                                $cadena="No se genero ningún recibo";
	
				alerta::sin_registro($this->configuracion,$cadena);

			}
			elseif($_REQUEST["opcion"]=='noConfirmados')
			{
				include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");
				
                                $cadena="No confirmo la generación de recibo(s).";
	
				alerta::sin_registro($this->configuracion,$cadena);

			}
			elseif($_REQUEST["opcion"]=='verPeriodos')
			{
				
				$this->funcion->recibosGeneradosest($this->configuracion,$_REQUEST['estudiante'],$_REQUEST['anno_per']);
						
			}
			elseif(isset($_REQUEST["secuencias"]))
			{	
				$secuencias=explode('#',$_REQUEST["secuencias"]);
				unset($secuencias[count($secuencias)-1]);
				$this->funcion->confirmarPeriodos($this->configuracion,$_REQUEST['estudiante'],$_REQUEST['anno_periodo'],$secuencias);				
			

			}
			/*elseif(isset($_REQUEST["XXXXXX"])) //aqui va la redireccion al inicio de copnsulta
			{	
				$periodosaPagar=explode('#',$_REQUEST["periodos"]);
				unset($periodosaPagar[count($periodosaPagar)-1]);
				 	$this->funcion->escogerOpcionRecibo($configuracion);

			}*/						
			else
			{	
				//echo "c";
				$this->funcion->consultarEstudiante($this->configuracion,$_REQUEST['estudiante'],$secuencias);
				
			}
			
		}
		else
		{
			if(!$_REQUEST["opcion"])
			{
				$valor[0]=$_REQUEST['anio'].$_REQUEST['periodo'];
				$valor[1]=$_REQUEST['estudiante'];
				$this->funcion->redireccionarInscripcion($this->configuracion,'verPeriodos',$valor);
			}
		}		
	}

	
	function action()
	{
		if(!$_REQUEST["secuencias"]){
		
			$recibos="";
			
			
			///Rescatar las secuencias que se de se reexpedir
			foreach($_REQUEST as $clave=>$valor)
			{
				if((substr($clave,0,9))=='secuencia'){
					$recibos.=$valor;	
						foreach($_REQUEST as $numclave=>$numvalor) //Rescata la fecha y el numero de mesees de interes para la secuencia
						{	
							//echo $numclave;
							$numsecuencia=explode("@",$numclave);
							if(($numsecuencia[1])==$valor){
								$recibos.='@'.$numvalor;	
							}	
						}
					$recibos.='#';
				}	
			}

			$variable[0]=$recibos;
			$variable[1]=$_REQUEST['anno_periodo'];
			$variable[2]=$_REQUEST['estudiante'];
			
				/*echo "<pre>RECIBOS:  ";
					var_dump($variable);
				echo "</pre>";*/
			
			if(!isset($_REQUEST["generar"])){
				$this->funcion->redireccionarInscripcion($this->configuracion,'confirmarPeriodos',$variable);	
			}
			else{
				/*
				echo "<pre>";
					var_dump($variable);
				echo "</pre>";
				
				echo "<pre>";
					var_dump($_REQUEST);
				echo "</pre>";	*/
				
				
				$this->funcion->generarRecibosExtemporaneos($this->configuracion,$variable);

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
	//	echo "2";
	//var_dump($_REQUEST);
		$esteBloque->html();
		
	}
	else
	{
		//echo "3";
		$esteBloque->action();
		
	}
}


?>
