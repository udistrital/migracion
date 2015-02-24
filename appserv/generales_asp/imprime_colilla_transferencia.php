<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_general.'msql_ano_per.php');
require_once(dir_general.'valida_usuario_prog.php');
require_once(dir_general.'valida_http_referer.php');
require_once(dir_general.'valida_inscripcion_ver_colilla.php');
?>
<html>
<head>
<title>Aspirantes</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="Logout.js"></script>
</head>

<body>
<?php

require_once(dir_general.'msql_colilla_transferencia.php'); 
?>
<p align="center" class="Estilo6">FORMULARIO DE TRANSFERENCIA EXTERNA<br><? print $periodo; ?></p>

<table width="95%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
  <br>

<table width="75%"  border="1" align="center" cellpadding="1" cellspacing="0">
<caption>COMPROBANTE DE INSCRIPCI&Oacute;N</caption>
  <tr>
    <td width="50%" align="left" class="Estilo5">Per&iacute;odo Acad&eacute;mico: </td>
    <td width="50%"><? print $RowCTransferencia[0][0];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Credencial:</td>
    <td width="50%"><? print $RowCTransferencia[0][1];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Proyecto Curricular: </td>
    <td><? print $RowCTransferencia[0][2];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tipo de Inscripci&oacute;n:</td>
    <td><? print $RowCTransferencia[0][3];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Universidad de donde viene:</td>
    <td><? print $RowCTransferencia[0][4];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Carrera que venia cursando:</td>
    <td><? print $RowCTransferencia[0][5];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">&Uacute;ltimo semestre cursado:</td>
    <td><? print $RowCTransferencia[0][6];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Pais de Nacimiento:</td>
    <td width="50%"><? print $RowCTransferencia[0][7];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Departamento:</td>
    <td width="50%"><? print $RowCTransferencia[0][8];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Municipio:</td>
    <td width="50%"><? print $RowCTransferencia[0][9];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Fecha de Nacimiento:</td>
    <td width="50%"><? print $RowCTransferencia[0][10];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Sexo:</td>
    <td width="50%"><? print $RowCTransferencia[0][11];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Estado Civil: </td>
    <td width="50%"><? print $RowCTransferencia[0][12];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Direcci&oacute;n:</td>
    <td width="50%"><? print $RowCTransferencia[0][13];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Localidad de Residencia: </td>
    <td width="50%"><? print UTF8_DECODE($RowCTransferencia[0][14]);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Estrato de Residencia: </td>
    <td width="50%"><? print $RowCTransferencia[0][15];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tel&eacute;fono:</td>
    <td width="50%"><? print $RowCTransferencia[0][16];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Correo Electr&oacute;nico: </td>
    <td width="50%"><? print $RowCTransferencia[0][17];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Documento de Identidad: </td>
    <td width="50%"><? print $RowCTransferencia[0][18];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Documento con que present&oacute; el ICFES: </td>
    <td width="50%"><? print $RowCTransferencia[0][19];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">SNP:</td>
    <td width="50%"><? print $RowCTransferencia[0][20];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Localidad del Colegio: </td>
    <td width="50%"><? print UTF8_DECODE($RowCTransferencia[0][21]);?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">&nbsp;</td>
    <td width="50%">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Fecha de Impresi&oacute;n:</td>
    <td width="50%"><? print $RowCTransferencia[0][22];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">C&oacute;digo de Seguridad: </td>
    <td width="50%"><? print $RowCTransferencia[0][23];?></td>
  </tr>
</table>
<p align="center">
Imprima y conserve este comprobante, deber&aacute; presentarlo en el momento de entregar los soportes.</p>

<p align="center">El diligenciamiento del presente formulario indica que el aspirante ha conocido el instructivo oficial de admisiones del periodo acad&eacute;mico <? print $periodo; ?> y acorde con la ley 1581 de 2012, autoriza de manera expresa e inequ&iacute;voca, que mis datos personales sean tratados conforme a las funciones propias de la Universidad, en su condici&oacute;n de Instituci&oacute;n de Educaci&oacute;n Superior. <br>
<? 
$cadena=$RowCTransferencia[0][20];
$snp=substr($cadena, 0, 7);
if ($snp=='AC20102')
{
	echo "<p align='justify'>Se&ntilde;or aspirante, tenga en cuenta que si su ex&aacute;men de estado ICFES o SABER 11, fue presentado en septiembre de 2010, este ser&aacute; transformado a la escala fija de las puntuaciones normalizadas en la escala hist&oacute;rica del ex&aacute;men SABER 11. Mayor informaci&oacute;n consultar las siguientes direcciones electr&oacute;nicas: <br>
                    . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/659-informacion-clave-para-jefes-de-admision<br>
                    . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/694-informaci%C3%B3n-importante-para-jefes-de-admisi%C3%B3n-2";
}    
?>
</fieldset>
</td></tr>
</table>
<p align="justify"><font color="red">
<b>NOTA:</b> En el evento de no quedar registrados los datos de la inscripci&oacute;n en el presente comprobante, vuelva a ingresar y  realice nuevamente
el proceso de inscripci&oacute;n; de llegar a persisitir esta situaci&oacute;n, favor acercarse a la Oficina de Admisiones ubicada en la carrera 8 No 40-62, primer piso, Edificio Sabio Caldas,
telefonos 3239300 Ext. 1102, o remitir su inquietud al correo electr&oacute;nico admisiones@udistrital.edu.co, dentro de los plazos asignados en el proceso de admisiones.
</font></p>
<center><input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:80" title="Clic par imprimir el reporte"></center>
<?php
fu_pie();
?>
</body>
</html>
