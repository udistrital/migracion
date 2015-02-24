<?PHP
$cursor = "SELECT est_cod,
	est_nombre,
	cra_abrev,
	est_estado_est,
	cla_estado
	FROM acest,geclaves,accra
	WHERE cla_codigo = est_cod
	AND cra_cod = est_cra_cod
	AND cra_estado = 'A'
	AND cla_tipo_usu = 51
	AND cla_estado = 'A'
	AND est_estado_est NOT IN('A','B','H','L')
	ORDER BY 3,2";
?>