<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_script.'class_nombres.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);

$nom = new Nombres;
$docente = $nom->rescataNombre($_SESSION['usuario_login'],'NombreDocente');
$_SESSION['carrera'] = $_REQUEST['cra'];
?>
<HTML>
<HEAD>
<TITLE>Carga Lectiva Docente</TITLE>
<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY style="margin:0;">

<?php
fu_print_cabezote("CARGA LECTIVA");
require_once('msql_doc_carga.php');
$ExeCarga=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCarga,"busqueda");

if(!is_array($ExeCarga))
{
	die('<center><h3>Su carga lectiva presenta cruce, o aun no ha sido digitada.</h3></center>');
	//exit;
}

print'<table width="100%" border="1" align="center" cellpadding="1" cellspacing="0" style="font-family:Tahoma;font-size:11px; border-collapse:collapse">
  <tr><td colspan="8" align="center">'.$docente.' /  Periodo Acad&eacute;mico: '.$ano.'-'.$per.'</td></tr>
  <tr>
    <td align="center"><b>HORA</b></td>
    <td align="center"><b>LUNES</b></td>
    <td align="center"><b>MARTES</b></td>
    <td align="center"><b>MI&Eacute;RCOLES</b></td>
    <td align="center"><b>JUEVES</b></td>
    <td align="center"><b>VIERNES</b></td>
    <td align="center"><b>S&Aacute;BADO</b></td>
    <td align="center"><b>DOMINGO</b></td>
  </tr>';
$i=0;
while(isset($ExeCarga[$i][0]))
{
	print'<tr>
	<td align="right"><b>'.$ExeCarga[$i][1].'</b></td>
	<td align="center">'.$ExeCarga[$i][2].'</td>
	<td align="center">'.$ExeCarga[$i][3].'</td>
	<td align="center">'.$ExeCarga[$i][4].'</td>
	<td align="center">'.$ExeCarga[$i][5].'</td>
	<td align="center">'.$ExeCarga[$i][6].'</td>
	<td align="center">'.$ExeCarga[$i][7].'</td>
	<td align="center">'.$ExeCarga[$i][8].'</td></tr>';
$i++;
}
?>
</table>
<table width="100%" border="0" align="center"><tr><td align="right" style="font-family:Tahoma;font-size:10px">Lease: C&oacute;digo de Asignatura - Grupo / Sal&oacute;n <br/> Sede / Edificio</td></tr></table>
<p></p>
</BODY>
</HTML>