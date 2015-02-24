<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'fu_tipo_user.php');
require_once('msql_ano_per.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(33);

$UpdSnp="UPDATE ";
$UpdSnp.="acasptransferencia ";
$UpdSnp.="SET ";
$UpdSnp.="atr_snp ='".$_REQUEST['snp']."' ";
$UpdSnp.="WHERE ";
$UpdSnp.="atr_ape_ano ='".$ano."' ";
$UpdSnp.="AND ";
$UpdSnp.="atr_cred ='".$_REQUEST['cred']."'";

$rowUpdsnp=$conexion->ejecutarSQL($configuracion,$accesoOracle,$UpdSnp,"busqueda");

if(isset($rowUpdsnp))
{
	header("Location: reg_snp_acasptransferencia.php");
}
?>