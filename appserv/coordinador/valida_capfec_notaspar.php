<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

if(isset($_REQUEST['cracod']))
   $_SESSION['C'] = $_REQUEST['cracod'];
//echo  "mmm".$_REQUEST['cracod'];;
$confechoy = "SELECT TO_CHAR(CURRENT_DATE,'YYYYMMDD')";
$rowfechoy = $conexion->ejecutarSQL($configuracion,$accesoOracle,$confechoy,"busqueda");
$fechahoy = $rowfechoy[0][0];

$confechas = "SELECT TO_CHAR(ACE_FEC_INI, 'YYYYMMDD'),
		TO_CHAR(ACE_FEC_FIN, 'YYYYMMDD'),
		TO_CHAR(ACE_FEC_FIN, 'DD/MM/YYYY')
		FROM ACCALEVENTOS,ACASPERI
		WHERE APE_ANO = ACE_ANIO
		AND APE_PER = ACE_PERIODO
		AND APE_ESTADO = 'A'
		AND ACE_CRA_COD = ".$_SESSION['C']."
		AND ACE_COD_EVENTO = 7
		AND ACE_ESTADO = 'A'";
//echo $confechas;		
$rowconfechas = $conexion->ejecutarSQL($configuracion,$accesoOracle,$confechas,"busqueda");

$fechafin=$rowconfechas[0][2];

if(($fechahoy < $rowconfechas[0][0] || $fechahoy > $rowconfechas[0][1]) || ($rowconfechas[0][0] == " ") || $rowconfechas[0][1]== " ")
{
	die('<h3>La captura de fechas para notas parciales est&aacute; cerrada.</h3>');
	//header("Location: ../err/err_cap_fecpar.php"); 
	exit;
}
?>