<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$QryHor = "SELECT COUNT(DPT_HORA)
	FROM acdocplantrabajo
	WHERE DPT_APE_ANO = $ano
	AND DPT_APE_PER = $per
	AND DPT_DOC_NRO_IDEN = ".$_REQUEST['HtpC'];

$RowHor = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryHor,"busqueda");
$NroAct = $RowHor[0][0];

$QryLec = "SELECT COUNT(CLE_HORA) FROM v_accargalectiva WHERE cle_doc_nro_iden = ".$_REQUEST['HtpC']." AND cle_cra_cod=".$_REQUEST['cracod'];

$RowLec = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryLec,"busqueda");
$NroLec = $RowLec[0][0];
$HorPt = $NroAct + $NroLec;
?>
