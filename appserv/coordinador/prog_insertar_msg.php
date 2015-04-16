<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


if(!isset($_REQUEST['tipo'])){
    $_REQUEST['tipo']=$_SESSION['usuario_nivel'];
}

if($_REQUEST['tipo']==110){
    fu_tipo_user(110);
    $tipo=110; 
}elseif($_REQUEST['tipo']==114){
    fu_tipo_user(114);
    $tipo=114; 
}elseif($_REQUEST['tipo']==4){
    fu_tipo_user(4);
    $tipo=4; 
}

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

if($_REQUEST['autor']=="" || $_REQUEST['cracod']=="" || $_REQUEST['para']=="" || $_REQUEST['fecini']=="" || $_REQUEST['fecfin']=="" || $_REQUEST['titulo']=="" || $_REQUEST['contenido']=="")
{
   echo "<script>location.replace('$redir?error_login=17')</script>";
   exit;
}
else
{
	$hora = date("g:i:s a");

	$qry_secuencia = "SELECT NVL(MAX(CME_CODIGO),0)+1 FROM accoormensaje WHERE CME_CRA_COD =".$_REQUEST['cracod'];
	 
	$row_sec = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_secuencia,"busqueda");
	$secuencia = $row_sec[0][0];

	$ins="INSERT ";
	$ins.="INTO ";
	$ins.="accoormensaje ";
	$ins.="VALUES ";
	$ins.="(";
	$ins.="$secuencia, ";
	$ins.="'".$_REQUEST['cracod']."', ";
	$ins.="'".$_REQUEST['para']."', ";
	$ins.="'".$_REQUEST['autor']."', ";
	$ins.="'".$_REQUEST['titulo']."', ";
	$ins.="TO_DATE('".$_REQUEST['fecini']."','dd/mm/YYYY'), ";
	$ins.="'".$hora."', ";
	$ins.="TO_DATE('".$_REQUEST['fecfin']."','dd/mm/YYYY'), ";
	$ins.="'".$_REQUEST['contenido']."'";
	$ins.=")";
	
	$row_ins = $conexion->ejecutarSQL($configuracion,$accesoOracle,$ins,"busqueda");
	
	if(isset($row_ins))
	{
		echo "<script>location.replace('coor_admin_msg.php?error_login=21')</script>";
	}
}
?>