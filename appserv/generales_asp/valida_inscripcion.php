<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");
require_once(dir_conect.'fu_tipo_user.php');

/*$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");*/

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(50);

$confec = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual";
$rows=$conexion->ejecutarSQL($configuracion,$accesoOracle,$confec,"busqueda");
$fechahoy =$rows[0][0];

$consulta = "SELECT NVL(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'),
		NVL(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0'),
		TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
		TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
		FROM accaleventos,acasperiadm
		WHERE APE_ANO = ACE_ANIO
		AND APE_PER = ACE_PERIODO
		AND APE_ESTADO = 'X'
		AND ACE_CRA_COD = 0
		AND ACE_COD_EVENTO = 19";

$rowc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
$FormFecIni = $rowc[0][2];
$FormFecFin = $rowc[0][3];


if( $rowc[0][0] == "" ||  $rowc[0][1] == "")
{
	die('<br><br><p align="center"><b><font color="#0000FF" size="3">No se han programado fechas para inscripci&oacute;n de aspirantes.</font></p>');
	exit;
}
if($fechahoy <  $rowc[0][0] &&  $rowc[0][0] > '0')
{
	header("Location: ../aspirantes/err/err_asp_ini.php?fecI=$FormFecIni&fecF=$FormFecFin");
	exit;
}
elseif($fechahoy >  $rowc[0][1] &&  $rowc[0][1] > '0')
{
	   header("Location: ../aspirantes/err/err_asp_fin.php?fec=$FormFecFin");
	   //header("Location: ../generales_asp/imprime_colilla_general.php");
   	   exit;
}
elseif( $rowc[0][0] == '0' ||  $rowc[0][1] == '0')
{
	   header("Location: ../err/err_asp_sinfec");
	   exit;
}

?>
