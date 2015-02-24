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
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloque_registro_PlanTrabajo extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{
 		$this->sql=new sql_registro_PlanTrabajo();
 		$this->funcion=new funciones_registro_PlanTrabajo($configuracion, $this->sql);
                $this->configuracion=$configuracion;
 		
	}
	
	
	function html()
	{
		if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
					case "dignotasPregrado":
						$this->funcion->digitarNotasPregrado($this->configuracion, $accesoOracle,$acceso_db);
						break;
					case "actividades":
						$this->funcion->registroActividades($this->configuracion);
						break;
					case "nuevoRegistro":
						$this->funcion->registrarPlanTrabajo($this->configuracion);
						break;
					case "mensajes":
						$this->funcion->mensajesErrores($this->configuracion);
						break;
					case "reportes":
						$this->funcion->reportes($this->configuracion);
						break;
					case "borrar":
						$this->funcion->borrarActividades($this->configuracion);
						break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->registrarPlanTrabajo($this->configuracion);
			}
		}
		else
		{
			$this->funcion->redireccionarInscripcion($this->configuracion, "formgrado");	
		}
	}
	
	function action()
	{
                $this->funcion->revisarFormulario();
		
		$tipo="busqueda";
			
		//Rescatar datos de sesion
		$usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "id_usuario");
		$_REQUEST["registro"]=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "identificacion");
		if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST["opcion"]) && !isset($_REQUEST["borrar"]) && !isset($_REQUEST["grabobs"]) && !isset($_REQUEST["modbobs"]))
			{
				$valor[10]=$_REQUEST['nivel'];
				$this->funcion->guardarPlanTrabajo($this->configuracion);
			}
			if(!isset($_REQUEST["opcion"]) && isset($_REQUEST["borrar"]) && !isset($_REQUEST["grabobs"]) && !isset($_REQUEST["modbobs"]))
			{
				$valor[10]=$_REQUEST['nivel'];
				$this->funcion->eliminarActividad($this->configuracion);
			}
			if(!isset($_REQUEST["opcion"]) && !isset($_REQUEST["borrar"]) && isset($_REQUEST["grabobs"]) && !isset($_REQUEST["modbobs"]))
			{
				$valor[10]=$_REQUEST['nivel'];
				$this->funcion->grabarObservacion($this->configuracion);
			}
			if(!isset($_REQUEST["opcion"]) && !isset($_REQUEST["borrar"]) && !isset($_REQUEST["grabobs"]) && isset($_REQUEST["modbobs"]))
			{
				$valor[10]=$_REQUEST['nivel'];
				$this->funcion->ModificarObservacion($this->configuracion);
			}
		}
		else
		{
			$valor[10]=$_REQUEST['nivel'];
			$this->funcion->redireccionarInscripcion($this->configuracion, "formgrado",$valor);	
		}
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registro_PlanTrabajo($configuracion);
if(!isset($_REQUEST['action']))
{
	$esteBloque->html();
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		$esteBloque->action();
	}
}


?>