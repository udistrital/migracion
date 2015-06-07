<?PHP
$QryInsFac = "SELECT dep_cod cod_fac,
		dep_nombre facultad,
		cra_cod codigo,
		cra_nombre carrera,
		'ASPIRANTES' AS tabla,
		COUNT(asp_cred) total_inscripciones
	FROM mntac.acasperiadm, mntge.gedep, mntac.accra, mntac.acaspw
	WHERE ape_estado = 'X'
		AND dep_cod = ".$_REQUEST['FacCod']."
		AND dep_cod = cra_dep_cod
		AND ape_ano = asp_ape_ano
		AND ape_per = asp_ape_per
		AND cra_cod = asp_cra_cod
	GROUP BY dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre,
		tabla   
	UNION
	SELECT dep_cod,
		dep_nombre,
		cra_cod,
		(CASE WHEN cra_cod = 0 THEN 'SIN CARRERA' ELSE cra_nombre END) AS decode,
		'REINGRESO' AS tabla,
		COUNT(are_cred)
	FROM mntac.acasperiadm, mntge.gedep, mntac.accra, mntac.acaspreingreso
	WHERE ape_estado = 'X'
		AND dep_cod = ".$_REQUEST['FacCod']."
		AND dep_cod = cra_dep_cod
		AND ape_ano = are_ape_ano
		AND ape_per = are_ape_per
		AND are_cra_cursando IS NULL
		AND are_cra_transferencia IS NULL
		AND cra_cod = COALESCE((SELECT est_cra_cod
		  FROM mntac.acest
		  WHERE mntac.acaspreingreso.are_est_cod = est_cod),0)
	GROUP BY dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre,
		tabla   
	UNION 
	SELECT dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre,
		'TRANS. INTERNA' AS TABLA,   
		COUNT(are_cred)
	FROM mntac.acasperiadm, mntge.gedep, mntac.accra, mntac.acaspreingreso
	WHERE ape_estado = 'X'
		AND dep_cod = ".$_REQUEST['FacCod']."
		AND dep_cod = cra_dep_cod
		AND ape_ano = are_ape_ano
		AND ape_per = are_ape_per
		AND cra_cod = are_cra_transferencia
	GROUP BY dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre,
		TABLA     
	UNION   
	SELECT dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre,
		'TRANS. EXTERNA' AS TABLA,   
		COUNT(atr_cred)
	FROM mntac.acasperiadm, mntge.gedep, mntac.accra, mntac.acasptransferencia
	WHERE ape_estado = 'X'
		AND dep_cod = ".$_REQUEST['FacCod']."
		AND dep_cod = cra_dep_cod
		AND ape_ano = atr_ape_ano
		AND ape_per = atr_ape_per
		AND cra_cod = atr_cra_cod
	GROUP BY dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre,
		TABLA    
	ORDER BY 1,2,3,4 ASC";
?>