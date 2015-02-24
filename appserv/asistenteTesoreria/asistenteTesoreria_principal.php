<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(122);
?>
<html>
<head>
<title>Soporte</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<link href="estilo_adm.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/fecha.js"></script>
<link href="../script/classic.css" rel="stylesheet" type="text/css"> 
</head>
<body onLoad="show5()">

<div align="center">
  <h3 style="background-image:url(../img/td.gif)"><span class="Estilo4">UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS</span></h3>
  <h3><span class="Estilo1">Oficina Asesora de Sistemas</span><br>
  <u class="Estilo5">ASISTENTE DE TESORERÍA</u></h3>
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
          <li class="ItemStyle">Bienvenido al módulo de asistente de tesorería, en este módulo usted podrá consultar los certificados de ingresos y retenciones de los funcionarios de planta y contratistas de la Universidad Distrital Francisco José de Caldas.</li>
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
</body>
</html>