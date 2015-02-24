<?PHP
if(!isset($_SESSION['usuario_login']) && !isset($_SESSION['usuario_password'])){
   require_once('conexion/conexion.php');
   require_once('conexion/atras.php');
}

$QryMes = OCIParse($oci_conecta, "SELECT TO_NUMBER(TO_CHAR(sysdate, 'MM')) FROM dual");
OCIExecute($QryMes) or die(Ora_ErrorCode());
$RowMes = OCIFetch($QryMes);
$Mes = OCIResult($QryMes,1);
OCIFreeCursor($QryMes);
//OCILogOff($oci_conecta);

$random = rand(1,14);
if($Mes != 12){
	$log = "<embed width='57' height='58' src='../img/cdr1.swf'>";
	$esc  = '<img src="../img/20cw03001.png" alt="Universidad Distrital Francisco Jos&eacute; de Caldas" border="0">';
	$oas = '<img src="../img/oas.gif" alt="Oficina Asesora de Sistemas" name="Image1" width="60" height="49" border="0">';
}
else{
	$log = "<embed width='57' height='58' src='../img/nav/cdr1_nav.swf'>";
	$esc  = '<img src="../img/20cw03001.png" alt="Universidad Distrital Francisco Jos&eacute; de Caldas" border="0">';
	$oas = '<img src="../img/nav/img'.$random.'.gif" alt="Feliz navidad y Prospero nuevo a&ntilde;o" border="0" width="80" height="69">';
}

require_once('general/msql_ano_per.php');
require_once('general/fecha_inscripcion.php');
ob_start();
?>
<HTML>
<HEAD><TITLE>Vicerrector&iacute;a Acad&eacute;mica - Comit&eacute; de Admisiones</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<LINK REL="SHORTCUT ICON" HREF="http://condor.udistrital.edu.co/appserv/img/favicon.ico">
<link href="../script/estilo_div.css" rel="stylesheet" type="text/css">
<link href="../script/estinx.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/md5.js"></script>
<script language="JavaScript" src="../script/Entrar.js"></script>
<script language="JavaScript" src="../script/MuestraLayer.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/modificado.js"></script>
<style type="text/css">
<!--
.Estilo14 {font-size: 9px}
-->
</style>
</HEAD>
<body onLoad="this.document.login.user.focus()">
<script LANGUAGE="JavaScript">
function quitarFrame(){
  if(self.parent.frames.length != 0) self.parent.location=document.location.href;
}
quitarFrame()
</script>
<!-- Capa de C�ndor  -->
<?php require_once('../generales/capa_condor.html'); ?>
<!-- Fin de la capa  -->

<table width="80%" height="80%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
  <td colspan="2" valign="top">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
      <td width="70%" align="left" valign="top"><a href="http://www.udistrital.edu.co" target="_self"><? echo $esc ?></a></td>
      <td width="30%" align="center" valign="middle"><? echo $log ?>
	  <a class="CapLink" onClick="MostrarCapa('C&oacute;ndor',120,580)" title="Haga clic para m&aacute;s informaci&oacute;n"><span class="CONDOR"><BR>C&Oacute;NDOR</span></a></td>
    </tr>
	<tr>
	  <td colspan="2" align="center"><hr width="100%" size="1"></td>
	  </tr>
	<tr>
	  <td colspan="2" align="center">
	  <span class="CONDOR">PROCESO DE ADMISIONES - PROGRAMAS DE PREGRADO<BR>REGISTRO DE ASPIRANTES AL <? print $periodo; ?></span>
	  <BR>
	  <? print $FecIns; ?>
	  <BR><BR>
	  </td>
	  </tr>
  </table>
 
  </td>
  </tr>
<tr align="center">
  <td width="57%" rowspan="2" valign="top"> 

		<table width="95%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr><td valign="top">
		<div style="width:100%">
		  <p align="justify" class="Estilo6"><strong class="Estilo13">Aspirante, c&oacute;mo ingresar: </strong>El usuario es el n&uacute;mero del documento de identidad suministrado en el momento de efectuar el pago.</p>
		  <p align="justify" class="Estilo6">La clave es el n&uacute;mero de referencia asignado por el banco, en el momento de efectuar el pago y se encuentra impreso en el formato de la consignaci&oacute;n.</p>
		</div>
		  <p align="justify" class="Estilo6">Se&ntilde;or aspirante, es preciso leer cuidadosamente el instructivo antes de diligenciar el formulario. Tenga en cuenta que una vez grabada la informaci&oacute;n, esta no podr&aacute; ser modificada.</p>
		 
		  <p align="justify" class="Estilo6">Diligencie cuidadosamente la informaci&oacute;n solicitada y cerciorese de la exactitud de los datos consignados.</p>
		  <p align="justify" class="Estilo6">Los datos consignados en el formulario ser&aacute;n guardados bajo la gravedad del juramento, y en el momento de grabar y enviar la informaci&oacute;n equivale a la firma de la inscripci&oacute;n.</p>
		  <? //require_once('resultados/aviso_resultados.php'); ?>
		</td></tr>
	  </table>

	</td>
  <td width="43%" height="293" align="right" valign="top">
<P align="center" class="Estilo3"><strong><a href="javascript:void(0)" onClick="javascript:popUpWindow('instructivo/index.php', 'yes', 0,0, screen.availWidth, screen.availHeight)" title="Instrictivo">INSTRUCTIVO DE ADMISIONES</a></strong></P>
<P align="center" class="Estilo6"><strong><a href="javascript:void(0)" onClick="javascript:popUpWindow('instructivo/reingreso.php', 'yes', 0,0, screen.availWidth, screen.availHeight)" title="Instrictivo de Reingreso">INSTRUCTIVO DE REINGRESO</a></strong></P>
<P align="center" class="Estilo6"><strong><a href="javascript:void(0)" onClick="javascript:popUpWindow('instructivo/reingreso.php', 'yes', 0,0, screen.availWidth, screen.availHeight)" title="Instrictivo de Transferencias">INSTRUCTIVO DE TRANSFERENCIAS</a></strong></P>
<form name="login" method="post" autocomplete="off" action="conexion/verifica.php">
<table width="161" border="1" align="center" cellpadding="0" cellspacing="0" bgcolor="#E4E6DD" style="border-collapse:collapse">
<tr><td>


<table width="153" border=0 align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
<caption><span class="Estilo6">Digite  Usuario y Clave</span></caption>
<tr><td colspan="2" align="center">
<?
require_once("../script/mensaje_error.inc.php");
if(isset($_GET['error_login'])){
   $error=$_GET['error_login'];
   echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
		<a OnMouseOver='history.go(-1)'><img src='../img/asterisco.gif'>$error_login_ms[$error]</font></a>";
}
ob_end_flush();
?>&nbsp;
</td>
</tr>
<tr><td align="right" class="Estilo6"><a href="#" onClick="javascript:popUpWindow('general/usuario.html', 'yes', 550, 390, 350, 160)">Usuario</a>:&nbsp;</td>
  <td><input name="user" type="text" class="input" size="15" onKeypress="if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
</tr>
<tr>
  <td align="right"><span class="Estilo6"><a href="#" onClick="javascript:popUpWindow('general/clave.html', 'yes', 550, 390, 350, 160)">Clave</a>:</span>&nbsp;</td>
  <td><input name="pass" type="password" class="input" id="password" onChange="javascript:this.value=this.value.toLowerCase();" size="15"></td>
</tr>
<tr> 
<td align="center">&nbsp; 
</td>
<td align="left"><input name="submit" type="submit" value="Entrar" class="Estilo6" onClick="enviaMD5(calculaMD5());" style="height:22; width:90; cursor:pointer" ></td>
</tr>
<input type="Hidden" name="cifrado" value="">
<input type="Hidden" name="numero" value="">
</table>

</td></tr></table>
<br>
<center><? print $mensaje; ?></center>
</form>
<br>
<center>
<a href="http://www.udistrital.edu.co/portal/dependencias/administrativas/tipica.php?id=10" target="_parent"><? print $oas; ?></a><br>
<P align="center" class="Estilo3">&nbsp;</P>
</center>
</td>
</table>

<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
</tr>
<tr>
<td align="center">
</td>
</tr>
</table>

<?php
print'<center><hr width="80%" SIZE="1">
 <font face="arial" size="1">Universidad Distrital Francisco Jos&eacute; de Caldas, Oficina Asesora de Sistemas. - 2006 - Todos los derechos reservados. <br>
 Vicerrector&iacute;a Acad&eacute;mica - Comit&eacute; de Admisiones Tel&eacute;fonos 3238400 Ext. 1102 - <a href="resultados/adm_frm_contacto.php" target="_blank">admisiones@udistrital.edu.co</a><br>
 <b>NOTA: En este sitio, recopilamos informaci&oacute;n que solo es de inter&eacute;s de los aspirantes a ingresar a la Universidad Distrital.</b></font>
</center>';
?>
</body>
</html>