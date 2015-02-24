<?PHP
include_once("../clase/multiConexion.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$confec ="SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual";
$registro1 =$conexion->ejecutarSQL($configuracion,$accesoOracle,$confec,"busqueda");
$fechahoy = $registro1[0][0];

$usuario = $_SESSION['usuario_login'];
$nivel  = $_SESSION["usuario_nivel"];

$cod_consulta = "SELECT TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
		TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
		FROM accaleventos,acasperi
		WHERE APE_ANO = ACE_ANIO
		AND APE_PER = ACE_PERIODO
		AND APE_ESTADO = 'A'
		AND ACE_CRA_COD = 0
		AND ACE_COD_EVENTO = 41
		AND $fechahoy BETWEEN TO_NUMBER(TO_CHAR(ACE_FEC_INI, 'yyyymmdd')) AND TO_NUMBER(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'))
		AND ACE_ESTADO = 'A'";

$resultado =$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consulta,"busqueda");
$variable=$resultado[0][0];

if(!is_array($resultado))
{
   echo "<script>location.replace(' ../err/err_pt_fec.php')</script>";	
   exit;
}

require_once('msql_doc_consulta_carlec_pt.php');
if(!is_array($resultado))
{
   echo "<script>location.replace(' ../err/err_sin_carga_lectiva.php')</script>";
   exit;
}
?>