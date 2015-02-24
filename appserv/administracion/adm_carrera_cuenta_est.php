<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(20);
?>
<html>
<head>
<title>Administraci&oacute;n de Mensajes</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="estilo_adm.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
fu_cabezote("-PROYECTOS CURRICULARES-");

require_once('msql_carreras.php');
$row_cra = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_cra,"busqueda");

if(!is_array($row_cra))
{
	die('<center><h3>No hay registros para esta consulta.</h3></center>');
}

echo'<form name="LIS_CRA" method="POST" action="adm_est_activos.php" target="lismsg">
<div align="center">
	<table border="0" width="400">
		<tr>
			<td width="320" align="right">
			<select size="1" name="cracod" style="font-size: 10pt; font-family: Tahoma">
			<option value="" selected>Seleccione el Proyecto Curricular, Haga clic en Consultar.</option>\n';
			$i=0;
			while(isset($row_cra[$i][0]))
			{
				echo'<option value="'.$row_cra[$i][0].'">'.$row_cra[$i][0].'--'.$row_cra[$i][1].'</option>\n';
				$i++;
			}
			echo'</select>
			</td><td width="80" align="left"><input type="submit" value="Consultar" name="B1" class="button" '.$evento_boton.'></td>
		</tr>
	</table>
</div></form>';
?>
</body>
</html>