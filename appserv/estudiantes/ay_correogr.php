<html>
<head>
<title>Ayuda</title>
<link href="../script/estilo_ay.css" rel="stylesheet" type="text/css">
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_jumpMenuGo(selName,targ,restore){ //v3.0
  var selObj = MM_findObj(selName); if (selObj) MM_jumpMenu(targ,selObj,restore);
}
//-->
</script>
</head>
<body>
<table width="90%" align="center" background="../img/fondo_ay.png">
  <tr>
    <td width="49" rowspan="3" class="td"><br>
    <img src="../img/ay.gif" width="30" height="30"></td>
    <td width="786" valign="middle"><span class="Estilo1"><br>
    &nbsp;&nbsp;&nbsp;
	<a href="est_adm_correos_doc.php" target="principal">ENVIO DE CORREOS A DOCENTES</a></span></td>
    <td width="39" valign="middle" align="center"><img src="../img/oas.gif" width="39" height="35"></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><hr style="height:1" color="#000000"></td>
  </tr>
  <tr>
    <td colspan="2">
	<ol>
    <li> Haga clic en la casilla de verificaci&oacute;n frente al nombre de la persona a la cual le desea enviar un correo.</li>
    <li>Haga clic en el bot&oacute;n &quot;Enviar Correos&quot;.</li>
    <li>Para desmarcar las casillas seleccionadas, haga clic en el bot&oacute;n &quot;Desmarcar.&quot; </li>
  </ol>  
  <p><strong>Para enviarle un correo a todo el grupo</strong></p>
  <ol>
    <li>Haga clic en el bot&oacute;n &quot;Marcar&quot;, esto seleccionará a todas las personas del grupo.</li>
    <li> Haga clic en el bot&oacute;n &quot;Enviar Correos&quot;.</li>
    <li>Para desmarcar las casillas seleccionadas, haga clic en el bot&oacute;n &quot;Desmarcar.&quot; </li>
  </ol>  
  <p>El bot&oacute;n &quot;Invertir&quot;, desmarca la selecci&oacute;n actual en el caso de haber alguna casilla seleccionada, y marca las casillas no seleccionadas. Si no hay casillas seleccionadas las marcar&aacute;  todas.</p>
    </td>
  </tr>
</table>
<br>
<table width="90%" align="center" class="tb">
  <tr>
    <td width="75%" align="left" valign="middle">
     <? require_once('ay_est_lis.php'); ?>
    </td>
    <td width="25%" align="right" valign="middle">
	<form name="form2">
	<input type="button" name="Submit" value="Cerrar" onClick="javascript:window.close();" class="button">
	</form></td>
  </tr>
</table>
</body>
</html>