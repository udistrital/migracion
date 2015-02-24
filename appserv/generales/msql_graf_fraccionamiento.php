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
   									     AND ACE_COD_EVENTO = 10");
OCIExecute($consulta15) or die(ora_errorcode());
$rowc = OCIFetch($consulta15);
$IniAd = OCIResult($consulta15, 1);
$FinAd = OCIResult($consulta15, 2);
$FecIniAddCan = OCIResult($consulta15, 4);
OCIFreeCursor($consulta15);

$Por15 = OCIParse($oci_conecta, "SELECT TO_DATE(sysdate, 'dd/mm/yyyy') - TO_DATE(ACE_FEC_INI, 'dd/mm/yyyy'),
										TO_DATE(ACE_FEC_FIN, 'dd/mm/yyyy') - TO_DATE(ACE_FEC_INI, 'dd/mm/yyyy')
								   FROM accaleventos,acasperi
								  WHERE APE_ANO = ACE_ANIO
								    AND APE_PER = ACE_PERIODO
									AND APE_ESTADO = 'A'
									AND ACE_COD_EVENTO = 10");
OCIExecute($Por15) or die(ora_errorcode());
$Row16 = OCIFetch($Por15);
$DiasTransAdd = OCIResult($Por15, 1);
$TotalDiasAdd = OCIResult($Por15, 2);
OCIFreeCursor($Por15);
$AddPor = sprintf("%1.1f",($DiasTransAdd/$TotalDiasAdd));
?>