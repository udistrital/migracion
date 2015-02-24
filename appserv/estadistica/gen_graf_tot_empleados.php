<br>
<table width="60%" border="1" align="center" cellpadding="2" cellspacing="0" style="border-collapse:collapse">
<tr><td colspan="12" align="center" class="Estilo10">Total de funcionarios por tipo</td></tr>
<tr>
<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

require_once('msql_tot_emp_graf.php');
$RowEmpG = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryEmpG,"busqueda");

if(!is_array($RowEmpG))
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
while(isset($RowEmpG[$i][0]))
{
	$rand = rand(0, 5);
	$height = sprintf("%1.2f",($RowEmpG[$i][1]/$totemp)*100) * 2;
	print'<td align="center" valign="bottom"><br>'.$RowEmpG[$i][1].'<br>
	<img src="../img/'.$img[$rand].'" height="'.$height.'" width="'.$width.'"><br>
	<span class="Estilo12">'.$RowEmpG[$i][2].'</span><br>
	<img src="../img/gris.png" width="'.$width.'" height="1"><br>
	<img src="../img/linh.png" height="1" width="'.$width.'"><br>
	<span class="Estilo13">'.number_format(sprintf("%.2f",($RowEmpG[$i][1]/$totemp)*100),1).'%</span></td>';
$i++;
}
?>
</tr>
<tr><td colspan="<? print $i?>" align="center" class="Estilo10">Porcentajes por tipo de funcionario</td></tr>
</table>
<br>