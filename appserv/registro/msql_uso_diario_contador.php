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

$QryUsoC = "SELECT rba_ape_ano, rba_ape_per, (TO_CHAR(rba_fecha,'DD/Mon/YYYY')), COUNT(*), (TO_CHAR(rba_fecha,'YYYYMMDD'))
	FROM mntac.acasperi, mntac.acrecbanasplog 
	WHERE ape_estado = 'A' 
	AND ape_ano = rba_ape_ano 
	AND ape_per = rba_ape_per
	AND EXISTS (SELECT rba_ref_pago
	FROM mntac.acasperiadm, mntac.acrecbanasp
	WHERE ape_estado = 'X'
	AND ape_ano = rba_ape_ano
	AND ape_per = rba_ape_per
	AND rba_ref_pago = mntac.acrecbanasplog.rba_ref_pago)
	GROUP BY rba_ape_ano, rba_ape_per, (TO_CHAR(rba_fecha,'DD/Mon/YYYY')), (TO_CHAR(rba_fecha,'YYYYMMDD'))
	ORDER BY (TO_CHAR(rba_fecha,'YYYYMMDD'))";
?>