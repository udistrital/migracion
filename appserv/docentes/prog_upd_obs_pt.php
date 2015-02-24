<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");
require_once('valida_fecha_pt.php');

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(30);

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

$observacion = $_REQUEST['texobs'];

$QryUpd="UPDATE ";
$QryUpd.="ACDOCPLANTRABAJOBS ";
$QryUpd.="SET ";
$QryUpd.="DPO_OBS ='".$observacion."' ";
$QryUpd.="WHERE ";
$QryUpd.="DPO_APE_ANO =$ano ";
$QryUpd.="AND ";
$QryUpd.="DPO_APE_PER = $per ";
$QryUpd.="AND ";
$QryUpd.="DPO_DOC_NRO_IDEN = '".$_SESSION['usuario_login']."'";

$result=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryUpd,"busqueda");

if(isset($result))
{
	header("Location: $redir");
}

?>