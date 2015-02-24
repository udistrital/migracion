<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
?>
<html>
<head>
<title>Departamentos</title>
<link href="../general/asp_estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
function RetornaValor(valor){
	var retorno_obs = '<? echo $_GET["httpR"] ?>';
	window.opener.document.forms[0].elements[retorno_obs].value = valor;
	window.close();
}
</script>
</head>
<body>
<?php
$QryDpto = "SELECT dep_cod, dep_nombre FROM mntge.gedepartamento WHERE dep_estado = 'A' ORDER BY 2";
$ExeDpto = OCIParse($oci_conecta, $QryDpto);
OCIExecute($ExeDpto);

print'<table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
<caption>DEPARTAMENTOS</caption>
<tr><td align="center" colspan="2">Haga clic en el nombre del departamento</td></tr>
<tr><td align="center"><span class="Estilo2">Código</span></td>
<td align="center"><span class="Estilo2">Nombre</span></td></tr>';

do{
   print'<tr><td align="right"><b>'.OCIResult($ExeDpto, 1).'</b></td>
   <td><a href="javascript:RetornaValor('.OCIResult($ExeDpto, 1).')" title="Departamento">'.OCIResult($ExeDpto, 2).'</a></td></tr>';
}while(OCIFetch($ExeDpto));
cierra_bd($ExeDpto,$oci_conecta);
?>
</table>
</body>
</html>