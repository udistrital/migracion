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
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="admin_certIngresosRetenciones";
		//$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'lugar')";
		
	}
		
	//Muestra los últimos tres años, los cuales el funcionario puede generar el Certificado de Ingresos y Retenciones.
	function vistaAnio($configuracion,$conexion)
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
		//$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		$valor[0]=$usuario;
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "DatosUsuarios",$valor);
		$resultUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "certificado",$valor);
		$resultCertificado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		//echo "mmm".$resultCertificado[0][0];
				
		?>
			<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
								<td class="cuadro_brown" >
								<br>
									<ul>
										<li> Seleccione un a&ntilde;o, haga Click en la imagen del PDF para generar el certificado de ingresos y retenciones del a&ntilde;o seleccionado.</li>
										<li> Usted podr&aacute; generar los certificados de los &uacute;ltimos tres a&ntilde;os.</li>
									</ul>
								</td>
							</tr>
						</table>
						<br>
						<table class="formulario" align="center">
							<tr  class="bloquecentralencabezado">
								<td colspan="2" align="center">
									<p><span class="texto_negrita">CERTIFICADO DE INGRESOS Y RETENCIONES <? echo $_REQUEST['tipo']; ?></span></p>
								</td>
							</tr>
												
							
							<tr align='center'>
								<td align="center">Identificaci&oacute;n</td>
								<td align="center">Nombre</td>
							</tr>
							<tr align='center'>
								<td align="center"><? echo $resultUsuario[0][1];?></td>
								<td align="center"><? echo $resultUsuario[0][0];?></td>
							</tr>
							<tr class="cuadro_color">
								<td colspan="2" align="center">
									
								</td>
							</tr>	
									<?php
										
                                                                                $ano=(date('Y')-1);
                                                                            
										for($i=$ano; $i>=($ano)-3; $i--)
                                                                                {
                                                                                      if($i>=2011)    
                                                                                         {
                                                                                                 include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                                                                                 include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
                                                                                                 setlocale(LC_MONETARY, 'en_US');
                                                                                                 $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
                                                                                                 $cripto=new encriptar();
                                                                                                 $archivo="IngresosRetencion";
                                                                                                 $menu=new navegacion();
                                                                                                 echo "<tr><td colspan='2' class='cuadro_plano centrar'>
                                                                                                 <a href='";
                                                                                                 $variable="pagina=adminIngresosRetenciones";
                                                                                                 $variable.="&opcion=generar_PDF";
                                                                                                 $variable.="&usuario=".$usuario."";
                                                                                                 $variable.="&anio=".$i."";
                                                                                                 $variable.="&tipo=".$archivo."";
                                                                                                 $variable=$cripto->codificar_url($variable,$configuracion);
                                                                                                 echo $indice.$variable."'";
                                                                                                 echo "title='Generar certificado'>";
                                                                                                 ?>
                                                                                                 <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/pdfPequenno.png" width="35"  border="0">Generar certificado de Ingresos y Retenciones correspondiente al a&ntilde;o <?echo $i?>
                                                                                                 </a>
                                                                                                 </td>
                                                                                                 </tr>
                                                                                                 <?
                                                                                                 //echo '<option value='.$i.'>'.$i.'</option>';
                                                                                         }
                                                                                     }
                                                                                
										?>
								
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<?
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
			<caption><font size="2" color="gold">El reporte se gener&oacute; satisfactoriamente.</font></caption>
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
		
}
	

?>

