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
require_once(dir_general.'msql_colilla_reingreso.php');
if(OCIResult($QryCReingreso,10) != ""){
   $CraCursando = '<tr><td align="left" class="Estilo5">Carrera que venía cursando:</td>
   <td width="50%">'.OCIResult($QryCReingreso,10).'</td></tr>
   <tr><td align="left" class="Estilo5">Carrera a la que se transfiere:</td>
   <td width="50%">'.OCIResult($QryCReingreso,11).'</td></tr>';
}
?>
<p align="center" class="Estilo6">FORMULARIO DE REINGRESO O TRANSFERENCIA INTERNA<br><? print $periodo; ?></p>

<table width="95%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
  <br>

<table width="75%"  border="1" align="center" cellpadding="1" cellspacing="0">
<caption>COMPROBANTE DE INSCRIPCIÓN</caption>
  <tr>
    <td width="50%" align="left" class="Estilo5">Per&iacute;odo Acad&eacute;mico: </td>
    <td width="50%"><? print OCIResult($QryCReingreso,1);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Credencial:</td>
    <td width="50%"><? print OCIResult($QryCReingreso,2);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tipo de Inscripción:</td>
    <td><? print OCIResult($QryCReingreso,3);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">N&uacute;mero de identificaci&oacute;n :</td>
    <td><? print OCIResult($QryCReingreso,4);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">C&oacute;digo de estudiante en la Universidad Distrital:</td>
    <td><? print OCIResult($QryCReingreso,5);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Cancel&oacute; semestre :</td>
    <td><? print OCIResult($QryCReingreso,6);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Motivo del retiro:</td>
    <td width="50%"><? print OCIResult($QryCReingreso,7);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tel&eacute;fono:</td>
    <td width="50%"><? print OCIResult($QryCReingreso,8);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Correo electrónico:</td>
    <td width="50%"><? print OCIResult($QryCReingreso,9);?></td>
  </tr>
  
  <? print $CraCursando; ?>
  
  <tr>
    <td align="left" class="Estilo5">&nbsp;</td>
    <td width="50%">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Fecha de Impresión:</td>
    <td width="50%"><? print OCIResult($QryCReingreso,12);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">C&oacute;digo de Seguridad: </td>
    <td width="50%"><? print OCIResult($QryCReingreso,13);?></td>
  </tr>
</table>
<p align="center">
Su inscripción ha sido recibida. Imprima y conserve este comprobante.</p>
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
OCIFreeCursor($QryCReingreso);
fu_pie();
ob_end_flush();
?>
</body>
</html>
