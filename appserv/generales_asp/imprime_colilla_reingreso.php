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
//echo "*<br>";
require_once(dir_general.'msql_colilla_reingreso.php');
//echo "*<br>";



if($RowCReingreso[0][9]!= ""){
   $CraCursando = '<tr><td align="left" class="Estilo5">Carrera que ven&iacute;a cursando:</td>
   <td width="50%">'.$RowCReingreso[0][9].'</td></tr>
   <tr><td align="left" class="Estilo5">Carrera a la que se transfiere:</td>
   <td width="50%">'.$RowCReingreso[0][10].'</td></tr>';
}

?>
<p align="center" class="Estilo6">FORMULARIO DE REINGRESO O TRANSFERENCIA INTERNA<br><? print $periodo; ?></p>

<table width="95%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
  <br>

<table width="75%"  border="1" align="center" cellpadding="1" cellspacing="0">
<caption>COMPROBANTE DE INSCRIPCI&Oacute;N</caption>
  <tr>
    <td width="50%" align="left" class="Estilo5">Per&iacute;odo Acad&eacute;mico: </td>
    <td width="50%"><? print $RowCReingreso[0][0];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Credencial:</td>
    <td width="50%"><? print $RowCReingreso[0][1];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tipo de Inscripci&oacute;n:</td>
    <td><? print $RowCReingreso[0][2];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">N&uacute;mero de identificaci&oacute;n :</td>
    <td><? print $RowCReingreso[0][3];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">C&oacute;digo de estudiante en la Universidad Distrital:</td>
    <td><? print $RowCReingreso[0][4];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Cancel&oacute; semestre :</td>
    <td><? print $RowCReingreso[0][5];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Motivo del retiro:</td>
    <td width="50%"><? print $RowCReingreso[0][6];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tel&eacute;fono:</td>
    <td width="50%"><? print $RowCReingreso[0][7];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Correo electr&oacute;nico:</td>
    <td width="50%"><? print $RowCReingreso[0][8];?></td>
  </tr>
  
  <? print $CraCursando; ?>
  
  <tr>
    <td align="left" class="Estilo5">&nbsp;</td>
    <td width="50%">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Fecha de Impresi&oacute;n:</td>
    <td width="50%"><? print $RowCReingreso[0][11];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">C&oacute;digo de Seguridad: </td>
    <td width="50%"><? print $RowCReingreso[0][12];?></td>
  </tr>
</table>
<p align="center">
Imprima y conserve este comprobante.</p>
<p align="center">El diligenciamiento del presente formulario indica que el aspirante ha conocido el instructivo oficial de admisiones del periodo acad&eacute;mico <? print $periodo; ?> y acorde con la ley 1581 de 2012, autoriza de manera expresa e inequ&iacute;voca, que mis datos personales sean tratados conforme a las funciones propias de la Universidad, en su condici&oacute;n de Instituci&oacute;n de Educaci&oacute;n Superior. <br>
</p>
</fieldset>
</td></tr>
</table>
<p align="justify"><font color="red">
<b>NOTA:</b> En el evento de no quedar registrados los datos de la inscripci&oacute;n en el presente comprobante, vuelva a ingresar y  realice nuevamente
el proceso de inscripci&oacute;n; de llegar a persisitir esta situaci&oacute;n, favor acercarse a la Oficina de Admisiones ubicada en la carrera 8 No 40-62, primer piso, Edificio Sabio Caldas,
telefonos 3239300 Ext. 1102, o remitir su inquietud al correo electr&oacute;nico admisiones@udistrital.edu.co, dentro de los plazos asignados en el proceso de admisiones.
</font></p>
<center><input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:80" title="Clic par imprimir el reporte"></center>
  
  
</fieldset>
<p></p>

</body>
</html>
