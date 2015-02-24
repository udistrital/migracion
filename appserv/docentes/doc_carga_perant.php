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

fu_tipo_user(30);
?>
<HTML>
<HEAD><TITLE>Docentes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script> 
</HEAD>
<BODY topmargin="0" leftmargin="0">

<?php
fu_cabezote("-CARGA ACAD&Eacute;MICA-");
$cedula = $_SESSION['usuario_login'];

$estado = 'P';
$consultapa = "SELECT DOC_NRO_IDEN,
		LTRIM(doc_nombre||'  '||doc_apellido) nombre,
		dep_cod, 
		dep_nombre,
		car_cra_cod,
		cra_nombre,
		tvi_cod,
		tvi_nombre,
		CAR_CUR_ASI_COD,
		asi_nombre,
		CAR_CUR_NRO,
		cur_nro_ins,
		ape_ano,
		ape_per
		FROM accargahis,acdocente,actipvin,acasi,accra,gedep,accursohis,acdoctipvin,acasperi
		WHERE dep_cod = cra_dep_cod
		AND dtv_tvi_cod=tvi_cod
		AND asi_cod = car_cur_asi_cod 
		AND car_ape_ano = ape_ano
		AND car_ape_per = ape_per
		AND ape_estado = '$estado'
		AND car_ape_ano = cur_ape_ano
		AND car_ape_per = cur_ape_per
		AND car_cur_asi_cod = cur_asi_cod
		AND car_cur_nro = cur_nro
		AND doc_nro_iden = $cedula
		AND cra_cod = cur_cra_cod
		AND doc_estado = 'A'
		AND cra_estado = 'A'
		AND cur_asi_cod = car_cur_asi_cod
		AND cur_nro = car_cur_nro
		AND cur_ape_ano = car_ape_ano
		AND cur_ape_per = car_ape_per
		AND cur_cra_cod = car_cra_cod
		AND car_doc_nro_iden = doc_nro_iden
		AND cur_ape_ano = dtv_ape_ano
		AND cur_ape_per = dtv_ape_per
		AND car_doc_nro_iden = dtv_doc_nro_iden
		AND car_cra_cod = dtv_cra_cod
		AND cur_estado = 'A'
		AND car_estado = 'A'
		ORDER BY dep_cod, car_cra_cod, doc_nro_iden, tvi_cod, car_cur_asi_cod, car_cur_nro ASC";
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consultapa,"busqueda");
$row = $consulta;
$_SESSION["carrera"] = $consulta[0][4];

if(!is_array($consulta))
{
	die('<h3>No hay registros para esta consulta.</h3>');
	 exit;
}

echo'<p>&nbsp;</p>
<div align="center" class="Estilo5">CARGA ACAD&Eacute;MICA DEL PER&Iacute;ODO ANTERIOR</div>
<table width="90%" border="0" align="center" cellspacing="1">
	<tr>
	  <td align="right"><B>Nombre:</B></td>
	  <td><B>'.$consulta[0][1].'</B></td>
	  <td align="right"><B>Identificaci&oacute;n:</B></td>
	  <td>'.$consulta[0][0].'</td>
	</tr>
	<tr>
	  <td align="right"><B>Facultad:</B></td>
	  <td>'.$consulta[0][3].'</td>
	  <td align="right">&nbsp;</td>
	  <td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right"><B>Vinculaci&oacute;n:</B></td>
	  <td>'.$consulta[0][7].'</td>
	  <td align="right"><b>Per&iacute;odo Acad&eacute;mico:</b></td>
	  <td>'.$consulta[0][12].'-'.$consulta[0][13].'</td>
	</tr>
  </table><p></p>';
?>
  <table border="0" width="90%" align="center" cellspacing="0" cellpadding="1">
  <tr class="tr">
	<td align="center">C&oacute;digo</td>
	<td align="center">Asignatura</td>
	<td align="center">Grupo</td>
	<td align="center">Ins.</td>
	<td align="center">Proyecto Curricular</td>
  </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	 <td align="right">'.$consulta[$i][8].'</td>
	 <td>
	 <a href="print_doc_notaspar_perant.php?asicod='.$consulta[$i][8].'&asigr='.$consulta[$i][10].'&carrera='.$consulta[$i][4].'" target="principal" onMouseOver="link();return true;" onClick="link();return true;" title="Horario de la asignatura">'.$consulta[$i][9].'</a></td>
	 <td align="center">'.$consulta[$i][10].'</td>
	 <td align="center">'.$consulta[$i][11].'</td>
	 <td align="left">'.$consulta[$i][5].'</td></tr>';
$i++;
}
?>
</table>
</div>
</BODY>
</HTML>