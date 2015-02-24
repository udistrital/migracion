<?PHP
//Llamado de coor_control_notas.php, coor_lis_carreras.php, coor_carreras_coor.php 
$qry_cra = "SELECT cra_cod, cra_abrev
	FROM accra
	WHERE
	cra_estado = 'A'
	ORDER BY cra_cod ASC";
?>