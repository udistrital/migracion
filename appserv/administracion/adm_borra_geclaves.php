<?php
//LLAMADO DE adm_docentes.php
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once('adm_ValAdm.php');

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

if($codigo == "") {
   header("Location: $redir?error_login=17");
   exit;
}
else{
    $del = OCIParse($oci_conecta, "DELETE FROM geclaves WHERE cla_codigo = $codigo");				  
	OCIExecute($del);
	OCICommit($oci_conecta);
	cierra_bd($del, $oci_conecta);
	header("Location: adm_principal.php?error_login=22");
}
?>