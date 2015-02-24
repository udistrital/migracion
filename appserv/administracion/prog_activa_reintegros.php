<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(20);

$redir = 'adm_usuarios_para_inactivar.php';

$UpdReintegro = OCIParse($oci_conecta, "UPDATE geclaves
										   SET cla_estado = 'A'
										 WHERE cla_tipo_usu = 51
										   AND cla_estado = 'I'
										   AND EXISTS (SELECT est_cod
														 FROM acest
														WHERE geclaves.cla_codigo=est_cod
														  AND est_estado_est IN('A','B','H','L'))");
OCIExecute($UpdReintegro) or die(Ora_ErrorCode());
OCICommit($oci_conecta);
cierra_bd($UpdReintegro, $oci_conecta);
header("Location: $redir?error_login=27");
?>