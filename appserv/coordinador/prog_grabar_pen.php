<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4);
/*foreach($_REQUEST as $clave=>$valor)
{
	echo $clave."->".$valor."<br>";
}*/
$i=1;
do{
  $qry="UPDATE ";
  $qry.="ACPEN ";
  $qry.="SET PEN_IND_ELE ='".$_REQUEST[sprintf('electiva_%d', $i)]."', ";
  $qry.="PEN_NRO_HT ='".$_REQUEST[sprintf('ht_%d', $i)]."', ";
  $qry.="PEN_NRO_HP ='".$_REQUEST[sprintf('hp_%d', $i)]."' ";
  $qry.="WHERE ";
  $qry.="PEN_CRA_COD ='".$_REQUEST['pencracod']."' ";
  $qry.="AND ";
  $qry.="PEN_ASI_COD='".$_REQUEST[sprintf('asicod_%d',$i)]."' ";
  $qry.="AND ";
  $qry.="PEN_NRO ='".$_REQUEST[sprintf('pn_%d',$i)]."' ";
  $qry.="AND ";
  $qry.="PEN_ESTADO ='".A."'";
  $registro = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry,"busqueda");
  //echo $qry;
  $i++;
}while($i <= $_REQUEST['num_regs']-1);
if(isset($registro))
{
	header("Location: coor_actualiza_pen.php");
}
else
{
	echo "No se pudo realizar el cambio";
}
?>