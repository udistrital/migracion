<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
?>
<html>
<head>
<title>Municipios</title>
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
if($_GET['httpS']=="" || $_GET['httpD']=="" || $_GET['httpH']==""){
   print '<p align="justify" class="error">error:<br><br>Para ver los municipios, antes debe seleccionar el Departamento.</p>'; exit;
}

$QryMun = "SELECT mun_cod, mun_nombre
			 FROM mntge.gemunicipio
			WHERE mun_dep_cod = ".$_GET['httpS']."
			  AND mun_estado = 'A'
		 ORDER BY mun_nombre";
$ExeMun = OCIParse($oci_conecta, $QryMun);
OCIExecute($ExeMun);

print'<table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
<caption>MUNICIPIOS</caption>
<tr><td align="center" colspan="2">Haga clic en el nombre del Municipio</td></tr>
<tr><td align="center"><span class="Estilo2">Código</span></td>
<td align="center"><span class="Estilo2">Nombre</span></td></tr>';

do{
   print'<tr><td align="right"><b>'.OCIResult($ExeMun, 1).'</b></td>
   <td><a href="javascript:RetornaValor('.OCIResult($ExeMun, 1).')" title="Municipio">'.OCIResult($ExeMun, 2).'</a></td></tr>';
}while(OCIFetch($ExeMun));
cierra_bd($ExeMun,$oci_conecta);
?>
</table>
</body>
</html>