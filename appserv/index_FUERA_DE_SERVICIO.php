<?PHP
//date_default_timezone_set('America/Bogota');
$Mes=date("m");
$random = rand(1,14);
if($Mes != 12){
	$log = "<img src='img/cdr.jpg' width='89' height='88' >";
	$esc  = '<img src="img/12cw03001.png" alt="Universidad Distrital Francisco Jos&eacute; de Caldas" border="0">';
	$oas = '<img src="img/oas.gif" alt="Oficina Asesora de Sistemas" name="Image1" width="60" height="49" border="0">';
}
else{
	$log = "<embed width='57' height='58' src='img/nav/cdr1_nav.swf'>";
	$esc  = '<img src="img/12cw03001.png" alt="Universidad Distrital Francisco Jos&eacute; de Caldas" border="0">';
	$oas = '<img src="img/nav/img'.$random.'.gif" alt="Feliz navidad y Prospero nuevo a&ntilde;o" border="0" width="80" height="69">';
}

$indiceMoodle="https://condor.udistrital.edu.co/moodle/index.php?";
        $variable="pagina=index";
        $variable.="&opcion=mostrar";
        $variable.="&modulo=index";
        $enlaceMoodle=$indiceMoodle.$variable;

$indiceProo="https://condor.udistrital.edu.co/weboffice/index.php?";
        $variable="pagina=adminProveedor";
        $variable.="&opcion=consultar";
        $variable.="&modulo=adminProveedor";
        //$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceConsultaPro=$indiceProo.$variable;


?>
<HTML>
	<HEAD>	<TITLE>C&oacute;ndor</TITLE>
	
		<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
		<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
		<LINK REL="SHORTCUT ICON" HREF="http://condor.udistrital.edu.co/appserv/img/favicon.ico">
		<link href="script/estilo_index.css" rel="stylesheet" type="text/css">
		<script language="JavaScript" src="script/clicder.js"></script>
		<script language="JavaScript" src="script/md5.js"></script>
		<script language="JavaScript" src="script/Entrar.js"></script>
		<script language="JavaScript" src="script/MuestraLayer.js"></script>
		<script language="JavaScript" src="script/BorraLink.js"></script>
		<script language="JavaScript" src="script/ventana.js"></script>
		<script language="JavaScript" src="script/modificado.js"></script>
		<script language="JavaScript" src='script/overlib/overlibmws.js'></script>
		<script language="JavaScript" src='script/overlib/overlibmws_filter.js'></script>
		<script language="JavaScript" src='script/overlib/overlibmws_print.js'></script>
		<script language="JavaScript" src='script/overlib/overlibmws_shadow.js'></script>
		<script LANGUAGE="JavaScript">
			function quitarFrame(){
			  if(self.parent.frames.length != 0) self.parent.location=document.location.href;
			}
			//quitarFrame()
			function llamarMenu(id){
				document.getElementById(id).style.cursor='pointer';
				document.getElementById(id).style.color='red';

				switch(id)
				{	
					case "menu_ayuda":
						SALIDA=	"<spam class='links'>"+
							"<br><a href='generales/cambia_clave_aut.php'>- Olvido su Clave?</a>"+
							"<br><a href=''>- No puede conectarse?</a>"+
							"<br><a href='javascript:void(0)' onClick='javascript:popUpWindow( \"generales/inf_condor.html\", \"yes\", 90, 40, 800, 620)'>- Servicios</a><br>"+							
							"<br><a href='javascript:void(0)' onClick='javascript:popUpWindow( \"HTTPS.pdf\", \"yes\", 90, 40, 800, 620)'>- Manual de Acceso a Condor con el nuevo Protocolo HTTPS</a><br>"+
							"</spam>";
						TITULO='<spam class=\"titlinks\">Ayuda</spam>'
						//mensaje.style.top=parseInt(elemento.style.top)+'px';
					break;
					case "menu_otros":
						SALIDA=	'<spam class=\"links\">'+
							'<br><a target= \"_blank\"  href=\"http://10.20.0.56:7779/sicapital\">- SICAPITAL</a>'+
							'<br><a target= \"_blank\"  href=\"manual/fcu.doc\">- Formato creaci&oacute;n de usuarios SICAPITAL</a>'+
                                                        '<br><a target= \"_blank\"  href=\"<?echo $enlaceMoodle;?>\">- Moodle</a>'+
                                                         '<br><a target= \"_blank\"  href=\"<?echo $enlaceConsultaPro;?>\">- Nuevo Proveedor</a>'+
							'<br><a target= \"_blank\"  href=\"https://sabioud.udistrital.edu.co\">- BODEGA DE DATOS</a>'+
							'</spam>';
						TITULO='<spam class=\"titlinks\">Otros Accesos</spam>'
						//mensaje.style.top=parseInt(elemento.style.top)+'px';
					break;
					case "menu_info":
						SALIDA=	'<spam class=\"links\">'+
							'<br><a target= \"_blank\"  href=\"http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=183&Itemid=101\">- Calendario Acad&eacute;mico</a>'+
							'<br><a target= \"_blank\"  href=\"http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=279&Itemid=1\">- Derechos Pecuniarios</a>'+
							'</spam>';
						TITULO='<spam class=\"titlinks\">Informaci&oacute;n</spam>'
						//mensaje.style.top=parseInt(elemento.style.top)+'px';
					break;
					case "menu_contacto":
						SALIDA=	'<spam class=\"links\">'+
							'<br><a target= \"_blank\" href=\"http://udistrital.edu.co/portal/dependencias/administrativas/tipica.php?id=10\">- Oficina Asesora de Sistemas</a>'+
							//'<br><a herf=\"\">No puede conectarse?</a>'+
							'</spam>';
						TITULO='<spam class=\"titlinks\">Contacto</spam>'
						//mensaje.style.top=parseInt(elemento.style.top)+'px';
					break;															
				}


				
				return overlib(	SALIDA,
						CAPTION,
						TITULO,
						CAPTIONPADDING,
						2,
						CGCLASS,
						'olcg',
						CAPCOLOR,
						'#000000',
				     		BORDER,
				     		2,
				     		BASE,
				     		2,
				     		BGCOLOR,
				     		'Gold',
				     		FGCOLOR,
				     		'#ffffee',
				     		FILTER,
				     		FADEIN,
				     		1,
				     		FADEOUT,
				     		1,
				     		SHADOW,
				     		SHADOWCOLOR,
				     		"Gold",
				     		SHADOWOPACITY,
				     		10,
				     		STICKY,
				     		TEXTFONTCLASS,
				     		'oltxt',
						WIDTH,
						150,
						OFFSETX,
						-60,
						OFFSETY,
						40
					);
			}
			function salirMenu(id){
				document.getElementById(id).style.color='DarkSlateGray';
				document.getElementById('mensaje').style.display = 'none';
			}
			function inicio(){
				document.getElementById('mensaje').style.display = 'none';

			}								
		</script>
	
	</HEAD>

	<BODY onLoad="inicio(); this.document.login.user.focus()">
<!--//
<table width="135" border="0" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose VeriSign SSL for secure e-commerce and confidential communications.">
<tr>
<td width="135" align="center" valign="top"><script type="text/javascript" src="https://seal.verisign.com/getseal?host_name=condor.udistrital.edu.co&amp;size=M&amp;use_flash=NO&amp;use_transparent=NO&amp;lang=es"></script><br />
<a href="http://www.verisign.es/products-services/security-services/ssl/ssl-information-center/" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">Acerca de los certificados SSL</a></td>
</tr>
</table>
-->	
	<center>

	<table style='background: transparent url(grafico/index.jpg) no-repeat scroll;'  width="1024px" height="640px" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr>
	 		<td colspan="3" height="260px">
		 		<div style=' border:0px solid; width:250px; height:250px; cursor:pointer;' onclick="location.href='http://www.udistrital.edu.co'"></div>
	  		</td>
	  	</tr>
	  	
	  	
		<tr>
	 		 <td width="580px" height="120px">
	  		 </td>		
	 		 <td width="200px">
				<form name="login" method="post" autocomplete="off" action="conexion/verifica.php">
					<table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#7A8180" style="border-collapse: collapse">
						<tr>
							<td height="120px">
								<table width="100%" border=0 align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
									<caption><span class="Estilo7"><b>Aviso ipmportante!!</b></span></caption>
									<!--caption><span class="Estilo6">Estamos en Mantenimiento...</span></caption-->
									
									<tr>
										<td colspan="2" align="center">
											<?
											require_once("script/mensaje_error.inc.php");
											if(isset($_GET['error_login'])){
											   $error=$_GET['error_login'];
											   echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
													<a OnMouseOver='history.go(-1)'><img src='img/asterisco.gif'>$error_login_ms[$error]</font></a>";
											}
											?>&nbsp;
										</td>
									</tr>
									<tr>
										<td align="justify">
										<span class="Estilo7">
											Se informa a todos los usuarios, que por mantenimiento a los servidores, el sistema de informaci&oacute;n acad&eacute;mica C&Oacute;NDOR, estar&aacute; fuera de servicio, se habilitar&aacute; a partir de las 12 m..  Agradecemos su comprensi&oacute;n.
										</span>	
										</td>
									</tr>
									<input type="Hidden" name="cifrado" value="">
									<input type="Hidden" name="numero" value="">
								</table>
								
							</td>
						</tr>
					</table>
				</form>
	  		</td>
	 		<td width="">
	  		</td>		  		
	  	</tr>
		<tr>
	 		 <td colspan="3" height="40px">
	  		</td>
	  	</tr>	  		  	
  	
		<tr align="center">
			<td colspan="3" >
				<table  width="100%" border='0' height="100%">
					<tr>
				  		<td width="64px"> 
				  		</td>	
				  			
				  		<td width="260px" valign="top">
							<?php
								$Win1 = "javascript:popUpWindow('generales/frm_contacto.php?pemail=admisiones@udistrital.edu.co', 'no', 100, 60, 530, 370)";
							?>
							<table width="100%"  border="0" cellpadding="0" cellspacing="0" align="center">
								<tr>
									<td valign="top"> 
									<!--///NOTICONDOR/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
							
										<div id='noticias' name='noticias'>
											<!--hr>
											
											<div class='noticia_index'>
												<b>&iexcl;ATENCI&Oacute;N ESTUDIANTES...Diferido pago de matr&iacute;cula!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se informa a todos los estudiantes de PREGRADO, que est&aacute;n abiertas las fechas, desde el 15/11/2011 hasta el 30/11/2011, para solicitar el diferido de la matr&iacute;cula correspondiente al periodo acad&eacute;mico 2012-1.', 
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											
											</div-->	

											<!--div class='noticia_index'>
												<b>&iexcl;ATENCI&Oacute;N USUARIOS...cambio de clave para ingresar a C&Oacute;NDOR!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se informa a todos los usuarios que por motivo de las pr&oacute;ximas elecciones que se realizar&aacute;n en la Universidad, solo se podr&aacute; hacer cambio de clave para ingresar al sistema C&Oacute;NDOR hasta el d&iacute;a lunes 10 de octubre de 2011.', 
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											
											</div>
											<hr>
											<div class='noticia_index'>
												<b>&iexcl;ATENCI&Oacute;N USUARIOS...suspensi&oacute;n de servicio!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se informa a todos los usuarios que por motivo de cierre de semestre, el sistema C&Oacute;NDOR estar&aacute; fuera de servicio el d&iacute;a Viernes 10 de Febrero de 2012. La OAS agradece de antemano su comprensi&oacute;n.', 
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											
											</div>	
											<hr>
											<div class='noticia_index'>
												<b>IMPORTANTE, CAMBIO DE CLAVES!</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se recuerda que como pr&aacute;ctica de seguridad inform&aacute;tica es pertinente el cambio peri&oacute;dico de su clave. Igualmente realice su acceso desde sitios confiables, ya que tenemos informaci&oacute;n de que existen personas sin escr&uacute;pulos intentado capturar su clave de manera fraudulenta. Recuerde que usted tambi&eacute;n es responsable de la seguridad de su informaci&oacute;n. &iexcl;Diga no a la CORRUPCI&Oacute;N!',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											</div>
											<!--div class='noticia_index'>
												<b>&iexcl;ATENCI&Oacute;N DOCENTES, PLANES DE TRABAJO!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se les informa a los Docentes que est&aacute; abiero el sistema para ingresar los PLANES DE TRABAJO, hasta el dia 15/09/2011.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>
											</div-->
											<!--div class='noticia_index'>
												<b>Recibos de pago de matr&iacute;cula!</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se informa a los estudiantes que los recibos de pago de matr&iacute;cula estar&aacute;n disponibles en C&Oacute;NDOR a partir del 1 de Julio de 2011. ',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											</div>										
											<hr>
											<div class='noticia_index'>
												<b>&iexcl;ATENCI&Oacute;N USUARIOS...suspensi&oacute;n de servicio!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se les informa a todos los usuarios que debido a mantenimiento en los servidores, el sistema C&Oacute;NDOR estar&aacute; fuera de servicio el d&iacute;a  Viernes 10 de diciembre de 2010, desde las 08:30 a.m. hasta las 10:00 a.m., de antemano agradecemos su comprensi&oacute;n.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>
											</div>
											<hr>
											<div class='noticia_index'>
												<b>&iexcl;Manual de Acceso a Condor con el nuevo Protocolo HTTPS.</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('A partir del 10 de Diciembre de 2010 el Sistema C&oacute;ndor cuenta con una seguridad adicional que consiste en el cifrado de todas sus p&aacute;ginas a trav&eacute;s del protocolo HTTPS-SSL. Mediante este protocolo la informaci&oacute;n transmitida entre los usuarios y el servidor viajara completamente encriptada ofreciendo con esto alta confidencialidad e integridad de la informaci&oacute;n.<BR>Tenga en cuenta que a partir de esta fecha la primera vez que ingrese al sistema C&oacute;ndor desde su computador su navegador le solicitara agregar un certificado de seguridad, para lo cual deber&aacute; aceptarlo siguiendo los pasos que este le indique.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>		<a href='HTTPS.pdf'>Descargar Tutorial</a>
											</div>
																						
											<div class='noticia_index'>
												<b>Diferido de Matr&iacute;cula </b><br>
												    <a  href="circular_capacitaciones_coordinadores__17_Noviembre_OAS.doc" >Descargar...</a>	
											</div>
											<div class='noticia_index'>
												<b>&iexcl;Diferido de Matr&iacute;cula!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('De acuerdo a la Resoluci&oacute;n 320 del 8 de septiembre de 2006, les informamos a todos los estudiantes, que estan abiertas las fechas para diferir el pago de matr&iacute;cula hasta el d&iacute;a 07 de junio de 2011. <br> De acuerdo con lo anterior, as&iacute; usted haya diferido su matr&iacute;cula el semestre anterior, esto lo deber&aacute; hacer semestralmente.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											</div-->
											<!--hr>
											<div class='noticia_index'>
												<b>C&iacute;rcular CONSEJO ACADEMICO [Captura de Notas]</b><br>
												    <a  href="Circular.pdf" >Descargar...</a>	
											</div-->
												
											<!--div class='noticia_index'>
												<b>C&aacute;lculo de promedio en cr&eacute;ditos</b><br>
												    <a  href="26may10/ManualCalculoPromedioEstudianteCreditos.pdf" >Descargar...</a>	
											</div>
											<hr-->											
											<!--div class='noticia_index'>
												<b>&iexcl;Curso de Franc&eacute;s intensivo - Gratis.!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('En el marco del proceso de internacionalizaci&oacute;n, que adelanta la Universidad con miras a la acreditaci&oacute;n de alta calidad institucional, es relevante fomentar el aprendizaje de una segunda lengua y para ello el Centro de Relaciones Interinstitucionales CERI en asocio con la organizaci&oacute;n internacional AIESEC, ofrece un curso de franc&eacute;s b&aacute;sico gratuito, para un grupo de estudiantes de su facultad, para lo cual se requiere pronta inscripci&oacute;n  haciendo click en Inscripci&oacute;n.  Para realizar la inscripci&oacute;n revise el horario de su facultad, el estudiante solo se puede inscribir en su facultad, en caso contrario se anular&aacute; la inscripci&oacute;n.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	<a href='http://ceri.udistrital.edu.co/index.php/internacionalizacion-e-interinstitucionalizacion-del-curriculo/cursos-de-idiomas/'>inscripci&oacute;n</a>
											</div-->										
											<!--div class='noticia_index'>
												<b>&iexcl;ANUNCIO ESTUDIANTES PRUEBA ACAD&Eacute;MICA!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se informa a los estudiantes que se encuentran en Prueba Acad&eacute;mica que el formato del Acta de compromiso acad&eacute;mico que deben presentar ante el Proyecto Curricular se encuentra disponible en la p&aacute;gina de descarga del Recibo Actual',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											</div>										
											<hr>										
											<div class='noticia_index'>
												<b>&iexcl;EVALUACI&Oacute;N DOCENTES!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se informa a los estudiantes que las fechas para realizar la evaluaci&oacute;n docentes, estan abiertas hasta el d&iacute;a 28 de mayo de 2011.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											</div>
											<div class='noticia_index'>
												<b>&iexcl;EVALUACI&Oacute;N DOCENTES!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se informa a los Docentes y a los Coordinadores que estan habilitadas las fechas para realizar la autoevaluaci&oacute;n y la evaluaci&oacute;n docente por el Consejo Curricular haste el d&iacute;a 27 de Junio de 2011.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											</div>	
											<hr-->
											<!--div class='noticia_index'>
												<b>&iexcl;Digitaci&oacute;n de notas!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se les informa a los Docentes, que por directriz de la Vicerrectoría Académica, se habilito el Sistema CONDOR para la digitación de notas parciales (todos los cohortes) hasta el día 15 de Junio de 2011. ',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											
											</div>
											<!--hr>
											<div class='noticia_index'>
												<b>&iexcl;Consejer&iacute;as...</b>
												    <a onmouseout="nd();" onmouseover="return overlib('Respetados Docentes y Estudiantes, se les informa que se encuentra disponible el servicio de Consejer&iacute;as, los invitamos a que lo conozcan y haga uso del mismo.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											
											</div>
											<div class='noticia_index'>
												<b>&iexcl;ATENCI&Oacute;N COORDINADORES...Inscripci&oacute;n ECAES!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se les informa a los Coordinadores de carreras, que hoy martes 14 de septiembre, es el &uacute;ltimo d&iacute;a de plazo para inscribir los estudiantes que van a presentar el ECAES.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											
											</div>
											<hr>
											<div class='noticia_index'>
												<b>&iexcl;ATENCI&Oacute;N USUARIOS...suspensi&oacute;n de servicio!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se les informa a todos los usuarios que por fallas en el aire acondicionado en el Centro de Gesti&oacute;n OLIMPO, los sistemas C&Oacute;NDOR, SICAPITAL, APLICACI&Oacute;N ACAD&Eacute;MICA, SIGUD, CIDC, estan fuera de servicio, y se reestablecer&aacute; en el transcurso del d&iacute;a, de antemano agradecemos su comprensi&oacute;n.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											
											</div-->
																						
											<!--div class='noticia_index'>
												<b>&iexcl;Fechas de CANCELACIONES!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se informa a los estudiantes, que por la interrupci&oacute;n sufrida en el servicio del sistema de informaci&oacute;n C&Oacute;NDOR, la cancelaci&oacute;n de asignaturas, se ampli&oacute; hasta el dia 01 de septiembre de 2010.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											
											</div-->
											
											<!--hr>

											<div class='noticia_index'>
												<b>Adici&oacute;n de asignaturas</b><br>
												    <a onmouseout="nd();" onmouseover="return overlib('<span class=links></a>Se&ntilde;ores Estudiantes, se les informa que la fecha para adici&oacute;n de asignaturas fu&eacute; ampliada hasta el 19 de febrero, de acuerdo con lo aprobado por el consejo acad&eacute;mico.</span>',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											</div>																
											<hr>
											<div class='noticia_index'>
												Actualizacion, depuracion y registro de informacion en base de datos Academica<br>
												    <a onmouseout="nd();" onmouseover="return overlib('<span class=links>Estimados Estudiantes y Coordinadores de los Proyectos Curriculares:<br><br> Les informamos que en los pr&oacute;ximos dias estar&aacute;n disponibles dos formularios de captura de informaci&oacute;n,los cuales deben ser diligenciados o actualizados por los estudiantes y coordinadores de los Proyectos Curriculares. Lo anterior siguiendo las instrucciones de la <a href=circularest.pdf>circular enviada por Rector&iacute;a y Vicerrector&iacute;a Acad&eacute;mica</a>.</span>',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											</div-->
											<!-- div class='noticia_index'>
												Sistema de Informaci&oacute;n C&Oacute;NDOR<br>
												    <a onmouseout="nd();" onmouseover="return overlib('<span class=links>Estimados usuarios, el sistema de informaci&oacute;n C&Oacute;NDOR actualmente se encuenta en proceso de mejoramiento, agradecemos nos hagan llegar sus comentarios, si tienen alguna sugerencia con respecto a la nueva imagen del sistema de informaci&oacute;n.  Para ingresar al sistema, les recomendamos utilizar el navegador MOZILLA (FIREFOX).</a></span>',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											</div-->
																						
										</div>
							
										<!--div style=' width:100%;'>
											<p align="justify" class="Estilo6"><span class="Estilo13">&iexcl;TENGA CUIDADO! </span>al digitar su clave secreta en kioskos, caf&eacute; internet y/o computadores de sitios p&uacute;blicos. Realice sus operaciones preferiblemente en su computador personal, desde su casa u oficina. En algunos sitios p&uacute;blicos pueden haber instalando programas para obtener su clave.</p>
										</div-->								
			<!--////NOTICONDOR///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->							
									</td>
								</tr>
					  		</table>

						</td>
				  		<td valign="top" align='left' >
							<!--div name="menu_ayuda" style="position: relative; width: 100px; top: -13px; left: 80px; cursor: pointer; color: DarkSlateGray;" id="menu_ayuda" class="menu" onmouseout="salirMenu(this.id);" onmouseover="llamarMenu(this.id);">	
								Ayuda
							</div>
							
							<div name="menu_otros" style="position: relative; width: 130px; top: -5px; left: 220px; cursor: pointer; color: DarkSlateGray;" id="menu_otros" class="menu" onmouseout="salirMenu(this.id);" onmouseover="llamarMenu(this.id);">	
								Otros Accesos
							</div>	
							<div name="menu_info" style="position: relative; width: 100px; top: -20px; left: 410px; cursor: pointer; color: DarkSlateGray;" id="menu_info" class="menu" onmouseout="salirMenu(this.id);" onmouseover="llamarMenu(this.id);">	
								Informaci&oacute;n
							</div>																				
							<div name="menu_contacto" style="position: relative; width: 100px; top: -70px; left: 548px; cursor: pointer; color: DarkSlateGray;" id="menu_contacto" class="menu" onmouseout="salirMenu(this.id);" onmouseover="llamarMenu(this.id);">	
								Contacto
							</div-->
							<div id='mensaje' class='mensaje'>
								<br><br>
								<center>Importante!!! Para que este sitio funcione correctamente es necesario activar el contenido JavaScript en su explorador.</center>
							
							</div> 

			  								  								  				
						</td>
						<td align="right">
							  <br><br><br><br>
							  <table width="75" border="0" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose VeriSign SSL for secure e-commerce and confidential communications.">
								<tr>
									<td width="75" align="center" valign="top"><script type="text/javascript" src="https://seal.verisign.com/getseal?host_name=condor.udistrital.edu.co&amp;size=M&amp;use_flash=NO&amp;use_transparent=NO&amp;lang=es"></script><br />
										<a href="http://www.verisign.es/products-services/security-services/ssl/ssl-information-center/" target="_blank"  style="color:#EFFBFB; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">Acerca de los certificados SSL</a>
									</td>
								</tr>
							  </table>
						</td>
					</tr>
					
				</table>
				
			</td>		
		</tr>	
	</table>

	</center>
	
	

	</body>
</html>
