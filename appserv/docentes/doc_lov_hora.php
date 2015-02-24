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
	var retorno = '<? echo $_REQUEST["httpR"] ?>';
	window.opener.document.forms[0].elements[retorno].value = valor;
	window.close();
}
</script>
</head>
<body>
<?php
$cod_consul = "SELECT HOR_COD,HOR_LARGA FROM GEHORA WHERE HOR_ESTADO = 'A' ORDER BY 1";
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

echo '<table border="0" width="200" align="center">
<caption>LISTADO DE HORAS</td>
<tr class="tr"><td width="10" align="center"><span class="Estilo2">C&oacute;digo</span></caption>
<td width="190" align="center"><span class="Estilo2">Rango</span></td></tr>';
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr><td width="3%" align="right"><b>'.$consulta[$i][0].'</b></td>
	<td width="20%"><a href="javascript:RetornaValor('.$consulta[$i][0].')" title="Dia">'.$consulta[$i][1].'</a></td></tr>';
$i++;
}
?>
</table>
</body>
</html>