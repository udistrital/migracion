<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

require_once('msql_uso_diario_contador.php');
$RowUsoC = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryUsoC,"busqueda");

?>
<P>&nbsp;</P>
	<table width="25%" border="1" align="center" cellpadding="2" cellspacing="0" style="border-collapse:collapse" <? print $EstiloTab; ?>>
		<tr>
			<td colspan="<? print $i;?>" align="center" class="Estilo10">Accesos Diarios</td>
		</tr>
		<tr>
		<?php
		$width=20;
		$img = array(0=>'green.png',
					1=>'blue.png',
					2=>'gray.png',
					3=>'red.png');
		$i=0;
		while(isset($RowUsoC[$i][0]))
		{
			$rand = rand(0, 3);
			$height = (sprintf("%1.2f",($RowUsoC[$i][3]/$TotCon)*100))* ($i/2);
			print'<td align="center" valign="bottom"><span class="Estilo3">'.$RowUsoC[$i][3].'</span><br>'.
			'<img src="../img/'.$img[$rand].'" height="'.$height.'" width="'.$width.'">'.'<br>
			<span class="Estilo12">'.$i.'</span><br>
			<img src="../img/gris.png" width="'.$width.'" height="1"><br>
			<span class="Estilo3">'.number_format(sprintf("%1.2f",($RowUsoC[$i][3]/$TotCon)*100),1).'%</span></td>';
		$i++;
		}
		?>
		</tr>
		<tr>
			<td colspan="<? print $i;?>" align="center" class="Estilo10">Porcentaje de accesos diarios.<br></td>
		</tr>
		<tr>
			<td colspan="<? print $i;?>" align="center" class="Estilo10">Promedio de ingresos por d&iacute;a: <? print number_format($tot/$i,2); ?></td>
		</tr>
	</table>
<P>&nbsp;</P>
<?php
?>