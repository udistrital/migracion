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
fu_print_cabezote("REGISTRO DE ASIGNATURAS CURSOS DE VACACIONES");
require_once(dir_script.'msql_est_asi_ins_curvac.php');
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

echo'<div align="center"><table border="0" width="647" cellpadding="0">
     <tr>
        <td width="80" align="right">'.$consulta[0][0].'</td>
        <td width="266"><strong>'.$consulta[0][1].'</strong></td>
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
        <td width="170" align="right"><strong>Cursos de Vacaciones</strong></td>
        <td width="102" align="left"></td>
      </tr></table></div>';
?>
  <div align="center">
  <table border="1" width="620" cellspacing="0" cellpadding="2">
  <caption>&nbsp;</caption>
  <tr>
    <td width="53" align="center"><font face="Tahoma" size="2"><b>C&oacute;digo</b></font></td>
	<td width="320" align="center"><font face="Tahoma" size="2"><b>Asignatura</b></font></td>
    <td width="22" align="center"><font face="Tahoma" size="2"><b>Gru</b></font></td>
	<td width="246" align="center"><font face="Tahoma" size="2"><b>Docente</b></font></td>
  </tr>
<?php
  $i=0;
    while(isset($consulta[$i][0]))
    {
     echo'<tr><td width="80" align="right">
	      <a href="est_asi_hor.php?asicod='.$consulta[$i][6].'&asigr='.$consulta[$i][8].'" target="asihor" onMouseOver="link();return true;" onClick="link();return true;" title="Ver horario de la asignatura">'.$consulta[$i][6].'</a></td>
	 	  <td width="266" align="left">'.$consulta[$i][7].'</td> 
     	  <td width="22" align="center">'.$consulta[$i][8].'</td>
		  <td width="246" align="left">
		  <a href="../generales/frm_est_envia_email_doc.php?usu='.$consulta[$i][9].'" target="_self" onMouseOver="link();return true;" onClick="link();return true;" title="Enviar correo al docente">'.$consulta[$i][10].'</a></td>'; 
      $i++;
      }
?>
</table><br>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');
ob_end_flush();
?>
</div>
</BODY>
</HTML>