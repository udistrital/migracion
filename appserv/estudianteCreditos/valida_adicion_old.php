<?PHP
$confec = OCIParse($oci_conecta, "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual");
OCIExecute($confec) or die(ora_errorcode());
$rows = OCIFetch($confec);
$fechahoy = OCIResult($confec, 1);

$consulta = OCIParse($oci_conecta, "SELECT NVL(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'),
										   NVL(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0'),
										   TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
										   TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
  				   					  FROM accaleventos,acasperi
 				  					 WHERE APE_ANO = ACE_ANIO
									   AND APE_PER = ACE_PERIODO
									   AND APE_ESTADO = 'A'
									   AND ACE_CRA_COD = ".$_SESSION['carrera']."
   									   AND ACE_COD_EVENTO = 15");
OCIExecute($consulta) or die(ora_errorcode());
$rowc = OCIFetch($consulta);
$FormFecIni = OCIResult($consulta, 3);
$FormFecFin = OCIResult($consulta, 4);

if(OCIResult($consulta, 1) == "" || OCIResult($consulta, 2) == ""){
   die('<br><br><p align="center"><b><font color="#0000FF" size="3">No se han programado fechas para adicionar asignaturas.</font></p>');
   exit;
}
if($fechahoy < OCIResult($consulta, 1) && OCIResult($consulta, 1) > '0')
   header("Location: ../err/err_add_ini.php?fecI=$FormFecIni&fecF=$FormFecFin");

elseif($fechahoy > OCIResult($consulta, 2) && OCIResult($consulta, 2) > '0')
	   header("Location: ../err/err_add_fin.php?fec=$FormFecFin");

elseif(OCIResult($consulta, 1) == '0' || OCIResult($consulta, 2) == '0')
	   header("Location: ../err/err_add_sinfec");

cierra_bd($confec, $oci_conecta);
cierra_bd($consulta, $oci_conecta);
?>