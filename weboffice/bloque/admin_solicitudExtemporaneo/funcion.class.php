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

class funciones_adminSolicitud extends funcionGeneral
{
//resultado
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->sql=$sql;
		$this->formulario="admin_generados";
		$this->verificar="control_vacio(".$this->formulario.",'estudiante')";
		$this->acceso_db=$this->conectarDB($configuracion,"");
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
                $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
                
		//Conexion ORACLE
                if($this->nivel==4){
                    $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
                }elseif($this->nivel==110){
                    $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
                }elseif($this->nivel==114){
                    $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
                }else{
                    echo "NO TIENE PERMISOS PARA ESTE MODULO";
                }
                $this->validacion=new validarUsu();

	}
	
	
	function nuevoRegistro($configuracion,$acceso_db)
	{}
	
   	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
   	{}
   	
    	function corregirRegistro()
    	{}
	

		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
	function consultarEstudiante($configuracion)
	{
		//Conexion ORACLE

					
		$accesoOracle=$this->accesoOracle;
		$conexion=$accesoOracle;
		
		$annoActual=$this->datosGenerales($configuracion,$conexion, "anno") ;
		$periodoActual=$this->datosGenerales($configuracion,$conexion, "per") ;
		
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",'');
			$verifica=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
						
			$anio=$verifica[0][0];
			$per=$verifica[0][1];
			
			$html='<form enctype="multipart/form-data" method="POST" action="index.php" name="consulta_estudiante">';
			$html.='<table width="50%" align="center"  class="bloquelateral">';
			$html.='	    <tr class="texto_subtitulo_gris"><td colspan="2">Ingrese el c&oacute;digo del estudiante</td><tr>';
			$html.='	    <tr >';
			$html.='			<td width="10%">';
			$html.='				<span class="bloquelateralcuerpo">Código:</span>';
			$html.='			</td>';			
			$html.='			<td width="90%">';
			$html.='				<input style="width:100%;" type="text" size="10" name="estudiante"/>';
			$html.='			</td>';
			$html.='		</tr>';
			$html.='	    <tr >';
			$html.='			<td width="10%">';
			$html.='				<span class="bloquelateralcuerpo">A&ntilde;o:</span>';
			$html.='			</td>';				
			$html.='			<td width="90%">';
			$html.='				<select style="width:100%;"  width="90%" name="anio" id="anio">';
			
									for($i=$anio;$i>=1990;$i--){
			$html.='						<option value="'.$i.'">'.$i.'</option>';			
									}
			$html.='				</select>';			

			$html.='			</td>';
			$html.='		</tr>';
			$html.='	    <tr >';
			$html.='			<td width="10%">';
			$html.='				<span class="bloquelateralcuerpo">Periodo:</span>';
			$html.='			</td>';				
			$html.='			<td width="90%">';
			$html.='				<select style="width:100%;"  width="90%" name="periodo" id="periodo">';
			$html.='						<option value="1">1</option>';			
			$html.='						<option value="3">3</option>';	
			$html.='				</select>';
			$html.='			</td>';
			$html.='		</tr>';			
			$html.='	    <tr >';
			$html.='			<td colspan="2" width="100%">';
			$html.='				<center><input type="hidden" value="admin_solicitudExtemporaneo" name="action"/>';
			$html.='				<input type="submit" onclick="document.forms[\'consulta_estudiante\'].submit()" tabindex="2" name="consultar" value="Consultar"/><br/></center>';		
			$html.='			</td>';
			$html.='		</tr>';						
			$html.='</table>';	
			$html.='<br><a href="documento/EXTEMPORANEOS.pdf"> <b>Ver ayuda</b></a>';								
			$html.='</form>';			
				
			echo $html;	
		
	}


	function recibosGeneradosest($configuracion,$codigo,$anno_per)
	{

		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		$botonGenerar='';
		$script='';

		$select=new html();
		
		$busqueda=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"periodosRecibos","");	
		
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
			
		$annoActual=$this->datosGenerales($configuracion,$this->accesoOracle, "anno") ;
		$periodoActual=$this->datosGenerales($configuracion,$this->accesoOracle, "per") ;
		
		$variable[0]=$codigo;
		$variable[1]=$annoActual;
		$variable[2]=$periodoActual;
				
		if($anno_per!=""){
			$variable[1]=substr($anno_per,0,4);
			$variable[2]=substr($anno_per,4,1);
		}

		$valor[0]=$codigo;
		$valor[1]=$this->usuario;

                if($this->nivel==4 || $this->nivel==28 ){
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaEstudianteCoordinador",$valor);
                    $verifica=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
                }elseif($this->nivel==110 ||$this->nivel==114){
                    $verifica = $this->validacion->validarProyectoAsistente($valor[0], $valor[1],$this->accesoOracle,$configuracion,  $this->acceso_db,  $this->nivel);
                }
		if(is_array($verifica)|| $verifica=='ok'){
		
                        $html='';
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"recibosGeneradosEstudiante",$variable);
		  	$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
		  	
                                $html.='<div style="position: absolute; display: none; left: 158px; top: 32px;" class="calendar"><table style="visibility: visible;" cellpadding="0" cellspacing="0"><thead><tr><td class="button" colspan="1"><div unselectable="on">?</div></td><td style="cursor: move;" class="title" colspan="6">Septiembre, 2009</td><td class="button" colspan="1"><div unselectable="on">×</div></td></tr><tr class="headrow"><td class="button nav" colspan="1"><div unselectable="on">«</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8249;</div></td><td class="button" colspan="4"><div unselectable="on">Hoy</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8250;</div></td><td class="button nav" colspan="1"><div unselectable="on">»</div></td></tr><tr class="daynames"><td class="name wn">sem</td><td class="day name">Lun</td><td class="day name">Mar</td><td class="day name">Mie</td><td class="day name">Jue</td><td class="day name">Vie</td><td class="name day weekend">S&aacute;b</td><td class="name day weekend">Dom</td></tr></thead><tbody><tr class="daysrow"><td class="day wn">36</td><td class="emptycell">&nbsp;</td><td class="day">1</td><td class="day">2</td><td class="day">3</td><td class="day">4</td><td class="day weekend">5</td><td class="day weekend">6</td></tr><tr class="daysrow"><td class="day wn">37</td><td class="day">7</td><td class="day">8</td><td class="day">9</td><td class="day">10</td><td class="day">11</td><td class="day weekend">12</td><td class="day weekend">13</td></tr><tr class="daysrow"><td class="day wn">38</td><td class="day">14</td><td class="day">15</td><td class="day">16</td><td class="day">17</td><td class="day">18</td><td class="day weekend">19</td><td class="day weekend">20</td></tr><tr class="daysrow"><td class="day wn">39</td><td class="day">21</td><td class="day">22</td><td class="day">23</td><td class="day">24</td><td class="day">25</td><td class="day selected today weekend">26</td><td class="day weekend">27</td></tr><tr class="daysrow"><td class="day wn">40</td><td class="day">28</td><td class="day">29</td><td class="day">30</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr><tr class="emptyrow"><td class="day wn">41</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr></tbody><tfoot><tr class="footrow"><td style="cursor: move;" class="ttip" colspan="8">Seleccionar fecha</td></tr></tfoot></table><div style="display: none;" class="combo"><div class="label">Ene</div><div class="label">Feb</div><div class="label">Mar</div><div class="label">Abr</div><div class="label">May</div><div class="label">Jun</div><div class="label">Jul</div><div class="label">Ago</div><div class="label">Sep</div><div class="label">Oct</div><div class="label">Nov</div><div class="label">Dic</div></div><div style="display: none;" class="combo"><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div></div></div>';
	
				if(!is_array($registro))
				{
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
					$cadena="El estudiante ".$codigo." no tiene recibos generados para este periodo.<br>";
					$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";	
					alerta::sin_registro($configuracion,$cadena);
			
					//$resultado=$this->ejecutarSQL($configuracion,$this->accesoOracle,"UPDATE acestmat SET ema_estado='I' where ema_est_cod=$codigo AND ema_ano=2009 AND ema_secuencia in (81931)", "");			
			
					//LO UTILIZO CUANDO NO EXISTE UN REGISTRO PREVIO EN ACESTMAT///////
					//La idea es realizar un formulario q permita la insercion por ahora lo estoy haciendo manual/////
			
					/*$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"secuencia");
					$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
					$secuencia=$resultado[0][0];
					$valor=298862;

			
							
					$resultado=$this->ejecutarSQL($configuracion,$this->accesoOracle,"INSERT INTO acestmat VALUES($codigo,(select est_cra_cod from acest where est_cod=$codigo),$valor,($valor*1),2009,3,SYSDATE,'A',$secuencia,1,to_date('17/11/09','dd/mm/yy'),to_date('17/11/09','dd/mm/yy'),2,'N',2008,1)", "");
					$resultado=$this->ejecutarSQL($configuracion,$this->accesoOracle,"INSERT INTO acrefest VALUES(2009,$secuencia,23,1,$valor)", "");	*/
					//$resultado=$this->ejecutarSQL($configuracion,$this->accesoOracle,"INSERT INTO acrefest VALUES(2009,$secuencia,23,2,5300)", "");
				}
				else{	
		
					$html.='<form enctype="multipart/form-data" method="POST" action="index.php" name="consulta_recibos">';
					$html.='<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >';
					$html.='<tr class="texto_subtitulo">';
					$html.='<td>';
					$html.='Solicitar Recibo(s) Extemporaneo(s):<br><br>';
					$html.='C&oacute;digo:'.$registro[0][1].'<br>';
					$html.='Nombre:'.$registro[0][15].'<br>';
					$html.='<hr class="hr_subtitulo"><br>';
					$html.='</td>';
					$html.='<td rospan="2">';
					$html.='Periodo<br>'.$variable[1]."-".$variable[2];
					$html.='</td>';								
					$html.='</tr>';
					$html.='</table>';
					
					$html.='<a href="documento/EXTEMPORANEOS.pdf"> <b>Ver ayuda</b></a>';
			
			
			
					$html.='<table class="formulario">';
			
					$html.='<tr class="texto_negrita">';
					$html.='<td rowspan="2">';
					$html.=' ';
					$html.='</td>';				
					$html.='<td rowspan="2">';
					$html.='N&uacute;m.';
					$html.='</td>';
					$html.='<td rowspan="2">';
					$html.='C';
					$html.='</td>';				
					$html.='<td colspan="2">';
					$html.='Ordinario';
					$html.='</td>';
					$html.='<td colspan="2">';
					$html.='Extraordinario';
					$html.='</td>';
					$html.='<td colspan="2">';
					$html.='Estado';
					$html.='</td>';	
					$html.='<td rowspan="2">';
					$html.='Intereses';
					$html.='</td>';	
					$html.='<td rowspan="2">';
					$html.='Nueva<br>Fecha<br>Pago<br><br>(dd/mm/aaaa)';
					$html.='</td>';	
					$html.='</tr>';	
			
					$html.='<tr class="texto_negrita">';
					$html.='<td>';
					$html.='Fecha';
					$html.='</td>';
					$html.='<td>';
					$html.='Valor';
					$html.='</td>';
					$html.='<td>';
					$html.='Fecha';
					$html.='</td>';	
					$html.='<td>';
					$html.='Valor';
					$html.='</td>';	
					$html.='<td>';
					$html.='Pago';
					$html.='</td>';	
					$html.='<td>';
					$html.='Registro';
					$html.='</td>';	
					$html.='</tr>';	
			
					$i=0;
					while(isset($registro[$i][0])){

						unset($valor);
						
						$valor[0]=$codigo;
						$valor[1]=$variable[1];
						$valor[2]=$variable[2];
						$valor[3]=$registro[$i][7];
						
	
						$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaPosgrado",$codigo);
					  	$aprobado=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");	
                                                $posgrado =$aprobado;
						if(!is_array($aprobado)){
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaAprobacion",$valor);
						  	$aprobado=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
						}						  	
						  	
					  	
						if($registro[$i][9]=='Activo'){
							$html.='<tr class="texto_subtitulo_verde">';
						}
						if($registro[$i][9]=='Inactivo'){
							$html.='<tr class="texto_subtitulo_rojo">';
						}
							
						$html.='<td>';
		  				if(!is_array($aprobado)){
		  					$html.='';
		  				}else{						
							$html.='<input type="checkbox" size="10" name="secuencia'.$registro[$i][0].'" value="'.$registro[$i][0].'"/>';
						}
						$html.='</td>';						
						$html.='<td>';
						$html.=$registro[$i][0];
						$html.='</td>';	
						$html.='<td>';
						$html.=$registro[$i][7];
						$html.='</td>';										
						$html.='<td>';
						$html.=$registro[$i][12];
						$html.='</td>';
						$html.='<td>';
						$html.=$registro[$i][3];
						$html.='</td>';
						$html.='<td>';
						$html.=$registro[$i][13];
						$html.='</td>';	
						$html.='<td>';
						$html.=$registro[$i][4];
						$html.='</td>';	
						$html.='<td>';
						$html.=$registro[$i][11];
						$html.='</td>';	
						$html.='<td>';
						$html.=$registro[$i][9];
						$html.='</td>';
						

		  				if(!is_array($aprobado)){
		  					$html.='<td colspan="2">';
							$html.='<br><center>No registra aprobaci&oacute;n de Consejo de Facultad</center><br>';						
							$html.='</td>';	  				
		  				
		  				}else{
		  				
			  				$html.='<td>';
							$html.='Desde:<br>';
							if(is_array($posgrado)){
                                                            $html.=''.$registro[$i][12].'';
                                                            $html.='<input type="hidden" size="8" value="'.$registro[$i][12].'" name="intIni@'.$registro[$i][0].'" id="intIni'.$registro[$i][0].'" readonly/>';
                                                            
                                                        }else{
                                                            $html.='<input type="text" size="8" value="'.$registro[$i][12].'" name="intIni@'.$registro[$i][0].'" id="intIni'.$registro[$i][0].'"/>';
                                                            
                                                        }
							$html.='<br>Hasta:<br>';					
							if(is_array($posgrado)){
                                                            $html.=''.$registro[$i][18].'';					
                                                            $html.='<input type="hidden" size="8" value="'.$registro[$i][18].'"  name="intFin@'.$registro[$i][0].'" id="intFin'.$registro[$i][0].'" readonly/>';					
                                                        }else{
                                                            $html.='<input type="text" size="8" value="'.$registro[$i][18].'"  name="intFin@'.$registro[$i][0].'" id="intFin'.$registro[$i][0].'"/>';					
                                                        }
							$html.='</td>';	
							$html.='<td>';
                                                        if(is_array($posgrado)){
                                                            $fecha_pago=$this->obtenerFechaPago($configuracion);
                                                            $html.=$fecha_pago;
                                                            $html.='<input type="hidden" size="8" value="'.$fecha_pago.'" name="fec@'.$registro[$i][0].'" id="fec'.$registro[$i][0].'" readonly/>';
                                                        }else{
                                                            $html.='<input type="text" size="8" name="fec@'.$registro[$i][0].'" id="fec'.$registro[$i][0].'"/>';
                                                        }
							$html.='</td>';	
						
							$script.='<script type="text/javascript">';
							$script.='	Calendar.setup({';
							$script.='		inputField:"fec'.$registro[$i][0].'",';
							$script.='		ifFormat:"%d/%m/%Y",';
							$script.='		button:"fec'.$registro[$i][0].'"';
							$script.='	});';
							$script.='	Calendar.setup({';
							$script.='		inputField:"intIni'.$registro[$i][0].'",';
							$script.='		ifFormat:"%d/%m/%Y",';
							$script.='		button:"intIni'.$registro[$i][0].'"';
							$script.='	});';	
							$script.='	Calendar.setup({';
							$script.='		inputField:"intFin'.$registro[$i][0].'",';
							$script.='		ifFormat:"%d/%m/%Y",';
							$script.='		button:"intFin'.$registro[$i][0].'"';
							$script.='	});';										
							$script.='</script>';
							
							$botonGenerar='<center><input type="submit" onclick="document.forms[\'consulta_recibos\'].submit()" tabindex="2" name="consultar" value="Solicitar Recibos"/></center><br/>';
						}
						
						
						
												
						$html.='</tr>';	


				
					$i++;	
					}	
							
						$html.='</table>';

					$html.='<input type="hidden" value="'.$codigo.'" name="estudiante"/>';
					$html.='<input type="hidden" value="'.$variable[1].$variable[2].'" name="anno_periodo"/>';
					$html.='<input type="hidden" value="confirmar" name="confirmar"/>';			
					$html.='<input type="hidden" value="admin_solicitudExtemporaneo" name="action"/>';
					$html.='<br>';			
					$html.=$botonGenerar;		
									
					$html.='</form>';
			
					echo $html;
					echo $script;

				}

		}	
		else{
                        if(isset($registro))
                        {    
                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
                            $cadena="El estudiante ".$registro[0][0]." no pertenece a su Coordinaci&oacute;n.<br>";

                            $cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
                            alerta::sin_registro($configuracion,$cadena);
                        }
                        else
                        {
                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
                            $cadena="El estudiante consultado no pertenece a su Coordinaci&oacute;n.<br>";

                            $cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
                            alerta::sin_registro($configuracion,$cadena);
                        }    
		}
	}	
	
	
	function confirmarPeriodos($configuracion,$codigo,$anno_periodo,$secuencias)
	{

                $annoPago=substr($anno_periodo,0,4);
                $perPago=substr($anno_periodo,4,1);

		$i=0;

                $registro='<form enctype="multipart/form-data" method="POST" action="index.php" name="confirmar_estudiante">';

		while(isset($secuencias[$i])){
                    	$valor=explode("@",$secuencias[$i]);
			$secuencia=$valor[0];
                        if($valor[1] && $valor[2]){
                            $f1=  explode("/", $valor[1]);
                            $fechaUno = $f1[2]."/".$f1[1]."/".$f1[0];
                            $f2=  explode("/", $valor[2]);
                            $fechaDos = $f2[2]."/".$f2[1]."/".$f2[0];
                            $f1=str_replace('/', '', $fechaUno);
                            $f2=str_replace('/', '',$fechaDos);
                            if($fechaUno<$fechaDos){
                                $diasTotalMora=$this->calcularDias($fechaUno,$fechaDos);
                            }else{
                                $diasTotalMora=0;
                            }
                        }else{
                            $diasTotalMora=0;
                        }
			$Fecha=$valor[3];
			$fechaInicio=$valor[1];
			$fechaFin=$valor[2];
			$registro.=$this->procesarRecibo($configuracion,$annoPago,$perPago,$secuencia,$diasTotalMora,$Fecha,$codigo,$fechaInicio,$fechaFin);

			$i++;	
		}
	
			$registro.='<input type="hidden" value="admin_solicitudExtemporaneo" name="action"/>';
			$registro.='<input type="hidden" value="admin_solicitudExtemporaneo" name="confirmar"/>';
			$registro.='<input type="hidden" value="'.$annoPago.$perPago.'" name="anno_periodo"/>';	
			$registro.='<input type="hidden" value="admin_solicitudExtemporaneo" name="generar"/>';	
			
			$registro.='<center><table>';
			$registro.='<tr>';								
			if($Fecha && $fechaInicio && $fechaFin){
                            $hoy = date('Ymd');
                            
                            $valores = explode('/',$Fecha);
                            $ano=$valores[2];
                            $mes=$valores[1];
                            $dia=$valores[0];
                            $frecibo=$ano.$mes.$dia;
                            if($frecibo>=$hoy){
                                if($f1<=$f2 && $f1<=$hoy && $f2<=$hoy){
                                    $registro.='<td><input type="submit" onclick="document.forms[\'confirmar_estudiante\'].submit()" tabindex="2" name="consultar" value="Generar"/><td/>';
                                }else{
                                    $registro.='<td>Fechas2 para el recibo no valida.<td/>';                               
                                }
                            }else{
                                $registro.='<td>Fecha1 para el recibo no valida.<td/>';                               
                            }
                        }else{
                            if(!$fechaInicio || !$fechaFin){
                                $registro.='<td>Fechas para calculo de intereses no valida.<td/>';                               
                            }else{
                                 $registro.='<td>Fecha para el recibo no valida.<td/>';
                            }
                        }
//			$html.='<td><input type="button" onclick="document.forms[\'generar_recibos\'].submit()" tabindex="2" name="consultar" value="Cancelar"/><td/>';					
			$registro.='</tr>';	
			$registro.='</table></center>';
			$registro.='</form>';	
			
	echo $registro;
				
	}
	


	function procesarRecibo($configuracion,$annoPago,$perPago,$secuencia,$diasTotalMora,$Fecha,$codigo,$fechaInicio,$fechaFin)
	{
		$variable[0]=$annoPago;
		$variable[1]=$perPago;
		$variable[2]=$secuencia;
		$variable[3]=$codigo;
                $obs='';
                $html='';
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verRecibo",$variable);
	  	$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle,$cadena_sql,"busqueda");
                
				$nuevoValor=$this->calcularInteres($configuracion,$registro[0][3],$fechaInicio,$fechaFin);
				///VALIDACION: SI EL VALOR CALCULADO ES MENOR A LA MATRICULA EXTRAORDIANRIA COBRAR LA EXTRAORDINARIA////////
				if($nuevoValor<$registro[0][4]){
					$obs="<br><span style='color:red'>Se generar&aacute; por el valor Extraordinario: ".$registro[0][4]."</span>";
					$obs.="<br><span style='color:red'>* Debido a que el valor calculado es menor al pago extraordinario<br> correspondiente a este recibo</span>";
				}
				
				$html.='<table class="formulario">';
				
				$html.='<tr class="texto_negrita">';
				$html.='<td colspan="2">';
				$html.='Ordinario';
				$html.='</td>';
				$html.='<td colspan="2">';
				$html.='Extraordinario';
				$html.='</td>';
				$html.='<td colspan="2" rowspan="3">';
				$html.='Valor Inicial: '.$registro[0][3];
				$html.='<br>N&uacute;m Dias de mora: '.$diasTotalMora;
				$html.='<br>Cuota: '.$registro[0][7];		
				$html.=$obs;									
				$html.='</td>';	
				$html.='</tr>';	
				
				$html.='<tr class="texto_negrita">';
				$html.='<td>';
				$html.='Fecha';
				$html.='</td>';
				$html.='<td>';
				$html.='Valor';
				$html.='</td>';
				$html.='<td>';
				$html.='Fecha';
				$html.='</td>';	
				$html.='<td>';
				$html.='Valor';
				$html.='</td>';	
				$html.='</tr>';	
				
		
				$html.='<tr>';
				$html.='<td>';
				$html.=$Fecha;
				$html.='</td>';
				$html.='<td>';
				$html.=$nuevoValor;
				$html.='</td>';
				$html.='<td>';
				$html.=$Fecha;
				$html.='</td>';	
				$html.='<td>';
				$html.=$nuevoValor;
				$html.='</td>';	
				$html.='</tr>';	

                                $html.='<tr class="texto_negrita">';
				$html.='<td colspan="6" align="center">';	
				$html.='<input type="checkbox" name="secuencia'.$secuencia.'" value="'.$secuencia.'@'.$diasTotalMora.'@'.$Fecha.'@'.$fechaInicio.'@'.$fechaFin.'"/> CONFIRMAR LA GENERACI&Oacute;N ';				
				$html.='</td>';	
				$html.='</tr>';	
											
				$html.='</table>';
				$html.='<input type="hidden" value="'.$registro[0][1].'" name="estudiante"/>';	

			
		return $html;
	}


	function calcularDias($fechaInicio,$fechaFin)
	{	//ingreso formato AAAA/MM/DD
		$A1=substr($fechaInicio,0,4)*1;
		$M1=substr($fechaInicio,5,2)*1;
		$D1=substr($fechaInicio,8,2)*1;
		
		$A2=substr($fechaFin,0,4)*1;
		$M2=substr($fechaFin,5,2)*1;
		$D2=substr($fechaFin,8,2)*1;
		if($M1==1||$M1==2){
			$A1=$A1*1-1;
			$M1=$M1*1+12;			
		}
		

		if($M2==1||$M2==2){
			$A2=$A2*1-1;
			$M2=$M2*1+12;			
		}
		
			
		$fechaJulianaInicio=(int)(365.25*($A1+4716))+(int)(30.6001*($M1+1))-(int)($A1/100)+(int)((int)($A1/100)/4)+$D1-1522.5;
		$fechaJulianaFin=(int)(365.25*($A2+4716))+(int)(30.6001*($M2+1))-(int)($A2/100)+(int)((int)($A2/100)/4)+$D2-1522.5;

		$numeroDias=$fechaJulianaFin-$fechaJulianaInicio+1;
		
		return	$numeroDias;
									
	}

	function calcularInteres($configuracion,$valor,$fechaInicio,$fechaFin)
	{	
                /***********************************************
                 ****      FORMULA INTERES POR MORA (IM)    ****
                 ****                                       ****
                 ****           IM = k* (TU/365) * N        ****
                 **** Donde:                                ****
                 **** k= Valor de la deuda                  ****
                 **** TU= Tasa de Usura                     ****
                 **** N= Número de días en mora             ****
		/***********************************************/
                $valorIntereses=0;
                $valorInteresesMes=0;
                //Aplicamos la formula con el porcentaje mes a mes
                $f1=  explode('/', $fechaInicio);
                $f2=  explode('/', $fechaFin);
                $fechaInicio=$f1[2].'/'.$f1[1].'/'.$f1[0];
                $fechaFin=$f2[2].'/'.$f2[1].'/'.$f2[0];
                if($fechaInicio && $fechaFin && $fechaInicio!=$fechaFin){
                        $anioInt=$f1[2];
                        $mesInt=$f1[1];
                        $fIniInteres = $fechaInicio;
                        $diasMes=date("t", strtotime("$fechaInicio"));
                        $fFinInteres = $anioInt."/".$mesInt."/".$diasMes;
                        while ($fFinInteres<=$fechaFin ) {
                            $numDiasMoraMes= $this->calcularDias($fIniInteres,$fFinInteres);
                            $cadena_sql="SELECT `tus_porcentaje` FROM `backoffice_tasasUsura` WHERE `tus_anio`=".$anioInt." AND tus_mes=".$mesInt;
                            $interes=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql,"busqueda");
                            $tasaInteres=$interes[0][0];
                            $valorInteresesMes=(int)$valor * ($tasaInteres/365) * (int)$numDiasMoraMes;
                            
                            $valorIntereses = $valorIntereses+(int)$valorInteresesMes;
                            $resultado = $this->incrementarMes($mesInt,$anioInt);
                            $mesInt = $resultado[0];
                            $anioInt = $resultado[1];
                            $fIniInteres = $anioInt."/".$mesInt."/01";
                            if($anioInt==$f2[2] && $mesInt==$f2[1]){
                                $diasMes=$f2[0];
                            }else{
                                $diasMes=date("t", strtotime("$fIniInteres"));
                            }
                            $fFinInteres = $anioInt."/".$mesInt."/".$diasMes;
                            
                        }
                }
		$nuevoValor=round((int)$valor+$valorIntereses);
		return	$nuevoValor;
									
	}

        function incrementarMes($mesInt,$anioInt){
                $mesInt=$mesInt+1;
                if($mesInt>12){
                    $mesInt='01';
                    $anioInt=$anioInt+1;
                }elseif($mesInt<10 && strlen($mesInt)==1){
                    $mesInt='0'.$mesInt;
                }
                $resultado[0]=$mesInt;
                $resultado[1]=$anioInt;
                return $resultado;
        }
                
        function insertarRegistro($configuracion,$annoPago,$perPago,$secuencia,$diasTotalMora,$Fecha,$estudiante,$fechaInicio,$fechaFin)
	{	
			//////////////////////////////////CODIGO/////////////////////////////
			$parametro[0]=$estudiante;
	
			///////////////////////////////////CARRERA/////////////////////////////
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiante",$parametro[0]);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	
			$parametro[1]=$resultado[0][3];	
			
			///////////////////////////////////PERIODO ACTUAL/////////////////////////////
			$parametro[2]=$this->datosGenerales($configuracion,$this->accesoOracle, "anno") ;
			$parametro[3]=$this->datosGenerales($configuracion,$this->accesoOracle, "per") ;

			///////////////////////////////////VALOR SEGURO/////////////////////////////
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"valorSeguro");
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	
			$parametro[4]=$resultado[0][0];	
			
			///////////////////////////////////FECHAS/////////////////////////////			
		
			$parametro[5]=$Fecha;	
			
			///////////////////////////////////SECUENCIA/////////////////////////////				
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"secuencia");
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	
		
			$parametro[6]=$resultado[0][0];	
			
			//////año pago/////////////
			$parametro[7]=$annoPago;
			
			//////periodo pago//////////
			$parametro[8]=$perPago;
			
			$parametro[9]=$this->usuario;	
			
			//Valor Extemporaneo

			$variable[0]=$annoPago;
			$variable[1]=$perPago;
			$variable[2]=$secuencia;
			$variable[3]=$estudiante;
		
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verRecibo",$variable);
		  	$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle,$cadena_sql,"busqueda");
                        
			$parametro[11]=$this->calcularInteres($configuracion,$registro[0][3],$fechaInicio,$fechaFin);
			
			///VALIDACION: SI EL VALOR CALCULADO ES MENOR A LA MATRICULA EXTRAORDIANRIA COBRAR LA EXTRAORDINARIA////////
				if($parametro[11]<$registro[0][4]){
					$parametro[11]=$registro[0][4];
				}

				/////CUOTA///////////////////////////////////
			$parametro[12]=$registro[0][7];	
					
			$parametro[10]="CUOTA ".$parametro[12]." - ".$parametro[7]."/".$parametro[8]." + INTERESES";
			
			//INSERTAR EN MYSQL////////////////////////
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"insertarSolicitud",$parametro);
			$resultado=$this->ejecutarSQL($configuracion,$this->acceso_db, $cadena_sql, "");	
			
			/*echo "Nota: Como estoy insertando desde pruebas el lregistro queda en pruebas por tanto adjunto SQL para ejecutar en produccion (MySQL)<br><br>";
			echo $cadena_sql;*/
			
			//////////INSERTAR EN ORACLE//////////////////
						
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarCuotaExtemporaneo",$parametro);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			
			//////////INACTIVAR EL ANTERIOR//////////////////
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"inactivaRecibo",$variable);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");						
			

			if($parametro[12]==1 && $annoPago==$parametro[2] &&  $perPago==$parametro[3]){
			
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConceptoSeguro",$parametro);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");	
			}	
				

			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConceptoMatricula",$parametro);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");		
                        return $resultado;
	}
	
	function generarRecibosExtemporaneos($configuracion,$variable)
	{
			
			$annoPago=substr($variable[1],0,4);
			$perPago=substr($variable[1],4,1);
		
			$secuencias=explode('#',$variable[0]);
			unset($secuencias[count($secuencias)-1]);
			
	
			$i=0;
			$totalGenerados=0;
                        if(is_array($secuencias) && isset($secuencias[$i])){
                                while(isset($secuencias[$i])){

                                        $registro=explode("@",$secuencias[$i]);
                                        $secuencia=$registro[0];
                                        $diasTotalMora=$registro[1];
                                        $Fecha=$registro[2];
                                        $fechaInicio=$registro[3];
                                        $fechaFin=$registro[4];
                                        $resultado=$this->insertarRegistro($configuracion,$annoPago,$perPago,$secuencia,$diasTotalMora,$Fecha,$variable[2],$fechaInicio,$fechaFin);
                                        $i++;	
                                        if($resultado){
                                            $totalGenerados++;
                                        }
                                }
                        }else{
                            $datos=array(0=>$_REQUEST['anno_periodo'],
                                1=>$_REQUEST['estudiante']);
                            $this->redireccionarInscripcion($configuracion,'noConfirmados',$datos);	

                        }
                        
                if($totalGenerados>=1){
                    $this->redireccionarInscripcion($configuracion,'exitoGenerados',$totalGenerados);	
                }else{
                    $datos=array(0=>$_REQUEST['anno_periodo'],
                                1=>$_REQUEST['estudiante']);
                    $this->redireccionarInscripcion($configuracion,'noGenerados',$datos);	

                }
				
				
	}			
		
	function redireccionarInscripcion($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		switch($opcion)
		{
			case "verPeriodos":
				$variable="pagina=admin_solicitud_Extemporaneo";
				$variable.="&estudiante=".$valor[1];
				$variable.="&anno_per=".$valor[0];
				$variable.="&opcion=verPeriodos";
				
			break;	
		
			case "confirmarPeriodos":
				$variable="pagina=admin_solicitud_Extemporaneo";
				$variable.="&estudiante=".$valor[2];
				$variable.="&confirmar=confirmar";				
				$variable.="&secuencias=".$valor[0];
				$variable.="&anno_periodo=".$valor[1];
				
			break;	
			case "exitoGenerados":
				$variable="pagina=admin_solicitud_Extemporaneo";
				$variable.="&opcion=exito";			
				$variable.="&totalGenerados=".$valor;			
			
			break;			
			case "noGenerados":
				$variable="pagina=admin_solicitud_Extemporaneo";
				$variable.="&estudiante=".$valor[1];
				$variable.="&anno_per=".$valor[0];
				$variable.="&opcion=noGenerados";
				
			break;			
			case "noConfirmados":
				$variable="pagina=admin_solicitud_Extemporaneo";
				$variable.="&estudiante=".$valor[1];
				$variable.="&anno_per=".$valor[0];
				$variable.="&opcion=noConfirmados";
				
			break;			
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);
		//echo $indice.$variable;
		
		//header("Location: ".$indice.$variable);
		
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();		
		
	}		
		
    function obtenerFechaPago($configuracion){
        $fecha = date('Y-m-d');
        $nuevafecha = strtotime ( '+6 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        $valida='-';
        while ($valida!='ok') {
            $nuevafecha = strtotime ( '+1 day' , strtotime ( $nuevafecha ) ) ;
            $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
            $valida = $this->validarFechasRecibo($nuevafecha,$configuracion);
        }
        $resultado=  explode('-', $nuevafecha);
        $nuevafecha=$resultado[2]."/".$resultado[1]."/".$resultado[0];
        return $nuevafecha;
    }
    
        /**
     * Función para validar fechas del recibo
     * @param type $fecha_inicio
     * @param type $fecha_fin
     * @return string
     */
    function validarFechasRecibo($fecha,$configuracion){
        $valida='';
        $hoy = date('Y-m-d');
        $fecha_hoy = str_replace('-', '', $hoy);
        $fecha_uno = str_replace('-', '', $fecha);
        $valida_cadena1=  $this->validarDatoFecha($fecha);
        $valores = explode('-',$fecha);
        $ano=$valores[0];
        $mes=$valores[1];
        $dia=$valores[2];
        $diaFecha = $this->diaSemana($ano,$mes,$dia);
        $festivo = $this->consultarFestivo($fecha,$configuracion);
        if($fecha_uno<=$fecha_hoy || $valida_cadena1==false || $diaFecha==0 || $diaFecha==6 || $festivo=='S'){
            $valida="Fechas no validas";

        }else{
            $valida='ok';
        }
        return $valida;
    }


     /**
     * Función para validar que el dato de la fecha tenga caracteres validos
     * @param type $cadena
     * @return boolean
     */
    function validarDatoFecha($cadena){
        $permitidos = ":-/1234567890 ";
        for ($i=0; $i<strlen($cadena); $i++){
        if (strpos($permitidos, substr($cadena,$i,1))===false){
        //no es válido;
        return false;
        }
        } 
        //si estoy aqui es que todos los caracteres son validos
        return true;
    }  

    function diaSemana($ano,$mes,$dia)
    {
        // 0->domingo     | 6->sabado
        $dia= date("w",mktime(0, 0, 0, $mes, $dia, $ano));
            return $dia;
    }
    
    function consultarFestivo($fecha,$configuracion) {
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"consultar_festivo",$fecha);
        $resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	
			
        if(is_array($resultado)){
            return $resultado[0][0];
        }else{
            return '';
        }
    }
    
}

?>