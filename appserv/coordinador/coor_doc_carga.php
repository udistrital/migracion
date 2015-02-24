<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(4);
?>
<HTML>
<HEAD><TITLE>Docentes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<script language="JavaScript" src="../script/BorraLink.js"></script>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<hr>
<?php
$cedula=$_REQUEST['cedula'];
$estado='A';
require_once(dir_script.'msql_cargadoc.php');

$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
?>
  <table align="center" border="1" width="90%" cellspacing="0" cellpadding="1">
  <caption><span class="Estilo5">ASIGNACI&Oacute;N ACAD&Eacute;MICA DE:</span> 
  <?
   print $consulta[0][1];
   ?>
  </caption>
  <tr class="tr">
	<td align="center">C&oacute;digo</td>
    <td align="center">Asignatura</td>
	<td align="center">Grupo</td>
	<td align="center">Inscritos</td>
	<td align="center">Carrera</td>
  </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	 <td align="right">
	 <a href="coor_doc_asi_hor.php?asicod='.$consulta[$i][8].'&asigr='.$consulta[$i][12].'&curso='.$consulta[$i][10].'" target="horasi" onMouseOver="link();return true;" onClick="link();return true;" title="Horario de la asignatura">'.$consulta[$i][8].'</a></td>
	 <td><font face="Tahoma" size="1">
	 <a href="coor_doc_lis_clase.php?as='.$consulta[$i][8].'&gr='.$consulta[$i][10].'&C='.$consulta[$i][4].'&CD='.$cedula.'&cur='.$consulta[$i][12].'" target="principal" onMouseOver="link();return true;" onClick="link();return true;" title="Lista de clase">'.$consulta[$i][9].'</a></td>
	 <td align="center">'.$consulta[$i][10].'</td>
	 <td align="center">'.$consulta[$i][11].'</td>
	 <td align="left">'.$consulta[$i][5].'</td></tr>';
$i++;
}
?>
</table>
</BODY>
</HTML>