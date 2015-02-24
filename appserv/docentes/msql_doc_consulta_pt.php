<?PHP
$cod_consulta = "SELECT DPT_APE_ANO, 
		DPT_APE_PER, 
		DPT_DOC_NRO_IDEN, 
		DAC_NOMBRE, 
		DIA_ABREV, 
		HOR_LARGA, 
		SED_ABREV, 
		SAL_DESCRIP, 
		DPT_FECHA, 
		DPT_ESTADO,
		DIA_COD,
		HOR_COD,
		DPT_DAC_COD,
		DAC_INTENSIDAD,
		tvi_nombre,
		decode(tvi_cod,1,'PL',6,'PL',0,'SD','VE')
		FROM acdocplantrabajo, acdocactividad,gedia,gehora,gesede,gesalon,actipvin
		WHERE DPT_APE_ANO = $ano
		AND DPT_APE_PER = $per
		AND DPT_DOC_NRO_IDEN = ".$_SESSION['usuario_login']."
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
		and tvi_cod=dpt_tvi_cod
		ORDER BY 11,12";
?>
