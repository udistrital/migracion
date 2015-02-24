<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_general.'msql_ano_per.php');

require_once(dir_general.'valida_usuario_prog.php');
require_once(dir_general.'valida_http_referer.php');
require_once(dir_general.'valida_formulario_formulario.php');
require_once(dir_general.'valida_inscripcion.php');

require_once(dir_general.'msql_credencial.php');

$est = 'A';
$RPrint = dir_general.'imprime_colilla_reingreso.php';

$insAcasp = OCIParse($oci_conecta, "INSERT INTO mntac.acaspreingreso(ARE_APE_ANO, 
																	   ARE_APE_PER, 
																	   ARE_CRED, 
																	   ARE_TI_COD,
																	   ARE_NRO_IDEN, 
																	   ARE_EST_COD, 
																	   ARE_CANCELO_SEM, 
																	   ARE_MOTIVO_RETIRO, 
																	   ARE_TELEFONO, 
																	   ARE_EMAIL, 
																	   ARE_CRA_CURSANDO, 
																	   ARE_CRA_TRANSFERENCIA, 
																	   ARE_ESTADO)
											VALUES(:banio, 
												   :bperi, 
												   :bcred,
												   :bticod,
												   :bDocAct,
												   :bestcod,
												   to_char(:bcansem),
												   to_char(:bmotretiro),
												   to_char(:btelefono),
												   to_char(:bcorreo),
												   :bcracur,
												   :bcratranfer,
												   to_char(:bestado))");
	   
OCIBindByName($insAcasp, ":banio", $ano);
OCIBindByName($insAcasp, ":bperi", $per);
OCIBindByName($insAcasp, ":bcred", $cred);
OCIBindByName($insAcasp, ":bticod", $_POST['TipoIns']);
OCIBindByName($insAcasp, ":bDocAct", $_POST['DocActual']);
OCIBindByName($insAcasp, ":bestcod", $_POST['EstCod']);
OCIBindByName($insAcasp, ":bcansem", $_POST['CanSem']);
OCIBindByName($insAcasp, ":bmotretiro", $_POST['MotRetiro']);
OCIBindByName($insAcasp, ":btelefono", $_POST['tel']);
OCIBindByName($insAcasp, ":bcorreo", $_POST['CtaCorreo']);
OCIBindByName($insAcasp, ":bcracur", $_POST['CraCod']);
OCIBindByName($insAcasp, ":bcratranfer", $_POST['TCraCod']);
OCIBindByName($insAcasp, ":bestado", $est);

OCIExecute($insAcasp) or die(Ora_ErrorCode());
OCICommit($oci_conecta);

cierra_bd($insAcasp, $oci_conecta);
header("Location: $RPrint");
?>