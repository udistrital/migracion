<?PHP
$qry_cierre = "SELECT dep_cod,dep_nombre, cra_cod, cra_nombre, (trim(doc_nombre)||' '||trim(doc_apellido)) emp_nombre
		FROM gedep, accra a, actipcra, acdocente
		WHERE dep_cod = cra_dep_cod
		AND dep_cod = $depcod
		AND cra_cod not in (22,97)
		AND cra_tip_cra = tra_cod
		AND tra_nivel = 'PREGRADO'
		AND cra_emp_nro_iden = doc_nro_iden (+)
		AND EXISTS (SELECT ins_cra_cod
		FROM acasperi, acins
		WHERE ape_ano = ins_ano
		AND ape_per = ins_per
		AND ape_estado = 'A'
		AND INS_ESTADO = 'A'
		AND a.cra_cod = ins_cra_cod)
		AND NOT EXISTS (SELECT ace_cra_cod
		FROM acasperi, accaleventos
		WHERE ape_ano = ace_anio
		AND ape_per = ace_periodo
		AND ape_estado = 'A'
		AND ace_cod_evento = 73
		AND a.cra_cod = ace_cra_cod)
		ORDER BY dep_cod, cra_cod ASC";
?>