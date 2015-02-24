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
//LLAMADO DE: adm_insert_geclaves.php

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

if($_REQUEST['cod'] == "" || $_REQUEST['cla'] == "" || $_REQUEST['tip'] == "") {
   header("Location: $redir?error_login=17");
   exit;
}

$datos = "SELECT CLA_CODIGO,
	CLA_CLAVE,
	CLA_TIPO_USU,
	CLA_ESTADO,
	CLA_CODIGO,
	CLA_CODIGO
	FROM GECLAVES
	WHERE CLA_CODIGO =".$_REQUEST['cod']."
	AND CLA_TIPO_USU =".$_REQUEST['tip'];

$rowdatos = $conexion->ejecutarSQL($configuracion,$accesoOracle,$datos,"busqueda");

if(is_array($rowdatos))
{
	echo "<script>location.replace('$redir?error_login=23')</script>";
}
else
{
	if($_REQUEST['cla'] != $_REQUEST['rcla'])
	{
		echo "<script>location.replace('$redir?error_login=12')</script>";
		exit;
	}
	else
	{
		$psw = sha1(md5($_REQUEST['cla']));
		$est = 'A';
		
		$ins="INSERT ";
		$ins.="INTO ";
		$ins.="geclaves ";
		$ins.="VALUES ";
		$ins.="( ";
		$ins.="'".$_REQUEST['cod']."', ";
		$ins.="'".$psw."', ";
		$ins.="'".$_REQUEST['tip']."', ";
		$ins.="'".$est."',";
		$ins.="'N'";
		$ins.=")";
		$registro = $conexion->ejecutarSQL($configuracion,$accesoOracle,$ins,"busqueda");
		
		if(isset($registro))
		{
			echo "<script>location.replace('$redir?error_login=21')</script>";
		}
	}
}

?>
