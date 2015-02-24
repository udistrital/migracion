<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_general.'msql_ano_per.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_general.'asp_pie_pagAdm.php');

require_once(dir_general.'valida_usuario_prog.php');
require_once(dir_general.'valida_http_referer.php');
require_once(dir_general.'valida_inscripcion.php');

ob_start();
?>
<html>
<head>
<title>Aspirantes</title>
<link href="asp_estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../script/BorraLink.js"></script>
<script language="JavaScript" src="Logout.js"></script>
</head>

<body>
<?php
require_once(dir_general.'cabezote.php'); 
require_once(dir_general.'msql_colilla_transferencia.php'); 
?>
<p align="center" class="Estilo6">FORMULARIO DE TRANSFERENCIA EXTERNA<br><? print $periodo; ?></p>

<table width="95%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
  <br>

<table width="75%"  border="1" align="center" cellpadding="1" cellspacing="0">
<caption>COMPROBANTE DE INSCRIPCIÓN</caption>
  <tr>
    <td width="50%" align="left" class="Estilo5">Per&iacute;odo Acad&eacute;mico: </td>
    <td width="50%"><? print OCIResult($QryCTransferencia,1);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Credencial:</td>
    <td width="50%"><? print OCIResult($QryCTransferencia,2);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Proyecto Curricular: </td>
    <td><? print OCIResult($QryCTransferencia,3);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tipo de Inscripción:</td>
    <td><? print OCIResult($QryCTransferencia,4);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Universidad de donde viene:</td>
    <td><? print OCIResult($QryCTransferencia,5);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Carrera que venia cursando:</td>
    <td><? print OCIResult($QryCTransferencia,6);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">&Uacute;ltimo semestre cursado:</td>
    <td><? print OCIResult($QryCTransferencia,7);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Pais de Nacimiento:</td>
    <td width="50%"><? print OCIResult($QryCTransferencia,8);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Departamento:</td>
    <td width="50%"><? print OCIResult($QryCTransferencia,9);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Municipio:</td>
    <td width="50%"><? print OCIResult($QryCTransferencia,10);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Fecha de Nacimiento:</td>
    <td width="50%"><? print OCIResult($QryCTransferencia,11);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Sexo:</td>
    <td width="50%"><? print OCIResult($QryCTransferencia,12);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Estado Civil: </td>
    <td width="50%"><? print OCIResult($QryCTransferencia,13);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Direcci&oacute;n:</td>
    <td width="50%"><? print OCIResult($QryCTransferencia,14);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Localidad de Residencia: </td>
    <td width="50%"><? print OCIResult($QryCTransferencia,15);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Estrato de Residencia: </td>
    <td width="50%"><? print OCIResult($QryCTransferencia,16);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tel&eacute;fono:</td>
    <td width="50%"><? print OCIResult($QryCTransferencia,17);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Correo Electr&oacute;nico: </td>
    <td width="50%"><? print OCIResult($QryCTransferencia,18);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Documento de Identidad: </td>
    <td width="50%"><? print OCIResult($QryCTransferencia,19);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Documento con que present&oacute; el ICFES: </td>
    <td width="50%"><? print OCIResult($QryCTransferencia,20);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">SNP:</td>
    <td width="50%"><? print OCIResult($QryCTransferencia,21);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Localidad del Colegio: </td>
    <td width="50%"><? print OCIResult($QryCTransferencia,22);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">&nbsp;</td>
    <td width="50%">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Fecha de Impresión:</td>
    <td width="50%"><? print OCIResult($QryCTransferencia,23);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">C&oacute;digo de Seguridad: </td>
    <td width="50%"><? print OCIResult($QryCTransferencia,24);?></td>
  </tr>
</table>
<p align="center">
Su inscripción ha sido recibida. Imprima y conserve este comprobante, deber&aacute; presentarlo en el momento de entregar los soportes.</p>
<p align="center">El diligenciamiento del presente formulario indica que el aspirante ha conocido el instructivo y las condiciones de admisiones de la Universidad Distrital U.D.F.J.C<br>
    <input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:80" title="Clic par imprimir el reporte">
    <input type="button" value="Salir" onClick="salir()" style="width:80; cursor:pointer">
</p>
  </fieldset>
</td></tr>
</table>
  
</fieldset>
<p></p>
<?php
OCIFreeCursor($QryCTransferencia);
fu_pie();
ob_end_flush();
?>
</body>
</html>