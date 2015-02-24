<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(4);

require_once('msql_coor_carreras.php');
if($_REQUEST['estcod'] == "") die('<center><h3><font color="#FF0000">No hay registros para borrar.</font></h3></center>');

$DelEst = "DELETE ACINS 
	WHERE INS_EST_COD = ".$_REQUEST['estcod']."
	AND INS_ANO = $ano
	AND INS_PER = $per
	AND INS_CRA_COD = ".$_SESSION['carrera'];
$registro = $conexion->ejecutarSQL($configuracion,$accesoOracle,$DelEst,"busqueda");
$afectados=$conexion->totalAfectados($configuracion,$accesoOracle);
if($afectados >= 1)
{
	echo $afectados." registro(s) borrado(s)"; 
}
?>