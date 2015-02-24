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
$APrint = dir_general.'imprime_colilla_acasp.php';
$insAcasp = OCIParse($oci_conecta, "INSERT INTO mntac.acaspw(ASP_APE_ANO, 
																ASP_APE_PER, 
																ASP_CRED, 
																ASP_MED_COD, 
																ASP_VECES, 
																ASP_CRA_COD,
																ASP_TI_COD, 
																ASP_NACIONALIDAD, 
																ASP_DEP_COD_NAC, 
																ASP_MUN_COD_NAC, 
																ASP_FECHA_NAC,
																ASP_SEXO,
																ASP_ESTADO_CIVIL, 
																ASP_EMAIL,
																ASP_NRO_IDEN_ACT, 
																ASP_NRO_TIP_ACT, 
																ASP_NRO_IDEN_ICFES, 
																ASP_NRO_TIP_ICFES,
																ASP_SNP,
																ASP_ESTRATO,
																ASP_DIRECCION, 
																ASP_LOCALIDAD, 
																ASP_TELEFONO, 
																ASP_LOCALIDAD_COLEGIO,
																ASP_OBSERVACION, 
																ASP_ESTADO)

VALUES(:banio, 
       :bperi, 
	   :bcred,
	   :bMedPub, 
	   :bSePresentaPor, 
	   :bCraCod, 
	   :bTipoIns, 
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
OCIBindByName($insAcasp, ":bMedPub", $_POST['MedPub']);
OCIBindByName($insAcasp, ":bSePresentaPor", $_POST['SePresentaPor']);
OCIBindByName($insAcasp, ":bCraCod", $_POST['CraCod']);
OCIBindByName($insAcasp, ":bTipoIns", $_POST['TipoIns']);
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
header("Location: $APrint");
?>