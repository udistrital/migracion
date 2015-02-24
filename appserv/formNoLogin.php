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
        $indiceClave = $configuracion["host"] . "/weboffice/index.php?";
	$variable ="pagina=adminClaves";
	$variable.="&parametro=@opcion=presentacion";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceClave=$indiceClave.$variable;

//Enlace para recuperación de contraseña
        include_once("clase/crypto/Encriptador.class.php");
        $miCodificador=Encriptador::singleton();
        $usuario ="159357645";
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
	 		<td width="200px" height="300px">
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
                        <td width="200px" height="125px">&nbsp;
                        </td>		
                        <td width="320px">&nbsp; </td>		
	 		 <td width="480px" rowspan="2" valign="top">
				
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
										<div id='noticias' name='noticias'  style="width: 300px; height: 180px; overflow-y: scroll;">
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
                                                '<br><a target= \"_blank\"  href=\"<?echo $enlacePassword;?>\">- Recuperaci&oacute;n de clave</a>'+
                                                '<br><a target= \"_blank\"  href=\"<?echo $enlaceVideo;?>\">- Video Recuperaci&oacute;n de clave</a>'+
                                                "<br><a href='javascript:void(0)' onClick='javascript:popUpWindow( \"http://www.udistrital.edu.co/novedades/particularNews.php?idNovedad=3985&Type=N\", \"yes\", 90, 40, 800, 620)'>-Recuperaci&oacute;n Contrase&ntilde;a Correo electr&oacute;nico</a><br>"+
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
