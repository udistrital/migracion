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
$cod_consul = "SELECT CRA_COD,CRA_NOMBRE
  				 FROM ACCRA
 				WHERE CRA_ESTADO = 'A'
   				  AND CRA_COD NOT IN(0,999)
 			 ORDER BY 2,1";
$consulta = OCIParse($oci_conecta, $cod_consul);
OCIExecute($consulta);

echo <<< HTML
<table border="0" width="550" cellspacing="0">
<caption>LISTADO DE CARRERAS</caption>
<tr class="tr"><td width="50" align="center">Código</td>
<td width="500" align="center">Carrera</td></tr>
HTML;
do{
   echo'<tr><td width="50" align="right"><b>'.OCIResult($consulta, 1).'</b></td>
   <td width="500"><a href="javascript:RetornaValor('.OCIResult($consulta, 1).')" title="Código del Proyecto Curricular">'.OCIResult($consulta, 2).'</a></td></tr>';
}while(OCIFetch($consulta));
  cierra_bd($consulta,$oci_conecta);
ob_end_flush();
?>
</table>
</body>
</html>