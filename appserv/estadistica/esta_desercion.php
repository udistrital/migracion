<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

require_once('msql_desercion_tab.php');

$RowEst = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryEst,"busqueda");

require_once('msql_tot_cra.php');
$RowTotCra = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryTotCra,"busqueda");

$print = "javascript:popUpWindow('print_esta_desercion.php', 'yes', 0, 0, 850, 650)";
print'<p>&nbsp;</p>
<div align="center" class="Estilo5">'.$RowEst[0][3].'<br>DESERCI&Oacute;N EN EL PER&Iacute;ODO ACAD&Eacute;MICO '.$RowEst[0][4].'-'.$RowEst[0][5].'</div>
<table width="80%" border="1" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
  <tr>
	<td align="center" valign="middle">
	<br>
	<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
	  <tr>
		<td colspan="3" align="center">Total Por Estado</td>
	  </tr>
	  <tr class="tr">
		<td align="center">C&oacute;d.</td>
		<td align="center">Estado</td>
		<td align="center">Total</td>
	  </tr>';
	$i=0;
	while(isset($RowEst[$i][0]))
	{
		print'<tr><td align="center">'.$RowEst[$i][6].'</td>
		<td align="left">'.ucfirst(strtolower($RowEst[$i][7])).'</td>
		<td align="right">'.$RowEst[$i][8].'</td></tr>';
		$cont+=$RowEst[$i][8];
	$i++;
	}
		
	if(!is_array($RowTotCra))
	{
   	   $totcra = 0;
	}
	else
	{
	     $totcra = $RowTotCra[0][7];
	     $pordesercion = sprintf("%1.2f", ($cont/$totcra)*100);
	}

	print'<tr>
	<td colspan="2" align="right"><b>Total Inscritos:</b></td>
	<td align="right"><b>'.$totcra.'</b></td></tr>
	
	<tr>
	<td colspan="2" align="right"><b>Total Deserci&oacute;n:</b></td>
	<td align="right"><b>'.$i.'</b></td></tr>
	
	<tr>
	<td colspan="2" align="right"><b>Porcentaje de Deserci&oacute;n:</b></td>
	<td align="right"><b>'.$pordesercion.'%</b></td></tr>
	
	</table><br>';
	print'</td><td align="center" valign="middle">';
	require_once('gen_graf_estadistica.php');
	print'</td></tr></table><br><center>
	<input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer;width:150" title="Clic par imprimir el reporte"><center>';
?>