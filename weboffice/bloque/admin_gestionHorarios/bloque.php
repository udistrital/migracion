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
class bloque_admin_gestionHorarios extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_admin_gestionHorarios();
 		$this->funcion=new funciones_admin_gestionHorarios($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
					case "gestionHorarios":
						$this->funcion->gestioHorarios($configuracion, $accesoOracle,$acceso_db);
						break;
					case "duplicarHorario":
						$this->funcion->duplicarHorario($configuracion, $accesoOracle,$acceso_db);
						break;
					case "verReporte":
						$this->funcion->verReportes($configuracion, $accesoOracle,$acceso_db);
						break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->gestioHorarios($configuracion, $accesoOracle,$acceso_db);
			}
		}
		else
		{
			$valor[0]=$_REQUEST['proyecto'];
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado");
		}
	}
	
	function action($configuracion)
	{
		$this->funcion->revisarFormulario();
		
		$tipo="busqueda";
		//echo $_REQUEST["opcion"]."<br>";
		
		//Rescatar datos de sesion
		$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
		$_REQUEST["registro"]=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "identificacion");
		if(!isset($_REQUEST['cancelar']))
		{
			if($_REQUEST["opcion"] == "duplicar")
			{
				$this->funcion->ejecutarDuplicarHorario($configuracion, $accesoOracle,$acceso_db);
			}
			if($_REQUEST["opcion"] == "seleccionar")
			{
				$this->funcion->selecconarPeriodo($configuracion, $accesoOracle,$acceso_db);
			}
			if($_REQUEST["opcion"]=="verreporte")
			{
				$this->funcion->direccionaraVerReportes($configuracion, $accesoOracle,$acceso_db);
			} 
		}
		else
		{
			$valor[0]=$_REQUEST['proyecto'];
			//echo "mmm".$_REQUEST['proyecto']; exit;
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado",$valor);	
		}	
		
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_admin_gestionHorarios($configuracion);
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