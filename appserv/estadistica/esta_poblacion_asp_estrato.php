<?PHP
require_once('dir_relativo.cfg');
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
$PoblacionAsp = "SELECT 
(CASE WHEN asp_estrato= 99 THEN 'Sin' WHEN asp_estrato IS NULL THEN 'Nulos' ELSE asp_estrato::text END), COUNT(asp_estrato)
	FROM mntac.acasp
	WHERE asp_ape_ano = $Anio
	AND asp_ape_per = $Peri
	GROUP BY asp_estrato
	ORDER BY asp_estrato";
	

$RowAsp = $conexion->ejecutarSQL($configuracion,$accesoOracle,$PoblacionAsp,"busqueda");

print'<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
<tr class="tr"><td align="center">Estrato</td>
<td align="center">Poblaci&oacute;n</td></tr>';
$i=0;
while(isset($RowAsp[$i][0]))
{
   	print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td align="right" class="Estilo12">'.$RowAsp[$i][0].'</td>
		<td align="right">'.$RowAsp[$i][1].'</td>
	</tr>';
   $cont = $cont + $RowAsp[$i][1];
$i++;
}
print'<tr><td align="right"><b>Total:</b></td>
<td align="right"><b>'.$cont.'</b></td></tr></table>';
?>
</BODY>
</HTML>