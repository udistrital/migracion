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

$qry="UPDATE ";
$qry.="accoormensaje ";
$qry.="SET ";
$qry.="CME_AUTOR ='".$_REQUEST['cmeautor']."', ";
$qry.="CME_TITULO ='".$_REQUEST['cmetitulo']."', ";
$qry.="CME_FECHA_INI = TO_DATE('".$_REQUEST['cmefecini']."','dd/mm/YYYY'), ";
$qry.="CME_FECHA_FIN = TO_DATE('".$_REQUEST['cmefecfin']."','dd/mm/YYYY'), ";
$qry.="CME_MENSAJE ='".$_REQUEST['cmemsg']."' ";
$qry.="WHERE ";
$qry.="CME_CODIGO ='".$_REQUEST['cmecod']."' ";
$qry.="AND ";
$qry.="CME_CRA_COD='".$_POST['cmecracod']."' ";

$row_qry = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry,"");

if(isset($row_qry))
{
	echo "<script>location.replace('coor_index_msg.php')</script>";
}
?>