<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 12 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		admin_solicitud
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.3
* @author		Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar las solicitudes de recibos de pago
*				realizadas por las diferentes coordinaciones. Implementa el
*				caso de uso: CONSULTAR SOLICITUD DE RECIBO DE PAGO
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
class bloqueAdminProyectoCurricular extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminProyectoCurricular();
 		$this->funcion=new funciones_adminProyectoCurricular($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{		
		//Rescatar datos de sesion
		$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
	    
		switch ($_REQUEST['opcion'])
			{ 
			  case 'consultar':
			  		//Consultar una carrera especifica
					$this->funcion->consultarCarrera($configuracion,$_REQUEST["carrera"]);
			  break;
			  
			  case 'acreditacion':
			  		$this->funcion->listaRegistro($configuracion,$_REQUEST["carrera"]);
			  break;
				
			  default:
			  		$variable[1]=$this->annoActual;
					$variable[2]=$this->periodoActual;
					//Rescatar Carreras
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->funcion->accesoOracle,"carreraCoordinador",$id_usuario);
					//	$cadena_sql.=" AND CRA_COD LIKE '%28%' "; 
					//echo $cadena_sql; 
					$registro=$this->funcion->ejecutarSQL($configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
					
					if(is_array($registro))
						{
							//Obtener el total de registros
							$totalRegistros=$this->funcion->totalRegistros($configuracion, $this->funcion->accesoOracle);
							if($totalRegistros>1)
								{
									$this->funcion->mostrarRegistro($configuracion,$registro, $totalRegistros, "multiplesCarreras", $variable);
								}
							else
								{
									//Consultar una carrera especifica
									$this->funcion->consultarCarrera($configuracion,$registro[0][0]);
								}
						}
					else
						{
							include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
							$cadena="No Existen Proyectos Curriculares Registrados.";
							alerta::sin_registro($configuracion,$cadena);
						}
							
			  	break;	
				
			}
		
	}
	
	
	function action($configuracion)
	{
		
		$this->funcion->editar_carrera($configuracion);
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloqueAdminProyectoCurricular($configuracion);


if(isset($_REQUEST['cancelar']))
{   unset($_REQUEST['action']);		
	$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
	$variable="pagina=ProyectoCurricular";
	$variable.="&opcion=mostrar";
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$this->cripto=new encriptar();
	$variable=$this->cripto->codificar_url($variable,$configuracion);
	
	echo "<script>location.replace('".$pagina.$variable."')</script>";
}


if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);

}


?>























