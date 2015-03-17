<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Karen Palacios
* @revision      Última revisión 13 de Junio de 2012
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		registro_inscripcion
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
* @author			Karen Palacios
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
class bloque_registroActualizaDatos extends bloque
{

	public function __construct($configuracion){
 		$this->sql=new sql_registroActualizaDatos();
 		$this->funcion=new funciones_registroActualizaDatos($configuracion, $this->sql);
 	}
	function html($configuracion){
	
		include_once("valida.js.php");
	
		if(!isset($_REQUEST['cancelar'])){
			if(isset($_REQUEST['opcion'])){
				$accion=$_REQUEST['opcion'];
				switch($accion){
					case "nuevo":
						$this->funcion->nuevoRegistro($configuracion);
					break;
				}
			}
			else{
				$accion="nuevo";
				$conexion=isset($conexion)?$conexion:'';
				$this->funcion->nuevoRegistro($configuracion,$conexion);
			}
		}
	}
	
	function action($configuracion){           
		$this->funcion->revisarFormulario();
		$tipo="busqueda";
		//Rescatar datos de sesion
		$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
		$_REQUEST["registro"]=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "identificacion");
		if(!isset($_REQUEST['cancelar'])){
			if(!isset($_REQUEST["confirmacion"])){
				if($_REQUEST["opcion"]=='editar'){	
					$valor = array_map(array($this,'clean'),$_REQUEST);
					$this->funcion->guardar($configuracion,$_REQUEST['dato'],$valor);
				}
			}
			else{
				$this->funcion->confirmarInscripcion($configuracion);
			}
		}
		else
		{
			$this->funcion->redireccionarInscripcion($configuracion, "");	
		}
		
	}
	function jxajax($configuracion){
		$accion=$_REQUEST['jxajax'];
		switch($accion){
			case "rescatarMunicipio":
				echo $this->funcion->rescatarMunicipios($configuracion,$_REQUEST['valor']);
			break;
			case "rescatarDepartamentos":
				echo $this->funcion->rescatarDepartamentos($configuracion);
			break;	
			case "rescatarBarrio":
				echo $this->funcion->rescatarBarrios($configuracion,$_REQUEST['valor']);
			break;
			case "rescatarLocalidades":
				echo $this->funcion->rescatarLocalidades($configuracion);
			break;				
		}			
	}	
	function clean($variable) {
		$variable=str_ireplace(array("<",">","[","]","*","^","update","select","delete","insert","where"),"",$variable);
		return $variable;
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroActualizaDatos($configuracion);

if(!isset($_REQUEST['jxajax'])){
	if(!isset($_REQUEST['action'])){
		$esteBloque->html($configuracion);
	}
	else{
		$esteBloque->action($configuracion);
	}
}else{
	$esteBloque->jxajax($configuracion);
}	


?>
