<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');

session_name($usuarios_sesion);
session_start();

$confec = OCIParse($oci_conecta, "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual");
OCIExecute($confec, OCI_DEFAULT) or die(Ora_ErrorCode());
$rows = OCIFetch($confec);
$fechahoy = OCIResult($confec, 1);
OCIFreeCursor($confec);

$consulta = OCIParse($oci_conecta,"SELECT TO_NUMBER(TO_CHAR(ACE_FEC_INI, 'yyyymmdd')),
										  TO_NUMBER(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd')),
										  TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
										  TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
									 FROM accaleventos, acasperi
									WHERE APE_ANO = ACE_ANIO
									  AND APE_PER = ACE_PERIODO
									  AND APE_ESTADO = 'A'
									  AND $fechahoy BETWEEN TO_NUMBER(TO_CHAR(ACE_FEC_INI, 'yyyymmdd')) AND TO_NUMBER(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'))
									  AND ACE_COD_EVENTO = 11
									  AND ACE_ESTADO = 'A'");
OCIExecute($consulta, OCI_DEFAULT) or die(Ora_ErrorCode());
$Row = OCIFetch($consulta);

if($Row != 1) header("Location: err_evadoc_fec.php");
else{
	 if($_SESSION["usuario_nivel"] == 51) header("Location: ../ev08prueba/est_evaluacion.php");
	 if($_SESSION["usuario_nivel"] == 30) header("Location: ../ev08prueba/doc_evaluacion.php");
	 if($_SESSION["usuario_nivel"] ==  4) header("Location: ../ev08prueba/cor_evaluacion.php");
	 if($_SESSION["usuario_nivel"] == 16) header("Location: ../ev08prueba/dec_evaluacion.php");
}
   
OCIFreeCursor($consulta);
?>