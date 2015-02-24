<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

fu_tipo_user(51);

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<HTML>
<HEAD>
<TITLE>Estudiante</TITLE>
<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY topmargin="0" leftmargin="0" background="../img/dnvpt.gif">

<?php
ob_start();
fu_print_cabezote("NOTAS DEFINITIVAS CURSOS DE VACACIONES");

$estcod = $_SESSION['usuario_login'];
$carrera = $_SESSION['carrera'];

require_once(dir_script.'msql_notas_curvac.php');
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

//---------------------------
echo'<br><div align="center">
  <table border="0" cellspacing="0" width="780">
    <tr>
      <td width="121" align="right">'.$consulta[0][0].'</td>
      <td width="407"><strong>'.$consulta[0][1].'</strong></td>
      <td width="126" align="right">Identificaci&oacute;n: </td>
      <td width="108">'.$consulta[0][2].'</td>
    </tr>
    <tr>
      <td width="121" align="right">'.$consulta[0][3].'</td>
      <td width="407"><strong>'.$consulta[0][4].'</strong></td>
      <td width="126" align="right">Promedio: </td>
      <td width="108">'.$consulta[0][5].'</td>
    </tr>
    <tr>
      <td width="762" colspan="4">
      <p align="center"><b>Cursos de Vacaciones</b></td>
    </tr>
  </table>
</div>';
?>
<div align="center">
  <table border="1" cellpadding="0" cellspacing="0" width="700">
    <tr class="tr">
      <td width="93" align="center">C&oacute;digo</td>
      <td width="462" align="center">Asignatura</td>
      <td width="50" align="center">Gr</td>
      <td width="50" align="center">Nota</td>
      <td width="50" align="center">Obs</td>
    </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	  echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	  <td width="93" align="right">'.$consulta[$i][6].'</td>
	  <td width="462" align="left">'.$consulta[$i][7].'</td>
	  <td width="50" align="center">'.$consulta[$i][8].'</td>
	  <td width="50" align="right">'.$consulta[$i][9].'</td>
	  <td width="50" align="right">'.$consulta[$i][10].'</td></tr>';
$i++;
}
?>
</table></div><br><br>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');
ob_end_flush();
?>
</BODY>
</HTML>