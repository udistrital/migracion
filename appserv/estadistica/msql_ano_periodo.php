<?PHP
$QryAnoPeriodo = "SELECT ape_ano||'-'||ape_per
		FROM acasperi
		WHERE ape_ano >= 2000
		AND ape_per NOT IN(2,4)
		ORDER BY 1 DESC";
?>