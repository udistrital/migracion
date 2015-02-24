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

class funciones_admin_certIngresosRetenciones extends funcionGeneral
{
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/pdf/fpdf.php");
		
		$this->cripto=new encriptar();
		$this->tema=$tema;
		$this->sql=$sql;
		
		//Conexion ORACLE
		$this->accesoOracle=$this->conectarDB($configuracion,"funcionario");
		
		//Conexion MySQL para generacion de certificado
		$this->accesoCertificados=$this->conectarDB($configuracion,"certificados");
		
                //Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="admin_desprendiblesPagos";
		//$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'lugar')";
		
	}
		
	function principal($configuracion,$conexion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		?><table align="center" class="tablaMarcoGeneral">
			<tbody>
				<tr>
					<td >
						<table class="tablaMarco">
							<tbody>
								<tr class=texto_elegante >
									<td>
									<b>::::..</b>  Desprendibles de Pago
									<hr class=hr_subtitulo>
									</td>
								</tr>
								<tr class="bloquecentralcuerpo">
									<td valign="top" colspan=2>
									<h2>Bienvenido!!!.</h2>
									<hr class="hr_subtitulo">
									<p>La <span class="texto_negrita">Oficina Asesora de Sistemas</span> pone a su disposici&oacute;n el m&oacute;dulo de 
									consulta e impresi&oacute;n de desprendibles de los diferentes pagos que le ha hecho la Universidad.</p>							
										<p>Tenga presente que este m&oacute;dulo se encuentra en fase de desarrollo por lo cual podr&iacute;a presentar cambios
										importantes a medida que avanza la prueba piloto.</p>
										<p>Agradecemos comunicar cualquier inquietud al personal de desarrollo quienes gustosamente atender&aacute;n sus solicitudes
										y reportes.</p>
										</p>
										<p>
											"Piense antes de imprimir. Ahorrar papel es cuidar nuestro ambiente".
											<center><img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/" ?>ambiente.jpeg"></center>
										</p>								
									<td class="centrar">
										<p>
										<img alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/" ?>grado.png">
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
	
	//Muestra los últimos tres años, los cuales el funcionario puede generar el Certificado de Ingresos y Retenciones.
	function desprendibleCesantias($configuracion,$conexion)
	{
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		if($usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",'');
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		$valor[0]=$usuario;
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "DatosUsuarios",$valor);
		$resultUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "cesantias",$valor);
		$resultCesantias=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if(!is_array($resultCesantias))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$regresar="<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-1)' style='cursor:pointer;'>";
			$regresar.='<img src="';
			$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
			$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
			$regresar.= '<br>Regresar</a></center>';
			$cadena="Estimado Usuario, usted no tiene registros de pagos de cesantias, comun&iacute;quese con la Divisi&oacute;n de Recuros Humanos.";
			alerta::sin_registro($configuracion,$cadena,$regresar);
		}
		else
		{
		?>
			<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
								<td class="cuadro_brown" >
								<br>
									<ul>
										<li> Estimado funcionario, a continuaci&oacute;n se relacionan los valores de sus cesantias pagadas a partir del a&ntilde;o 2008 a la fecha, cualquier observaci&oacute;n que tenga al respecto, comun&iacute;quese con la Divisi&oacute;n de Recursos Humanos.</li>
										
									</ul>
								</td>
							</tr>
						</table>
						<br>
						<table class="formulario" align="center">
							<tr  class="bloquecentralencabezado">
								<td colspan="3" align="center">
									<p><span class="texto_negrita">CONSULTA PAGO DE CESANTIAS<? echo $_REQUEST['tipo']; ?></span></p>
								</td>
							</tr>
												
							
							<tr align='center' class="cuadro_color">
								<td align="center">Identificaci&oacute;n</td>
								<td align="center" colspan="2">Nombre</td>
							</tr>
							<tr align='center'>
								<td align="center"><? echo $resultUsuario[0][1];?></td>
								<td align="center" colspan="2"><? echo $resultUsuario[0][0];?></td>
							</tr>
							<tr class="cuadro_color">
								<td colspan="3" align="center">
									
								</td>
							</tr>	
							<tr class="cuadro_color">
								<td>
									Fecha causaci&oacute;n <br>(formato DD/MM/AAAA)
								</td>
								<td>
									Valor
								</td>
								<td>
									Fondo
								</td>				
								<td>
									Opciones
								</td>				
							</tr>
								<?
								$i=0;
								while(isset($resultCesantias[$i][0]))
								{
									$imprimir=0;
                                                                        $imprimir = $this->validarVigencia($resultCesantias[$i][3]);
                                                                        echo '<tr>
										<td>
											'.$resultCesantias[$i][1].'
										</td>
										<td>
											$'.number_format($resultCesantias[$i][0],2).'
										</td>
										<td>
											'.$resultCesantias[$i][2].'
										</td>
                                                                                <td>';
                                                                        if ($imprimir==1 && strcmp($resultCesantias[$i][2],'UNIVERSIDAD DISTRITAL')!=0){
                                                                            $this->mostrarEnlaceGenerarCertificado($configuracion,$usuario,$resultCesantias[$i][0],$resultCesantias[$i][1]);
                                                                        }		
									echo '	</td>
									</tr>';
                                                                        $i++;
								}
								?>
						</table>
					</td>
				</tr>
			</table>
		<?
		}
	}
	
	//Exporta el certificad a un archivo PDF. 
	function exportarPdf($sJem, $reportsPath, $reportFileName, $print,$configuracion)
	{
	// completamos el nombre del archivo a exportar con usuario, y la fecha
	$sJem->exportReportToPdfFile($print, $reportsPath.$reportFileName.".pdf");
	
	if (file_exists($reportsPath.$reportFileName.".pdf"))
		{
		$mi_pdf = $reportsPath.$reportFileName.".pdf";
	
		$texto_ayuda = "<b>Exportar en archivo PDF. ";
		?>
		<table class="formulario" align="center">
			<tr>
				<td class="cuadro_brown" >
				<br>
					<ul>
						<li> Haga Click en la imagen del PDF para consultar el certificado de ingresos y retenciones.</li>
						<li> Haga Click en "Regresar" para generar otro certificado.</li>
					</ul>
				</td>
			</tr>
		</table>
		<br>
		<table class="contenidotabla2 centrar" align="center" border="0">
			<caption><font size="2" color="red">El reporte se genero satisfactoriamente.</font></caption>
			<tr>
			<th colspan="3" class="centrar">
				Ver Certificado
			</th>
			</tr>
			<tr>
			<!--td class="centrar">
				<a href="<//?echo $configuracion['host']."/appserv/funcionario/certingret/".$reportFileName.".xls"?>" target=popup>
				<img alt="" src="<?//echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']."/excel.jpg"?>" style="width: 40px;  height: 40px; border-width: 0px;"><br>Formato Excel
				</a>
			</td-->
			<td class="centrar">
				<a href="<?echo $configuracion['host']."/appserv/funcionario/certingret/".$reportFileName.".pdf"?>" target=popup>
				<img alt="" src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']."/pdfPequenno.png"?>" style="width: 40px;  height: 40px; border-width: 0px;"><br>Formato PDF
				</a>
			</td>
			</tr>
			<tr>
			<td colspan="3" class="centrar">
				<?
				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminIngresosRetenciones";
				$variable.="&opcion=mostrar";
				$variable.="&usuario=".$_REQUEST['usuario']."";
				$variable.="&anio=".$_REQUEST['anio']."";
	
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);
				
				$regresar="<br><br><br><br><center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-1)' style='cursor:pointer;'>";
				$regresar.='<img src="';
				$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
				$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
				$regresar.= '<br>Regresar</a></center>';
				//$cadena="<< Regresar >>";
				echo $regresar;
				?>
				
			</td>
			</tr>
		</table>
		<?
		}else{
			echo "<br>ERROR: Hubo problemas al exportar el reporte a formato pdf";
		}
	
	}
	
	/*function exportarXls($objJrep, $reportsPath, $reportFileName, $print,$configuracion)
	{
		$exporterXls = new java("net.sf.jasperreports.engine.export.JRXlsExporter");
		$exporterXls->setParameter($objJrep->JASPER_PRINT,$print);
		$exporterXls->setParameter($objJrep->OUTPUT_FILE_NAME, $reportsPath.$reportFileName.".xls");
		$exporterXls->exportReport();
	
		if (file_exists($reportsPath.$reportFileName.".xls"))
		{
		$mi_xls = $reportsPath.$reportFileName.".xls";
		//echo "<br>Archivo ".$mi_xls." exportado exitosamente!!";
		?>
		<div id="capa_excel" style="position:absolute; left:800px; top:0px; z-index:2; border: 1px none #FFFFFF;">
			<table class="contenidotabla centrar">
			<tr>
				<td>
				<a href="<?echo $configuracion['host']."/appserv/funcionario/certingret/".$reportFileName.".xls"?>" target=popup>
					<span onmouseover="return escape('<?echo $texto_ayuda?>')">
					<img alt=" " src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']."/excel.jpg"?>" style="width: 25px;  height: 25px; border-width: 0px;"><br>Excel
					</span>
				</a>
				</td>
			</tr>
			</table>
		</div>
		<?
		}else{
			echo "<br>ERROR: Hubo problemas al exportar el reporte a formato xls";
			}
		}*/
	
	//Función que genera el certificado de ingresos y retenciones.
	function generar_reporte($configuracion)
    	{
		//$_REQUEST['tipo']="prueba_ms";
		$dir =  $configuracion["raiz_documento"].$configuracion["bloques"]."/CertificadoIngresosRetencion/";
		//Se establecen los paths de los reportes y de las librerias de Java
		$reportsPath = $configuracion["raiz_condor"]."/funcionario/certingret/" ;
		$reportFileName = $_REQUEST['tipo'].".jrxml";//nombre del jrxml
		//$nombre_exportar = $reportFileName;//nombre para los archivos q se exportan
		$nombre_exportar = str_replace(".jrxml","",$reportFileName); //nombre para los archivos q se exportan
		$nombre_exportar .= "_".$this->usuario."_".date('Ymd');
		//echo $reportFileName; EXIT;
		//$jasperReportsLib = "/usr/local/jdk1.6.0_17/jre/lib/";
		
		$jasperReportsLib = $configuracion['jasperReportsLib'];
		//echo "<br>libs ".$jasperReportsLib;exit;
		if($this->checkJavaExtension($configuracion))
		{
			//echo "<br>check ok ";exit;
			//Consultamos de la conexion de acuerdo al proceso
			$servidor = $this->accesoOracle->servidor;
			$base_datos = $this->accesoOracle->db;
			//$base_datos = "sudd";
			$usuario_bd = $this->accesoOracle->usuario;
			$pw_bd = $this->accesoOracle->clave;
			$nombre_conexion = "funcionario";
			/*echo "ooo: ".$base_datos."<br>";
			echo "rrr: ".$servidor."<br>";
			echo "mmm: ".$usuario_bd."<br>";
			echo "nnn: ".$pw_bd."<br>";
			EXIT;*/
			$handle = @opendir($jasperReportsLib);
					
			while(($new_item = readdir($handle))!==false)
			{
			$java_library_path .= 'file:'.$jasperReportsLib.'/'.$new_item .';';
			}try
			{
				java_require($java_library_path);
		
				$Conn2 = new Java("org.altic.jasperReports.JdbcConnection");
				// driver Oracle
				$Conn2->setDriver("oracle.jdbc.driver.OracleDriver");
		
				// Driver MySQL
				// $Conn2->setDriver("com.mysql.jdbc.Driver");
		
				// Se establecen los parametros de connexion para oracle
				//$Conn2->setConnectString("jdbc:oracle:thin:@10.20.0.17:1521:sudd");
				
				$Conn2->setConnectString("jdbc:oracle:thin:@".$servidor.":1521:".$base_datos."");
				
				// Se establecen los parametros de conexion para mysql
				// $Conn2->setConnectString("jdbc:mysql://".$servidor."/".$base_datos."");
		
				$Conn2->setUser("$usuario_bd");
				$Conn2->setPassword("$pw_bd");
				//echo $Conn2; exit;
				//Se compila el reporte .jrxml
				$sJcm = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
		
				$report = $sJcm->compileReport($dir.$reportFileName);
				//enviamos los parametros de filtrado del reporte
				$reportParams = new Java("java.util.HashMap");
				//establece el envio de parametros de acuerdo al reporte
		
				$reportParams->put("usuario",$_REQUEST['usuario']);
				$reportParams->put("anio",$_REQUEST['anio']);
				//$cadena_parametros .= $parametros[0]."=>".$_REQUEST['codProyecto'].",";
				//echo $reportParams; exit;
				//Ejecutamos el reporte para que tome los datos de la BD.
				$sJfm = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
		
				if(!$parametros)
					$print = $sJfm->fillReport($report, $reportParams, $Conn2->getConnection());
				elseif($cadena_parametros)
					$print = $sJfm->fillReport($report, $reportParams, $Conn2->getConnection());
				// Get object containing export parameters
				$objJrep = new Java('net.sf.jasperreports.engine.JRExporterParameter');
		
				$sJem = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
		
				if($print)
				{
				/// Exportar a formato pdf ///
				$this->exportarPdf($sJem, $reportsPath, $nombre_exportar, $print,$configuracion);
				// Exportar a formato xls ///
				//$this->exportarXls($objJrep, $reportsPath, $nombre_exportar, $print, $configuracion);
		
				$archivo = $reportsPath.$nombre_exportar.".pdf";
		
				// Exportar a formato HTML ///
				//$objStream = $this->exportarHtml($sJem, $reportsPath, $nombre_exportar, $print,$objJrep,$configuracion);
		
				}
			}
			catch (JavaException $ex)
			{
			$trace = new Java("java.io.ByteArrayOutputStream");
			$ex->printStackTrace(new Java("java.io.PrintStream", $trace));
			print "java stack trace: $trace\n";
			}
		}
		else
		{
			echo "<br>NO carga las librerias del Bridge ";
		}
	}//cierra if de opcion = generar

	///////////////////////////////////////////////////////////////////////////////////////////////
	// Funcion:     checkJavaExtension                                                           //
	// Descripción: funcion que revisa que se haya cargado la libreria para JavaBridge.         //
	// Parametros de entrada:   variable $configuracion, arreglo $registro, $tema  y $estilo.    //
	// Valores de salida:       true si las librerias fueron cargadas exitosamente.              //
	///////////////////////////////////////////////////////////////////////////////////////////////
	
	function checkJavaExtension($configuracion)
	{
		if(!extension_loaded('java'))
		{
		$sapi_type = php_sapi_name();
		$port = (isset($_SERVER['SERVER_PORT']) && (($_SERVER['SERVER_PORT'])>1024)) ? $_SERVER['SERVER_PORT'] : '8080';
		if ($sapi_type == "cgi" || $sapi_type == "cgi-fcgi" || $sapi_type == "cli")
		{
			if(!(PHP_SHLIB_SUFFIX=="so" && @dl('java.so'))&&!(PHP_SHLIB_SUFFIX=="dll" && @dl('php_java.dll'))&&!(@include_once("java/Java.inc"))&&!(require_once("http://127.0.0.1:$port/java/Java.inc")))
			{
			return "java extension not installed.";
			}
		}
		else
		{
			if(!(@include_once("java/Java.inc")))
			{
			//incluye las librerias para el Bridge con Java
			//require_once("/usr/local/apache-tomcat-6.0.29/webapps/JavaBridge/java/Java.inc");
			//require_once("http://localhost:8082/JavaBridge/java/Java.inc");
			require_once($configuracion['JavaBridge']);
			}
		}
		}
		if(!function_exists("java_get_server_name"))
		{
		return "The loaded java extension is not the PHP/Java Bridge";
		}
		return true;
	}
	
	//Muestra los mensajes de errores que se puedan presentar.	
	function mensajesErrores($configuracion, $accesoOracle,$acceso_db)
	{
		?><table class="fondoImportante" align="center">
			<tr>
				<td class="cuadro_brown" >
				<br>
					<?
					/*foreach($_REQUEST as $clave=>$valor)
					{
						echo $clave.'--'.$valor."<br>";
						
					}*/
					$regresar="<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-1)' style='cursor:pointer;'>";
					$regresar.='<img src="';
					$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
					$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
					$regresar.= '<br>Regresar</a></center>';
					
					if($_REQUEST['mensaje']==1)
					{
						$valor[0]= $_REQUEST['mensaje'];
						echo "<p>El porcentaje digitado, correspondiente a " .$_REQUEST['valor']. ",  NO es un valor num&eacute;rico</p>";
						echo $regresar;
					}
					?>	
				</td>
			</tr>
		</table><?
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
	
	
			
	//Redirecciona la página dependiendo de la acción que se esté realizando en el módulo.
	function redireccionarInscripcion($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		unset($_REQUEST['action']);
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		switch($opcion)
		{
			case "administracion":
				$variable="pagina=admin_usuario";
				$variable.="&accion=1";
				$variable.="&hoja=0";
				break;
			case "msgErrores":
				$variable="pagina=registro_notasDocente";
				$variable.="&opcion=mensajes";
				$variable.="&mensaje=".$valor[0];
				$variable.="&valor=".$valor[1];
				$variable.="&clave=".$valor[2];
				break;
			
			case "formgrado":
				$variable="pagina=registro_notasDocente";
				$variable.="&nivel=".$valor[4];
				break;
				
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}

 
        /**
         * Funcion que muestra el enlace para generar los certificados
         * @param type $configuracion
         * @param type $identificacion
         * @param type $valor
         * @param type $fecha 
         */
        function mostrarEnlaceGenerarCertificado($configuracion,$identificacion,$valor,$fecha) {
       
                $pagina = $configuracion["host"] . $configuracion["site"] . "/index.php?";
                $variable = "pagina=adminDesprendiblesPagos";
                $variable.="&no_pagina=true";
                $variable.="&opcion=generarCertificado";
                $variable.="&identificacion=".$identificacion;
                $variable.="&valor_ces=".$valor;
                $variable.="&fecha_ces=".$fecha;
                include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
                $this->cripto = new encriptar();
                $variable = $this->cripto->codificar_url($variable, $configuracion);
                ?>
                <a href="<?= $pagina . $variable ?>" >Generar Certificado
                </a>
       <?

       }
       
       /**
        *Funcion que llama el metodo para crear el certificado
        * @param type $configuracion 
        */
       function generarCertificado($configuracion){
           $tipo_certificado=1;
           $parametro_sql=array('IDENTIFICACION'=>$_REQUEST['identificacion'],
                                'VALOR'=>$_REQUEST['valor_ces'],
                                'FECHA'=>$_REQUEST['fecha_ces']);
           $this->crearCertificado($tipo_certificado,$parametro_sql,$configuracion);
       
       }
   
       /**
        * Funcion para validar si la vigencia de las cesantias es del ultimo periodo
        * @param type $vigencia
        * @return int 
        */
       function validarVigencia($vigencia){
           $imprimir=0;
           $mes = date('m');
           if($mes<'03') {
               $anio = date('Y')-2;
           }else{
               $anio = date('Y')-1;
           }
           if($anio==$vigencia){
               $imprimir=1;
           }
             
           return $imprimir;
       }
       
       /**
        * Funcion que crea el certificado
        * @param int $tipo
        * @param <array> $parametro_sql
        * @param <array> $configuracion 
        * Utiliza los metodos consultarSeccionesDocumento, consultarParametrosDocumento, ejecutarSqlParametros, verificarValoresConjuntos,
        * reemplazarParametrosEnSecciones, armarDocumento, generarPDF
        */
       function crearCertificado($tipo,$parametro_sql,$configuracion){
           
           $secciones = $this->consultarSeccionesDocumento($tipo);
           if(is_array($secciones)){
                $parametros = $this->consultarParametrosDocumento($tipo);
                if (is_array($parametros)){
                    $parametros = $this->ejecutarSqlParametros($parametros, $parametro_sql);
                    $parametros = $this->verificarValoresConjuntos($parametros);
                    $secciones = $this->reemplazarParametrosEnSecciones($parametros,$secciones);
                    $documento = $this->armarDocumento($secciones);
                    //echo $documento;
                    $this->generarPDF($documento,$configuracion);
                }else{
                    echo "El documento no tiene parametros relacionados";
                }
                
           }else{
               echo "Error al consultar formato del documento";
           }
          
       }
       
       /**
        * Funcion que consulta las secciones que tiene un documento
        * @param int $tipo
        * @return <array> 
        */
       function consultarSeccionesDocumento($tipo){
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoCertificados,"secciones_documento",$tipo);
            return $resultado=$this->ejecutarSQL($configuracion, $this->accesoCertificados, $cadena_sql, "busqueda");
		    
       }
       
       /**
        * Funcion que consulta los parametros de un documento
        * @param int $tipo
        * @return <array> 
        */
       function consultarParametrosDocumento($tipo){
            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoCertificados,"parametros_documento",$tipo);
            return $resultado=$this->ejecutarSQL($configuracion, $this->accesoCertificados, $cadena_sql, "busqueda");
           
       }
       
       /**
        * Función que ejecuta los sql de los parametros para obtener los valores correspondientes
        * @param <array> $parametros
        * @param <array> $parametro_sql
        * @return <array> 
        */
       function ejecutarSqlParametros($parametros,$parametro_sql){
           //var_dump($parametro_sql);
           $parametros= $this->reemplazarParametrosEnSql($parametros,$parametro_sql);
           foreach ($parametros as $key => $parametro) {
               $cadena_sql = $parametro['SENTENCIA_SQL'];

               if($cadena_sql ){
                    $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
                    if (is_array($resultado)){
                        
                        $indices = array_keys($resultado[0]);
                            if(count($indices)>2){
                                $parametros[$key]['VALOR']= $resultado[0];
                            }else{
                            
                                $parametros[$key]['VALOR']= $resultado[0][0];
                            }
                            
                    }
                }
         
            }
         return $parametros;
       }
       
       /**
        * Funcion que reemplaza los valores de los parametros de SQL en el SQL
        * @param <array> $parametros
        * @param <array> $parametro_sql
        * @return <array> 
        */
       function reemplazarParametrosEnSql($parametros,$parametro_sql){
           foreach ($parametros as $key => $parametro) {
               $cadena = $parametro['SENTENCIA_SQL'];
               if($cadena){
                    foreach ($parametro_sql as $key2 => $value) {
                        if($key2 && $value){
                            
                                $nombre_parametro = "P[".$key2."]";
                                $valor_parametro = "'".$value."'";
                                $parametros[$key]['SENTENCIA_SQL']=$this->reemplazarParametroEnCadena($cadena,$nombre_parametro,$valor_parametro);
                                $cadena = $parametros[$key]['SENTENCIA_SQL'];
                        }
                    }
               }
           }
           return $parametros;
       }
       
       /**
        * Funcion para reemplazar los valores de los parametros en una cadena
        * @param String $cadena
        * @param String $nombre
        * @param String $valor
        * @return String
        */
       function reemplazarParametroEnCadena($cadena,$nombre,$valor){
           $cadena=str_replace($nombre, $valor, $cadena);
           return $cadena;
           
       }
       
       /**
        * Funcion que verifica si un resultado de un parametro es un conjunto de valores que se encuentra en un arreglo, y si corresponde a un arreglo,
        * toma cada valor de cada parametro 
        * @param <array> $parametros
        * @return <array> 
        */
       function verificarValoresConjuntos($parametros){
           foreach ($parametros as $key => $parametro) {
                if(is_array($parametro['VALOR'])){
                    $arreglo_valores=$parametro['VALOR'];
                    foreach ($arreglo_valores as $key2 => $value) {
                            if(!is_numeric($key2)){
                                $nombre_parametro = $key2;
                                $valor_parametro = $value;
                                
                                foreach ($parametros as $key3 => $value3) {
                                    if($nombre_parametro==$value3['NOMBRE']){
                        
                                        $parametros[$key3]['VALOR']= $valor_parametro; 
                                    }
                                   
                                }
                            }
                                
                        
                     }
                 
                 }
           }
           return $parametros;
       }

       /**
        * Funcion qie reemplaza los valores de los parametros en las secciones del documento
        * @param <array> $parametros
        * @param <array> $secciones
        * @return <array> 
        */
       function reemplazarParametrosEnSecciones($parametros,$secciones){
           //var_dump($parametros);
           foreach ($parametros as $key => $parametro) {
               $nombre_parametro = "P['".$parametro['NOMBRE']."']";
               $pos = strpos($nombre_parametro, 'VALOR');
               if ($pos>0) {
                    $valor_parametro = "$".number_format($parametro['VALOR'],2);
                   
               }else{
                    $valor_parametro = $parametro['VALOR'];
               }
               if($parametro['NOMBRE'] && $valor_parametro){
                    foreach ($secciones as $key2 => $value) {
                            $cadena=$value['CONTENIDO'];
                            $secciones[$key2]['CONTENIDO']=$this->reemplazarParametroEnCadena($cadena,$nombre_parametro,$valor_parametro);
                    }
               }
           }
           return $secciones;
       }
       
       /**
        * Funcion que une las secciones de un documento
        * @param <array> $secciones
        * @return <array> 
        */
       function armarDocumento($secciones){
            $documento="";
            foreach ($secciones as $key => $seccion) {
                $documento .= $seccion['CONTENIDO'];
            }
            return $documento;
       }
    
       /**
        * Funcion que genera un archivo pdf con el documento
        * @param String $documento
        * @param <array> $configuracion 
        * Utiliza la clase fpdf
        */
       function generarPDF($documento,$configuracion){
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf/fpdf.php");
            ob_end_clean();

            $pdf=new FPDF('P','mm','letter');
            $pdf->SetMargins(30, 40 ,20);
            $pdf->AddPage();
            setlocale(LC_MONETARY, 'es_ES');
            $pdf->SetTextColor(50, 50, 50);
            $pdf->SetFont('Arial','',12);
            $pdf->Ln(1);
            $pdf->MultiCell(0,7,UTF8_DECODE($documento));
            $pdf->Ln(10);
            $pdf->SetFont('Arial','',7);
            $pdf->Cell(70,4,UTF8_DECODE("Generado Automáticamente por Sistema Cóndor"),0);	
            $pdf->Ln(3);
            $pdf->Cell(70,4,UTF8_DECODE("Fuente de datos: Sistema de Nomina"),0);	
            $pdf->Ln(3);
            $pdf->Cell(70,4,UTF8_DECODE("Diseñado por Oficina Asesora de Sistemas - FMCC"),0);	
            $pdf->Output();

       }
       
             
}
	

?>

