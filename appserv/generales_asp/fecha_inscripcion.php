<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(50);

$QryFecIns = "SELECT TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
	TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
	FROM accaleventos,acasperiadm
	WHERE APE_ANO = ACE_ANIO
	AND APE_PER = ACE_PERIODO
	AND APE_ESTADO = 'X'
	AND ACE_CRA_COD = 0
	AND ACE_COD_EVENTO = 19";

$RowFecIns = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFecIns,"busqueda");

$FormFecIni = $RowFecIns[0][0];
$FormFecFin = $RowFecIns[0][1];
?>