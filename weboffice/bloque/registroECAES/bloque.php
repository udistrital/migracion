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
class bloque_registroInscripcionEcaes extends bloque
{
        public $configuracion;

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroInscripcionEcaes();
 		$this->funcion=new funciones_registroInscripcionEcaes($configuracion, $this->sql);
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
					case "nuevo":
						$this->funcion->nuevoRegistro($this->configuracion,$this->funcion->conexion);
						break;
					case "mostrar":
                                                $tab=0;
                                                $fila=0;
                                                $verificar=0;
                                                $formulario="";
                                                $tema="";
						$this->funcion->datosEstudiante($this->configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$this->funcion->acceso_db);
						break;
					case "exito":
						$this->funcion->confirmacionRegistro($this->configuracion, $this->funcion->accesoOracle,$this->funcion->acceso_db);
						break;
					case "yains":
						$this->funcion->mensajeyainscrito($this->configuracion, $this->funcion->accesoOracle,$this->funcion->acceso_db);
						break;
					case "noencontrado":
						$this->funcion->mensajecodnoencontrado($this->configuracion, $this->funcion->accesoOracle,$this->funcion->acceso_db);
						break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->nuevoRegistro($this->configuracion,'');
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
			if(isset($_REQUEST["opcion"]))
			{
				$this->funcion->ejecutarConsulta($this->configuracion,$this->funcion->accesoOracle,$this->funcion->acceso_db);
			}
			if(isset($_REQUEST["inscribir"]))
			{
				$this->funcion->guardarInscripcion($this->configuracion, $this->funcion->accesoOracle,$this->funcion->acceso_db);
			}
		}
		else
		{
			$this->funcion->redireccionarInscripcion($this->configuracion, "formgrado");	
		}
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroInscripcionEcaes($configuracion);
//echo $_REQUEST['action'];
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