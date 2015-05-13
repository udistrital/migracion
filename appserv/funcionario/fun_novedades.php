<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(24);
?>
<HTML>
<HEAD>
<TITLE>Desprendible de Pago</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../calendario/javascripts.js"></script>
</HEAD>
<BODY>
<?php
fu_cabezote("NOVEDADES DE NOMINA");
$funcod = $_SESSION['usuario_login'];
//$funcod = $_SESSION["fun_cod"];
$QryCod = "select emp_cod from peemp where emp_nro_iden = '".$_SESSION['usuario_login']."'";
$RowCod = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCod,"busqueda");
$_SESSION["fun_cod"]=$RowCod[0][0];

$estado = 'A';
$prnovedades = 'PRNOVED';

require_once('msql_novedades_fun.php');
$Rownoved = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sqlnoved,"busqueda");
if(!is_array($Rownoved))
{
	header("Location: ../err/err_sin_registros.php");
	exit;
}

print'<p>&nbsp;</p>
  <table border="1" width="97%" align="center" '. $EstiloTab .'>
  <caption>NOVEDADES ACTIVAS</caption>
	<tr class="tr">
		<td align="center">Tipo</td>
		<td align="center">C&oacute;digo</td>
		<td align="center">Concepto</td>
		<td align="center">Sec.</td>
		<td align="center">Unidades</td>
		<td align="center">Valor</td>
		<td align="center">Cuotas</td>
		<td align="center">Fecha</td>
		<td align="center">Estado</td>
	</tr>';
$i=0;
while(isset($Rownoved[$i][0]))
{
	print'<tr>
		<td align="right">'.$Rownoved[$i][0].'</td>
		<td align="right">'.$Rownoved[$i][1].'</td>
		<td align="left">'.$Rownoved[$i][2].'</td>
		<td align="right">'.$Rownoved[$i][3].'</td>
		<td align="left">'.$Rownoved[$i][4].'</td>
		<td align="right">'.number_format($Rownoved[$i][5]).'</td>
		<td align="right">'.$Rownoved[$i][6].'</td>
		<td align="right">'.$Rownoved[$i][7].'</td>
		<td align="center">'.$Rownoved[$i][8].'</td>
	</tr>';
$i++;
}
print'</table><p>&nbsp;</p>';
require_once('inconsistencia.php');
?>
</BODY>
</HTML>