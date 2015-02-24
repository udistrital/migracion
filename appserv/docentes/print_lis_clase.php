<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);
?>
<HTML>
<HEAD><TITLE>Notas Parciales</TITLE>
<link href="../script/print_estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY topmargin="0" leftmargin="0">

<?php
$docnroiden = $_SESSION['usuario_login'];

fu_print_cabezote("LISTA DE CLASE");
$estado = 'A';

require_once(dir_script.'msql_notaspar_doc.php');
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consulta,"busqueda");

$_REQUEST['asicod'] = $_REQUEST['as'];
$_REQUEST['asigr'] = $_REQUEST['gr'];

require_once(dir_script.'msql_doc_asi_hor.php');
$Qryasihor = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
$row = $Qryasihor;
//---------------------------
echo'<table align="center" border="1" width="90%" cellspacing="2" cellpadding="0">
  <tr><td align="right">'.$consulta[0][4].'</td>
  <td><b>'.$consulta[0][5].'</b></td>
  <td align="center"><b>Grupo</b></td>
  <td align="center"><b>Inscritos</b></td>
  <td align="center"><b>Periodo</b></td>
  </tr>
  <tr>
  <td align="right">'.$consulta[0][0].'</td>
  <td align="left"><b>'.$consulta[0][1].'</b></td>
  <td align="center">'.$consulta[0][6].'</td>
  <td align="center">'.$consulta[0][30].'</td>
  <td align="center">'.$consulta[0][2].'-'.$consulta[0][3].'</td>
  </tr></table> 
  <table align="center" border="1" width="90%" cellspacing="2" cellpadding="0">
  <tr class="tr">
	<td align="center"><b>D&iacute;a</b></td>
    <td align="center"><b>Hora</b></td>
    <td align="center"><b>Sal&oacute;n</b></td>
	<td align="center"><b>Sede</b></td>
  </tr>';
$i=0;
while(isset($Qryasihor[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td align="center">'.$Qryasihor[$i][0].'</td>
     	<td align="center">'.$Qryasihor[$i][1].'-'.$Qryasihor[$i][2].'</td>
	<td align="center">'.$Qryasihor[$i][3].'</td>
	<td align="center">'.$Qryasihor[$i][4].'</td></tr>'; 
$i++;
}
print'</table>
  <table width="90%" border="1" align="center" cellpadding="2" cellspacing="0">
    <tr>
	  <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="right">PORCENTAJES DE NOTAS:&nbsp;</td>
      <td align="center">%1</td>
      <td align="center">%2</td>
      <td align="center">%3</td>
      <td align="center">%4</td>
      <td align="center">%5</td>
      <td align="center">LAB</td>
      <td align="center">EXA</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
	<tr>
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>';
?>
    <tr>
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="8" align="center">NOTAS PARCIALES</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
	  <td align="center"><b>No.</b></td>
      <td align="center"><b>C&oacute;digo</b></td>
      <td align="center"><b>Nombre</b></td>
      <td align="center"><b>1</b></td>
      <td align="center"><b>2</b></td>
      <td align="center"><b>3</b></td>
      <td align="center"><b>4</b></td>
      <td align="center"><b>5</b></td>
      <td align="center"><b>LAB</b></td>
      <td align="center"><b>EXA</b></td>
      <td align="center"><b>HAB</b></td>
      <td align="center"><b>DEF</b></td>
    </tr>
<?php
$i=0;
$cont=1;
while(isset($consulta[$i][0]))
{
	echo'<tr>
	<td align="center">'.$cont.'</td>
     	<td align="right">'.$consulta[$i][7].'</td>
     	<td><font face="Tahoma" size="1">'.$consulta[$i][8].'</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td></tr>';
$cont++;
$i++;
}
?>
<tr><td colspan="12" align="right" style="font-size:9px">Dise&ntilde;&oacute;: Oficina Asesora de Sistemas</td></tr>
</table>
</BODY>
</HTML>