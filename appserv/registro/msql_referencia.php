<?PHP
$QryRef = "SELECT ape_ano, ape_per, rba_nro_iden, rba_ref_pago,rba_dia||'/'||rba_mes||'/'||rba_ano, rba_asp_cred
	FROM acasperiadm, mntac.acrecbanasp
	WHERE ape_estado = 'X'
	AND ape_ano = rba_ape_ano
	AND ape_per = rba_ape_per
	AND rba_ref_pago = ".$_REQUEST['ref'];	
?>