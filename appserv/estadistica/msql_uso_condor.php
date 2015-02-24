<?PHP
$QryUsoCondor = "SELECT mes_nombre, '$dir'||ape_ano||(lpad(mes_cod,2,0))||'.PDF'
		FROM acasperi,gemes
		WHERE ape_estado = 'A'
		AND mes_cod BETWEEN 1 
		AND (SELECT (MAX(TO_NUMBER(TO_CHAR(cnx_fecha,'mm')))-1) FROM geconexlog)
		ORDER BY mes_cod ASC";
?>