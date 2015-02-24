<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');

$consulta15 = OCIParse($oci_conecta, "SELECT NVL(TO_CHAR(ACE_FEC_INI, 'yyyy-mm-dd'), '0'),
										     NVL(TO_CHAR(ACE_FEC_FIN, 'yyyy-mm-dd'), '0'),
										     TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
										     TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
  				   					    FROM accaleventos,acasperi
 				  					   WHERE APE_ANO = ACE_ANIO
									     AND APE_PER = ACE_PERIODO
										 AND APE_ESTADO = 'A'
									     AND ACE_CRA_COD = ".$_SESSION['carrera']."
   									     AND ACE_COD_EVENTO = 15");
OCIExecute($consulta15) or die(ora_errorcode());
$rowc = OCIFetch($consulta15);
$IniAd = OCIResult($consulta15, 1);
$FinAd = OCIResult($consulta15, 2);
$FecIniAddCan = OCIResult($consulta15, 3);
OCIFreeCursor($consulta15);

$consulta16 = OCIParse($oci_conecta, "SELECT NVL(TO_CHAR(ACE_FEC_INI, 'yyyy-mm-dd'), '0'),
										     NVL(TO_CHAR(ACE_FEC_FIN, 'yyyy-mm-dd'), '0'),
										     TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
										     TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
  				   					    FROM accaleventos,acasperi
 				  					   WHERE APE_ANO = ACE_ANIO
									     AND APE_PER = ACE_PERIODO
										 AND APE_ESTADO = 'A'
									     AND ACE_CRA_COD = ".$_SESSION['carrera']."
   									     AND ACE_COD_EVENTO = 16");
OCIExecute($consulta16) or die(ora_errorcode());
$Row16 = OCIFetch($consulta16);
$IniCa = OCIResult($consulta16, 1);
$FinCa = OCIResult($consulta16, 2);
OCIFreeCursor($consulta16);

$Por15 = OCIParse($oci_conecta, "SELECT TO_DATE(sysdate, 'dd/mm/yyyy') - TO_DATE(ACE_FEC_INI, 'dd/mm/yyyy'),
										TO_DATE(ACE_FEC_FIN, 'dd/mm/yyyy') - TO_DATE(ACE_FEC_INI, 'dd/mm/yyyy')
								   FROM accaleventos,acasperi
								  WHERE APE_ANO = ACE_ANIO
								    AND APE_PER = ACE_PERIODO
									AND APE_ESTADO = 'A'
									AND ACE_CRA_COD = ".$_SESSION['carrera']."
									AND ACE_COD_EVENTO = 15");
OCIExecute($Por15) or die(ora_errorcode());
$Row16 = OCIFetch($Por15);
$DiasTransAdd = OCIResult($Por15, 1);
$TotalDiasAdd = OCIResult($Por15, 2);
OCIFreeCursor($Por15);
$AddPor = sprintf("%1.1f",($DiasTransAdd/$TotalDiasAdd));

$Por16 = OCIParse($oci_conecta, "SELECT TO_DATE(sysdate, 'dd/mm/yyyy') - TO_DATE(ACE_FEC_INI, 'dd/mm/yyyy'),
										TO_DATE(ACE_FEC_FIN, 'dd/mm/yyyy') - TO_DATE(ACE_FEC_INI, 'dd/mm/yyyy')
								   FROM accaleventos,acasperi
								  WHERE APE_ANO = ACE_ANIO
									AND APE_PER = ACE_PERIODO
								    AND APE_ESTADO = 'A'
									AND ACE_CRA_COD = ".$_SESSION['carrera']."
									AND ACE_COD_EVENTO = 16");
OCIExecute($Por16) or die(ora_errorcode());
$Row16 = OCIFetch($Por16);
$DiasTransCan = OCIResult($Por16, 1);
$TotalDiasCan = OCIResult($Por16, 2);
OCIFreeCursor($Por16);
$CanPor = sprintf("%1.1f",($DiasTransCan/$TotalDiasCan));
OCILogOff($oci_conecta);
?>