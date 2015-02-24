<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);
?>
<html>
<head>
<title>Lista de Valores</title>
<link href="../script/estilo_ay.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
function RetornaValor(valor){
	var retorno_obs = '<? echo $_REQUEST["obs_retorno"] ?>';
	window.opener.document.forms[0].elements[retorno_obs].value = valor;
	window.close();
}
</script>
</head>
<body>
<?php
$cod_consul = "SELECT NOB_COD, NOB_NOMBRE FROM ACNOTOBS WHERE NOB_COD IN(0,1,3,10,11,19,20) ORDER BY NOB_COD";
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

echo '<table border="0" width="200" align="center">
<caption>OBSERVACIONES DE NOTAS</caption>
<tr class="tr"><td width="104" align="center">C&oacute;digo</td>
<td width="197" align="center">Descripci&oacute;n</td></tr>';
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr><td width="3%" align="right"><b>'.$consulta[$i][0].'</b></td>
	<td width="20%"><a href="javascript:RetornaValor('.$consulta[$i][0].')" title="C&oacute;digo de la observaci&oacute;n">'.$consulta[$i][1].'</a></td></tr>';
$i++;
}
?>
</table>
</body>
</html>