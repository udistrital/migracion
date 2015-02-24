<?PHP
$QryValor = OCIParse($oci_conecta, "SELECT (smi_valor*0.10) FROM acsalmin WHERE smi_estado = 'A'"); 
OCIExecute($QryValor) or die(Ora_ErrorCode());
$RowValor = OCIFetch($QryValor);
$VlrInscripcion = OCIResult($QryValor,1);
OCIFreeCursor($QryValor);
?>