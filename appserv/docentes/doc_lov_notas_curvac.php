<?php
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
	var retorno_obs = '<? echo $_GET["obs_retorno"] ?>';
	window.opener.document.forms[0].elements[retorno_obs].value = valor;
	window.close();
}
</script>
</head>
<body>
<?php

echo <<< HTML
<table border="0" width="212" align="center">
<caption>NOTAS</caption>
<tr class="tr"><td width="104" align="center">N�mero</td>
<td width="209" align="left" align="center">
<p align="center">Letras</td></tr>


<tr><td width="104" align="center"><a href="javascript:RetornaValor(0)">0</a></td>
<td width="209" align="left"><span class="Estilo2"><a href="javascript:RetornaValor(0)">Para Obs (19 o 20)</a></span></td></tr>

<tr><td width="104" align="center"><a href="javascript:RetornaValor(15)">15</a></td>
<td width="209" align="left"><span class="Estilo2"><a href="javascript:RetornaValor(15)">Uno con cinco</a></span></td></tr>

<tr><td width="104" align="center"><a href="javascript:RetornaValor(20)">20</a></td>
<td width="209" align="left"><span class="Estilo2"><a href="javascript:RetornaValor(20)">Dos</a></span></td></tr>

<tr><td width="104" align="center"><a href="javascript:RetornaValor(25)">25</a></td>
<td width="209" align="left"><span class="Estilo2"><a href="javascript:RetornaValor(25)">Dos con cinco</a></span></td></tr>

<tr><td width="104" align="center"><a href="javascript:RetornaValor(30)">30</a></td>
<td width="209" align="left"><span class="Estilo2"><a href="javascript:RetornaValor(30)">Tres</span></a></td></tr>

<tr><td width="104" align="center"><a href="javascript:RetornaValor(35)">35</a></td>
<td width="209" align="left"><span class="Estilo2"><a href="javascript:RetornaValor(35)">Tres con cinco</a></span></td></tr>

<tr><td width="104" align="center"><a href="javascript:RetornaValor(40)">40</a></td>
<td width="209" align="left"><span class="Estilo2"><a href="javascript:RetornaValor(40)">Cuatro</a></span></td></tr>

<tr><td width="104" align="center"><a href="javascript:RetornaValor(45)">45</a></td>
<td width="209" align="left"><span class="Estilo2"><a href="javascript:RetornaValor(45)">Cuatro con cinco</a></span></td></tr>

<tr><td width="104" align="center"><a href="javascript:RetornaValor(50)">50</a></td>
<td width="209" align="left"><span class="Estilo2"><a href="javascript:RetornaValor(50)">Cinco</a></span></td></tr>
HTML;
ob_end_flush();
?>
</table>
</body>
</html>
