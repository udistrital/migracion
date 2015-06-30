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
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion_usu_wo.class.php");

class funciones_admin_consultaRecibos extends funcionGeneral
{
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		
		$this->cripto=new encriptar();
		$this->tema=$tema;
		$this->sql=$sql;
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
                $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
                
		//Conexion ORACLE
                if($this->nivel==4){
                    $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
                }elseif($this->nivel==110){
                    $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
                }elseif($this->nivel==114){
                    $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
                }else{
                    //echo "NO TIENE PERMISOS PARA ESTE MODULO";
                    $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
                }

		$this->formulario="admin_consultaRecibos";
		$this->bloque="admin_consultaRecibos";
		//$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'lugar')";

                $this->validacion=new validarUsu();

	}
	
        //Función que permite seleccionar el período para la genracion de recibos
        
        function seleccionarPeriodo($configuracion) {
            $tab=0;
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"seleccionarPeriodo");
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                ?>
                <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                    <table class=tablaMarco width="100%" border="0" align="center" cellpadding="4 px" cellspacing="0px" >
                        <tr>    
                            <td width="50%">Seleccione el per&iacute;odo para generaci&oacute;n de recibos</td>
                            <td >
                                <?
                                    $html_perCod="<select id='periodo_codif' tabindex='".$tab++."' size='1' name='periodo_codif'>";
                                        $html_perCod.="<option value='".$resultAnioPer[0][0]."-".$resultAnioPer[0][1]."'";
                                         $html_perCod.=" >".$resultAnioPer[0][0]."-".$resultAnioPer[0][1]."</option>  ";          
                                        $html_perCod.="<option value='".$resultAnioPer[1][0]."-".$resultAnioPer[1][1]."'";
                                         $html_perCod.=" >".$resultAnioPer[1][0]."-".$resultAnioPer[1][1]."</option>  ";          
                                $html_perCod.="</select>";
                                $html_perCod.="<font color='red'> *</font>";
                                echo $html_perCod;

                                ?> 
                            </td>
                        </tr>
                        <tr>    
                            <td colspan='3'><? $this->enlaceConsultar('Continuar','consultaProyectos',$_REQUEST['usuario']);?></td>
                        </tr>
                    </table>
                </form>
                
            <?
            
        }
        
    /**
     * Función para mostrar enlace que envia los datos para consultar informacion de estudiante
     */
    function enlaceConsultar($nombre,$opcion,$usuario)
        {
            ?><input type='hidden' name='formulario' value="<? echo $this->formulario ?>">
              <input type='hidden' name='action' value="<? echo $this->bloque?>">
              <input type='hidden' name='opcion' value="<?echo $opcion;?>">
              <input type='hidden' name='usuario' value="<?echo $usuario;?>">
              <input value="<?echo $nombre;?>" name="aceptar" tabindex='20' type="button" onclick="document.forms['<? echo $this->formulario?>'].submit()">                              
            <?
        }
    
        
        
	//Ve la lista de Proyectos Curriculares que tiene a cargo el Coordinador
	function verProyectos($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
                    $usuario=$_REQUEST['usuario'];
		}
		if($usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!";
			EXIT;
		}
		$estado=isset($_REQUEST['nivel'])?$_REQUEST['nivel']:'';
		$valor[10]=$estado;
		if (isset($_REQUEST['anio']) && isset($_REQUEST['periodo']))
                {
                    $valor[0]=$usuario;
                    $valor[1]=$ano=$_REQUEST['anio'];
                    $valor[2]=$per=$_REQUEST['periodo'];
                    
                }else{
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
                    $resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                    $ano=$resultAnioPer[0][0];
                    $per=$resultAnioPer[0][1];
                    $valor[0]=$usuario;
                    $valor[1]=$ano;
                    $valor[2]=$per;
                }
		
		if($this->nivel==4){
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaProyectos",$valor);
                    $registroProyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                }elseif($this->nivel==110 || $this->nivel==114){
                    $proyectos =$this->validacion->consultarProyectosAsistente($this->usuario,  $this->nivel, $this->accesoOracle,$configuracion, $this->acceso_db);
                    if(is_array($proyectos)){
                         foreach ($proyectos as $key => $proyecto) {
                            $registroProyectos[$key][0]= $proyecto[0];
                            $registroProyectos[$key][1]= $proyecto[4];
                         }
                    }else{
                            echo "<br><p align='center'>No tiene ningún proyecto curricular asociado.<br><br></p>";
                            exit;
                        }
                }
                
		$cuentaProyectos=count($registroProyectos);
		if(!is_array($registroProyectos))
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaDecanos",$valor);
			$registroDecanos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			if(is_array($registroDecanos))
			{
				$valor[3]=$registroDecanos[0][0];
			}
			else
			{
				$valor[3]=9999;
			}
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaProyectosDecano",$valor);
			$registroProyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			$cuentaProyectos=count($registroProyectos);
			
			if(!is_array($registroProyectos))
			{
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listaProyectosPregrado",$valor);
				$registroProyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
				$cuentaProyectos=count($registroProyectos);
			}
		}
		?>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown" colspan="5">
								<br>
								<ul>
									<li> Primero, haga click sobre en la columna "Generar PDF", luego haga click en la columna "Enviar Correo" para enviar el recibo de pago de matr&iacute;cua a los estudiantes.</li>
									<li> Los estudiantes que esten en prueba académica o tengan alguna deuda con la Universidad, no se les enviar&aacute; el recibo al correo.</li>  
								</ul>
							</td>
						</tr>
						<tr>
							<td>
								<!--p><a href="https://condor.udistrital.edu.co/appserv/manual/plan_trabajo.pdf">
								<img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfito.png"?>" />
								Ver Manual de Usuario.</a></p-->
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td class="" colspan="5" align="center">
								<p><span class="texto_negrita">PROYECTOS CURRICULARES PERIODO ACAD&Eacute;MICO <?echo $valor[1].' - '.$valor[2];?></span></p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table class="contenidotabla">
			<tr>
				<td>
					<fieldset>
						<legend>
							Proyectos Curriculares
						</legend>
						<table class="contenidotabla">
							<tr class="cuadro_color">
								<td>
									Cod. Carrera
								</td>
								<td>
									Carrera
								</td>
								<td>
									<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/pdfmini.png" border="0"><br> Generar PDF
								</td>
								<td>
									<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/mail.png" border="0"><br> Enviar Correo
								</td>
							</tr>  
							<? 
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																								
								setlocale(LC_MONETARY, 'en_US');
								$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
								$cripto=new encriptar();
								for($i=0; $i<=$cuentaProyectos-1; $i++)
								{
									$valor[3]=$registroProyectos[$i][0];
									$valor[4]=$registroProyectos[$i][1];  
									
									$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"totalGenerado",$valor);
									$registroTotalGenerado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");  
									
									$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"pendientePorEnviar",$valor);
									$registroPendienteEnviar=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");  
								  
									echo '<tr><td>'.$registroProyectos[$i][0].'</td>';
									echo '<td>'.$registroProyectos[$i][1].'</td>';
									
									if($registroTotalGenerado[0][0]==0)
									{
									echo '<td>';
										echo $registroTotalGenerado[0][0];
									echo '</td>';
									}
									else
									{
									//echo '<meta http-equiv="refresh" content="3">';  
									echo "<td><a href='";
										$variable="pagina=adminGeneraArchivoRecibos";
										$variable.="&opcion=imprimir";
										$variable.="&no_pagina=true";
										$variable.="&carrera=".$registroProyectos[$i][0];
										$variable.="&usuario=".$valor[0];
										$variable.="&nivel=".$valor[10];
										$variable.="&anio=".$ano;
										$variable.="&periodo=".$per;  
										//$variable.="&no_pagina=true";
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para generar el archivo.'>";
										echo $registroTotalGenerado[0][0];
										echo '</a>';
									echo '</td>';
									}
									if($registroPendienteEnviar[0][0]==0)
									{
									echo '<td>';
										echo $registroPendienteEnviar[0][0];
									echo '</td>';
									}
									else
									{
									echo '<meta http-equiv="refresh" content="60">'; 
									echo "<td><a href='";
										$variable="pagina=adminConsultaRecibos";
										$variable.="&opcion=enviaCorreo";
										//$variable.="&no_pagina=true";
										$variable.="&carrera=".$registroProyectos[$i][0];
										$variable.="&usuario=".$valor[0];
										$variable.="&nivel=".$valor[10];
										$variable.="&anio=".$ano;
										$variable.="&periodo=".$per;  
										//$variable.="&no_pagina=true";
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para enviar los recibos por correo electr&oacute;nico.'>Enviar ";
										echo $registroPendienteEnviar[0][0];
										echo '</a>';
									echo '</td>';
									}
									echo '</tr>';
									
								}  
							?>
							
						</table>
					</fieldset>
				</td>
			</tr>
		</table>
					
		<?
		
	}
	
	//Envia los recibos de pago de matrícula a los correos de los estudiantes.
	function enviarCorreo($configuracion)
	{
		//require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/ProgressBar.class.php");
		/*$sesionActual=isset($sesion_actual[0][0])?$sesion_actual[0][0]:'';
		$ses['id_sesion']=$sesionActual;
		$ses['vl']=(time()+$this->configuracion["expiracion"]);*/
		
		//$cod_renueva = $this->cadena_sql('renovar',$ses);
		//$renueva=  $this->ejecutarSQL($this->configuracion,  $this->acceso_MY, $cod_renueva,"");

		//$cod_renueva=$this->sql->cadena_sql($configuracion,$this->acceso_db,"renovar",$ses);
		//$renueva=$this->ejecutarSQL($configuracion,$this->acceso_db, $cod_renueva,"busqueda");	
		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		setlocale(LC_MONETARY, 'en_US');
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();

		$valor[1]=$_REQUEST['anio'];
		$valor[2]=$_REQUEST['periodo'];
		$valor[3]=$_REQUEST['carrera'];
		
		/*echo "mmm".$valor[1]."<br>";
		echo "mmm".$valor[2]."<br>";
		echo "mmm".$valor[3]."<br>";*/

		//echo "Aqui envia los email";
		//exit;

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"generadoCompleto",$valor);
		$registro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		//echo "mmm".$registro[0][0]."<br>";
		$i=0;
		while(isset($registro[$i][0]))
		{
			$factura[$i]=$registro[$i][0];
			$valor[4]=$registro[$i][1]; 
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"infoEstudiante",$valor);
			$registroEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"deudaEstudiante",$valor);
			$registroDeuda=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			
			if(($registroEstudiante[0][0]==$registro[$i][1]) || ($registroDeuda[0][0]==$registro[$i][1]))
			//if($registroDeuda[0][0]==$registro[$i][1]) //Solamente estudiantes con deuda
			{
				?>
					<script language='javascript'>
					alert('<?echo 'El código '.$registro[$i][1].' está en prueba académica o registra una deuda, por lo tanto el recibo NO será enviado al correo electrónico del estudiante!'?>');
					</script>                   
					<?
					$cierto=1;
			}
			else
			{
				//include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/mail/class.phpmailer.php");
				//include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/mail/class.smtp.php");

				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/mail/class.phpmailer.php");
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/mail/class.smtp.php");
				
				$mail = new PHPMailer();     
			
                                $semilla='condor';
                                $cadena_clave=$cripto->decodificar_variable($configuracion["clave_envios"], $semilla);

				//configuracion de cuenta de envio
				$mail->Host     = "200.69.103.49";
				$mail->Mailer   = "smtp";
				$mail->SMTPAuth = true;
				$mail->Username = $configuracion["correo_envios"];
				$mail->Password = $cadena_clave;
				$mail->Timeout  = 1200;
				$mail->Charset  = "utf-8";
				$mail->IsHTML(false);
				
				//remitente
				$fecha = date("d-M-Y g:i:s A");
				$mail->From     = $configuracion["correo_envios"];
				$mail->FromName = 'OFICINA ASESORA DE SISTEMAS';
				$contenido= "Fecha de envio: " . $fecha . "\n";
				//$contenido.="Respetados estudiantes, en atención a la solicitud del Comité de Decanos, la Oficina Asesora de Sistemas quiere invitarlos amablemente a participar activamente en las pruebas preparatorias que se aplicarán a los procesos automatizados de preinscripción por demanda e inscripciones (Adiciones y cancelaciones)  a través del Sistema CÓNDOR para el próximo semestre 2012-III, las cuales se realizarán desde las 8:00 AM del Jueves 5 de Julio, hasta el viernes 5 de Julio a las 5:00 PM., en el link https://pruebasoas.udistrital.edu.co. \nAgradecemos su colaboración.";
				//$contenido.="\nNOTA IMPORTANTE, los datos usados para esta prueba corresponden al cierre del semeste 2011-3 e inicio del semestre 2012-1.";  
				$contenido.= "Señor estudiante, adjunto encontrará su recibo de pago de matrícula, correspondiente al periodo académico ".$valor[1]." ".$valor[2].", si tiene alguna inquietud con respecto a su recibo, por favor dirijase a la Coordinación su Proyecto Curricular. \n";
				
				$contenido.= "\nIMPORTANTE: Recomendamos imprimir el recibo en una impresora laser.\n";
				$contenido.="\nDatos del estudiante:\n";
				$contenido.='Código: '.$registro[$i][1]. "\n";
				$contenido.='Nombre: '.$registro[$i][13]. "\n";
				//$contenido.=' Proyecto:'.$this->datosRemitente['CODIGO_PROYECTO'].'-'.$this->datosRemitente['NOMBRE_PROYECTO']."\n";
				//$contenido.=' Mail institucional:'.$this->datosRemitente['MAIL_INSTITUCIONAL']. "\n";
				//$contenido.=' Mail personal:'.$this->datosRemitente['MAIL_PERSONAL']. "\n\n";
				//$contenido.= $_REQUEST["contenido"] . "\nAdjunto encontrará archivo con la CIRCULAR especificando fechas.";
				$contenido.= "\n\nRECIBOS DE PAGO - TÉRMINOS Y CONDICIONES";
				$contenido.= "\n\nUn recibo de pago no será valido para la matrícula en los siguientes casos:";
				$contenido.= "\n\n- Cuando el titular del recibo esté en condición de inhabilidad o pérdida de la calidad de estudiante.";
				$contenido.= "\n- Cuando se registren novedades de notas al titular del recibo que afecten el estado académico de éste, incurriendo en situación de inhabilidad o pérdida de la calidad de estudiante*.";
				$contenido.= "\n\nSin importar si el titular del recibo de pago de matrícula, ha cancelado su valor, la matrícula será invalidada en cualquiera de las dos situaciones anteriores.";
				$contenido.= "\n\n* Para el caso de los espacios académicos con nota pendiente, el recibo de pago estará condicionado y será validado únicamente si el titular del recibo, luego de obtener sus notas finales, no incurre en situación de inhabilidad o pérdida de calidad de estudiante.\n";
                                 
                                
				$contenido.= isset($_REQUEST["contenido"])?$_REQUEST["contenido"]:'' . "\nEste mesaje ha sido enviado desde el módulo de recibos de pago de matrícula. Favor no responder.";
				$mail->Body    = $contenido;
				$mail->Subject = "Recibo de pago de matrícula periodo académico ".$valor[1]." - ".$valor[2]."";
				//destinatarios
				//$to_mail1 = ;
				
				$to_mail1 = $registro[$i][17]; //Correo institucional ; 
				$to_mail2 = $registro[$i][16];//Correo personal
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
					/*?>
					<script language='javascript'>
					alert('<?echo 'Mensaje enviado correctamente!'?>');
					</script>                   
					<?*/
					$cierto=1;
					/*echo "Redireccionando.....";
					//sleep(5);//espera 1 segundo
					$indicedoc=$configuracion["host"].$configuracion["site"]."/index.php?";
					$variable="pagina=adminConsultaRecibos";
					//Codigo del Estudiante
					$variable.="&opcion=consultaProyectos";
					//$variable.="&no_pagina=true";
					$variable.="&aplicacion=Condor";
					$variable.="&carrera=".$_REQUEST["carrera"];
					$variable.="&usuario=".$_REQUEST["usuario"];
					$variable=$cripto->codificar_url($variable,$configuracion);
					$enlace=$indicedoc.$variable;		
					//echo $enlace;
					echo "<script>location.replace('".$indicedoc.$variable."')</script>";*/
				}
				
				$mail->ClearAllRecipients();
				$mail->ClearAttachments();
				
							
			}
			$i++;
		}
		$cierto1=isset($cierto)?$cierto:'';
		$cierto=$cierto1;    
		if($cierto==1)
		{
			echo "Redireccionando.....";
			$indicedoc=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=adminConsultaRecibos";
			//Codigo del Estudiante
			$variable.="&opcion=consultaProyectos";
			//$variable.="&no_pagina=true";
			$variable.="&aplicacion=Condor";
			$variable.="&carrera=".$_REQUEST["carrera"];
			$variable.="&usuario=".$_REQUEST["usuario"];
                        $variable.='&anio='.$valor[1];
                        $variable.='&periodo='.$valor[2];
			$variable=$cripto->codificar_url($variable,$configuracion);
			$enlace=$indicedoc.$variable;
			echo "<script>location.replace('".$indicedoc.$variable."')</script>";
		}
	}  
 /*__________________________________________________________________________________________________
		
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
}
	

?>

