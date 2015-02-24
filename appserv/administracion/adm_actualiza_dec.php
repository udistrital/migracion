<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
//LLAMADO DE: adm_decanos.php
?>
<html>
<link href="estilo_adm.css" rel="stylesheet" type="text/css">
<body>
<?php
fu_tipo_user(20);
fu_cabezote("DECANO");

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];
$_SESSION["codigo"] = $_GET['codigo'];

//ACTUALIZA DATOS
if($_POST['actualizar']) {
   require_once('adm_ValAdm.php');
   $_SESSION["codigo"] = $_POST['cod'];
   $upd = OCIParse($oci_conecta, "UPDATE GECLAVES
		                             SET CLA_ESTADO = :bestado
								   WHERE CLA_CODIGO =".$_POST['cod']."
								     AND CLA_TIPO_USU =".$_POST['tip']);
   OCIBindByName($upd, ":bestado", $_POST['est']);
   OCIExecute($upd, OCI_DEFAULT) or die(Ora_ErrorCode());
   OCICommit($oci_conecta);
	   
   $updoc = OCIParse($oci_conecta, "UPDATE acdocente
  									   SET doc_nombre = :bnombre,
  	  									   doc_apellido = :bapellido
									 WHERE doc_nro_iden =".$_POST['cod']);
   OCIBindByName($updoc, ":bnombre", trim($_POST['nombre']));
   OCIBindByName($updoc, ":bapellido", trim($_POST['apellido']));
   OCIExecute($updoc, OCI_DEFAULT) or die(Ora_ErrorCode());
   OCICommit($oci_conecta);
   cierra_bd($upd, $oci_conecta);
   cierra_bd($updoc, $oci_conecta);
}

//EDITA LOS DATOS
$consulta = OCIParse($oci_conecta, "SELECT cla_codigo,
										   doc_nombre, 
	   									   doc_apellido,
	   									   cla_tipo_usu,
	   									   cla_estado
  									  FROM geclaves,acdocente
 									 WHERE cla_codigo = doc_nro_iden
   									   AND cla_codigo = ".$_SESSION["codigo"]."
									   AND cla_tipo_usu = 16
 								  ORDER BY doc_nombre");
OCIExecute($consulta, OCI_DEFAULT);
$row = OCIFetch($consulta);

echo'<FORM method="post" ACTION="adm_actualiza_dec.php" name="act"><div align="center">

<table border="1" width="55%" cellspacing="0" cellpadding="0">
<tr class="tr"><td colspan="2" align="center"><span class="Estilo11">ACTUALIZACI�N DE DATOS - DECANOS</span></td></tr>
<tr class="td"><td align="left">
<input name="nombre" id="nombre" type="text" value="'.OCIResult($consulta, 2).'" size="32" onChange="javascript:this.value=this.value.toUpperCase();"></td>
<td align="left">
<input name="apellido" id="apellido" type="text" value="'.OCIResult($consulta, 3).'" size="32" onChange="javascript:this.value=this.value.toUpperCase();">
</td></tr></table>

<table border="1" width="55%" cellspacing="0" cellpadding="0">
<tr class="tr" align="center">
<td>C�digo</td>
<td>Tipo</td>
<td >Estado</td></tr>';

echo'<tr class="td" align="center">
<td><input type="text" name="cod" id="cod" size="15" value="'.OCIResult($consulta, 1).'" readonly style="text-align: right"></td>
<td><input type="text" name="tip" id="tip" size="4" value="'.OCIResult($consulta, 4).'" readonly style="text-align: right"></td>
<td><input type="text" name="est" id="est" size="4" value="'.OCIResult($consulta, 5).'" style="text-align: center" onChange="javascript:this.value=this.value.toUpperCase();"></td></tr>';

echo'</table><input type="submit" name="actualizar" value="Grabar" class="button" '.$evento_boton.'></div><br><br><br></form>';
cierra_bd($consulta, $oci_conecta);

fu_pie();
?>
</body>
</html>