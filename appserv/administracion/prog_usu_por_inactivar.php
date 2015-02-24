<?PHP
require_once('dir_relativo.cfg'); 
require_once(dir_conect.'valida_pag.php'); 
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(20); 

$redir = 'adm_usuarios_para_inactivar.php';

if($_REQUEST['tipo'] == 51)
{
	require_once('msql_est_por_inactivar.php');
	$Rowcursor = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cursor,"busqueda");
}
if($_REQUEST['tipo'] == 30)
{
	require_once('msql_doc_sin_carga_por_inactivar.php');
	$Rowcursor = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cursor,"busqueda");
}
if($_REQUEST['tipo'] == 24)
{
	require_once('msql_fun_por_inactivar.php');
	$Rowcursor = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cursor,"busqueda");
}
if($_REQUEST['tipo'] == 301)
{
	require_once('msql_doc_por_inactivar.php');
	$Rowcursor = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cursor,"busqueda");
	$_REQUEST['tipo'] = 30;
}

$estado='I';
$i=0;
while(isset($Rowcursor[$i][0]))
{
	$qry="UPDATE ";
	$qry.="geclaves ";
	$qry.="SET ";
	$qry.="cla_estado ='".$estado."' ";
	$qry.="WHERE ";
	$qry.="cla_codigo ='".$Rowcursor[$i][0]."' ";
	$qry.="AND ";
	$qry.="cla_tipo_usu ='".$_REQUEST['tipo']."' ";
	$Rowqry = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry,"busqueda");
	$i++;
}
if(isset($Rowqry))
{
	echo "<script>location.replace('$redir?error_login=27')</script>";
}
?>