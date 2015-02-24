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
	 		 <td width="480px" height="120px">
	  		 </td>		
	 		 <td width="400px">
				<form name="fuera" method="post" autocomplete="off" action="index.php">
					<table width="100%" height="120%"  border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#7A8180" style="border-collapse: collapse">
						<tr>
							<td height="100px">
								<table width="100%" border=0 align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
									<tr>
										<td colspan="2" align="justify">
											<?
											echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='4' color='#FF0000'>
											<img src='img/asterisco.gif'> Respetado(a) Usuario(a).<br>Se esta procesando su petición!</font>";
                                                                                        echo"<br><br><font face='Verdana, Arial, Helvetica, sans-serif' size='3' color='#FF0000'>Por favor, espere algunos minutos!</font>";
											?><br>
										</td>
									</tr>
									
									<tr> 
										
										<td colspan="2" align="center"><br><input name="submit" type="submit" value=" Intertar de Nuevo" class="Estilo6" /></td>
									</tr>
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
	 		 <td colspan="3" height="20px">
	  		</td>
	  	</tr>	  		  	
  	
		<tr align="center">
			<td colspan="3" >
				<table  width="100%" border='0' height="100%">
					<tr>
				  		<td width="55px"> 
				  		</td>	
				  			
				  		<td width="260px" valign="top">
							<?php
								$Win1 = "javascript:popUpWindow('generales/frm_contacto.php?pemail=admisiones@udistrital.edu.co', 'no', 100, 60, 530, 370)";
							?>
							<table width="100%"  border="0" cellpadding="0" cellspacing="0" align="center">
								<tr>
									<td valign="top" align="justify" > 
									<!--///NOTICONDOR/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
							
										<div id='noticias' name='noticias'>
											<div class='noticia_index'>
												<b>
                                                                                                La Oficina Asesora de Sistemas presenta excusas por los inconvenientes que se puedan presentar en la prestaci&oacute;n del servicio.</b>
                                                                                                <br>
											</div>	
											<hr>
                                                                                        <!--
											<div class='noticia_index'>
												<b>&iexcl;ATENCI&Oacute;N DOCENTES, PLANES DE TRABAJO!...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('Se les informa a los Docentes que est&aacute; abiero el sistema para ingresar los PLANES DE TRABAJO, hasta el dia 18/03/2012.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>
											</div>-->
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
							<div name="menu_ayuda" style="position: relative; width: 100px; top: -13px; left: 80px; cursor: pointer; color: DarkSlateGray;" id="menu_ayuda" class="menu" onmouseout="salirMenu(this.id);" onmouseover="llamarMenu(this.id);">	
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
							</div>
							<div id='mensaje' class='mensaje'>
								<br><br>
								<center>Importante!!! Para que este sitio funcione correctamente es necesario activar el contenido JavaScript en su explorador.</center>
							
							</div> 

			  								  								  				
						</td>
						<td align="right">
							  <br><br><br><br>
							  <!--table width="75" border="0" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose VeriSign SSL for secure e-commerce and confidential communications.">
								<tr>
									<td width="75" align="center" valign="top"><script type="text/javascript" src="https://seal.verisign.com/getseal?host_name=condor.udistrital.edu.co&amp;size=M&amp;use_flash=NO&amp;use_transparent=NO&amp;lang=es"></script><br />
										<a href="http://www.verisign.es/products-services/security-services/ssl/ssl-information-center/" target="_blank"  style="color:#EFFBFB; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">Acerca de los certificados SSL</a>
									</td>
								</tr>
							  </table-->
						</td>
					</tr>
					
				</table>
				
			</td>		
		</tr>	
	</table>

	</center>
	
	

	</body>
</html>
