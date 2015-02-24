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
	PLAN DE ESTUDIO </span></td>
    <td width="39" valign="middle" align="center"><img src="../img/oas.gif" width="39" height="35"></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><hr style="height:1" color="#000000"></td>
  </tr>
  <tr>
    <td colspan="2">
	<p align="justify">Pensum completo
  de la carrera ordenado por semestre, estas son las asignaturas que usted
  deberá cursar durante toda la carrera.
  <p align="justify"><b>Código</b>:
  Código de la asignatura.
  <p align="justify"><b>Asignatura</b>: Nombre de la asignatura.</p>
  <p align="justify"><b>Sem</b>: Semestre de la asignatura.</p>
  <p align="justify"><b>Nota</b>: Calificación que ha obtenido de
  cada una de las asignaturas del pensum.</p>
  <p align="justify"><b>Observación</b>: Observación de cada una
  de las notas obtenidas.</p>
  <p align="justify">Haga clic en el código de la asignatura, para
  ver el requisito de la misma.</p>
<p align="justify">Para imprimir el plan de estudio, haga clic en el botón &quot;<i><b>Imprimir 
Plan de Estudio</b></i>&quot; y luego haga clic en &quot;<i><b>Impreso:</b></i></p>
    </td>
  </tr>
</table>
<br>
<table width="90%" align="center" class="tb">
  <tr>
    <td width="75%" align="left" valign="middle">
     <? require_once('ay_doc_lis.php');?>
    </td>
    <td width="25%" align="right" valign="middle">
	<form name="form2">
	<input type="button" name="Submit" value="Cerrar" onClick="javascript:window.close();" class="button">
	</form></td>
  </tr>
</table>
</body>
</html>