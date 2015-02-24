<br>
<table width="60%" border="1" align="center" cellpadding="2" cellspacing="0" style="border-collapse:collapse">
<tr><td colspan="12" align="center" class="Estilo10">Total de estudiantes activos por sexo</td></tr>
<tr>
<?php
$TotActivoSexG = "SELECT COUNT(eot_sexo) FROM acestotr x
		WHERE EXISTS(SELECT * FROM acest WHERE est_cod = eot_cod AND est_estado_est IN ('A','B','H','L'))";

$RowAspG = $conexion->ejecutarSQL($configuracion,$accesoOracle,$TotActivoSexG,"busqueda");

$TotActSex = $RowAspG[0][0];

$PoblacionActivoSexG = "SELECT DECODE(trim(eot_sexo), NULL, 'Sin', eot_sexo), COUNT(eot_sexo)
			FROM acestotr x
			WHERE EXISTS(SELECT * FROM acest WHERE est_cod = eot_cod AND est_estado_est IN ('A','B','H','L'))
			GROUP BY eot_sexo
			ORDER BY eot_sexo DESC";

$RowAspSexG = $conexion->ejecutarSQL($configuracion,$accesoOracle,$PoblacionActivoSexG,"busqueda");

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
while(isset($RowAspSexG[$i][0]))
{
	$rand = rand(0, 5);
	$height = sprintf("%1.2f",($RowAspSexG[$i][1]/$TotActSex)*100);
	
	print'<td align="center" valign="bottom"><br>'.$RowAspSexG[$i][1].'<br>
	<img src="../img/'.$img[$rand].'" height="'.$height.'" width="'.$width.'"><br>
	<span class="Estilo12">'.$RowAspSexG[$i][0].'</span><br>
	<img src="../img/gris.png" width="'.$width.'" height="1"><br>
	<img src="../img/linh.png" height="1" width="'.$width.'"><br>
	<span class="Estilo13">'.number_format(sprintf("%.2f",($RowAspSexG[$i][1]/$TotActSex)*100),1).'%</span></td>';
$i++;
}
?>
</tr>
<tr><td colspan="<? print $i?>" align="center" class="Estilo10">Porcentajes por estrato</td></tr>
</table>
<br>