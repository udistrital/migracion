<br>
<table width="60%" border="1" align="center" cellpadding="2" cellspacing="0" style="border-collapse:collapse">
<tr><td colspan="12" align="center" class="Estilo10">Total de admitidos por estrato</td></tr>
<tr>
<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
$TotAdmSexG = "SELECT COUNT(asp_sexo)
		FROM mntac.acasp
		WHERE asp_ape_ano = $Anio
		AND asp_ape_per = $Peri
		AND asp_admitido = 'A'";
		
$RowTotAspSexG = $conexion->ejecutarSQL($configuracion,$accesoOracle,$TotAdmSexG,"busqueda");

$totadmSex = $RowTotAspSexG[0][0];

$PoblacionAdmSexG = "SELECT (CASE WHEN asp_sexo IS NULL THEN 'Sin' ELSE asp_sexo::text END), 
        COUNT(asp_sexo)
		FROM mntac.acasp
		WHERE asp_ape_ano = $Anio
		AND asp_ape_per = $Peri
		AND asp_admitido = 'A'
		GROUP BY asp_sexo
		ORDER BY asp_sexo";

$RowAspSexG = $conexion->ejecutarSQL($configuracion,$accesoOracle,$PoblacionAdmSexG,"busqueda");

if(!is_array($RowAspSexG))
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
	$height = sprintf("%1.2f",($RowAspSexG[$i][1]/$totadmSex)*100);
	print'<td align="center" valign="bottom"><br>'.$RowAspSexG[$i][1].'<br>
	<img src="../img/'.$img[$rand].'" height="'.$height.'" width="'.$width.'"><br>
	<span class="Estilo12">'.$RowAspSexG[$i][0].'</span><br>
	<img src="../img/gris.png" width="'.$width.'" height="1"><br>
	<img src="../img/linh.png" height="1" width="'.$width.'"><br>
	<span class="Estilo13">'.number_format(sprintf("%.2f",($RowAspSexG[$i][1]/$totadmSex)*100),1).'%</span></td>';
$i++;
}
?>
</tr>
<tr><td colspan="<? print $i?>" align="center" class="Estilo10">Porcentajes por estrato</td></tr>
</table>
<br>