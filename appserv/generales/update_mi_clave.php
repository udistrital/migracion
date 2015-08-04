<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");


$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
//establece conexion para mysql
$accesoMY=$conexion->estableceConexion('cambio_claveMY');




$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

$QryClave = "SELECT cla_clave FROM geclaves
	WHERE cla_codigo = ".$_SESSION['usuario_login']."
	AND cla_tipo_usu = ".$_SESSION['usuario_nivel'];

$RowClave = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryClave,"busqueda");
$Clave = $RowClave[0][0];

if($Clave == "")
{
	echo "<script>location.replace('$redir?error_login=4')</script>";	
	exit;
}
if($Clave == md5($_REQUEST['nc']))
{
	echo "<script>location.replace('$redir?error_login=32')</script>";
	exit;
}
if($_POST['nc'] != $_REQUEST['rnc'])
{
	echo "<script>location.replace('$redir?error_login=12')</script>";
	exit;
}
if($_REQUEST['nc'] == "" || $_REQUEST['rnc'] == ""){
	echo "<script>location.replace('$redir?error_login=3')</script>";
	exit;
}
else{
	$encriptaclave = md5($_REQUEST['nc']);
	$qry="UPDATE ";
	$qry.="geclaves ";
	$qry.="SET ";
	$qry.="cla_clave ='$encriptaclave' ";
	$qry.="WHERE ";
	$qry.="cla_codigo =".$_SESSION['usuario_login']." ";
	
	$resultado =$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry,"busqueda");
        if(isset($accesoMY)){$resultado2 =$conexion->ejecutarSQL($configuracion,$accesoMY,$qry,"busqueda");}
	echo "<script>location.replace('$redir?error_login=13')</script>";
}
?>