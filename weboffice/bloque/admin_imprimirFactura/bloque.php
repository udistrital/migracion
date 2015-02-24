<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");
//Clase
class bloqueGenerarFactura extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->funcion=new funciones_generarFactura($configuracion);
 		$this->sql=new sql_generarFactura();
	}
	
	
	function html($configuracion)
	{
		$this->acceso_db=$this->conectarDB($configuracion);
		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
		$nueva_sesion=new sesiones($configuracion);
		$nueva_sesion->especificar_enlace($enlace);
		$esta_sesion=$nueva_sesion->numero_sesion();
		//Rescatar el valor de la variable usuario de la sesion
		$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
		if($registro)
		{
			
			$id_usuario=$registro[0][0];
		}
		else
		{
			exit;
		
		}
		

		
// 		//MatrizGeneral
// 		$matrizGeneral=$this->funcion->informacionGeneral($configuracion,$accesoOracle);
// 		
// 		//Matriz Detalle de pago
// 		$matrizDetalle=$this->funcion->detallePago($configuracion);
// 		
// 		//Matriz codigoBarras
// 		//Codigo ordinario
// 		$codigoOrdinario=$this->funcion->codigoBarras($configuracion,"ordinario");
// 		
// 		//Codigo extraordinario
// 		$codigoExtraordinario=$this->funcion->codigoBarras($configuracion,"extraordinario");
// 		
// 		//Generar imagen codigo ordinario
// 		$imagenOrdinario=$this->funcion->imagenCodigoBarras($configuracion,$codigoOrdinario);
// 		
// 		//Generar imagen codigo extraordinario
// 		$imagenExtraordinario=$this->funcion->imagenCodigoBarras($configuracion,$codigoExtraordinario);
	}
	
	
	function action()
	{
		//Procesar el formulario
		
	
	
	}
	
	function conectarDB($configuracion,$nombre="")
	{
	
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
		
		$this->conexion=new dbConexion($configuracion);
		
		return $this->conexion->recursodb($configuracion,$nombre);
	}	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloqueGenerarFactura($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>
