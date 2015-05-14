<?PHP
$datos = "SELECT EMP_COD,
	fua_invierte_nombre(EMP_NOMBRE),
	EMP_NRO_IDEN,
	(CASE WHEN EMP_TIPO_IDEN =  1 THEN 'CEDULA DE CIUDADANIA'WHEN EMP_TIPO_IDEN =  2 THEN  'TARJETA DE IDENTIDAD' END), 
	FUA_NOMBRE_LUGAR(EMP_LUG_COD_IDEN),
	FUA_NOMBRE_LUGAR(EMP_LUG_COD_NAC),
	TO_CHAR(EMP_FECHA_NAC, 'DD-Mon-YYYY'),
	(CASE WHEN EMP_SEXO = 'M' THEN 'MASCULINO'WHEN EMP_SEXO =  'F' THEN  'FEMENINO' END),
    (CASE WHEN EMP_ESTADO_CIVIL =  1 THEN 'SOLTERO'WHEN EMP_ESTADO_CIVIL =  2 THEN 'CASADO' END),
	EMP_DIRECCION,
	FUA_NOMBRE_LUGAR(EMP_LUG_COD_DOM),
	EMP_TELEFONO,
	EMP_TELEFONO_ALT,
	TO_CHAR(EMP_DESDE, 'DD-Mon-YYYY'),
	(CASE WHEN EMP_REGIMEN = 'A' THEN 'ANTIGUO'WHEN EMP_REGIMEN =  'N' THEN  'NUEVO' END),
	CAR_NOMBRE,
	DEP_NOMBRE,
	TO_CHAR(CAR_SUELDO, '$999,999,999.99') BASICO,
	date_part('year',age(fua_fecha_sys(), EMP_FECHA_NAC)),
	date_part('year',age(fua_fecha_sys(), EMP_DESDE)),
	EMP_EMAIL
	FROM PEEMP, PECARGO, GEDEP
	WHERE EMP_NRO_IDEN = $funcod
	AND CAR_COD = EMP_CAR_COD
	AND DEP_COD = EMP_DEP_COD";
?>