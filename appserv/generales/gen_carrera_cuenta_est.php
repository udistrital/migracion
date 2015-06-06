<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'valida_pag.php');
require_once('../administracion/msql_carreras.php');
include_once("../clase/multiConexion.class.php");


$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

?>
<html>
<head>
<title>Administraci&oacute;n de Mensajes</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body style="margin-top:0; margin-bottom:0 ">
<?php
fu_cabezote("PROYECTOS CURRICULARES");

$rows = $conexion->ejecutarSQL($configuracion, $accesoOracle, $qry_cra, "busqueda");


if(!$rows) die('<center><h3>No hay registros para esta consulta.</h3></center>');

$i=0;
echo'<form name="LIS_CRA" method="POST" action="gen_est_activos.php" target="lismsg">
<div align="center"><table border="0" width="400">
<tr><td width="320" align="right">
	<select size="1" name="cracod" style="font-size: 10pt; font-family: Tahoma">
	<option value="" selected>Seleccione el Proyecto Curricular, Haga clic en Consultar.</option>\n';
	do{
	   echo'<option value="'.$rows[$i][0].'">'.$rows[$i][0].'--'.$rows[$i][1].'</option>\n';
	   $i++;
	}while(isset($rows[$i][0]));

	echo'</select>
	</td><td width="80" align="left"><input type="submit" value="Consultar" name="B1" style="cursor:pointer" title="Ejecutar la consulta"></td></tr>
</table></div></form>';

?>
</body>
</html>