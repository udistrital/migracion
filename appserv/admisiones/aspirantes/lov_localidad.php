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
	var retorno_obs = '<? echo $_GET["obs_retorno"] ?>';
	window.opener.document.forms[0].elements[retorno_obs].value = valor;
	window.close();
}
</script>
</head>
<body>
<?php
$QryLoc = "SELECT loc_nro,loc_nombre 
			  FROM mntac.aclocalidad 
			 WHERE loc_ape_ano = (select ape_ano from acasperi where ape_estado = 'A')
			   AND loc_ape_per = (select ape_per from acasperi where ape_estado = 'A')";
$ExeLoc = OCIParse($oci_conecta, $QryLoc);
OCIExecute($ExeLoc);

print'<table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
<caption>DEPARTAMENTOS</caption>
<tr><td align="center" colspan="2">Haga clic en el nombre de la localidad</td></tr>
<tr><td align="center"><span class="Estilo2">Código</span></td>
<td align="center"><span class="Estilo2">Nombre</span></td></tr>';

do{
   print'<tr><td align="right"><b>'.OCIResult($ExeLoc, 1).'</b></td>
   <td><a href="javascript:RetornaValor('.OCIResult($ExeLoc, 1).')" title="Localidad">'.OCIResult($ExeLoc, 2).'</a></td></tr>';
}while(OCIFetch($ExeLoc));
cierra_bd($ExeLoc,$oci_conecta);
?>
</table>
</body>
</html>