<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4);

require_once('valida_capfec_notaspar.php');

$fefi = explode("/", $fechafin);
$fecfinal=mktime($fefi[2],$fefi[1],$fefi[0]);

$fec1i = explode("/", $_REQUEST['p1i']);
$p1i=mktime($fec1i[2],$fec1i[1],$fec1i[0]);

$fec1f = explode("/", $_REQUEST['p1f']);
$p1f=mktime($fec1f[2],$fec1f[1],$fec1f[0]);

$fec2i = explode("/", $_REQUEST['p2i']);
$p2i=mktime($fec2i[2],$fec2i[1],$fec2i[0]);

$fec2f = explode("/", $_REQUEST['p2f']);
$p2f=mktime($fec2f[2],$fec2f[1],$fec2f[0]);

$fec3i = explode("/", $_REQUEST['p3i']);
$p3i=mktime($fec3i[2],$fec3i[1],$fec3i[0]);

$fec3f = explode("/", $_REQUEST['p3f']);
$p3f=mktime($fec3f[2],$fec3f[1],$fec3f[0]);

$fec4i = explode("/", $_REQUEST['p4i']);
$p4i=mktime($fec4i[2],$fec4i[1],$fec4i[0]);

$fec4f = explode("/", $_REQUEST['p4f']);
$p4f=mktime($fec4f[2],$fec4f[1],$fec4f[0]);

$fec5i = explode("/", $_REQUEST['p5i']);
$p5i=mktime($fec5i[2],$fec5i[1],$fec5i[0]);

$fec5f = explode("/", $_REQUEST['p5f']);
$p5f=mktime($fec5f[2],$fec5f[1],$fec5f[0]);

$fecli = explode("/", $_REQUEST['labi']);
$labi=mktime($fecli[2],$fecli[1],$fecli[0]);

$feclf = explode("/", $_REQUEST['labf']);
$labf=mktime($feclf[2],$feclf[1],$feclf[0]);

$fecei = explode("/", $_REQUEST['exai']);
$exai=mktime($fecei[2],$fecei[1],$fecei[0]);

$fecef = explode("/", $_REQUEST['exaf']);
$exaf=mktime($fecef[2],$fecef[1],$fecef[0]);

$fechi = explode("/", $_REQUEST['habi']);
$habi=mktime($fechi[2],$fechi[1],$fechi[0]);

$fechf = explode("/", $_REQUEST['habf']);
$habf=mktime($fechf[2],$fechf[1],$fechf[0]);

$estado='A';

if(($p1i>$p1f)||($p2i>$p2f)||($p3i>$p3f)||($p4i>$p4f)||($p5i>$p5f)||($labi>$labf)||($exai>$exaf)||($habi>$habf))
  
{
	echo "La fecha inicial no puede ser mayor a la fecha final!!";
}
elseif(($fecfinal<$p1f)||($fecfinal<$p2f)||($fecfinal<$p3f)||($fecfinal<$p4f)||($fecfinal<$p5f)||($fecfinal<$labf)||($fecfinal<$exaf)||($fecfinal<$habf))
{
	echo "La feha final no puede ser mayor a la fecha de cierre de digitaci&oacute;n de notas!!";
}
else
{
	$QryInNp="INSERT ";
	$QryInNp.="INTO ";
	$QryInNp.="acnotparfec ";
	$QryInNp.="VALUES (";
	$QryInNp.="'".$_SESSION['C']."', ";
	$QryInNp.="TO_DATE('".$_REQUEST['p1i']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['p1f']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['p2i']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['p2f']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['p3i']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['p3f']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['p4i']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['p4f']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['p5i']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['p5f']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['labi']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['labf']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['exai']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['exaf']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['habi']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['habf']."','dd/mm/YYYY'), ";
	$QryInNp.="'".$estado."', ";
	$QryInNp.="TO_DATE('".$_REQUEST['habi']."','dd/mm/YYYY'), ";
	$QryInNp.="TO_DATE('".$_REQUEST['habf']."','dd/mm/YYYY'))";
//echo $QryInNp;
}
$RowInNp = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryInNp,"busqueda");

if (isset($RowInNp))
{
header("Location: coor_fec_notaspar.php?c=".$_SESSION['C']);
}
?>