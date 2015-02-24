<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_general.'msql_ano_per.php');
require_once(dir_general.'asp_pie_pagAdm.php');
require_once(dir_general.'fecha_inscripcion.php');
require_once(dir_general.'valida_usuario_prog.php');
require_once(dir_general.'valida_inscripcion.php');

ob_start();
?>
<html>
<head>
<title>Aspirantes</title>
<link href="../general/asp_estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../script/BorraLink.js"></script>
<script language="JavaScript" src="Logout.js"></script>
</head>

<body>

<?php require_once(dir_general.'cabezote.php'); ?>
<p align="center" class="Estilo6">PROCESO DE ADMISIONES<br><? print $periodo; ?></p>

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="3">
<caption><span class="Estilo3"><? print 'Fecha inicial: '.$FormFecIni.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Fecha final: '.$FormFecFin; ?></span>
</caption>
  <tr>
    <td width="50%" align="justify">
	<fieldset style="margin:3; padding:2">
	<p align="justify">Se&ntilde;or aspirante, es preciso leer cuidadosamente el <b><a href="../instructivo/index.html" target="_blank">instructivo</a></b> antes de diligenciar el formulario. Tenga en cuenta que una vez grabada la informaci&oacute;n, esta no podr&aacute; ser modificada.</p>

	<p align="justify">Recuerde que el mal diligenciamiento del formulario y/o el suministro de datos erroneos,  ser&aacute;n causal de la anulaci&oacute;n de la inscripci&oacute;n.</p>

	<p align="justify">Diligencie cuidadosamente la informaci&oacute;n solicitada  y cerci&oacute;rese de la exactitud de todos los datos consignados.</p>

	<p align="justify">Los datos consignados en el formulario ser&aacute;n guardados bajo la gravedad del juramento, y en el momento de grabar y enviar la informaci&oacute;n equivale a la firma de la inscripci&oacute;n.</p>

	<p align="justify">La informaci&oacute;n enviada a trav&eacute;s del formulario es confidencial y s&oacute;lo puede ser utilizada por la instituci&oacute;n a  la cual est&aacute; dirigida. Toda la informaci&oacute;n aqu&iacute; consignada est&aacute; sujeta a verificaci&oacute;n.</p>
	<p></p>
	</fieldset>
	</td>
    <td width="50%" align="center" valign="middle">
	<fieldset style="margin:3; padding:2">
	<h3>Seleccione el Tipo de Formulario de Inscripci&oacute;n</h3>
	<!--<br>
	<a href="acasp.php" title="Aspirantes a ingresar a primer semestre">INSCRIPCI&Oacute;N PARA INGRESO A PRIMER SEMESTRE</a><br>-->
	<br>
    <a href="reingreso.php" title="Estudiantes antiguos de la Universidad Distrital">REINGRESO O TRANSFERENCIA INTERNA</a><br>
    <!--<br>
    <a href="transferencia.php" title=" Viene de otra institucion">TRANSFERENCIA EXTERNA</a><br>-->
	<!--<br>
    <a href="../general/imprime_colilla_general.php" title="Ver datos de la inscripcion"><span class="Estilo10">VER INSCRIPCI&Oacute;N NORMAL</span></a><br>-->
	<br>
    <a href="../general/imprime_colilla_reingreso.php" title="Ver datos de la inscripcion"><span class="Estilo10">VER INSCRIPCI&Oacute;N REINGRESO / TRANS. INTERNA</span></a><br>
	<!--<br>
	<a href="../general/imprime_colilla_transferencia.php" title="Ver datos de la inscripcion"><span class="Estilo10">VER INSCRIPCI&Oacute;N TRANSFERENCIA EXTERNA</span></a>-->
	<p align="center">Diligencie &uacute;nicamente el formulario que le corresponde.</p>
	<p class="error">Tiempo estimado para diligenciar el formulario 8 minutos.</p>
	<form name="form1" method="post" action="">
 	<input type="button" value="Salir" onClick="salir()" style="width:80; cursor:pointer">
	</form>
	</fieldset>
	</td>
  </tr>
</table>

<p></p>
</fieldset>
<p></p>
<?php
fu_pie();
ob_end_flush();
?>
</body>
</html>