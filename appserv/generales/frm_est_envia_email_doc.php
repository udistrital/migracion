<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(51);
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

<?php
fu_cabezote("CONTACTAR AL DOCENTE");

if(empty($_GET['usu'])){
   $usuario = $_SESSION['usu'];
}
else{
     $_SESSION['usu'] = $_GET['usu'];
     $usuario = $_GET['usu'];
}

$nivel=30;
require_once(dir_script.'NombreUsuario.php');
$docnombre=$Nombre;
$docmail=$Email;

$QryNombre = OCIParse($oci_conecta, "SELECT est_nombre, NVL(eot_email, 'Actualize sus datos, agregue un email.')
   									   FROM acest,acestotr
									  WHERE est_cod = ".$_SESSION['usuario_login']."
										AND est_cod = eot_cod");
OCIExecute($QryNombre) or die(Ora_ErrorCode());
$RowNombre = OCIFetch($QryNombre);

echo'<form action="est_envia_email_doc.php" method="post">
<center>Los campos con asterisco son de car&aacute;cter obligatorio.</center>
<table width="451" border="1" align="center" cellspacing="0" cellpadding="0">
<tr>
  <td height="18" colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Para:<font face="Tahoma" size="2" color="blue"><b>'.$docnombre.'</b></td>
</tr>
<tr>
  <td align="right">E-mail:</td>
  <td align="left"><input name="emaildoc" type=text value="'.$docmail.'" size=57 readonly></td>
</tr>
<tr>
  <td width="59" align="right">De:</td>
  <td width="382" align="left">
  <input type=text name="nombre" size=57 value="'.OCIResult($QryNombre, 1).'" onChange="javascript:this.value=this.value.toUpperCase();" readonly></td>
</tr>
<tr> 
  <td width="59" align="right">E-mail:</td>
  <td><input type=text name="emailest" size=57 value="'.OCIResult($QryNombre, 2).'" onChange="javascript:this.value=this.value.toLowerCase();" readonly></td>
</tr>
<tr> 
<td width="59" height="24" align="right"><font color="#FF0000" face="Tahoma">*</font>Asunto:</td>
  <td><input name="asunto" type=text value="" size=57>
  </td>
</tr>
<tr>
  <td height="24" align="right">Adjuntar:</td>
  <td><INPUT type=hidden value=307200 name=MAX_FILE_SIZE><input type=file size=44 maxlength=100000 name="archivo" accept="text/*"></td>
</tr>
<tr> 
  <td colspan="2" align="right">    
    <div align="center">
	  <!--webbot bot="SaveResults" U-File="fpweb:///_private/form_results.txt" S-Format="TEXT/CSV" S-Label-Fields="TRUE" -->
      <!--webbot bot="Validation" B-Value-Required="TRUE" I-Minimum-Length="250" I-Maximum-Length="5" --> 
      <textarea name="mensaje" cols=76 rows=7 id="mensaje"></textarea>
    </div></td>
  </tr>
<tr> 
  <td colspan="2"> 
  <table width="453" border="0">
<tr> 
  <td height="20" colspan="2" align="center">';

if(isset($_GET['error_login'])){
   $error=$_GET['error_login'];
   echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='2' color='#FF0000'>
   <a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</font></a>";
}

echo' 
  </td>
  </tr>
<tr> 
<td width="219" height="26"><p align="center">
  <input name="submit" type=submit value="Enviar">

</td>
<td width="215"><div align="center">
  <input name="RESET" type = "RESET" value = " Borrar ">
</div></td></tr></table></td></tr></table>
</form><p>&nbsp;</p>';
OCIFreeCursor($QryNombre);
OCILogOff($oci_conecta);
fu_pie(); 
?>
</body> 
</html> 