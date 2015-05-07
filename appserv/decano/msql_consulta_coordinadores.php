<?php
$qry_coor = "SELECT cra_cod, cra_abrev, doc_nro_iden,
	(LTRIM(doc_nombre)||' '||LTRIM(doc_apellido)),doc_email
	FROM gedep, accra, acdocente
	WHERE dep_cod = $depcod
	AND dep_cod = cra_dep_cod
	AND cra_estado = 'A'
	AND cra_emp_nro_iden = doc_nro_iden
	AND doc_estado = 'A'
	ORDER BY 1 ASC";


?>