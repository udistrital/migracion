<?PHP
require_once('conexion/conexion.php');

//PROCESO DE EVALUACIÓN DOCENTE

$Qrey10 = OCIParse($oci_conecta, "SELECT 'S'
									FROM accaleventos,acasperi
								   WHERE APE_ANO = ACE_ANIO
									 AND APE_PER = ACE_PERIODO
									 AND APE_ESTADO = 'A'
									 AND ACE_COD_EVENTO = 11
									 AND TO_DATE(sysdate, 'DD/MM/YYYY') BETWEEN TO_DATE(ACE_FEC_INI, 'DD/MM/YYYY') AND TO_DATE(ACE_FEC_FIN, 'DD/MM/YYYY')");
OCIExecute($Qrey10) or die(ora_errorcode());
$rowc = OCIFetch($Qrey10);

if(OCIResult($Qrey10,1) == 'S')
   print'<p></p><div align="center"><a href="#" onClick="javascript:popUpWindow(\'generales/gen_graf_evadoc.php\', \'no\', 100, 60, 430, 170)"><strong>PROCESO DE EVALUACIÓN DOCENTE</strong></a></div>';

OCIFreeCursor($Qrey10);
OCILogOff($oci_conecta);
?>