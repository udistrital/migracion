<?PHP
require_once('dir_relativo.cfg');
//require_once(dir_conect.'valida_pag.php'
//require_once(dir_conect.'fu_tipo_user.php');
//include_once("../clase/multiConexion.class.php");
require_once('msql_coor_carreras.php');

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$row_cra = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_cra,"busqueda");

echo'<div align="center">
<form name="LisCra" method="post" action="'.$_SERVER['PHP_SELF'].'">
<select size="1" name="cracod">
<option value="" selected>Seleccione el Proyecto Curricular y haga clic en Consultar</option>';


$i=1;
while(isset($row_cra[$i][0]))
{
	echo'<option value="'.$row_cra[$i][0].'">'.$row_cra[$i][0].'--'.$row_cra[$i][1].'</option>\n';
	$nombrecarrera= $row_cra[$i][1];
$i++;
}
   
echo'</select><INPUT TYPE="Submit" VALUE="Consultar" style="cursor:pointer" title="Ejecutar la Consulta">
</form></div>';
if(!$_REQUEST['cracod'])
{
$_REQUEST['cracod']=$cracod;
}
?>