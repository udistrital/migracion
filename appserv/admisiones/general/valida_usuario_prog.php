<?PHP
session_name($usuarios_sesion);
session_start();

$QryUsu = OCIParse($oci_conecta, "SELECT 'S'
									FROM acrecbanasp,acasperiadm
								   WHERE ape_ano = rba_ape_ano
									 AND ape_per = rba_ape_per
									 AND ape_estado = 'X'
									 AND rba_nro_iden = ".$_SESSION["usuario_login"]."
									 AND rba_clave = '".$_SESSION["usuario_password"]."'"); 
OCIExecute($QryUsu) or die(Ora_ErrorCode());
$RowUsu = OCIFetch($QryUsu);

if(OCIResult($QryUsu,1) != 'S'){
   session_destroy();
   die('<p>&nbsp;</p><p align="center"><b><font color="#FF0000"><u>Acceso incorrecto!</u></font></b></p>');
   exit;
}
OCIFreeCursor($QryUsu);
OCILogOff($oci_conecta);
?>