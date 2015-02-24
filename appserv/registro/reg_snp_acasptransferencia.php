<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(33);

fu_cabezote("CONSULTA Y MODIFICACI&Oacute;N DE SNP");
require_once('msql_snp_acasptransferencia.php');
$RowSNPT = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QrySNPT,"busqueda");
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body>
<p></p>
<?
if(isset($_REQUEST['cred']) && isset($_REQUEST['iden']))
{
	require_once('reg_actualiza_snp_acasptransferencia.php');
}

print'<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<caption>Relaci&oacute;n de SNP sin datos del ICFES - Transferencias</caption>
	<tr align="center" class="tr">
		<td>#</td>
		<td>Credencial</td>
		<td>Identificaci&oacute;n Icfes</td>
		<td>Correo Electr&oacute;nico</td>
		<td>Tel&eacute;fono</td>
		<td>SNP Icfes</td>
		<td>Gesti&oacute;n</td>
	</tr>';
  
$b_edit = '<img src="../img/b_edit.png" border="0" title="Editar registro">';
$i=0;
while(isset($RowSNPT[$i][0]))
{
  print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
  <td align="right">'.$i.'</td>
  <td align="right">'.$RowSNPT[$i][0].'</td>
  <td align="right">'.$RowSNPT[$i][1].'&nbsp;</td>
  <td align="left">'.$RowSNPT[$i][2].'</td>
  <td align="left">'.$RowSNPT[$i][3].'</td>
  <td align="left">'.$RowSNPT[$i][4].'</td>
  <td align="center"><a href="reg_snp_acasptransferencia.php?cred='.$RowSNPT[$i][0].'&iden='.$RowSNPT[$i][1].'">'.$b_edit.'</a></td>
  </tr>';
  $i++;
}
print'</table>
<p align="center">
<input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:80" title="Clic par imprimir el reporte">
</p>';
?>
</body>
</html>