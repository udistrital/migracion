<?PHP
$conbas = "SELECT EB_NOMBRE_INST, 
	DECODE(EB_TIPO_ESTUDIO, 1,'PRIMARIA',2,'CLASICA',3,'NORMAL',4,'INDUSTRIAL',5,'COMERCIAL',6,'AGRICOLA',7,'EXTERIOR',8,'RADIO',9,'VALIDACI�N'), 
	DECODE(EB_JORNADA,'D','DIURNA','N','NOCTURNA'),  
	EB_ULT_GRADO, 
	DECODE(EB_ESTADO_AVAN,1,'ESTUDIANDO',2,'SUSPENDIDO',3,'TERMINO',4,'GRADUADO'), 
	EB_ANO_TERMINO
	FROM mntpe.PEESTBAS
	WHERE EB_EMP_COD = ".$_SESSION["fun_cod"];

$consup = "SELECT INS_NOMBRE, 
	PRG_NOMBRE, 
	DECODE(ES_TIPO_ESTUDIO,1,'TECNICO',2,'TECNOLOGICO',3,'ESP.TECNOLOGICO',4,'PREGRADO',5,'POSGRADO',6,'MAESTRIA',7,'DOCTORADO'),
	DECODE(ES_JORNADA,'D','DIURNA','N','NOCTURNA'), 
	ES_ANO_DESDE, 
	DECODE(ES_ESTADO_AVAN,1,'ESTUDIANDO',2,'SUSPENDIDO',3,'TERMINO',4,'GRADUADO'), 
	to_char(ES_FECHA_GRADO, 'DD-Mon-YYYY'), 
	ES_RESOL_CONVAL, 
	ES_FECHA_RESOL
	FROM mntpe.PEESTSUP, GEINST, GEPROG
	WHERE ES_EMP_COD = ".$_SESSION["fun_cod"]."
	AND INS_COD = NVL(ES_INS_COD,0)
	AND PRG_COD = NVL(ES_PRG_COD,0)";
?>