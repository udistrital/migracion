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
    <td width="786" valign="middle"><span class="Estilo1"><br>&nbsp;&nbsp;&nbsp;
	    <a href="coor_index_msg.php" target="principal">ADMINISTRACIÓN DE NOTICIAS</a></span></td>
    <td width="39" valign="middle" align="center"><img src="../img/oas.gif" width="39" height="35"></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><hr style="height:1" color="#000000"></td>
  </tr>
  <tr>
    <td colspan="2">
	<p align="justify">Seleccione de la lista, el nombre del Proyecto Curricular y haga clic en &quot;<b>Consultar</b>&quot;.</p>
<p align="center"><span class="Estilo1">GESTIÓN DE NOTICIAS</span></p>
<p align="justify">
<font color="#0000FF">Administración</font><img alt="Administración de Noticias" src="http://localhost/CONDOR/img/b_home.png" border="0" width="16" height="16">: 
Formulario para administrar las noticias publicadas.<p align="justify">
<img alt="Editar Noticia" src="http://localhost/CONDOR/img/b_edit.png" border="0" width="16" height="16"> 
Icono para editar las noticias.<br>
<img alt="Borrar Noticia" src="http://localhost/CONDOR/img/b_deltbl.png" border="0" width="16" height="16"> 
Icono para borrar las noticias.<br>
<img alt="Publicar Nueva Noticia" src="http://localhost/CONDOR/img/b_insrow.png" border="0" width="16" height="16"> 
Icono para insertar nuevas noticias.<br>
<img alt="Consultar Noticias Publicadas" src="http://localhost/CONDOR/img/b_browse.png" border="0" width="16" height="16"> 
Icono para consultar las noticias.<p align="center">
<span class="Estilo1">PUBLICACIÓN DE NOTICIAS</span><p align="justify">
<font color="#0000FF">Publicación</font><img alt="Publicacón de Noticias" src="http://localhost/CONDOR/img/b_insrow.png" border="0" width="16" height="16">: 
Formulario para publicar nuevas noticia.</p>
<ul>
 <li>Autor: Nombre de quien publica la noticia.</li>
<li>Proyecto Curricular: 
Seleccione de la lista, el nombre del Proyecto Curricular de los estudiantes a 
quienes les publicará la noticia y en &quot;Noticia Para:&quot;, a quien le publicará la 
noticia.</li>
<li>Fecha Inicial: Fecha en la 
que se iniciará la publicación de la noticia.</li>
<li>Fecha Final: Ultimo día de 
publicación de la noticia.</li>
<li>Titulo: Título de la 
noticia.</li>
<li>Contenido: Cuerpo de la noticia.</li>
</ul>
</td>
  </tr>
</table>
<br>
<table width="90%" align="center" class="tb">
  <tr>
    <td width="75%" align="left" valign="middle">
      <? require_once('ay_coor_lis.php'); ?>
    </td>
    <td width="25%" align="right" valign="middle">
	<form name="form2">
	<input type="button" name="Submit" value="Cerrar" onClick="javascript:window.close();" class="button">
	</form></td>
  </tr>
</table>
</body>
</html>