<?PHP
require_once('dir_relativo.cfg'); 
require_once(dir_conect.'valida_pag.php'); 
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(20);
fu_cabezote("LOG DE ADICI&Oacute;N Y CANCELACI&Oacute;N");
?>
<HTML>
<HEAD>
<TITLE>Oficina Asesora de Sistemas</TITLE>
<script language="JavaScript" src="../script/KeyIntro.js"></script>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>

<BODY onLoad="this.document.doc.estcod.focus();">
<br><br>
<FORM NAME='doc' method="post" ACTION="<? $_SERVER['PHP_SELF'] ?>" target="_self">
<table width="298" border="0" align="center">
<tr>
<td align="right">C&oacute;digo:&nbsp;
<input name='estcod' type='text' size='15' style="text-align: right" onKeyPress="check_enter_key(event,document.getElementById('doc')); if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;">
</td>
<td><input type='Submit' value='Consultar Estudiante' class="button" <? print $evento_boton;?>></td>
<td><input type="Submit" name="all"  value="Ver todo el log" class="button" <? print $evento_boton;?>></td>
</tr>
</table>
</FORM>
<?php
if(is_numeric($_REQUEST['estcod'])){
	require_once('msql_log_addcan.php');
	$rowaddcan=$conexion->ejecutarSQL($configuracion,$accesoOracle,$addcan,"busqueda");
	
	print'<p>&nbsp;</p><table width="90%" border="1" align="center" cellpadding="2" cellspacing="0">
	  <tr class="tr">
		<th colspan="5" scope="row" align="center" class="Estilo11">LOG DEL PROCESO DE ADICI&Oacute;N Y CANCELACI&Oacute;N DE ASIGNATURAS</th>
	  </tr>
	  <tr class="td">
		<td align="right">'.$rowaddcan[0][0].'</td>
		<td colspan="4"><b>'.$rowaddcan[0][1].'</b></td>
	  </tr>
	  <tr align="center" class="tr">
		<td>Par&aacute;metro</td>
		<td>Direcci&oacute;n IP</td>
		<td>Transacci&oacute;n</td>
		<td>Fecha</td>
		<td>Hora</td>
	  </tr>';
	$i=0;
	while(isset($rowaddcan[$i][0]))
	{
		print'<tr class="td" onClick="this.className=\'raton_arr\'" onDblClick="this.className=\'raton_aba\'">
			<td>'.$rowaddcan[$i][2].'</td>
			<td>'.$rowaddcan[$i][3].'</td>
			<td>'.$rowaddcan[$i][4].'</td>
			<td>'.$rowaddcan[$i][5].'</td>
			<td>'.$rowaddcan[$i][6].'</td>
		</tr>';
		$i++;
	}
	print'</table><p>&nbsp;</p>';
}
if(isset($_REQUEST['all']))
{
	$Qrylog = "SELECT CLO_CLA_CODIGO,
		EST_NOMBRE, 
		CLO_TIPO_USU,
		CLO_IP, 
		to_char(CLO_FECHA, 'DD-Mon-YYYY'),
		CLO_HORA,
		CLO_URL,
		(CASE WHEN CLO_TRANSACCION= 'AD' THEN 'Adicion' WHEN CLO_TRANSACCION= 'CG' THEN 'Cambio de grupo' WHEN CLO_TRANSACCION= 'BO' THEN 'Cancelacion' ELSE CLO_TRANSACCION::text END),
		CLO_ESTADO 
		FROM accondorlog,acest
		WHERE CLO_CLA_CODIGO = EST_COD
		ORDER BY 5,6 ASC";
		$Rowslog = $conexion->ejecutarSQL($configuracion,$accesoOracle,$Qrylog,"busqueda");
	
		print'<table width="98%" border="0" align="center" cellpadding="2" cellspacing="0">
			<caption>LOG DEL PROCESO DE ADICI&Oacute;N Y CANCELACI&Oacute;N DE ASIGNATURAS<caption>
			<tr class="tr">
				<td align="center">#</td>
				<td align="center">C&oacute;digo</td>
				<td align="center">Nombre</td>
				<td align="center">T</td>
				<td align="center">IP</td>
				<td align="center">Fecha</td>
				<td align="center">Hora</td>
				<td align="center">Par&aacute;metro</td>
				<td align="center">Gesti&oacute;n</td>
			</tr>';
		$i=0;
		while(isset($Rowslog[$i][0]))
		{
			print'<tr class="td" onClick="this.className=\'raton_arr\'" onDblClick="this.className=\'raton_aba\'">
				<td>'.$i.'</td>
				<td>'.$Rowslog[$i][0].'</td>
				<td>'.$Rowslog[$i][1].'</td>
				<td>'.$Rowslog[$i][2].'</td>
				<td>'.$Rowslog[$i][3].'</td>
				<td>'.$Rowslog[$i][4].'</td>
				<td>'.$Rowslog[$i][5].'</td>
				<td>'.$Rowslog[$i][6].'</td>
				<td>'.$Rowslog[$i][7].'</td>
			</tr>';
			$i++;
		}
		print'</table>';
}

?>
</BODY>
</HTML>