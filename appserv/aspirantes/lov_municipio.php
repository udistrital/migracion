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
<title>Municipios</title>
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
if($_REQUEST['httpS']=="" || $_REQUEST['httpD']=="" || $_REQUEST['httpH']==""){
   print '<p align="justify" class="error">error:<br><br>Para ver los municipios, antes debe seleccionar el Departamento.</p>'; exit;
}

$QryMun = "SELECT mun_cod, mun_nombre
	FROM mntge.gemunicipio
	WHERE mun_dep_cod = ".$_REQUEST['httpS']."
	AND mun_estado = 'A'
	ORDER BY mun_nombre";
$RowMun = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryMun,"busqueda");


print'<table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
<caption>MUNICIPIOS</caption>
<tr><td align="center" colspan="2">Haga clic en el nombre del Municipio</td></tr>
<tr><td align="center"><span class="Estilo2">C&oacute;digo</span></td>
<td align="center"><span class="Estilo2">Nombre</span></td></tr>';
$i=0;
while(isset($RowMun[$i][0]))
{
	print'<tr><td align="right"><b>'.$RowMun[$i][0].'</b></td>
	<td><a href="javascript:RetornaValor('.$RowMun[$i][0].')" title="Municipio">'.$RowMun[$i][1].'</a></td></tr>';
$i++;
}
?>
</table>
</body>
</html>