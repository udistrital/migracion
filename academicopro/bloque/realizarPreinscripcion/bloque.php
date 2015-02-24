<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 05 de febrero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		registro_inscripcion
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
* @author			Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	
*
/*--------------------------------------------------------------------------------------------------------------------------*/
if(!isset($GLOBALS["autorizado"]))//es la autorización que viene de pagina.class.php linea 28
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");
//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/multiConexion.class.php");


//Clase
class bloque_realizarPreinscripcion extends bloque //añade o camiba la funcionalidad a la clase bloque
{

	 public function __construct($configuracion) //se ejecuta cuando se crea un objeto con esta clase
	{
 		$this->sql=new sql_realizarPreinscripcion();
 		$this->funcion=new funciones_realizarPreinscripcion($configuracion, $this->sql);
                //$conexion=new multiConexion();
 		//$this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
                //$this->acceso_db=$this->conectarDB($configuracion,$tipoUser);
                //$this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

                
	}
	
	
	function html($configuracion)
	{
		$this->acceso_db=$this->funcion->conectarDB($configuracion);
                if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
					case "mensaje":
						$this->funcion->mensaje($configuracion, $this->acceso_db);
						break;
					case "planestudios":
						$this->funcion->seleccionarProyecto($configuracion, $this->acceso_db);
						break;
    					case "parametros":
						$this->funcion->parametros($configuracion,$accion);
						break;		
					case "borrar":
						$this->funcion->datos($configuracion,$this->funcion->acceso_db);
						break;
                                        case "cancelar":
                                                $this->funcion->cancelar($configuracion);
					
				}
			}
			else
			{
                                $this->funcion->cancelar($configuracion);
			}
		}
		else
		{
                        $this->funcion->cancelar($configuracion);
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
		//si elige ACEPTAR
                {
			if(isset($_REQUEST["opcion"]))
			{
                                $this->funcion->parametros($configuracion,$this->funcion->accesoOracle,$this->funcion->acceso_db);
			}
			if(isset($_REQUEST["datos"]))
			{
				$this->funcion->borrarDatosPrevios($configuracion,$this->funcion->accesoGestion, $this->funcion->accesoOracle);
			}
			if(isset($_REQUEST["borrar"]))
			{
				echo "Borrando datos de Pre-inscripci&oacute;n...";
                                $this->funcion->borrarDatosPrevios($configuracion,$this->funcion->accesoGestion, $this->funcion->accesoOracle);
			}
			if(isset($_REQUEST["guardar"]))
			{
				echo "Guardando datos de inscripción...";
                                $this->funcion->guardarDatos($configuracion,$this->funcion->accesoGestion, $this->funcion->accesoOracle);
			}
		}
                //si se elige CANCELAR en el formulario
		else
		{
                        echo "Cancelando...";
                        $this->funcion->redireccionarProceso($configuracion, "cancelar", "");
		}

	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_realizarPreinscripcion($configuracion);


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
