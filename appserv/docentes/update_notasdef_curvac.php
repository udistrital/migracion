<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$i=0;
do{
	$qry="UPDATE ";
	$qry.="ACINS ";
	$qry.="SET ";
	$qry.="INS_NOTA ='".$_REQUEST[sprintf('nota%d',$i)]."', ";
	$qry.="INS_OBS ='".$_REQUEST[sprintf('obs_%d',$i)]."', ";
	$qry.="INS_USUARIO ='".$_SESSION['usuario_login']."' ";
	$qry.="WHERE ";
	$qry.="INS_ANO ='".$_REQUEST['ano']."' ";
	$qry.="AND ";
	$qry.="INS_PER ='".$_POST['per']."' ";
	$qry.="AND ";
	$qry.="INS_ASI_COD ='".$_SESSION["A"]."' ";
	$qry.="AND ";
	$qry.="INS_GR ='".$_SESSION["cur"]."' ";
	$qry.="AND ";
	$qry.="INS_EST_COD ='".$_REQUEST[sprintf('cod_%d',$i)]."' ";
	
	$resulQry=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry,"busqueda");
$i++;

}while($i<=$_REQUEST['num_regs']-1);
?>
