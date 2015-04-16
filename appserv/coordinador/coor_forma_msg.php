<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../calendario/calendario.php');
require_once(dir_script."mensaje_error.inc.php");
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


if(!isset($_REQUEST['tipo'])){
    $_REQUEST['tipo']=$_SESSION['usuario_nivel'];
}

if($_REQUEST['tipo']==110){
    fu_tipo_user(110);
    $tipo=110; 
}elseif($_REQUEST['tipo']==114){
    fu_tipo_user(114);
    $tipo=114; 
}elseif($_REQUEST['tipo']==4){
    fu_tipo_user(4);
    $tipo=4; 
}

?>
<html>
<head>
<title>Publicar Noticias</title>
<script language="JavaScript" src="../calendario/javascripts.js"></script>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">

</head>
<body onLoad="this.document.dat.autor.focus();">
<?php
fu_cabezote("ADMINISTRACI&Oacute;N DE NOTICIAS");
global $raiz;
$nombreformulario = "dat";
$nombrecampo1 = "fecini";
$nombrecampo2 = "fecfin";

print'<p>&nbsp;</p><form name="dat" id="dat" METHOD="post" ACTION="prog_insertar_msg.php">
<div align="center">
<table width="608" col="2" cellspacing="0" cellpadding="1" border="1">
<tr class="tr"><td colspan="3" align="center" width="608">Publicaci&oacute;n de Noticias</td></tr>

<tr>
  <td colspan="3" align="center" class="Estilo10">Los campos con asterisco son de caracter obligatorio.</td>
  </tr>
<tr>
  <td align="right" width="608"><font color="#FF0000" face="Tahoma" size="2">*</font>Autor:</td>
  <td width="525" colspan="2"><input type="text" name="autor" size="53" maxlength="30" onChange="javascript:this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
  <td align="right" width="608"><font color="#FF0000" size="2" face="Tahoma">*</font>Proyecto Curricular:</td>
  <td width="525"><input type="text" name="cracod" size="12" value="'.$_SESSION['carrera'].'" readonly></td>
  <td width="305">
      <select size="1" name="para" style="font-size: 10pt; font-family: Tahoma">
      <option value="51" selected>Estudiantes</option>
      </select>
  </td>
</tr>
<tr>
  <td width="608" align="right"><font color="#FF0000" face="Tahoma" size="2">*</font>Fecha Inicial: </td>
  <td width="525" colspan="2"><input name="fecini" type="text" id="fecini" size="12" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo1.'\')" readonly>DD/MM/AAAA</td>
</tr>

<tr>
  <td width="608" align="right"><font color="#FF0000" face="Tahoma" size="2">*</font>Fecha Final: </td>
  <td width="525" colspan="2"><input name="fecfin" type="text" id="fecfin" size="12" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo2.'\')" readonly>DD/MM/AAAA</td>
</tr>
<tr>
  <td align="right" width="608"><font color="#FF0000" face="Tahoma" size="2">*</font>Titulo:</td>
  <td width="525" colspan="2"><input type="text" name="titulo" size="53" maxlength="50"></td>
</tr>

<tr> 
  <td align="right" valign="top" width="608"><font color="#FF0000" face="Tahoma" size="2">*</font>Contenido:</td>
  <td width="525" colspan="2"><textarea name="contenido" cols="63" rows="9" wrap="true"></textarea></td>
</tr>

<tr> 
  <td align="center" width="608" colspan="3">
  
  <div align="center">
  <table border="0" width="100%" cellspacing="1" cellpadding="0">
  <tr>
	<td width="25%" align="center"><a HREF="coor_admin_msg.php">
    <IMG SRC="../img/b_home.png" alt="Administraci&oacute;n de noticias" border="0"></a></td>
	<td width="25%" align="center"><input type="submit" value=" Publicar"></td>
	<td width="25%" align="center"><input type="reset" value="Borrar"></td>
	<td width="25%" align="center"><a href="coor_index_msg.php">
	<IMG SRC="../img/b_browse.png" alt="Listado de noticias" border="0"></a></td>
  </tr>
  <tr>
	<td width="100%" align="center" colspan="4"><p></p>';
	if(isset($_REQUEST['error_login'])){
       $error=$_REQUEST['error_login'];
       echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'><img src='../img/asterisco.gif'>$error_login_ms[$error]</font>
	   <BR><INPUT TYPE='button' VALUE=' Corregir ' onClick='history.go(-1)' style='background-color: #0099CC; color: #FFFFFF; border: 3 outset #06C1FF'>";
    }	
	echo'</td>
  </tr>
  </table>
  </div>
</td>
</tr>
</table>
</div>
</form>';
?>
</body>
</html>