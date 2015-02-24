<?PHP
require_once('dir_relativo.cfg');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");

fu_cabezote("ACCESOS AL SISTEMA DE INFORMACI&Oacute;N C&Oacute;NDOR");

require_once('msql_uso_condor.php');
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
<div align="center" class="Estilo5">Accesos mensuales al sistema de informaci&oacute;n<br> C&oacute;ndor durante el a&ntilde;o <? print $ano;?></div><br>
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
while(isset($rowsUsoC[$i][0]))
{
	$rand = rand(0, 3);
	$height = (sprintf("%1.2f",($rowsUsoC[0][2])/$TotAno)*100);
	print'<td align="center" valign="bottom">'.$rowsUsoC[0][2].'<br>'.
	'<img src="../img/'.$img[$rand].'" height="'.$height.'" width="'.$width.'">'.'<br>
	'.ucfirst(strtolower($rowsUsoC[0][1])).'<br>
	<img src="../img/gris.png" width="'.$width.'" height="1"><br>
	<span class="Estilo13">'.number_format(sprintf("%1.2f",($rowsUsoC[0][2]/$TotAno)*100),1).'%</span></td>';
$i++;
}
?>
</tr>
<tr><td colspan="<? print $i;?>" align="center" class="Estilo10">Porcentaje de acceso mensual.</td></tr>
</table><br>
<div align="center" class="Estilo5">Total de accesos durante el a&ntilde;o: <? print number_format($TotAno);?></div>
<div align="center" class="Estilo5">Accesos total a C&oacute;ndor: <? print number_format($TotCon);?></div>
<P>&nbsp;</P>
</body>
</html>