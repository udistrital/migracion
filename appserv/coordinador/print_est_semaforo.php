<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4);
?>
<HTML>
<HEAD>
<TITLE>Estudiante</TITLE>
<script language="JavaScript" src="../script/clicder.js"></script>
<link href="../script/print_estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY topmargin="0" leftmargin="0">

<?php
ob_start();
fu_print_cabezote("- PLAN DE ESTUDIO -");

$estcod = $_REQUEST['estcod'];
$carrera = $_SESSION['carrera'];

$print = "javascript:popUpWindow('print_est_semaforo.php?estcod=$estcod', 'yes', 0, 0, 850, 650)";

require_once(dir_script.'msql_semaforo.php');
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

echo'<br><div align="center">
<table border="0" width="95%" cellspacing="0" cellpadding="2" style="font-family: Tahoma; font-size: 10 pt;">
     <tr><td align="right">'.$consulta[0][0].'</td>
     <td align="left"><strong>'.$consulta[0][1].'</strong></td>
     <td align="right">Documento de Identidad: </td>
     <td align="right">'.$consulta[0][2].'</td></tr><tr>
  
     <td align="right"></td>
     <td align="left"></td>
     <td align="right">Promedio:</td>
     <td align="right">'.$consulta[0][5].'</td></tr>
  
     <td align="right">'.$consulta[0][3].'</td>
     <td align="left">'.$consulta[0][4].'</td>
     <td align="right">Pensum:</td>
     <td align="right">'.$consulta[0][6].'</td></tr></table></div>';
?>
  <table border="1" width="95%" align="center" cellspacing="0" cellpadding="2" background="../img/dnvpt.gif">
  <tr>
    <td align="center">C&oacute;digo</td>
	<td align="center">Asignatura</td>
    <td align="center">Sem</td>
	<td align="center">Nota</td>
	<td align="center">Observaci&oacute;n</td>
  </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr><td align="right">'.$consulta[$i][7].'</td>
	<td>'.$consulta[$i][8].'</td>
	<td align="center">'.$consulta[$i][9].'</td>
	<td align="center">'.$consulta[$i][10].'</td>
	<td align="laft">'.$consulta[$i][11].'</td></tr>';
$i++;
}
?>
</table>
</BODY>
</HTML>