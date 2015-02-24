<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');

//LLAMADO DE: adm_cambiar_clave.php
fu_tipo_user(20); 

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

if($_POST['codigo'] == "") {
   header("Location: $redir?error_login=17");
   exit;
}
if($_POST['nc'] != $_POST['rnc']) {
   header("Location: $redir?error_login=12");
   exit;
}
if($_POST['nc'] == "" || $_POST['rnc'] == "") {
   header("Location: $redir?error_login=3");
   exit;
}
else{
	 $encriptaclave = md5($_POST['nc']);	 
	 $qry = OCIParse($oci_conecta, "UPDATE geclaves SET cla_clave = :bencriptaclave WHERE cla_codigo =".$_POST['codigo']);
     OCIBindByName($qry, ":bencriptaclave", $encriptaclave);
	 OCIExecute($qry) or die(Ora_ErrorCode());
	 OCICommit($oci_conecta);
	 cierra_bd($qry,$oci_conecta);
	 header("Location: $redir?error_login=13");
}
?>