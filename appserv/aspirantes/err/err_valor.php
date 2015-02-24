<HTML>
<HEAD>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../clicder.js"></script>
<script language="JavaScript" src="../../BorraLink.js"></script>
</HEAD>
<body style="margin-top:70">
<table width="60%" height="40%" border="0" cellpadding="5" cellspacing="0" align="center">
<tr><td><fieldset style="padding:20;">
		
		<table width="70%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr><td valign="top">
		<p align="center">&nbsp;</p>
		<h3>Error</h3>
		<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
		
		<p align="justify" style="background-color:#FFFFCC">Valor consignado: $<? print number_format($_GET['c']);?><br>
		Costo formulario:&nbsp;&nbsp;$<? print number_format($_GET['f']);?></p>
		<p align="justify" class="Estilo10" style="background-color:#FFFFCC">El valor consignado es menor al costo del formulario.</p>
		<p></p>
		<p align="justify">El costo del formulario de inscripci&oacute;n, corresponde al 10% de un salario m&iacute;mino  mensual legal vigente.<br><br>
		   Acuerdo 004 de Enero 25 de 2006, del Consejo Superior. ARTICULO 13.</p>
		
		
	    <p align="justify">Para poder inscribirse a la Universidad Distrital, debe hacer una &uacute;nica consignaci&oacute;n que corresponda al costo total del formulario de inscripci&oacute;n.</p>
		</fieldset>
		
		<p align="center"><input type="button" name="Submit" value="Regresar" onClick="javascript:history.go(-1)" style="width:150; cursor:pointer"></p>
		  </td></tr>
	  </table>
	  <p>&nbsp;</p>

 </fieldset></td></tr></table>
</body>
</html>