<?php
require_once('dir_relativo.cfg');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
?>
<HTML>
<HEAD>
<TITLE>Oficina Asesora de Sistemas</TITLE>
<script language="JavaScript" src="../script/KeyIntro.js"></script>
<link href="estilo_adm.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY onLoad="this.document.doc.estcod.focus();">
<?php
fu_tipo_user(20);
fu_cabezote("CONSULTA DE ESTUDIANTES");
?>
<FORM NAME='doc' method="post" ACTION="adm_actualiza_datos_est.php" target="inferior">
<table width="298" border="0" align="center"><tr>
<td align="right">
<input name='estcod' type='text' size='15' style="text-align: right" onKeyPress="check_enter_key(event,document.getElementById('doc')); if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;">
</td><td><input type='Submit' value='Consultar Estudiante' class="button" <? print $evento_boton;?>></td></tr></table></FORM>
</BODY>
</HTML>