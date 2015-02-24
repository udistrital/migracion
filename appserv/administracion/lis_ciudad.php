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
$datos = "SELECT lug_cod,lug_nombre FROM gelugar ORDER BY lug_nombre";

$row = $conexion->ejecutarSQL($configuracion,$accesoOracle,$datos,"busqueda");

echo'<div align="center">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<caption>LISTADO DE CIUDADES</caption>
		<tr>
			<td width="100%" align="center">
			<form name="CIUDAD" method="POST" action="lis_ciudad.php">
			<select size="1" name="CIUNOM" onclick="javascript:document.forms.CIUDAD.CRACOD.value = document.forms.CIUDAD.CIUNOM.value" style="font-size: 9pt; font-family: Tahoma">';
			$i=0;
			while(isset($row[$i][0]))
			{
				echo'<font face="Tahoma" size="1"><option value="'.$row[$i][0].'" selected>'.$row[$i][1].'</option></font>\n';
				$i++;
			}
			echo'</select><input name="CRACOD" type="text" id="CRACOD" value="" size="7" style="text-align: right" readonly></form>
			</td>
		</tr>
	</table>
</div>';
?>
</body>
</html>