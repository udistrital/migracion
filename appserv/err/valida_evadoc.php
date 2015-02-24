<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
$usuario = $_SESSION['usuario_login'];
$nivel  = $_SESSION["usuario_nivel"];

$confec = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual";

$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$confec,"busqueda");
$fechahoy = $registro[0][0];

$consulta = "SELECT TO_NUMBER(TO_CHAR(ACE_FEC_INI, 'yyyymmdd')),
	TO_NUMBER(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd')),
	TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
	TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
	FROM accaleventos, acasperi
	WHERE APE_ANO = ACE_ANIO
	AND APE_PER = ACE_PERIODO
	AND APE_ESTADO = 'A'
	AND $fechahoy BETWEEN TO_NUMBER(TO_CHAR(ACE_FEC_INI, 'yyyymmdd')) AND TO_NUMBER(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'))
	AND ACE_COD_EVENTO = 11
	AND ACE_ESTADO = 'A'";
$registro1=$conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");

if(!is_array($registro1))
{
echo "<script>location.replace('err_evadoc_fec.php')</script>";
}
else
{
	 if($_SESSION["usuario_nivel"] == 50) header("Location: ../evaldocentes/est_evaluacion.php");
	 if($_SESSION["usuario_nivel"] == 51) header("Location: ../evaldocentes/est_evaluacion.php");
	 if($_SESSION["usuario_nivel"] == 52) header("Location: ../evaldocentes/est_evaluacion.php");
	 if($_SESSION["usuario_nivel"] == 30) header("Location: ../evaldocentes/doc_evaluacion.php");
	 if($_SESSION["usuario_nivel"] ==  4) header("Location: ../evaldocentes/cor_evaluacion.php");
	 if($_SESSION["usuario_nivel"] == 16) header("Location: ../evaldocentes/dec_evaluacion.php");
}
?>