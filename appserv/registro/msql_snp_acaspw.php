<?PHP
$QrySNP = "SELECT asp_cred,asp_nro_iden_icfes,asp_email,asp_telefono,asp_snp,ape_ano,asp_ape_ano,ape_per,asp_ape_per
	FROM mntac.acaspw a, acasperiadm
		WHERE NOT EXISTS(SELECT * 
		FROM acasp,acasperiadm 
		WHERE ape_ano = asp_ape_ano
		AND ape_per = asp_ape_per
		AND ape_estado = 'X' 
		AND a.asp_cred = acasp.asp_cred)
	AND ape_ano=asp_ape_ano
	AND ape_per=asp_ape_per
	AND ape_estado = 'X' 
	ORDER BY 1";
?>