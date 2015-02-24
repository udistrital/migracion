<?PHP
//INGRESOS POR TIPO DE INSCRIPCION
$QryTipIns = "SELECT 'ASPIRANTES',COUNT(asp_cred)
	FROM mntac.acasperiadm, mntac.acaspw
	WHERE ape_estado = 'X'
		AND ape_ano = asp_ape_ano
		AND ape_per = asp_ape_per
	UNION 
	SELECT 'REINGRESO / TRANS. INTERNA',COUNT(are_cred)
	FROM mntac.acasperiadm, mntac.acaspreingreso
	WHERE ape_estado = 'X'
		AND ape_ano = are_ape_ano
		AND ape_per = are_ape_per
	UNION
	SELECT 'TRANSFERENCIA EXTERNA',COUNT(atr_cred)
	FROM mntac.acasperiadm, mntac.acasptransferencia
	WHERE ape_estado = 'X'
		AND ape_ano = atr_ape_ano
		AND ape_per = atr_ape_per
	ORDER BY 1 ASC";
?>