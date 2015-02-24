<?PHP
require_once('dir_relativo.cfg');
require_once(dir_script.'fu_cabezote.php');
require_once('msql_ano_per.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(33);

fu_cabezote("ACCESOS DIARIOS AL M&Oacute;DULO INSCRIPCI&Oacute;N DE ASPIRANTES");

require_once('msql_uso_diario.php');

$RowUsoDia = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryUsoDia,"busqueda");
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
<P></P>
<div align="center" class="Estilo5">Inscripci&oacute;n de Aspirantes para el Per&iacute;odo Acad&eacute;mico <? print $ano.'-'.$per; ?><br><br>
Accesos Diarios al Sistema de Informaci&oacute;n<br> M&oacute;dulo Inscripci&oacute;n de Aspirantes</div><br>
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="20%" align="left">

<table width="25%" border="1" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse"<? print $EstiloTab; ?>>
	<tr class="tr">
		<td align="center">D&iacute;a</td>
		<td align="center">Fecha</td>
		<td align="center">Accesos</td>
	</tr>
		<?php
		$i = 0;
		$tot = 0;
		while(isset($RowUsoDia[$i][0]))
		{
			print'<tr><td align="right" class="Estilo12">'.$i.'</td>
			<td class="Estilo3">'.$RowUsoDia[$i][2].'</td>
			<td align="right" class="Estilo3">'.$RowUsoDia[$i][3].'</td></tr>';
			$tot=$tot+$RowUsoDia[$i][3];
		$i++;
		}
		?>
	<tr>
		<td align="right" colspan="2"><b>Total:</b></td>
		<td align="right"><b><? print $tot; ?></b></td>
	</tr>
	<tr>
		<td align="right" colspan="2">Promedio:</td>
		<td align="right"><? print number_format($tot/$i,2); ?></td>
	</tr>
</table>

</td>

    <td><? require_once('reg_uso_diario_contador.php');?></td>
  </tr>
</table>
<P></P>
</body>
</html>