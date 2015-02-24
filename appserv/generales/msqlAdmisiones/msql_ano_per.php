<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$per_consulta = "SELECT ape_ano, ape_per FROM acasperiadm WHERE ape_estado='X'";

$registro = $conexion->ejecutarSQL($configuracion,$accesoOracle,$per_consulta,"busqueda");

$ano = $registro[0][0];
$per = $registro[0][1];
if($per==1) $peri ='PRIMER';
if($per==3) $peri ='SEGUNDO';
$periodo = $peri.' PER&Iacute;ODO ACAD&Eacute;MICO DEL '.$ano;
?>