
<?PHP
$QryInsFac = "SELECT dep_cod cod_fac,
		dep_nombre facultad,
		cra_cod codigo,
		cra_nombre carrera,
		'ASPIRANTES' tabla,
		COUNT(asp_cred) total_inscripciones
		FROM mntac.acasperiadm, mntge.gedep, accra, mntac.acaspw
		WHERE ape_estado = 'X'
		AND dep_cod = ".$_REQUEST['FacCod']."
		AND dep_cod = cra_dep_cod
		AND ape_ano = asp_ape_ano
		AND ape_per = asp_ape_per
		AND cra_cod = asp_cra_cod
		GROUP BY dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre
		UNION
		SELECT dep_cod,
		dep_nombre,
		cra_cod,
		CASE WHEN cra_cod=0 THEN 'SIN CARRERA' ELSE cra_nombre END,
		'REINGRESO',   
		COUNT(are_cred)
		FROM mntac.acasperiadm, mntge.gedep, accra, mntac.acaspreingreso
		WHERE ape_estado = 'X'
		AND dep_cod = ".$_REQUEST['FacCod']."
		AND dep_cod = cra_dep_cod
		AND ape_ano = are_ape_ano
		AND ape_per = are_ape_per
		AND are_cra_cursando IS NULL
		AND are_cra_transferencia IS NULL
		AND cra_cod = coalesce((SELECT est_cra_cod
		FROM acest
		WHERE mntac.acaspreingreso.are_est_cod = est_cod),0)
		GROUP BY dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre
		UNION 
		SELECT dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre,
		'TRANS. INTERNA',   
		COUNT(are_cred)
		FROM mntac.acasperiadm, mntge.gedep, accra, mntac.acaspreingreso
		WHERE ape_estado = 'X'
		AND dep_cod = ".$_REQUEST['FacCod']."
		AND dep_cod = cra_dep_cod
		AND ape_ano = are_ape_ano
		AND ape_per = are_ape_per
		AND cra_cod = are_cra_transferencia
		GROUP BY dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre 
		UNION   
		SELECT dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre,
		'TRANS. EXTERNA',   
		COUNT(atr_cred)
		FROM mntac.acasperiadm, mntge.gedep, accra, mntac.acasptransferencia
		WHERE ape_estado = 'X'
		AND dep_cod = ".$_REQUEST['FacCod']."
		AND dep_cod = cra_dep_cod
		AND ape_ano = atr_ape_ano
		AND ape_per = atr_ape_per
		AND cra_cod = atr_cra_cod
		GROUP BY dep_cod,
		dep_nombre,
		cra_cod,
		cra_nombre   
		ORDER BY 1,2,3,4 ASC";
	
?>