<?PHP
$cursor = "SELECT emp_nro_iden,
	emp_nombre,
	emp_estado_e,
	cla_estado
	FROM mntpe.peemp, geclaves
	WHERE emp_nro_iden = cla_codigo
	AND cla_tipo_usu = 24
	AND emp_estado_e = 'R'
	AND cla_estado = 'A'
	ORDER BY 1";
?>