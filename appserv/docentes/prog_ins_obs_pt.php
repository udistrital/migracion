<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");
require_once('valida_fecha_pt.php');

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);
$usuario = $_SESSION['usuario_login'];

if($_REQUEST['texobs']=="")
{
	echo "<script>location.replace('$redir?error_login=29')</script>";
	exit;
}
else
{
	$insobs="INSERT INTO ";
	$insobs.="acdocplantrabajobs ";
	$insobs.="(";
	$insobs.="DPO_APE_ANO, ";
	$insobs.="DPO_APE_PER, ";
	$insobs.="DPO_DOC_NRO_IDEN, ";
	$insobs.="DPO_OBS, ";
	$insobs.="DPO_ESTADO ";
	$insobs.=") ";
	$insobs.="VALUES ";
	$insobs.="( ";
	$insobs.=$ano.", ";
	$insobs.=$per.", ";
	$insobs.=$usuario.", ";
	$insobs.="'".$_REQUEST['texobs']."', ";
	$insobs.="'A' ";
	$insobs.=")";
	
	$resultado=$conexion->ejecutarSQL($configuracion,$accesoOracle,$insobs,"busqueda");
		
	if(isset($resultado))
	{
		echo "<script>location.replace('doc_adm_pt.php')</script>";
	}
	else
	{	
		echo "<script>location.replace('$redir?error_login=21')</script>";
	}
}
?>