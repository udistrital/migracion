<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once('msql_ano_per.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(33);

fu_cabezote("INSCRIPCI&Oacute;N DE ASPIRANTES");

require_once('msql_relacion_consignaciones.php');
$RowVlrCon = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryVlrCon,"busqueda");
ob_start();
?>
<html>
<head>
<title>Pecuniarios</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
</head>
<body style="margin:0">
<P>&nbsp;</P><P>&nbsp;</P>
<div align="center" class="Estilo5">Inscripci&oacute;n de Aspirantes para el Per&iacute;odo Acad&eacute;mico <? print $ano.'-'.$per;?>
<br>Relaci&oacute;n de Consignaciones</div><br>
<table width="60%" border="1" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse"<? print $EstiloTab; ?>>
<tr class="tr">
<td>&nbsp;</td>
<td>Valor</td>
<td>Total</td>
<td>M&iacute;nimo Consignado </td>
<td>M&aacute;ximo Consignado </td>
<td>Total Recaudado </td>
</tr>
<?php

$i=0;
while(isset($RowVlrCon[$i][0]))
{ 
	print'<tr>
		<td align="left">'.$RowVlrCon[$i][0].'</td>
		<td align="right">'.number_format($RowVlrCon[$i][1]).'</td>
		<td align="right">'.number_format($RowVlrCon[$i][2]).'</td>
		<td align="right">'.number_format($RowVlrCon[$i][3]).'</td>
		<td align="right">'.number_format($RowVlrCon[$i][4]).'</td>
		<td align="right"><b>'.number_format($RowVlrCon[$i][5]).'</b></td>
	</tr>';
$i++;
}
?>

</table>
<P>&nbsp;</P>
<?php
?>
</body>
</html>