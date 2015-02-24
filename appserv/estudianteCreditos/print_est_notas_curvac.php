<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(51);
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
$consulta = OCIParse($oci_conecta,$cod_consul);
OCIExecute($consulta, OCI_DEFAULT);
$row = OCIFetch($consulta);

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
      <td width="121">&nbsp;</td>
      <td width="407">&nbsp;</td>
      <td width="126" align="right"><b></b></td>
      <td width="108"></td>
    </tr>
  </table>
</div>';
?>
<div align="center">
  <table border="1" cellpadding="0" cellspacing="0" width="700">
    <tr>
      <td width="93" align="center"><b>Código</b></td>
      <td width="462" align="center"><b>Asignatura</b></td>
      <td width="50" align="center"><b>Gr</b></td>
      <td width="50" align="center"><b>Nota</b></td>
      <td width="50" align="center"><b>Obs</b></td>
    </tr>
<?php
  do{
     echo'<tr><td width="93" align="right">'.OCIResult($consulta, 7).'</td>
      <td width="462" align="left">'.OCIResult($consulta, 8).'</td>
      <td width="50" align="center">'.OCIResult($consulta, 9).'</td>
      <td width="50" align="right">'.OCIResult($consulta, 10).'</td>
      <td width="50" align="right">'.OCIResult($consulta, 11).'</td></tr>'; 
  }while(OCIFetch($consulta));
  cierra_bd($consulta,$oci_conecta);
?>
</table></div><br><br>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');
ob_end_flush();
?>
</BODY>
</HTML>