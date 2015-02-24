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
fu_print_cabezote("REGISTRO DE ASIGNATURAS CURSOS DE VACACIONES");
require_once(dir_script.'msql_est_asi_ins_curvac.php');
$consulta = OCIParse($oci_conecta,$cod_consul);
OCIExecute($consulta);
$row = OCIFetch($consulta);

echo'<div align="center"><table border="0" width="647" cellpadding="0">
      <tr>
        <td width="80" align="right">'.OCIResult($consulta, 1).'</td>
        <td width="266"><font face="Tahoma" size="2"><strong>'.OCIResult($consulta, 2).'</strong></font></td>
        <td width="170" align="right">Identificación:</td>
        <td width="102" align="left">'.OCIResult($consulta, 3).'</td>
      </tr>
      <tr>
        <td width="80" align="right">'.OCIResult($consulta, 4).'</td>
        <td width="266"><font face="Tahoma" size="2"><strong>'.OCIResult($consulta, 5).'</strong></font></td>
        <td width="170" align="right">Promedio:</td>
        <td width="102" align="left">'.OCIResult($consulta, 6).'</td>
      </tr>
      <tr>
        <td width="80" align="right">&nbsp;</td>
        <td width="266">
        <p align="right">&nbsp;</td>
        <td width="170" align="right"></td>
        <td width="102" align="left"></td>
      </tr></table></div>';
?>
  <div align="center">
  <table border="1" width="620" cellspacing="0" cellpadding="2">
  <caption>&nbsp;</caption>
  <tr>
    <td width="53" align="center"><font face="Tahoma" size="2"><b>Código</b></font></td>
	<td width="320" align="center"><font face="Tahoma" size="2"><b>Asignatura</b></font></td>
    <td width="22" align="center"><font face="Tahoma" size="2"><b>Gru</b></font></td>
	<td width="246" align="center"><font face="Tahoma" size="2"><b>Docente</b></font></td>
  </tr>
<?php
  do{
     echo'<tr><td width="53" align="right"><font face="Tahoma" size="2">'.OCIResult($consulta, 7).'</font></td>
	 	  <td width="320" align="left"><font face="Tahoma" size="2">'.OCIResult($consulta, 8).'</font></td> 
     	  <td width="22" align="center"><font face="Tahoma" size="2">'.OCIResult($consulta, 9).'</font></td>
		  <td width="246" align="left"><font face="Tahoma" size="2">'.OCIResult($consulta, 11).'</font></td>'; 
  }while(OCIFetch($consulta));
  cierra_bd($consulta,$oci_conecta);
?>
</table><br>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');
ob_end_flush();
?>
</div>
</BODY>
</HTML>