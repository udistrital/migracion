<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('valida_http_referer.php');
require_once('valida_cancelacion.php');
require_once('valida_estudiante_activo.php');
require_once('valida_estudiante_nuevo.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
fu_tipo_user(51);
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY topmargin="2">

<?php


if($_GET['asicod'] == "") die("<center><font face='Tahoma' size='3' color='#FF0000'><b>No tiene asignaturas inscritas.</b></font></center>");

$estcod = $_SESSION['usuario_login'];
$estcra = $_SESSION['carrera'];
$asicod = $_GET['asicod'];
$asigru = $_GET['asigru'];

require_once(dir_script.'NombreAsignatura.php');

echo'<table border="0" width="500" cellspacing="0" cellpadding="2"  align="center">
	 <tr class="tr"><td width="500" colspan="2" align="center"><span class="Estilo1">CANCELAR ASIGNATURA</span></td></tr>
     <tr><td width="282" align="right"><span class="Estilo2">Período Académico:</span></td>
     <td width="218" align="left"><span class="Estilo2">'.$ano.'-'.$per.'</span></td></tr></table>';

echo'<table border="0" cellpadding="2" width="60%" align="center">
    <tr>
      <td colspan="2" align="center"><span class="Estilo10">¿Esta seguro(a) de cancelar la asignatura:</span></td>
    </tr>
	<tr>
      <td colspan="2" align="center" class="Estilo5">'.$Asignatura.' ?</td>
    </tr>
    <tr><td width="50%" align="center">
        <form name="form1" action="prg_borra_asi.php" target="inferior" method="post">
		<input type="submit" value="SI" title="Se cancela la asignatura" style="width:50; cursor:pointer">
		<input name="estcod" type="hidden" value="'.$estcod.'">
        <input name="asicod" type="hidden" value="'.$_GET['asicod'].'">
		<input name="asigru" type="hidden" value="'.$_GET['asigru'].'">
		</form>
    </td>
	<td width="50%" align="center">
    <form name="form1" action="est_fre_inscripcion.php" target="principal" method="post">
	<input type="submit" value="NO" title="No cancela la asignatura" style="width:50; cursor:pointer"></form></td>
	</tr>
</table><br>';

?>
</BODY>
</HTML>
