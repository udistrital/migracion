<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(80);
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
  <u class="Estilo5">ADMINISTRACI&Oacute;N  USUARIOS DE C&Oacute;NDOR</u></h3>
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
          <li class="ItemStyle">&nbsp;Para controlar la <strong>fecha final del proceso de digitaci&oacute;n de notas parciales</strong>, se requiere que se actualicen las fechas, el a&ntilde;o y el periodo a cada carrera. <strong>evento 7</strong>. (Para posgrados, no cambiar el e&ntilde;o ni el per&iacute;odo actual.)</li>
          <li class="ItemStyle">&nbsp;Cargar docentes y estudiantes nuevos en la tabla de control de acceso.</font></li>
          <li class="ItemStyle">&nbsp;Encriptar las claves a los nuevos usuarios, &quot;Encriptar Claves&quot;.</font></li>
          <li class="ItemStyle">&nbsp;Desactivar en la tabla de usuarios, a los Decanos, Coordinadores, docentes, estusiantes y funcionarios que no esten activos en la instituci&oacute;n.</font></li>
          <li class="ItemStyle">&nbsp;Actualizar las fechas del evento <strong>10</strong> para controlar que los estudiantes puedan seleccionar el <strong>diferido de matricula</strong>.</li>
          <li class="ItemStyle">&nbsp;Actualizar el campo est_diferido en &quot;N&quot;, para que los estudiantes puedan seleccionar el diferido de matricula. (Dos ultimas semana de clase).</font></li>
          <li class="ItemStyle">&nbsp;Para controlar la digitaci&oacute;n de notas de <strong>cursos de vacacione</strong>s, se requiere que se actualicen las fechas, el a&ntilde;o y el periodo a cada carrera en la tabla de control de eventos en el <strong>evento 52</strong>.</font></li>
          <li class="ItemStyle">&nbsp;Controlar <strong>digitaci&oacute;n de horarios</strong> de cursos de vacaciones,<strong>evento 22</strong>.</font></li>
          <li class="ItemStyle">&nbsp;Controlar <strong>carga acad&eacute;mica de cursos de vacaciones</strong>,evento 32.</font></li>
          <li class="ItemStyle">&nbsp;Controlar fechas de <strong>&quot;Adici&oacute;n&quot;</strong> de asignaturas,<strong>evento 15</strong>.</font></li>
          <li class="ItemStyle">&nbsp;Controlar fechas de <strong>&quot;Cancelaci&oacute;n&quot;</strong> de asignaturas,<strong>evento 16</strong>.</font></li>
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