<br>
<table width="60%" border="1" align="center" cellpadding="2" cellspacing="0" style="border-collapse:collapse">
<tr><td colspan="<? print $i?>" align="center" class="Estilo10">Total de admitidos por localidad</td></tr>
<tr>
<?php
$TotAdmLocG = "SELECT count(asp_localidad)
	FROM acasp
	WHERE asp_ape_ano = $Anio
	AND asp_ape_per = $Peri
	AND asp_admitido = 'A'";

$RowAspLocG = $conexion->ejecutarSQL($configuracion,$accesoOracle,$TotAdmLocG,"busqueda");

$TotAdmLoc = $RowAspLocG[0][0];

$PoblacionAdmLocG = "SELECT loc_nombre, 
		COUNT(asp_localidad), to_number(loc_nro,'9999999') 
		FROM mntac.acasp, mntac.aclocalidad
		WHERE asp_ape_ano = $Anio
		AND asp_ape_per = $Peri
		AND loc_ape_ano = asp_ape_ano
		AND loc_ape_per = asp_ape_per
		AND loc_nro = asp_localidad
		AND asp_admitido = 'A'
		AND loc_estado = 'A'
		GROUP BY loc_nombre, to_number(loc_nro,'9999999')
		ORDER BY to_number(loc_nro,'9999999') ASC";
		

$RowAspLocG = $conexion->ejecutarSQL($configuracion,$accesoOracle,$PoblacionAdmLocG,"busqueda");

if(!is_array($RowAspLocG))
{
	die('<div align="center"><font color="#FF0000">--// Sin Datos //--</font></div>');
}
$width=20;
$img = array(0=>'green.png',
			 1=>'silver.png',
			 2=>'blue.png',
			 3=>'gold.png',
			 4=>'red.png',
			 5=>'gray.png');
$i=0;
while(isset($RowAspLocG[$i][0]))
{
	$rand = rand(0, 5);
	$height = sprintf("%1.2f",($RowAspLocG[$i][1]/$TotAdmLoc)*100);
	print'<td align="center" valign="bottom"><span class="Estilo3"><br>'.$RowAspLocG[$i][1].'<br></span>
	<img src="../img/'.$img[$rand].'" height="'.$height.'" width="'.$width.'"><br>
	<span class="Estilo12">'.$RowAspLocG[$i][2].'</span><br>
	<img src="../img/gris.png" width="'.$width.'" height="1"><br>
	<img src="../img/linh.png" height="1" width="'.$width.'"><br>
	<span class="Estilo3">'.number_format(sprintf("%.2f",($RowAspLocG[$i][1]/$TotAdmLoc)*100),1).'%</span></td>';
$i++;
}
?>
</tr>
<tr><td colspan="<? print $i?>" align="center" class="Estilo10">Porcentajes por localidad</td></tr>
</table>
<br>