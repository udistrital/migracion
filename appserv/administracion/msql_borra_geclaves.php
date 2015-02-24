<?php
//LLAMADO DE: adm_docentes.php, adm_coordinadores.php, adm_decanos.php, adm_admon.php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(20); 

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

$del = OCIParse($oci_conecta, "DELETE geclaves 
								WHERE cla_codigo =".$_GET['codigo']."
								  AND cla_tipo_usu =".$_GET['tipo']);
OCIExecute($del) or die(Ora_ErrorCode());
OCICommit($oci_conecta);
cierra_bd($del, $oci_conecta);
header("Location: $redir");
?>