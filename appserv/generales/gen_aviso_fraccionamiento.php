<?PHP
require_once('conexion/conexion.php');

//FRACCIONAMIENTO DE PAGO DE MATRÍCULA

$Qrey10 = OCIParse($oci_conecta, "SELECT 'S'
									FROM accaleventos,acasperi
								   WHERE APE_ANO = ACE_ANIO
									 AND APE_PER = ACE_PERIODO
									 AND APE_ESTADO = 'A'
									 AND ACE_COD_EVENTO = 10
									 AND TO_DATE(sysdate, 'DD/MM/YYYY') BETWEEN TO_DATE(ACE_FEC_INI, 'DD/MM/YYYY') AND TO_DATE(ACE_FEC_FIN, 'DD/MM/YYYY')");
OCIExecute($Qrey10) or die(ora_errorcode());
$rowc = OCIFetch($Qrey10);

if(OCIResult($Qrey10,1) == 'S')
   print'<p></p><center><div><a href="#" onClick="javascript:popUpWindow(\'generales/gen_graf_fraccionamiento.php\', \'no\', 100, 60, 500, 170)"><strong>FRACCIONAMIENTO DE PAGO DE MATRÍCULA</strong></a></div></center>';

OCIFreeCursor($Qrey10);
OCILogOff($oci_conecta);
?>