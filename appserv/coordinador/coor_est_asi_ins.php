<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
if(isset($_REQUEST['tipo'])==110){
    fu_tipo_user(110);
    $tipo=110; 
}elseif(isset($_REQUEST['tipo'])==114){
    fu_tipo_user(114);
    $tipo=114; 
}else{
    fu_tipo_user(4);
    $tipo=4; 
}
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script> 
</HEAD>
<BODY>

<?php
if(isset($_REQUEST['estcod']))
{
	$_SESSION['ccfun'] = $_REQUEST['estcod'];
}
$_REQUEST['estcod'] = $_SESSION['ccfun'];

$estados = "'A','B','C','J','M','P','R','S','T','V','Z'";
require_once('msql_est_asi_ins.php');
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

echo'<table border="0" width="90%" align="center" cellpadding="0">
	<caption>ASIGNATURAS INSCRITAS</caption>
	  <tr>
		<td width="80" align="right">'.$consulta[0][0].'</td>
		<td width="266"><strong>'.htmlentities($consulta[0][1]).'</strong></td>
		<td width="170" align="right">Identificaci&oacute;n:</td>
		<td width="102" align="left">'.$consulta[0][2].'</td>
	  </tr>
	  <tr>
		<td width="80" align="right">'.$consulta[0][3].'</td>
		<td width="266"><strong>'.$consulta[0][4].'</strong></td>
		<td width="170" align="right">Promedio:</td>
		<td width="102" align="left">'.$consulta[0][5].'</td>
	  </tr>
	  <tr>
		<td width="80" align="right">&nbsp;</td>
		<td width="266">
		<p align="right">&nbsp;</td>
		<td width="170" align="right"><strong>Per&iacute;odo Acad&eacute;mico:</strong></td>
		<td width="102" align="left">'.$ano.'-'.$per.'</td>
	  </tr></table><p></p>

  <table border="1" width="90%" align="center" cellspacing="0" cellpadding="2">
  <tr class="tr">
	<td align="center">C&oacute;digo</td>
	<td align="center">Asignatura</td>
	<td align="center">Gru</td>
	<td align="center">Docente</td>
  </tr>';
$i=0;
while(isset($consulta[$i][0]))
{
	 echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	 <td width="80" align="right">
	 <a href="coor_est_asi_ins.php?asicod='.$consulta[$i][6].'&asigr='.$consulta[$i][12].'" target="_self" onMouseOver="link();return true;" onClick="link();return true;" TITLE="Horario de la asignatura">'.$consulta[$i][6].'</a></td>
	 <td width="266" align="left">'.htmlentities($consulta[$i][7]).'</td> 
	 <td width="22" align="center">'.$consulta[$i][8].'</td>
	 <td width="246" align="left">'.$consulta[$i][10].'</td></tr>';
$i++;
}
print'</table>';

if(isset($_REQUEST['asicod'])){
   $asicod = $_REQUEST['asicod'];
   require_once(dir_script.'NombreAsignatura.php');
   $rowAsignatura = $conexion->ejecutarSQL($configuracion,$accesoOracle,$NombreAsignatura,"busqueda");
	
   require_once(dir_script.'msql_est_asi_hor.php');
   $consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
	
   print'<p></p><table border="1" width="90%" align="center" cellspacing="0" cellpadding="0">
   <caption>'.htmlentities($Asignatura).'</caption>
   <tr class="tr">
   <td align="center">D&iacute;a</td>
   <td align="center">Hora</td>
   <td align="center">Sal&oacute;n</td>
   <td align="center">Sede</td></tr>';
	$i=0;
	while(isset($consulta[$i][0]))
	{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td align="center">'.$consulta[$i][0].'</td>
	<td align="center">'.$consulta[$i][1].'-'.$consulta[$i][2].'</td>
	<td align="center">'.htmlentities($consulta[$i][3]).'</td>
	<td align="center">'.$consulta[$i][4].'</td></tr>'; 
	$i++;
	}
}
print'</table>';
?>
</BODY>
</HTML>