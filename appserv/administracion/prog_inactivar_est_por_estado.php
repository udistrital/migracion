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

$estado = $_REQUEST['EstadoEst'];
require_once('msql_inactivar_est_por_estado.php');
$RowEst = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cursor,"busqueda");

if(!is_array($RowEst))
{
	echo "<script>location.replace('$redir?error_login=25')</script>";
}
  
$ClaEstado = 'I';
$i=0;
while(isset($RowEst[$i][0]))
{
	$qry="UPDATE ";
	$qry.="geclaves ";
	$qry.="SET ";
	$qry.="cla_estado ='".$ClaEstado."' ";
	$qry.="WHERE ";
	$qry.="cla_codigo ='".$RowEst[$i][0]."' ";
	$qry.="AND ";
	$qry.="cla_tipo_usu = 51";
	$Rowqry = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry,"busqueda");
	$i++;
}
if(isset($Rowqry))
{
	echo "<script>location.replace('$redir?error_login=27')</script>";
}
?>