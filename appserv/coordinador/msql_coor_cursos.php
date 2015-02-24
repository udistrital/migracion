<?PHP
$QryCur = "SELECT cur_asi_cod,asi_nombre,cur_id,(lpad(cur_cra_cod,3,0)||'-'||cur_grupo) GRUPO
	FROM accursos,acasi,acasperi
	WHERE ape_ano = cur_ape_ano
	AND ape_per = cur_ape_per
	AND asi_cod = cur_asi_cod
	AND ape_estado = 'A'
	AND cur_cra_cod = ".$_REQUEST['cracod']."
	ORDER BY 2,1";
?>