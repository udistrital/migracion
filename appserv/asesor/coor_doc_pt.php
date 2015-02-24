<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(34);
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="Javascript">
function imprSelec(nombre)
{
var ficha = document.getElementById(nombre);
var ventimp = window.open(' ', 'popimpr');
ventimp.document.write( ficha.innerHTML );
ventimp.document.close();
ventimp.print( );
ventimp.close();
}
</script>
</HEAD>
<BODY style="margin-top:0">
<DIV ID="seleccion">
<?php
fu_cabezote("- PLAN DE TRABAJO -");
include_once(dir_script.'class_nombres.php');
$nom = new Nombres;
require_once('msql_coor_doc_digito_pt.php');

$ExeCarLec=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCarLec,"busqueda");
$rowDocPt=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryDocPt,"busqueda");

require_once('msql_coor_consulta_obs_pt.php');
$rowObs=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryObs,"busqueda");

//require_once('msql_coor_cuenta_act_pt.php');
$codigo=$_REQUEST['HtpC'];
//echo $codigo;

require_once('msql_doc_cuenta_act_pt.php');

$Tipovinculacion = "select distinct dtv_tvi_cod,tvi_nombre
from acdoctipvin,acasperi,actipvin
where ape_ano=dtv_ape_ano
and tvi_cod=dtv_tvi_cod
and ape_per=dtv_ape_per
and ape_estado='A'
and dtv_estado='A'
and dtv_doc_nro_iden=$codigo";
$registro1=$conexion->ejecutarSQL($configuracion,$accesoOracle,$Tipovinculacion,"busqueda");

$nomdoc="SELECT LTRIM(doc_nombre||'  '||doc_apellido) FROM acdocente WHERE doc_nro_iden = $codigo AND doc_estado = 'A'";
$rownomdoc=$conexion->ejecutarSQL($configuracion,$accesoOracle,$nomdoc,"busqueda");
$Nombre=$rownomdoc[0][0];

echo'<p></p><table border="0" align="center" cellspacing="0" width="98%">
    <tr><td width="13%" align="left">Docente:</td>
      <td width="55%" align="left"><b>'.$Nombre.'</b></td>
      <td width="25%" align="left">A&ntilde;o:</td>
      <td width="8%" align="left"><b>'.$ano.'</b></td></tr>
    <tr><td align="left">Identificaci&oacute;n:</td>
      <td align="left">'.$_REQUEST['HtpC'].'</td>
      <td align="left">Per&iacute;odo:</td>
      <td align="left"><b>'.$per.'</b></td></tr>
	<tr><td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">Total Horas Plan de Trabajo:</td>
      <td align="left">'.$HorPt.'</td></tr>  
	  </table><p></p>';

//TABLA DE CARGA LECTIVA
print'<table width="98%" border="1" align="center" cellpadding="0" cellspacing="0">
<caption>CARGA LECTIVA</caption>
  <tr class="tr">
    <td width="40%" align="center">Asignatura</td>
    <td width="10%" align="center">D&iacute;a</td>
    <td width="10%" align="center">Hora</td>
    <td width="35%" align="center">Sede</td>
    <td width="5%" align="center">Sal&oacute;n</td>
    <td width="55%" align="center">Carrera</td>
    <td width="55%" align="center">Tip. vin.</td>
  </tr>';
$i=0;
while(isset($ExeCarLec[$i][0]))
{
	print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'" style="font-size:11px">
	<td>'.$ExeCarLec[$i][4].'</td>
	<td>'.$ExeCarLec[$i][5].'</td>
	<td>'.$ExeCarLec[$i][6].'</td>
	<td>'.$ExeCarLec[$i][7].'</td>
	<td align="center">'.$ExeCarLec[$i][8].'</td>
	<td align="center">'.$ExeCarLec[$i][9].'</td>
	<td align="center">'.$ExeCarLec[$i][10].'</td></tr>';
$i++;
}
echo '</table><p></p>';

echo '<br><center>';

echo '<table style="border-collapse:collapse;border-color:black;"  border="1">';
echo '<tr bgcolor="#E4E5DB" class="raton_aba" style="font-size: 11px;"><td align="center"><b>Tip. vin.</b></td><td align="center"><b>TIPO DE VINCULACION</b </td><td align="center"><b>TOTAL HORAS LECTIVAS</b></td></tr>';

$contarTipVin = "SELECT count(*) FROM actipvin";
$registroTipVin=$conexion->ejecutarSQL($configuracion,$accesoOracle,$contarTipVin,"busqueda");
$totalTipVin=$registroTipVin[0][0];

for($k=0;$k<$totalTipVin;$k++){
	if(!is_null($tipoVinculacion[$k])){
		echo '<tr bgcolor="#E4E5DB" class="raton_aba" style="font-size: 11px;"><td align="center"><b>'.$NroTip[$k].'</b></td><td> '.$tipoVinculacion[$k].'</td><td align="center"> '.$NroLec[$k].'</td></td></tr>';
	}
}
echo '<tr class="raton_aba" style="font-size: 11px;"><td colspan="4" align="center"><b>PL</b> = PLANTA,  <b>VE</b> = VINCULACI&Oacute;N ESPECIAL</td></tr>';
echo '</table>';
echo '</center><br>';

//TABLA DE ACTIVIDADES
echo '<table width="98%" border="1" align="center" cellspacing="0" cellpadding="1">
<caption>ACTIVIDADES DEL PLAN DE TRABAJO</caption>
  <tr class="tr">
  	  <td align="center">Actividad</td>
	  <td align="center">D&iacute;a</td>
	  <td align="center">Hora</td>
	  <td align="center">Sede</td>
	  <td align="center">Sal&oacute;n</td>
	  <td align="center">Tip. vin.</td>
  </tr>';
$i=0;
while(isset($rowDocPt[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'" style="font-size:11px">
	<td align="left">'.$rowDocPt[$i][1].'</td>
	<td align="center">'.$rowDocPt[$i][2].'</td>
	<td align="left">'.$rowDocPt[$i][3].'</td>
	<td align="left">'.$rowDocPt[$i][4].'</td>
	<td align="left">'.$rowDocPt[$i][5].'</td>
	<td align="center">'.$rowDocPt[$i][6].'</td>
	</tr>';
$i++;
}

echo '<tr><td colspan="8" bgcolor="#E4E5DB"><strong>Observaci&oacute;n a su plan de trabajo:</strong><div align="justify">'.$registro4[0][3].'</div></td></tr></table>';
echo '<br><center>';
echo '<table style="border-collapse:collapse;border-color:black;"  border="1">';
echo '<tr bgcolor="#E4E5DB" class="raton_aba" style="font-size: 11px;"><td align="center"><b>Tip. vin.</b></td><td align="center"><b>TIPO DE VINCULACION</b </td><td align="center"><b>TOTAL HORAS ACTIVIDADES</b></td><td align="center"><b>TOTAL (LECTIVAS + ACTIVIDADES)</b></td></tr>';

for($k=0;$k<$totalTipVin;$k++){
	if(!is_null($tipoVinculacion[$k])){
		echo '<tr bgcolor="#E4E5DB" class="raton_aba" style="font-size: 11px;"><td align="center"><b>'.$NroTip[$k].'</b></td><td> '.$tipoVinculacion[$k].'</td><td align="center"> '.$NroAct[$k].'</td><td align="center">'.((int)$NroAct[$k] + (int)$NroLec[$k]).'</td></td></tr>';
	}
}
echo '<tr class="raton_aba" style="font-size: 11px;"><td colspan="4" align="center"><b>PL</b> = PLANTA,  <b>VE</b> = VINCULACI&Oacute;N ESPECIAL</td></tr>';
echo '</table>';
echo '</center><br>
</table>';
?>
</DIV>
<a href="javascript:imprSelec('seleccion')" >
<center><img src="../img/impresora.gif" border="0"/>Imprimir el Plan de Trabajo.</center><br><br>
</a>
</BODY>
</HTML>
