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
class bloque_registroNotasDocentes extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroNotasDocentes();
 		$this->funcion=new funciones_registroNotasDocentes($configuracion, $this->sql);
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
					case "dignotasPregrado":
						$this->funcion->digitarNotasPregrado($configuracion);
						break;
					case "dignotasPosgrado":
						$this->funcion->digitarNotasPosgrado($configuracion);
						break;
					case "mensajes":
						$this->funcion->mensajesErrores($configuracion);
						break;
					case "exito":
						$this->funcion->mensajeyainscrito($configuracion);
						break;
					case "reportes":
						$this->funcion->ReporteNotas($configuracion);
						break;
					case "verfechas":
						$this->funcion->fechasNotas($configuracion);
						break;
					case "notasPerAnterior":
						$this->funcion->notasPerAnterior($configuracion);
						break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->verListaClase($configuracion);
			}
		}
		else
		{
			$valor[4]=$_REQUEST['nivel'];
			$valor[10]=$_REQUEST['periodo'];
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
			$valor[4]=$_REQUEST['nivel'];
			if(isset($_REQUEST["opcion"]) && !isset($_REQUEST["notdef"]) && !isset($_REQUEST["opcionpos"]))
			{
                            $this->funcion->guardarNotasPregrado($configuracion);
			}
			if(isset($_REQUEST["opcionpos"]) && !isset($_REQUEST["notdef"]) && !isset($_REQUEST["opcion"]))
			{
                            $this->funcion->guardarNotasPosgrado($configuracion);
			}
			if(isset($_REQUEST["notdef"]))
			{
                            $this->funcion->calculoDefinitiva($configuracion);
			}
		}
		else
		{
                    $valor[4]=$_REQUEST['nivel'];
			$valor[10]=$_REQUEST['periodo'];
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado",$valor);	
		}
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroNotasDocentes($configuracion);
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