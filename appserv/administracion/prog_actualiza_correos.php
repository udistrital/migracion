<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(20);

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

require_once('msql_consulta_correos_est.php');

do{
   $EstCod = OCIResult($Qrycorreo,1);
   $CorreoIns = OCIResult($Qrycorreo,4);

   $UpdCorreos = OCIParse($oci_conecta, "UPDATE acestotr
											SET eot_email = :bcorreo_ins
										  WHERE eot_cod = $EstCod
										    AND eot_email IS NULL
   											AND eot_email_ins IS NOT NULL");
   OCIBindByName($UpdCorreos, ":bcorreo_ins", $CorreoIns);
   OCIExecute($UpdCorreos) or die(Ora_ErrorCode());
}while(OCIFetch($Qrycorreo));
OCICommit($oci_conecta);
cierra_bd($UpdCorreos, $oci_conecta);
OCIFreeCursor($Qrycorreo);
header("Location: $redir?error_login=27");
?>