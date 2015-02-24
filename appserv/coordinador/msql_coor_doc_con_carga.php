<?PHP
//Llamado de coor_lis_docentes.php
$qry_doc = "SELECT doc_nro_iden,
	((doc_nombre)||' '||(doc_apellido)),
	doc_telefono,
	doc_email
	FROM acdocente a
	WHERE doc_estado = 'A'
	AND EXISTS (SELECT car_doc_nro
	FROM acasperi, accargas,accursos,achorarios
	WHERE a.doc_nro_iden = car_doc_nro
	AND ape_ano = cur_ape_ano
	AND ape_per = cur_ape_per
  AND car_hor_id=hor_id
  AND hor_id_curso=cur_id
	AND ape_estado = 'A'
	AND cur_cra_cod = $carrera
	AND car_estado = 'A')
ORDER BY LTRIM(doc_nombre) ASC";
?>