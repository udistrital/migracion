<?PHP
//RELACION DE CONSIGNACIONES
$QryVlrCon = "SELECT 'IGUAL A', (smi_valor*0.10), COUNT(*), MIN(rba_valor), MAX(rba_valor), SUM(rba_valor)
	FROM mntac.acasperiadm, mntac.acsalmin, mntac.acrecbanasp
	WHERE ape_estado = 'X'
		AND ape_ano = rba_ape_ano
		AND ape_per = rba_ape_per
		AND smi_estado = 'A'
		AND smi_valor*0.10 = rba_valor
	GROUP BY (smi_valor*0.10)
	UNION
	SELECT 'MAYOR A',
		(smi_valor*0.10),
		COUNT(*),
		MIN(rba_valor),
		MAX(rba_valor),   
		SUM(rba_valor)
	FROM mntac.acasperiadm, mntac.acsalmin, mntac.acrecbanasp
	WHERE ape_estado = 'X'
		AND ape_ano = rba_ape_ano
		AND ape_per = rba_ape_per
		AND smi_estado = 'A'
		AND smi_valor*0.10 < rba_valor
	GROUP BY (smi_valor*0.10)
	UNION
	SELECT 'MENOR A',
		(smi_valor*0.10),
		COUNT(*),
		MIN(rba_valor),
		MAX(rba_valor),   
		SUM(rba_valor)
	FROM mntac.acasperiadm, mntac.acsalmin, mntac.acrecbanasp
	WHERE ape_estado = 'X'
		AND ape_ano = rba_ape_ano
		AND ape_per = rba_ape_per
		AND smi_estado = 'A'
		AND smi_valor*0.10 > rba_valor
	GROUP BY (smi_valor*0.10)
	UNION
	SELECT 'TOTALES',
		NULL,
		COUNT(*),
		NULL,
		NULL,   
		SUM(rba_valor)
	FROM mntac.acasperiadm, mntac.acrecbanasp
	WHERE ape_estado = 'X'
		AND ape_ano = rba_ape_ano
		AND ape_per = rba_ape_per
	ORDER BY 1 ASC";
?>