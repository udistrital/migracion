<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

//LLAMADO DE: adm_cambiar_clave.php
fu_tipo_user(20); 

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

$QryEstE = "SELECT cla_codigo
	FROM geclaves G
	WHERE cla_tipo_usu = 51
	AND cla_estado = 'A'
	AND EXISTS (SELECT est_cod
	FROM acest
	WHERE G.cla_codigo = est_cod
	AND est_estado_est = 'E')";

$RowEstE = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryEstE,"busqueda");
if(!is_array($RowEstE))
{
	echo "<script>location.replace('$redir?error_login=25')</script>";	
}
$estado = 'I';
$i=0;
while(isset($RowEstE[$i][0]))
{
	$codigo = $RowEstE[$i][0];
	$qry="UPDATE ";
	$qry.="geclaves ";
	$qry.="SET ";
	$qry.="cla_estado ='".$estado."' ";
	$qry.="WHERE ";
	$qry.="cla_codigo ='".$codigo."' ";
	$qry.="AND ";
	$qry.="cla_tipo_usu=51 ";
	$qry.="AND ";
	$qry.="cla_estado ='A' ";
	$Rowqry = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry,"busqueda");
	$i++;
}
if(isset($Rowqry))
{
	echo "<script>location.replace('$redir?error_login=7')</script>";
}
?>