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
fu_cabezote("CUENTA BANCARIA");
$funcod = $_SESSION['usuario_login'];
$funcod = $_SESSION["fun_cod"];
$QryCod = "select emp_cod from peemp where emp_nro_iden = '".$_SESSION['usuario_login']."'";
$RowCod = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCod,"busqueda");
$_SESSION["fun_cod"]=$RowCod[0][0];

require_once('msql_banco.php');
$rowcta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$sqlcta,"busqueda");
if(!is_array($rowcta))
{
	echo "<script>location.replace('../err/err_sin_registros.php')</script>";
	exit;
}

print'<p>&nbsp;</p>
<table border="1" width="97%" align="center" '. $EstiloTab .'>
	<tr class="tr">
		<td colspan="5" align="center">CUENTA PARA CONSIGNACI&Oacute;N DEL SUELDO</td>
	</tr>
	<tr class="td">
		<td align="center">C&oacute;digo</td>
		<td align="center">Banco</td>
		<td align="center">Nro. Cuenta</td>
		<td align="center">Tipo</td>
		<td align="center">P&aacute;gina Web</td>
	</tr>
	<tr>
		<td align="center">'.$rowcta[0][0].'</td>
		<td align="center">'.$rowcta[0][1].'</td>
		<td align="center">'.$rowcta[0][2].'</td>
		<td align="center">'.$rowcta[0][3].'</td>
		<td align="center"><a href="'.$rowcta[0][4].'" target="_blank">'.$rowcta[0][4].'</a></td>
	</tr>
  </table><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
fu_pie(); 
?>
</BODY>
</HTML>