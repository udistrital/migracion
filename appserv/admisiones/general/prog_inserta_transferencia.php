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
$TPrint = dir_general.'imprime_colilla_transferencia.php';
$insAcasp = OCIParse($oci_conecta, "INSERT INTO mntac.acasptransferencia(ATR_APE_ANO, 
																		   ATR_APE_PER, 
																		   ATR_CRED,   
																		   ATR_CRA_COD,
																		   ATR_TI_COD,
																			ATR_UNIVERSIDAD_PROVIENE,
																			ATR_CARRERA_PROVIENE,
																			ATR_SEMESTRE,
																			ATR_MOTIVO,
																			ATR_NACIONALIDAD, 
																			ATR_DEP_COD_NAC, 
																			ATR_MUN_COD_NAC, 
																			ATR_FECHA_NAC,
																			ATR_SEXO,
																			ATR_ESTADO_CIVIL, 
																			ATR_EMAIL,
																			ATR_NRO_IDEN_ACT, 
																			ATR_NRO_TIP_ACT, 
																			ATR_NRO_IDEN_ICFES, 
																			ATR_NRO_TIP_ICFES,
																			ATR_SNP,
																			ATR_ESTRATO,
																			ATR_DIRECCION, 
																			ATR_LOCALIDAD, 
																			ATR_TELEFONO, 
																			ATR_LOCALIDAD_COLEGIO,
																			ATR_OBSERVACION, 
																			ATR_ESTADO)
										VALUES(:banio, 
											   :bperi, 
											   :bcred,
											   :bCraCod,
											   :bticod,
											   to_char(:bUprov),
											   to_char(:bCraprov),
											   :bSempro,
											   to_char(:bmotivo),
											   to_char(:bPaisNac), 
											   :bDptoNac, 
											   :bCiudadNac,
											   to_date(:bFechaNac,'dd/mm/yyyy'),
											   to_char(:bSexo), 
											   :bEstCivil,
											   to_char(:bCtaCorreo), 
											   :bDocActual, 
											   :bTipDocAct, 
											   :bDocIcfes, 
											   :bTipDocIcfes,
											   to_char(:bsnp),
											   :bStrRes,	   	   	   	      
											   to_char(:bdir),
											   to_char(:bLocRes),
											   to_char(:btel), 
											   to_char(:bLocCol),  
											   to_char(:bobs), 
											   to_char(:bestado))");
	   
OCIBindByName($insAcasp, ":banio", $ano);
OCIBindByName($insAcasp, ":bperi", $per);
OCIBindByName($insAcasp, ":bcred", $cred);
OCIBindByName($insAcasp, ":bCraCod", $_POST['CraCodT']);
OCIBindByName($insAcasp, ":bticod", $_POST['TiCod']);
OCIBindByName($insAcasp, ":bUprov", $_POST['UdPro']);
OCIBindByName($insAcasp, ":bCraprov", $_POST['CraCur']);
OCIBindByName($insAcasp, ":bSempro", $_POST['LastSem']);
OCIBindByName($insAcasp, ":bmotivo", $_POST['motivo']);
OCIBindByName($insAcasp, ":bPaisNac", $_POST['PaisNac']);
OCIBindByName($insAcasp, ":bDptoNac", $_POST['DptoNac']);
OCIBindByName($insAcasp, ":bCiudadNac", $_POST['CiudadNac']);
OCIBindByName($insAcasp, ":bFechaNac", $_POST['FechaNac']);
OCIBindByName($insAcasp, ":bSexo", $_POST['Sexo']);
OCIBindByName($insAcasp, ":bEstCivil",  $_POST['EstCivil']);
OCIBindByName($insAcasp, ":bCtaCorreo", $_POST['CtaCorreo']);
OCIBindByName($insAcasp, ":bDocActual", $_POST['DocActual']);
OCIBindByName($insAcasp, ":bTipDocAct", $_POST['TipDocAct']);
OCIBindByName($insAcasp, ":bDocIcfes", $_POST['DocIcfes'] );
OCIBindByName($insAcasp, ":bTipDocIcfes", $_POST['TipDocIcfes']);
OCIBindByName($insAcasp, ":bsnp", $_POST['NroIcfes']);
OCIBindByName($insAcasp, ":bStrRes", $_POST['StrRes']);
OCIBindByName($insAcasp, ":bdir", $_POST['dir']);
OCIBindByName($insAcasp, ":bLocRes", $_POST['LocRes']);
OCIBindByName($insAcasp, ":btel", $_POST['tel']);
OCIBindByName($insAcasp, ":bLocCol", $_POST['LocCol']);
OCIBindByName($insAcasp, ":bobs", $_POST['obs']);
OCIBindByName($insAcasp, ":bestado", $est);

OCIExecute($insAcasp) or die(Ora_ErrorCode());
OCICommit($oci_conecta);

cierra_bd($insAcasp, $oci_conecta);
header("Location: $TPrint");
?>