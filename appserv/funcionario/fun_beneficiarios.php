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
<link rel="stylesheet" type="text/css" href="../script/estilo.css" />
<script language="JavaScript" src="../calendario/javascripts.js"></script>
</HEAD>
<BODY>
<?php
fu_cabezote("BENEFICIARIOS");

$funcod = $_SESSION['usuario_login'];

$QryCod = "select emp_cod from peemp where emp_nro_iden = '".$_SESSION['usuario_login']."'";

$RowCod = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCod,"busqueda");
$_SESSION["fun_cod"]=$RowCod[0][0];

require_once('msql_beneficiarios_fun.php');

$registro = $conexion->ejecutarSQL($configuracion,$accesoOracle,$beneficiarios,"busqueda");

if(!is_array($registro))
{
	die('<h3>No tiene beneficiarios registrados en el sistema.</h3>');
	exit;
}

echo'<p></p>
  <table width="97%" border="1" align="center" '. $EstiloTab .'>
    <tr class="tr">
      <td align="center" class="Estilo5">1</td>
      <td align="center" class="Estilo5">2</td>
      <td align="center" class="Estilo5">3</td>
      <td align="center" class="Estilo5">4</td>
      <td align="center" class="Estilo5">5</td>
      <td align="center" class="Estilo5">6</td>
      <td align="center" class="Estilo5">7</td>
      <td align="center" class="Estilo5">8</td>
      <td align="center" class="Estilo5">9</td>
      <td align="center" class="Estilo5">10</td>
      <td align="center" class="Estilo5">11</td>
      <td align="center" class="Estilo5">12</td>
      <td align="center" class="Estilo5">13</td>
      <td align="center" class="Estilo5">14</td>
      <td align="center" class="Estilo5">15</td>
      <td align="center" class="Estilo5">16</td>
      <td align="center" class="Estilo5">17</td>
    </tr>';
$i=0;
while(isset($registro[$i][0]))
{
	echo'<tr>
	<td align="left"><span class="Estilo3">'.$registro[$i][0].'</td>
	<td align="right"><span class="Estilo3">'.$registro[$i][1].'</td>
	<td align="left"><span class="Estilo3">'.$registro[$i][2].'</td>
	<td align="left"><span class="Estilo3">'.$registro[$i][3].'</td>
	<td align="right"><span class="Estilo3">'.$registro[$i][4].'</td>
	<td align="left"><span class="Estilo3">'.$registro[$i][5].'</td>
	<td align="center"><span class="Estilo3">'.$registro[$i][6].'</td>
	<td align="center"><span class="Estilo3">'.$registro[$i][7].'</td>
	<td align="center"><span class="Estilo3">'.$registro[$i][8].'</td>
	<td align="center"><span class="Estilo3">'.$registro[$i][9].'</td>
	<td align="center"><span class="Estilo3">'.$registro[$i][10].'</td>
	<td align="center"><span class="Estilo3">'.$registro[$i][11].'</td>
	<td align="center"><span class="Estilo3">'.$registro[$i][12].'</td>
	<td align="center"><span class="Estilo3">'.$registro[$i][13].'</td>
	<td align="right"><span class="Estilo3">'.$registro[$i][14].'</td>
	<td align="right"><span class="Estilo3">'.$registro[$i][15].'</td>
	<td align="right"><span class="Estilo3">'.$registro[$i][16].'</td></tr>';
$i++;
}
print'</table>';
?>
<p>&nbsp;</p>
<table width="97%" border="0" align="center">
    <tr>
      <td width="50%" align="left">

  <table width="251" border="1" <? $EstiloTab ?>>
    <tr>
      <td width="23" align="right" class="Estilo5">1</td>
      <td width="219">Nombre del beneficiario.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">2</td>
      <td>Documento de identidad.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">3</td>
      <td>Tipo de documento de identidad.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">4</td>
      <td>Lugar donde se expidi&oacute; el documento.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">5</td>
      <td>Fecha de nacimiento.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">6</td>
      <td>Lugar de nacimiento.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">7</td>
      <td>Sexo.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">8</td>
      <td>Estado civil.</td>
    </tr>
    </table>
</td>

<td align="center" valign="middle">CONVENCI&Oacute;N</td>

<td width="50%" align="right">

  <table border="1" width="251" <? $EstiloTab ?>>
    <tr>
      <td align="right" class="Estilo5">9</td>
      <td>Ocupaci&oacute;n del beneficiario.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">10</td>
      <td>Parentesco del beneficiario.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">11</td>
      <td>Subsidio familiar.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">12</td>
      <td>Auxilio de libros.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">13</td>
      <td>Servicio m&eacute;dico.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">14</td>
      <td>Seguro de vida.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">15</td>
      <td>Fecha de ingreso.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">16</td>
      <td>Edad.</td>
    </tr>
    <tr>
      <td align="right" class="Estilo5">16</td>
      <td>Estado.</td>
    </tr>
  </table>
	  </td>
    </tr>
  </table>
<p>&nbsp;</p>
<? 
require_once('inconsistencia.php');
?>
</BODY>
</HTML>
