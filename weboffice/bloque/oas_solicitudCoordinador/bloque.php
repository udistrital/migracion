<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 12 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		admin_solicitud
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.3
* @author		Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar las solicitudes de recibos de pago
*				realizadas por las diferentes coordinaciones. Implementa el
*				caso de uso: CONSULTAR SOLICITUD DE RECIBO DE PAGO
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
class bloqueAdminSolicitudCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminSolicitudCoordinador();
 		$this->funcion=new funciones_adminSolicitudCoordinador($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		if(isset($_REQUEST["confirmar"]))
		{
			//Mostrar pantalla de confirmacion
			echo "<br>Entro en confirmacion //bloque.php Line 49";	
			echo "<br>_REQUEST[carrera]: ".$_REQUEST["carrera"]." //bloque.php Line 49";

			$this->funcion->confirmarGeneracion($configuracion);
		}
		elseif(!isset($_REQUEST["action"]))
		{
		
			$tipo="busqueda";
			
			//Rescatar datos de sesion
			$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
			$id_usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
			
			
			$variable[1]=isset($this->annoActual)?$this->annoActual:'';
			$variable[2]=isset($this->periodoActual)?$this->periodoActual:'';
			
			//TO REVIEW
			//Se rescatan las carreras de los cuales el usuario es coordinador, si el usuario tiene mas de una carrera
			//y no existe una solicitud explicita entonces se muestra un cuadro general con todas las carreras
			
			if(isset($_REQUEST["carrera"]))
			{
				//Consultar una carrera especifica
				
				$this->funcion->consultarCarrera($configuracion);
			}
			else
			{
				//Rescatar Carreras
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->funcion->accesoOracle,"carreraCoordinador",$id_usuario);
				$registro=$this->funcion->ejecutarSQL($configuracion, $this->funcion->accesoOracle, $cadena_sql, $tipo);
				
				if(is_array($registro))
				{
					//Obtener el total de registros
					$totalRegistros=$this->funcion->totalRegistros($configuracion, $this->funcion->accesoOracle);
					if($totalRegistros>1)
					{
						$this->funcion->mostrarRegistro($configuracion,$registro, $totalRegistros, "multiplesCarreras", $variable);
					}
					else
					{
						
						$_REQUEST["carrera"]=$registro[0][0];
						//Consultar una carrera especifica
						$this->funcion->consultarCarrera($configuracion);
					}
					
					
				}
				else
				{
					
				}
			}
		}
		
	}
	
	
	function action($configuracion)
	{
		
		$this->funcion->generarLoteRecibos($configuracion);
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloqueAdminSolicitudCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{	
	//echo "<br>No hay action";
	$esteBloque->html($configuracion);
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		//echo "<br>No hay confirmar";
		$esteBloque->action($configuracion);
	}
	else
	{	
		//echo "<br>Si hay action y si hay confirmar";
		$esteBloque->html($configuracion);	
	}
}


?>




















