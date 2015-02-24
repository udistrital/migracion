<?PHP
$cursor = "SELECT doc_nro_iden,
	doc_nombre||' '||doc_apellido,
	doc_estado,
	cla_estado
	FROM acdocente,geclaves
	WHERE doc_nro_iden = cla_codigo
	AND cla_tipo_usu = 30
	AND doc_estado = 'A'
	AND cla_estado = 'A'
	AND NOT EXISTS (SELECT car_doc_nro_iden
	FROM acasperi, accarga
	WHERE ape_estado = 'A'
	AND ape_ano = car_ape_ano
	AND ape_per = car_ape_per
	AND geclaves.cla_codigo = car_doc_nro_iden
	AND car_estado = 'A')
	ORDER BY 2";
?>