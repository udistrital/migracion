<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require(dir_conect.'conexion.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

fu_tipo_user(51);

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY>

<?php

fu_cabezote("NOTAS DEFINITIVAS CURSOS DE VACACIONES");

$estcod = $_SESSION['usuario_login'];
$print = "javascript:popUpWindow('print_est_notas_curvac.php?estcod=$estcod', 'yes', 0, 0, 850, 450)";

require_once(dir_script.'msql_notas_curvac.php');
//require(dir_conect.'conexion.php');
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
if(!is_array($consulta))
{ 
	die('<h3>No tiene asignaci&oacute;n acad&eacute;mica en cursos de vacaciones.</h3>'); exit;
}
//{ header("Location: ../err/err_sin_asi_curvac.php"); exit; }

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
</table>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');
echo'<input type="submit" value="Imprimir Notas Parciales" onClick="'.$print.'">'; ?>
</div><br><br>
<?php 
require(dir_conect.'conexion.php');
$cod_consulobs = "SELECT NOB_COD, NOB_NOMBRE FROM ACNOTOBS WHERE NOB_COD IN(0,1,3,19,20) ORDER BY NOB_COD";
$consultaobs=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consulobs,"busqueda");
 
echo'<div align="right"><table border="1" width="250" cellspacing="0" cellpadding="1"><tr>
	<td align="center" colspan="2" bgcolor="#FFFF99" width="250"><font color="#0000FF"><b>OBSERVACIONES DE NOTAS</b></font> (<font size="2"><b>Obs</b></font>)</td>    ';
$i=0;
while(isset($consultaobs[$i][0]))
{
     echo'<tr><td width="3%" align="right">'.$consultaobs[$i][0].'</td>
     <td width="20%">'.$consultaobs[$i][1].'</td></tr>';
$i++;
}
echo'</table></div>';
fu_pie(); 
ob_end_flush();
?>
</BODY>
</HTML>