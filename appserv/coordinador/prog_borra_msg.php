<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


if(!isset($_REQUEST['tipo'])){
    $_REQUEST['tipo']=$_SESSION['usuario_nivel'];
}

if($_REQUEST['tipo']==110){
    fu_tipo_user(110);
    $tipo=110; 
}elseif($_REQUEST['tipo']==114){
    fu_tipo_user(114);
    $tipo=114; 
}elseif($_REQUEST['tipo']==4){
    fu_tipo_user(4);
    $tipo=4; 
}

$del = "DELETE FROM accoormensaje 
   	WHERE CME_CODIGO =".$_REQUEST['del']."
	AND CME_CRA_COD =".$_REQUEST['cracod'];
	
$row_del = $conexion->ejecutarSQL($configuracion,$accesoOracle,$del,"");

if(isset($row_del))
{
	echo "<script>location.replace('coor_admin_msg.php')</script>";
}
?>