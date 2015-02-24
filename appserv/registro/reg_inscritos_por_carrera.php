<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once('msql_ano_per.php');
require_once('fecha_inscripcion.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(33);

fu_cabezote("INSCRITOS POR PROYECTO CURRICULAR");
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
<span class="Estilo1">
<? print 'Fecha inicial: '.$FormFecIni.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Fecha final: '.$FormFecFin; ?></span>
</div>

<?php
$datos = "SELECT cra_cod,cra_nombre 
	FROM accra 
	WHERE cra_estado = 'A' 
	AND cra_se_ofrece = 'S'
	ORDER BY cra_nombre ASC";

$row = $conexion->ejecutarSQL($configuracion,$accesoOracle,$datos,"busqueda");

print'<form name="LIS_CRA" method="POST" action="'.$_SERVER['PHP_SELF'].'">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td width="100%" align="center">

<select size="1" name="CraCod" style="font-size: 9pt; font-family: Tahoma">';
$i=0;
while(isset($row[$i][0]))
{
	print'<option value="'.$row[$i][0].'" selected>'.$row[$i][0].' - '.$row[$i][1].'</option>\n';
	$CraCod = $row[$i][0];
$i++;
}

print'</select><input type="submit" name="Submit" value="Consultar" title="Ejecutar consulta" style="width:80; cursor:pointer">
</td></tr></table></form>';

if(empty($_REQUEST['CraCod'])) $_REQUEST['CraCod'] = $CraCod;

if(!empty($_REQUEST['CraCod']))
{
	require_once('msql_inscritos_por_carrera.php');
	$RowInsCra = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryInsCra,"busqueda");
	
	if(!is_array($RowInsCra))
	{
		echo "<script>location.replace('../err/err_sin_registros.php')</script>";
	}
	print'<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse" '.$EstiloTab.'>
		<tr class="tr">
			<td align="center">C&oacute;d</td>
			<td align="center">Proyecto Curricular</td>
			<td align="center">Tipo de Inscripci&oacute;n</td>
			<td align="center">Total</td></tr>';
			
			$tot=0;
			$i=0;
			while(isset($RowInsCra[$i][0]))
			{
				print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
					<td align="right">'.$RowInsCra[$i][2].'</td>
					<td align="left">'.$RowInsCra[$i][3].'</td>
					<td align="left">'.$RowInsCra[$i][4].'</td>
					<td align="right">'.$RowInsCra[$i][5].'</td>
				</tr>';
				$tot=$tot+$RowInsCra[$i][5];
			$i++;
			}
		
			print'<tr><td colspan="3" align="right"><b>Total de inscritos en el Proyecto Curricular:</b></td>
			<td align="right"><b>'.$tot.'</b></td>
		</tr>
	</table><P></P>';
}

require_once('msql_uso_tipo_formulario.php');
$RowTipIns = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryTipIns,"busqueda");

print'<table width="35%" border="1" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse" '.$EstiloTab.'>
	<tr class="tr">
		<td align="center">Formulario</td>
		<td align="center">Total</td>
	</tr>';
	$tot = 0;
	$i=0;
	while(isset($RowTipIns[$i][0]))
	{ 
		print'<tr>
		<td>'.$RowTipIns[$i][0].'</td>
			<td align="right">'.$RowTipIns[$i][1].'</td>
		</tr>';
		$tot=$tot+$RowTipIns[$i][1];
	$i++;
	}
	print'<tr>
		<td align="right"><b>Total de inscritos en la Universidad:</b></td>
		<td align="right"><b>'.$tot.'</b></td>
	</tr>
</table>';

print'<p align="center">
<input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:80" title="Clic par imprimir el reporte">
</p>';
?>
</body>
</html>