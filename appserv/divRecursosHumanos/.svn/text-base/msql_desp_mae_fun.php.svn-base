<?PHP
$maestro = "SELECT emp_cod,
	emp_nombre,
	emp_nro_iden,
	car_nombre,
	emp_dep_cod,
	dep_nombre,
	esa_sueldo,
	max(liq_dias) dias,
	lne_valor
	FROM mntpe.peemp, mntpe.pecargo, mntpe.prliquid, mntpe.empsal, gedep, mntpe.liquidaneto
	WHERE emp_car_cod = car_cod
	AND emp_cod = ".$_SESSION["fun_cod"]."
	AND emp_cod = liq_emp_cod
	AND emp_cod = esa_cod
	AND emp_dep_cod = dep_cod
	AND emp_cod = lne_emp_cod(+)
	GROUP BY emp_dep_cod, dep_nombre, emp_cod,emp_nombre, emp_nro_iden, car_nombre, esa_sueldo, lne_valor
	ORDER BY emp_dep_cod ASC";
?>