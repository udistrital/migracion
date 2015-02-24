<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(20);

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

$UpdUsuNoEst = OCIParse($oci_conecta, "UPDATE geclaves
										  SET cla_estado = 'A'
										WHERE cla_tipo_usu != 51
										  AND cla_estado = 'W'");
OCIExecute($UpdUsuNoEst) or die(Ora_ErrorCode());
OCICommit($oci_conecta);
cierra_bd($UpdUsuNoEst, $oci_conecta);
header("Location: $redir?error_login=27");
?>