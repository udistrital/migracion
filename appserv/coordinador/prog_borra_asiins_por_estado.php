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

if($_REQUEST['estado'] == "") die('<center><h3><font color="#FF0000">No hay registros para borrar.</font></h3></center>');

$estado = $_REQUEST['estado'];
$DelEst = "DELETE ACINS Z
	WHERE INS_CRA_COD = ".$_SESSION['carrera']."
	AND INS_ANO = $ano
	AND INS_PER = $per
	AND EXISTS(SELECT EST_COD
	FROM ACEST
	WHERE EST_COD = Z.INS_EST_COD
	AND EST_CRA_COD = Z.INS_CRA_COD
	AND EST_ESTADO_EST = '$estado')";
$registro = $conexion->ejecutarSQL($configuracion,$accesoOracle,$DelEst,"busqueda");
if(isset($registro))
{
	echo "Los registros se borraron exitosamente";
	//echo "<script>location.replace('print_est_estado.php?estado='".$_REQUEST['estado']."')</script>";
}
?>