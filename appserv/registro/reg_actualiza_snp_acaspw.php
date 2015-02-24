<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
$b_upd = '<button name="boton" type="submit" title="Actualizar registro" style="cursor:pointer"><img src="../img/save.png"></button>';

$QryCred = "SELECT asp_cred,asp_nro_iden_act,asp_email,asp_telefono,asp_snp
	FROM mntac.acaspw,mntac.acasperiadm
	WHERE ape_ano = asp_ape_ano
	AND ape_per = asp_ape_per
	AND ape_estado = 'X' 
	AND asp_cred = ".$_REQUEST['cred'];

$RowCred = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCred,"busqueda");
	
print'<p></p>
<form action="prog_actualiza_snp_acaspw.php" name="snpw" id="snpw" method="post">
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<caption>Actualizaci&oacute;n del SNP</caption>
		<tr align="center" class="tr">
			<td>Credencial</td>
			<td>Identificaci&oacute;n</td>
			<td>Correo Electr&oacute;nico</td>
			<td>Tel&eacute;fono</td>
			<td>SNP Icfes</td>
			<td>Grabar</td>
		</tr>
		<tr>
			<td align="center"><input name="cred" type="text" value="'.$RowCred[0][0].'" size="5" readonly></td>
			<td align="right">'.$RowCred[0][1].'&nbsp;</td>
			<td align="left">'.$RowCred[0][2].'</td>
			<td align="left">'.$RowCred[0][3].'</td>
			<td align="center"><input name="snp" type="text" value="'.$RowCred[0][4].'" size="17" maxlength="15"></td>
			<td align="center">'.$b_upd.'</td>
		</tr>
	</table>
</form>';
?>