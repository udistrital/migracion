<?php
//LLAMADO DE adm_decanos.php
$consulta = "SELECT cla_codigo,
		doc_nombre||' '||doc_apellido,
		dep_nombre,
		cla_tipo_usu,
		cla_estado
		FROM geclaves,acdocente,gedep,mntpe.peemp
		WHERE cla_codigo = doc_nro_iden
		AND cla_tipo_usu = 16
		AND cla_estado = 'A'
		and dep_emp_cod = emp_cod
		and emp_nro_iden = cla_codigo
		and emp_estado_e != 'R'
		ORDER BY 3";
?>