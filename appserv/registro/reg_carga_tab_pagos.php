<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(33);

fu_cabezote("CARGAR TABLA DE PAGOS");

//require_once('valida_inscripcion.php');
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body>
<p>&nbsp;</p>

<table width="60%" align="center" border="0" cellpadding="0" cellspacing="3">
<tr><td colspan="2">
<p align="justify">Ejecute este proceso para:</p>
<ul>
<li class="ItemStyle"><p align="justify">Insertar en la tabla de pagos, los registros subidos a la base de datos desde el archivo de reporte de pagos.</li>
<li class="ItemStyle"><p align="justify">Generarles una credencial a los aspirantes.</p></li>
</ul>
<p align="justify">Tenga en  cuenta que al cargar el nuevo reporte, se borraran los datos anteriores de la tabla acplanorecbanasp.</p>
<p align="justify">Una vez terminado el proceso, ejecute el item número 4 en el menú "Procesos".</p>
<p align="justify">Siga las instrucciones que aparecen después de la ejecución del proceso.</p>
</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" align="center" class="Estilo10">Está seguro(a) de ejecutar este proceso? </td></tr>
<tr align="center"><td>
<form action="prog_carga_pagos.php" method="post" name="ejecutar">
<input name="cargar" type="submit" value="Si" style="width:50; height:20;cursor:pointer">
</form>
</td>
<td>
<form action="javascript:history.back();" method="post" name="ejecutar" target="_top">
<input name="no" type="submit" value="No" style="width:50; height:20;cursor:pointer">
</form>
</td>
</tr>
</table>
<p>&nbsp;</p>
<?PHP fu_pie(); ?>
</body>
</html>