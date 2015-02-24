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
   									   AND ACE_COD_EVENTO = 19");
OCIExecute($QryFecIns) or die(ora_errorcode());
$RowFecIns = OCIFetch($QryFecIns);
$FormFecIni = OCIResult($QryFecIns, 1);
$FormFecFin = OCIResult($QryFecIns, 2);

if(OCIResult($QryFecIns, 3) == '0' || OCIResult($QryFecIns, 4) == '0'){
   $FecIns = '<span class="Estilo13">No se han programado fechas para inscripción de aspirantes.</span>';
   $mensaje = '<p>&nbsp;</p>';
}

if($fechahoy < OCIResult($QryFecIns, 3) && OCIResult($QryFecIns, 3) > '0'){
   $FecIns = '<span class="Condor">FECHAS DE INSCRIPCIÓN: &nbsp;DEL '.$FormFecIni.'&nbsp;&nbsp;&nbsp;AL&nbsp;&nbsp;'.$FormFecFin.'</span>';
   $mensaje = '<p align="justify"><span class="Estilo13">En el evento de aparecer el error "<span class="Estilo13">Usuario no existe</span>". Tenga en cuenta que su ingreso al sistema será valido 2 ó 4 horas después del pago.<br></span></p>';
}
if($fechahoy > OCIResult($QryFecIns, 4) && OCIResult($QryFecIns, 4) > '0'){
   $FecIns = '<span class="Estilo13">El proceso de inscripción terminó el '.$FormFecFin.'</span>';
   $mensaje = '<p>&nbsp;</p>';
}

OCIFreeCursor($QryFecIns);
?>