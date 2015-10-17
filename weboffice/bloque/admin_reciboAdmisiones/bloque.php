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
class bloque_adminAdmisiones extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminAdmisiones();
 		$this->funcion=new funciones_adminAdmisiones($configuracion, $this->sql);
 		
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
					case "mostrar":
						$this->funcion->mostrarRegistro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$this->funcion->acceso_db);
						break;
					case "nuevo":
						$this->funcion->nuevoRegistro($configuracion);
						break;
					case "credencial":
						$this->funcion->reciboCredencial($configuracion);
						break;
					case "fecha":
						$this->funcion->reciboFecha($configuracion);
						break;
					case "adminFechasRecibos":
						$this->funcion->administracionFechasRecibos($configuracion);
						break;
					case "verPDFporFecha":
						$this->funcion->consultarPDFporFecha($configuracion);
						break;  
					case "exitoEditarAplic":
						$this->funcion->registroEditado($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$this->funcion->acceso_db);
						break;
				}
			}
			else
			{	
				$accion="nuevo";
				$this->funcion->nuevoRegistro($configuracion);
			}
		}
		else
		{
                    $valor[1]=(isset($_REQUEST['anio'])?$_REQUEST['anio']:'');
                    $valor[2]=(isset($_REQUEST['periodo'])?$_REQUEST['periodo']:'');
                    $valor[3]=(isset($_REQUEST['nivel'])?$_REQUEST['nivel']:'');
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado",$valor);	
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
			$valor[3]=$_REQUEST['nivel'];
			if(isset($_REQUEST["consultar"])&&!isset($_REQUEST["modificar"]))
			{
				$this->funcion->consultarRegistro($configuracion);
			}
			if(!isset($_REQUEST["consultar"])&&isset($_REQUEST["modificar"]))
			{
				$this->funcion->modificarFechasPago($configuracion);
			}
			
		}
		else
		{
                    $valor[1]=(isset($_REQUEST['anio'])?$_REQUEST['anio']:'');
                    $valor[2]=(isset($_REQUEST['periodo'])?$_REQUEST['periodo']:'');
                    $valor[3]=(isset($_REQUEST['nivel'])?$_REQUEST['nivel']:'');
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado",$valor);	
		}
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminAdmisiones($configuracion);
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
