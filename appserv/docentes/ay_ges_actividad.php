<?PHP
require_once('dir_relativo.cfg');
$b_insrow ='<IMG SRC='.dir_img.'b_insrow.png alt="Insertar Actividad" border="0">';
$b_edit ='<IMG SRC='.dir_img.'b_edit.png alt="Modificar Actividad" border="0">';
$b_deltbl='<IMG SRC='.dir_img.'b_deltbl.png alt="Borrar Actividad" border="0">';
?>
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
	<a href="doc_adm_pt.php" target="principal">PLAN DE TRABAJO</a></span></td>
    <td width="39" valign="middle" align="center"><img src="../img/oas.gif" width="39" height="35"></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><hr style="height:1" color="#000000"></td>
  </tr>
  <tr>
    <td colspan="2">
	<div align="center"><B>DECRETOS, ACUERDOS Y RESOLUCIONES DEL R&Eacute;GIMEN DOCENTE</strong></B></div>
<p align="justify">
<strong>ART�CULO 49</strong>.- Plan de trabajo. Los profesores de carrera deben concertar con su
coordinador de proyecto curricular, con una anticipaci�n m�nima de un (1) mes a la
iniciaci�n del per�odo acad�mico, un plan de trabajo describiendo detalladamente las
actividades que se compromete a realizar durante el mismo tiempo.</p>

<strong>Ingresar una actividad:</strong><br>
    <ol>
	<li>Para ingresar una actividad, selecci�nela de la lista desplegable "<span class="error">ACTIVIDADES</span>".</li>
	<li>Haga clic en el �dem "D�a" o en el bot�n frente al mismo, esto le permitir� ver la lista de valores y all� podr� seleccionar la informaci�n deseada.</li>
	<li>Repita el paso 2 para los �tem "Hora, Sede y Sal�n".</li>
	<li>Complete el formulario y haga clic en el bot�n "Grabar".</li>
	</ol>
    Para ingresar una nueva actividad repita los pasos 1,2, 3 y 4.<br>
    <br>
    <strong>Borrar una actividad:</strong> haga clic en el icono <A onmouseover="link();return true;" onclick="link();return true;" 
href="prog_doc_borra_pt.php?&amp;Ac=&amp;Hr="><IMG alt="Borrar Actividad" src="../img/b_deltbl.png" border=0></A> frente a cada actividad.</p>
<p>&nbsp;</p></td>
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