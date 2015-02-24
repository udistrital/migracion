<?PHP
include_once("../clase/multiConexion.class.php");
$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion('default');

$confec = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual";

$rowsconfec = $conexion->ejecutarSQL($configuracion,$accesoOracle,$confec,"busqueda");
$fechahoy = $rowsconfec[0][0];

$QryFecIns = "SELECT TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
		TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY'),
		NVL(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'),
		NVL(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0')
		FROM accaleventos,acasperiadm
		WHERE APE_ANO = ACE_ANIO
		AND APE_PER = ACE_PERIODO
		AND APE_ESTADO = 'X'
		AND ACE_CRA_COD = 0
		AND ACE_COD_EVENTO = 21";

$RowFecIns = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFecIns,"busqueda");
$FormFecIni = $RowFecIns[0][0];
$FormFecFin = $RowFecIns[0][1];
?>