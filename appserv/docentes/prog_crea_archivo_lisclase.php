<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

if($_REQUEST['as']=="")
   die('<center><font color="#FF0000"><h3>No tiene estudiantes inscritos.</h3></font></center>');
   
$estado = 'A';
require_once(dir_script.'msql_lisclase.php');
$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

if(!is_array($registro))
{
die('<center><h3>No hay registros para esta consulta.</h3></center>'); 
} 
$nombreEspacio=str_replace(" ", "_", $registro[0][1]);
$nom_archivo=$nombreEspacio.'-'.$registro[0][2].'.xls';
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=".$nom_archivo."");
header("Pragma: no-cache");
header("Expires: 0");

print '<table cellspacing="1" cellpadding="1" border="1">';
print '<tr><td colspan=13 align=center>LISTADO DE CLASE</td></tr>';
print '<tr><td> Asignatura:</td><td colspan=12><b>'.htmlentities($registro[0][1]).'</b></td></tr>';
print '<tr><td>Grupo: </td><td colspan=3><b>'.$registro[0][2].'</b></td><td align="right">Periodo:</td><td colspan=8><b>'.$registro[0][8].'-'.$registro[0][9].'</b></td></tr>';
print '<tr align=center><td><b>ID</b></td><td><b>C&oacute;digo</b></td><td><b>Nombre</b></td><td>PAR 1</td><td>PAR 2</td><td>PAR 3</td><td>PAR 4</td><td>PAR 5</td><td>PAR 6</td><td>LAB</td><td>EXA</td><td>HAB</td><td>DEF</td></tr>';

$i=0;
$j=$i+1;
while(isset($registro[$i][0]))
{
	$datos='<tr ><td align="center">'.$j.'</td><td>'.$registro[$i][11].'</td><td>'.$registro[$i][12].'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
   	//fwrite($fp,$datos);
   	print $datos;
$i++;
$j++;
}

print '</table>';

exit;
?>
