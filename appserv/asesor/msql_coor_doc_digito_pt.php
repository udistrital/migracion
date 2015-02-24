<?PHP
$QryDocPt = "SELECT DPT_DAC_COD,
	DAC_NOMBRE, 
	DIA_NOMBRE,
	HOR_LARGA,
	SED_ABREV,
	SAL_DESCRIP,
	decode(tvi_cod,1,'PL',6,'PL','VE')
	FROM ACDOCPLANTRABAJO,ACDOCACTIVIDAD,ACASPERI,ACDOCENTE,GEDIA,GEHORA,GESEDE,GESALON, mntac.actipvin
	WHERE APE_ANO = DPT_APE_ANO
	AND APE_PER = DPT_APE_PER
	AND APE_ESTADO = 'A'
	AND DOC_NRO_IDEN = DPT_DOC_NRO_IDEN
	AND DPT_DOC_NRO_IDEN = ".$_REQUEST['HtpC']."
	AND DOC_ESTADO = 'A'
	AND DAC_COD = DPT_DAC_COD 
	AND DAC_ESTADO = 'A'
	AND DIA_COD = DPT_DIA_NRO
	AND DIA_ESTADO = 'A'
	AND HOR_COD = DPT_HORA
	AND HOR_ESTADO = 'A'
	AND SED_COD = DPT_SED_COD
	AND SED_ESTADO = 'A'
	AND DPT_ESTADO = 'A'
	AND SAL_SED_COD = DPT_SED_COD
	AND SAL_COD = DPT_SAL_COD
	AND SAL_ESTADO = 'A'
	AND tvi_cod = dpt_tvi_cod
	ORDER BY DPT_DIA_NRO,DPT_HORA";

$QryCarLec = "SELECT cle_ape_ano,
	cle_ape_per,
	cle_doc_nro_iden,
	dac_nombre,
	cle_asi_nombre,
	dia_nombre,
	hor_larga,
	sed_nombre,
	sal_cod,
	cra_nombre,
	decode(tvi_cod,1,'PL',6,'PL','VE')
	FROM gedia, gehora, gesede, gesalon, acdocactividad, v_accargalectiva, accra, mntac.actipvin
	WHERE dac_cod = cle_dac_cod
	AND dia_cod = cle_dia_nro
	AND hor_cod = cle_hora
	AND sed_cod = cle_sed_cod
	AND sed_cod = sal_sed_cod
	AND sal_cod = cle_sal_cod
	AND cle_doc_nro_iden = ".$_REQUEST['HtpC']."
	AND cle_cra_cod = cra_cod
	AND tvi_cod = cle_tvi_cod
	ORDER BY dia_cod, hor_cod ASC";  
	
	//echo $QryCarLec;
?>
