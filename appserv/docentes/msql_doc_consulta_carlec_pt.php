<?PHP
$QryCarLec = "SELECT cle_ape_ano,
	cle_ape_per,
	cle_doc_nro_iden,
	dac_nombre,
	cle_asi_nombre,
	dia_nombre,
	hor_larga,
	sed_nombre,
	sal_cod,
	tvi_nombre,
	decode(tvi_cod,1,'PL',6,'PL','VE')
	FROM gedia, gehora, gesede, gesalon, acdocactividad, v_accargalectiva, mntac.actipvin
	WHERE dac_cod = cle_dac_cod
	AND dia_cod = cle_dia_nro
	AND hor_cod = cle_hora
	AND sed_cod = cle_sed_cod
	AND sed_cod = sal_sed_cod
	AND sal_cod = cle_sal_cod
	AND tvi_cod = cle_tvi_cod
	AND cle_doc_nro_iden = ".$_SESSION['usuario_login']."
	ORDER BY dia_cod, hor_cod ASC";
?>
