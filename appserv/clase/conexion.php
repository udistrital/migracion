<?PHP
require_once("../clase/config.class.php");
require_once("../clase/funcionGeneral.class.php");

//echo "hola";

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../"); 

//Por defecto rescata 
$conexion=new funcionGeneral();

$oci_conecta=$conexion->conectarDB($configuracion,"default");
$oci_logueo=$conexion->conectarDB($configuracion,"logueo");
//echo $oci_conecta;

if($oci_conecta || $oci_logueo)
{
	//inclue('interno.php');
	if(!isset($_SESSION['usuario_nivel']))
	{
		session_name($configuracion["usuarios_sesion"]);
		session_start();
	}
}
else
{
	header("Location: ../?error_login=109");
	exit;
}
// Deshabilitar todo reporte de errores
error_reporting(0);

?>
