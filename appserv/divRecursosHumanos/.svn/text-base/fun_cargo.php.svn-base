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
ob_start();

fu_cabezote("CARGO ACTUAL");
$funcod = $_SESSION['usuario_login'];

$QryCod = "select emp_cod from peemp where emp_nro_iden = '".$_SESSION['usuario_login']."'";
$RowCod = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCod,"busqueda");

$_SESSION["fun_cod"]=$RowCod[0][0];

require_once('msql_cargo_fun.php');
$Rowcargo = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cargo,"busqueda");

if(!is_array($Rowcargo))
{
	header("Location: ../err/err_sin_registros.php");
	exit;
}

print'<p>&nbsp;</p>
  <table border="1" width="97%" align="center" '. $EstiloTab .'>
	<tr class="tr">
		<td align="center">C&oacute;digo del Cargo</td>
		<td align="center">Nivel</td>
		<td align="center">Grado</td>
		<td align="center">Nombre</td>
		<td align="center">Sueldo</td>
	</tr>
	<tr>
		<td align="center">'.$Rowcargo[0][0].'</td>
		<td align="center">'.$Rowcargo[0][1].'</td>
		<td align="center">'.$Rowcargo[0][2].'</td>
		<td align="left">'.$Rowcargo[0][3].'</td>
		<td align="right">$'.number_format($Rowcargo[0][4]).'</td>
	</tr>
  </table><p>&nbsp;</p><p>&nbsp;</p>';

cierra_bd($cargo, $oci_conecta);
require_once('inconsistencia.php');
?>
</BODY>
</HTML>