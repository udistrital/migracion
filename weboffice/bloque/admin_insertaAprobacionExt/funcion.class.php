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
		$this->accesoOracle=$this->conectarDB($configuracion,"secretarioacad");
		$this->acceso_db=$this->conectarDB($configuracion,"");
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
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
		
		if($this->usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
					
		$accesoOracle=$this->conectarDB($configuracion,"coordinador");
		$conexion=$accesoOracle;
		
		$annoActual=$this->datosGenerales($configuracion,$conexion, "anno") ;
		$periodoActual=$this->datosGenerales($configuracion,$conexion, "per") ;
		

	  			
			$html='<form enctype="multipart/form-data" method="POST" action="index.php" name="consulta_estudiante">';
			$html.='<table align="center"  class="bloquelateral">';
			$html.='	<tbody>';
			$html.='	        <tr class="texto_subtitulo_gris"><td>Ingrese el c&oacute;digo del estudiante</td><tr>';
			$html.='	        <tr >';
			$html.='		<td width="90%">';
			$html.='		<span class="bloquelateralcuerpo">Código:</span>';
			$html.='		<input type="text" size="10" name="estudiante"/>';
			$html.='		<input type="hidden" value="admin_insertaAprobacionExt" name="action"/>';
			$html.='		<input type="submit" onclick="document.forms[\'consulta_estudiante\'].submit()" tabindex="2" name="consultar" value="Consultar"/><br/>';		
			$html.='		</td>';
			$html.='	</tr>';
			$html.='</table>';					
			$html.='</form>';
			
			$html.='<center><a href="documento/extemporaneossec.pdf">Ver Manual</a></center>';
			
						
			
			echo $html;	
		
	}

	
	function consultarPeriodos($configuracion,$codigo)
	{
	
		if($this->usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiante",$codigo);
	  	$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
	
		$valor[0]=$codigo;
		$valor[1]=$this->usuario;  	

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaEstudianteCoordinador",$valor);
		$verifica=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
		
	      //echo "MMM".$verifica[0][0];
		if(is_array($verifica)){

			$html='<div style="position: absolute; display: none; left: 158px; top: 32px;" class="calendar"><table style="visibility: visible;" cellpadding="0" cellspacing="0"><thead><tr><td class="button" colspan="1"><div unselectable="on">?</div></td><td style="cursor: move;" class="title" colspan="6">Septiembre, 2009</td><td class="button" colspan="1"><div unselectable="on">×</div></td></tr><tr class="headrow"><td class="button nav" colspan="1"><div unselectable="on">«</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8249;</div></td><td class="button" colspan="4"><div unselectable="on">Hoy</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8250;</div></td><td class="button nav" colspan="1"><div unselectable="on">»</div></td></tr><tr class="daynames"><td class="name wn">sem</td><td class="day name">Lun</td><td class="day name">Mar</td><td class="day name">Mie</td><td class="day name">Jue</td><td class="day name">Vie</td><td class="name day weekend">S&aacute;b</td><td class="name day weekend">Dom</td></tr></thead><tbody><tr class="daysrow"><td class="day wn">36</td><td class="emptycell">&nbsp;</td><td class="day">1</td><td class="day">2</td><td class="day">3</td><td class="day">4</td><td class="day weekend">5</td><td class="day weekend">6</td></tr><tr class="daysrow"><td class="day wn">37</td><td class="day">7</td><td class="day">8</td><td class="day">9</td><td class="day">10</td><td class="day">11</td><td class="day weekend">12</td><td class="day weekend">13</td></tr><tr class="daysrow"><td class="day wn">38</td><td class="day">14</td><td class="day">15</td><td class="day">16</td><td class="day">17</td><td class="day">18</td><td class="day weekend">19</td><td class="day weekend">20</td></tr><tr class="daysrow"><td class="day wn">39</td><td class="day">21</td><td class="day">22</td><td class="day">23</td><td class="day">24</td><td class="day">25</td><td class="day selected today weekend">26</td><td class="day weekend">27</td></tr><tr class="daysrow"><td class="day wn">40</td><td class="day">28</td><td class="day">29</td><td class="day">30</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr><tr class="emptyrow"><td class="day wn">41</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr></tbody><tfoot><tr class="footrow"><td style="cursor: move;" class="ttip" colspan="8">Seleccionar fecha</td></tr></tfoot></table><div style="display: none;" class="combo"><div class="label">Ene</div><div class="label">Feb</div><div class="label">Mar</div><div class="label">Abr</div><div class="label">May</div><div class="label">Jun</div><div class="label">Jul</div><div class="label">Ago</div><div class="label">Sep</div><div class="label">Oct</div><div class="label">Nov</div><div class="label">Dic</div></div><div style="display: none;" class="combo"><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div></div></div>';
			$html.='<br>Aprobaci&oacute;n actual:<br><br>';
			$html.='C&oacute;digo:'.$registro[0][0].'<br>';
			$html.='Nombre:'.$registro[0][2].'<br>';
				
			$html.='<hr>';
			
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"recibosSinPagar",$codigo);
	  		$nopagos=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");	
	  		

		  		//echo $cadena_sql;
		  	
		  		  		
	  		if(is_array($nopagos))
	  		{	  		
	  				
			$html.='<form enctype="multipart/form-data" method="POST" action="index.php" name="consulta_recibos">';
			
			$html.='<table align="center"  class="bloquelateral">';
			$html.='	<tr>';
			$html.='		<td>';
			$html.='			<table align="center"  class="bloquelateral">';			
			$html.='		        	<tr class="texto_subtitulo_gris"><td>Selecione los peridos a aprobados<br> en el Acta:</td><tr>';
			$html.='		        	<tr >';
			$html.='					<td width="90%">';
			$html.='						<span class="bloquelateralcuerpo"><br></span>';
			
			//el value corresponde al periodo que se va a pagar  no al periodo actual



												
		  	$html.="							<table  width='100%' border=1>";
		  	$html.="								<tr><td width='50%' >PERIODO</td><td align='center'>CUOTA</td></tr>";
												$i=0;
												while(isset($nopagos[$i][0])){
		  	
			$html.='								<tr>';
			$html.='									<td><input type="checkbox" size="10" name="reciboperiodo'.$nopagos[$i][0].$nopagos[$i][1].'" value="'.$nopagos[$i][0].$nopagos[$i][1].'"/>'.$nopagos[$i][0].'-'.$nopagos[$i][1].'</td>';
			$html.='									<td align="center"><select  name="'.$nopagos[$i][0].$nopagos[$i][1].'"  ><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></td>';
			$html.='								</tr>';			
				
												 $i++;	
												 }					
			$html.="							</table>";
										
														
			$html.='					</td>';
			$html.='				</tr>';
			$html.='				<tr>';
			$html.='					<td>';
			$html.='						<input type="hidden" value="'.$codigo.'" name="estudiante"/>';			
			$html.='						<input type="hidden" value="confirmar" name="confirmar"/>';			
			$html.='						<input type="hidden" value="admin_insertaAprobacionExt" name="action"/>';
			$html.='						<br>';			
			$html.='						<center><input type="submit" onclick="document.forms[\'consulta_recibos\'].submit()" tabindex="2" name="consultar" value="Registrar"/></center><br/>';		
			$html.='					</td>';
			$html.='				</tr>';
			$html.='			</table>';			
			$html.='		</td>';
			$html.='		<td>';
			$html.='			No. Acta: <br><input type="text" value="" name="numacta"/><br> ';			
			$html.='			Fecha Acta:<br> <input type="text"  id="fecacta" value="" name="fecacta"/><br> ';

			$html.='		</td>';				
			$html.='	</tr>';

			$html.='</table>';												
			$html.='</form>';	
				
			$html.='<script type="text/javascript">';
			$html.='	Calendar.setup({';
			$html.='		inputField:"fecacta",';
			$html.='		ifFormat:"%d/%m/%Y",';
			$html.='		button:"fecacta"';
			$html.='	});';
			$html.='</script>';
													
			}else{
			
				$html.='<table align="center"  class="bloquelateral">';
				$html.='	        <tr class="texto_subtitulo_gris"><td>Selecione los peridos a generar:</td><tr>';
				$html.='	        <tr class="bloquelateralcuerpo" ><td>No existen recibos pendientes</td><tr>';		
				$html.='</table>';			
			}
			echo $html;
				
		}else{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="El estudiante ".$registro[0][0]." no pertenece a su Coordinaci&oacute;n.<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);
			
		}	
		
	}
		
	
	function confirmarPeriodos($configuracion,$codigo,$periodosaPagar,$acta)
	{

		if($this->usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
			/*echo "<pre>";
			var_dump($periodosaPagar);
			echo "</pre>";*/
	
		$i=0;
		
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiante",$codigo);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
			
			
		$html='Los siguientes recibos podr&aacute;n ser generados desde la respectiva coordinaci&oacute;n';
		$html.='<hr>';
		$html.='<form  enctype="multipart/form-data" method="POST" action="index.php" name="generar_recibos" >';		
		$html.='<center><table>';	
			$html.='<tr class="texto_subtitulo_gris">';			
			$html.='<td>';	
			$html.='<b>Codigo</b>';
			$html.='</td>';	
			$html.='<td>';	
			$html.='<b>Nombre</b>';
			$html.='</td>';				
			$html.='<td>';	
			$html.='<b>Periodo</b>';
			$html.='</td>';				
			$html.='<td>';		
			$html.='<b>Cuota</b>';
			$html.='</td>';	
			$html.='<td>';		
			$html.='<b>Acta</b>';
			$html.='</td>';	
			$html.='<td>';		
			$html.='<b>Confirmar</b>';
			$html.='</td>';															
			$html.='</tr>';		
				
		while(isset($periodosaPagar[$i])){
		
			$referencia=explode('@',$periodosaPagar[$i]);
			$annoPago=substr($referencia[0],0,4);
			$perPago=substr($referencia[0],4,1);
			$cuota=$referencia[1];
		
			$html.='<tr class="texto_subtitulo_gris">';			
			$html.='<td>';	
			$html.=$resultado[0][0];
			$html.='</td>';	
			$html.='<td>';	
			$html.=$resultado[0][2];
			$html.='</td>';				
			$html.='<td>';	
			$html.=$annoPago."/".$perPago;
			$html.='</td>';	
			$html.='<td>';					
			$html.=$cuota;
			$html.='</td>';	
			$html.='<td>';					
			$html.=$acta[0]." de ".$acta[1];
			$html.='</td>';				
			$html.='<td>';					
			$html.='<input type="checkbox" size="10" value="'.$periodosaPagar[$i].'" name="reciboperiodo'.$periodosaPagar[$i].'"/>';
			$html.='</td>';												
			$html.='</tr>';	

		$i++;	
		}
		$html.='</table></center>';
		
			$html.='<input type="hidden" value="'.$codigo.'" name="estudiante"/>';			
			$html.='<input type="hidden" value="admin_insertaAprobacionExt" name="action"/>';
			$html.='<input type="hidden" value="admin_insertaAprobacionExt" name="confirmar"/>';
			$html.='<input type="hidden" value="admin_insertaAprobacionExt" name="generar"/>';
			$html.='<input type="hidden" value="'.$acta[0].'" name="numacta"/>';
			$html.='<input type="hidden" value="'.$acta[1].'" name="fecacta"/>';				
			
			$html.='<center><table>';
			$html.='<tr>';								
			$html.='<td><input type="submit" onclick="document.forms[\'generar_recibos\'].submit()" tabindex="2" name="consultar" value="Aprobar"/><td/>';
//			$html.='<td><input type="button" onclick="document.forms[\'generar_recibos\'].submit()" tabindex="2" name="consultar" value="Cancelar"/><td/>';					
			$html.='</tr>';	
			$html.='</table></center>';
			$html.='</form>';		
		echo $html;
		
	
		
	}
			
	function insertarAprobacion($configuracion,$codigo,$variable)
	{
		$periodosaPagar=explode('#',$variable[0]);
		unset($periodosaPagar[count($periodosaPagar)-1]);

		

		$i=0;	
		
		while(isset($periodosaPagar[$i])) 
		{
			$referencia=explode("@",$periodosaPagar[$i]);
			
			//echo "<br>carrera:".
			$valor[0]=$codigo;
			$valor[1]=substr($referencia[0],0,4);
			$valor[2]=substr($referencia[0],4,1);
			$valor[3]=$referencia[1];
			$valor[4]=$variable[3]; //fecha Acta
			$valor[5]=$variable[2];
			$valor[6]=$this->usuario;
			$valor[7]=$_SERVER['REMOTE_ADDR'];
	  
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verficaAprobacion",$valor);
			$verificaRegistro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

			if(is_array($verificaRegistro))
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena="El estudiante ".$valor[0]." ya existe un registro de aprobaci&oacute;n para el periodo ".$valor[1]."-".$valor[2].", correspondiente a la cuota No. ".$valor[3]."!!<br>";

				$cadena.="<br><a href='javascript:window.history.back(-3)'>.::Regresar::.</a>";		
				alerta::sin_registro($configuracion,$cadena); EXIT;
			}
			else
			{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarAprobacion",$valor);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
			}
			
			//$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
			
			$i++;	
			
		}			
		if($resultado==true)
		{
			$this->redireccionarInscripcion($configuracion,'exitoAprobados');
		}
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="El registro no fu&eacute; guardado, revise que que se hayan realizado todos los pasos correctamente e intente de nuevo!!<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);	
		}
				
	}			
		
	function redireccionarInscripcion($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			
		//var_dump($valor);
			
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		switch($opcion)
		{
			case "verPeriodos":
				$variable="pagina=admin_inserta_aprobacion_ext";
				$variable.="&estudiante=".$valor;
				$variable.="&opcion=verPeriodos";
				
			break;	
		
			case "confirmarPeriodos":
				$variable="pagina=admin_inserta_aprobacion_ext";
				$variable.="&estudiante=".$valor[1];
				$variable.="&confirmar=confirmar";				
				$variable.="&periodos=".$valor[0];
				$variable.="&numacta=".$valor[2];			
				$variable.="&fecacta=".$valor[3];				
				
			break;	
			case "exitoAprobados":
				$variable="pagina=admin_inserta_aprobacion_ext";
				$variable.="&opcion=exito";			
			
			break;
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);

		//echo $indice.$variable;
		
		//header("Location: ".$indice.$variable);
		
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();		
		
	}		
		
}

?>
