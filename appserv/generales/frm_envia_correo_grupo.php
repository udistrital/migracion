<?PHP
require_once('dir_relativo.cfg');
require_once(dir_script.'mensaje_error.inc.php');

//$Nombre = isset($_REQUEST['DocNombre']);

$Nombre = isset($_REQUEST['DocNombre']) ? $_REQUEST['DocNombre']:NULL;
$Correo = isset($_REQUEST['DocCorreo']) ? $_REQUEST['DocCorreo']:NULL;


$msg=(isset($msg)?$msg:'');
?>
<html> 
<head> 
<title>Contacto</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<form action="envia_contacto.php" method="post" enctype="multipart/form-data" >
<table width="453" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
  <td height="18" colspan="2" align="center">
    <span class="Estilo10"><? print $msg; ?></span></td>
</tr>
<tr>
  <td height="18" colspan="2" align="center">
    <span class="Estilo2">Los campos con asterisco son de car&aacute;cter obligatorio.</span></td>
</tr>
<tr>
  <td align="right">Para:</td>
  <td align="left"><textarea name="pemail" cols="68"><?  if(isset($_REQUEST['ctas'])) print $_REQUEST['ctas']; ?></textarea></td>
</tr>
<tr>
  <td align="right"><font color="#FF0000">*</font>Nombre:</td>
  <td width="383" align="left"><input name="nombre" type="text" value="<? echo $Nombre; ?>" size="57" readonly="true"></td>
</tr>
<tr> 
  <td align="right"><font color="#FF0000">*</font>E-mail:</td>
  <td><input name="email" type="text" value="<? echo $Correo; ?>" size="57" readonly="true"></td>
</tr>
<tr> 
<td align="right"><font color="#FF0000">*</font>Asunto:</td>
  <td><input type="text" name="asunto" size=57></td>
</tr>
<tr>
  <td height="24" align="right">Adjuntar:</td>
  <td><input type="file" size=44 maxlength=100000 name="archivo" accept="text/*"></td>
</tr>
<tr>
  <td colspan="2" align="center">
	  <!--webbot bot="SaveResults" U-File="fpweb:///_private/form_results.txt" S-Format="TEXT/CSV" S-Label-Fields="TRUE" -->
      <!--webbot bot="Validation" B-Value-Required="TRUE" I-Minimum-Length="250" I-Maximum-Length="5" --> 
      <textarea name="mensaje" cols=74 rows=8 id="mensaje"></textarea>
    </td>
  </tr>
<tr> 
  <td colspan="2"><table width="453" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2" align="center">
<?php
if(isset($_REQUEST['error_login'])){
	//<INPUT TYPE=HIDDEN VALUE=507200 NAME=MAX_FILE_SIZE>
   $error=$_REQUEST['error_login'];
   echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='2' color='#FF0000'>
   <a OnMouseOver='history.go(-1)'><img src='../img/asterisco.gif'>$error_login_ms[$error]</a></font>";
}
?>
      </td>
      </tr>
    <tr>
      <td width="223" align="center">
        <input type="submit" name="enviar"  value="Enviar">
      </td>
      <td width="220" align="center">
        <input name="borrar" type = "RESET" value = " Borrar ">
      </td>
    </tr>
  </table>
  </td>
</tr>
</table>
</form>
</body>
</html>