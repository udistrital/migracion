<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
require_once('fu_print_cabezote_pt.php');
include_once("../clase/multiConexion.class.php");
fu_tipo_user(30);
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<HTML>
<HEAD><TITLE>Plan de trabajo</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/print_estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</HEAD>
<BODY style="margin-top:0">

<?php
fu_print_cabezote_pt('ACTIVIDADES DOCENTES Y PLAN DE TRABAJO');
$usuario = $_SESSION['usuario_login'];
$nivel  = $_SESSION["usuario_nivel"];

require_once(dir_script.'NombreUsuario.php');
require_once('msql_doc_consulta_carlec_pt.php');
require_once('msql_doc_consulta_pt.php');
require_once('msql_doc_consulta_obs_pt.php');
require_once('msql_doc_cuenta_act_pt.php');

$Tipovinculacion = "select distinct dtv_tvi_cod,tvi_nombre
from acdoctipvin,acasperi,actipvin
where ape_ano=dtv_ape_ano
and tvi_cod=dtv_tvi_cod
and ape_per=dtv_ape_per
and ape_estado='A'
and dtv_estado='A'
and dtv_doc_nro_iden=$usuario";
$registro1=$conexion->ejecutarSQL($configuracion,$accesoOracle,$Tipovinculacion,"busqueda");

echo'<p></p><table border="0" align="center" cellspacing="0" width="100%">
    <tr><td width="13%" align="right"><span class="Estilo5">Docente:</span></td>
      <td width="55%" align="left"><b>'.$Nombre.'</b></td>
      <td width="25%" align="right"><span class="Estilo5">Per&iacute;odo:</span></td>
      <td width="8%" align="left"><b>'.$ano.'-'.$per.'</b></td></tr>
    <tr><td align="right"><span class="Estilo5">Identificaci&oacute;n:</span></td>
      <td align="left">'.$usuario.'</td>
      <td align="right">&nbsp;</td>
      <td align="left">&nbsp;</td></tr>
	  </table>';

//TABLA DE CARGA LECTIVA
print'<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
<caption><span class="Estilo10">CARGA LECTIVA</span></caption>
  <tr>
    <td align="center"><span class="Estilo5">Asignatura</span></td>
    <td align="center">Tipo de vinculaci&oacute;n</td>
    <td align="center">.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td align="center"><span class="Estilo5">D&iacute;a</span></td>
    <td align="center"><span class="Estilo5">Hora</span></td>
    <td align="center"><span class="Estilo5">Sede</span></td>
    <td align="center"><span class="Estilo5">Sal&oacute;n</span></td>
  </tr>';
$registro2=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCarLec,"busqueda");
$i=0;
while(isset($registro2[$i][0]))
{
	print'<tr style="font-size:11px">
   	<td>'.UTF8_DECODE($registro2[$i][4]).'</td>
   	<td>'.$registro2[$i][9].'</td>
   	<td><b>'.$registro2[$i][10].'</b></td>
  	<td>'.$registro2[$i][5].'</td>
   	<td>'.$registro2[$i][6].'</td>
   	<td align="center">'.$registro2[$i][7].'</td>
   	<td align="right">'.$registro2[$i][8].'</td></tr>';
	$i++;
}
echo '</table><p></p>';

echo '<br><center>';

echo '<table style="border-collapse:collapse;border-color:black;"  border="1">';
echo '<tr bgcolor="#E4E5DB"><td align="center"><b>TIPO DE VINCULACION</b </td><td align="center"><b>TOTAL HORAS LECTIVAS</b></td><td align="center">.</td></tr>';

$contarTipVin = "SELECT count(*) FROM actipvin";
$registroTipVin=$conexion->ejecutarSQL($configuracion,$accesoOracle,$contarTipVin,"busqueda");
$totalTipVin=$registroTipVin[0][0];

for($k=0;$k<$totalTipVin;$k++){
	if(!is_null($tipoVinculacion[$k])){
		echo '<tr bgcolor="#E4E5DB"><td> '.$tipoVinculacion[$k].'</td><td align="center"> '.$NroLec[$k].'</td></td><td align="center"><b>'.$NroTip[$k].'</b></td></tr>';
	}
}
echo '</table>';
echo '</center><br>';


//TABLA DE ACTIVIDADES
echo'<table width="100%" border="1" align="center" cellspacing="0" cellpadding="1">
<caption><span class="Estilo10">ACTIVIDADES</span></caption>
 	<tr><td align="center"><span class="Estilo5">Actividad</span></td>
  	<td align="center">Tipo de vinculaci&oacute;n</td>
  	<td align="center">.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td align="center"><span class="Estilo5">D&iacute;a</span></td>
	<td align="center"><span class="Estilo5">Hora</span></td>
	<td align="center"><span class="Estilo5">Sede</span></td>
	<td align="center"><span class="Estilo5">Sal&oacute;n</span></td>
  </tr>';
$registro3=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consulta,"busqueda");
$i=0;
while(isset($registro3[$i][0]))
{
	echo'<tr style="font-size:11px">
	<td align="left">'.UTF8_DECODE($registro3[$i][3]).'</td>
	<td align="left">'.$registro3[$i][14].'</td>
	<td align="left"><b>'.$registro3[$i][15].'</b></td>
	<td align="left">'.$registro3[$i][4].'</td>
	<td align="left">'.$registro3[$i][5].'</td>
	<td align="center">'.$registro3[$i][6].'</td>
	<td align="left">'.$registro3[$i][7].'</td></tr>';
$i++;
}
$registro4=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryObs,"busqueda");
echo '<tr><td colspan="7"><strong>Observaci&oacute;n a su plan de trabajo:</strong><div align="justify">'.$registro4[0][3].'</div></td></tr></table>';
echo '<br><center>';
echo '<table style="border-collapse:collapse;border-color:black;"  border="1">';
echo '<tr bgcolor="#E4E5DB"><td align="center"><b>TIPO DE VINCULACION</b </td><td align="center"><b>TOTAL HORAS ACTIVIDADES</b></td><td align="center"><b>TOTAL (LECTIVAS + ACTIVIDADES)</b></td><td align="center">.</td></tr>';

for($k=0;$k<$totalTipVin;$k++){
	if(!is_null($tipoVinculacion[$k])){
		echo '<tr bgcolor="#E4E5DB"><td> '.$tipoVinculacion[$k].'</td><td align="center"> '.$NroAct[$k].'</td><td align="center">'.((int)$NroAct[$k] + (int)$NroLec[$k]).'</td></td><td align="center"><b>'.$NroTip[$k].'</b></td></tr>';
	}
}

echo '</table>';
echo '</center><br>';

echo '<center><input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="width:170;cursor:pointer"></center>';
?>
</div>
</BODY>
</HTML>