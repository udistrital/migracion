<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'mensaje_error.inc.php');
include_once("../clase/multiConexion.class.php");
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(104);
?>
<html>
<head>
<title>Administraci&oacute;n</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/fecha.js"></script>
</head>
<body onLoad="show5()">

<div align="center">
  <h3 style="background-image:url(../img/td.gif)"><span class="Estilo1">UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS</span></h3>
  <h3><span class="Estilo1">Oficina Asesora de Sistemas</span><br>
  <u class="Estilo5">ASPU</u></h3>
</div>

<table width="80%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr> 
<td width="70%"><SCRIPT>dia()</SCRIPT></td>

<td width="30%" align="right"> 
 <span id="liveclock" style="margin-left:0; margin-top:0; width:213; height:19"></span> 
 <script language="JavaScript1.1" src="../script/reloj.js"></script>
</td>
</tr>
</table>

<table width="80%" border="0" align="center" bgcolor="#FFFFFF">
  <tr>
    <td>
	<fieldset style="padding:10">
      <ul>
          <li class="ItemStyle">&nbsp;</li>
    </ul>
	</fieldset>
	</td>
  </tr>
</table>
<?php 
  if(isset($_REQUEST['error_login'])){
   	 $error=$_REQUEST['error_login'];
   	 echo"<center><font face='Tahoma' size='2' color='#FF0000'><b>$error_login_ms[$error]</b></font></center>";
  }
?>
<br>
<br>
<p>&nbsp;</p>
</body>
</html>