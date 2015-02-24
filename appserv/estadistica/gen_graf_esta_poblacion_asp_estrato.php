<br>
<table width="60%" border="1" align="center" cellpadding="2" cellspacing="0" style="border-collapse:collapse">
<tr><td colspan="12" align="center" class="Estilo10">Total de aspirantes por estrato</td></tr>
<tr>
<?php
$TotAspG = "SELECT COUNT(asp_cred)
	FROM acasp
	WHERE asp_ape_ano = $Anio
	AND asp_ape_per = $Peri";
	
$RowAspG=$conexion->ejecutarSQL($configuracion,$accesoOracle,$TotAspG,"busqueda");

$totasp = $RowAspG[0][0];

$PoblacionAspG = "SELECT DECODE(trim(asp_estrato),99,'Sin',NULL, 'Nulos',asp_estrato), COUNT(asp_estrato)
		FROM acasp
		WHERE asp_ape_ano = $Anio
		AND asp_ape_per = $Peri
		GROUP BY asp_estrato
		ORDER BY asp_estrato";

$RowAspG = $conexion->ejecutarSQL($configuracion,$accesoOracle,$PoblacionAspG,"busqueda");

if(!is_array($RowAspG))
{
	die('<div align="center"><font color="#FF0000">--// Sin Datos //--</font></div>');
}
$width=35;
$img = array(0=>'green.png',
			 1=>'silver.png',
			 2=>'blue.png',
			 3=>'gold.png',
			 4=>'red.png',
			 5=>'gray.png');
$i=0;
while(isset($RowAspG[$i][0]))
{
	$rand = rand(0, 5);
	$height = sprintf("%1.2f",($RowAspG[$i][1]/$totasp)*100);
	
	print'<td align="center" valign="bottom"><br>'.$RowAspG[$i][1].'<br>
	<img src="../img/'.$img[$rand].'" height="'.$height.'" width="'.$width.'"><br>
	<span class="Estilo12">'.$RowAspG[$i][0].'</span><br>
	<img src="../img/gris.png" width="'.$width.'" height="1"><br>
	<img src="../img/linh.png" height="1" width="'.$width.'"><br>
	<span class="Estilo13">'.number_format(sprintf("%.2f",($RowAspG[$i][1]/$totasp)*100),1).'%</span></td>';
$i++;
}
?>
</tr>
<tr><td colspan="<? print $i?>" align="center" class="Estilo10">Porcentajes por estrato</td></tr>
</table>
<br>