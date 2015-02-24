<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
fu_tipo_user(80);
?>
<html>
<head>
<title>Docentes</title>
<script language="JavaScript" src="../script/KeyIntro.js"></script>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body onLoad="this.document.cambio.codigo.focus();">
<?php
fu_cabezote("CAMBIO DE CLAVES");

echo'<br><br><br><br><br><br><br><br><div align="center">
<form  name="cambio" method="POST" action="msql_update_geclaves.php">

<table border="0" width="350" cellpadding="0" cellspacing="3" cellpadding="0"  class="fondoTab">
<caption>CAMBIO DE CLAVE POR USUARIO</caption>
<tr> 
<td colspan="2"><div align="justify">
Digite el usuario. Digite la nueva clave, confirme la nueva clave 
y haga clic en el bot&oacute;n &quot;<strong>Cambiar Clave</strong>&quot;.</div></td></tr>
              
<tr> 
 <td>&nbsp;</td>
 <td>&nbsp;</td>
</tr>

<tr> 
 <td width="340" align="left"><span class="Estilo11">*</span>Usuario:</td>
 <td width="173"><input name="codigo" size="18" value=""></td>
</tr>

<tr> 
  <td width="340" align="left"><span class="Estilo11">*</span>Clave nueva:</td>
  <td width="173"><input type="password" name="nc" size="18" onChange="javascript:this.value=this.value.toLowerCase();">
  </td>
</tr>

<tr> 
 <td width="340" align="left"><span class="Estilo11">*</span>Confirme clave nueva:</td>
  <td width="173"><input type="password" name="rnc" size="18" onKeyPress="check_enter_key(event,document.getElementById(\'cambio\'))" onChange="javascript:this.value=this.value.toLowerCase();"></td>
</tr>
  
<tr>
<td width="340" colspan="2" align="center">&nbsp;'; 
if(isset($_REQUEST['error_login'])){ 
   $error=$_REQUEST['error_login']; 
   echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'> 
   <a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</a></font>"; 
}
echo'</font></td></tr><tr> 
<td width="350" colspan="2" align="center"><input type="submit" value="Cambiar Clave" name="bcc" class="button" '.$evento_boton.'></td>
</tr>
</table></form></div>';
?>
</body>
</html>