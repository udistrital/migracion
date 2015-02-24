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

if($_REQUEST['co'] == "" || $_REQUEST['ti'] == "")
{
	echo "<script>location.replace('$redir?error_login=25')</script>";
	exit;
}
else
{
	$estado = $_REQUEST['es'];
	$qry="UPDATE ";
	$qry.="geclaves ";
	$qry.="SET ";
	$qry.="cla_estado ='".$estado."' ";
	$qry.="WHERE ";
	$qry.="cla_codigo ='".$_REQUEST['co']."' ";
	$qry.="AND ";
	$qry.="cla_tipo_usu ='".$_REQUEST['ti']."' ";
	$rowqry=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry,"busqueda");
	
	if(isset($rowqry))
	{
		header("Location: adm_perfil_usuario.php?u=".$_REQUEST['co']);
	}
}
?>