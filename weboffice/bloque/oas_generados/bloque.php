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
class bloqueAdminSolicitud extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminSolicitud();
 		$this->funcion=new funciones_adminSolicitud($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		if(isset($_REQUEST["confirmar"]))
		{
			//Mostrar pantalla de confirmacion
			$this->funcion->confirmarDesbloqueo($configuracion);
		}
		elseif(!isset($_REQUEST["action"]))
		{
		
			//Conexion ORACLE
			$accesoOracle=$this->funcion->conectarDB($configuracion,"oracle");
			//Conexion General
			$acceso_db=$this->funcion->conectarDB($configuracion,"");
			
			
			$conexion=$accesoOracle;
			$tipo="busqueda";
			
			//Rescatar datos de sesion
			$usuario=$this->funcion->rescatarValorSesion($configuracion, $acceso_db, "usuario");
			$id_usuario=$this->funcion->rescatarValorSesion($configuracion, $acceso_db, "id_usuario");
			
			//Rescatar Datos Generales
			$annoActual=$this->funcion->datosGenerales($configuracion,$conexion, "anno") ;
			$periodoActual=$this->funcion->datosGenerales($configuracion,$conexion, "per") ;
			
			echo "<br><span class='texto_subtitulo'>Periodo:  $annoActual / $periodoActual</span>";
			
			$variable[1]=$annoActual;
			$variable[2]=$periodoActual;
			
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
				unset($registro);
				$cadena_sql=$this->sql->cadena_sql($configuracion,$conexion,"carreraCoordinador",$id_usuario);
				
				//echo $cadena_sql;

				$registro=$this->funcion->ejecutarSQL($configuracion, $conexion, $cadena_sql, $tipo);
				if(is_array($registro))
				{
					//Obtener el total de registros
					$totalRegistros=$this->funcion->totalRegistros($configuracion, $conexion);
					
					echo "Total de Registros = $totalRegistros";

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
					$_REQUEST["carrera"]=$registro[0][0];
						//Consultar una carrera especifica
					$this->funcion->consultarCarrera($configuracion);
				}
			}
		}
	}
	
	
	function action($configuracion)
	{
		$accesoOracle=$this->funcion->conectarDB($configuracion,"oracle");
		if($accesoOracle){ echo "conectado";}
		$this->funcion->desbloquearRecibos($configuracion,$accesoOracle);
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloqueAdminSolicitud($configuracion);

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
	else
	{
		
		$esteBloque->html($configuracion);	
	}
}


?>




















