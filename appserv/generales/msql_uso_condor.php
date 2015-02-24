<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$QryTotCon = "SELECT COUNT(*) FROM geconexlog";
$RowsTotCon = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryTotCon,"busqueda");
$TotCon = $QryTotCon[0][0];

$QryUsoC = "SELECT mes_cod,mes_abrev,COUNT(*),ape_ano
	FROM acasperi, gemes, geconexlog 
	WHERE ape_ano = TO_NUMBER(TO_CHAR(cnx_fecha,'yyyy'))
	AND ape_estado = 'A'
	AND mes_cod = TO_CHAR(cnx_fecha,'mm')
	GROUP BY mes_cod,mes_abrev,ape_ano
	ORDER BY mes_cod ASC";

$rowsUsoC = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryUsoC,"busqueda");

$QryCont = "SELECT COUNT(*)
	FROM acasperi, geconexlog 
	WHERE ape_ano = TO_NUMBER(TO_CHAR(cnx_fecha,'yyyy'))
	AND ape_estado = 'A'";

$RowCont = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCont,"busqueda");
$TotAno = $RowCont[0][0];
?>