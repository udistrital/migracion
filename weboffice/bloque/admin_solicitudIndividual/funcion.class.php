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

		$this->annoActual=$this->datosGenerales($configuracion,$this->accesoOracle, "anno");
		$this->periodoActual=$this->datosGenerales($configuracion,$this->accesoOracle, "per");
	}
	
	
	function nuevoRegistro($configuracion,$acceso_db)
	{}
	
   	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
   	{}
   	
    	function corregirRegistro()
    	{}

        function mostrarRegistro($configuracion,$registro, $total, $opcion="",$variable)
    	{
	}
	
	//Valida que la generacion de recibos por reintegro s haga dentro de las fechas establecidas.
	function validaCalendario($configuracion)
	{
		//Valida las fechas del calendario
		
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$_REQUEST['usuario'];
		}
		$valor[4]=$usuario;
		
		$confec = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual";
		@$rows=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confec, "busqueda");
		$fechahoy =$rows[0][0];
						
		if($this->nivel==4){
                    $qryFechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "validaFechas",$valor);
                    @$calendario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryFechas, "busqueda");
                }elseif($this->nivel==110 || $this->nivel==114){
                    $cadenaP='';
                        $proyectos =$this->validacion->consultarProyectosAsistente($usuario,  $this->nivel,$this->accesoOracle,$configuracion,$this->acceso_db);
                        if(is_array($proyectos)){
                                foreach ($proyectos as $key => $proyecto) {
                                   $registro[$key][0]= $proyecto[0];
                                   $registro[$key][1]= $proyecto[4];
                                   if(!$cadenaP){
                                       $cadenaP = $proyecto[0];
                                   }else{
                                       $cadenaP .= ",".$proyecto[0];
                                   }
                                   $qryFechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "validaFechasAsistentes",$cadenaP);
                                   @$calendario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryFechas, "busqueda");
                               }
                        }else{
                            echo "<br><p align='center'>No tiene ningún proyecto curricular asociado.<br><br></p>";
                            exit;
                        }
                    }
		$FormFecIni = (isset($calendario[0][2])?$calendario[0][2]:'');
		$FormFecFin = (isset($calendario[0][3])?$calendario[0][3]:'');
			if( (isset($calendario[0][0])?$calendario[0][0]:'') == "" ||  (isset($calendario[0][1])?$calendario[0][1]:'') == "")
			{
				die('<br><br><p align="center"><b><font color="#0000FF" size="3">No se han programado fechas para la generaci&oacute;n de recibos de pago para estudiantes de REINGRESO .</font></p>');
				exit;
			}
			elseif($fechahoy <  $calendario[0][0] &&  $calendario[0][0] > '0')
			{
				
				echo '<table width="60%" height="40%" border="0" cellpadding="5" cellspacing="0" align="center">
					<tr>
						<td><fieldset style="padding:20;">
							<table width="80%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
								<tr>
									<td valign="top">
									<div><h3><center>Aviso</center></h3></div>
									<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
										<p align="justify">&nbsp;</p>
										<p align="center"><font color="red"><b>El proceso de generaci&oacute;n de recibos de pago para estudiantes de REINGRESO, ser&aacute; del: <br>'.$FormFecIni.' al '.$FormFecFin.'</b></font></p>
										<p align="justify">&nbsp;</p>
									</fieldset>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
				exit;
			}
			elseif($fechahoy >  $calendario[0][1] &&  $calendario[0][1] > '0')
			{
				echo '<table width="60%" height="40%" border="0" cellpadding="5" cellspacing="0" align="center">
					<tr>
						<td><fieldset style="padding:20;">
							<table width="80%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
								<tr>
									<td valign="top">
									<div><h3><center>Aviso</center></h3></div>
									<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
										<p align="justify">&nbsp;</p>
										<p align="center"><font color="red"><b>El proceso de generaci&oacute;n de recibos de pago para estudiantes de REINGRESO,  termin&oacute; el: '.$FormFecFin.'</b></font></p>
										<p align="justify">&nbsp;</p>
									</fieldset>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
				exit;
			}
			else
			{
				return $calendario;
			}
	}

		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
	function consultarEstudiante($configuracion)
	{
		//Conexion ORACLE
		/*$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle,"INSERT INTO acestmat VALUES(20061170010,(select est_cra_cod from acest where est_cod=20061170010),(461500*0.35*2),(461500*0.35*2),2009,3,SYSDATE,'I',seq_matricula.NEXTVAL,1,to_date('17/12/09','dd/mm/yy'),to_date('17/12/09','dd/mm/yy'),2,'N',2009,1)","busqueda");
		$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle,"INSERT INTO acestmat VALUES(20061170010,(select est_cra_cod from acest where est_cod=20061170010),(461500*0.35*2),(461500*0.35*2),2009,3,SYSDATE,'I',seq_matricula.NEXTVAL,1,to_date('17/12/09','dd/mm/yy'),to_date('17/12/09','dd/mm/yy'),2,'N',2008,3)","busqueda");
		$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle,"INSERT INTO acestmat VALUES(20061170010,(select est_cra_cod from acest where est_cod=20061170010),(433700*0.35*2),(433700*0.35*2),2009,3,SYSDATE,'I',seq_matricula.NEXTVAL,1,to_date('17/12/09','dd/mm/yy'),to_date('17/12/09','dd/mm/yy'),2,'N',2008,1)","busqueda");
		$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle,"INSERT INTO acestmat VALUES(20061170010,(select est_cra_cod from acest where est_cod=20061170010),(433700*0.35*2),(433700*0.35*2),2009,3,SYSDATE,'I',seq_matricula.NEXTVAL,1,to_date('17/12/09','dd/mm/yy'),to_date('17/12/09','dd/mm/yy'),2,'N',2007,3)","busqueda");						
*/
		$calendario=$this->validaCalendario($configuracion);
		 		
			$html='<form enctype="multipart/form-data" method="POST" action="index.php" name="consulta_estudiante">';
			$html.='<table align="center"  class="bloquelateral">';
			$html.='	<tbody>';
			$html.='	        <tr class="texto_subtitulo_gris"><td>Ingrese el c&oacute;digo del estudiante</td><tr>';
			$html.='	        <tr >';
			$html.='		<td width="90%">';
			$html.='		<span class="bloquelateralcuerpo">Código:</span>';
			$html.='		<input type="text" size="10" name="estudiante"/>';
			$html.='		<input type="hidden" value="admin_solicitudIndividual" name="action"/>';
			$html.='		<input type="submit" onclick="document.forms[\'consulta_estudiante\'].submit()" tabindex="2" name="consultar" value="Consultar"/><br/>';		
			$html.='		</td>';
			$html.='	</tr>';
			$html.='</table>';						
			$html.='</form>';			
			
			echo $html;	
			
				  	//$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"");
		
	}

	
	function consultarPeriodos($configuracion,$codigo)
	{
	
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiante",$codigo);
	  	$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
	
		$valor[0]=$codigo;
		$valor[1]=$this->usuario;  	

                if($this->nivel==4){
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaEstudianteCoordinador",$valor);
                    $verifica=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
                }elseif($this->nivel==110 || $this->nivel==114){
                    $valido=$this->validacion->validarProyectoAsistente($valor[0], $valor[1],$this->accesoOracle,$configuracion,  $this->acceso_db,$this->nivel);
                    if($valido=='ok'){
                        $verifica[0]=$codigo;
                    }
                }

		$html='';
		if(is_array($verifica)){

	
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaPosgrado",$codigo);
		  	$aprobado=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");	

				$variable[0]=$codigo;
				$variable[1]=$this->annoActual;
				$variable[2]=$this->periodoActual;

			if(!is_array($aprobado)){
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaExisRecibo",$variable);
			  	$existe=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
		
			}
												
			if(!is_array($aprobado)){
				$cadena_sql2=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaAprobacion",$variable);
			  	$aprobado=$this->ejecutarSQL($configuracion,$this->accesoOracle,$cadena_sql2,"busqueda");
			  	
			}
		
	
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			
			
			/*if(is_array($aprobado)){
			
				$cadena="No requiere aprobaci&oacute;n de Consejo de Facultad.<br>";
				alerta::sin_registro($configuracion,$cadena);			
			}*/
			//else{
			
				if(is_array($existe)){
					$cadena="<b>Advertencia:</b>  Estimado Coordinador, ya existe un recibo generado para este periodo, solamente podr&aacute; generar recibos para estudiantes que est&eacute;n solicitando reintegro, los recibos que se generen por este m&oacute;dulo estan sujetos a revisi&oacute;n por parte de la Vicerrector&iacute;a Acad&eacute;mica.<br> Para reexpedici&oacute;n de recibos, utilice el m&oacute;dulo de Extemporaneos, el cual contempla el cobro de intereses por mora como se&ntilde;ala el artículo 10 del Acuerdo 004 de 2006.<br>";					$html=alerta::sin_registro($configuracion,$cadena);			
				}
			
				$html.='<span class="bloquelateralcuerpo" ><br>Solicitud actual:<br><br>';
				$html.='C&oacute;digo:'.$registro[0][0].'<br>';
				$html.='Nombre:'.$registro[0][2].'<br></span>';
				
				$html.='<hr>';
			
			
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"valoraPagar",$codigo);
		  		$valorPago=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");	
		  		
	 		
		  		  		
		  		if(is_array($valorPago))
		  		{	  		
		  				
				$html.='<form enctype="multipart/form-data" method="POST" action="index.php" name="consulta_recibos">';
				$html.='<table width="90%" align="center"  class="bloquelateral">';
				$html.='	<tr class="texto_subtitulo_gris"><td>CONCEPTOS:</td><tr>';
				$html.='	<tr class="centralcuerpo">';
				$html.='		<td>';
				$html.='			<input type="checkbox" size="10" value="0" name="conceptoSeguro"/>Seguro';
				//$html.='			<input type="checkbox" size="10" value="1" name="conceptoCarnet"/>Carn&eacute;t';
				//$html.='			<input type="checkbox" size="10" value="2" name="conceptoSistem"/>Sistematizaci&oacute;n';						
				$html.='		</td>';
				$html.='	</tr>';
				$html.='</table>';
			
				$html.='<br>';				

				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"consultarExencion",$codigo);
		  		//$exencion=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");	 
		  		
		  		
		  		if((isset($exencion))&&is_array($exencion))
		  		{	  		
					$html.='<table width="90%" align="center"  class="bloquelateral">';
					$html.='	<tr class="texto_subtitulo_gris"><td>EXENCIONES:</td><tr>';
					$html.='	<tr class="centralcuerpo">';
					$html.='		<td>';
					$html.='		</td>';
					$html.='	</tr>';
					$html.='</table>';
				}else{
					$html.='<table width="90%" align="center"  class="bloquelateral">';
					$html.='	<tr class="texto_subtitulo_gris"><td>EXENCIONES:</td><tr>';
					$html.='	<tr class="centralcuerpo">';
					$html.='		<td>';
					$html.='			El estudiante no tiene registros de exenciones si desea adicionar una utilice el formulario de Exenciones';				
					$html.='		</td>';
					$html.='	</tr>';
					$html.='</table>';			
			
				}
			
				
				
			
				$html.='<br>';	

				$html.='<div style="position: absolute; display: none; left: 158px; top: 32px;" class="calendar"><table style="visibility: visible;" cellpadding="0" cellspacing="0"><thead><tr><td class="button" colspan="1"><div unselectable="on">?</div></td><td style="cursor: move;" class="title" colspan="6">Septiembre, 2009</td><td class="button" colspan="1"><div unselectable="on">×</div></td></tr><tr class="headrow"><td class="button nav" colspan="1"><div unselectable="on">«</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8249;</div></td><td class="button" colspan="4"><div unselectable="on">Hoy</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8250;</div></td><td class="button nav" colspan="1"><div unselectable="on">»</div></td></tr><tr class="daynames"><td class="name wn">sem</td><td class="day name">Lun</td><td class="day name">Mar</td><td class="day name">Mie</td><td class="day name">Jue</td><td class="day name">Vie</td><td class="name day weekend">S&aacute;b</td><td class="name day weekend">Dom</td></tr></thead><tbody><tr class="daysrow"><td class="day wn">36</td><td class="emptycell">&nbsp;</td><td class="day">1</td><td class="day">2</td><td class="day">3</td><td class="day">4</td><td class="day weekend">5</td><td class="day weekend">6</td></tr><tr class="daysrow"><td class="day wn">37</td><td class="day">7</td><td class="day">8</td><td class="day">9</td><td class="day">10</td><td class="day">11</td><td class="day weekend">12</td><td class="day weekend">13</td></tr><tr class="daysrow"><td class="day wn">38</td><td class="day">14</td><td class="day">15</td><td class="day">16</td><td class="day">17</td><td class="day">18</td><td class="day weekend">19</td><td class="day weekend">20</td></tr><tr class="daysrow"><td class="day wn">39</td><td class="day">21</td><td class="day">22</td><td class="day">23</td><td class="day">24</td><td class="day">25</td><td class="day selected today weekend">26</td><td class="day weekend">27</td></tr><tr class="daysrow"><td class="day wn">40</td><td class="day">28</td><td class="day">29</td><td class="day">30</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr><tr class="emptyrow"><td class="day wn">41</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr></tbody><tfoot><tr class="footrow"><td style="cursor: move;" class="ttip" colspan="8">Seleccionar fecha</td></tr></tfoot></table><div style="display: none;" class="combo"><div class="label">Ene</div><div class="label">Feb</div><div class="label">Mar</div><div class="label">Abr</div><div class="label">May</div><div class="label">Jun</div><div class="label">Jul</div><div class="label">Ago</div><div class="label">Sep</div><div class="label">Oct</div><div class="label">Nov</div><div class="label">Dic</div></div><div style="display: none;" class="combo"><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div></div></div>';

						
				$html.='<table width="90%" align="center"  class="bloquelateral">';
				$html.='	<tr class="texto_subtitulo_gris"><td colspan="2" >MATRICULA:</td><tr>';
				$html.='	<tr class="centralcuerpo">';
				$html.='		<td>';
				$html.="			<b>Valor(Incluye Exenciones): $</b>".$valorPago[0][0];	
				$html.='		</td>';
				$html.='		<td>';
				$html.="			<b>Fecha Ordinaria: </b> <input type='text' size='8' value='' name='ordinaria' id='ordinaria'/>(dd/mm/aaaa)";
				$html.='		</td>';			
				$html.='	</tr>';
				$html.='</table>';
										
				$html.='<script type="text/javascript">';
				$html.='	Calendar.setup({';
				$html.='		inputField:"ordinaria",';
				$html.='		ifFormat:"%d/%m/%Y",';
				$html.='		button:"ordinaria"';
				$html.='	});';
				$html.='</script>';
							
							
							

										
														
					$html.='		<input type="hidden" value="'.$codigo.'" name="estudiante"/>';			
					$html.='		<input type="hidden" value="confirmar" name="confirmar"/>';			
					$html.='		<input type="hidden" value="admin_solicitudIndividual" name="action"/>';
					$html.='		<br>';			
					$html.='		<center><input type="submit" onclick="document.forms[\'consulta_recibos\'].submit()" tabindex="2" name="consultar" value="Solicitar Recibo"/></center><br/>';		
						
					$html.='</form>';								
				}else{
			
					$html.='<table align="center"  class="bloquelateral">';
					$html.='	        <tr class="bloquelateralcuerpo" ><td>El estudiante no tiene registro de Matricula</td><tr>';		
					$html.='</table>';			
				}
			
			//}
			
			
			echo $html;
				
		}else{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="El estudiante ".$registro[0][0]." no pertenece a su Coordinaci&oacute;n.<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);
			
		}	
		
	}
		
	
	function confirmaRecibo($configuracion,$codigo,$valor)
	{

		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/calendario.class.php");
		$html='';
		$miCalendario=new calendario();
		
	
		$i=0;
		
			///////////////////////////////////FECHAS/////////////////////////////			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"valoraPagar",$codigo);
	  		$valorPago=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
			
			//$fechaPago=$resultado[0][0];	


								
		$html.='<span class="bloquelateralcuerpo" >El siguientes recibo esta listo para generar, una vez generado el proceso no podr&aacute; ser revertido</span>';
		$html.='<hr>';
		$html.='<form  enctype="multipart/form-data" method="POST" action="index.php" name="generar_recibos" >';		
		$html.='<center><table   class="bloquelateral" >';	
			$html.='<tr class="texto_subtitulo_gris">';			
			$html.='<td style="padding:10px" >';	
			$html.='<b>Fecha Ord</b>';
			$html.='</td>';	
			$html.='<td style="padding:10px" >';	
			$html.='<b>Fecha Ext</b>';
			$html.='</td>';				
			$html.='<td style="padding:10px" >';	
			$html.='<b>Periodo a pagar</b>';
			$html.='</td>';				
			$html.='<td style="padding:10px" >';		
			$html.='<b>Valor</b>';
			$html.='</td>';	
			$html.='</tr>';		
				
			
			$html.='<tr class="centralcuerpo">';			
			$html.='<td style="padding:10px" >';	
			$html.=$valor[4];
			$html.='</td>';	
			$html.='<td style="padding:10px" >';	
			
					//CALCULO DE LA FECHA EXTRAORDINARIA
					
					$dia=substr($valor[4],0,2);
					$mes=substr($valor[4],3,2);
					$anno=substr($valor[4],6,4);
					
					
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
					
					$valor[5]=$extradia."/".$extrames."/".$extraanno;
					
					
					
								
			
			$html.=$valor[5];
			$html.='</td>';				
			$html.='<td style="padding:10px" >';	
			$html.=$this->annoActual."/".$this->periodoActual;
			$html.='</td>';	
			$html.='<td style="padding:10px" >';
			$html.=$valorPago[0][0];
			
				for($i=1;$i<=3;$i++){
					switch($i){
						case 1:
							if($valor[1]==1){
								$html.="+ SEGURO";
							}
						break;
						case 2:
							if($valor[2]==1){
								$html.="+ CARNET";
							}
						break;						
						case 3:
							if($valor[3]==1){
								$html.="+ SISTEMATIZACI&Oacute;N";
							}
						break;							
					}
					
					
					
				}
			
			$html.='</td>';	
										
			$html.='</tr>';	

		$html.='</table></center>';
			
			$variable[0]=$this->usuario;
			$variable[1]=strtotime($mes."/".$dia."/".$anno);;
			$variable[2]=strtotime($extrames."/".$extradia."/".$extraanno);;
			$variable[3]=$this->annoActual;
			$variable[4]=$this->periodoActual;
			$variable[5]=$valorPago[0][0];
			$variable[6]=$valor[1].$valor[2].$valor[3];
			$variable[7]=time();
			$variable[8]=$codigo;		
			
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"insertarControl",$variable);
	  		$registro=$this->ejecutarSQL($configuracion,$this->acceso_db, $cadena_sql,"");	
	  			  		
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"rescatarControlID",$variable);
	  		$id=$this->ejecutarSQL($configuracion,$this->acceso_db, $cadena_sql,"busqueda");	
	  		
	  		
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		
				$cripto=new encriptar();
				$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
				$ayuda="pagina=admin_solicitud_individual";
				$ayuda.="&action=admin_solicitudIndividual";
				$ayuda.="&confirmar=admin_solicitudIndividual";
				$ayuda.="&generar=admin_solicitudIndividual";
				$ayuda.="&codigo=".$variable[8];
				$ayuda.="&id=".$id[0][0];
			
				$ayuda=$cripto->codificar_url($ayuda,$configuracion);
		
			
			$html.='<br><center><span class="bloquelateralcuerpo" ><a style="display:block; width:54px" href="'.$indice.$ayuda.'" > GENERAR </a></span></center>';

			$html.='</form>';		
		echo $html;
		
	
		
	}
			
	function generarRecibosIndividual($configuracion,$id)
	{

			///////////////////////////////////CARRERA/////////////////////////////
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"rescatarControl",$id);
	  		$registro=$this->ejecutarSQL($configuracion,$this->acceso_db, $cadena_sql,"busqueda");	
	  		
			$codigo=$_REQUEST['codigo'];
				
			
			$parametro[0]=$registro[0][6];
			
			
			$parametro[1]=$registro[0][4];	
			

			$parametro[2]=$registro[0][2]; //año
			$parametro[3]=$registro[0][3]; //periodo

			$parametro[4]=1; //cuota
			
		
			$parametro[5]=date("d",$registro[0][0])."/".date("n",$registro[0][0])."/".date("Y",$registro[0][0]);	 //ordinaria
			$parametro[6]=date("d",$registro[0][1])."/".date("n",$registro[0][1])."/".date("Y",$registro[0][1]);	 //extraordinaria
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"valoraPagar",$codigo);
	  		$valorPago=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
			
			$parametro[12]=$valorPago[0][0];
				  
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"secuencia");
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
			
			$parametro[7]=$resultado[0][0]; //secuencia
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"valorSeguro");
			$seguro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	
			
			$parametro[8]=$seguro[0][0];
							
			//Se decidio no diferir las matriculas 			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarCuotaIndividual",$parametro);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");		
			
				///////////////////////////////////CONCEPTOS/////////////////////////////
				
							
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConceptoMatricula",$parametro);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");	


				if(substr($registro[0][5],0,1)<>0){
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConceptoSeguro",$parametro);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
				
				}
				
				/*if(substr($registro[0][5],1,1)<>0){
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConceptoCarnet",$parametro);
				//$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			
				}
				if(substr($registro[0][5],2,1)<>0){				
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConceptoSistematizacion",$parametro);
				//$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");	
								
				}*/
				
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"actualizaConfirmacion",$id);
				$resultado=$this->ejecutarSQL($configuracion, $this->acceso_db, $cadena_sql, "");	


		
		$this->redireccionarInscripcion($configuracion,'exitoGenerados');		
				
				
	}

	function solicitudIndividualPosgrados($configuracion)
	{
		//$calendario=$this->validaCalendario();
		 		
		$html='<form enctype="multipart/form-data" method="POST" action="index.php" name="consulta_estudiante">';
		$html.='<table align="center"  class="bloquelateral">';
		$html.='	<tbody>';
		$html.='	        <tr class="texto_subtitulo_gris"><td>Ingrese el c&oacute;digo del estudiante</td><tr>';
		$html.='	        <tr >';
		$html.='		<td width="90%">';
		$html.='		<span class="bloquelateralcuerpo">Código:</span>';
		$html.='		<input type="text" size="10" name="estudiante"/>';
		$html.='		<input type="hidden" value="admin_solicitudIndividual" name="action"/>';
		$html.='		<input type="hidden" value="consultaEstudiante" name="consultaEstudiante"/>';
		$html.='		<input type="submit" onclick="document.forms[\'consulta_estudiante\'].submit()" tabindex="2" name="consultar" value="consultar"/><br/>';		
		$html.='		</td>';
		$html.='	</tr>';
		$html.='</table>';						
		$html.='</form>';			
		
		echo $html;
	}

	function verificaEstudiantePosgrado($configuracion,$valor)
	{
		
		$valor[1]=$this->usuario;
		if($valor[1]=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaEstudiantePosgrado",$valor);
		$verifica=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");

		
		if(is_array($verifica)){

	
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaPosgrado",$codigo);
		  	$aprobado=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");	

				$variable[0]=$codigo;
				$variable[1]=$this->annoActual;
				$variable[2]=$this->periodoActual;

			if(!is_array($aprobado)){
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaExisRecibo",$variable);
			  	$existe=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
		
			}
												
			if(!is_array($aprobado)){
				$cadena_sql2=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaAprobacion",$variable);
			  	$aprobado=$this->ejecutarSQL($configuracion,$this->accesoOracle,$cadena_sql2,"busqueda");
			  	
			}
		
	
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			
			
			/*if(is_array($aprobado)){
			
				$cadena="No requiere aprobaci&oacute;n de Consejo de Facultad.<br>";
				alerta::sin_registro($configuracion,$cadena);			
			}*/
			//else{
			
				if(is_array($existe)){
					$cadena="<b>Advertencia:</b>  Estimado Coordinador, ya existe un recibo generado para este periodo, solamente podr&aacute; generar recibos para estudiantes que est&eacute;n solicitando reintegro, los recibos que se generen por este m&oacute;dulo estan sujetos a revisi&oacute;n por parte de la Vicerrector&iacute;a Acad&eacute;mica.<br> Para reexpedici&oacute;n de recibos, utilice el m&oacute;dulo de Extemporaneos, el cual contempla el cobro de intereses por mora como se&ntilde;ala el artículo 10 del Acuerdo 004 de 2006.<br>";					$html=alerta::sin_registro($configuracion,$cadena);			
				}
			
				$html.='<span class="bloquelateralcuerpo" ><br>Solicitud actual:<br><br>';
				$html.='C&oacute;digo:'.$registro[0][0].'<br>';
				$html.='Nombre:'.$registro[0][2].'<br></span>';
				
				$html.='<hr>';
			
			
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"valoraPagar",$codigo);
		  		$valorPago=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");	
		  		
	 		
		  		  		
		  		if(is_array($valorPago))
		  		{	  		
		  				
				$html.='<form enctype="multipart/form-data" method="POST" action="index.php" name="consulta_recibos">';
				$html.='<table width="90%" align="center"  class="bloquelateral">';
				$html.='	<tr class="texto_subtitulo_gris"><td>CONCEPTOS:</td><tr>';
				$html.='	<tr class="centralcuerpo">';
				$html.='		<td>';
				$html.='			<input type="checkbox" size="10" value="0" name="conceptoSeguro"/>Seguro';
				//$html.='			<input type="checkbox" size="10" value="1" name="conceptoCarnet"/>Carn&eacute;t';
				//$html.='			<input type="checkbox" size="10" value="2" name="conceptoSistem"/>Sistematizaci&oacute;n';						
				$html.='		</td>';
				$html.='	</tr>';
				$html.='</table>';
			
				$html.='<br>';				

				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"consultarExencion",$codigo);
		  		//$exencion=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");	 
		  		
		  		
		  		if(is_array($exencion))
		  		{	  		
					$html.='<table width="90%" align="center"  class="bloquelateral">';
					$html.='	<tr class="texto_subtitulo_gris"><td>EXENCIONES:</td><tr>';
					$html.='	<tr class="centralcuerpo">';
					$html.='		<td>';
					$html.='		</td>';
					$html.='	</tr>';
					$html.='</table>';
				}else{
					$html.='<table width="90%" align="center"  class="bloquelateral">';
					$html.='	<tr class="texto_subtitulo_gris"><td>EXENCIONES:</td><tr>';
					$html.='	<tr class="centralcuerpo">';
					$html.='		<td>';
					$html.='			El estudiante no tiene registros de exenciones si desea adicionar una utilice el formulario de Exenciones';				
					$html.='		</td>';
					$html.='	</tr>';
					$html.='</table>';			
			
				}
			
				
				
			
				$html.='<br>';	

				$html.='<div style="position: absolute; display: none; left: 158px; top: 32px;" class="calendar"><table style="visibility: visible;" cellpadding="0" cellspacing="0"><thead><tr><td class="button" colspan="1"><div unselectable="on">?</div></td><td style="cursor: move;" class="title" colspan="6">Septiembre, 2009</td><td class="button" colspan="1"><div unselectable="on">×</div></td></tr><tr class="headrow"><td class="button nav" colspan="1"><div unselectable="on">«</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8249;</div></td><td class="button" colspan="4"><div unselectable="on">Hoy</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8250;</div></td><td class="button nav" colspan="1"><div unselectable="on">»</div></td></tr><tr class="daynames"><td class="name wn">sem</td><td class="day name">Lun</td><td class="day name">Mar</td><td class="day name">Mie</td><td class="day name">Jue</td><td class="day name">Vie</td><td class="name day weekend">S&aacute;b</td><td class="name day weekend">Dom</td></tr></thead><tbody><tr class="daysrow"><td class="day wn">36</td><td class="emptycell">&nbsp;</td><td class="day">1</td><td class="day">2</td><td class="day">3</td><td class="day">4</td><td class="day weekend">5</td><td class="day weekend">6</td></tr><tr class="daysrow"><td class="day wn">37</td><td class="day">7</td><td class="day">8</td><td class="day">9</td><td class="day">10</td><td class="day">11</td><td class="day weekend">12</td><td class="day weekend">13</td></tr><tr class="daysrow"><td class="day wn">38</td><td class="day">14</td><td class="day">15</td><td class="day">16</td><td class="day">17</td><td class="day">18</td><td class="day weekend">19</td><td class="day weekend">20</td></tr><tr class="daysrow"><td class="day wn">39</td><td class="day">21</td><td class="day">22</td><td class="day">23</td><td class="day">24</td><td class="day">25</td><td class="day selected today weekend">26</td><td class="day weekend">27</td></tr><tr class="daysrow"><td class="day wn">40</td><td class="day">28</td><td class="day">29</td><td class="day">30</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr><tr class="emptyrow"><td class="day wn">41</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr></tbody><tfoot><tr class="footrow"><td style="cursor: move;" class="ttip" colspan="8">Seleccionar fecha</td></tr></tfoot></table><div style="display: none;" class="combo"><div class="label">Ene</div><div class="label">Feb</div><div class="label">Mar</div><div class="label">Abr</div><div class="label">May</div><div class="label">Jun</div><div class="label">Jul</div><div class="label">Ago</div><div class="label">Sep</div><div class="label">Oct</div><div class="label">Nov</div><div class="label">Dic</div></div><div style="display: none;" class="combo"><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div></div></div>';

						
				$html.='<table width="90%" align="center"  class="bloquelateral">';
				$html.='	<tr class="texto_subtitulo_gris"><td colspan="2" >MATRICULA:</td><tr>';
				$html.='	<tr class="centralcuerpo">';
				$html.='		<td>';
				$html.="			<b>Valor(Incluye Exenciones): $</b>".$valorPago[0][0];	
				$html.='		</td>';
				$html.='		<td>';
				$html.="			<b>Fecha Ordinaria: </b> <input type='text' size='8' value='".$registro[$i][12]."' name='ordinaria' id='ordinaria'/>(dd/mm/aaaa)";
				$html.='		</td>';			
				$html.='	</tr>';
				$html.='</table>';
										
				$html.='<script type="text/javascript">';
				$html.='	Calendar.setup({';
				$html.='		inputField:"ordinaria",';
				$html.='		ifFormat:"%d/%m/%Y",';
				$html.='		button:"ordinaria"';
				$html.='	});';
				$html.='</script>';
							
							
							

										
														
					$html.='		<input type="hidden" value="'.$codigo.'" name="estudiante"/>';			
					$html.='		<input type="hidden" value="confirmar" name="confirmar"/>';			
					$html.='		<input type="hidden" value="admin_solicitudIndividual" name="action"/>';
					$html.='		<br>';			
					$html.='		<center><input type="submit" onclick="document.forms[\'consulta_recibos\'].submit()" tabindex="2" name="consultar" value="Solicitar Recibo"/></center><br/>';		
						
					$html.='</form>';								
				}else{
			
					$html.='<table align="center"  class="bloquelateral">';
					$html.='	        <tr class="bloquelateralcuerpo" ><td>El estudiante no tiene registro de Matricula</td><tr>';		
					$html.='</table>';			
				}
			
			//}
			
			
			echo $html;
				
		}else{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="El estudiante ".$registro[0][0]." no pertenece a su Coordinaci&oacute;n o no es estudiante de POSGRADO.<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);
			
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
				$variable="pagina=admin_solicitud_individual";
				$variable.="&estudiante=".$valor;
				$variable.="&opcion=verPeriodos";
				
			break;	
		
			case "confirmarRecibo":
				$variable="pagina=admin_solicitud_individual";
				$variable.="&estudiante=".$valor[0];
				$variable.="&confirmar=confirmar";				
				$variable.="&conceptoSeguro=".$valor[1];
				$variable.="&conceptoCarnet=".$valor[2];
				$variable.="&conceptoSistem=".$valor[3];
				$variable.="&ordinaria=".$valor[4];												
				
			break;	
			case "exitoGenerados":
				$variable="pagina=admin_solicitud_individual";
				$variable.="&opcion=exito";			
			
			break;
			
			case "solicitudrecibo":
				$variable="pagina=admin_solicitud_individual";
				$variable.="&opcion=solicitudRecibo";
				$variable.="&estudiante=".$valor;
			
			break;
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);

		
		//header("Location: ".$indice.$variable);
		
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();		
		
	}		
		
}

?>
