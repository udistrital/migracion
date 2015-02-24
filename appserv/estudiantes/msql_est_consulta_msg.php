<?PHP
require_once('dir_relativo.cfg');
require_once(dir_script.'CodigoEstCraCod.php');

$qry_msg = OCIParse($oci_conecta, "SELECT CME_AUTOR, 
										  CME_TITULO, 
										  TO_CHAR(CME_FECHA_INI,'dd/Mon/yyyy'),
										  CME_HORA_INI, 
										  TO_CHAR(CME_FECHA_FIN,'dd/Mon/yyyy'), 
										  CME_MENSAJE
								     FROM accoormensaje
								    WHERE CME_CRA_COD = $EstCraCod
									  AND CME_TIPO_USU IN(0,51)
									  AND TO_NUMBER(TO_CHAR(sysdate,'yyyymmdd')) BETWEEN TO_NUMBER(TO_CHAR(CME_FECHA_INI,'yyyymmdd')) AND TO_NUMBER(TO_CHAR(CME_FECHA_FIN,'yyyymmdd'))");


OCIExecute($qry_msg) or die(ora_errorcode());
$row_msg = OCIFetch($qry_msg);
?>