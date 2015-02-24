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
class bloque_admin_consultasAdmisiones extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_admin_consultasAdmisiones();
 		$this->funcion=new funciones_admin_consultasAdmisiones($configuracion, $this->sql);
 		
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
					case "consultaDatosAspirantes":
						$this->funcion->consultaDatosAspirantes($configuracion, $accesoOracle,$acceso_db);
						break;
					case "mostrarDatos":
						$this->funcion->rescatarDatos($configuracion, $accesoOracle,$acceso_db);
						break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->consultaDatosAspirantes($configuracion, $accesoOracle,$acceso_db);
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
			if($_REQUEST["opcion"] == "buscador")
			{	
				$valor[0]=$_REQUEST['credencial'];
				$this->funcion->redireccionarInscripcion($configuracion, "mostrardatos",$valor);
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

$esteBloque=new bloque_admin_consultasAdmisiones($configuracion);
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