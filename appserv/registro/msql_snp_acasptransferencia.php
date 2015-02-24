<?PHP
$QrySNPT = "SELECT atr_cred,atr_nro_iden_icfes,atr_email,atr_telefono,atr_snp,atr_ape_ano,atr_ape_per,ape_ano,ape_per
	FROM mntac.acasptransferencia a, acasperiadm
		WHERE NOT EXISTS(SELECT * 
		FROM acasp,acasperiadm 
		WHERE ape_ano = asp_ape_ano
		AND ape_per = asp_ape_per
		AND ape_estado = 'X' 
		AND a.atr_cred = acasp.asp_cred)
	AND atr_ape_ano=ape_ano
	AND atr_ape_per=ape_per
	AND ape_estado = 'X'
	ORDER BY 1";
?>