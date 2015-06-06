<?PHP
$QryEst = "SELECT dep_cod,
	dep_nombre,
	cra_cod,
	cra_nombre,
	ape_ano,
	ape_per,
	estado_cod,
	estado_nombre,
	COUNT(DISTINCT(est_cod))
	FROM acasperi a, acestado, gedep, accra, acest b
	WHERE dep_cod = cra_dep_cod
	AND cra_cod = est_cra_cod 
	AND cra_cod = ".$_SESSION['C']."
	AND est_estado_est NOT IN ('A','B','H','L','E','N')
	AND estado_cod = est_estado_est
	AND ape_ano = ".$_SESSION['A']."
	AND ape_per = ".$_SESSION['G']."
	AND EXISTS (SELECT not_est_cod
	FROM acnot
	WHERE b.est_cod = not_est_cod
	AND a.ape_ano = not_ano
	AND a.ape_per = not_per
	AND not_est_reg = 'A')
	AND NOT EXISTS (SELECT not_est_cod
	FROM acnot
	WHERE b.est_cod = not_est_cod
	AND (CASE WHEN a.ape_per=1 THEN (cast((a.ape_ano::text||a.ape_per::text) as integer)+2) WHEN a.ape_per=3 
 	THEN (cast((a.ape_ano::text||a.ape_per::text) as integer)+8) END ) = cast((not_ano::text||not_per::text) as integer))			
	AND NOT EXISTS (SELECT egr_est_cod
	FROM acegresado
	WHERE egr_est_cod = b.est_cod)
	AND NOT EXISTS (SELECT est_cod
	FROM acesthis
	WHERE b.est_cod = est_cod
	AND a.ape_ano = est_ano
	AND a.ape_per = est_per
	AND est_estado_est IN('H','L')
	AND est_reg = 'A')
	GROUP BY dep_cod,
	dep_nombre,
	cra_cod,
	cra_nombre,
	ape_ano,
	ape_per, 
	estado_cod,
	estado_nombre
	ORDER BY dep_cod,
	cra_cod,
	ape_ano,
	ape_per,
	cra_nombre, 
	estado_cod";

?>