<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require(dir_conect.'conexion.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(51);
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
require(dir_conect.'conexion.php');
$consulta = OCIParse($oci_conecta,$cod_consul);
OCIExecute($consulta, OCI_DEFAULT);
$row = OCIFetch($consulta);
if($row != 1) { die('<h3>No tiene asignación académica en cursos de vacaciones.</h3>'); exit; }
//{ header("Location: ../err/err_sin_asi_curvac.php"); exit; }

//---------------------------
echo'<br><div align="center">
  <table border="0" cellspacing="0" width="780">
    <tr>
      <td width="121" align="right">'.OCIResult($consulta, 1).'</td>
      <td width="407"><strong>'.OCIResult($consulta, 2).'</strong></td>
      <td width="126" align="right">Identificación: </td>
      <td width="108">'.OCIResult($consulta, 3).'</td>
    </tr>
    <tr>
      <td width="121" align="right">'.OCIResult($consulta, 4).'</td>
      <td width="407"><strong>'.OCIResult($consulta, 5).'</strong></td>
      <td width="126" align="right">Promedio: </td>
      <td width="108">'.OCIResult($consulta, 6).'</td>
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
      <td width="93" align="center">Código</td>
      <td width="462" align="center">Asignatura</td>
      <td width="50" align="center">Gr</td>
      <td width="50" align="center">Nota</td>
      <td width="50" align="center">Obs</td>
    </tr>
<?php
do{
   echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
   <td width="93" align="right">'.OCIResult($consulta, 7).'</td>
      <td width="462" align="left">'.OCIResult($consulta, 8).'</td>
      <td width="50" align="center">'.OCIResult($consulta, 9).'</td>
      <td width="50" align="right">'.OCIResult($consulta, 10).'</td>
      <td width="50" align="right">'.OCIResult($consulta, 11).'</td></tr>';  
  }while(OCIFetch($consulta));
  cierra_bd($consulta,$oci_conecta);
?>
</table>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');
echo'<input type="submit" value="Imprimir Notas Parciales" onClick="'.$print.'">'; ?>
</div><br><br>
<?php 
require(dir_conect.'conexion.php');
$cod_consul = "SELECT NOB_COD, NOB_NOMBRE FROM ACNOTOBS WHERE NOB_COD IN(0,1,3,19,20) ORDER BY NOB_COD";
  $consulta = OCIParse($oci_conecta, $cod_consul);
  OCIExecute($consulta);
  
echo'<div align="right"><table border="1" width="250" cellspacing="0" cellpadding="1"><tr>
	<td align="center" colspan="2" bgcolor="#FFFF99" width="250"><font color="#0000FF"><b>OBSERVACIONES DE NOTAS</b></font> (<font size="2"><b>Obs</b></font>)</td>    ';
  do{
     echo'<tr><td width="3%" align="right">'.OCIResult($consulta, 1).'</td>
     <td width="20%">'.OCIResult($consulta, 2).'</td></tr>';
  }while(OCIFetch($consulta));
  cierra_bd($consulta,$oci_conecta);
echo'</table></div>';
fu_pie(); 
ob_end_flush();
?>
</BODY>
</HTML>