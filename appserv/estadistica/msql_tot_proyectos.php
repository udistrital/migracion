<?PHP
$QryTotProy = "SELECT tra_nivel,count(cra_cod)
	FROM actipcra, accra
	WHERE tra_cod = cra_tip_cra
	AND cra_estado = 'A'
	AND tra_estado = 'A'
	AND exists(select est_cod
	from acest
	where accra.cra_cod = est_cra_cod
	and est_estado_est in ('A','B','H','L'))
	GROUP BY tra_nivel
	ORDER BY 1";
?>