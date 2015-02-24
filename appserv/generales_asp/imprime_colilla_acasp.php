<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_general.'msql_ano_per.php');
require_once(dir_general.'valida_usuario_prog.php');
require_once(dir_general.'valida_http_referer.php');
require_once(dir_general.'valida_inscripcion_ver_colilla.php');

?>
<html>
<head>
<title>Aspirantes</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="Logout.js"></script>
</head>

<body>
<?php
require_once('msql_colilla_acasp.php'); 

?>
<p align="center" class="Estilo6">FORMULARIO DE INSCRIPCI&Oacute;N PARA INGRESO<br><? print $periodo; ?></p>

<table width="95%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
  <br>

<table width="75%"  border="1" align="center" cellpadding="1" cellspacing="0">
<caption>COMPROBANTE DE INSCRIPCI&Oacute;N</caption>
  <tr>
    <td width="50%" align="left" class="Estilo5">Per&iacute;odo Acad&eacute;mico: </td>
    <td width="50%"><? print $RowCAcasp[0][0];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Credencial:</td>
    <td width="50%"><? print $RowCAcasp[0][1];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Proyecto Curricular: </td>
    <td width="50%"><? print $RowCAcasp[0][3];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tipo de Inscripci&oacute;n: </td>
    <td width="50%"><? print $RowCAcasp[0][4];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Pais de Nacimiento:</td>
    <td width="50%"><? print $RowCAcasp[0][5];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Departamento:</td>
    <td width="50%"><? print $RowCAcasp[0][6];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Municipio:</td>
    <td width="50%"><? print $RowCAcasp[0][7];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Fecha de Nacimiento: </td>
    <td width="50%"><? print $RowCAcasp[0][8];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Sexo:</td>
    <td width="50%"><? print $RowCAcasp[0][9];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Estado Civil: </td>
    <td width="50%"><? print $RowCAcasp[0][10];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Direcci&oacute;n:</td>
    <td width="50%"><? print $RowCAcasp[0][11];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Localidad de Residencia: </td>
    <td width="50%"><? print $RowCAcasp[0][12];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Estrato de Residencia: </td>
    <td width="50%"><? print $RowCAcasp[0][13];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Estrato socioecon&oacute;mico de quien costear&aacute; los estudios: </td>
    <td width="50%"><? print $RowCAcasp[0][28];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tel&eacute;fono:</td>
    <td width="50%"><? print $RowCAcasp[0][14];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Correo Electr&oacute;nico: </td>
    <td width="50%"><? print $RowCAcasp[0][15];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Documento de Identidad: </td>
    <td width="50%"><? print $RowCAcasp[0][16];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Documento con que present&oacute; el ex&aacute;men de estado ICFES o SABER 11: </td>
    <td width="50%"><? print $RowCAcasp[0][17];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">SNP:</td>
    <td width="50%"><? print $RowCAcasp[0][18];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Localidad del Colegio: </td>
    <td width="50%"><? print $RowCAcasp[0][19];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Car&aacute;cter del Colegio: </td>
    <td width="50%"><? print $RowCAcasp[0][20];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Tipo de incapacidad: </td>
    <td width="50%"><? print $RowCAcasp[0][26];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Valid&oacute;: </td>
    <td width="50%"><? print $RowCAcasp[0][22];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Semestres transcurridos desde la terminaci&oacute;n del grado once:</td>
    <td width="50%"><? print $RowCAcasp[0][27];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">Fecha de Impresi&oacute;n:</td>
    <td width="50%"><? print $RowCAcasp[0][23];?></td>
  </tr>
  <tr>
    <td align="left" class="Estilo5">C&oacute;digo de Seguridad: </td>
    <td width="50%"><? print $RowCAcasp[0][24];?></td>
  </tr>
</table>
<p align="center">
Su inscripci&oacute;n ha sido recibida. Imprima y conserve este comprobante, deber&aacute; presentarlo en el momento de realizar alg&uacute;n tr&aacute;mite con la Universidad.</p>

	<?php
	$car=$RowCAcasp[0][2];
	if(($car==72)||($car==573)||($car==374)||($car==77)||($car==578)||($car==379))
	{
		echo '<p align="justify"><b>Se&ntilde;or aspirante, el paso que usted debe seguir es:</b></p>
		<p align="justify">Entregar obligatoriamente los documentos que a continuaci&oacute;n se describen, el siguiente d&iacute;a h&aacute;bil posterior a la realizaci&oacute;n de la inscripci&oacute;n en la cra 8 No. 40-62 Edificio Sabio Caldas, Oficina de Admisiones primer piso:<br>
		A)  Dos hojas del comprobante de inscripci&oacute;n impresa de internet.<br>
		B)  Certificado de localidad de residencia del aspirante expedido por la Junta de Acci&oacute;n Comunal o Alcald&iacute;a Local.<br>
		C)  Certificado de estrato socioecon&oacute;mico de residencia de quien costear&aacute; los estudios expedido por Planeaci&oacute;n Distrital (Supercades).<br>
		D)  Certificado expedido por el Colegio donde termin&oacute; o est&aacute; cursando estudios de bachillerato en el cual se indique la localidad donde est&aacute; ubicado y el valor mensual de la pensi&oacute;n pagada por el aspirante.  En el caso de haber validado el estudio de educaci&oacute;n media, la Universidad tendr&aacute; en cuenta el costo de la prueba de validaci&oacute;n (Art. 3 Acuerdo 004 de enero 25 de 2006 del CSU).<br>
		E)  Declaraci&oacute;n de renta o certificado de ingresos y retenciones de quien costear&aacute; los estudios, este certificado debe ser expedido en formato de la Direcci&oacute;n de Impuestos y Aduanas Nacionales (DIAN).<br>
		En caso de trabajadores independientes o familiares no declarantes, debe presentarse certificado de no contribuyente.<br>
		F) Certificado de la condici&oacute;n de Inscripci&oacute;n Especial (Avalado por el Ministerio del Interior para el caso de la poblaci&oacute;n indigena y minorias &eacute;tnicas y por el CADEL de la localidad, para los mejores bachilleres de colegios distritales de Bogot&aacute; del a&ntilde;o 2012) en el evento de realizar una Inscripci&oacute;n Especial.</p>';
		$cadena=$RowCAcasp[0][18];
		$snp=substr($cadena, 0, 7);
		if ($snp=='AC20102')
		{
			echo "Se&ntilde;or aspirante, tenga en cuenta que si su ex&aacute;men de estado ICFES o SABER 11, fue presentado en septiembre de 2010, este ser&aacute; transformado a la escala fija de las puntuaciones normalizadas en la escala hist&oacute;rica del ex&aacute;men SABER 11. Mayor informaci&oacute;n consultar las siguientes direcciones electr&oacute;nicas: <br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/659-informacion-clave-para-jefes-de-admision<br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/694-informaci%C3%B3n-importante-para-jefes-de-admisi%C3%B3n-2";
		}  
	}
	elseif(($car==383)||($car==373)||($car==678)||($car==579)||($car==372)||($car==377)||($car==375))
	{
		echo '<p align="justify"><b>Se&ntilde;or aspirante, el paso que usted debe seguir es:</b></p>
		<p align="justify">Entregar obligatoriamente los documentos que a continuaci&oacute;n se describen, el siguiente d&iacute;a h&aacute;bil posterior a la realizaci&oacute;n de la inscripci&oacute;n, en la cra 8 No. 40-62 Edificio Sabio Caldas, Oficina de Admisiones primer piso:<br>
		A)  Dos hojas del comprobante de inscripci&oacute;n impresa de internet.<br>
		B)  Certificado de localidad de residencia del aspirante expedido por la Junta de Acci&oacute;n Comunal o Alcald&iacute;a Local.<br>
		C)  Certificado de estrato socioecon&oacute;mico de residencia de quien costear&aacute; los estudios expedido por Planeaci&oacute;n Distrital (Supercades).<br>
		D)  Certificado expedido por el Colegio donde termin&oacute; estudios de bachillerato en el cual se indique la localidad donde est&aacute; ubicado y el valor mensual de la pensi&oacute;n pagada por el aspirante.  En el caso de haber validado el estudio de educaci&oacute;n media, la Universidad tendr&aacute; en cuenta el costo de la prueba de validaci&oacute;n (Art. 3 Acuerdo 004 de enero 25 de 2006 del CSU).<br>
		E)  Declaraci&oacute;n de renta o certificado de ingresos y retenciones de quien costear&aacute; los estudios, este certificado debe ser expedido en formato de la Direcci&oacute;n de Impuestos y Aduanas Nacionales (DIAN).<br>
		En caso de trabajadores independientes o familiares no declarantes, debe presentarse certificado de no contribuyente.<br>
		F) Certificado de la condici&oacute;n de Inscripci&oacute;n Especial (Avalado por el Ministerio del Interior para el caso de la poblaci&oacute;n indigena y minorias &eacute;tnicas y por el CADEL de la localidad, para los mejores bachilleres de colegios distritales de Bogot&aacute; del a&ntilde;o 2012) en el evento de realizar una Inscripci&oacute;n Especial.</p>
		Adicionalmente debe anexar:<br>
		<ul>
			<li>Tarjeta o reporte del ex&aacute;men de estado ICFES o SABER 11.</li>
			<li>T&iacute;tulo de tecn&oacute;logo.</li>
			<li>Certificado de notas original, donde se especifique el promedio acumulado (num&eacute;rico) en la carrera tecnol&oacute;gica</li>
			<li>Certificado de experiencia laboral, despues del grado de tecn&oacute;logo si la posee, el cual debe presentarse en papel membreteado y firmado por el representante legal o jefe de recursos humanos de la empresa.</li>
		</ul></p>';
		$cadena=$RowCAcasp[0][18];
		$snp=substr($cadena, 0, 7);
		if ($snp=='AC20102')
		{
			echo "Se&ntilde;or aspirante, tenga en cuenta que si su ex&aacute;men de estado ICFES o SABER 11, fue presentado en septiembre de 2010, este ser&aacute; transformado a la escala fija de las puntuaciones normalizadas en la escala hist&oacute;rica del ex&aacute;men SABER 11. Mayor informaci&oacute;n consultar las siguientes direcciones electr&oacute;nicas: <br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/659-informacion-clave-para-jefes-de-admision<br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/694-informaci%C3%B3n-importante-para-jefes-de-admisi%C3%B3n-2";
		}  
	}
	elseif(($car==188)||($car==135)||($car==140)||($car==145)||($car==150)||($car==155)||($car==160)||($car==165)||($car==187))
	{
		echo '<p align="justify"><b>Se&ntilde;or aspirante, el paso que usted debe seguir es:</b></p>
		<p>Asistir a la sede Macarena A, cra 3 A No. 26 A - 40  el d&iacute;a 12 de Noviembre de 2014 despu&eacute;s de las 2:00 p.m., con el fin de consultar si fue <strong>preseleccionado</strong> para presentar entrevista. Tenga en cuenta que dicha preselecci&oacute;n se har&aacute; de acuerdo a los mayores puntajes del ex&aacute;men de estado ICFES o SABER 11.</p>
		<p align="justify">Solamente en el evento de haber realizado una inscripci&oacute;n especial por (Ind&iacute;gena, minor&iacute;as &eacute;tnicas, desplazado, mejores bachilleres de Colegios Distritales de Bogot&aacute; del a&ntilde;o 2012, beneficiarios Ley 1081 o beneficiarios Ley 1084) deben traer los documentos que se indican en el instructivo oficial de admisiones link <a href="../instructivo/cuposesp.php " target="_blank">"cupos especiales"</a>, el siguiente d&iacute;a h&aacute;bil despu&eacute;s de realizada la inscripci&oacute;n a la cra 8 No. 40-62 Oficina de Admisiones.</p>';
		$cadena=$RowCAcasp[0][18];
		$snp=substr($cadena, 0, 7);
		if ($snp=='AC20102')
		{
			echo "Se&ntilde;or aspirante, tenga en cuenta que si su ex&aacute;men de estado ICFES o SABER 11, fue presentado en septiembre de 2010, este ser&aacute; transformado a la escala fija de las puntuaciones normalizadas en la escala hist&oacute;rica del ex&aacute;men SABER 11. Mayor informaci&oacute;n consultar las siguientes direcciones electr&oacute;nicas: <br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/659-informacion-clave-para-jefes-de-admision<br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/694-informaci%C3%B3n-importante-para-jefes-de-admisi%C3%B3n-2";
		}  
	}
	elseif(($car==98)||($car==102)||($car==16))
	{
		echo '<p align="justify"><b>Se&ntilde;or aspirante, el paso que usted debe seguir es:</b></p>
		<p>Asistir a una jornada de inducci&oacute;n para efectos de realizaci&oacute;n de las pruebas especif&iacute;cas teniendo en cuenta el siguiente cronograma:<br><br>
		<table width="100%" align="center"  border="1" cellspacing="0" cellpadding="1">
			<tr class="tr">
				<td>Proyecto Curricular</td>
				<td>Sal&oacute;n</td>
				<td>Fecha</td>
				<td>Hora</td>
			</tr>
			<tr onMouseOver="this.className="raton_arr"" onMouseOut="this.className="raton_aba"">
				<td align="center">Artes Pl&aacute;sticas</td>
				<td align="left">Auditorio Samuel Bedoya, Facultad de Artes ASAB</td>
				<td align="left">10 de Noviembre 2014</td>
				<td align="center">2:00 pm</td>
			</tr>
			<tr onMouseOver="this.className="raton_arr"" onMouseOut="this.className="raton_aba"">
				<td align="center">Artes Musicales</td>
				<td align="left">Auditorio Samuel Bedoya, Facultad de Artes ASAB Cra 13 No. 14 - 69</td>
				<td align="left">2 de Diciembre 2014</td>
				<td align="center">8:00 a.m.</td>
			</tr>
			<tr onMouseOver="this.className="raton_arr"" onMouseOut="this.className="raton_aba"">
				<td align="center">Arte Danzario</td>
				<td align="left">Sal&oacute;n Rogelio Salmona sede Nueva Santa Fe (Cra 5 No. 6 B 15)</td>
				<td align="left">9 de Diciembre de 2014</td>
				<td align="center">7:00 a.m.</td>
			</tr>
                </table>
		</p>
		<p align="justify">Solamente en el evento de haber realizado una inscripci&oacute;n especial por (Ind&iacute;gena, minor&iacute;as &eacute;tnicas, desplazado, mejores bachilleres de Colegios Distritales de Bogot&aacute; del a&ntilde;o 2012, beneficiarios Ley 1081 o beneficiarios Ley 1084) deben traer los documentos que se indican en el instructivo oficial de admisiones link <a href="../instructivo/cuposesp.php " target="_blank">"cupos especiales"</a>, el siguiente d&iacute;a h&aacute;bil despu&eacute;s de realizada la inscripci&oacute;n a la cra 8 No. 40-62 Oficina de Admisiones.</p>';
		$cadena=$RowCAcasp[0][18];
		$snp=substr($cadena, 0, 7);
		if ($snp=='AC20102')
		{
			echo "Se&ntilde;or aspirante, tenga en cuenta que si su ex&aacute;men de estado ICFES o SABER 11, fue presentado en septiembre de 2010, este ser&aacute; transformado a la escala fija de las puntuaciones normalizadas en la escala hist&oacute;rica del ex&aacute;men SABER 11. Mayor informaci&oacute;n consultar las siguientes direcciones electr&oacute;nicas: <br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/659-informacion-clave-para-jefes-de-admision<br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/694-informaci%C3%B3n-importante-para-jefes-de-admisi%C3%B3n-2";
		}  
	}
	elseif($car==104)
	{
		echo '<p align="justify"><b>Se&ntilde;or aspirante, el paso que usted debe seguir es:</b></p>
		<p>Dirigirse con el presente comprobante de inscripci&oacute;n a la Facultad de Artes al día hábil de la inscripción</p>
                <p>Nota 1: Llevar directamente a la Facultad de Artes ASAB, del 4 al 19 de Diciembre de 2014 el formulario con el énfasis (Actuación O Dirección)</p>
		<p>Nota 2: todos los aspirantes sin excepci&oacute;n deben asistir a una jornada de inducci&oacute;n para la realizaci&oacute;n de las pruebas espec&iacute;ficas teniendo en cuenta el siguiente cronograma:</p>
		<table width="100%" align="center"  border="1" cellspacing="0" cellpadding="1">
			<tr onMouseOver="this.className="raton_arr"" onMouseOut="this.className="raton_aba"">
				<td align="center">Artes Esc&eacute;nicas</td>
				<td align="left">Teatro Luis Enrique Osorio (Av. Jim&eacute;nez con Cra. 8 esquina)</td>
				<td align="left">19,20,21,22,23 y 26 de Enero de 2015</td>
				<td align="center">8:00 a.m.</td>
			</tr>
		</table>
		</p>
		<p align="justify">Solamente en el evento de haber realizado una inscripci&oacute;n especial por (Ind&iacute;gena, minor&iacute;as &eacute;tnicas, desplazado, mejores bachilleres de Colegios Distritales de Bogot&aacute; del a&ntilde;o 2012, beneficiarios Ley 1081 o beneficiarios Ley 1084) deben traer los documentos que se indican en el instructivo oficial de admisiones link <a href="../instructivo/cuposesp.php " target="_blank">"cupos especiales"</a>, el siguiente d&iacute;a h&aacute;bil despu&eacute;s de realizada la inscripci&oacute;n a la Cra 8 No. 40-62 Oficina de Admisiones.</p>';
		$cadena=$RowColillaGen[0][15];
		$snp=substr($cadena, 0, 7);
		if ($snp=='AC20102')
		{
			echo "Se&ntilde;or aspirante, tenga en cuenta que los puntajes de ex&aacute;men de estado ICFES o SABER 11, presentados en su comprobante de inscripci&oacute;n son el reflejo de las transformaciones a la escala hist&oacute;rica del ex&aacute;men SABER 11. Mayor informaci&oacute;n consultar las siguientes direcciones electr&oacute;nicas: <br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/659-informacion-clave-para-jefes-de-admision<br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/694-informaci%C3%B3n-importante-para-jefes-de-admisi%C3%B3n-2";
		}    
	}
	/*elseif($car==102)
	{
		echo '<p align="justify"><b>Se&ntilde;or aspirante, el paso que usted debe seguir es:</b></p>
		<p>Asistir a la sede de la cra 13 No. 14-69 a una jornada de inducci&oacute;n para efectos de realizaci&oacute;n de  las pruebas especif&iacute;cas teniendo en cuenta el siguiente cronograma:<br><br>
		<table width="100%" align="center"  border="1" cellspacing="0" cellpadding="1">
			<tr class="tr">
				<td>Proyecto Curricular</td>
				<td>Sal&oacute;n</td>
				<td>Fecha</td>
				<td>Hora</td>
			</tr>
			<tr onMouseOver="this.className="raton_arr"" onMouseOut="this.className="raton_aba"">
				<td align="center">Artes Danzario</td>
				<td align="left">Auditorio Samuel Bedoya, Facultad de Artes ASAB</td>
				<td align="left">3 de julio de 2012</td>
				<td align="center">7 am</td>
			</tr>';
			<tr onMouseOver="this.className="raton_arr"" onMouseOut="this.className="raton_aba"">
				<td align="center">Artes Esc&eacute;nicas opci&oacute;n Teatro</td>
				<td align="left">125</td>
				<td align="left">11 de junio de 2010</td>
				<td align="center">8 am</td>
			</tr>
                echo '</table>
		</p>
		<p align="justify">Solamente en el evento de haber realizado una inscripci&oacute;n especial por (Ind&iacute;gena, minor&iacute;as &eacute;tnicas, desplazado, mejores bachilleres de Colegios Distritales de Bogot&aacute; del a&ntilde;o 2012, beneficiarios Ley 1081 o beneficiarios Ley 1084) deben traer los documentos que se indican en el instructivo oficial de admisiones link <a href="../instructivo/cuposesp.php " target="_blank">"cupos especiales"</a>, el siguiente d&iacute;a h&aacute;bil despu&eacute;s de realizada la inscripci&oacute;n a la cra 8 No. 40-62 Oficina de Admisiones.</p>';
		$cadena=$RowColillaGen[0][15];
		$snp=substr($cadena, 0, 7);
		if ($snp=='AC20102')
		{
			echo "Se&ntilde;or aspirante, tenga en cuenta que los puntajes de ex&aacute;men de estado ICFES o SABER 11, presentados en su comprobante de inscripci&oacute;n son el reflejo de las transformaciones a la escala hist&oacute;rica del ex&aacute;men SABER 11. Mayor informaci&oacute;n consultar las siguientes direcciones electr&oacute;nicas: <br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/659-informacion-clave-para-jefes-de-admision<br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/694-informaci%C3%B3n-importante-para-jefes-de-admisi%C3%B3n-2";
		}  
	} */
	else
	{
		echo '<p align="justify"><b>Se&ntilde;or aspirante, el paso que usted debe seguir es:</b></p>
		<p>Consultar los resultados de admisiones el d&iacute;a 23 de Noviembre de 2014.</p>
		<p align="justify">Solamente en el evento de haber realizado una inscripci&oacute;n especial por (Ind&iacute;gena, minor&iacute;as &eacute;tnicas, desplazado, mejor bachiller de Colegios Distritales de Bogot&aacute; del a&ntilde;o 2012, beneficiarios Ley 1081 o beneficiarios Ley 1084) deben traer los documentos que se indican en el instructivo oficial de admisiones link <a href="../instructivo/cuposesp.php " target="_blank">"cupos especiales"</a>, el siguiente d&iacute;a h&aacute;bil despu&eacute;s de realizada la inscripci&oacute;n a la cra 8 No. 40-62 Oficina de Admisiones.</p>';
/*echo "<p><strong>PROGRAMA ACAD&Eacute;MICO DE ADMINISTRACI&Oacute;N DEPORTIVA </strong>.</p>
			<p>1. Publicaci&oacute;n listado de Admitidos: 26 de julio de 2013.</p>
			<p>2. Entrega de documentos para la liquidación de la Matrícula: 29 y 30 de julio de 2013 de 8 a.m. a 5 p.m. (únicos días) en la cra 8 No. 40-62 piso 1.</p>
			<p>3. EntregaEntrega de Recibos de Pago Estudiantes Admitidos: 31 de julio y 01 de agosto de 2013 (&uacute;nicos d&iacute;as), en el respectivo Proyecto Curricular.</p>
			<p>4. Fecha Limite de Pago de Matr&iacute;cula: 2 de agosto de 2013.</p>
			<p>5. Carnetizaci&oacute;n del 31 de julio de 2013 al 2 de agosto de 2013 en la cra 8 No. 40-62 piso 1 Oficina de Admisiones.</p>
			<p>6. Plazo de oficializaci&oacute;n de la Matr&iacute;cula: del 31 de julio de 2013 hasta el 02 de agosto de 2013, en la Coordinaci&oacute;n del respectivo Proyecto Curricular (en la Sede Tecnol&oacute;gica).</p>
			<p>7. Convocatoria opcionados Proyecto Curricular de Administraci&oacute;n Deportiva: 05 de agosto de 2013.</p>  
			<p class='Estilo16'>* Estas fechas son &Uacute;NICAMENTE para el Proyecto Curricular de Administraci&oacute;n Deportiva.</p>
			<p><strong><br>";*/
		$cadena=$RowCAcasp[0][18];
		$snp=substr($cadena, 0, 7);
		if ($snp=='AC20102')
		{
			echo "Se&ntilde;or aspirante, tenga en cuenta que si su ex&aacute;men de estado ICFES o SABER 11, fue presentado en septiembre de 2010, este ser&aacute; transformado a la escala fija de las puntuaciones normalizadas en la escala hist&oacute;rica del ex&aacute;men SABER 11. Mayor informaci&oacute;n consultar las siguientes direcciones electr&oacute;nicas: <br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/659-informacion-clave-para-jefes-de-admision<br>
			      . http://www.icfes.gov.co/sala-de-prensa/noticias/novedades-historico/694-informaci%C3%B3n-importante-para-jefes-de-admisi%C3%B3n-2";
		}  
	}
	?>
	<p align="center">El diligenciamiento del presente formulario indica que el aspirante ha conocido el instructivo y acepta las condiciones de admisiones de la Universidad Distrital U.D.F.J.C <br>
	</p>
  </fieldset>
</td></tr>
</table>
<p align="justify"><font color="red">
<b>NOTA:</b> En el evento de no quedar registrados los datos de la inscripci&oacute;n en el presente comprobante, vuelva a ingresar y realice nuevamente
el proceso de inscripci&oacute;n; de llegar a persisitir esta situaci&oacute;n, favor acercarse a la Oficina de Admisiones ubicada en la carrera 8 No 40-62, primer piso, Edificio Sabio Caldas,
telefonos 3239300 Ext. 1102, o remitir su inquietud al correo electr&oacute;nico admisiones@udistrital.edu.co, dentro de los plazos asignados en el proceso de admisiones.
</font></p>
<center><input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:80" title="Clic par imprimir el reporte"></center>
</body>
</html>
