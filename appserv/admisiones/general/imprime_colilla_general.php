<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_general.'msql_ano_per.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_general.'asp_pie_pagAdm.php');

require_once(dir_general.'valida_usuario_prog.php');
require_once(dir_general.'valida_http_referer.php');
//require_once(dir_general.'valida_inscripcion.php');

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
require_once(dir_general.'msql_colilla_general.php');
//echo $QryColillaGen;
?>

<table width="95%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
  <br>

<table width="95%"  border="1" align="center" cellpadding="1" cellspacing="0" style="border-collapse:collapse">
<caption>COMPROBANTE DE INSCRIPCI&Oacute;N</caption>
  <tr>
    <td width="50%" align="left" class="Estilo5">Per&iacute;odo acad&eacute;mico: </td>
    <td width="50%"><? print OCIResult($QryColillaGen,1).'-'.OCIResult($QryColillaGen,2);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Credencial:</td>
    <td width="50%"><? print OCIResult($QryColillaGen,3);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tipo de inscripci&oacute;n:</td>
    <td><? print OCIResult($QryColillaGen,7);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Nombre:</td>
    <td><? print OCIResult($QryColillaGen,9).' '.OCIResult($QryColillaGen,8);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">N&uacute;mero de identificaci&oacute;n:</td>
    <td><? print OCIResult($QryColillaGen,10);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Proyecto Curricular al que se inscribi&oacute;: </td>
    <td><? print OCIResult($QryColillaGen,4).' - '.OCIResult($QryColillaGen,5);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Sexo:</td>
    <td><? print OCIResult($QryColillaGen,11);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Prest&oacute; servicio militar: </td>
    <td><? print OCIResult($QryColillaGen,12);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Localidad de residencia:</td>
    <td><? print OCIResult($QryColillaGen,13);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Estrato de residencia:</td>
    <td><? print OCIResult($QryColillaGen,14);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Medio por el cual se enter&oacute; de la Universidad:</td>
    <td width="50%"><? print OCIResult($QryColillaGen,15);?></td>
  </tr>
  </table>
  <p></p>
  <table width="95%"  border="1" align="center" cellpadding="1" cellspacing="0" style="border-collapse:collapse">
  <caption>INFORMACI&Oacute;N DEL ICFES</caption>
  <tr>
    <td align="left" class="Estilo5">N&uacute;mero del SNP:</td>
    <td align="left" class="Estilo14"><? print OCIResult($QryColillaGen,16);?></td>
    <td>&nbsp;</td>
    <td align="left" class="Estilo5">Espa&ntilde;ol y Literatura: </td>
    <td><? print OCIResult($QryColillaGen,24);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tipo de ICFES:</td>
    <td align="left" class="Estilo14"><? print OCIResult($QryColillaGen,17);?></td>
    <td>&nbsp;</td>
    <td align="left" class="Estilo5">Aptitud Matem&aacute;tica: </td>
    <td><? print OCIResult($QryColillaGen,25);?></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="Estilo5">Ciencias Sociales: </td>
    <td><? print OCIResult($QryColillaGen,18);?></td>
    <td align="left" class="Estilo5">Conocimiento Matem&aacute;tico:</td>
    <td><? print OCIResult($QryColillaGen,26);?></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="Estilo5">Biolog&iacute;a:</td>
    <td><? print OCIResult($QryColillaGen,19);?></td>
    <td align="left" class="Estilo5">Filosof&iacute;a:</td>
    <td><? print OCIResult($QryColillaGen,27);?></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="Estilo5">Qu&iacute;mica:</td>
    <td><? print OCIResult($QryColillaGen,20);?></td>
    <td align="left" class="Estilo5">Historia:</td>
    <td><? print OCIResult($QryColillaGen,28);?></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="Estilo5">F&iacute;sica:</td>
    <td><? print OCIResult($QryColillaGen,21);?></td>
    <td align="left" class="Estilo5">Geograf&iacute;a:</td>
    <td><? print OCIResult($QryColillaGen,29);?></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="Estilo5">Sociales:</td>
    <td><? print OCIResult($QryColillaGen,22);?></td>
    <td align="left" class="Estilo5">Idioma:</td>
    <td><? print OCIResult($QryColillaGen,30);?></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="Estilo5">Aptitud Verbal: </td>
    <td><? print OCIResult($QryColillaGen,23);?></td>
    <td align="left" class="Estilo5">Electiva:</td>
    <td><? print OCIResult($QryColillaGen,31);?></td>
  </tr>
</table>
<p align="center">
Su inscripci&oacute;n ha sido recibida. Imprima y conserve este comprobante.</p>
<p align="center">El diligenciamiento del presente formulario indica que el aspirante ha conocido el instructivo y las condiciones de admisiones de la Universidad Distrital U.D.F.J.C<br>
    <br>
    <input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:80" title="Clic para imprimir el reporte">
    <input type="button" value="Salir" onClick="salir()" style="width:80; cursor:pointer">
</p>
  </fieldset>
</td></tr>
</table>
  
</fieldset>
<p></p>
<?php
OCIFreeCursor($QryColillaGen);
fu_pie();
ob_end_flush();
?>
</body>
</html>
