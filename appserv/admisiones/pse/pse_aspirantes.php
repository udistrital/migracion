<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_general.'msql_ano_per.php');
require_once(dir_general.'asp_pie_pagAdm.php');
require_once(dir_general.'fecha_inscripcion.php');

require_once('valida_pse.php');
ob_start();
?>
<html>
<head>
<title>Aspirantes</title>
<link href="../general/asp_estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../BorraLink.js"></script>
<script language="JavaScript" src="pseLogout.js"></script>
</head>

<body>

<?php require_once(dir_general.'cabezote.php'); ?>
<p align="center" class="Estilo6">PROCESO DE ADMISIONES<br><? print $periodo; ?></p>

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="3">
<caption><span class="Estilo3"><b>FECHAS DE INSCRIPCIÓN: <? print '&nbsp;Del '.$FormFecIni.'&nbsp;&nbsp;&nbsp;al&nbsp;&nbsp;'.$FormFecFin; ?></b></span>
</caption>
  <tr>
    <td width="50%" align="justify">
	<fieldset style="margin:3; padding:2">
	<p align="justify">Señor aspirante, es preciso leer cuidadosamente el <b><a href="../instructivo/index.html" target="_blank">instructivo</a></b> antes de diligenciar el formulario. Tenga en cuenta que una vez grabada la información, esta no podrá ser modificada.</p>

	<p align="justify">Recuerde que el mal diligenciamiento del formulario y/o el suministro de datos erróneos,  ser&aacute;n causal de la anulación de la inscripción.</p>

	<p align="justify">Diligencie cuidadosamente la información solicitada  y cerciórese de la exactitud de todos los datos consignados.</p>

	<p align="justify">Los datos consignados en el formulario serán guardados bajo la gravedad del juramento, y en el momento de grabar y enviar la información equivale a la firma de la inscripción.</p>

	<p align="justify">La información enviada a través del formulario es confidencial y sólo puede ser utilizada por la institución a  la cual está dirigida. Toda la información aquí consignada está sujeta a verificación.</p>
	<p></p>
	</fieldset>
	</td>
    <td width="50%" align="center" valign="middle">
	<fieldset style="margin:3; padding:2">
	<h3>Seleccione el Tipo de Formulario de Inscripci&oacute;n</h3>
	<br>
	<a href="pse_acasp.php" title="Aspirantes a ingresar a primer semestre">INSCRIPCIÓN PARA INGRESO (Primer semestre)</a><br>
	<br>
    <a href="pse_reingreso.php" title="Estudiantes antiguos de la Universidad Distrital">REINGRESO O TRANSFERENCIA INTERNA</a><br>
    <br>
    <a href="pse_transferencia.php" title=" Viene de otra institución">TRANSFERENCIA EXTERNA</a>
    <p>&nbsp;</p>

	<p align="center">Diligencie únicamente el formulario que le corresponde.</p>
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