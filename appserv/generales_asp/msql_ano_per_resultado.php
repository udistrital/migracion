<?PHP
include_once("../clase/multiConexion.class.php");
$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion('default');

$per_consulta = "SELECT ape_ano, ape_per FROM acasperiadm WHERE ape_estado='X'";

$rowper = $conexion->ejecutarSQL($configuracion,$accesoOracle,$per_consulta,"busqueda");
$ano = $rowper[0][0];
$per = $rowper[0][1];
if($per==1) $peri ='PRIMER';
if($per==3) $peri ='SEGUNDO';
$periodo = $peri.' PER&Iacute;ODO ACAD&Eacute;MICO DEL '.$ano;
?>