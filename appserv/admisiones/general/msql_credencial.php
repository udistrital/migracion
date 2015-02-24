<?PHP
session_name($usuarios_sesion);
session_start();
//TRAE LA CREDENCIAL
$QryCred = OCIParse($oci_conecta, "SELECT rba_asp_cred
									 FROM acrecbanasp, acasperiadm
								    WHERE ape_ano = rba_ape_ano
									  AND ape_per = rba_ape_per
									  AND ape_estado = 'X'
									  AND rba_nro_iden =  ".$_SESSION["usuario_login"]."
									  AND rba_clave = '".$_SESSION["usuario_password"]."'"); 
OCIExecute($QryCred) or die(Ora_ErrorCode());
$RowCred = OCIFetch($QryCred);
if($RowCred == ""){
   die('<p>&nbsp;</p><p align="center"><b><font color="#FF0000">No se ha generado una credencial.</font></b></p>');
   exit;
}
else $cred = OCIResult($QryCred,1);
OCIFreeCursor($QryCred);
?>