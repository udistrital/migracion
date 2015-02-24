<?PHP
 require('../conexion/conexion.php');

$QryCred = OCIParse($oci_conecta, "SELECT rba_nro_iden, rba_clave, DECODE(asp_cred,NULL,'N','S')
									FROM mntac.acasperiadm, mntac.acrecbanasp, mntac.acaspw
									WHERE ape_ano = rba_ape_ano
									   AND ape_per = rba_ape_per
									   AND ape_estado = 'X'
									   AND rba_nro_iden = ".$_SESSION["usuario_login"]."
									   and rba_clave = '".$_SESSION["usuario_password"]."'
									   AND rba_ape_ano = asp_ape_ano (+)
									   AND rba_ape_per = asp_ape_per (+)
									   AND rba_asp_cred = asp_cred (+)
									UNION 
									SELECT rba_nro_iden,
									   rba_clave,
									   DECODE(are_cred,NULL,'N','S')
									FROM mntac.acasperiadm, mntac.acrecbanasp, mntac.acaspreingreso
									WHERE ape_ano = rba_ape_ano
									   AND ape_per = rba_ape_per
									   AND ape_estado = 'X'
									   AND rba_nro_iden = ".$_SESSION["usuario_login"]."
									   and rba_clave = '".$_SESSION["usuario_password"]."'
									   AND rba_ape_ano = are_ape_ano (+)
									   AND rba_ape_per = are_ape_per (+)
									   AND rba_asp_cred = are_cred (+)
									UNION
									SELECT rba_nro_iden,
									   rba_clave,
									   DECODE(atr_cred,NULL,'N','S')
									FROM mntac.acasperiadm, mntac.acrecbanasp, mntac.acasptransferencia
									WHERE ape_ano = rba_ape_ano
									   AND ape_per = rba_ape_per
									   AND ape_estado = 'X'
									   AND rba_nro_iden = ".$_SESSION["usuario_login"]."
									   and rba_clave = '".$_SESSION["usuario_password"]."'
									   AND rba_ape_ano = atr_ape_ano (+)
									   AND rba_ape_per = atr_ape_per (+)
									   AND rba_asp_cred = atr_cred (+)"); 
OCIExecute($QryCred) or die(Ora_ErrorCode());
$RowCred = OCIFetch($QryCred);

do{
   if(OCIResult($QryCred,3) == "S"){
	  header("Location: ../general/imprime_colilla_general.php");
      exit;
   }
}while($RowCred = OCIFetch($QryCred));

OCIFreeCursor($QryCred);
OCILogOff($oci_conecta);
?>