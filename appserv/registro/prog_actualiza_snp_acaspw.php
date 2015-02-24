<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once('msql_ano_per.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(33);

$UpdSnp="UPDATE ";
$UpdSnp.="acaspw ";
$UpdSnp.="SET ";
$UpdSnp.="asp_snp='".$_REQUEST['snp']."' ";
$UpdSnp.="WHERE ";
$UpdSnp.="asp_ape_ano='".$ano."' ";
$UpdSnp.="AND ";
$UpdSnp.="asp_ape_per='".$per."' ";
$UpdSnp.="AND ";
$UpdSnp.="asp_cred ='".$_REQUEST['cred']."'";

$rowSnp = $conexion->ejecutarSQL($configuracion,$accesoOracle,$UpdSnp,"busqueda");

if(isset($rowSnp))
{
	header("Location: reg_snp_acaspw.php");
}
?>