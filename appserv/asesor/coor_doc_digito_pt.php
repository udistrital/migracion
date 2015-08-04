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

fu_tipo_user(34);
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
				doc_email email,
				esa_sueldo
			FROM acasperi,accra,acdocente,accargas,achorarios,accursos,peemp,prempsal
			WHERE ape_estado = 'A'
				AND cra_cod = ".$_REQUEST['cracod']."
                                AND acasperi.ape_ano = cur_ape_ano
                                AND acasperi.ape_per = cur_ape_per
                                AND acdocente.doc_nro_iden = car_doc_nro
                                AND car_estado = 'A'
                                AND car_hor_id=hor_id
                                AND hor_id_curso=cur_id
                                AND cur_estado = 'A'
                                AND hor_estado = 'A'
                                AND cra_cod = cur_cra_cod
                                AND car_doc_nro = doc_nro_iden
                                AND car_doc_nro = emp_nro_iden
			UNION       
			SELECT DISTINCT(doc_nro_iden) cedula,
				(trim(doc_apellido)||' '||trim(doc_nombre)) nombre,
				fua_doc_digito_pt(doc_nro_iden) digito,
				mntac.fua_horas_plan_trabajo(".$_REQUEST['cracod'].",doc_nro_iden) total,      
				doc_celular celular,
				doc_email email,
				null::numeric
			
			FROM acasperi,accra,acdocente,accargas,achorarios,accursos
			WHERE ape_estado = 'A'
				AND cra_cod = ".$_REQUEST['cracod']."
                                AND acasperi.ape_ano = cur_ape_ano
                                AND acasperi.ape_per = cur_ape_per
                                AND acdocente.doc_nro_iden = car_doc_nro
                                AND car_estado = 'A'
                                AND car_hor_id=hor_id
                                AND hor_id_curso=cur_id
                                AND cur_estado = 'A'
                                AND hor_estado = 'A'
                                AND cra_cod = cur_cra_cod
                                AND car_doc_nro = doc_nro_iden
				AND car_doc_nro NOT IN (SELECT emp_nro_iden
							FROM pecargo, peemp
							WHERE emp_estado_e <> 'R'
								AND emp_car_cod = car_cod
								AND car_tc_cod IN ('DP','DC','DH'))
			ORDER BY 2 ASC";


	//echo $QryDocPtsn;
	
	$RowDocPtsn = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryDocPtsn,"busqueda");
	
	print'<div align="center"><font color="#009900"><b>S</b></font>: Digit&oacute; Plan de Trabajo.&nbsp;|&nbsp;
	<font color="#FF0000"><b>N</b></font>: No digit&oacute; Plan de Trabajo.
	|&nbsp;	<b>V/E</b>: Vinculaci&oacute;n especial.</div>';
	
	print'<p></p><table width="90%" border="1" align="center" cellpadding="2" cellspacing="0">
	  <caption><span class="Estilo5">REPORTE DE ACTIVIDADES</a></span><br></caption><br>
	  <tr class="tr">
		<td align="center">C&eacute;dula</td>
		<td align="center">Nombre</td>
		<td align="center">Digit&oacute</td>
		<td align="center">#Horas</td>		
		<td align="center">Celular</td>
		<td align="center">E-mail</td>
		<td align="center">Sueldo b&aacute;sico</td>
	  </tr>';
	$i=0;
	while(isset($RowDocPtsn[$i][0]))
	{
		$moneda=$RowDocPtsn[$i][6];
		setlocale(LC_MONETARY, 'en_US');

		if($RowDocPtsn[$i][2]=='S')
		{
			$sn = '<font color="#009900"><b>S</b></font>';
		}
		if($RowDocPtsn[$i][2]=='N')
		{
			$sn = '<font color="#FF0000"><b>N</b></font>';
		}
		print '<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
			
			<td align="right">'.$RowDocPtsn[$i][0].'</td>
			<td align="left">'.$RowDocPtsn[$i][1].'</td>
			<td align="center">'.$sn.'</td>
			<td align="right">'.$RowDocPtsn[$i][3].'</td>
			<td align="right">'.$RowDocPtsn[$i][4].'</td>		
			<td align="left">'.$RowDocPtsn[$i][5].'</td>';
			if($RowDocPtsn[$i][6] == NULL)
			{
				echo '<td align="right"> V/E </td>';
			}
			else
			{
				echo '<td align="right">'; echo money_format('$ %!.0i', $moneda); echo '</td>';
			}	
		echo '</tr>';
	$i++;
	}
	print'</table><p></p>';
}
?>
</body>
</html>
