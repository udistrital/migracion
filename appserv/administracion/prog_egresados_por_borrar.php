<?PHP
require_once('dir_relativo.cfg'); 
require_once(dir_conect.'valida_pag.php'); 
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(20); 

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

$del = OCIParse($oci_conecta, "DELETE geclaves
							    WHERE cla_tipo_usu = 51
								  AND cla_estado IN('A','I')
								  AND EXISTS(SELECT est_cod
											   FROM acest
											  WHERE geclaves.cla_codigo = est_cod
												AND est_estado_est = 'E')");
OCIExecute($del) or die(Ora_ErrorCode());
OCICommit($oci_conecta);
cierra_bd($del, $oci_conecta);

header("Location: $redir?error_login=27");
?>