<?php
$cod_consul = "SELECT distinct DOC_NRO_IDEN,
		LTRIM(doc_nombre||'  '||doc_apellido) nombre,
		dep_cod, 
		dep_nombre,
		cur_cra_cod,
		cra_nombre,
		car_tip_vin,
		tvi_nombre,
		cur_asi_cod,
		asi_nombre,
		(lpad(cur_cra_cod,3,0)||'-'||cur_grupo),
		cur_nro_ins,
		tra_nivel,
                cur_id
		FROM accargas,acdocente,actipvin,acasi,accra a,gedep,accursos,achorarios,acasperi b,ACTIPCRA
		WHERE dep_cod = cra_dep_cod
		AND car_tip_vin = tvi_cod
		AND asi_cod = cur_asi_cod 
		AND cur_ape_ano = ape_ano
		AND cur_ape_per = ape_per
		AND ape_estado = 'A'
		AND car_hor_id = hor_id
		AND hor_id_curso=cur_id
                AND doc_nro_iden = $cedula
                AND cra_cod = cur_cra_cod
		AND doc_estado = 'A'
		AND cra_estado = 'A'
		AND car_doc_nro = doc_nro_iden
		AND cur_estado = 'A'
		AND car_estado = 'A'
		AND cra_tip_cra=tra_cod
		ORDER BY dep_cod, cur_cra_cod, cur_asi_cod, cur_id ASC
";
?>