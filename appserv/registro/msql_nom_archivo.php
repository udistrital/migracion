<?PHP
$QryPartNom = "SELECT '$dir'||ape_ano||ape_per||'.pdf', ape_ano||'-'||ape_per
	FROM acasperi
	WHERE ape_ano >= 2000
	AND ape_per NOT IN(2,4)
	AND ape_estado != 'X'
	ORDER BY 1 DESC";

?>