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
class bloque_admin_consultasCoordinador extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{
 		$this->sql=new sql_admin_consultasCoordinador();
 		$this->funcion=new funciones_admin_consultasCoordinador($configuracion, $this->sql);
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
					case "verProyectos":
					      $this->funcion->verProyectos($this->configuracion);
					      break;
					case "controlNotas":
						$this->funcion->vercontrolNotas($this->configuracion);
						break;
					case "registroAcuerdo":
						$this->funcion->registrarEstudiantes($this->configuracion);
						break;
					case "seleccionPeriodo":
						$this->funcion->seleccionarPeriodo($this->configuracion);
						break;
					case "histNotas":
						$this->funcion->historicoNotas($this->configuracion);
						break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->verProyectos($this->configuracion,$conexion);
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
			//echo "nnn".$_REQUEST['opcion']; exit;
			//echo "nnn".$_REQUEST["usuario"]; exit;
			$accion=$_REQUEST['opcion'];
			switch($accion)
			{
				case "seleccionarPeriodo":
					$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
					$variable="pagina=adminConsultasCoordinador";
					$variable.="&opcion=histNotas";
					$variable.="&carrera=".$_REQUEST["carrera"];
					$variable.="&periodo=".$_REQUEST["periodo"];
					$variable.="&usuario=".$_REQUEST["usuario"];
					include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
					$this->cripto=new encriptar();
					$variable=$this->cripto->codificar_url($variable,$this->configuracion);
					echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
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

$esteBloque=new bloque_admin_consultasCoordinador($configuracion);
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