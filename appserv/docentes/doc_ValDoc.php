<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
//session_name($usuarios_sesion);

$nu = $_SESSION["usuario_login"];
$cs = $_SESSION['usuario_password'];
$as = $_SESSION["A"];
$gr = $_SESSION["G"];
$cur=$_SESSION["cur"];
$QryValDoc = "SELECT 'S' FROM geclaves
	WHERE cla_codigo = $nu
	AND cla_tipo_usu = 30
	AND cla_estado = 'A'";
$RowValDoc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryValDoc,"busqueda");

$QryDocIden =  "SELECT 'S'
		FROM acdocente x
		WHERE doc_nro_iden = $nu
		AND doc_estado = 'A'
		AND exists (SELECT distinct car_doc_nro
		FROM ACCARGAS
		INNER JOIN ACHORARIOS ON CAR_HOR_ID=HOR_ID
		INNER JOIN ACCURSOS ON HOR_ID_CURSO=CUR_ID
		INNER JOIN ACASPERI ON APE_ANO=CUR_APE_ANO AND APE_PER=CUR_APE_PER
		WHERE ape_estado = '$estado'
		AND cur_asi_cod = $as
		AND cur_id = $cur
		AND cur_estado = 'A'
		AND car_doc_nro = x.doc_nro_iden)";
$RowDocIden = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryDocIden,"busqueda");

if($RowValDoc[0][0] != 'S' || $RowDocIden[0][0] != 'S')
{
	session_destroy();
	die('<p align="center"><b><font color="#FF0000"><u>Sesi&oacute;n Cerrada!</u></font></b></p>');
	exit;
}
?>
