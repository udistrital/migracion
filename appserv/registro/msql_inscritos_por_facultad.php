<?PHP
$QryInsFac = "SELECT dep_cod cod_fac,
		dep_nombre facultad,
		cra_cod codigo,
		cra_nombre carrera,
		'ASPIRANTES' tabla,
		COUNT(asp_cred) total_inscripciones
	FROM mntac.acasperiadm, mntac.gedep, mntac.accra, mntac.acaspw
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
		'ASPIRANTES'   
	UNION
	SELECT dep_cod,
		dep_nombre,
		cra_cod,
		DECODE(cra_cod,0,'SIN CARRERA',cra_nombre),
		'REINGRESO',   
		COUNT(are_cred)
	FROM mntac.acasperiadm, mntac.gedep, mntac.accra, mntac.acaspreingreso
	WHERE ape_estado = 'X'
		AND dep_cod = ".$_REQUEST['FacCod']."
		AND dep_cod = cra_dep_cod
		AND ape_ano = are_ape_ano
		AND ape_per = are_ape_per
		AND are_cra_cursando IS NULL
		AND are_cra_transferencia IS NULL
		AND cra_cod = NVL((SELECT est_cra_cod
	FROM mntac.acest
	WHERE mntac.acaspreingreso.are_est_cod = est_cod),0)
	GROUP BY dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre,
		'REINGRESO'   
	UNION 
	SELECT dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre,
		'TRANS. INTERNA',   
		COUNT(are_cred)
	FROM mntac.acasperiadm, mntac.gedep, mntac.accra, mntac.acaspreingreso
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
		'TRANS. INTERNA'     
	UNION   
	SELECT dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre,
		'TRANS. EXTERNA',   
		COUNT(atr_cred)
	FROM mntac.acasperiadm, mntac.gedep, mntac.accra, mntac.acasptransferencia
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
		'TRANS. EXTERNA'    
	ORDER BY 1,2,3,4 ASC";
?>