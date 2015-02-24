<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_print_cabezote_fun.php');
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
<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY topmargin="0" leftmargin="0" background=" "><BR>
<?php
fu_print_cabezote_fun("DESPRENDIBLE DE PAGO");

$funcod = $_SESSION['usuario_login'];
$anio = $_REQUEST['anio'];
$mes = $_REQUEST['mes'];
$tipq = $_REQUEST['tipq'];

require_once('msql_desp_quincena_fun.php');
$Rowquincena = $conexion->ejecutarSQL($configuracion,$accesoOracle,$quincena,"busqueda");
$Rowfecha = $conexion->ejecutarSQL($configuracion,$accesoOracle,$fecha,"busqueda");

require_once('msql_desp_mae_fun.php');
$Rowmaestro = $conexion->ejecutarSQL($configuracion,$accesoOracle,$maestro,"busqueda");

require_once('msql_desp_dev_desc_fun.php');
$Rowdevengos = $conexion->ejecutarSQL($configuracion,$accesoOracle,$devengos,"busqueda");
$Rowdescto = $conexion->ejecutarSQL($configuracion,$accesoOracle,$descto,"busqueda");

require_once('msql_netos_fun.php');
$Rowdev = $conexion->ejecutarSQL($configuracion,$accesoOracle,$Dev,"busqueda");
$Rowdes = $conexion->ejecutarSQL($configuracion,$accesoOracle,$Des,"busqueda");

echo'<br><table width="88%" align="center" border="1" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="right"><b><font face="Tahoma" size="2" color="#336699">A&ntilde;o:</font></b></td>
		<td><font size="2" face="Tahoma">'.$Rowdevengos[0][8].'</font></td>
		<td align="right" align="right"><b><font face="Tahoma" size="2" color="#336699">D&iacute;as Pagados:</font></b></td>
		<td><font size="2" face="Tahoma">'.$Rowdevengos[0][5].'</font></td>
	</tr>
	<tr>
		<td align="right"><b><font face="Tahoma" size="2" color="#336699">Nombres y Apellidos:</font></b></td>
		<td><font size="2" face="Tahoma">'.$Rowmaestro[0][1].'</font></td>
		<td align="right"><b><font face="Tahoma" size="2" color="#336699">Quincena:</font></b></td>
		<td><font size="2" face="Tahoma">'.$Rowdevengos[0][6].'</font></td>
	</tr>
	<tr>
		<td align="right"><b><font face="Tahoma" size="2" color="#336699">Identificaci&oacute;n:</font></b></td>
		<td><font size="2" face="Tahoma">'.$Rowmaestro[0][2].'</font></td>
		<td align="right"><b><font face="Tahoma" size="2" color="#336699">Mes:</font></b></td>
		<td><font size="2" face="Tahoma">'.$Rowdevengos[0][7].'</font></td>
	</tr>
	<tr>
		<td align="right"><b><font face="Tahoma" size="2" color="#336699">Cargo:</font></b></td>
		<td><font size="2" face="Tahoma">'.$Rowmaestro[0][3].'</font></td>
		<td align="right">&nbsp;</td>
		<td>&nbsp;</td></tr></table><p></p>';

echo'<table width="660" border="1" align="center" cellpadding="1" cellspacing="1" style="border-collapse:collapse" bordercolor="#111111">
     <tr><td width="330" colspan="2" valign="top" align="center">';
	  
echo'<table width="350" border="1" align="center" style="border-collapse:collapse" bordercolor="#111111">
     <tr><td width="300" colspan="2" align="center"><b><font face="Tahoma" size="2" color="#336699">Devengos</font></b></td></tr>';
$i=0;
while(isset($Rowdevengos[$i][0]))
{
	echo'<tr>
		<td width="250"><font size="2" face="Tahoma">'.$Rowdevengos[$i][3].'</font></td>
		<td width="110" align="right"><font size="2" face="Tahoma">'.number_format($Rowdevengos[$i][4]).'</font></td>
	</tr>';
$i++;
}
echo'</table></center></div>';
$neto = $Rowdev[0][0] - $Rowdes[0][0];
echo'</td><td width="350" colspan="3" align="center" valign="top">';

echo'<table width="350" border="1" align="center" style="border-collapse:collapse" bordercolor="#111111">
	<tr>
		<td width="300" colspan="2" align="center"><b><font face="Tahoma" size="2" color="#336699">Descuentos</font></b></td>
		<td width="50" align="center"><b><font size="1" face="Tahoma" color="#336699">Ctas/Pdte</font></b></td>
	</tr>';
$i=0;
while(isset($Rowdescto[$i][0]))
{
	echo'<tr>
   		<td width="250" align="left"><font size="2" face="Tahoma">'.$Rowdescto[$i][3].'</font></td>
		<td width="110" align="right"><font size="2" face="Tahoma">'.number_format($Rowdescto[$i][4]).'</font></td>
		<td width="50" align="center"><font size="2" face="Tahoma">'.$Rowdescto[$i][5].'</font></td>
	</tr>';
$i++;
}
echo'</table>';
echo'</td></tr>

	<tr>
		<td width="250" align="right"><b><font face="Tahoma" color="#336699" size="2">Total Devengos:</font></b></td>
		<td width="110" align="right"><b><font size="2" face="Tahoma">$'.number_format($Rowdev[0][0]).'</font></b></td>
		<td width="260" align="right"><b><font face="Tahoma" color="#336699" size="2">Total Descuentos:</font></b></td>
		<td width="110" align="right"><b><font size="2" face="Tahoma">$'.number_format($Rowdes[0][0]).'</font></b></td>
		<td width="50">&nbsp;</td>
	</tr>
	<tr>
		<td width="250" align="right"><b><font face="Tahoma" color="#336699" size="2">Neto a pagar:</font></b></td>
		<td width="110" align="right"><b><font size="2" face="Tahoma">$'.number_format($neto).'</font></b></td>
		<td width="250">&nbsp;</td>
		<td width="110" align="right">&nbsp;</td>
		<td width="50"><img src="../img/espacio.gif" width="52" height="1"></td>
	</tr>
</table><p></p>';

//require_once(dir_script.'msg_doc_no_valido.php');
?>
</BODY>
</HTML>