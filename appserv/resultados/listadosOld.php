<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_general.'msql_ano_per_resultado.php');
require_once('fu_pie_pagAdm.php');
$log = "<embed width='57' height='58' src='../../img/cdr.swf'>";

?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../script/BorraLink.js"></script>
<script language="JavaScript" type="text/JavaScript">
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
<script LANGUAGE="JavaScript">
function redir(pag) {
	location.href=pag
}
</script>

<title>Comité de Admisiones</title>
</head>

<body>
<br>
<form name="form1">
<table width="780" align="center" cellpadding="3" cellspacing="0" style="border-color:#999999; border-style:double">
  <tr bgcolor="#E4E5DB">
    <td width="98"><a href="http://www.udistrital.edu.co" title="Universidad Distrital Francisco José de Caldas" target="_self">
	<img src="../../img/EscudoUD.gif" width="90" height="110" border="0"></a></td>
    <td width="677" colspan="2" align="center">
	<br><img src="../../img/12cw03003.png" border="0"><br>
      <span class="Estilo14">VICERRECTOR&Iacute;A ACAD&Eacute;MICA- COMIT&Eacute; DE ADMISIONES</span><br>
      <br>
      <span class="Estilo12">CONSULTA DE ASPIRANTES PARA EL <br>
      <? print $peri; ?> PER&Iacute;ODO ACAD&Eacute;MICO DE <? print $ano; ?>
    </span> </td>
    <td width="97" align="center" title="Sistema de Información Cóndor"><? echo $log ?>&nbsp;<span class="Estilo9"><br>CÓNDOR</span>
	</td>
  </tr>
  <tr>
    <td colspan="4">
      <table width="780" border="0" align="center">
        <tr>
          <td colspan="4"><p align="justify">Para poder ver los documentos que contienen la informaci&oacute;n, necesita un visor de archivos pdf. Si no tiene instalado uno, por favor desc&aacute;rguelo de aqu&iacute;:&nbsp;&nbsp;<a href="http://www.adobe.com/es/products/acrobat/readstep2.html" target="_blank"><img src="../../img/reader_icon.gif" width="21" height="20" border="0"></a></p></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><h3>RESULTADOS</h3></td>
        </tr>
		<tr>
          <td colspan="4" align="center">&nbsp;</td>
        </tr>
		<tr>
          <td colspan="4" align="center"><a href="../pdf/especial.PDF" target="_self" title="Ver resultados.">Resultados Inscripciones Especiales</a></td>
          </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="4" align="center">
		  <select name="menu1" style="font-size: 12px; font-family: Tahoma">
		    <option value="">Seleccione el Proyecto Curricular al cual se present&oacute; y haga clic en el botón "Consultar".</option>
            <option value="../pdf/185.PDF">ADMINISTRACION AMBIENTAL</option>
            <option value="../pdf/4.PDF">ADMINISTRACION DEPORTIVA (NOCTURNA)</option>
            <option value="../pdf/1.PDF">ADMINISTRACION DEPORTIVA</option>
			<!--<option value="../pdf/98.PDF">ARTES MUSICALES</option>
			<option value="../pdf/16.PDF">ARTES PLASTICAS Y VISUALES</option> -->
            <option value="../pdf/81.PDF">GESTION AMBIENTAL Y SERVICIOS PUBLICOS</option>
            <option value="../pdf/180.PDF">INGENIERIA AMBIENTAL</option>
            <option value="../pdf/25.PDF">INGENIERIA CATASTRAL Y GEODESIA</option>
            <option value="../pdf/20.PDF">INGENIERIA DE SISTEMAS</option>
            <option value="../pdf/7.PDF">INGENIERIA ELECTRICA</option>
            <option value="../pdf/5.PDF">INGENIERIA ELECTRONICA</option>
            <option value="../pdf/10.PDF">INGENIERIA FORESTAL</option>
            <option value="../pdf/15.PDF">INGENIERIA INDUSTRIAL</option>
            <option value="../pdf/32.PDF">INGENIERIA TOPOGRAFICA</option>
            <option value="../pdf/140.PDF">LICENCIATURA EN BIOLOGIA</option>
            <option value="../pdf/155.PDF">LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN CIENCIAS SOCIALES</option>
            <option value="../pdf/165.PDF">LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN ING, LENGUA EXTRANJERA</option>
            <option value="../pdf/160.PDF">LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN LENGUA CASTELLANA</option>
            <option value="../pdf/145.PDF">LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN MATEMATICAS</option>
            <option value="../pdf/135.PDF">LICENCIATURA EN FISICA</option>
            <option value="../pdf/187.PDF">LICENCIATURA EN PEDAGOGIA INFANTIL</option>
			<option value="../pdf/188.PDF">LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN EDUCACION ARTISTICA</option>
            <option value="../pdf/150.PDF">LICENCIATURA EN QUIMICA</option>
            <option value="../pdf/167.PDF">MATEMATICAS</option>
            <option value="../pdf/85.PDF">SANEAMIENTO AMBIENTAL</option>
            <option value="../pdf/79.PDF">TECNOLOGIA CONSTRUC. CIVILES</option>
            <!-- <option value="../pdf/481.PDF">TECNOLOGIA EN GESTION AMBIENTAL (CONVENIO 174 SED)</option>
			<option value="../pdf/485.PDF">TECNOLOGIA EN SANEAMIENTO AMBIENTAL (CONVENIO 174 SED)</option> -->
            <option value="../pdf/72.PDF">TECNOLOGIA EN ELECTRICIDAD</option>
            <option value="../pdf/73.PDF">TECNOLOGIA EN ELECTRONICA</option>		
            <option value="../pdf/77.PDF">TECNOLOGIA EN INDUSTRIAL</option>
            <option value="../pdf/74.PDF">TECNOLOGIA EN MECANICA</option>
            <option value="../pdf/30.PDF">TECNOLOGIA EN TOPOGRAFIA</option>
            <option value="../pdf/31.PDF">TECNOLOGIA EN TOPOGRAFIA(DIURNO)</option>
            <option value="../pdf/78.PDF">TECNOLOGIA SISTEMATIZACION DE DATOS</option>
             </select>
            <input type="button" name="Button1" value="Consultar" onClick="MM_jumpMenuGo('menu1','parent',1)" style="cursor:pointer" title="Ejecutar la consulta.">
		  </td>
          </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4"><div align="center"><strong>FACULTAD DE ARTES - ASAB</strong></div></td>
        </tr>
        <tr>
          <!--<td align="center"><a href="../pdf/16.PDF" target="_blank" title="Ver resultados.">Artes Pl&aacute;sticas</a></td>
          <td align="center"><!-- <a href="../pdf/97.html" target="_blank" title="Ver resultados.">Artes Esc&eacute;nicas</a></td>
          <td align="center"><a href="../pdf/98.PDF" target="_blank" title="Ver resultados.">Artes Musicales</a></td> -->
        </tr>
        <tr>
          <td colspan="4" align="center" class="Estilo10">Publicación de resultados 29-Junio-2008</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="4"><div align="center"><strong>FACULTAD TECNOL&Oacute;GICA - PROGRAMAS DE INGENIER&Iacute;A </strong></div></td>
          </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="center">
		  <select name="menu2" style="font-size: 12px; font-family: Tahoma">
		    <option value="">Seleccione el Proyecto Curricular al cual se present&oacute; y haga clic en el botón "Consultar".</option>
            <option value="../pdf/279.PDF">INGENIERÍA CIVIL</option>
            <option value="../pdf/277.PDF">INGENIERÍA DE PRODUCCCIÓN</option>
            <option value="../pdf/283.PDF">INGENIERÍA EN CONTROL</option>
            <option value="../pdf/272.PDF">INGENIERÍA EN DISTRIBUCIÓN Y REDES ELÉCTRICAS</option>
            <option value="../pdf/273.PDF">INGENIERÍA EN TELECOMUNICACIONES</option>
            <option value="../pdf/378.PDF">INGENIERÍA EN TELEMÁTICA</option>
            <option value="../pdf/275.PDF">INGENIERÍA MECÁNICA</option>
            <option value="../pdf/372.PDF">INGENIERIA ELECTRICA (CICLOS PROPEDEUTICOS)</option>			
          </select>
            <input type="button" name="Button2" value="Consultar" onClick="MM_jumpMenuGo('menu2','parent',1)" style="cursor:pointer" title="Ejecutar la consulta.">
			</td>
          </tr>
        <tr>
          <td colspan="4" align="left"><!-- <a href="../pdf/res03.PDF" target="_blank">RESOLUCIÓN No. 03 - (Julio 06 de 2007)</a> --></td>
        </tr>
        <tr align="center">
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4"><div align="center"><strong>Nota:</strong> La asignaci&oacute;n de cupos, fue reglamentada por el Consejo Acad&eacute;mico proporcionalmente a la demanda de inscritos.</div></td>
        </tr>
      </table>
	  </td>
  </tr>
  <tr>
    <td height="10" colspan="4" align="center" class="Estilo10">Mayores informes carrera 8 No 40 - 75 Admisiones, Edificio Sabio Caldas&nbsp; primer piso.</td>
  </tr>
  <tr align="center">
    <td colspan="4"></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="10%">&nbsp;</td>
    <td align="right" width="30%"><input name="cr" type="button" value="Credencial" style="cursor:pointer" title="Consultar resultados de aspirantes por número de credencial." onClick="redir('index.php')"></td>
    <td align="center" width="50%"><input name="salir" type="button" value="Salir" style="cursor:pointer" title="Salir de esta página." onClick="redir('http://www.udistrital.edu.co/')"></td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><? fu_pie(); ?></td>
  </tr>
</table>
</form>
</body>
</html>