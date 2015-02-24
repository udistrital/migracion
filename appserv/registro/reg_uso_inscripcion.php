<?PHP
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

fu_cabezote("ACCESOS AL M&Oacute;DULO INSCRIPCI&Oacute;N DE ASPIRANTES");

require_once('msql_uso_inscripcion.php');
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
<div align="center" class="Estilo5">Accesos Mensuales al Sistema de Informaci&oacute;n<br> M&oacute;dulo Inscripci&oacute;n de Aspirantes a&ntilde;o <? print $RowsUsoc[0][3]?></div><br>
<table width="25%" height="40%" border="1" align="center" cellpadding="2" cellspacing="0" style="border-collapse:collapse" <? print $EstiloTab; ?>>
<tr><td colspan="12" align="center" class="Estilo10">Accesos Mensuales</td></tr>
<tr>
<?php

$width=40;
$img = array(0=>'green.png',
			 1=>'blue.png',
			 2=>'gray.png',
			 3=>'red.png');
$i=0;
while(isset($RowsUsoc[$i][0]))
{
	$rand = rand(0, 3);
	$height = (sprintf("%1.2f",($RowsUsoc[$i][2]/$TotAno)*100));
	print'<td align="center" valign="bottom">'.$RowsUsoc[$i][2].'<br>'.
	'<img src="../img/'.$img[$rand].'" height="'.$height.'" width="'.$width.'">'.'<br>
	'.ucfirst(strtolower($RowsUsoc[$i][1])).'<br>
	<img src="../img/gris.png" width="'.$width.'" height="1"><br>
	<span class="Estilo13">'.number_format(sprintf("%1.2f",($RowsUsoc[$i][2]/$TotAno)*100),1).'%</span></td>';
$i++;
}
?>
</tr>
<tr><td colspan="<? print $i;?>" align="center" class="Estilo10">Porcentaje de acceso mensual.</td></tr>
</table><br>
<div align="center" class="Estilo5">Total de accesos durante el a&ntilde;o: <? print number_format($TotAno);?></div>
<div align="center" class="Estilo5">Accesos a Inscripci&oacute;n de Aspirantes: <? print number_format($TotCon);?></div>
<P>&nbsp;</P>
<?php
?>
</body>
</html>