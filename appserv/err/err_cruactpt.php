<?PHP
require_once('dir_relativo.cfg');
require_once(dir_script.'fu_pie_pag.php');
?>
<HTML>
<HEAD><TITLE>Oficina Asesora de Sistemas</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</HEAD>
<body>
<p>&nbsp;</p>
<table width="60%" height="60%" border="1" cellpadding="0" cellspacing="0" align="center">
<tr>
  <td valign="top">
  </td>
  </tr>
<tr align="center">
  <td valign="middle"> 
		
		<table width="70%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr><td valign="top">
		<div><h3>Error</h3></div>

		<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
		<p align="justify">La actividad que intenta ingresar presenta cruce con otra actividad de su plan de trabajo.</p>
		</fieldset>
	    <? print $_GET['err']; ?>
		<p align="justify" class="Estilo6">&nbsp;</p>
		<p align="center"><input type="button" name="Submit" value="Regresar" onClick="javascript:history.go(-1)" style="width:150"></p>
		  </td></tr>
		<tr><td align="center" class="Estilo10">Por seguridad, cambie la clave peri&oacute;dicamente.</td></tr>
	  </table>
        
    </td>
</tr>
</table>
<?php fu_pie(); ?>
</body>
</html>