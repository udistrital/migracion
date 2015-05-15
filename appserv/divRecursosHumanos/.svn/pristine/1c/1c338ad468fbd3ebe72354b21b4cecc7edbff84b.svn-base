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
<TITLE>Funcionarios</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../calendario/javascripts.js"></script>
</HEAD>
<BODY>
<?php
$funcod = $_SESSION['usuario_login'];
$QryCod = "select emp_cod from peemp where emp_nro_iden = '".$_SESSION['usuario_login']."'";
$RowCod = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCod,"busqueda");
$_SESSION["fun_cod"]=$RowCod[0][0];

require_once('msql_hiscar_fun.php');
$Rowhiscar = $conexion->ejecutarSQL($configuracion,$accesoOracle,$hiscar,"busqueda");

if(!is_array($Rowhiscar))
{
	header("Location: ../err/err_sin_registros.php"); 
	exit;
}
fu_cabezote("HIST&Oacute;RICO DE CARGOS");

echo'<p>&nbsp;</p>
  <table width="150%" border="1" align="center" '. $EstiloTab .'>
	<tr class="tr">
		<td align="center">Cargo</td>
		<td align="center">Labora</td>
		<td align="center">Tip.Vin</td>
		<td align="center">Doc.Vin</td>
		<td align="center">Tip.Nom</td>
		<td align="center">Desde</td>
		<td align="center">Hasta</td>
		<td align="center">Fec.Doc</td>
		<td align="center">Fec.Acta</td>
		<td align="center">Acta</td>
		<td align="center">Est</td>
	</tr>';
$i=1;
while(isset($Rowhiscar[$i][0]))
{
	echo'<tr>
		<td align="left">'.$Rowhiscar[$i][0].'</td>
		<td align="left">'.$Rowhiscar[$i][2].'</td>
		<td align="left">'.$Rowhiscar[$i][3].'</td>
		<td align="center">'.$Rowhiscar[$i][4].'</td>
		<td align="left">'.$Rowhiscar[$i][5].'</td>
		<td align="center">'.$Rowhiscar[$i][6].'</td>
		<td align="center">'.$Rowhiscar[$i][7].'</td>
		<td align="center">'.$Rowhiscar[$i][8].'</td>
		<td align="center">'.$Rowhiscar[$i][9].'</td>
		<td align="center">'.$Rowhiscar[$i][10].'</td>
		<td align="center">'.$Rowhiscar[$i][11].'</td>
	</tr>';
$i++;
}
print'</table><p>&nbsp;</p>';
require_once('inconsistencia.php');
?>
</BODY>
</HTML>