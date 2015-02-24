<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<html>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<body>
<?php
$datos = "SELECT cra_cod,cra_abrev 
	FROM accra 
	WHERE cra_estado = 'A' 
	AND cra_dep_cod IN(23,24,32,33,100)
	ORDER BY cra_nombre ASC";

$row_datos = $conexion->ejecutarSQL($configuracion,$accesoOracle,$datos,"busqueda");

echo'<div align="center">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<caption>LISTADO DE CARRERAS</caption>
		<tr>
			<td width="100%" align="center">
			<form name="LIS_CRA" method="POST" action="lis_carrera.php">
			<select size="1" name="CRANOM" onclick="javascript:document.forms.LIS_CRA.CRACOD.value = document.forms.LIS_CRA.CRANOM.value" style="font-size: 9pt; font-family: Tahoma">';
			$i=0;
			while(isset($row_datos[$i][0]))
			{
				echo'<option value="'.$row_datos[$i][0].'" selected>'.$row_datos[$i][1].'</option>\n';
				$i++;
			}
			echo'</select><input name="CRACOD" type="text" id="CRACOD" value="" size="3" style="text-align: right" readonly></form>
			</td>
		</tr>
	</table>
</div>';
?>
</body>
</html>