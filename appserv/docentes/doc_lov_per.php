<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(30);
ob_start();
?>
<html>
<head>
<title>Lista de Valores</title>
<link href="../script/estilo_ay.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
function RetornaValor(valor){
	var retorno = '<? echo $_GET["httpR"] ?>';
	window.opener.document.forms[0].elements[retorno].value = valor;
	window.close();
}
</script>
</head>
<body>
<?php
$cod_consul = "SELECT APE_ANO,APE_PER
  				 FROM ACASPERI
 				WHERE APE_ESTADO IN('A','X')";
$consulta = OCIParse($oci_conecta, $cod_consul);
OCIExecute($consulta);

echo <<< HTML
<table border="0" width="100" cellspacing="0">
<caption>SELECCIONE EL PERIODO</caption>
<tr class="tr"><td width="10" align="center"><span class="Estilo2">Año</span></td>
<td width="90" align="center"><span class="Estilo2">Período</span></td></tr>
HTML;
do{
   echo'<tr><td width="10" align="l"><b>'.OCIResult($consulta, 1).'</b></td>
   <td width="90" align="center"><a href="javascript:RetornaValor('.OCIResult($consulta, 2).')"><b>'.OCIResult($consulta, 2).'</b></a></td>
   </tr>';
}while(OCIFetch($consulta));
  cierra_bd($consulta,$oci_conecta);
ob_end_flush();
?>
</table>
</body>
</html>