<?PHP
$conbas = "SELECT EB_NOMBRE_INST, 
	(CASE WHEN EB_TIPO_ESTUDIO =  1 THEN 'PRIMARIA' WHEN EB_TIPO_ESTUDIO = 2 THEN 'CLASICA' WHEN EB_TIPO_ESTUDIO = 3 THEN 'NORMAL' WHEN EB_TIPO_ESTUDIO = 4 THEN 'INDUSTRIAL' WHEN EB_TIPO_ESTUDIO = 5 THEN 'COMERCIAL' WHEN EB_TIPO_ESTUDIO = 6 THEN 'AGRICOLA' WHEN EB_TIPO_ESTUDIO = 7 THEN 'EXTERIOR' WHEN EB_TIPO_ESTUDIO = 8 THEN 'RADIO' WHEN EB_TIPO_ESTUDIO = 9 THEN 'VALIDACI&Oacute;N' END),
    (CASE WHEN	EB_JORNADA = 'D' THEN 'DIURNA' WHEN     EB_JORNADA = 'N' THEN 'NOCTURNA' END),
	EB_ULT_GRADO, 
	(CASE WHEN EB_ESTADO_AVAN = 1 THEN 'ESTUDIANDO' WHEN EB_ESTADO_AVAN = 2 THEN 'SUSPENDIDO' WHEN EB_ESTADO_AVAN = 3 THEN 'TERMINO' WHEN EB_ESTADO_AVAN = 4 THEN 'GRADUADO' END),
	EB_ANO_TERMINO
	FROM mntpe.PEESTBAS
	WHERE EB_EMP_COD = ".$_SESSION["fun_cod"];

$consup = "SELECT INS_NOMBRE, 
	PRG_NOMBRE, 
	(CASE WHEN ES_TIPO_ESTUDIO = 1 THEN 'TECNICO' WHEN ES_TIPO_ESTUDIO = 2 THEN 'TECNOLOGICO' WHEN ES_TIPO_ESTUDIO = 3 THEN 'ESP.TECNOLOGICO' WHEN ES_TIPO_ESTUDIO = 4 THEN 'PREGRADO' WHEN ES_TIPO_ESTUDIO = 5 THEN 'POSGRADO' WHEN ES_TIPO_ESTUDIO = 6 THEN 'MAESTRIA' WHEN ES_TIPO_ESTUDIO = 7 THEN 'DOCTORADO' END),
    (CASE WHEN ES_JORNADA = 'D' THEN 'DIURNA' WHEN ES_JORNADA = 'N' THEN 'NOCTURNA' END),
	ES_ANO_DESDE, 
	(CASE WHEN ES_ESTADO_AVAN = 1 THEN 'ESTUDIANDO' WHEN ES_ESTADO_AVAN = 2 THEN 'SUSPENDIDO' WHEN ES_ESTADO_AVAN = 3 THEN 'TERMINO' WHEN ES_ESTADO_AVAN = 4 THEN 'GRADUADO' END), 
	to_char(ES_FECHA_GRADO, 'DD-Mon-YYYY'), 
	ES_RESOL_CONVAL, 
	ES_FECHA_RESOL
	FROM mntpe.PEESTSUP, GEINST, GEPROG
	WHERE ES_EMP_COD = ".$_SESSION["fun_cod"]."
	AND INS_COD = COALESCE(ES_INS_COD,0)
	AND PRG_COD = COALESCE(ES_PRG_COD,0)";
?>