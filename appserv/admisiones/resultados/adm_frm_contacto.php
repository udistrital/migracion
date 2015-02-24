<?PHP
require_once('dir_relativo.cfg');
require_once('fu_pie_pagAdm.php');
require_once('../../script/mensaje_error.inc.php');
$log = "<embed width='57' height='58' src='../../img/cdr.swf'>";
?>
<html> 
<head> 
<title>Contacto</title>
<script>
var pagina="index.php";
function Gback() { 
	location.href=pagina;
} 
</script>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<p>&nbsp;</p>

<table width="750" height="460" align="center" cellpadding="3" cellspacing="0" style="border-color:#999999; border-style:double">
  <tr bgcolor="#E4E5DB">
    <td width="98" height="124">
	 <a href="http://www.udistrital.edu.co" title="Universidad Distrital Francisco José de Caldas" target="_self">
	 <img src="../../img/EscudoUD.gif" width="90" height="110" border="0"></a>
    </td>
    <td width="677" align="center">
	  <br><img src="../../img/12cw03003.png" border="0"><br>
      <span class="Estilo14">VICERRECTOR&Iacute;A ACAD&Eacute;MICA- COMIT&Eacute; DE ADMISIONES</span><br>
      <br>
      <span class="Estilo12">CONTACTAR CON EL COMIT&Eacute; DE ADMISIONES</span> </td>
    <td width="97" align="center" title="Sistema de Información Cóndor"><? echo $log ?><span class="Estilo9"><br>CÓNDOR</span></td>
  </tr>
  <tr>
   <td height="216" colspan="3">

<form action="../../generales/envia_contacto.php" method="post">
<table width="451" border="0" align="center">
<tr>
  <td height="18" colspan="2" align="center"><BR>
    <span class="Estilo10">Los campos con asterisco son de car&aacute;cter obligatorio.</span></td>
</tr>
<tr>
  <td width="59" align="right"><font color="#FF0000">*</font>Nombre:</td>
  <td width="382" align="left"><input type=text name="nombre" size=57 onChange="javascript:this.value=this.value.toUpperCase();"></td>
</tr>
<tr> 
  <td width="59" align="right"><font color="#FF0000">*</font>E-mail:</td>
  <td><input name="email" type=text id="email" onChange="javascript:this.value=this.value.toLowerCase();" size=57></td>
</tr>
<tr> 
<td width="59" height="24" align="right"><font color="#FF0000">*</font>Asunto:</td>
  <td><input type=text name="asunto" size=57></td>
</tr>
<tr> 
  <td colspan="2" align="center">
	  <!--webbot bot="SaveResults" U-File="fpweb:///_private/form_results.txt" S-Format="TEXT/CSV" S-Label-Fields="TRUE" -->
      <!--webbot bot="Validation" B-Value-Required="TRUE" I-Minimum-Length="250" I-Maximum-Length="5" --> 
      <textarea name="mensaje" cols="76" rows="8" id="mensaje" style="background-image:url(../../img/escudo_fondo.png); background-attachment:fixed; background-repeat:no-repeat; background-position:bottom"></textarea>
    </td>
  </tr>
<tr> 
  <td colspan="2"> 
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr> 
  <td height="20" colspan="3" align="center">
<?php
if(isset($_GET['error_login'])){
   $error=$_GET['error_login'];
   echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='2' color='#FF0000'>
   <a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</a></font>";
}
?>
  </td>
  </tr>
<tr>
<td width="33%" align="center"><input name="submit" type=submit value="Enviar" style="cursor:pointer"></td>
<td width="33%" align="center"><input name="RESET" type = "RESET" value = " Borrar " style="cursor:pointer"></td>
<td width="33%" align="center"><input type="button" value="Regresar" OnClick="history.go(-1)" style="cursor:pointer"></td>
</tr>
</table>
</td>
</tr>
</table>
<input name="pemail" type="hidden" value="oasicfes@udistrital.edu.co">
</form>
</td>
   </tr>
   <tr>
     <td height="60" colspan="3"><? fu_pie(); ?></td>
   </tr>
</table>
</body> 
</html> 