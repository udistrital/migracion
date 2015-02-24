<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

require_once('msql_tot_emp.php');
$RowEmp = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryEmp,"busqueda");

print'<br><table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr class="tr">
    <td align="center"></td>
    <td align="center">Tipo de Funcionario</td>
    <td align="center">Total</td>
  </tr>';
$i=0;
$totemp=0;
while(isset($RowEmp[$i][0]))
{
	print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td align="center" class="Estilo12">'.$RowEmp[$i][2].'</td>
	<td align="left"><a href="index_tot_empleados.php?t='.$RowEmp[$i][2].'" title="Ver detalle">'.$RowEmp[$i][0].'</a></td>
	<td align="right">'.$RowEmp[$i][1].'</td>
	</tr>';
	$totemp = $totemp+$RowEmp[$i][1];
$i++;
}
print'<tr><td align="right" colspan="2"><b>Total de Funcionarios:</b></td>
<td align="right"><b>'.$totemp.'</b></td>
</table><p></p>';
require_once('esta_detalle_empleados.php');
?>