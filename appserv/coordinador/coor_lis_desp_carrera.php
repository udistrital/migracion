<?PHP
require_once('dir_relativo.cfg');
require_once('msql_coor_carreras.php');
include_once('../clase/validacion_usu.class.php');

$obj_validacion=new validarUsu();
if(!$tipo){
    $tipo=$_SESSION['usuario_nivel'];
}
if($tipo==4){
        $row_cra = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_cra,"busqueda");
}elseif($tipo==114 || $tipo==110){
        $proyectos_asistente=$obj_validacion->consultarProyectosAsistente($_SESSION["usuario_login"],$tipo,$conexion,$configuracion,$accesoOracle);
        foreach ($proyectos_asistente as $key => $proyecto) {
            $row_cra[$key][0]=$proyecto[0];
            $row_cra[$key][1]=$proyecto[4];
        }
       
}

echo'<div align="center">
<form name="LisCra" method="post" action="'.$_SERVER['PHP_SELF'].'">
<select size="1" name="cracod">
<option value="" selected>Seleccione el Proyecto Curricular y haga clic en Consultar</option>';
$cracod = $row_cra[0][0];

$i=0;
while(isset($row_cra[$i][0]))
{
	echo'<option value="'.$row_cra[$i][0].'">'.$row_cra[$i][0].'--'.$row_cra[$i][1].'</option>\n';
$i++;
}
   
echo'</select><INPUT TYPE="Submit" VALUE="Consultar" style="cursor:pointer" title="Ejecutar la Consulta">
</form></div>';
if(!$_REQUEST['cracod'])
{
$_REQUEST['cracod']=$cracod;
}
?>