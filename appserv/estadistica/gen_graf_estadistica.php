<br>
<table width="60%" border="1" align="center" cellpadding="2" cellspacing="0" style="border-collapse:collapse">
<tr><td colspan="12" align="center" class="Estilo10">Deserci&oacute;n Por Estado</td></tr>
<tr>
<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

require_once('msql_desercion_graf.php');

$RowGraf = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryGraf,"busqueda");

if(!is_array($RowGraf))
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
	while(isset($RowGraf[$i][0]))
	{
		$rand = rand(0, 5);
		$height = sprintf("%1.2f",($RowGraf[$i][4]/$cont)*100) * 2;
		print'<td align="center" valign="bottom"><br>'.$RowGraf[$i][4].'<br>
		<img src="../img/'.$img[$rand].'" height="'.$height.'" width="'.$width.'"><br>
		<span class="Estilo12">'.ucfirst(strtolower($RowGraf[$i][2])).'</span><br>
		<img src="../img/gris.png" width="'.$width.'" height="1"><br>
		<img src="../img/linh.png" height="1" width="'.$width.'"><br>
		<span class="Estilo13">'.number_format(sprintf("%.2f",($RowGraf[$i][4]/$cont)*100),1).'%</span></td>';
	$i++;
	}
?>
</tr>
<tr><td colspan="<? print $i ?>" align="center" class="Estilo10">Porcentajes de deserci&oacute;n por estado</td></tr>
</table>
<br>