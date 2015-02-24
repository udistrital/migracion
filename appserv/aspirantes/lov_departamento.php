<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<html>
<head>
<title>Departamentos</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
function RetornaValor(valor){
	var retorno_obs = '<? echo $_REQUEST["httpR"] ?>';
	window.opener.document.forms[0].elements[retorno_obs].value = valor;
	window.close();
}
</script>
</head>
<body>
<?php
$QryDpto = "SELECT dep_cod, dep_nombre FROM gedepartamento WHERE dep_estado = 'A' ORDER BY 2";
$RowDpto = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryDpto,"busqueda");

print'<table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
	<caption>DEPARTAMENTOS</caption>
	<tr><td align="center" colspan="2">Haga clic en el nombre del departamento</td></tr>
	<tr><td align="center"><span class="Estilo2">C&oacute;digo</span></td>
	<td align="center"><span class="Estilo2">Nombre</span></td></tr>';
	$i=0;
	while(isset($RowDpto[$i][0]))
	{
		print'<tr><td align="right"><b>'.$RowDpto[$i][0].'</b></td>
		<td><a href="javascript:RetornaValor('.$RowDpto[$i][0].')" title="Departamento">'.$RowDpto[$i][1].'</a></td></tr>';
	$i++;	
	}
	?>
</table>
</body>
</html>
