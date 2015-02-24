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
class bloque_admincopiarHorarios extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{
 		$this->sql=new sql_copiarHorarios();
 		$this->funcion=new funciones_copiarHorarios($configuracion, $this->sql);
 		$this->configuracion=$configuracion;
	}
	
	
	function html()
	{
            //echo "html";var_dump($_REQUEST);exit;
		if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
					case "copiarHorarios":
						$this->funcion->formCopiarHorarios($this->configuracion);
						break;
					case "duplicarHorario":
						$this->funcion->duplicarHorario($this->configuracion);
						break;
					case "verReporte":
						$this->funcion->verReportes($this->configuracion);
						break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->formCopiarHorarios($this->configuracion);
			}
		}
		else
		{
			$valor[0]=$_REQUEST['proyecto'];
			$this->funcion->redireccionarInscripcion($this->configuracion, "formgrado");
		}
	}
	
	function action()
	{
            //echo "action";var_dump($_REQUEST);exit;
		$this->funcion->revisarFormulario();
		
		$tipo="busqueda";
		//echo $_REQUEST["opcion"]."<br>";
		
		//Rescatar datos de sesion
		$usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "id_usuario");
		$_REQUEST["registro"]=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "identificacion");
		if(!isset($_REQUEST['cancelar']))
		{
			if($_REQUEST["opcion"] == "duplicar")
			{
				$this->funcion->ejecutarDuplicarHorario($this->configuracion);
			}
			if($_REQUEST["opcion"] == "seleccionar")
			{
				$this->funcion->seleccionarPeriodo($this->configuracion);
			}
			if($_REQUEST["opcion"]=="verreporte")
			{
				$this->funcion->direccionaraVerReportes($this->configuracion);
			} 
		}
                else
		{
			$this->funcion->redireccionarInscripcion($this->configuracion, "formgrado",$_REQUEST);
		}

	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_admincopiarHorarios($configuracion);
//echo $_REQUEST['action'];

//var_dump($_REQUEST);
//exit;
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