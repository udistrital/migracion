<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(24);

?>
<HTML>
<HEAD>
<TITLE>Funcionarios</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../calendario/javascripts.js"></script>
</HEAD>
<BODY>

<?php
fu_cabezote("FORMACI&Oacute;N B&Aacute;SICA Y SUPERIOR");
$funcod = $_SESSION['usuario_login'];

$QryCod = "select emp_cod from peemp where emp_nro_iden = '".$_SESSION['usuario_login']."'";

$RowCod = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCod,"busqueda");
$_SESSION["fun_cod"]=$RowCod[0][0];

require_once('msql_formacion_fun.php');
$Rowbas = $conexion->ejecutarSQL($configuracion,$accesoOracle,$conbas,"busqueda");
print_r($Rowbas); exit;
if(!is_array($Rowbas))
{
	header("Location: ../err/err_sin_registros.php");
	exit;
}
$Rowsup = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consup,"busqueda");
if(!is_array($Rowsup))
{
	header("Location: ../err/err_sin_registros.php");
	exit;
}

echo'<p>&nbsp;</p>
  <table border="1" width="100%" align="center" '. $EstiloTab .'>
	<tr class="tr">
		<td colspan="6" align="center">FORMACI&Oacute;N B&Aacute;SICA</td>
	</tr> 
	<tr class="td">
		<td align="center">Colegio</td>
		<td align="center">Tipo.Estudio</td>
		<td align="center">Jornada</td>
		<td align="center">Ult.Grado</td>
		<td align="center">Estado</td>
		<td align="center">Termin&oacute;</td>
	</tr>';
$i=0;
while(isset($Rowbas[$i][0]))
{
	echo'<tr class="Estilo1">
		<td align="left">'.$Rowbas[$i][0].'</td>
		<td align="left">'.$Rowbas[$i][1].'</td>
		<td align="center">'.$Rowbas[$i][2].'</td>
		<td align="center">'.$Rowbas[$i][3].'</td>
		<td align="center">'.$Rowbas[$i][4].'</td>
		<td align="center">'.$Rowbas[$i][5].'</td>
	</tr>';
$i++;
}
print'</table>';

echo'<p>&nbsp;</p>
  <table border="1" width="100%" align="center" '. $EstiloTab .'>
	<tr class="tr">
		<td colspan="9">
		<p align="center">FORMACI&Oacute;N SUPERIOR</td>
	</tr> 
	<tr class="td">
		<td align="center">Instituci&oacute;n</td>
		<td align="center">Programa</td>
		<td align="center">Tipo</td>
		<td align="center">Jornada</td>
		<td align="center">Desde</td>
		<td align="center">Estado</td>
		<td align="center">Fec.Grado</td>
		<td align="center">Resol.</td>
		<td align="center">Fec.Resol</td>
	</tr>';
$i=0;
while(isset($Rowsup[$i][0]))
{
	echo'<tr class="Estilo1">
		<td align="left">'.$Rowsup[$i][0].'</td>
		<td align="left">'.$Rowsup[$i][1].'</td>
		<td align="center">'.$Rowsup[$i][2].'</td>
		<td align="center">'.$Rowsup[$i][3].'</td>
		<td align="center">'.$Rowsup[$i][4].'</td>
		<td align="center">'.$Rowsup[$i][5].'</td>
		<td align="center">'.$Rowsup[$i][6].'</td>
		<td align="center">'.$Rowsup[$i][7].'</td>
		<td align="center">'.$Rowsup[$i][8].'</td>
	</tr>';
$i++;
}
?>
</table><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
<? 
require_once('inconsistencia.php');
?>
</BODY>
</HTML>