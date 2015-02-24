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

if($_REQUEST['Ac']==""){
   echo "<script>location.replace('$redir?error_login=30')</script>";
   exit;
}

$del="DELETE ";
$del.="acdocplantrabajo ";
$del.="WHERE ";
$del.="DPT_APE_ANO = $ano ";
$del.="AND ";
$del.="DPT_APE_PER = $per ";
$del.="AND ";
$del.="DPT_DOC_NRO_IDEN ='".$_SESSION['usuario_login']."' "; 
$del.="AND ";
$del.="DPT_DAC_COD ='".$_GET['Ac']."' ";
$del.="AND ";
$del.="DPT_HORA ='".$_GET['Hr']."' "; 
$del.="AND ";
$del.="DPT_ESTADO = 'A'";

$resultado=$conexion->ejecutarSQL($configuracion,$accesoOracle,$del,"busqueda");

if(isset($resultado)){
echo "<script>location.replace('$redir?error_login=22')</script>";
}
?>