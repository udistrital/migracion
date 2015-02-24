<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_script.'fu_pie_pag.php');
fu_tipo_user(51);
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script> 
</HEAD>
<BODY>

<?php
ob_start();
fu_cabezote("REGISTRO DE ASIGNATURAS CURSOS DE VACACIONES");

$estcod = $_SESSION['usuario_login'];
$carrera = $_SESSION['carrera'];
$nivel = $_SESSION["usuario_nivel"];

require_once(dir_script.'msql_est_asi_ins_curvac.php');
$consulta = OCIParse($oci_conecta,$cod_consul);
OCIExecute($consulta, OCI_DEFAULT);
$row = OCIFetch($consulta);
if($row != 1) { die('<h3>No tiene asignación académica en cursos de vacaciones.</h3>'); exit; }
//{ header("Location: ../err/err_sin_asi_curvac.php"); exit; }

echo'<br><br><div align="center"><table border="0" width="647" cellpadding="0">
      <tr>
        <td width="80" align="right">'.OCIResult($consulta, 1).'</td>
        <td width="266"><strong>'.OCIResult($consulta, 2).'</strong></td>
        <td width="170" align="right">Identificación:</td>
        <td width="102" align="left">'.OCIResult($consulta, 3).'</td>
      </tr>
      <tr>
        <td width="80" align="right">'.OCIResult($consulta, 4).'</td>
        <td width="266"><strong>'.OCIResult($consulta, 5).'</strong></td>
        <td width="170" align="right">Promedio:</td>
        <td width="102" align="left">'.OCIResult($consulta, 6).'</td>
      </tr>
      <tr>
        <td width="80" align="right">&nbsp;</td>
        <td width="266">
        <p align="right">&nbsp;</td>
        <td width="170" align="right"><strong>Cursos de Vacaciones</strong></td>
        <td width="102" align="left"></td>
      </tr></table></div>';
?>
  <div align="center">
  <table border="1" width="600" cellspacing="0" cellpadding="2">
  <tr class="tr">
    <td width="80" align="center">Código</td>
	<td width="266" align="center">Asignatura</td>
    <td width="22" align="center">Gru</td>
	<td width="246" align="center">Docente</td>
  </tr>
<?php
  do{
     echo'<tr><td width="80" align="right">
	      <a href="est_asi_hor.php?asicod='.OCIResult($consulta, 7).'&asigr='.OCIResult($consulta, 9).'" target="asihor" onMouseOver="link();return true;" onClick="link();return true;" title="Ver horario de la asignatura">'.OCIResult($consulta, 7).'</a></td>
	 	  <td width="266" align="left">'.OCIResult($consulta, 8).'</td> 
     	  <td width="22" align="center">'.OCIResult($consulta, 9).'</td>
		  <td width="246" align="left">
		  <a href="../generales/frm_est_envia_email_doc.php?usu='.OCIResult($consulta, 10).'" target="_self" onMouseOver="link();return true;" onClick="link();return true;" title="Enviar correo al docente">'.OCIResult($consulta, 11).'</a></td>'; 
  }while(OCIFetch($consulta));
  cierra_bd($consulta,$oci_conecta);
?>
</table><br>
<?PHP
$print = "javascript:popUpWindow('print_est_asiins_curvac.php?estcod=$estcod', 'yes', 0, 0, 790, 500)";
require_once(dir_script.'msg_doc_no_valido.php');
print'<input type="submit" value="Imprimir Registro de Asignaturas" onClick="'.$print.'"></td></table><br><br><br><br><br><br>';
fu_pie();
ob_end_flush();
?>
</div>
</BODY>
</HTML>