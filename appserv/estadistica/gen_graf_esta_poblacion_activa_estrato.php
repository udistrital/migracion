<br>
<table width="60%" border="1" align="center" cellpadding="2" cellspacing="0" style="border-collapse:collapse">
<tr><td colspan="12" align="center" class="Estilo10">Total de estudiantes activos por estrato</td></tr>
<tr>
<?php
$TotActivoG = "SELECT COUNT(eot_estrato_social)
		FROM acestotr x
		WHERE EXISTS(SELECT * FROM acest WHERE est_cod = eot_cod AND est_estado_est IN ('A','B','H','L'))";

$RowAspG = $conexion->ejecutarSQL($configuracion,$accesoOracle,$TotActivoG,"busqueda");

$totasp = $RowAspG[0][0];

$PoblacionActivoG = "SELECT 
				(CASE WHEN eot_estrato_social= 99 THEN 'Sin' WHEN eot_estrato_social IS NULL THEN 'Nulos' ELSE eot_estrato_social::text END),
				COUNT(eot_estrato_social)
		FROM acestotr x
		WHERE EXISTS(SELECT * FROM acest WHERE est_cod = eot_cod AND est_estado_est IN ('A','B','H','L'))
		GROUP BY eot_estrato_social
		ORDER BY eot_estrato_social";

$RowAspG = $conexion->ejecutarSQL($configuracion,$accesoOracle,$PoblacionActivoG,"busqueda");

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