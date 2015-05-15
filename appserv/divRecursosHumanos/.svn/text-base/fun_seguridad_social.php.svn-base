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
fu_cabezote("SEGURIDAD SOCIAL");
$funcod = $_SESSION['usuario_login'];

$QryCod = "select emp_cod from peemp where emp_nro_iden = '".$_SESSION['usuario_login']."'";
$RowCod = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCod,"busqueda");
$_SESSION["fun_cod"]=$RowCod[0][0];

require_once('msql_seguridad_social_fun.php');
$RowArp = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryArp,"busqueda");
$RowEps = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryEps,"busqueda");
$RowFondo = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFondo,"busqueda");
$RowCaja = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCaja,"busqueda");

print'<p>&nbsp;</p>
<table width="98%" border="1" align="center" '.$EstiloTab.'>
  <tr class="tr">
    <td align="center"></td>
    <td align="center">Nombre</td>
    <td align="center">Direcci&oacute;n</td>
    <td align="center">Tel&eacute;fono</td>
  </tr>
  <tr>
    <td class="Estilo5" align="right">ARP:&nbsp;</td>
    <td align="left">'.$RowArp[0][0].'</td>
    <td align="left">'.$RowArp[0][1].'</td>
    <td align="right">'.$RowArp[0][2].'</td>
  </tr>
  <tr>
    <td class="Estilo5" align="right">EPS:&nbsp;</td>
    <td align="left">'.$RowEps[0][0].'</td>
    <td align="left">'.$RowEps[0][1].'</td>
    <td align="right">'.$RowEps[0][2].'</td>
  </tr>
  <tr>
    <td class="Estilo5" align="right">FONDO DE PENSIONES:&nbsp;</td>
    <td align="left">'.$RowFondo[0][0].'</td>
    <td align="left">'.$RowFondo[0][1].'</td>
    <td align="right">'.$RowFondo[0][2].'</td>
  </tr>
  <tr>
    <td class="Estilo5" align="right">CAJA COMP. FAMILIAR:&nbsp;</td>
    <td align="left">'.$RowCaja[0][0].'</td>
    <td align="left">'.$RowCaja[0][1].'</td>
    <td align="right">'.$RowCaja[0][2].'</td>
  </tr>
</table>
<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
require_once('inconsistencia.php');
?>
</BODY>
</HTML>