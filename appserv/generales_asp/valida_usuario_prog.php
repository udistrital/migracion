<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");


$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


session_name($usuarios_sesion);

$QryUsu="SELECT 'S'
	FROM acrecbanasp,acasperiadm
  	WHERE ape_ano = rba_ape_ano
	AND ape_per = rba_ape_per
	AND ape_estado = 'X'
	
	AND rba_clave = '".$_SESSION["usuario_password"]."'"; 

//AND rba_nro_iden = ".$_SESSION["usuario_login"]."

$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryUsu,"busqueda");

if($registro[0][0]!='S')
{
   session_destroy();
   die('<p>&nbsp;</p><p align="center"><b><font color="#FF0000"><u>Acceso incorrecto!</u></font></b></p>');
   exit;
}
?>
