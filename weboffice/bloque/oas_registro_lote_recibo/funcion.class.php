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

class funciones_registroLoteRecibo extends funcionGeneral
{

	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->sql=$sql;
		
		//Crear conexion a ORACLE
		$this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
		
		
		//Crear conexion a MySQL con la cuenta por defecto
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		//TO REVIEW
		//Se agrega el siguiente codigo para evitar usuarios con id =0
		if($this->identificacion ==0 && $this->usuario>0)
		{
			$this->identificacion=$this->usuario;
		}		
	}
	
	
	function nuevoRegistro($configuracion,$acceso_db)
	{
		$formulario="registro_lote_recibo";
		$verificar="control_vacio(".$formulario.",'archivo')";		
		$tab=0;
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();	
	?><form enctype='multipart/form-data' method="POST" action="index.php" name="<? echo $formulario?>" onsubmit="">
	<table class="tablaMarco">
		<tbody>
			<tr>
				<td align="center" valign="middle">
					<table style="width: 100%; text-align: left;" border="0" cellpadding="6" cellspacing="0">
						<tr class="bloquecentralcuerpo">
							<td colspan="2" rowspan="1">
								<span class="encabezado_normal">Carga de registros por lotes. (Plantillas)</span>
								<hr class="hr_division">
							</td>		
						</tr>	
						<tr class="bloquecentralcuerpo">
							<td>
								<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="1">
									<tr class="bloquecentralcuerpo">
										<td>
											Archivo
										</td>
										<td>
											<input type="file" name="archivo" tabindex="<? echo $tab++ ?>">
										</td>
									</tr>
									<tr class="bloquecentralcuerpo">
										<td colspan="2">
											<hr class="hr_division">
										</td>									
									</tr>							
									<tr align="center">
										<td colspan="2">
											<table width="80%" align="center" border="0">
												<tr>
													<td align="center">
														<input type="hidden" name="formulario" value="<?														
														$datos="&pagina=oas_registro_recibo_lote";
														$datos.="&procesar=true";
														$datos=$cripto->codificar_url($datos,$configuracion);	
														echo $datos;		
														?>">
														<input type="submit" value="Aceptar" title="Aceptar" />
													</td>
													<td align="center">
														<input type="submit" value="Cancelar" title="Cancelar" />
													</td>
												</tr>
											</table>	
										</td>
									</tr>	
								</table>
							</td>	
						</tr>									
					</table>
				</td>
			</tr>							
		</tbody>
	</table>
	</form><?
	}
	
   	function editarRegistro($configuracion,$tema,$id_entidad,$accesoOracle,$formulario)
   	{}
   	
    	function corregirRegistro()
    	{}
	
	function mostrarRegistro($configuracion,$registro, $total, $opcion="",$variable)
    	{
	}
	
		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/

	function cargarArchivoLote($configuracion)
	{
		//echo "<br>*2 Entro en la funcion cargarArchivoLote*  //funcion.class.php line 130";
		
		$parametro["directorio"]=$configuracion['raiz_documento']."/documento/solicitudes/";
		$parametro["nombreCampo"]="archivo";
		$tipoArchivo= array("xls");
		
		$resultado=$this->cargarArchivoServidor($configuracion, $parametro,$tipoArchivo);	
		
		//Si la carga tuvo exito guardar los datos 
		if(is_array($resultado))
		{	
			//echo "<br>*3 El archivo se cargo al servidor*  //funcion.class.php line 141";
				
			if($this->identificacion>0)
			{
				echo "<br>*4 El identificador de usuario es diferente de 0*  //funcion.class.php line 144";
				$resultado["id_usuario"]=$this->identificacion;
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"insertarLote", $resultado);
				$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");
				return $resultado;
			}
			else
			{
				$this->errorCarga["sinUsuario"]="El archivo no tiene un usuario asociado.";
				return false;
				
			}
			
		}
		else
		{
			return false;		
		}
	}
	
	//Metodo para guardar el contenido del archivo en una matriz 
	function leerArchivoLote($configuracion,$archivo)
	{
		//echo "<br>*6 Entro en funcion leerArchivoLote*  //funcion.class.php line 167";
		
		require_once ($configuracion["raiz_documento"].$configuracion["clases"]."/reader.class.php");
		$data=new Spreadsheet_Excel_Reader();
		$data->read($configuracion['raiz_documento']."/documento/solicitudes/".$archivo);
		
		if(isset($data->sheets))
		{
			return $data;
		}
		else
		{
			$this->errorCarga["sinlectura"]="El archivo no se ha podido leer";
			return false;
		}
		
	}
	
	//Funcion de decodificacion	
	function isUTF8($string)
	{
		return (utf8_encode(utf8_decode($string)) == $string);
	}
	
	//Metodo que recorre la matriz de datos para verificar cada uno de los codigos
	function verificarLote($valor, $data, $tipo="", $configuracion)
	{
		//La primera fila que se lee de cada plantilla especifica el tipo.
		//La variable filaInicio contiene el valor de la fila desde donde se empiezan a procesar registros	
		echo "<br>*9 Entro en funcion verificarlote el tipo de la plantilla es $tipo *  //funcion.class.php line 80";
		switch($tipo)
		{
			case "PLANTILLA GENERAL PREGRADOS":
				$filaInicio=4;			
				$codigoPlantilla=1;
				break;
			
			
			case "PLANTILLA GENERAL POSTGRADOS CREDITOS":
				$filaInicio=5;			
				$codigoPlantilla=2;
				break;
				
			case "PLANTILLA GENERAL POSTGRADOS SALARIOS MINIMOS LEGALES":
				$filaInicio=5;			
				$codigoPlantilla=3;
				break;
			/*
			Esta plantilla ya es innecesaria pues se utilizaba para solicitar copias de recibos.
			case "PLANTILLA SIMPLE":
				$filaInicio=3;
				$codigoPlantilla=4;			
				break;
			*/
			default:
				$this->errorCarga["plantilla"]="El n&uacute:mero de plantilla no es v&aacute;lido.";
				return false;
				break;
		}
		
		$filas=$data->sheets[0]['numRows'];
		$columnas=$data->sheets[0]['numCols'];
		
		$registroProcesar=0; 
		$indiceNoProcesado=0;
		$indiceProcesado=0;
				
		
		$this->solicitud["anno"]=$this->datosGenerales($configuracion,$this->accesoOracle, "anno", "");
		$this->solicitud["periodo"]=$this->datosGenerales($configuracion,$this->accesoOracle, "per", "");
		
		
		//Obtener los proyectos curriculares de las cuales es coordinador el usuario actual
		
		$carrerasCoordinador=$this->carrerasCoordinador($configuracion, $this->identificacion, $this->accesoOracle);
		
		if(is_array($carrerasCoordinador))
		{
			//Recorrer los valores obtenidos de la Plantilla
			for ($i = $filaInicio; $i <= $filas; $i++) 
			{
				unset($exencion);
				
				if(isset($data->sheets[0]['cells'][$i][1]))
				{
					$this->solicitud["estudiante"]=$data->sheets[0]['cells'][$i][1];
					
					$pagador=$this->verificarCodigo($configuracion, $carrerasCoordinador);
					if(is_array($pagador))
					{
						if($codigoPlantilla==2||$codigoPlantilla==3)
						{
							$this->solicitud["unidad"]=$data->sheets[0]['cells'][$i][2];;
						
						}
						$this->solicitud["plantilla"]=$codigoPlantilla;				
						$this->solicitud["usuario"]=$this->identificacion;
						$this->solicitud["carrera"]=$pagador[0][3];
						$this->solicitud["diferido"]=$pagador[0][4];
						$resultado=$this->procesarRegistro($configuracion, $data->sheets[0]['cells'][$i]);
						if($resultado==false)
						{
							$codigoNoProcesado[$indiceNoProcesado]["descripcion"]=$this->errorProceso;
							$codigoNoProcesado[$indiceNoProcesado++]=$data->sheets[0]['cells'][$i][1];
						
						}
						else
						{
							
							$registroProcesar++;
							$codigoProcesado[$indiceProcesado++]=$data->sheets[0]['cells'][$i][1];
						}
					}
					else
					{
						$codigoNoProcesado[$indiceNoProcesado]["descripcion"]=$this->errorProceso;
						$codigoNoProcesado[$indiceNoProcesado++]["valor"]=$data->sheets[0]['cells'][$i][1];
					}
				}	
				
			}
			echo "<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/basico/estilo.php' />\n";
			if($indiceNoProcesado>0)
			{
				$this->mensajeErrorCarga($configuracion, "parcial", $valor);			
				echo "<table class='tablaMarco bloquecentralcuerpo'>";
				echo "<tr><td colspan='5'>Los siguientes C&oacute;digos no se han procesado. Por favor contacte a la Oficina Asesora de Sistemas o Solicitelos mediante oficio.</td></tr>";
				echo "<tr><td colspan='5'>Las razones principales de este rechazo pueden ser: ";
				echo "<ul><li>El estado actual del estudiante no permiten la generaci&oacute;n del recibo.</li>";
				echo "<li>El estudiante no pertenece al (los) proyecto(s) curricular(es) del Coordinador.</li>";
				echo "<li>Los datos de fecha no se ingresaron correctamente. (En el caso de postgrados)</li>";
				echo "<li>El sistema no puede determinar la carrera al cual pertenece el estudiante</li></ul></td></tr>";
				echo "<tr class='bloquecentralencabezado'><td colspan='5'>C&oacute;digos no procesados (".$indiceNoProcesado.")</td></tr>";
				for ($i=0;$i<$indiceNoProcesado;$i++) 
				{
				
					echo "<tr>";
					echo "<td>";
					echo $codigoNoProcesado[$i]["valor"];
					echo "</td>";
					echo "<td>";
					echo $codigoNoProcesado[$i]["descripcion"];
					echo "</td>";
					echo "</tr>\n";
				}
				echo "</table>";
			
			}
			
			if($indiceProcesado>0)
			{	
				
				echo "<table class='tablaMarco bloquecentralcuerpo'>";
				echo "<tr>";
				echo "	<td class='cuadro_plano cuadro_brown' colspan='5'>";
				echo "	<p>En la actualidad se han cargado en el sistema $registroProcesar solicitudes.</p>";
				echo "<p>Para generar los recibos es necesario verificar estas solicitudes en el men&uacute; lateral <span class='texto_negrita'>Solicitados</span></p>";
				echo "<hr class='hr_subtitulo'>";
				echo "	</td>";
				echo "</tr>";
				echo "<tr class='bloquecentralencabezado'><td colspan='5'>C&oacute;digos Exitosamente Procesados (".$indiceProcesado.")</td></tr>";
				for ($i=0;$i<$indiceProcesado;$i++) 
				{
				
					if(is_int($i/5))
					{
						echo "<tr>";
					}
					echo "<td class='cuadro_plano'>";
					echo $codigoProcesado[$i];
					echo "</td>";
					if($i>1 && is_int(($i+1)/5))
					{	
						echo "</tr>\n";
					}	
				}
				echo "</table>";
			
			}
			
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$cripto=new encriptar();
			
			?><hr class="hr_subtitulo">
			<table class='tablaMarco bloquecentralcuerpo'>
			<tr>
			<td>
			<p>N&uacute;mero de registros a verificar:<? echo $registroProcesar?></p>
			<p>N&uacute;mero de registros no procesados:<? echo $indiceNoProcesado?></p>
			<hr class="hr_subtitulo">
			</td>	
			</tr>
			
			</table><?
			return true;
		}
		else
		{
			$this->errorCarga["carreraCoordinador"]="No se encontr&oacute; ninguna carrera asociada al usuario.";
			return false;
		
		}
		
	
	}
	
	function verificarSolicitudCodigo($configuracion, $codigoPagador)
	{
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"datosSolicitud", $codigoPagador);
		$solicitud=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "busqueda");	
		
		if(is_array($solicitud))
		{
			//Colocar esa(s) solicitud(es) en estado (2) cancelada
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"cancelarSolicitud", $codigoPagador);
			$resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");	
		}
		
		return true;
	
	}
	
	function procesarRegistro($configuracion, $registro )
	{
			
		//Verificar que no exista una solicitud del mismo estudiante sin imprimir.
		//En ese caso la ultima peticion sobreescribe la anterior.
		unset($this->errorProceso);
		
		$this->verificarSolicitudCodigo($configuracion, $this->solicitud["estudiante"]);
		
		
		$concepto=$this->conceptoPlantilla($registro, $this->solicitud["plantilla"]);
		$exencion=$this->exencionPlantilla($registro, $this->solicitud["plantilla"]);
				
		$this->solicitud["cuota"]=$this->cuotaPlantilla($registro, $this->solicitud["plantilla"]);	
		
		//Para postgrados se verifica que hayan colocado las fechas de pago de cada cuota y tengan un formato valido
		if($this->solicitud["plantilla"]==2 || $this->solicitud["plantilla"]==3)
		{			
			if(is_numeric($this->solicitud["cuota"]))
			{
			
				for($i=1;$i<($this->solicitud["cuota"]+1);$i++)
				{
					$this->solicitud["cuotaGuardar"]=$i;
					
					$dia=$registro[17+(3*($i-1))];
					$mes=$registro[18+(3*($i-1))];
					$anno=$registro[19+(3*($i-1))];
					
					if(($dia>31||$mes>12||$anno !=$this->solicitud["anno"]))
					{
						$this->errorProceso="D&iacute;a, mes o a&ntilde; erroneos. Recuerde que el a&ntilde;o debe escribirse completo. Ej. 2009";
						return false;
					}
					
				}
			
			}
		
		}
		elseif($this->solicitud["plantilla"]==1)
		{
		
			if(is_numeric($this->solicitud["cuota"]))
			{
				if($this->solicitud["cuota"]!=0 && $this->solicitud["cuota"]!=1 && $this->solicitud["cuota"]!=2)
				{
					$this->solicitud["cuota"]=0;
				
				}
				
			}
			else
			{
				$this->solicitud["cuota"]=0;
			}
		
		}
		
		$this->solicitud["solicitud"]=$this->guardarSolicitud($configuracion);
		
		if(is_numeric($this->solicitud["solicitud"]))
		{
			
			$resultado=true;
			
			//Guardar informacion de las cuotas
			$resultado=$this->guardarCuotas($configuracion, $registro);
			if($resultado==false)
			{
				$this->errorProceso="No se pudo guardar la cuota.";
				return false;
			
			}
			
			
			//Guardar exenciones
			if(is_array($exencion))
			{
				$resultado=$this->guardarExencion($configuracion, $exencion);
				if($resultado==false)
				{
					$this->errorProceso="No se pudo guardar las exenciones.";
					return false;
				}
			}
			
			//***************************************************************
			
			
			//Guardar Concepto
					
			if(is_array($concepto))
			{
				$resultado =$this->guardarConcepto($configuracion, $concepto);
				if($resultado==false)
				{
					$this->errorProceso="No se pudo guardar los conceptos.";
					return false;
				}
				
			}
			
			return $resultado;
		}		
		else
		{	
			$this->errorProceso="No se pudo guardar la solicitud";
			return false;
		}
	
	
	
	}
	
	function verificarCodigo($configuracion, $carrera)
	{
				
		//Buscar el codigo en la base de datos	
		$cadena_sql=$this->sql->cadena_sql($configuracion, $this->accesoOracle,"datosEstudiante", $this->solicitud["estudiante"]);	
		$pagador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql ,"busqueda");	
		
		if(is_array($pagador))
		{
			$carreraEstudiante=$pagador[0][3];
			
			$i=0;
			while(isset($carrera[$i])) 
			{
				if($carreraEstudiante==$carrera[$i][0])
				{
					return $pagador;		
				}
			
				$i++;
			}
			$this->errorProceso="La carrera del estudiante no se puede asociar a la del usuario..";
			
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion, $this->accesoOracle,"datosBasicosEstudiante", $this->solicitud["estudiante"]);	
			$pagador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql ,"busqueda");
			if(is_array($pagador))
			{
				$cadena_sql=$this->sql->cadena_sql($configuracion, $this->accesoOracle,"datosPagoEstudiante", $this->solicitud["estudiante"]);	
				$pagador=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql ,"busqueda");
				if(!is_array($pagador))
				{
					$this->errorProceso="Sin informaci&oacute;n de matr&iacute;cula. Solicitar por Oficio indicando este mensaje.";
				}
				
			}
			else
			{
				$this->errorProceso="El c&oacute;digo no esta registrado en la base de datos.";
			}
		}
		return false;
		
	
	}
	
	function guardarExencion($configuracion, $exencion)
	{
		
		switch($this->solicitud["plantilla"])
		{
		
			//De acuerdo a la columna de la plantilla se correlacionan las diferentes exenciones
			//Ej: en la plantilla Pregrados General la columna 8 equivale a la exencion 4 de la tabla exencion
			case 1:
				//Pregrados General
				$idExencion = array(5 => 1, 6 => 2, 7 => 3, 8 => 4, 9 => 5 , 10 => 6, 11 => 7, 12 => 8, 13 => 9);
				break;
				
			case 2: //Postgrados Creditos
				$idExencion = array(7 => 1, 8 => 10, 9 => 11, 10 => 12 , 11 => 5, 12 => 13, 13 => 14, 14 => 15, 15 => 2, 16 => 16);			
				break;
				
			case 3://Postgrados SMLV
				$idExencion = array(7 => 1, 8 => 10, 9 => 11, 10 => 12 , 11 => 5, 12 => 13, 13 => 14, 14 => 15, 15 => 2, 16 => 16);			
				break;
		}
		
		$resultado=true;
		
		//Se recorre todas las exenciones marcada en la plantilla
		$porcentajeExencion=0;
		foreach($exencion as $columna => $valor) 
		{
			unset($variable);
			if (array_key_exists($columna, $idExencion)) 
			{
				$this->solicitud["id_exencion"]=$idExencion[$columna];
				
				if($this->solicitud["id_exencion"]==1)
				{
					$certificadoElectoral=true;				
				}
				
				$cadena_sql=$this->sql->cadena_sql($configuracion, $this->acceso_db,"porcentajeExencion",$this->solicitud["id_exencion"]);	
				$registroPorcentaje=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql ,"busqueda");
				if(is_array($registroPorcentaje))
				{
					$porcentajeExencion+=$registroPorcentaje[0][0];
					$cadena_sql=$this->sql->cadena_sql($configuracion, $this->acceso_db,"insertarExencion", $this->solicitud);	
					$resultado.=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql ,"");
				}
				unset($registroPorcentaje);
			}			
		}
		
		//Si no se tiene el certificado electoral explicito y el porcentaje de las exenciones es menor al 90% se verifica que tampoco 
		//exista en ACCERELECTORAL
		if(!isset($certificadoElectoral) && $porcentajeExencion<=90)
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion, $this->accesoOracle,"certificadoElectoral", $this->solicitud["estudiante"]);	
			
			$registroCertificado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql ,"busqueda");	
			if(is_array($registroCertificado))
			{
				$this->solicitud["id_exencion"]=1;
				$cadena_sql=$this->sql->cadena_sql($configuracion, $this->acceso_db,"insertarExencion", $this->solicitud);	
				$resultado.=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql ,"");
			}
		}
		
		return $resultado;
		
		
	
	}
	
	function guardarSolicitud($configuracion)
	{
		$cadena_sql=$this->sql->cadena_sql($configuracion, $this->acceso_db,"insertarSolicitud", $this->solicitud);	
		$resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql ,"");
		
		if($resultado==true)
		{
			
			return $this->acceso_db->ultimo_insertado();
		
		}
		else
		{
			return false;
		
		}
	}
	
	
	function guardarConcepto($configuracion, $concepto)
	{
		
		$resultado=true;
		
		//TO REVIEW
		//Se verifican los conceptos registrados en la plantillas. 
		
		//Este bloque es para procesar plantillas de matricula por tanto el concepto de matricula se guarda por defecto
		$this->solicitud["id_concepto"]=1;
		$cadena_sql=$this->sql->cadena_sql($configuracion, $this->acceso_db,"insertarConcepto", $this->solicitud);	
		$resultado.=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql ,"");
		
		//Guardar otros conceptos
		if(is_array($concepto)){
		foreach ($concepto as $clave => $valor)
		{
			
			switch($clave)
			{
				case 0:
					$this->solicitud["id_concepto"]=2;
					break;
				
				case 1:
					$this->solicitud["id_concepto"]=3;
					break;
					
				case 2:
					$this->solicitud["id_concepto"]=4;
					break;
				
				default:
					break;
			}
			
			$cadena_sql=$this->sql->cadena_sql($configuracion, $this->acceso_db,"insertarConcepto", $this->solicitud);	
			$resultado.=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql ,"");
		}
		
		if($resultado==true)
		{
			
			return true;
		
		}
		else
		{
			return false;
		
		}
		}
	
	
	}
	
	
	
	function conceptoPlantilla($registro, $tipo)
	{
		switch($tipo)
		{
			case 1:
			$columnaInicial=2;
			break;
			
			case 2:
			case 3:
			$columnaInicial=4;
			break;
		
		}
		
		//Plantillas para solo tres conceptos
		for($j=$columnaInicial;$j<=($columnaInicial+2);$j++)
		{
			if(isset($registro[$j]))
			{
				$concepto[($j-$columnaInicial)]=$registro[$j];
			}
		
		}
		if(isset($concepto))
		{
			return $concepto;
		}
		else
		{
			return false;
		}
	}
	
	function exencionPlantilla($registro, $tipo)
	{
		/*foreach ($registro as $key => $value) 
		{
		
			echo $key."=>".$value."<br>";
		
		}
		*/
		switch($tipo)
		{
			case 1:
			$columnaInicial=5;
			$columnaFinal=13;
			break;
			
			case 2:
			case 3:
			$columnaInicial=7;
			$columnaFinal=16;
			break;
				
		
		}
		
		for($j=$columnaInicial;$j<=$columnaFinal;$j++)
		{
			if(isset($registro[$j]))
			{
				$exencion[$j]=$registro[$j];
				
			}
		
		}
		if(isset($exencion))
		{
			return $exencion;	
		}
		else
		{
			return false;
		}
	}
	
	function cuotaPlantilla($registro, $tipo)
	{
		switch($tipo)
		{
			case 1:
			//TO DO
			//Casos en los que toque generar solo la segunda cuota
				if(isset($registro[2]))
				{
					
					$cuota=$registro[2];
				}
				else
				{
					$cuota="0";
				}
			
			break;
			
			case 2:
			case 3:
				if(isset($registro[3]))
				{
					$cuota=$registro[3];
				}
				else
				{
					$cuota="1";
				}
			
			break;
			
				
		
		}
		
		return $cuota;
	}
	
	function guardarCuota($configuracion)
	{
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosSolicitud.class.php");
	$datoSolicitud=new datosSolicitud();
		
		switch($this->solicitud["plantilla"])
		{
			//Para pregrado se rescatan los datos de la tabla fechaPago en MySQL.
			//Esto se hace asi para permitir que admisiones modifique las fechas de ORACLE
			case 1:
				$laFechaPago=$datoSolicitud->rescatarDatoSolicitud($configuracion, "fechaPago", $this->solicitud["cuotaGuardar"], $this->acceso_db);
				$fecha=explode("/",$laFechaPago[0][1]);				
				$dia=$fecha[0];
				$mes=$fecha[1];
				$anno=$fecha[2];
				$this->solicitud["fechaOrdinaria"]=strtotime($mes."/".$dia."/".$anno);
				$fecha=explode("/",$laFechaPago[0][2]);				
				$dia=$fecha[0];
				$mes=$fecha[1];
				$anno=$fecha[2];
				$this->solicitud["fechaExtraordinaria"]=strtotime($mes."/".$dia."/".$anno);
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"insertarCuota", $this->solicitud);
				$resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");	
				break;
			
			//Para postgrado se guardan las fechas que vienen en la plantilla
			case 2:
			case 3:
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"insertarCuota", $this->solicitud);
				$resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");	
				break;
		}
		
		return $resultado;
		
	
	}
	
	
	
	
	
	
	function guardarCuotas($configuracion, $registro)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/calendario.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosSolicitud.class.php");
		
		$miCalendario=new calendario();
		$datoSolicitud=new datosSolicitud();
		
		/*
		if($miCalendario->buscar_festivo(3,11,2008,$configuracion))
		{
			echo "es festivo";
		
		}
		else
		{
			echo "no es festivo";
		}
		exit;
		*/
		
		
		
		$resultado=true;
		switch($this->solicitud["plantilla"])
		{
			case 1:
				//Verificar si el estudiante ha solicitado pago diferido
				/*foreach ($solicitud as $key => $value) 
				{
				
					echo $key."=>".$value."<br>";
				
				}
				
				*/
				if($this->solicitud["diferido"]=='S')
				{
					$this->solicitud["porcentajeCuota"]=50;
					//Puede ser la 1, la 2 o las dos cuotas
					switch($this->solicitud["cuota"])
					{
						case 0:
							for($i=1;$i<3;$i++)
							{
								$this->solicitud["cuotaGuardar"]=$i;
								$this->guardarCuota($configuracion);	
							
							}
							break;
							
							
						case 1:
						//La primera cuota
							$this->solicitud["cuotaGuardar"]=1;
							$this->guardarCuota($configuracion);
							break;
						
						
						case 2:
						//La segunda cuota
							$this->solicitud["cuotaGuardar"]=2;
							$this->guardarCuota($configuracion);
							break;
						
					}
					
				}
				else
				{
					$this->solicitud["porcentajeCuota"]=100;
					$this->solicitud["cuotaGuardar"]=1;
					$this->guardarCuota($configuracion, $this->solicitud);
					
				}
				
				
				break;
			
			case 2:
			case 3:
				
				for($i=1;$i<($this->solicitud["cuota"]+1);$i++)
				{
					$this->solicitud["cuotaGuardar"]=$i;
					
					//En las plantillas de postgrado la fecha de la 
					
					$dia=$registro[17+(3*($i-1))];
					$mes=$registro[18+(3*($i-1))];
					$anno=$registro[19+(3*($i-1))];
					
					$this->solicitud["fechaOrdinaria"]=strtotime($mes."/".$dia."/".$anno);
					
					//8 dias despues del pago ordinario
					$suma=8;
					$esta_fecha=strtotime($mes."/".$dia."/".$anno)+($suma*24*60*60);
					$extradia=date("d",$esta_fecha);
					$extrames=date("n",$esta_fecha);
					$extraanno=date("Y",$esta_fecha);
					
					//Verifica que el dia no sea festivo
					while($miCalendario->buscar_festivo($extradia,$extrames,$extraanno,$configuracion))
					{
						$esta_fecha=strtotime($extrames."/".$extradia."/".$extraanno)+(24*60*60);
						$extradia=date("d",$esta_fecha);
						$extrames=date("n",$esta_fecha);
						$extraanno=date("Y",$esta_fecha);
					}
					
					$this->solicitud["fechaExtraordinaria"]=strtotime($extrames."/".$extradia."/".$extraanno);
					
					//Los porcentajes se encuentra en la columna 26,27,28
					if(isset($registro[25+$i]) && is_numeric($registro[25+$i]) && ($registro[25+$i]<100))
					{
						$this->solicitud["porcentajeCuota"]=$registro[25+$i];
					}
					else
					{
						//Valores por defecto
						switch($this->solicitud["cuota"])
						{
							case 1:
								$this->solicitud["porcentajeCuota"]=100;
								break;
							case 2:
								if($i==1)
								{
									$this->solicitud["porcentajeCuota"]=60;
								}
								elseif($i==2)
								{
									$this->solicitud["porcentajeCuota"]=40;
								}
								break;
							case 3:
								if($i==1)
								{
									$this->solicitud["porcentajeCuota"]=40;
								}
								elseif( $i==2 || $i==3 )
								{
									$this->solicitud["porcentajeCuota"]=30;
								}
								break;
						}	
					
					}
					
					$this->guardarCuota($configuracion, $this->solicitud);
				}
				
		}
		
		
		if($resultado==true)
		{
			
			return true;
		
		}
		else
		{
			return false;
		
		}
	
	
	}
	
	
	
	function carrerasCoordinador($configuracion, $variable)
	{
		$cadena_sql=$this->sql->cadena_sql($configuracion, $this->accesoOracle,"carrerasCoordinador", $variable);	
		$registroCarrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql ,"busqueda");
		
		if(is_array($registroCarrera))
		{
			return $registroCarrera;
		}
		else
		{
			return false;
			//return false;
		}
	
	}
	
	
	
	function mensajeErrorCarga($configuracion, $tipo, $valor="")
	{
		$encabezado="";
		$cadena="";
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
		switch($tipo)
		{
			case "inconsistencia":
				$encabezado="EL ARCHIVO NO SE HA PODIDO CARGAR AL SISTEMA";
				$cadena.="<p>Debido a inconsistencias en su formato o problemas de conexi&oacute;n no se ha podido procesar autom&aacute;ticamente.</p>";
				$cadena.="<hr class='hr_subtitulo'>";
				$cadena.="<p>Por favor contacte a la Oficina Asesora de Sistemas indicando este n&uacute;mero de solicitud:<span class='texto_negrita'> ".substr($valor[1],0,(strlen($valor[1])-4))."</span></p>";
				break;
			
			case "noArchivo":
				$encabezado="ERROR EN LA CARGA DEL ARCHIVO";
				$cadena="Por favor contacte a la Oficina Asesora de Sistemas para soporte.";
				break;
				
			case "sinLeer":
				$encabezado="ERROR EN LA LECTURA DEL ARCHIVO";
				$cadena="<p>El archivo <span class='texto_negrita'>".$valor[0]."</span> SE HA GUARDADO EXITOSAMENTE EN NUESTRO SERVIDOR.</p";
				$cadena.="<p> Sin embargo, debido a inconsistencias en su formato no se ha podido leer los datos.</p>";
				$cadena.="<hr class='hr_subtitulo'>";
				$cadena.="<p>Por favor contacte a la Oficina Asesora de Sistemas indicando este n&uacute;mero de solicitud:<span class='texto_negrita'> ".substr($valor[1],0,(strlen($valor[1])-4))."</span></p>";
				
				break;
				
			case "parcial":
				$encabezado="ARCHIVO PROCESADO PARCIALMENTE";
				$cadena="<p>El archivo <span class='texto_negrita'>".$valor[0]."</span> SE HA GUARDADO EXITOSAMENTE EN NUESTRO SERVIDOR.</p>";
				$cadena.="<p> Sin embargo no se ha podido procesar totalmente.</p>";
				$cadena.="<hr class='hr_subtitulo'>";
				$cadena.="<p>Por favor contacte a la Oficina Asesora de Sistemas indicando este n&uacute;mero de solicitud:<span class='texto_negrita'> ".substr($valor[1],0,(strlen($valor[1])-4))."</span></p>";
				break;
				
			default:
				$encabezado="ERROR";
				$cadena.="<p>".$valor." </p>";
				
			
		}
		
		echo "<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/basico/estilo.php' />\n";
			
		alerta::sin_registro($configuracion,$cadena, $encabezado);
	
	
	}
	
	
	

		
		
}

?>

