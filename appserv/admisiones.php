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
							"</spam>";
						TITULO='<spam class=\"titlinks\">Ayuda</spam>'
						//mensaje.style.top=parseInt(elemento.style.top)+'px';
					break;
					case "menu_otros":
						SALIDA=	'<spam class=\"links\">'+
							"<br><a href='javascript:void(0)' onClick='javascript:popUpWindow( \"resultados/aviso_resultados.php\", \"yes\", 480, 260, 380, 250)'>- Consultar resultados</a><br>"+
							//'<br><a target= \"_blank\"  href=\"\">No puede conectarse?</a>'+
							'</spam>';
						TITULO='<spam class=\"titlinks\">Ver resultados</spam>'
						//mensaje.style.top=parseInt(elemento.style.top)+'px';
					break;
					case "menu_info":
						SALIDA=	'<spam class=\"links\">'+
							'<br><a target= \"_blank\"  href=\"http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=183&Itemid=101\">- Calendario Acad&eacute;mico</a>'+
							'<br><a target= \"_blank\"  href=\"http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=184&Itemid=100\">- Derechos Pecuniarios</a>'+
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


	
	<center>

	<table style='background: transparent url(grafico/index2.jpg) no-repeat scroll;'  width="1024px" height="640px" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr>
	 		<td colspan="3" height="260px">
		 		<div style=' border:0px solid; width:250px; height:250px; cursor:pointer;' onclick="location.href='http://www.udistrital.edu.co'"></div>
	  		</td>
	  		
	  	</tr>
	  	
	  	
		<tr>
	 		 <td width="580px" height="120px">
	  		 </td>		
	 		 <td width="200px">
				<form name="login" method="post" autocomplete="off" action="conexion/verifica_Aspirante.php">
					<table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#7A8180" style="border-collapse: collapse">
						<tr>
							<td height="120px">
								<table width="100%" border=0 align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
									<tr>
										<td><span class="Estilo6">
											<a href="javascript:void(0)" onClick="javascript:popUpWindow('instructivo/index.php', 'yes', 0,0, screen.availWidth, screen.availHeight)" title="Instrictivo">Instructivo de Admisiones </a>
										</td>
									</tr>
									<tr>
										<td><span class="Estilo6">
											<a href="javascript:void(0)" onClick="javascript:popUpWindow('instructivo/reingreso.php', 'yes', 0,0, screen.availWidth, screen.availHeight)" title="Instrictivo de Reingreso">Instructivo de Reingreso </a>
										</td>
									</tr>
									<tr>
										<td><span class="Estilo6">
											<a href="javascript:void(0)" onClick="javascript:popUpWindow('instructivo/reingreso.php', 'yes', 0,0, screen.availWidth, screen.availHeight)" title="Instrictivo de Transferencias">Instructivo de Transferencias </a>
										</td>
									</tr>
								</table>
								<hr>
								<table width="100%" border=0 align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
									<!--caption><span class="Estilo6">Digite  Usuario y Clave</span></caption-->
									<caption><span class="Estilo6">Digite Usuario y Clave </span></caption>
									<td>	
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
											<td align="right"><span class="Estilo6">Usuario:&nbsp;</span></td>
											<td><input name="user" type="text" class="input" size="15" onKeypress="if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
										</tr>
										<tr>
											<td align="right"><span class="Estilo6">Clave:</span>&nbsp;</td>
											<td><input name="pass" type="password" class="input" id="password" onChange="javascript:this.value=this.value.toLowerCase();" size="15"></td>
										</tr>
										<tr> 
											<td align="center">&nbsp;</td>
											<td align="left"><input name="submit"  type="submit" value=" Entrar " class="Estilo6" onClick="enviaMD5(calculaMD5());" style="height:22; width:90; cursor:pointer" ></td>
										</tr>
									</td>
									<input type="Hidden" name="cifrado" value="">
									<input type="Hidden" name="numero" value="">
								</table>
							</td>
						</tr>
					</table>
				</form>
	  		</td>
	 		<td width="" height="120px">
	 			<span class="Estilo9" height="120"></span>
	  		</td>
	  		</td>
	 		<!--td width="" height="120px">
	 			<span class="Estilo9" height="120"><a href="javascript:void(0)" onClick="javascript:popUpWindow('aspirantes/consignacion.php', 'yes', 210,260, 480, 300)" title="C&oacute;mo ingresar"><font size="48 px">?</font></a></span>
	  		</td-->		  		
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
											<div class='noticia_index'>
												<b>Aspirante, c&oacute;mo ingresar:...</b> <br>
												    <a onmouseout="nd();" onmouseover="return overlib('El usuario es el n&uacute;mero del documento de identidad suministrado en el momento de efectuar el pago.<br>La clave es el n&uacute;mero de referencia asignado por el banco, en el momento de efectuar el pago y se encuentra impreso en el formato de la consignaci&oacute;n.',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="admisiones.php" >ver mas...</a>	
											</div>
											<!--hr>
											<div class='noticia_index'>
												Actualizacion, depuracion y registro de informacion en base de datos Academica<br>
												    <a onmouseout="nd();" onmouseover="return overlib('<span class=links>Estimados Estudiantes y Coordinadores de los Proyectos Curriculares:<br><br> Les informamos que en los pr&oacute;ximos dias estar&aacute;n disponibles dos formularios de captura de informaci&oacute;n,los cuales deben ser diligenciados o actualizados por los estudiantes y coordinadores de los Proyectos Curriculares. Lo anterior siguiendo las instrucciones de la <a href=circularest.pdf>circular enviada por Rector&iacute;a y Vicerrector&iacute;a Acad&eacute;mica</a>.</span>',
												     BORDER,6, BASE,6, BGBACKGROUND,'img/fondo.jpg',
												     FGBACKGROUND,'img/fondo.png', TEXTFONTCLASS,'oltxt',
												     WIDTH,332, OFFSETX,-60, OFFSETY,20, VAUTO);" onmousemove="if(OLie7)self.status=this.href;" href="" >ver mas...</a>	
											</div-->																
											<!--hr>
											<div class='noticia_index'>
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
								<img src="aspirantes/images/consignacion.png">
							</div-->
							
							<div name="nada" style="position: relative; width: 130px; top: -20px; left: 300px; cursor: pointer; color: DarkSlateGray;" id="nada" class="menu" onmouseout="salirMenu(this.id);" onmouseover="llamarMenu(this.id);">	
								<a href="javascript:void(0)" onClick="javascript:popUpWindow('resultados/aviso_resultados.php', 'yes', 200,150, 800, 600)" title="Instrictivo de Reingreso">Ver resultados</a>
							</div>	
							<!--div name="menu_info" style="position: relative; width: 100px; top: -20px; left: 410px; cursor: pointer; color: DarkSlateGray;" id="menu_info" class="menu" onmouseout="salirMenu(this.id);" onmouseover="llamarMenu(this.id);">	
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
					</tr>
				</table>
			</td>		
		</tr>	
	</table>

	</center>
	
	

	</body>
</html>
