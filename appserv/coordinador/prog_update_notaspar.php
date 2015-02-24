<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

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

$QryUpNp="UPDATE ";
$QryUpNp.="acnotparfec "; 
$QryUpNp.="SET ";

if($p1i<=$p1f)
{
	if($fecfinal>=$p1f)
	{	
		$QryUpNp.="NPF_IPAR1 = TO_DATE('".$_REQUEST['p1i']."','dd/mm/YYYY'), ";
		$QryUpNp.="NPF_FPAR1 = TO_DATE('".$_REQUEST['p1f']."','dd/mm/YYYY'), ";
	}
	else
	{
		echo "La feha final del parcial 1 no puede ser mayor a la fecha de cierre de digitaci&oacute;n de notas!!<br>";
	}
}
else
{
	echo "La fecha inicial del parcial 1 no puede ser mayor a la fecha final!!<br>";
}
if($p2i<=$p2f)
{
	if($fecfinal>=$p2f)
	{	
		$QryUpNp.="NPF_IPAR2 = TO_DATE('".$_REQUEST['p2i']."','dd/mm/YYYY'), ";
		$QryUpNp.="NPF_FPAR2 = TO_DATE('".$_REQUEST['p2f']."','dd/mm/YYYY'), ";
	}
	else
	{
		echo "La feha final del parcial 2 no puede ser mayor a la fecha de cierre de digitaci&oacute;n de notas!!<br>";
	}
}
else
{
	echo "La fecha inicial del parcial 2 no puede ser mayor a la fecha final!!<br>";
}
if($p3i<=$p3f)
{
	if($fecfinal>=$p3f)
	{
		$QryUpNp.="NPF_IPAR3 = TO_DATE('".$_REQUEST['p3i']."','dd/mm/YYYY'), ";
		$QryUpNp.="NPF_FPAR3 = TO_DATE('".$_REQUEST['p3f']."','dd/mm/YYYY'), ";
	}
	else
	{
		echo "La feha final del parcial 3 no puede ser mayor a la fecha de cierre de digitaci&oacute;n de notas!!<br>";
	}
}
else
{
	echo "La fecha inicial del parcial 3 no puede ser mayor a la fecha final<br>!!";
}
if($p4i<=$p4f)
{
	if($fecfinal>=$p4f)
	{
		$QryUpNp.="NPF_IPAR4 = TO_DATE('".$_REQUEST['p4i']."','dd/mm/YYYY'), ";
		$QryUpNp.="NPF_FPAR4 = TO_DATE('".$_REQUEST['p4f']."','dd/mm/YYYY'), ";
	}
	else
	{
		echo "La feha final del parcial 4 no puede ser mayor a la fecha de cierre de digitaci&oacute;n de notas!!<br>";
	}
}
else
{
	echo "La fecha inicial del parcial 4 no puede ser mayor a la fecha final!!<br>";
}
if($p5i<=$p5f)
{
	if($fecfinal>=$p5f)
	{
		$QryUpNp.="NPF_IPAR5 = TO_DATE('".$_REQUEST['p5i']."','dd/mm/YYYY'), ";
		$QryUpNp.="NPF_FPAR5 = TO_DATE('".$_REQUEST['p5f']."','dd/mm/YYYY'), ";
	}
	else
	{
		echo "La feha final del parcial 5 no puede ser mayor a la fecha de cierre de digitaci&oacute;n de notas!!<br>";
	}
}
else
{
	echo "La fecha inicial del parcial 5 no puede ser mayor a la fecha final!!<br>";
}
if($labi<=$labf)
{
	if($fecfinal>=$labf)
	{
		$QryUpNp.="NPF_ILAB = TO_DATE('".$_REQUEST['labi']."','dd/mm/YYYY'), ";
		$QryUpNp.="NPF_FLAB = TO_DATE('".$_REQUEST['labf']."','dd/mm/YYYY'), ";
	}
	else
	{
		echo "La feha final del laboratorio no puede ser mayor a la fecha de cierre de digitaci&oacute;n de notas!!<br>";
	}
}
else
{
	echo "La fecha inicial de laboratorio no puede ser mayor a la fecha final!!<br>";
}
if($exai<=$exaf)
{
	if($fecfinal>=$exaf)
	{
		$QryUpNp.="NPF_IEXA = TO_DATE('".$_REQUEST['exai']."','dd/mm/YYYY'), ";
		$QryUpNp.="NPF_FEXA = TO_DATE('".$_REQUEST['exaf']."','dd/mm/YYYY'), ";
	}
	else
	{
		echo "La feha final del ex&aacute;men no puede ser mayor a la fecha de cierre de digitaci&oacute;n de notas!!<br>";
	}
}
else
{
	echo "La fecha inicial del ex&aacute;men no puede ser mayor a la fecha final!!<br>";
}
if($habi<=$habf)
{
	if($fecfinal>=$habf)
	{
		$QryUpNp.="NPF_IHAB = TO_DATE('".$_REQUEST['habi']."','dd/mm/YYYY'), ";
		$QryUpNp.="NPF_FHAB = TO_DATE('".$_REQUEST['habf']."','dd/mm/YYYY') ";
	}
	else
	{
		echo "La feha final de la habilitaci&oacute;n no puede ser mayor a la fecha de cierre de digitaci&oacute;n de notas!!<br>";
	}
}
else
{
	echo "La fecha inicial de la habilitaci&oacute;n no puede ser mayor a la fecha final!!<br>";
}

$QryUpNp.="WHERE ";
$QryUpNp.="NPF_CRA_COD ='".$_SESSION['C']."' ";
$QryUpNp.="AND ";
$QryUpNp.="NPF_ESTADO ='A'";

$RowUpNp = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryUpNp,"busqueda");

if (isset($RowUpNp))
{
header("Location: coor_fec_notaspar.php?c=".$_SESSION['C']);
}
?>