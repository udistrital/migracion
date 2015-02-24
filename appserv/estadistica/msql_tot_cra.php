<?PHP
$QryTotCra = "SELECT dep_cod cod_fac,
		dep_nombre facultad,
		tra_nivel nivel,
		cra_cod cod_cra,
		cra_nombre carrera,
		ape_ano ano,
		ape_per per,
		COUNT(DISTINCT(est_cod)) total
		FROM gedep, accra, actipcra, acest a, acasperi b
		WHERE ape_ano = ".$_SESSION['A']."
		AND ape_per = ".$_SESSION['G']."
		AND cra_cod = ".$_SESSION['C']."
		AND dep_cod = cra_dep_cod
		AND tra_cod = cra_tip_cra
		AND tra_nivel = 'PREGRADO'
		AND cra_cod = est_cra_cod 
		AND (EXISTS (SELECT not_est_cod
		FROM acnot
		WHERE a.est_cra_cod = not_cra_cod
		AND a.est_cod = not_est_cod
		AND b.ape_ano = not_ano
		AND b.ape_per = not_per
		AND not_est_reg = 'A')
		OR EXISTS (SELECT est_cod
		FROM acesthis
		WHERE b.ape_ano = est_ano
		AND b.ape_per = est_per
		AND a.est_cra_cod = est_cra_cod
		AND a.est_cod = est_cod
		AND est_estado IN ('A','B','H','L')
		AND est_reg = 'A'))
		GROUP BY dep_cod,
		dep_nombre,
		tra_nivel,
		cra_cod,
		cra_nombre,
		ape_ano,
		ape_per		  
		UNION SELECT dep_cod cod_fac,
		dep_nombre facultad,
		tra_nivel,
		cra_cod cod_cra,
		cra_nombre carrera,
		ape_ano,
		ape_per,
		COUNT(DISTINCT(est_cod)) total
		FROM mntac.gedep, mntac.accra, mntac.actipcra, mntac.acest a, mntac.acasperi b
		WHERE ape_ano = ".$_SESSION['A']."
		AND ape_per = ".$_SESSION['G']."
		and cra_cod = ".$_SESSION['C']."
		AND dep_cod = cra_dep_cod
		AND tra_cod = cra_tip_cra
		AND tra_nivel <> 'PREGRADO'
		AND cra_cod = est_cra_cod
		AND EXISTS (SELECT not_est_cod
               FROM mntac.acnot
		WHERE a.est_cra_cod = not_cra_cod
		AND a.est_cod = not_est_cod
		AND b.ape_ano = not_ano
		AND b.ape_per = not_per
		AND not_est_reg = 'A')
		GROUP BY dep_cod,
		dep_nombre,
		tra_nivel,
		cra_cod,
		cra_nombre,
		ape_ano,
		ape_per
	ORDER BY 1,2,3,4,5,6,7 ASC";
?>