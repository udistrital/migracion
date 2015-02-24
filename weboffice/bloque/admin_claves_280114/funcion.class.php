<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");


class funciones_admin_claves extends funcionGeneral
{
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
						
		$this->cripto=new encriptar();
		$this->tema=$tema;
		$this->sql=$sql;
		
		//Conexion ORACLE
		$this->accesoOracle=$this->conectarDB($configuracion,"docente");
		$this->accesoOracle2=$this->conectarDB($configuracion,"estudiante");
		$this->accesoOracle3=$this->conectarDB($configuracion,"funcionario");
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		//$this->acceso_db2=$this->conectarDB($configuracion,"cambio_claveMY");
		$this->acceso_db2=$this->conectarDB($configuracion,"cambio_claveMY");
		//var_dump($this->acceso_db2);  EXIT;
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		 //echo  "mmm".$_REQUEST['usuario'];  
  
		$this->formulario="admin_claves";
		$this->verificar="control_vacio(".$this->formulario.",'mail')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'usu')";
		$this->verificar.="&& verificar_correo(".$this->formulario.",'mail')";
		$this->formulario1="admin_claves";
		$this->verificar1="control_vacio(".$this->formulario1.",'clave')";
		$this->verificar1.="&& control_vacio(".$this->formulario1.",'nclave')";
		$this->verificar1.="&& longitud_cadena(".$this->formulario1.",'clave','6')";
		$this->verificar1.="&& longitud_cadena(".$this->formulario1.",'nclave','6')";
		$this->verificar1.="&& comparar_contenido(".$this->formulario1.",'clave','nclave')";
	}
	
	//Presentación módulo administración de contedido
	function presentacion($configuracion)
	{
	?>
	<table align="center" class="tablaMarcoGeneral">
		<tbody>
			<tr>
				<td >
					<table class="tablaMarco">
						<tbody>
							<tr class="bloquecentralcuerpo">
								<td valign="top">
									<h3>Recuperaci&oacute;n de clave para ingresar al Sistema de Gesti&oacute;n Acad&eacute;mica C&Oacute;NDOR. </h3>
									<p>Hoy en d&iacute;a la seguridad en Internet es fundamental para proteger nuestra informaci&oacute;n de posibles "ladrones inform&aacute;ticos", en la oficina o en nuestra casa podemos tener informaci&oacute;n muy valiosa que es fundamental proteger, esto hace muy importante poner la informaci&oacute;n bajo una clave de acceso dif&iacute;cil de adivinar.</p>
									
									<p>Tenga presente que este m&oacute;dulo se encuentra en fase de desarrollo por lo cual podr&iacute;a presentar cambios
										importantes a medida que avanza la prueba piloto.
									</p>
									<p>Agradecemos comunicar cualquier inquietud al personal de desarrollo quienes gustosamente atender&aacute;n sus solicitudes
										y reportes.</p>
									
									<table border="0" width="90%" cellspacing="0" cellpadding="0" align="center">
									<tr>
										<td colspan="2" align="justify">
											<span class="Estilo2"><strong>Instrucciones para obtener la clave de acceso al Sistema de Gesti&oacute;n Acad&eacute;mica C&oacute;ndor.</strong></span>
											<ol>
												<li>Escriba el correo electr&oacute;nico que tiene registrado en sus datos b&aacute;sicos.</li>
												<li>Digite el usuario.</li>
												<li>Haga clic en &quot;Enviar&quot;.</li>
												<li>El sistema enviar&aacute; a su correo electr&oacute;nico registrado en el sistema, el enlace del formulario para el cambio de su clave, el cual tendr&aacute; vigencia hasta las 11:59:59 p.m. del día que es enviado el correo. </li>
												<li>Si a&uacute;n no ha registrado una cuenta de correo electr&oacute;nico, dir&iacute;jase al Proyecto Curricular para tal fin. De otra forma, no podr&aacute; ingresar al Sistema de Informaci&oacute;n C&oacute;ndor.</li>
											</ol>
										</td>
									</tr>	  
									</table>
									<table width="50%" align="center" border="1" cellpadding="10" cellspacing="0" >
									<tr>
									<td>
									<table class="contenidotabla centrar">
										<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
										<tr>
											<td align="center">
												<fieldset>
													<legend>
														Recuperaci&oacute;n de contrase&ntilde;a
													</legend>
													<table class="formulario">
														<tr>
															<td>
																Usuario
															</td>
															<td>
																<input name="usu" type="text" value="" size="25" maxlength="15"/>
															</td>
														</tr>
                                                                                                                <tr>
															<td>
																Correo
															</td>
															<td>
																<input name="mail" type="text" value="" size="25" maxlength="125"/>
															</td>
														</tr>

														
														<tr align='center'>
															<td colspan="16">
																<table class="tablaBase">
																	<tr>
																		
																		<td align="center">
																			<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
																			<input type='hidden' name='proceso' value='confirmarDatos'>
																			<input value="Enviar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
																		</td>
																		<!--td align="center">
																			<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
																			<input type="submit" name="notdef" value="Calcular Acumulado">
																		</td-->
																		<td align="center">
																			<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</fieldset>	
											</td>
										</tr>
										</form>
									</table>
									</td>
									</tr>
									</table>
									<p align="center">
										"Piense antes de imprimir. Ahorrar papel es cuidar nuestro ambiente".
										<center><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/" ?>ambiente.jpeg"></center>
									</p>	  
									
									<p class="texto_negrita">
									
									</p>
									
									</p>								
								</td>
							</tr>
						</tbody>
					</table>

				</td>
			</tr>
		</tbody>
	</table>
	<?  
	}
	
	//Confirma que el usuario y correo electrónico estśn registrados en el sistema
	function confirmaDatos($configuracion)
	{
		//echo "Prueba";
		if($_REQUEST['mail'] == "")
		{       include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='El campo correo es de obligatorio diligenciamiento.!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
			EXIT;
		}
		if($_REQUEST['usu'] == "")
		{       include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='El campo usuario es de obligatorio diligenciamiento.!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
			EXIT;
		}
		
		unset($valor);
		$valor[0]=$_REQUEST['mail'];
		$valor[1]=$_REQUEST['usu'];

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "tipoUsuario",$valor);
		$resultadoTipoUsu=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                
                
		$cuenta=count($resultadoTipoUsu);
		if(is_array($resultadoTipoUsu))
		{
			//for($i=0; $i<=$cuenta-1; $i++)
                        $i=0;
                    
                        while($i<=$cuenta-1)
			{
				$tipo=$resultadoTipoUsu[$i][0];
				//echo "<br>nnn".$resultadoTipoUsu[$i][0];
				if($tipo==51 || $tipo==52)
				{
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle2, "correoEstudiante",$valor);
					$resultadoCorreoEst=$this->ejecutarSQL($configuracion, $this->accesoOracle2, $cadena_sql, "busqueda");
					$cierto=0;
					if(is_array($resultadoCorreoEst))
					{
						$valor[2]=$tipo;
						$cierto=1;
                                                $i=$cuenta;
					}

				}
				elseif($tipo==4 || $tipo==16 || $tipo==30)
				{
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "correoDocente",$valor);
					$resultadoCorreoDoc=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
					$cierto=0;
					if(is_array($resultadoCorreoDoc))
					{
						$valor[2]=$tipo;
						$cierto=2;
                                                $i=$cuenta;
					}
				}
				else
				{
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle3, "correoFuncionario",$valor);
					$resultadoCorreoFun=$this->ejecutarSQL($configuracion, $this->accesoOracle3, $cadena_sql, "busqueda");
					$cierto=0;
					if(is_array($resultadoCorreoFun))
					{
						$valor[2]=$tipo;
						$cierto=3;
                                                $i=$cuenta;
					}
				}
                           $i++;     
			}

		}
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='El usuario no est&aacute; registrados en el sistema!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
			EXIT;
		}

		if($cierto==1 || $cierto==2 || $cierto==3)
		{
			//echo "Procesando.....";
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='Procesando.....';
			alerta::sin_registro($configuracion,$cadena);
			$this->redireccionarInscripcion($configuracion,"enviaEnlace",$valor);
		}
                else
                {
                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
                        $cadena='El usuario o el correo electr&oacute;nico no est&aacute;n registrados en el sistema!!';
                        $cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
                        alerta::sin_registro($configuracion,$cadena);
                        EXIT;
                }
	}  
	
	//Envia el enlace por correo electrónico
	function enviaEnlace($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		unset($_REQUEST['action']);
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";

		$valor[0]=$_REQUEST['mail'];
		$valor[1]=$_REQUEST['usu'];
		$valor[2]=$_REQUEST['tipoUsu'];
		
		$variable="pagina=adminClaves";
		$variable.="&opcion=cambiarClave";
		$variable.="&mail=".$valor[0];
		$variable.="&usu=".$valor[1];
		$variable.="&tipoUsu=".$valor[2];
		
		$variable=$cripto->codificar_url($variable,$configuracion);
		$enlace=$indice.$variable; 
		//echo "MMM".$enlace;

		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/mail/class.phpmailer.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/mail/class.smtp.php");
		
		$mail = new PHPMailer();     
	
		//configuracion de cuenta de envio
		$mail->Host     = "mail.udistrital.edu.co";
		$mail->Mailer   = "smtp";
		$mail->SMTPAuth = true;
		$mail->Username = "condor@udistrital.edu.co";
		$mail->Password = "CondorOAS2012";
		$mail->Timeout  = 1200;
		$mail->Charset  = "utf-8";
		$mail->IsHTML(false);
		
		//remitente
		$fecha = date("d-M-Y g:i:s A");
		$mail->From     = 'condor@udistrital.edu.co';
		$mail->FromName = 'OFICINA ASESORA DE SISTEMAS';
		$contenido= "Fecha de envio: " . $fecha . "\n";
		$contenido.= "Señor usuario, se ha recibido una solicitud de restauración de contraseña del usuario ".$valor[1].", si tiene alguna inquietud con respecto a este correo, por favor comunicarse con la Oficina Asesora de Sistemas. \n";
		$contenido.= "\nPara restablecer su contraseña, haga clic en el enlace siguiente (o copie y pegue la URL en su navegador):.\n";
		$contenido.= $enlace;
		$contenido.= "\nEste enlace caduca a las 11:59:59 p.m. del día que fue enviado este correo.\n \n";
		$contenido.= isset($_REQUEST["contenido"])?$_REQUEST["contenido"]:'' . "\n \nEste correo ha sido generado automáticamente. Favor no responder.";
		$mail->Body    = $contenido;
		$mail->Subject = " Restaución de la contraseña del sistema de Gestión Académica CÓNDOR";
		//$mail->Subject = "Pruebas piloto inicio de semestre";
		//destinatarios
		//$to_mail1 = ;
		
		$to_mail1 = $valor[0]; //Correo institucional ; 
		//$to_mail2 = $registro[$i][16];//Correo personal
		$to_mail3 = 'recibos@correo.udistrital.edu.co';//Clave del correo recibos2012
		$rutadoc=stripslashes($configuracion["raiz_documento"].'/recibos/'.$registro[$i][1].''.$registro[$i][0].'.pdf');
		//$rutadoc=stripslashes($configuracion["raiz_documento"].'/recibos/Invitacion_Pruebas.pdf');
		//echo $rutadoc;
		//exit;
		$mail->AddAddress($to_mail1);
		$mail->AddCC($to_mail2);
		$mail->AddBCC($to_mail3);
		//$rutadoc = stripslashes($_REQUEST["archivo"]);
		$mail->AddAttachment($rutadoc);
		
		if(!$mail->Send())
		{
			?>
			<script language='javascript'>
			alert('Error! El mensaje no pudo ser enviado!');
			</script>
			<?
			$this->redireccionar();
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "modificaEstadoEnvio",$valor);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			?>
			<script language='javascript'>
			alert('<?echo 'Fue enviado un enlace a su correo electronico, para al cambio clave!'?>');
			</script>                   
			<?
			/*
			echo "Redireccionando.....";
			$indicedoc=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=adminClaves";
			$variable.="&opcion=redireccionar";
			$variable=$cripto->codificar_url($variable,$configuracion);
			$enlace=$indicedoc.$variable;
			echo "<script>location.replace('".$indicedoc.$variable."')</script>";*/

			
			$indicedoc=$configuracion["host"]."/appserv/index.php";
			//$enlace=$indicedoc.$variable;
			echo "<script>location.replace('".$indicedoc."')</script>";
		}
		
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();

	}
	
	//Contiene el formulario para cambiar la clave
	function cambioClave($configuracion)
	{
		$Semilla="cambioClaves";
		$valor[0]=$_REQUEST['mail'];
		$valor[1]=$_REQUEST['usu'];
		$valor[2]=$_REQUEST['tipoUsu'];
		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();
		
		$usuC=$cripto->codificar_variable($valor[1],$Semilla);
		$tipoUsuC=$cripto->codificar_variable($valor[2],$Semilla);
		
		?>
		<table align="center" class="tablaMarcoGeneral">
			<tbody>
				<tr>
					<td >
						<table class="tablaMarco">
							<tbody>
								<tr class="bloquecentralcuerpo">
									<td valign="top">
										<h3>Recuperci&oacute;n de clave para ingresar al Sistema de Gesti&oacute;n Acad&eacute;mica C&Oacute;NDOR. </h3>
										
										<table border="0" width="90%" cellspacing="0" cellpadding="0" align="center">
										<tr>
											<td width="40%" valign="top">
											Una clave segura re&uacute;ne las siguientes caracter&iacute;sticas:</p>
											<ul>
												<li class="PopItemStyle">Contiene entre 6 y 16 caracteres.</li>
												<li class="PopItemStyle">Utiliza los siguientes tipos de caracteres:</li>
												<ul>
													<li>Letras min&uacute;sculas (a, b, c, d...).</li>
													<li>N&uacute;meros (1, 2, 3, 4...).</li>
													<li>S&iacute;mbolos (` ~ ! @ # $ % ^ &amp; * ( ) _ + - = { } | [ ] \ : " ; ' &lt; &gt; ? , . /). </li>
												</ul>
												<li>No debe ser el n&uacute;mero de su documento de identidad ni parte de el.</li>
												<li>No debe ser un nombre o una palabra corriente ni una ligera variaci&oacute;n de alguna de ellas.</li>
												<li>Por favor, digite como m&iacute;nimo 6 caracteres.</li>
											</ul>
											<P align=justify><br>
											<STRONG>Nota.</STRONG> No digite la clave en presencia de otras personas, recuerde que usted es el &uacute;nico responsable e interesado en la informaci&oacute;n aqu&iacute; guardada.</P>
					
											</td>
										</tr>
										</table>
										<table width="50%" align="center" border="1" cellpadding="10" cellspacing="0" >
										<tr>
										<td>
										<table class="contenidotabla centrar">
											<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
											<tr>
												<td align="center">
													<fieldset>
														<legend>
															Cambio de clave
														</legend>
														<table class="formulario">
															<tr>
																<td>
																	Nueva clave
																</td>
																<td>
																	<input name="clave" type="password" value="" size="25" maxlength="15"/>
																</td>
															</tr>
															<tr>
																<td>
																	Confirme nueva clave
																</td>
																<td>
																	<input name="nclave" type="password" value="" size="25" maxlength="15"/>
																</td>
															</tr>
															
															<tr align='center'>
																<td colspan="16">
																	<table class="tablaBase">
																		<tr>
																			
																			<td align="center">
																				<input type='hidden' name='usu' value='<? echo $usuC?>'>
																				<input type='hidden' name='tipoUsu' value='<? echo $tipoUsuC?>'>
																				<input type='hidden' name='action' value='<? echo $this->formulario?>'>
																				<input type='hidden' name='proceso' value='cambiarClave'>
																				<input value="Grabar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $this->verificar1; ?>){document.forms['<? echo $this->formulario1?>'].submit()}else{false}"/><br>
																			</td>
																			<!--td align="center">
																				<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
																				<input type="submit" name="notdef" value="Calcular Acumulado">
																			</td-->
																			<td align="center">
																				<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</fieldset>	
												</td>
											</tr>
											</form>
										</table>
										</td>
										</tr>
										</table>
									 <p>Hoy en d&iacute;a la seguridad en Internet es fundamental para proteger nuestra informaci&oacute;n de posibles "ladrones inform&aacute;ticos", en la oficina o en nuestra casa podemos tener informaci&oacute;n muy valiosa que es fundamental proteger, esto hace muy importante poner la informaci&oacute;n bajo una clave de acceso dif&iacute;cil de adivinar.</p>
									   
									</td>
								</tr>
							</tbody>
						</table>

					</td>
				</tr>
			</tbody>
		</table>
		<? 
	}
	function modificarClave($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();
		$Semilla="cambioClaves";
		unset($valor);
		$valor[0]=$_REQUEST['clave'];
		$valor[1]=$_REQUEST['nclave'];
		$valor[2]=$_REQUEST['usu'];
		$valor[3]=$_REQUEST['tipoUsu'];

		$usuD=$cripto->decodificar_variable($valor[2],$Semilla);
		$tipoUsuD=$cripto->decodificar_variable($valor[3],$Semilla);

		$valor[4]=md5($_REQUEST['clave']);
		$valor[5]=$usuD;

		/*if (ereg("^[a-zA-Z0-9\-_!*"%()@]{6,15}$", $valor[0]))
		{
			 echo " es correcto<br>";
		}
		else
		{
			 echo "no es válido<br>";
		}*/
			
            // var_dump($_REQUEST);
                
		if($_REQUEST['clave']=="" || $_REQUEST['nclave']=="")
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='Clave o contrase&ntilde;a inv&aacute;lida!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
			EXIT;
		}
		if($valor[0]==$usuD)
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='Clave o contrase&ntilde;a inv&aacute;lida, debe ser diferenta a su usuario!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
			EXIT;
		}
		if (strlen($_REQUEST['clave']) > "15")
		{ 
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='Clave o contrase&ntilde;a demasiado larga, debe ser menor a 15 caract&eacute;res!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
			EXIT;
		}
		if (strlen($_REQUEST['clave']) < "5")
		{ 
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='Clave o contrase&ntilde;a demasiado corta, debe ser mayor o igual 6 caract&eacute;res!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
			EXIT;
		}
		if($_REQUEST['clave']==$_REQUEST['nclave'])
		{
		        if($tipoUsuD==51 || $tipoUsuD==52)
			{
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle2, "modificaClaveOracle",$valor);
				$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle2, $cadena_sql, "");

				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db2, "modificaClaveMySQL",$valor);
				$resultado2=$this->ejecutarSQL($configuracion, $this->acceso_db2, $cadena_sql, "");

			}
			elseif($tipoUsuD==4 || $tipoUsuD==16 || $tipoUsuD==30)
			{
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "modificaClaveOracle",$valor);
				$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");

				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db2, "modificaClaveMySQL",$valor);
				$resultado2=$this->ejecutarSQL($configuracion, $this->acceso_db2, $cadena_sql, "");
			}
			else
			{
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle3, "modificaClaveOracle",$valor);
				$resultado1=$this->ejecutarSQL($configuracion, $this->accesoOracle3, $cadena_sql, "");

				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db2, "modificaClaveMySQL",$valor);
				$resultado2=$this->ejecutarSQL($configuracion, $this->acceso_db2, $cadena_sql, "");

			}
		}
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='La clave y la confirmaci&oacute;n son diferentes, por favor reintente!!';
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";
			alerta::sin_registro($configuracion,$cadena);
			EXIT;
		}
		if($resultado1==TRUE && $resultado2==TRUE)
		{
			//echo "Su clave se cambió exitosamente";
			?>
			<script language='javascript'>
			alert('<?echo 'Su clave fue cambiada exitosamente!'?>');
			</script>                   
			<?
			$indicedoc=$configuracion["host"]."/appserv/index.php";
			$enlace=$indicedoc.$variable;
			echo "<script>location.replace('".$indicedoc.$variable."')</script>";
		}
	}

	function comprobar_nombre_usuario_expresiones_regulares($nombre_usuario)
	{
		if (ereg("^[a-zA-Z0-9\-_]*[!]{6,20}$", $nombre_usuario)) {
		    echo "El nombre de usuario $nombre_usuario es correcto<br>";
		    return true;
		} else {
		    echo "El nombre de usuario $nombre_usuario no es válido<br>";
		    return false;
		  }
	} 
 /*_________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
	
	//Rescata el usuario de la variable de sesion.
	function verificarUsuario()
	{
		//Verificar existencia del usuario 	
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosUsuario",$this->identificacion);
		@$unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if(is_array($unUsuario))
		{
			return $unUsuario;			
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "datosUsuario",$this->usuario);
			@$unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");	
			if(is_array($unUsuario))
			{
				return $unUsuario;
			}
			else
			{
				return false;
			}
		
		}
		
	}	
	
	//Redirecciona la página dependiendo de la acción que se esté realizando en el módulo.
	function redireccionarInscripcion($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		unset($_REQUEST['action']);
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		switch($opcion)
		{
			case "formgrado":
				$variable="pagina=adminClaves";
				//$variable.="&opcion=verificar";
				break;
			case "enviaEnlace":
				$variable="pagina=adminClaves";
				$variable.="&opcion=enlace";
				$variable.="&mail=".$valor[0];
				$variable.="&usu=".$valor[1];
				$variable.="&tipoUsu=".$valor[2];
				break;
			
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

