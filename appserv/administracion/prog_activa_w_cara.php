<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(20);
$redir = 'adm_usuarios_para_inactivar.php';
/*
if(!is_integer($_POST['cracod'])){
   die('<aviso>Seleccione el Proyecto Curricular.</aviso>');
   exit;
}
else */
$cracod = $_POST['cracod'];

$UpdeActEstEnW = OCIParse($oci_conecta, "UPDATE geclaves
										    SET cla_estado = 'A'
										  WHERE cla_tipo_usu = 51 
										    AND cla_estado = 'W'
										    AND exists(SELECT * 
													     FROM acest,accra
													    WHERE geclaves.cla_codigo = est_cod
													      AND cra_cod = est_cra_cod
													      AND cra_cod = $cracod)");
OCIExecute($UpdeActEstEnW) or die(Ora_ErrorCode());
OCICommit($oci_conecta);
cierra_bd($UpdeActEstEnW, $oci_conecta);
header("Location: $redir?error_login=27");
?>