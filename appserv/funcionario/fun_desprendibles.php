<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

?>
<HTML>
<HEAD>
<TITLE>Desprendible de Pago</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/ventana.js"></script>
</HEAD>
<BODY topmargin="0">
<?php
fu_tipo_user(24);
if(isset($_REQUEST['codigo']))
{
	$_SESSION["fun_cod"]=$_REQUEST['codigo'];
}
else
{
	$funcod = $_SESSION["fun_cod"];
	$QryCod = "select emp_cod from peemp where emp_estado_e <> 'R' AND emp_nro_iden = '".$_SESSION['usuario_login']."'";
	$RowCod = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCod,"busqueda");
	$cuenta=count($RowCod);
	
	//echo $cuenta; exit;
	$_SESSION["fun_cod"]= $RowCod[0][0];
}	
$anio = $_REQUEST['anio'];
$mes = $_REQUEST['mes'];
$tipq = $_REQUEST['tipq'];

require_once('msql_desp_quincena_fun.php');
$Rowquincena = $conexion->ejecutarSQL($configuracion,$accesoOracle,$quincena,"busqueda");
if(!is_array($Rowquincena))
{
	echo "<script>location.replace('../err/err_sin_registros.php')</script>";
	exit;
}

$Rowfecha = $conexion->ejecutarSQL($configuracion,$accesoOracle,$fecha,"busqueda");
if(!is_array($Rowfecha))
{
	echo "<script>location.replace('../err/err_sin_registros.php')</script>";
	exit;
}

require_once('msql_desp_mae_fun.php');
$Rowmaestro = $conexion->ejecutarSQL($configuracion,$accesoOracle,$maestro,"busqueda");
if(!is_array($Rowmaestro))
{
	echo "<script>location.replace('../err/err_sin_registros.php')</script>";
	exit;
}

require_once('msql_desp_dev_desc_fun.php');

$Rowdevengos = $conexion->ejecutarSQL($configuracion,$accesoOracle,$devengos,"busqueda");
//echo $devengos."<br>";
if(!is_array($Rowdevengos))
{
	echo "<script>location.replace('../err/err_sin_registros.php')</script>";
	exit;
}

$Rowdescto = $conexion->ejecutarSQL($configuracion,$accesoOracle,$descto,"busqueda");

/*if(!is_array($Rowdescto))
{
	echo "<script>location.replace('../err/err_sin_registros.php')</script>";
	exit;
}*/

require_once('msql_netos_fun.php');
$Rowdev = $conexion->ejecutarSQL($configuracion,$accesoOracle,$Dev,"busqueda");
/*if(!is_array($Rowdev))
{
	echo "<script>location.replace('../err/err_sin_registros.php')</script>";
	exit;
}*/

$Rowdes = $conexion->ejecutarSQL($configuracion,$accesoOracle,$Des,"busqueda");
/*if(!is_array($Rowdes))
{
	echo "<script>location.replace('../err/err_sin_registros.php')</script>";
	exit;
}*/

$print = "javascript:popUpWindow('print_desprendible_fun.php?anio=$anio&mes=$mes&tipq=$tipq', 'yes', 0, 0, 820, 650)";

echo'<br><table width="700" border="1" align="center" style="border-collapse: collapse" bordercolor="#E6E6DE">
	<tr>
		<td width="186" align="left"><span class="Estilo5">A&ntilde;o:</span></td>
		<td width="550">'.$Rowdevengos[0][8].'</td>
		<td width="120" align="left" align="right"><span class="Estilo5">D&iacute;as Pagados:</span></td>
		<td width="142">'.$Rowdevengos[0][5].'</td>
	</tr>
	<tr>
		<td width="186" align="left"><span class="Estilo5">Apellidos y Nombres:</span></td>
		<td width="550">'.$Rowmaestro[0][1].'</td>
		<td width="120" align="left"><span class="Estilo5">Quincena:</span></td>
		<td width="142">'.$Rowdevengos[0][6].'</td>
	</tr>
	<tr>
		<td width="186" align="left"><span class="Estilo5">Identificaci&oacute;n:</span></td>
		<td width="550">'.$Rowmaestro[0][2].'</td>
		<td width="120" align="left"><span class="Estilo5">Mes:</span></td>
		<td width="142">'.$Rowdevengos[0][7].'</td>
	</tr>
	<tr>
		<td width="186" align="left"><span class="Estilo5">Cargo:</span></td>
		<td width="550">'.$Rowmaestro[0][3].'</td>
		<td width="120" align="right">&nbsp;</td>
		<td width="142">&nbsp;</td></tr></table><p></p>';

echo'<table width="660" border="1" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse" bordercolor="#E6E6DE">
     <tr><td width="330" colspan="2" valign="top" align="center">';
	  
echo'<table width="350" border="1" align="center" style="border-collapse: collapse" bordercolor="#E6E6DE">
     <tr><td width="350" colspan="2" align="center"><span class="Estilo5">Devengos</span></td></tr>';
$i=0;
while(isset($Rowdevengos[$i][0]))
{
	echo'<tr><td width="250">'.$Rowdevengos[$i][3].'</td>
        <td width="110" align="right">'.number_format($Rowdevengos[$i][4]).'</td></tr>';
$i++;
}
echo'</table>';

echo'</td><td width="350" colspan="3" align="center" valign="top">';

echo'<div align="center"><center>
     <table border="1" style="border-collapse: collapse" bordercolor="#E6E6DE" width="350">
		<tr>
			<td width="300" colspan="2" align="center"><span class="Estilo5">Descuentos</span></td>
			<td width="50" align="center"><span class="Estilo5">Cuot/Pdte</span></td>
		</tr>';
		$i=0;
		while(isset($Rowdescto[$i][0]))
		{
			echo'<tr>
				<td width="260" align="left">'.$Rowdescto[$i][3].'</td>
				<td width="110" align="right">'.number_format($Rowdescto[$i][4]).'</td>
				<td width="50" align="center">'.$Rowdescto[$i][5].'</td>
			</tr>';
		$i++;
		}
	echo'</table>';
$neto = $Rowdev[0][0] - $Rowdes[0][0];
echo'</td></tr>
<tr>
	<td width="250" align="left"><span class="Estilo5">Total Devengos:</span></td>
	<td width="110" align="right"><b>$'.number_format($Rowdev[0][0]).'</b></td>
	<td width="250" align="left"><span class="Estilo5">Total Descuentos:</span></td>
	<td width="110" align="right"><b>$'.number_format($Rowdes[0][0]).'</b></td>
	<td width="50">&nbsp;</td>
</tr>

<tr>
	<td width="250" align="left"><span class="Estilo5">Neto a pagar:</span></td>
	<td width="110" align="right"><b>$'.number_format($neto).'</b></td>
	<td width="250">&nbsp;</td>
	<td width="110" align="right">&nbsp;</td>
	<td width="50"><img border="0" src="../img/espacio.gif" width="67" height="1"></td>
</tr>
</table><p></p>

<BR><center><input type="submit" value="Imprimir Desprendible de Pago" onClick="'.$print.'" style="cursor:pointer"><BR>';
//require_once(dir_script.'msg_doc_no_valido.php');
echo'</center>';

?>
</BODY>
</HTML>