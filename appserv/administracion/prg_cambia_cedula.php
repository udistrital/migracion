<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(20);
$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

$UpdDoc = "BEGIN Pck_Pr_Actualiza_Docente.Pra_Nro_iden(".$_REQUEST['CedInCor'].",".$_REQUEST['CedCor']."); END;";

$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$UpdDoc,"busqueda");

if(isset($registro))
{
	echo "<script>location.replace('$redir?error_login=31')</script>";	
}
else
{
	echo "Operaci&oacute;n fallida";
}

?>