<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../docentes/fu_print_cabezote_pt.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4);
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<link href="../script/print_estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</HEAD>
<BODY style="margin-top:0">
<?php
fu_print_cabezote_pt('ACTIVIDADES DOCENTES Y PLAN DE TRABAJO');

include_once(dir_script.'class_nombres.php');
$nom = new Nombres;
require_once('msql_coor_doc_digito_pt.php');
$RowCarLec=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCarLec,"busqueda");
$RowDocPt=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryDocPt,"busqueda");

require_once('msql_coor_consulta_obs_pt.php');
$rowObs=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryObs,"busqueda");
require_once('msql_coor_cuenta_act_pt.php');

$cadena_sql="SELECT ";
$cadena_sql.="doc_apellido, ";
$cadena_sql.="doc_nombre, ";
$cadena_sql.="doc_nro_iden ";
$cadena_sql.="FROM ";
$cadena_sql.="acdocente ";
$cadena_sql.="WHERE ";
$cadena_sql.="doc_nro_iden=".$codigo." ";
$cadena_sql.="AND ";
$cadena_sql.="doc_estado = 'A' ";

$rowDocente=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");

echo'<p></p><table border="0" align="center" cellspacing="0" width="98%">
      <tr><td width="13%" align="right">Docente:</td>
      <td width="55%" align="left"><b>'.UTF8_DECODE($rowDocente[0][0]).' '.UTF8_DECODE($rowDocente[0][1]).'</b></td>
      <td width="25%" align="right">A&ntilde;o:</td>
      <td width="8%" align="left"><b>'.$ano.'</b></td></tr>
      <tr><td align="right">Identificaci&oacute;n:</td>
      <td align="left">'.$_GET['HtpC'].'</td>
      <td align="right">Per&iacute;odo:</td>
      <td align="left"><b>'.$per.'</b></td></tr>
      <tr><td align="right">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="right">Total Horas Plan de Trabajo:</td>
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
  </tr>';
$i=0;
while(isset($RowCarLec[$i][0]))
{
	print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'" style="font-size:11px">
	<td>'.$RowCarLec[$i][4].'</td>
	<td>'.$RowCarLec[$i][5].'</td>
	<td>'.$RowCarLec[$i][6].'</td>
	<td>'.$RowCarLec[$i][7].'</td>
	<td align="center">'.$RowCarLec[$i][8].'</td></tr>';
$i++;
}
print'<tr><td colspan="5" align="center"><b>TOTAL HORAS LECTIVAS: '.$NroLec.'</b></td></tr>
</table><p></p>';

//TABLA DE ACTIVIDADES
echo'<table width="98%" border="1" align="center" cellspacing="0" cellpadding="1">
<caption>ACTIVIDADES DEL PLAN DE TRABAJO</caption>
  <tr class="tr">
  	  <td align="center">Actividad</td>
	  <td align="center">D&iacute;a</td>
	  <td align="center">Hora</td>
	  <td align="center">Sede</td>
	  <td align="center">Sal&oacute;n</td>
  </tr>';
$i=0;
while(isset($RowDocPt[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'" style="font-size:11px">
	<td align="left">'.$RowDocPt[$i][1].'</td>
	<td align="center">'.$RowDocPt[$i][2].'</td>
	<td align="left">'.$RowDocPt[$i][3].'</td>
	<td align="left">'.$RowDocPt[$i][4].'</td>
	<td align="left">'.$RowDocPt[$i][5].'</td></tr>';
$i++;
}

echo '<tr><td colspan="7" bgcolor="#F4F5EB"><b>Observaci&oacute;n al plan de trabajo:</b><div align="justify">'.$rowObs[0][3].'</div></td></tr>
<tr><td colspan="7" align="center"><b>TOTAL HORAS ACTIVIDADES: '.$NroAct.'</b></td></tr>
<tr><td colspan="7" align="center"><b>TOTAL HORAS PLAN DE TRABAJO: '.$HorPt.'</b></td></tr>
<tr><td colspan="5" align="right" style="font-size:9px">Dise&ntilde;&oacute;: Oficina Asesora de Sistemas</td></tr>
</table>';
?>
<TR style="font-size:9px "></TR>
</BODY>
</HTML>