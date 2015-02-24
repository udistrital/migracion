<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<HTML>
<HEAD>
<TITLE>Estadisticas</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?php
$PoblacionAspSex = "SELECT DECODE(asp_sexo,NULL,'Sin',asp_sexo), COUNT(asp_sexo)
		FROM acasp
		WHERE asp_ape_ano = $Anio
		AND asp_ape_per = $Peri
		GROUP BY asp_sexo
		ORDER BY asp_sexo";

$RowAspSex = $conexion->ejecutarSQL($configuracion,$accesoOracle,$PoblacionAspSex,"busqueda");

print'<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
<tr class="tr"><td align="center">Sexo</td>
<td align="center">Poblaci&oacute;n</td></tr>';
$i=0;
while(isset($RowAspSex[$i][0]))
{
	print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td align="right" class="Estilo12">'.$RowAspSex[$i][0].'</td>
	<td align="right">'.$RowAspSex[$i][1].'</td></tr>';
	$cont = $cont + $RowAspSex[$i][1];
$i++;
}
print'<tr><td align="right"><b>Total:</b></td>
<td align="right"><b>'.$cont.'</b></td></tr></table>';
?>
</BODY>
</HTML>