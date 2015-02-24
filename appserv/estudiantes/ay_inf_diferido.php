<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_conect.'cierra_bd.php');

$consulta = OCIParse($oci_conecta, "select smi_valor from acsalmin where smi_estado='A'");
OCIExecute($consulta);
$row = OCIFetch($consulta);

$ConFecha = OCIParse($oci_conecta, "select to_char(ace_fec_ini,'dd-Mon-YYYY'),to_char(ace_fec_fin,'dd-Mon-YYYY')
  									  from accaleventos
 									 where ace_cod_evento=10
   									   and ace_anio=$ano
   									   and ace_periodo=$per");
OCIExecute($ConFecha);
$Row_ConFecha = OCIFetch($ConFecha );							   
									   
$fechaini=OCIresult($ConFecha, 1);
$fechafin=OCIresult($ConFecha, 2);

$salmin = number_format(OCIresult($consulta, 1));
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
	INFORMACI&Oacute;N DE DIFERIDO </span></td>
    <td width="39" valign="middle" align="center"><img src="../img/oas.gif" width="39" height="35"></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><hr style="height:1" color="#000000"></td>
  </tr>
  <tr>
    <td colspan="2">
	
  <b>Fecha de selección: Del <? print $fechaini.' al '.$fechafin; ?></b><br>
  <b>SMMLV(Salario Mínimo Mensual Legal Vigente)&nbsp;&nbsp;&nbsp;<? print '$'.$salmin; ?></b>
  <p>&nbsp;</p>
      <p align="center"><strong>UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS</strong></p>
      <p align="center"><strong>RESOLUCI&Oacute;N N&ordm;( 320 )<br>(08 de Septiembre de 2006)</strong></p>
      <p><strong><em>&ldquo;Por la cual se Modificanlos Art&iacute;culos 5</em>&ordm; y 6&ordm;<em> de </em> <em>la Resoluci&oacute;n</em> <em> 025 del 26 de enero de 2006</em></strong></p>
      <p>El Rector de laUniversidad Distrital Francisco Jos&eacute; de Caldas, en uso de sus atribuciones legales, estatutarias, y </p>
      <p align="center"><strong>C O N S I D E R A N D O</strong></p>
      <p align="justify">Que medianteAcuerdo 004 de enero 25 de 2006, el Consejo Superior Universitario &ldquo;establecey unifica el r&eacute;gimen de liquidaci&oacute;n de matr&iacute;culas para los estudiantes de la Universidad Distrital Francisco Jos&eacute; de Caldas&rdquo;&nbsp;</p>
      <p align="justify">Que mediante Resoluci&oacute;n de Rector&iacute;a 025 de enero 26 de 2006, se reglament&oacute; en el Art&iacute;culo 5&deg; del Acuerdo 004 de 2005, lo relacionado con el fraccionamiento de la matr&iacute;cula para los estudiantes de pregrado de la Universidad Distrital Francisco Jos&eacute; de Caldas.</p>
      <p align="justify">Que el Art&iacute;culo 5&deg; de la mencionada Resoluci&oacute;n, adopt&oacute; un sistema de fraccionamiento en el pago de la matr&iacute;cula semestral de los estudiantes de la Universidad Distrital que cursan programas de pregrado siempre y cuando &eacute;sta equivalga como m&iacute;nimo al cincuenta por ciento (50%) de un Salario M&iacute;nimo Legal Mensual Vigente (SMMLV), estableci&eacute;ndose tres modalidadesde fraccionamientoy el procedimientopara tal fin.</p>
      <p align="justify">Que en elArt&iacute;culo 6&deg;de la precitada Resoluci&oacute;nse adopt&oacute; el Calendario de fechas l&iacute;mitesdepago de matr&iacute;cula cuando haya lugar al fraccionamiento</p>
      <p align="justify">Que el Comit&eacute; de Admisiones de la Universidad,atendiendoa lo contempladoen el par&aacute;grafo 1&deg; del Articulo 11 del Acuerdo 004 de 2006, en su sesi&oacute;n del d&iacute;a 25 de julio de 2006, recomiendamodificar los art&iacute;culos5&deg; y 6&deg;de la citada Resoluci&oacute;n.</p>
      <p>En m&eacute;rito de lo expuesto este despacho;</p>
      <p align="center"><strong>R E S U E L V E: </strong></p>
      <p><strong><em>ARTICULO 1o</em>.- Modificar el <em>Art&iacute;culo5</em>&ordm;de  la <em>Resoluci&oacute;n</em> <em> 025del 26de enero de 2006</em></strong>, el cual quedara as&iacute;:</p>
      <p align="justify">&ldquo;Articulo <strong><em>5</em></strong><strong>&ordm; </strong>Ad&oacute;ptese un sistema de fraccionamiento en el pago de la matr&iacute;cula semestral de los estudiantes de la Universidad Distrital que cursan programas de pregrado, siempre y cuando &eacute;sta equivalga como m&iacute;nimo al cincuenta por ciento (50%) de un Salario M&iacute;nimo Legal Mensual Vigente (SMMLV). La modalidad &uacute;nica de fraccionamientoes de Dos (2) cuotas.</p>
      <p align="justify"><strong>PAR&Aacute;GRAFO.- </strong>La solicitud de fraccionamiento debe hacerse en la semana de pre-inscripci&oacute;n de asignaturas seg&uacute;n lo establecido en el Calendario Acad&eacute;mico de la Universidad, proceso que se surtir&aacute; a trav&eacute;s de la Oficina Asesora de Sistemas por intermedio del ingreso del estudiante al &ldquo;Aplicativo C&oacute;ndor&rdquo; o al que haga sus veces. Esta instancia ser&aacute; la encargada de adelantar los tr&aacute;mites respectivos&rdquo;. </p>
      <p align="justify"><strong><em>ARTICULO 2&ordm;. Modif&iacute;quese el</em> <em>Art&iacute;culo </em> 6&ordm;<em> de </em> <em>laResoluci&oacute;n</em> <em> 025del 26de enero de 2006</em></strong>, el cual quedara as&iacute;:</p>
      <p align="justify"><strong><em>&ldquo;ARTICULO 6&ordm;</em></strong>Ad&oacute;ptese el siguienteCalendariode fechas l&iacute;mites de pago de matr&iacute;cula cuando haya lugar al fraccionamiento:</p>
      <div align="justify">
        <ul>
          <li>Cincuenta por ciento (50%) en el periodo de pago de matr&iacute;culas ordinarias como requisito para realizar pre-oficializaci&oacute;n de matr&iacute;cula y cincuenta por ciento(50%) hasta la octava (8&ordf;) semana lectiva de clases.</li>
        </ul>
      </div>      
      <p align="justify"><strong>PAR&Aacute;GRAFO PRIMERO.- </strong>En todos los casos, para llevar a cabo los procesos de inscripci&oacute;n de asignaturas y pre-oficializaci&oacute;n de la matr&iacute;cula, los estudiantes deber&aacute;n cancelar el valor correspondiente a la primera cuota del fraccionamiento aprobado.</p>
      <p align="justify"><strong>PAR&Aacute;GRAFO SEGUNDO.-</strong> En todos los casos, para acceder de nuevo a este derecho y poder llevar a cabo el proceso de matr&iacute;cula en el siguiente semestre, el (la) estudiante deber&aacute; haber efectuado la totalidad de los pagos correspondientes al valor fraccionado de la matr&iacute;cula del semestre acad&eacute;mico inmediatamente anterior. En caso que el (la)estudiante no haya pagado el valor total de la matr&iacute;cula en las fechas m&aacute;ximas establecidas, se le considerar&aacute; como &ldquo;No renovaci&oacute;n de matr&iacute;cula&rdquo; en t&eacute;rminos del Estatuto Estudiantil vigente y por tanto se har&aacute; responsable de las consecuencias que esta situaci&oacute;n les acarrea&rdquo;.</p>
      <p align="justify"><strong><em>ART&Iacute;CULO 7&ordm;.</em></strong> La presente Resoluci&oacute;n rige a partir de la fecha de su expedici&oacute;n.</p>
      <p align="center"></p>
      <p align="center"><strong>PUBL&Iacute;QUESE, COMUN&Iacute;QUESEYC&Uacute;MPLASE</strong></p>
      <p>Dada en Bogot&aacute; D.C., a los 08 d&iacute;as del mes de Septiembre de 2006</p>
      <p align="center"><strong>RICARDO GARCIA DUARTE<br></strong>Rector </p>
      <p align="left">Elabor&oacute;: @Camargo<br>
        M Murillo <br>
        VoBo: Comit&eacute; de Admisiones <br>
        Gustavo Tabares<br>
      Aprob&oacute; : </p>

    </td>
  </tr>
</table>
<br>
<table width="90%" align="center" class="tb">
  <tr>
    <td width="75%" align="left" valign="middle">
     <? 
	 require_once('ay_est_lis.php'); 
	 OCIFreeCursor($consulta);
	 OCIFreeCursor($ConFecha);
	 OCILogOff($oci_conecta);
	 ?>
    </td>
    <td width="25%" align="right" valign="middle">
	<form name="form2">
	<input type="button" name="Submit" value="Cerrar" onClick="javascript:window.close();" class="button">
	</form></td>
  </tr>
</table>
</body>
</html>