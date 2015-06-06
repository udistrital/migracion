<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$QryTotCon = "SELECT COUNT(*) FROM mntac.acrecbanasplog";
$RowsTotCon = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryTotCon,"busqueda");
$TotCon = $RowsTotCon[0][0];

$QryUsoC = "SELECT mes_cod,mes_abrev,COUNT(*),ape_ano
	FROM acasperiadm, gemes, mntac.acrecbanasplog
	WHERE ape_ano = CAST(TO_CHAR(rba_fecha,'yyyy') AS INT)
	AND ape_estado = 'X'
	AND mes_cod = CAST(TO_CHAR(rba_fecha,'mm') AS INT)
	GROUP BY mes_cod,mes_abrev,ape_ano
	ORDER BY mes_cod ASC";
	
$RowsUsoc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryUsoC,"busqueda");

$QryCont = "SELECT COUNT(*)
	FROM acasperiadm, mntac.acrecbanasplog
	WHERE ape_ano = CAST(TO_CHAR(rba_fecha,'yyyy') AS INT)
	AND ape_estado = 'X'";

$RowCont = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCont,"busqueda");
$TotAno = $RowCont[0][0];
?>