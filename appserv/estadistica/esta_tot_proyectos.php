<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<HTML>
<HEAD>
<TITLE>Estadisticas</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?php
fu_cabezote("PROYECTOS CURRICULARES"); 
require_once('msql_tot_proyectos.php');
$RowTotProy = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryTotProy,"busqueda");

print'<p>&nbsp;</p>
<p align="center" class="Estilo5">PROYECROS CURRICULARES CON ESTUDIANTES ACTIVOS</p>
<table width="35%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr class="tr">
    <td align="center">Tipo</td>
    <td align="center">Total</td>
  </tr>';

$totpyc = 0;
$i=0;
while(isset($RowTotProy[$i][0]))
{ 
	print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td align="left"><a href="esta_tot_proyectos.php?t='.$RowTotProy[$i][0].'" title="Ver detalle">'.$RowTotProy[$i][0].'</a></td>
		<td align="right">'.$RowTotProy[$i][1].'</td>
	</tr>';
	$totpyc = $totpyc+$RowTotProy[$i][1];
	$i++;
}
print'<tr><td align="right"><b>Total Proyectos Curriculares:</b></td>
<td align="right"><b>'.$totpyc.'</b></td>
</table><p>&nbsp;</p>';
require_once('esta_detalle_proyectos.php');
?>
</BODY>
</HTML>