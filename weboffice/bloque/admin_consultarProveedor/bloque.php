<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 05 de febrero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		consulta de proveedores
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
* @author			Johanna Correa Campos
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
class bloque_adminConsultarProveedor extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminConsultarProveedor();
 		$this->funcion=new funciones_adminConsultarProveedor($configuracion, $this->sql);
 		
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
					
					case "consultarProveedor":
						$this->funcion->consultaProveedor($configuracion);
						break;
					case "buscador":
						$this->funcion->buscaProveedor($configuracion);
						break;
					case "verProveedor":
						$this->funcion->verProveedor($configuracion);
						break;
					
				}
			}
			else
			{
				$accion="mostrar";
				$this->funcion->formularioRegistro($configuracion,$conexion);
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
		
		switch($_REQUEST['opcion'])
		{
                       case "buscador":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminConsultarProveedor";
				$variable.="&opcion=buscador";
				$variable.="&documento=".$_REQUEST["documento"];
				$variable.="&digito=".$_REQUEST["digito"];
				$variable.="&razonSocial=".$_REQUEST["razonSocial"];
				$variable.="&actividad=".$_REQUEST["actividad"];
				$variable.="&especialidad=".$_REQUEST["especialidad"];
                                $variable.="&codigoRad=".$_REQUEST["codigorad"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;


                       
		}

                $tipo="busqueda";
			
		//Rescatar datos de sesion
		$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
		$_REQUEST["registro"]=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "identificacion");
		
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminConsultarProveedor($configuracion);


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
