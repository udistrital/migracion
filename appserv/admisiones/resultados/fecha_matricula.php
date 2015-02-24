<?PHP
$confec = OCIParse($oci_conecta, "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual");
OCIExecute($confec) or die(ora_errorcode());
$rows = OCIFetch($confec);
$fechahoy = OCIResult($confec, 1);
OCIFreeCursor($confec);

$QryFecIns = OCIParse($oci_conecta, "SELECT TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
										    TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY'),
										    NVL(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'),
										    NVL(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0')
  				   					  FROM accaleventos,acasperiadm
 				  					 WHERE APE_ANO = ACE_ANIO
									   AND APE_PER = ACE_PERIODO
									   AND APE_ESTADO = 'X'
									   AND ACE_CRA_COD = 0
   									   AND ACE_COD_EVENTO = 21");
OCIExecute($QryFecIns) or die(ora_errorcode());
$RowFecIns = OCIFetch($QryFecIns);
$FormFecIni = OCIResult($QryFecIns, 1);
$FormFecFin = OCIResult($QryFecIns, 2);

OCIFreeCursor($QryFecIns);
OCILogOff($oci_conecta);
?>