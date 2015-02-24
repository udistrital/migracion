<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);
?>
<HTML>
<HEAD><TITLE>Docentes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script> 
</HEAD>
<BODY topmargin="0" leftmargin="0">

<?php
fu_cabezote("CARGA ACAD&Eacute;MICA");
    
$cedula = $_SESSION['usuario_login'];
$estado = 'A';

require_once(dir_script.'msql_cargadoc.php');
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
$row = $consulta;

if(!is_array($consulta))
{
die('<h3>No hay registros para esta consulta.</h3>');
exit;
}

$_SESSION['carrera'] = $consulta[0][4];

$print = "javascript:popUpWindow('print_doc_carga.php?cra=".$consulta[0][4]."', 'yes', 0, 0, 850, 650)";

echo'<p>&nbsp;</p><table width="90%" border="0" align="center" cellspacing="1">
    <tr>
      <td align="left"><B>Nombre:</B></td>
      <td><B>'.$consulta[0][1].'</B></td>
      <td align="left"><B>Identificaci&oacute;n:</B></td>
      <td>'.$consulta[0][0].'</td>
    </tr>
    <tr>
      <td align="left"><B>Facultad:</B></td>
      <td>'.$consulta[0][3].'</td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left"><B>Vinculaci&oacute;n:</B></td>
      <td>'.$consulta[0][7].'</td>
      <td align="left"><b>Per&iacute;odo Acad&eacute;mico:</b></td>
      <td>'.$ano.'-'.$per.'</td>
    </tr>
  </table><p></p>';
?>
  <table border="0" width="90%" align="center" cellspacing="0" cellpadding="1">
  <tr class="tr">
	<td align="center">C&oacute;digo</td>
    <td align="center">Asignatura</td>
	<td align="center">Grupo</td>
	<td align="center">Ins.</td>
	<td align="center">Carrera</td>
  </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
 	<td align="left">
	<a href="doc_asi_hor.php?asicod='.$consulta[$i][8].'&asigr='.$consulta[$i][12].'" target="horasi" onMouseOver="link();return true;" onClick="link();return true;" title="Horario de la asignatura">'.$consulta[$i][8].'</a></td>
	<td >'.htmlentities($consulta[$i][9]).'</td>
	<td align="center">'.$consulta[$i][10].'</td>
	<td align="center">'.$consulta[$i][11].'</td>
	<td align="left">'.$consulta[$i][5].'</td></tr>';
$i++;
}
?>
</table>
<p align="center"><input type="submit" value="Imprimir Carga Lectiva" onClick="<? print $print; ?>" style="cursor:pointer"></p>
</div>
<input name="num_regs" type="hidden" value="">
</form>
</BODY>
</HTML>
