<?php
$qry_dec = "SELECT dep_cod, dep_nombre, emp_nro_iden, emp_nombre
	FROM gedep, peemp
	WHERE dep_emp_cod = emp_cod
	AND emp_nro_iden = $usuario
	AND emp_car_cod = 218
	AND emp_estado_e <> 'R'";
?>