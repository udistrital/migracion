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
class bloque_admin_evaldocentes extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_admin_evaldocentes();
 		$this->funcion=new funciones_admin_evaldocentes($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		if(!isset($_REQUEST['cancelar']))
		{
			
			if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				//echo "zzz".$_REQUEST["opcion"]; exit;
				switch($accion)
				{
					case "seleccionPeriodo":
						$this->funcion->seleccionarPeriodo($configuracion);
						break;
					case "observaciones":
                                                $registro=isset($registro)?$registro:'';
                                                $total=isset($total)?$total:'';
                                                 $valor=isset($valor)?$valor:''; 
						$this->funcion->reporteObservaciones($configuracion,$registro, $total, $opcion="",$valor);
						break;
					case "consultarDocente":
						$this->funcion->consultaDocente($configuracion);
						break;
					case "observacionesDoc":
						$this->funcion->consultaObservaciones($configuracion);
						break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->verProyectos($configuracion,$conexion);
			}
		}
		else
		{
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
			//echo "nnn".$_REQUEST['opcion']; exit;
			
			$accion=$_REQUEST['opcion'];
			switch($accion)
			{
				case "seleccionarPeriodo":
                                        $anio= isset($_REQUEST["anio"])?$_REQUEST["anio"]:'';
                                        $per= isset($_REQUEST["per"])?$_REQUEST["per"]:'';
					$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
					$variable="pagina=adminEvaldocentes";
					$variable.="&opcion=observaciones";
					$variable.="&anio=".$anio;
					$variable.="&per=".$per;
					$variable.="&periodo=".$_REQUEST["periodo"];
					$variable.="&facultad=".$_REQUEST["facultad"];
					$variable.="&accion=listaCompleta";
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
					$this->cripto=new encriptar();
					$variable=$this->cripto->codificar_url($variable,$configuracion);
					echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
				case "consultaObservacionesDocente":
					//echo "ll".$_REQUEST['opcion']; exit;
					$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
					$variable="pagina=adminEvaldocentes";
					$variable.="&opcion=observacionesDoc";
					$variable.="&docente=".$_REQUEST["docente"];
					$variable.="&accion=listaCompleta";
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
					$this->cripto=new encriptar();
					$variable=$this->cripto->codificar_url($variable,$configuracion);
					echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
			}
		}
		else
		{
			$valor[10]=$_REQUEST['nivel'];
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado",$valor);	
		}
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_admin_evaldocentes($configuracion);
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