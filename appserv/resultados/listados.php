<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_general.'msql_ano_per_resultado.php');
require_once('fu_pie_pagAdm.php');
$log = "<embed width='57' height='58' src='../img/cdrlogo.png'>";

?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../script/BorraLink.js"></script>
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

<title>Comit&eacute; de Admisiones</title>
</head>

<body>
<br>
<form name="form1">
<table width="780" align="center" cellpadding="3" cellspacing="0" style="border-color:#999999; border-style:double">
  <tr bgcolor="#E4E5DB">
    <td width="98"><a href="http://www.udistrital.edu.co" title="Universidad Distrital Francisco Jos&eacute; de Caldas" target="_self">
	<img src="../img/EscudoUD.gif" width="90" height="110" border="0"></a></td>
    <td width="677" colspan="2" align="center">
	<br><img src="../img/12cw03003.png" border="0"><br>
      <span class="Estilo14">VICERRECTOR&Iacute;A ACAD&Eacute;MICA- COMIT&Eacute; DE ADMISIONES</span><br>
      <br>
      <span class="Estilo12">CONSULTA DE ASPIRANTES PARA EL <br>
      <? print $peri; ?> PER&Iacute;ODO ACAD&Eacute;MICO DE <? print $ano; ?>
    </span> </td>
    <td width="97" align="center" title="Sistema de Informaci&oacute;n C&oacute;ndor"><? echo $log ?>&nbsp;<span class="Estilo9"><br>C&Oacute;NDOR</span>
	</td>
  </tr>
  <tr>
    <td colspan="4">
      <table width="780" border="0" align="center">
        <tr>
          <td colspan="4"><p align="justify">Para poder ver los documentos que contienen la informaci&oacute;n, necesita un visor de archivos pdf. Si no tiene instalado uno, por favor desc&aacute;rguelo de aqu&iacute;:&nbsp;&nbsp;<a href="http://www.adobe.com/es/products/acrobat/readstep2.html" target="_blank"><img src="../img/reader_icon.gif" width="21" height="20" border="0"></a></p></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><h3>RESULTADOS</h3></td>
        </tr>
		<tr>
          <td colspan="4" align="center">&nbsp;</td>
        </tr>
		<tr>
          <td colspan="4" align="center"><font size="3"><a href="../pdf/especial.PDF" target="_self" title="Ver resultados.">Resultados Inscripciones Especiales</a></font></td>
          </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="4" align="center">
		<select name="menu1" style="font-size: 12px; font-family: Tahoma">
		<?/*?>	<option value="">Seleccione el Proyecto Curricular al cual se present&oacute; y haga clic en el bot&oacute;n "Consultar".</option>
			<option value="../pdf/1.PDF">ADMINISTRACION DEPORTIVA</option>
			<option value="../pdf/98.PDF">ARTES MUSICALES</option><?*/?>
			<option value="../pdf/104.PDF">ARTES ESCENICAS</option>
		<?/*?>  <option value="../pdf/102.PDF">ARTE DANZARIO</option>  
                        <option value="../pdf/16.PDF">ARTES PLASTICAS Y VISUALES</option>
			<option value="../pdf/181.PDF">INGENIERIA SANITARIA</option>
			<option value="../pdf/185.PDF">ADMINISTRACION AMBIENTAL</option>
			<option value="../pdf/180.PDF">INGENIERIA AMBIENTAL</option>
			<option value="../pdf/85.PDF">SANEAMIENTO AMBIENTAL</option>
			<option value="../pdf/81.PDF">GESTION AMBIENTAL Y SERVICIOS PUBLICOS</option>
			<option value="../pdf/32.PDF">INGENIERIA TOPOGRAFICA</option>
			<option value="../pdf/31.PDF">TECNOLOGIA EN TOPOGRAFIA</option>
			<option value="../pdf/10.PDF">INGENIERIA FORESTAL</option>
		<?/*?>	<option value="../pdf/379.PDF">TECNOLOGIA CONSTRUC. CIVILES</option>
			<option value="../pdf/578.PDF">TECNOLOGIA SISTEMATIZACION DE DATOS</option>
			<option value="../pdf/77.PDF">TECNOLOGIA EN INDUSTRIAL</option>
			<option value="../pdf/374.PDF">TECNOLOGIA EN MECANICA</option>
			<option value="../pdf/573.PDF">TECNOLOGIA EN ELECTRONICA</option>
			<!--option value="../pdf/72.PDF">TECNOLOGIA EN ELECTRICIDAD</option-->
			<option value="../pdf/25.PDF">INGENIERIA CATASTRAL Y GEODESIA</option>
			<option value="../pdf/20.PDF">INGENIERIA DE SISTEMAS</option>
			<option value="../pdf/15.PDF">INGENIERIA INDUSTRIAL</option>
			<option value="../pdf/7.PDF">INGENIERIA ELECTRICA</option>
			<option value="../pdf/5.PDF">INGENIERIA ELECTRONICA</option>
			<option value="../pdf/135.PDF">LICENCIATURA EN FISICA</option>
			<option value="../pdf/188.PDF">LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN EDUCACION ARTISTICA</option>
			<option value="../pdf/187.PDF">LICENCIATURA EN PEDAGOGIA INFANTIL</option>
			<option value="../pdf/167.PDF">MATEMATICAS</option>
			<option value="../pdf/165.PDF">LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN ING, LENGUA EXTRANJERA</option>
			<option value="../pdf/160.PDF">LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN LENGUA CASTELLANA</option>
			<option value="../pdf/155.PDF">LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN CIENCIAS SOCIALES</option>
			<option value="../pdf/150.PDF">LICENCIATURA EN QUIMICA</option>
			<option value="../pdf/145.PDF">LICENCIATURA EN EDUCACION BASICA CON ENFASIS EN MATEMATICAS</option>
			<option value="../pdf/140.PDF">LICENCIATURA EN BIOLOGIA</option><?*/?>
		</select>
            <input type="button" name="Button1" value="Consultar" onClick="MM_jumpMenuGo('menu1','parent',1)" style="cursor:pointer" title="Ejecutar la consulta.">
		  </td>
          </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
<!--        <tr>
          <td colspan="4"><div align="center"><strong>FACULTAD DE ARTES - ASAB</strong></div></td>
        </tr>
        <tr>
          <!--<td align="center"><a href="../pdf/16.PDF" target="_blank" title="Ver resultados.">Artes Pl&aacute;sticas</a></td>
          <td align="center"><!-- <a href="../pdf/97.html" target="_blank" title="Ver resultados.">Artes Esc&eacute;nicas</a></td>
          <td align="center"><a href="../pdf/98.PDF" target="_blank" title="Ver resultados.">Artes Musicales</a></td> -->
<!--        </tr>
        <tr>
          <td colspan="4" align="center" class="Estilo10">Publicaci&oacute;n de resultados 23 de diciembre de 2012 </td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
          </tr>-->
	 <!--tr>
          <td colspan="4"><div align="center"><strong>Ingenier&iacute;a Sanitaria</strong></div></td>
        </tr>
        <tr>
          <td align="center"><a href="../pdf/16.pdf" target="_blank" title="Ver resultados.">Artes Pl&aacute;sticas</a></td>
          <td align="center"><a href="../pdf/102.pdf" target="_blank" title="Ver resultados.">Arte Danzario</a></td>
          <td align="center"><a href="../pdf/98.pdf" target="_blank" title="Ver resultados.">Artes Musicales</a></td>
        </tr>
        <tr>
          <td colspan="4" align="center" class="Estilo10">Publicaci&oacute;n de resultados 22 de julio de 2012 </td>
        </tr-->
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
	<?/*?>	    <option value="">Seleccione el Proyecto Curricular al cual se present&oacute; y haga clic en el bot&oacute;n "Consultar".</option>
                    <option value="../pdf/579.PDF">INGENIER&Iacute;A CIVIL (CICLOS PROPEDEUTICOS)</option>
                    <option value="../pdf/377.PDF">INGENIER&Iacute;A DE PRODUCCCI&Oacute;N (CICLOS PROPEDEUTICOS)</option>
                    <option value="../pdf/383.PDF">INGENIER&Iacute;A EN CONTROL (CICLOS PROPEDEUTICOS)</option>
                    <option value="../pdf/373.PDF">INGENIER&Iacute;A EN TELECOMUNICACIONES (CICLOS PROPEDEUTICOS)</option>
                    <option value="../pdf/678.PDF">INGENIER&Iacute;A EN TELEM&Aacute;TICA (CICLOS PROPEDEUTICOS)</option>
                    <option value="../pdf/375.PDF">INGENIER&Iacute;A MEC&Aacute;NICA (CICLOS PROPEDEUTICOS)</option>
              <?/*?><option value="../pdf/372.PDF">INGENIERIA ELECTRICA (CICLOS PROPEDEUTICOS)</option><?*/?>			
          </select>
              
            <input type="button" name="Button2" value="Consultar" onClick="MM_jumpMenuGo('menu2','parent',1)" style="cursor:pointer" title="Ejecutar la consulta.">
			</td>
          </tr>
        <tr>
          <td colspan="4" align="left"><!-- <a href="../pdf/res03.PDF" target="_blank">RESOLUCI�N No. 03 - (Julio 06 de 2007)</a> --></td>
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
    <td height="10" colspan="4" align="center" class="Estilo10">Mayores informes carrera 8 No 40 - 62 Admisiones, Edificio Sabio Caldas&nbsp; primer piso.</td>
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
    <td align="right" width="30%"><input name="cr" type="button" value="Credencial" style="cursor:pointer" title="Consultar resultados de aspirantes por n&uacute;mero de credencial." onClick="redir('index.php')"></td>
    <td align="center" width="50%"><input name="salir" type="button" value="Salir" style="cursor:pointer" title="Salir de esta p&aacute;gina." onClick="redir('http://www.udistrital.edu.co/')"></td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><? fu_pie(); ?></td>
  </tr>
</table>
</form>
</body>
</html>
