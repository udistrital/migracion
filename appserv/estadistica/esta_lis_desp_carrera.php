<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

if(isset($_REQUEST['cracod'])=='Seleccione el Proyecto Curricular' || isset($_REQUEST['peri'])=='Per&iacute;odo'){
   die('<h3>Seleccione un Proyecto curricular y un per&iacute;odo.<br><input type="button" value="Regresar" OnClick="history.go(-1)" style="cursor:pointer"><h3>');
   exit;
}
//header("Location: ../err/err_con_est.php");

$QryCra = "SELECT cra_cod, cra_abrev
	FROM accra
	WHERE cra_cod NOT IN(0,999)
	AND cra_estado = 'A'
	ORDER BY cra_cod ASC";

$RowCra = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCra,"busqueda");
$cracod = $RowCra[0][0];

$QryPer = "SELECT ape_ano, ape_per
	FROM acasperi
	WHERE ape_per != 2
	AND ape_estado IN('I','P')
	ORDER BY ape_ano DESC";

$RowPer = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryPer,"busqueda");
$per = $RowPer[0][0]-2 .$RowPer[0][1];

echo'<div align="center">
<form name="LisCra" method="post" action="'.$_SERVER['PHP_SELF'].'">
<select size="1" name="cracod">
<option selected>Seleccione el Proyecto Curricular</option>';
$i=0;
while(isset($RowCra[$i][0]))
{
	echo'<option value="'.$RowCra[$i][0].'">'.$RowCra[$i][0].'--'.$RowCra[$i][1].'</option>\n';
$i++;
}
echo'</select>&nbsp;&nbsp;

<select size="1" name="peri">
<option selected>Per&iacute;odo</option>';
$i=0;
while(isset($RowPer[$i][0]))
{
	echo'<option value="'.$RowPer[$i][0].$RowPer[$i][1].'">'.$RowPer[$i][0].'-'.$RowPer[$i][1].'</option>\n';
$i++;
}

echo'</select><INPUT TYPE="Submit" VALUE="Consultar" style="cursor:pointer" style="cursor:pointer" title="Ejecutar la consulta">

</form></div>';

if(!isset($_REQUEST['cracod'])) $_REQUEST['cracod'] = $cracod;
if(!isset($_REQUEST['peri'])) $_REQUEST['peri'] = $per;
?>