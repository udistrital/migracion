<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");
require_once(dir_conect.'fu_tipo_user.php');

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(50);

$QryCred ="SELECT rba_asp_cred 
	FROM acrecbanasp 
	WHERE rba_nro_iden = ".$_SESSION["usuario_login"]."
	AND rba_clave = '".$_SESSION["usuario_password"]."'";


$RowCred = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCred,"busqueda");
$credencial = $RowCred[0][0];

$QryColillaGen_2 = "SELECT APE_ANO ANIO,
APE_PER PERIODO,
ASP_CRED CREDENCIAL,
CRA_COD CODIGO,
 CRA_NOMBRE CARRERA,
TI_COD TIP_INS,
TI_NOMBRE INSCRIPCION,
 ASP_APELLIDO APELLIDOS,
 ASP_NOMBRE NOMBRES,
 ASP_NRO_IDEN IDENTIFICACION,
 DECODE(ASP_SEXO,'F','FEMENINO','M','MASCULINO') SEXO,
 DECODE(ASP_SER_MILITAR,'S','SI','N','NO') SERV_MILITAR,  LOC_NOMBRE
LOCALIDAD,  STR_NRO ESTRATO,  MED_NOMBRE MEDIO_DE_PUBLICIDAD,  ASP_SNP SNP,
 DECODE(ASP_TIP_ICFES,'N','NUEVO','A','ANTIGUO',ASP_TIP_ICFES) TIPO_ICFES,
ASP_CIE_SOC CIENCIAS_SOCIALES,  ASP_BIO BIOLOGIA,  ASP_QUI QUIMICA,  ASP_FIS
FISICA,  ASP_SOC SOCIALES, ASP_APT_VERBAL APT_VERBAL,  ASP_ESP_Y_LIT
ESP_Y_LIT,  ASP_APT_MAT APT_MAT,  ASP_CON_MAT CON_MAT,  ASP_FIL FILOSOFIA,
ASP_HIS HISTORIA,  ASP_GEO GEOGRAFIA,  ASP_IDIOMA IDIOMA,  
 (SELECT ELE_NOMBRE
  FROM ACELECT
  WHERE ASP_ELE_COD = ELE_COD) ELECTIVA,  
 ASP_ESTRATO_COSTEA 
 FROM ACASPERIADM,  ACLOCALIDAD,  ACESTRATO,  ACMEDIO,  ACCRA,  ACTIPINS,
MNTAC.acasp WHERE APE_ESTADO = 'X'
 AND ASP_CRED =$credencial
 AND APE_ANO = ASP_APE_ANO
 AND APE_PER = ASP_APE_PER
 AND MED_COD = NVL(ASP_MED_COD,7)
 AND CRA_COD = ASP_CRA_COD
 AND TI_COD = ASP_TI_COD
 AND ASP_LOCALIDAD = LOC_NRO
 AND APE_ANO = LOC_APE_ANO
 AND APE_PER = LOC_APE_PER
 AND LOC_ESTADO = 'A'
 AND ASP_ESTRATO = STR_NRO
  AND APE_ANO = STR_APE_ANO
 AND APE_PER = STR_APE_PER";

$RowColillaGen=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryColillaGen_2,"busqueda");

if(!is_array($RowColillaGen))
{
	header("Location: ../err/err_consulta_inscripcion.php");
}

?>

