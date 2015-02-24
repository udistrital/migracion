<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$rowRef = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryRef,"busqueda");

print'<p>&nbsp;</p><table width="60%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<caption>INFORMACI&Oacute;N REPORTADA POR EL BANCO</caption>';
	echo '<tr class="tr">
		      <td align="center">A&ntilde;o</td>
		      <td align="center">Per&iacute;odo</td>
		      <td align="center">Identificaci&oacute;n</td>
		      <td align="center">Referencia</td>
		      <td align="center" title="dd/mm/yyyy">Fecha Pago</td>
		      <td align="center">Credencial</td>
		</tr>';  
	for ($i=0; $i<=count($rowRef); $i++)
	{
		echo '<tr>
		      <td align="center">'.$rowRef[$i][0].'</td>
		      <td align="center">'.$rowRef[$i][1].'</td>
		      <td align="center">'.$rowRef[$i][2].'</td>
		      <td align="center">'.$rowRef[$i][3].'</td>
		      <td align="center" title="dd/mm/yyyy">'.$rowRef[$i][4].'</td>
		      <td align="center">'.$rowRef[$i][5].'</td>
		</tr>';
	}
	echo '</table><p>&nbsp;</p>';
?>