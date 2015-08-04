<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(20);
?>
<html>
<head>
<title>Facultades</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
fu_cabezote("FACULTADES");

$QryFac = "SELECT DISTINCT(cra_dep_cod), dep_nombre
	FROM accra, gedep
	WHERE dep_cod = cra_dep_cod
	AND cra_estado = 'A'
	AND cra_dep_cod != 0
	AND dep_estado ='A'
	ORDER BY 1";

$RowFac = $registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFac,"busqueda");

echo'<BR>
<table border="1" width="80%" cellspacing="0" cellpadding="0" align="center">
	<tr class="tr" align="center">
	<td>C&oacute;digo</td>
	<td>Nombre De La Facultad</td></tr>';
	$i=0;
	while(isset($RowFac[$i][0]))
	{
		echo'<tr class="td">
		<td align="right">'.$RowFac[$i][0].'</td>
		<td align="left"><a href="adm_coordinadores.php?depcod='.$RowFac[$i][0].'" target="inferior">'.$RowFac[$i][1].'</a></td></tr>';
		$i++;
	}
echo'</table>';
?>
</body>
</html>