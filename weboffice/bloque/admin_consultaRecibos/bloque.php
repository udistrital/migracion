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
class bloque_admin_consultaRecibos extends bloque
{
    public $configuracion;
    public function __construct($configuracion)
	{
 		$this->sql=new sql_admin_consultaRecibos();
 		$this->funcion=new funciones_admin_consultaRecibos($configuracion, $this->sql);
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
					case "seleccionarPeriodo":
						$this->funcion->seleccionarPeriodo($this->configuracion);
						break;
					case "consultaProyectos":
						$this->funcion->verProyectos($this->configuracion);
						break;
					case "enviaCorreo":
						$this->funcion->enviarCorreo($this->configuracion);
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
			if(isset($_REQUEST["opcion"]) && isset($_REQUEST["action"]))
			{
                            unset($_REQUEST['action']);
                            $periodo=explode('-',$_REQUEST['periodo_codif']);
                            $pagina=$this->configuracion['host'].$this->configuracion['site'].'/index.php?';
                            $variable='pagina=adminConsultaRecibos';
                            $variable.='&opcion=consultaProyectos';
                            $variable.='&usuario='.$_REQUEST['usuario'];
                            $variable.='&anio='.$periodo[0];
                            $variable.='&periodo='.$periodo[1];
                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
//                            echo "<br>variable ".$pagina.$variable;exit;
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                            echo "<script>location.replace('".$pagina.$variable."')</script>";
			}
			if(!isset($_REQUEST["opcion"]) && isset($_REQUEST["borrar"]) && !isset($_REQUEST["grabobs"]) && !isset($_REQUEST["modbobs"]))
			{
				$valor[10]=$_REQUEST['nivel'];
				$this->funcion->eliminarActividad($this->configuracion, $accesoOracle,$acceso_db);
			}
			if(!isset($_REQUEST["opcion"]) && !isset($_REQUEST["borrar"]) && isset($_REQUEST["grabobs"]) && !isset($_REQUEST["modbobs"]))
			{
				$valor[10]=$_REQUEST['nivel'];
				$this->funcion->grabarObservacion($this->configuracion, $accesoOracle,$acceso_db);
			}
			if(!isset($_REQUEST["opcion"]) && !isset($_REQUEST["borrar"]) && !isset($_REQUEST["grabobs"]) && isset($_REQUEST["modbobs"]))
			{
				$valor[10]=$_REQUEST['nivel'];
				$this->funcion->ModificarObservacion($this->configuracion, $accesoOracle,$acceso_db);
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

$esteBloque=new bloque_admin_consultaRecibos($configuracion);
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