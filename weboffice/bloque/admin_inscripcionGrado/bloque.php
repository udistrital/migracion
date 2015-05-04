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
class bloque_adminInscripcionGrado extends bloque
{		
	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminInscripcionGrado();
 		$this->funcion=new funciones_adminInscripcionGrado($configuracion, $this->sql);
 		
	}
	
	function html($configuracion)
	{

		$tema =  isset($tema)?$tema:'';
		$formulario = isset($formulario)?$formulario:'';
		$verificar = isset($verificar)?$verificar:'';
		$fila = isset($fila)?$fila:'';
		$tab = isset($tab)?$tab:'';
		$accesoOracle = isset($accesoOracle)?$accesoOracle:'';
		$acceso_db = isset($acceso_db)?$acceso_db:'';
		
		if(isset($_REQUEST['opcion']))
		{
			
			$registro=(isset($registro)?$registro:'');
			$total = (isset($total)?$total:'');
			$valor= (isset($valor)?$total:'');
			$accion=$_REQUEST['opcion'];
			
			switch($accion)
			{
				case "listaCompleta":
					$this->funcion->mostrarRegistro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$this->funcion->acceso_db);
					break;
				case "nuevo":
					$this->funcion->nuevoRegistro($configuracion,$conexion);
					break;
				case "listadoTotalProyecto":
					$this->funcion->listadoProyecto($configuracion, $accesoOracle,$acceso_db);
					break;
				case "listadoTotalCarrera":
					$this->funcion->listadoCarrera($configuracion,$registro, $total, $opcion="",$valor);
					break;
				case "formularioInscripcion":
					$this->funcion->mostrarFormulario($configuracion,$registro, $total, $opcion="",$valor);
					break;
				case "generaExcel":
					$this->funcion->reporteExcel($valor);
					break;
				case "promedioEgresados":
					$this->funcion->consultaPromedioEgresados($configuracion, $accesoOracle,$acceso_db);
					break;
				case "reportePromEgresados":
					$this->funcion->reportePromedioEgresados($configuracion, $accesoOracle,$acceso_db);
					break;
			}
		}
		else
		{
			$accion="listaCompleta";
			$this->funcion->mostrarRegistro($configuracion,$conexion);
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
		
		if(isset($_REQUEST["opcion"])||!isset($_REQUEST["consultar"]))
		{
			$this->funcion->mostrarRegistro($configuracion,$this->funcion->accesoOracle,$this->funcion->acceso_db);
		}
		if(!isset($_REQUEST["opcion"])||isset($_REQUEST["consultar"]))
		{
			$this->funcion->ejecutarConsultaPromedioEgresados($configuracion, $accesoOracle,$acceso_db);
		}
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminInscripcionGrado($configuracion);
//echo $_REQUEST['action'];
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
