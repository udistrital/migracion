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

fu_tipo_user(4);
?>
<HTML>
<HEAD><TITLE>Coordinador</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script> 
</HEAD>
<BODY>
<?php
fu_cabezote("CONTROL DIGITACI&Oacute;N PLAN DE TRABAJO");
include_once(dir_script.'class_nombres.php');
$NomCra = new Nombres;

require_once('coor_lis_desp_carrera.php');

if($_REQUEST['cracod']){
	$QryDocPtsn = "SELECT DISTINCT(doc_nro_iden) cedula,
		(trim(doc_apellido)||' '||trim(doc_nombre)) nombre,
		fua_doc_digito_pt(doc_nro_iden) digito,
		mntac.fua_horas_plan_trabajo(".$_REQUEST['cracod'].",doc_nro_iden) total,		
		doc_celular celular,
		doc_email email
		FROM acasperi, accra, acdocente, accarga
		WHERE ape_estado = 'A'
		AND cra_cod = ".$_REQUEST['cracod']."
		AND ape_ano = car_ape_ano
		AND ape_per = car_ape_per
		AND cra_cod = car_cra_cod
		AND car_estado = 'A'
		AND car_doc_nro_iden = doc_nro_iden
		ORDER BY 2 ASC";

	//echo $QryDocPtsn;
	
	$RowDocPtsn = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryDocPtsn,"busqueda");
	
	print'<div align="center"><font color="#009900"><b>S</b></font>: Digit&oacute; Plan de Trabajo.&nbsp;|&nbsp;
	<font color="#FF0000"><b>N</b></font>: No digit&oacute; Plan de Trabajo.</div>';
	
	print'<p></p><table width="90%" border="1" align="center" cellpadding="2" cellspacing="0">
	  <caption><span class="Estilo5"><a href="coor_doc_totact_pt.php">VER REPORTE COMPLETO DE ACTIVIDADES</a></span><br></caption><br>
	  <tr class="tr">
		<td align="center">C&eacute;dula</td>
		<td align="center">Nombre</td>
		<td align="center">Digit&oacute</td>
		<td align="center">#Horas</td>		
		<td align="center">Celular</td>
		<td align="center">E-mail</td>
	  </tr>';
	$i=0;
	while(isset($RowDocPtsn[$i][0]))
	{
		if($RowDocPtsn[$i][2]=='S')
		{
			$sn = '<font color="#009900"><b>S</b></font>';
		}
		if($RowDocPtsn[$i][2]=='N')
		{
			$sn = '<font color="#FF0000"><b>N</b></font>';
		}
		print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
			
			<td align="right">'.$RowDocPtsn[$i][0].'</td>
			<td align="left">
				<a href="coor_doc_pt.php?HtpC='.$RowDocPtsn[$i][0].'&cracod='.$_REQUEST['cracod'].'" target="principal" onMouseOver="link();return true;" onClick="link();return true;" title="Haga clic para ve el Plan de Trabajo del docente">'.$RowDocPtsn[$i][1].'</a></td>
		
			<td align="center">'.$sn.'</td>
		
			<td align="right">'.$RowDocPtsn[$i][3].'</td>
		
			<td align="right">'.$RowDocPtsn[$i][4].'</td>		
		
			<td align="left">
			<a href="coor_form_contacto_doc.php?para='.$RowDocPtsn[$i][5].'" target="principal" onMouseOver="link();return true;" onClick="link();return true;" title="Haga clic para contactar al docente">
			'.$RowDocPtsn[$i][5].'</td>
		</tr>';
	$i++;
	}
	print'</table><p></p>';
}
?>
</body>
</html>
