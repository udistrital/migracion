<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_script.'class_nombres.php');
include_once("../clase/multiConexion.class.php");

fu_tipo_user(51);

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

	$nom = new Nombres;
	$estudiante = $nom->rescataNombre($_SESSION['usuario_login'],"NombreEstudiante");

	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=".$_SESSION['usuario_login'],"busqueda");
	$cra = $nom->rescataNombre($registro[0][0],"NombreCarrera");



?>

<HTML>
<HEAD>
<TITLE>Estudiante</TITLE>
<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY style="margin:0;">

<?php

fu_print_cabezote("HORARIO DE CLASE");

$estados = "'A','B','H','J','L','T','V'";
require_once(dir_script.'msql_est_horario.php');



	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryHor,"busqueda");


if(!is_array($registro))
{ 

echo '<center><h3>Su registro de asignaturas presenta cruce, o no tiene asignaturas inscritas.</h3></center>';

}

print'<table width="100%" border="1" align="center" cellpadding="1" cellspacing="0" style="font-family:Tahoma;font-size:11px; border-collapse:collapse">
  <caption><b>'.$cra.'</b></caption>
  <tr><td colspan="8" align="center">'.$estudiante.' /  Periodo Acad&eacute;mico: '.$ano.'-'.$per.'</td></tr>
  <tr>
    <td align="center"><b>HORA</b></td>
    <td align="center"><b>LUNES</b></td>
    <td align="center"><b>MARTES</b></td>
    <td align="center"><b>MIERCOLES</b></td>
    <td align="center"><b>JUEVES</b></td>
    <td align="center"><b>VIERNES</b></td>
    <td align="center"><b>SABADO</b></td>
    <td align="center"><b>DOMINGO</b></td>
  </tr>';

$i=0;
while(isset($registro[$i][0])){
   print'<tr>
    <td align="right"><b>'.$registro[$i][1].'</b></td>
    <td align="center">'.$registro[$i][2].'</td>
    <td align="center">'.$registro[$i][3].'</td>
    <td align="center">'.$registro[$i][4].'</td>
    <td align="center">'.$registro[$i][5].'</td>
    <td align="center">'.$registro[$i][6].'</td>
    <td align="center">'.$registro[$i][7].'</td>
    <td align="center">'.$registro[$i][8].'</td></tr>';
	$i++;
}




echo '</table>';
echo '<table width="100%" border="0" align="center"><tr><td align="right" style="font-family:Tahoma;font-size:10px">Lease: C&oacute;digo de Asignatura - Grupo / Sal&oacute;n - Sede</td></tr></table>';
echo '<p></p>';


require_once(dir_script.'msql_est_asi_ins.php');


	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");



echo '  <table width="100%" border="1" align="center" cellpadding="1" cellspacing="0" style="font-family:Tahoma;font-size:11px; border-collapse:collapse">';
echo '  <caption>Las asignaturas que aparecen repetidas, tienen asignado m&aacute;s de un docente.</caption>';
echo '  <tr>';
echo '    <td align="center"><b>C&oacute;digo</b></td>';
echo '	<td align="center"><b>Asignatura</b></td>';
echo '    <td align="center"><b>Gru</b></td>';
echo '	<td align="center"><b>Docente</b></td>';
echo '  </tr>';


$i=0;
	while(isset($registro[$i][0])){
		echo'<tr><td align="right">'.$registro[$i][6].'</td>
		<td align="left">'.$registro[$i][7].'</td> 
		<td align="center">'.$registro[$i][8].'</td>
		<td align="left">'.$registro[$i][10].'</td>'; 
	$i++;
 	}


echo '  <tr><td colspan="4" align="right" style="font-size:9px">Dise&ntilde;o Oficina Asesora de Sistemas</td></tr>';
echo '  </table>';
echo '  </BODY>';
echo '  </HTML>';

?>
