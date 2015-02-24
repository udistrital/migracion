<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
$tipo=(isset($_SESSION['usuario_nivel'])?$_SESSION['usuario_nivel']:'');

if(!$_REQUEST['tipo']){
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

?>
<html>
<head>
<title>Administraci&oacute;n de Mensajes</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
fu_cabezote("ADMINISTRACI&Oacute;N DE NOTICIAS");
include_once(dir_script.'class_nombres.php');
$NomCra = new Nombres;

require_once('coor_lis_desp_carrera.php');

if($_REQUEST['cracod']){
	$_SESSION['carrera'] = $_REQUEST['cracod'];
	$b_home='<IMG SRC='.dir_img.'b_home.png alt="Administraci&oacute;n de Noticias" border="0">';
	$b_insrow ='<IMG SRC='.dir_img.'b_insrow.png alt="Publicaci&oacute;n de Noticias" border="0">';
	
	require_once('msql_consulta_msg.php');
	$row_msg = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_msg,"busqueda");
        
	echo'<div align="center"><h3>PROYECTO CURRICULAR: '. $NomCra->rescataNombre($_REQUEST['cracod']) .'</h3>
	<table width="550" border="1" cellpadding="0" cellspacing="0">
	<tr class="tr"><td width="550" align="center" colspan="2">Gesti&oacute;n de Noticias</td></tr><tr>
	<td width="275" align="center"><a href="coor_admin_msg.php">Administraci&oacute;n'.$b_home.'</a></td>
	<td width="270" align="center"><a href="coor_forma_msg.php">Publicaci&oacute;n'.$b_insrow.'</a></td></tr></table></div><br>
	<p></p>';
	//if($rows_msg != 1) die('<center><h3>No hay registros para esta consulta.</h3></center>');
	
	echo'<div align="center"><span class="Estilo5">LISTADO DE NOTICIAS</span>';
	$i=0;
	while(isset($row_msg[$i][0]))
	{
		echo'<table width="550" border="1" cellpadding="0" cellspacing="0">
		<tr><td width="234"><span class="Estilo12">'.$row_msg[$i][4].'</span></td>
		<td width="316" align="right"><span class="Estilo13">|&nbsp;'.$row_msg[$i][5].'&nbsp;|
		'.$row_msg[$i][6].'|&nbsp;</span><span class="Estilo10">'.$row_msg[$i][3].'</span></td></tr><tr> 
		<td colspan="2" width="550">'.$row_msg[$i][8].'</td></tr></table><br>';
	$i++;
	}
	echo'</div><br>';
}
?>
</body>
</html>