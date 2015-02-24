<?PHP
$QryEmp = "SELECT 'ADMINISTRATIVOS', COUNT(emp_nro_iden),'ADM'
	FROM peemp, pecargo
	WHERE emp_estado_e <> 'R'
	AND emp_car_cod = car_cod
	AND car_tc_cod NOT IN('DP','DC','DH','PA','PD')
	UNION 
	SELECT 'DOCENTES DE PLANTA',COUNT(emp_nro_iden),'DOP'
	FROM peemp, pecargo
	WHERE emp_estado_e <> 'R'
	AND emp_car_cod = car_cod
	AND car_tc_cod IN ('DP','DC','DH')
	UNION
	SELECT 'PENSIONADOS',COUNT(emp_nro_iden),'PEN'
	FROM peemp, pecargo
	WHERE emp_estado_e <> 'R'
	AND emp_car_cod = car_cod
	AND car_tc_cod IN ('PA','PD')
	UNION
	SELECT 'DOCENTES DE VIN. ESPECIAL',COUNT(doc_nro_iden),'DVE'
	FROM acdocente
	WHERE doc_estado = 'A'
	AND EXISTS (SELECT car_doc_nro_iden
	FROM acasperi, accarga
	WHERE ape_ano = car_ape_ano
	AND ape_per = car_ape_per
	AND acdocente.doc_nro_iden = car_doc_nro_iden
	AND ape_estado = 'A'
	AND car_estado = 'A')
	AND NOT EXISTS (SELECT emp_nro_iden
	FROM peemp, pecargo
	WHERE acdocente.doc_nro_iden = emp_nro_iden
	and emp_car_cod = car_cod
	and car_tc_cod in ('DP','DC','DH')
	and emp_estado_e <> 'R')";
?>