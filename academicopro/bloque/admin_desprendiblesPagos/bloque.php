<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @revision      Última revisión 05 de febrero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		registro_inscripcion
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	
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
class bloque_admin_certIngresosRetenciones extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_admin_certIngresosRetenciones();
 		$this->funcion=new funciones_admin_certIngresosRetenciones($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		if(!isset($_REQUEST['cancelar']))
		{
		    
            //Se obtienen las variables de la coneccion juusechec
            $conexion = $this->funcion->conexion;
            $accesoOracle = $this->funcion->accesoOracle;
            $acceso_db = $this->funcion->acceso_db;
            //Se termina la adquisición de los valores de la conexión
			if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
					case "cesantias":
						$this->funcion->desprendibleCesantias($configuracion, $accesoOracle,$acceso_db);
						break;
					case "generarCertificado":
						$this->funcion->generarCertificado($configuracion);
						break;
					default:
                                            $this->funcion->principal($configuracion,$conexion);
                                            break;
					
					
				}
			}
			else
			{
				$accion="nuevo";
                                $this->funcion->principal($configuracion,$conexion);
			}
		}
		else
		{
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado");	
		}
	}
	
	function action($configuracion)
	{
		$this->funcion->revisarFormulario();
		
		$tipo="busqueda";
			
		//Rescatar datos de sesion
		$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
		$_REQUEST["registro"]=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "identificacion");
		if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST["opcion"]))
			{
				$this->funcion->guardarNotasPregrado($configuracion, $accesoOracle,$acceso_db);
			}
		}
		else
		{
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado",$valor);	
		}
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_admin_certIngresosRetenciones($configuracion);
//var_dump($_REQUEST);exit;
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
}


?>