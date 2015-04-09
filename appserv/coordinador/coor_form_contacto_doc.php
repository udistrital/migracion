<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'mensaje_error.inc.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$usuario = $_SESSION["usuario_login"];
$nivel = $_SESSION["usuario_nivel"];

require_once(dir_script.'NombreUsuario.php');
?>
<html> 
<head> 
<title>Contacto</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head> 
<body>
<hr noshade class="hr">
<form action="coor_envia_contacto_doc.php" method="post" enctype="multipart/form-data">
<table width="440" border="1" align="center" cellpadding="0" cellspacing="0">
<tr>
  <td height="18" colspan="2" align="center">
  <span class="Estilo10">Los campos con asterisco son de car&aacute;cter obligatorio.</span></td>
</tr>
<tr>
  <td align="right"><font color="#FF0000">*</font>Nombre:</td>
  <td width="383" align="left"><input name="nombre" type="text" value="<? echo $Nombre; ?>" size="57" readonly="true"></td>
</tr>
<tr>
  <td width="52" align="right"><font color="#FF0000">*</font>De:</td>
  <td width="399" align="left"> 
  <input name="su_email" type=text id="su_email" value="<? echo $Email; ?>" size=55 readonly></td>
</tr>
<tr> 
  <td width="52" align="right"><font color="#FF0000">*</font>Para:</td>
  <td><input name="email" type=text value="<? echo isset($_REQUEST['para'])?$_REQUEST['para']:""; ?>" size=55 readonly></td>
</tr>
<tr> 
  <td width="52" height="24" align="right"><font color="#FF0000">*</font>Asunto:</td>
  <td><input type=text name="asunto" size=55></td>
</tr>
<tr>
  <td height="24" align="right">Adjuntar:</td> 
   <td><input type=hidden value=307200 name=MAX_FILE_SIZE><input name="archivo" type="file" accept="text/*" size="42"></td>
</tr>
<tr> 
  <td colspan="2" align="center"><textarea name="mensaje" cols=65 rows=8 id="mensaje"></textarea></td></tr>
<tr> 
  <td colspan="2"> 
  <table width="451" border="0" cellpadding="0" cellspacing="0">
<tr> 
  <td height="20" colspan="2" align="center">
<?php
if(isset($_REQUEST['error_login'])){
   $error=$_REQUEST['error_login'];
   echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='2' color='#FF0000'>
   <a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</a></font>";
}
?>
</td>
</tr>
<tr>
<td width="224" height="26" align="center"><input name="envia" type="submit" id="envia" value="Enviar"></td>
<td width="227" align="center"><input name="limpia" type="reset" id="limpia" value="Limpiar"></td>
</tr>
</table>
</td>
</tr>
</table>
</form> 
</body> 
</html> 