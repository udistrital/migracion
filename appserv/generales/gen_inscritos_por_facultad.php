<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once('msqlAdmisiones/msql_ano_per.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

require_once('msqlAdmisiones/fecha_inscripcion.php');

fu_cabezote("AS&Iacute; VA EL PROCESO DE ADMISIONES");
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
</head>
<body style="margin:0">
<P></P>
<div align="center"><span class="Estilo5">Inscripci&oacute;n de Aspirantes para el Per&iacute;odo Acad&eacute;mico <? print $ano.'-'.$per; ?><br><br>
N&uacute;mero de Inscritos por Facultad</span><br><br>
<span class="Estilo1"><? print 'Fecha inicial: '.$FormFecIni.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Fecha final: '.$FormFecFin; ?></span>
</div>
<br>

<?php
$QryFac = "SELECT unique(cra_dep_cod), dep_nombre
	FROM accra, gedep
	WHERE dep_cod = cra_dep_cod
	AND cra_estado = 'A'
	AND dep_estado = 'A'
	AND cra_dep_cod NOT IN(0,500)
	ORDER BY 1";

$RowFac = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFac,"busqueda");

print'<form name="LisFac" method="POST" action="'.$_SERVER['PHP_SELF'].'">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" align="center">

<select size="1" name="FacCod" style="font-size: 9pt; font-family: Tahoma">';
$i=0;
while(isset($RowFac[$i][0]))
{
	print'<option value="'.$RowFac[$i][0].'" selected>'.$RowFac[$i][0].' - '.$RowFac[$i][1].'</option>\n';
	$FacCod = $RowFac[$i][0];
$i++;
}
print'</select><input type="submit" name="Submit" value="Consultar" title="Ejecutar consulta" style="width:80; cursor:pointer">
</td></tr></table></form>';

if(empty($_REQUEST['FacCod'])) $_REQUEST['FacCod'] = $FacCod;

if(!empty($_REQUEST['FacCod'])){
	$depcod = $_REQUEST['FacCod'];
	require_once(dir_script.'NombreFacultad.php');
	
	require_once('msqlAdmisiones/msql_inscritos_por_facultad.php');
	
	$RowInsFac = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryInsFac,"busqueda");
	
	print'<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse" '.$EstiloTab.'>
	<caption>'.$Facultad.'</caption>
	<tr class="tr">
	<td align="center">C&oacute;digo</td>
	<td align="center">Proyecto Curricular</td>
	<td align="center">Tipo de Inscripci&oacute;n</td>
	<td align="center">Total</td></tr>';
	$i=0;
	$tot=0;
	while(isset($RowInsFac[$i][0]))
	{
		print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td align="right">'.$RowInsFac[$i][2].'</td>
		<td align="left">'.$RowInsFac[$i][3].'</td>
		<td align="left">'.$RowInsFac[$i][4].'</td>
		<td align="right">'.$RowInsFac[$i][5].'</td></tr>';
		$tot=$tot+$RowInsFac[$i][5];
	$i++;
	}
	
	print'<tr><td colspan="3" align="right"><b>Total Inscritos en la Facultad:</b></td>
	<td align="right"><b>'.$tot.'</b></td>
	</tr>
	</table><P></P>';
}

//TIPO DE INSCRIPCI�N
require_once('msqlAdmisiones/msql_uso_tipo_formulario.php');
$RowTipIns = $registro = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryTipIns,"busqueda");

print'<table width="35%" border="1" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse" '.$EstiloTab.'>
<tr class="tr">
<td align="center">Formulario</td>
<td align="center">Total</td></tr>';
$i=0;
$tot=0;
while(isset($RowTipIns[$i][0]))
{
	print'<tr>
	<td>'.$RowTipIns[$i][0].'</td>
	<td align="right">'.$RowTipIns[$i][1].'</td></tr>';
	$tot=$tot+$RowTipIns[$i][1];
$i++;
}
print'<tr>
<td align="right"><b>Total Inscritos en la Universidad:</b></td>
<td align="right"><b>'.$tot.'</b></td>
</tr>
</table>';

print'<p align="center">
<input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:80" title="Clic par imprimir el reporte">
</p>';
?>
</body>
</html>