<!DOCTYPE HTML PUBLIC  "-//W3C//DTD HTML 4.01//EN" "http://www.w3c.org/TR/html4/strict.dtd">
<?PHP
 session_start(); // this MUST be called prior to any output including whitespaces and line breaks!
$GLOBALS['DEBUG_MODE'] = 1;// CHANGE TO 0 TO TURN OFF DEBUG MODE
//date_default_timezone_set('America/Bogota');
require_once("clase/config.class.php");
require_once("clase/encriptar.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("");
$cripto=new encriptar();
$indiceMoodle="https://condor.udistrital.edu.co/moodle/index.php?";
        $variable="";
        //$variable="pagina=index";
        //$variable.="&opcion=mostrar";
        //$variable.="&modulo=index";
        $enlaceMoodle=$indiceMoodle.$variable;

$enlaceSoporte='https://portalws.udistrital.edu.co/soporte/';
$enlaceVideo='https://docs.google.com/file/d/0BzG7rdBcnWhoUWNsRzlTNmJoZVU/preview';


$indiceProo="https://condor.udistrital.edu.co/weboffice/index.php?";
        $variable="pagina=adminProveedor";
        $variable.="&opcion=consultar";
        $variable.="&modulo=adminProveedor";
        //$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceConsultaPro=$indiceProo.$variable;

include_once("clase/crypto/Encriptador.class.php");
        $miCodificador=Encriptador::singleton();
        $usuario ="159357645";
        //$identificacion = $_SESSION['usuario_login'];
        $tipo=1;
        $indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
        $tokenCondor = "l4v3rn42013!r3cup3raci0ncl4v3s2013";
        $tokenCondor = $miCodificador->codificar($tokenCondor);
        $opcion="temasys=";
        $variable="gestionPassword&pagina=claves";                                                        
        $variable.="&usuario=".$usuario;
        $variable.="&tipo=".$tipo;
        $variable.="&token=".$tokenCondor;
        $variable.="&opcionPagina=gestionPassword";
        //$variable=$cripto->codificar_url($variable,$configuracion);
        $variable=$miCodificador->codificar($variable);
        $enlacePassword = $indiceSaraPassword.$opcion.$variable;  

        //enlaces para información
        $enlacePecuniario=$configuracion["host_derechos_pecuniarios"];
        $enlaceCalendario=$configuracion["host_calendario_acad"]; 

?>
<HTML>
	<HEAD>	<TITLE>C&oacute;ndor</TITLE>
		<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
		<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
		<LINK REL="SHORTCUT ICON" HREF="http://condor.udistrital.edu.co/appserv/img/favicon.ico">
		<link href="script/estilo_index.css" rel="stylesheet" type="text/css">
	</HEAD>
	<BODY onLoad="inicio();">
	<center>
        <table class='centrar'  width="1024px" height="640px" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr>
	 		<td width="200px" height="250px">
		 		<div style=' border:0px solid; width:250px; height:225px; cursor:pointer;' onclick="location.href='http://www.udistrital.edu.co'">
                                <img src="grafico/sabio.png" align="bottom" border="0" />
                                </div>
	  		</td>
                        <td colspan="3" valign="top" align='left' >
                            <div name="menu_ayuda" style="position: relative; width: 130px; top: 50px; left: 40px; cursor: pointer; color: DarkSlateGray;" id="menu_ayuda" class="menu" onmouseout="salirMenu(this.id);" onmouseover="llamarMenu(this.id);">	
                                    Ayuda
                            </div>
                            <div name="menu_otros" style="position: relative; width: 130px; top: 31px; left: 210px; cursor: pointer; color: DarkSlateGray;" id="menu_otros" class="menu" onmouseout="salirMenu(this.id);" onmouseover="llamarMenu(this.id);">	
                                    Otros Accesos
                            </div>	
                            <div name="menu_info" style="position: relative; width: 130px; top: 12px; left: 400px; cursor: pointer; color: DarkSlateGray;" id="menu_info" class="menu" onmouseout="salirMenu(this.id);" onmouseover="llamarMenu(this.id);">	
                                    Informaci&oacute;n
                            </div>																					
                            <div id='mensaje' class='mensaje'>
                                    <br><br>
                                    <center>Importante!!! Para que este sitio funcione correctamente es necesario activar el contenido JavaScript en su explorador.</center>
                            </div> 
                            <div name="condor" style="position: relative; width: 200px; top: 60px; left: 380px; cursor: pointer; color: DarkSlateGray;" id="condor" >	
                                    <img src="grafico/condor.png" width='250' height='120' border="0" align='center' />
                                   
                            </div>
	  		</td> 
                </tr>
                <tr>
                        <td colspan="3" height="30px" align="right" valign="top">
                            <font face='Verdana, Arial, Helvetica, sans-serif' size='5' color='#3b3e35'><B>PORTAL DOCENTE Y ADMINISTRATIVO</B></font>&nbsp;
                        </td>
                        <td width="20px">&nbsp;
                        </td>
                </tr>
		<tr>
                        <td width="200px" height="125px">&nbsp;
                        </td>		
                        <td width="320px">&nbsp; </td>		
	 		 <td width="480px" rowspan="2" valign="top">
                                <br><br><br>
				<form name="login" method="post" autocomplete="off" action="clase/verifica.class.php">
					<table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#7A8180" style="border-collapse: collapse">
						<tr> <td align="left">
                                                    <span class="Estilo6"><b>INGRESE SUS DATOS DE USUARIO</b></span>
                                                    </td>
                                                </tr>
                                                <tr>    
							<td width="100%" >
                                                    		<table width="100%" border=0 align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
									<tr>
										<td colspan="4" height="25px" align="left">
											<?
                                                                                        require_once("script/mensaje_error.inc.php");
											if(isset($_REQUEST['msgIndex'])){
											   $error=$_REQUEST['msgIndex'];
											   echo"<br><font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
													<img src='img/asterisco.gif'>$error_login_ms[$error]</font>";
											} ?>&nbsp;
                                                                                        
                                                                                        
										</td>
									</tr>
									<tr>
										<td align="left" width="60px"><span class="Estilo6">Usuario:&nbsp;</span></td>
									  	<td colspan="3"><input name="<? echo $this->varFormLogueo['usuario'];?>" type="text" class="input" size="30" onKeypress="if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
									</tr>
									<tr>
										<td align="left"><span class="Estilo6">Clave:&nbsp;</span></td>
										<td colspan="3">
                                                                                    <input name="<? echo $this->varFormLogueo['contrasena'];?>" type="password" class="input" id="<? echo $this->varFormLogueo['contrasena'];?>" size="30">
                                                                                
                                                                                </td>
									</tr>
                                                                <?  //verificar accesos errados para mostrar capcha
                                                                  if(isset($_REQUEST[$this->varFormLogueo['acceso']]) && $_REQUEST[$this->varFormLogueo['acceso']]>2 )
                                                                      { ?>
                                                                        <tr>
                                                                            <td align="left" valign="middle" colspan="3" height="20px" >
                                                                             <span class="Estilo3"><b>Ingrese este c&oacute;digo en el campo Validaci&oacute;n<br></b></span>
                                                                             </td>                                                                            
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="left" valign="bottom" colspan="4" height="60px" >
                                                                                    <img id="oasimage" style="border: 0px solid #000; margin-right: 15px" src="./clase/captchaphp/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" align="center" />
                                                                                    <!--<object type="application/x-shockwave-flash" data="./clase/captchaphp/securimage_play.swf?bgcol=#ffffff&amp;icon_file=./clase/captchaphp/images/audio_icon.png&amp;audio_file=./clase/captchaphp/securimage_play.php" height="18" width="18">
                                                                                    <param name="movie" value="./clase/captchaphp/securimage_play.swf?bgcol=#ffffff&amp;icon_file=./clase/captchaphp/images/audio_icon.png&amp;audio_file=./clase/captchaphp/securimage_play.php" />
                                                                                    </object>-->
                                                                                    <a tabindex="-1" style="border-style: none;" href="#" title="Recargar C&oacute;digo" onclick="document.getElementById('oasimage').src = './clase/captchaphp/securimage_show.php?sid=' + Math.random(); this.blur(); return false">
                                                                                 <img src="./clase/captchaphp/images/refresh.png" alt="Recargar C&oacute;digo" height="18" width="18" onclick="this.blur()" align="bottom" border="0" /></a>
                                                                                 <span class="Estilo6"><b>Recargar C&oacute;digo</b></span>
                                                                             </td>
                                                                        </tr>
                                                                        <tr> 
										<td align="left" height="40px"><span class="Estilo6">Validaci&oacute;n:</span>&nbsp;</td>
										<td colspan="2">
                                                                                <input type="text" name="<? echo $this->varFormLogueo['oas_captcha'];?>" class="input" maxlength="8" /></td>
									</tr>    
                                                                  <?  } //termina mostrar capcha ?>
                                                                         <tr> 
                                                                             <td align="center">&nbsp;</td> 
                                                                            <td align="left" colspan="2" height="40px">
                                                                                <input name="submit" type="submit" value=" Entrar " class="Estilo6" onClick="enviarDatos();" style="height:22; width:90; cursor:pointer" >
                                                                            </td>
                                                                        </tr>
									<input type="hidden" name="<? echo $this->varFormLogueo['cifrado'];?>" value="">
									<input type="hidden" name="<? echo $this->varFormLogueo['numero'];?>" value="">
                                                              <? if(isset($_REQUEST[$this->varFormLogueo['acceso']]))
                                                                         {?>
                                                                        <input name="<? echo $this->varFormLogueo['acceso'];?>" type="hidden" value="<? echo $_REQUEST[$this->varFormLogueo['acceso']];?>" class="Estilo6" />
                                                                          <? } ?>  
								</table>
							</td>
						</tr>
                                                
					</table>
				</form>
	  		</td>
	 		<td width="">
	  		</td>		  		
	  	</tr>
		<tr align="center">
			<td colspan="2" valign="top" >
				<table  width="100%" border='0' height="100%">
					<tr>
				  		<td width="5px"> 
				  		</td>	
				  		<td width="300px" valign="top">
							<?php
								$Win1 = "javascript:popUpWindow('generales/frm_contacto.php?pemail=admisiones@udistrital.edu.co', 'no', 100, 60, 530, 370)";
							?>
							<table width="100%"  border="0" cellpadding="0" cellspacing="0" align="center">
								<tr>
									<td valign="top"> 
									<!--///NOTICONDOR/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
										<div id='noticias' name='noticias' style="width: 300px; height: 180px; overflow-y: scroll;">
                                                                                 <?include ('notiCondor/notiCondor.php');?>   
										</div>
									</td>
								</tr>
								<tr>
									<td valign="top"> 
									<table width="100%"  border="0" cellpadding="0" cellspacing="0" align="center">
										<tr>
											<td align="center">
											<div style=' border:0px solid; cursor:pointer;' onclick="location.href='http://acreditacion.udistrital.edu.co'">
											<img src="grafico/acreditacion_Institucional.png" align="bottom" width='69' height='80' border="0" />
											</div>
											</td>
											<td align="center">
											<div style=' border:0px solid; cursor:pointer;' onclick="location.href='http://autoevaluacion.udistrital.edu.co'">
                             				                                <img src="grafico/Autoevaluacion_Acreditación.jpg" align="bottom" width='130' height='49' border="0" />
                             								</div>
											</td>
											<td align="center"> 
											<div style=' border:0px solid; cursor:pointer;' onclick="location.href='http://www.udistrital.edu.co'">
											<img src="grafico/UDistrital.jpg" align="bottom" width='79' height='77' border="0" />
											</div>
											</td>
										</tr>
										

							  		</table>
									</td>
								</tr>
					  		</table>
						</td>
				  		<td align="right">
							  <br><br><br><br>
							  </table-->
						</td>
					</tr>
                                        
				</table>
			</td>	
                        <td colspan="2" >
			</td>
		</tr>	
	</table>
	</center>
	</body>
        <script language="JavaScript" src="script/clicder.js"></script>
        <script language="JavaScript" src="script/md5.js"></script>
        <!--script language="JavaScript" src="script/Entrar.js"></script-->
        <script language="JavaScript" src="script/MuestraLayer.js"></script>
        <script language="JavaScript" src="script/BorraLink.js"></script>
        <script language="JavaScript" src="script/ventana.js"></script>
        <script language="JavaScript" src="script/modificado.js"></script>
        <script language="JavaScript" src='script/overlib/overlibmws.js'></script>
        <script language="JavaScript" src='script/overlib/overlibmws_filter.js'></script>
        <script language="JavaScript" src='script/overlib/overlibmws_print.js'></script>
        <script language="JavaScript" src='script/overlib/overlibmws_shadow.js'></script>
        <script LANGUAGE="JavaScript">
                 function enviarDatos()
                        {document.forms["login"].elements['<? echo $this->varFormLogueo['contrasena'];?>'].value = calcMD5(document.forms['login'].elements['<? echo $this->varFormLogueo['contrasena'];?>'].value);
                         document.forms["login"].submit;
                        }
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
                                                '<br><a target= \"_blank\"  href=\"<?echo $enlacePassword;?>\">- Recuperaci&oacute;n Contrase&ntilde;a de C&oacute;ndor </a>'+
                                                '<br><a target= \"_blank\"  href=\"<?echo $enlaceVideo;?>\">- Video Recuperaci&oacute;n de clave</a>'+
                                                "<br><a href='javascript:void(0)' onClick='javascript:popUpWindow( \"http://www.udistrital.edu.co/novedades/particularNews.php?idNovedad=3985&Type=N\", \"yes\", 90, 40, 800, 620)'>-Recuperaci&oacute;n Contrase&ntilde;a del Correo electr&oacute;nico</a><br>"+
                                                "</spam>";
                                        TITULO='<spam class=\"titlinks\">Ayuda</spam>'
                                        //mensaje.style.top=parseInt(elemento.style.top)+'px';
                                break;
                                case "menu_otros":
                                        SALIDA=	'<spam class=\"links\">'+
                                                '<a target= \"_blank\"  href=\"<?echo $enlaceMoodle;?>\">- Moodle</a>'+
                                                '<br><a target= \"_blank\"  href=\"<?echo $enlaceSoporte;?>\">- Manuales y Videotutoriales de Ayuda</a>'+
                                                '</spam>';
                                        TITULO='<spam class=\"titlinks\">Otros Accesos</spam>'
                                        //mensaje.style.top=parseInt(elemento.style.top)+'px';
                                break;
                                case "menu_info":
                                        SALIDA=	'<spam class=\"links\">'+
                                                '<br><a target= \"_blank\"  href=\"<?echo $enlaceCalendario;?>">- Calendario Acad&eacute;mico</a>'+
                                                '<br><a target= \"_blank\"  href=\"<?echo $enlacePecuniario;?>">- Derechos Pecuniarios</a>'+
                                                '</spam>';
                                        TITULO='<spam class=\"titlinks\">Informaci&oacute;n</spam>'
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
                function inicio()
                    { document.getElementById('mensaje').style.display = 'none';
                      document.forms['login'].elements['<? echo $this->varFormLogueo['usuario'];?>'].focus();
                    }								
        </script>

        
</html>
