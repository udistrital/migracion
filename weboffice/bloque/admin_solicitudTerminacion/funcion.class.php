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

	}
	
	
	function nuevoRegistro($configuracion,$acceso_db)
	{}
	
   	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
   	{}
   	
    	function corregirRegistro()
    	{}
	

		
/*__________________________________________________________________________soportekjm________________________
		
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
			$html.='		<input type="hidden" value="admin_solicitudTerminacion" name="action"/>';
			$html.='		<input type="submit" onclick="document.forms[\'consulta_estudiante\'].submit()" tabindex="2" name="consultar" value="Consultar"/><br/>';		
			$html.='		</td>';
			$html.='	</tr>';
			$html.='</table>';						
			$html.='</form>';			
			
			echo $html;	

		
	}

	
	function consultarPeriodos($configuracion,$codigo)
	{
	
	
		/*$tmp=$this->ejecutarSQL($configuracion,$this->acceso_db,"SELECT * FROM backoffice_tmp","busqueda");
		
		$i=0;
		//$tmp[$i][0];
		while(isset($tmp[$i][0])){
		
			echo "<br>".$tmp[$i][0];
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"secuencia");
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
			
			//echo "<br>SELECT * from acestmat where ema_est_cod=".$tmp[$i][0]." and ema_ano_pago=2010 and ema_per_pago=1 and ema_cuota=2 and ema_pago='N'";	
			
			$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle,"SELECT * from acestmat where ema_est_cod=".$tmp[$i][0]." and ema_ano_pago=2010 and ema_per_pago=1 and ema_cuota=2 and ema_pago='N'","busqueda");
			
			//echo "<br>UPDATE acestmat SET ema_estado='I' where ema_est_cod=".$registro[0][0]." and ema_ano_pago=2010 and ema_per_pago=1 and ema_cuota=2 and ema_pago='N'";
			$update=$this->ejecutarSQL($configuracion,$this->accesoOracle,"UPDATE acestmat SET ema_estado='I' where ema_est_cod=".$registro[0][0]." and ema_ano_pago=2010 and ema_per_pago=1 and ema_cuota=2 and ema_pago='N'","");
			
			//echo "<br>INSERT INTO acestmat VALUES(".$registro[0][0].",(select est_cra_cod from acest where est_cod=".$registro[0][0]."),".$registro[0][2].",".$registro[0][2].",2010,1,SYSDATE,'A',".$resultado[0][0].",2,TO_DATE('04/05/10','dd/mm/yy'),TO_DATE('04/05/10','dd/mm/yy'),1,'N',2010,1)";
			$acestmat=$this->ejecutarSQL($configuracion,$this->accesoOracle,"INSERT INTO acestmat VALUES(".$registro[0][0].",(select est_cra_cod from acest where est_cod=".$registro[0][0]."),".$registro[0][2].",".$registro[0][2].",2010,1,SYSDATE,'A',".$resultado[0][0].",2,TO_DATE('04/05/10','dd/mm/yy'),TO_DATE('04/05/10','dd/mm/yy'),1,'N',2010,1)","");
		
			//echo "<br>INSERT INTO acrefest VALUES(2010,".$resultado[0][0].",23,1,".$registro[0][2].")";
			$acrefest=$this->ejecutarSQL($configuracion,$this->accesoOracle,"INSERT INTO acrefest VALUES(2010,".$resultado[0][0].",23,1,".$registro[0][2].")","");
			
		
		$i++;
		}
		*/
	
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiante",$codigo);
	  	$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
	
		$valor[0]=$codigo;
		$valor[1]=$this->usuario;  	

		
                if($this->nivel==4){
                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaEstudianteCoordinador",$valor);
                    $verifica=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
                }elseif($this->nivel==110 || $this->nivel==114){
                    $valido=$this->validacion->validarProyectoAsistente($valor[0], $valor[1],$this->accesoOracle,$configuracion,  $this->accesoOracle,$this->nivel);
                    if($valido=='ok'){
                        $verifica[0]=$codigo;
                    }
                }

		//$verifica=array(0,1);
		
		if(is_array($verifica)){

		
			$html='<br>Solicitud actual:<br><br>';
			$html.='C&oacute;digo:'.$registro[0][0].'<br>';
			$html.='Nombre:'.$registro[0][2].'<br>';
				
			$html.='<hr>';
			
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"recibosSinPagar",$codigo);
	  		$nopagos=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
	  		
	  		if(is_array($nopagos))
	  		{	  		
	  				
			$html.='<form enctype="multipart/form-data" method="POST" action="index.php" name="consulta_recibos">';
			$html.='<table align="center"  class="bloquelateral">';
			$html.='	<tbody>';
			$html.='	        <tr class="texto_subtitulo_gris"><td>Selecione los peridos a generar:</td><tr>';
			$html.='	        <tr >';
			$html.='		<td width="90%">';
			$html.='		<span class="bloquelateralcuerpo">Tenga en cuenta que los recibos ser&aacute;n generados &uacute;nicamente por valor del seguro<br></span><br>';
			
			//el value corresponde al periodo que se va a pagar  no al periodo actual



		  		$i=0;
		  		
		  		while(isset($nopagos[$i][0])){
		  	
					$html.='<input type="checkbox" size="10" name="reciboperiodo'.$nopagos[$i][0].$nopagos[$i][1].'" value="'.$nopagos[$i][0].$nopagos[$i][1].'"/>'.$nopagos[$i][0].'-'.$nopagos[$i][1].'<br>';			
				
				$i++;	

				}							
										
														
				$html.='		<input type="hidden" value="'.$codigo.'" name="estudiante"/>';			
				$html.='		<input type="hidden" value="confirmar" name="confirmar"/>';			
				$html.='		<input type="hidden" value="admin_solicitudTerminacion" name="action"/>';
				$html.='		<br>';			
				$html.='		<center><input type="submit" onclick="document.forms[\'consulta_recibos\'].submit()" tabindex="2" name="consultar" value="Solicitar Recibos"/></center><br/>';		
				$html.='		</td>';
				$html.='	</tr>';
				$html.='</table>';						
				$html.='</form>';								
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
		
	
	function confirmarPeriodos($configuracion,$codigo,$periodosaPagar)
	{
		$i=0;
		
			///////////////////////////////////FECHAS/////////////////////////////
//se adiciona para consultar la facultad a la que pertenece el proyecto
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiante",$codigo);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	
                
                        $cadena_sql=$this->sql->cadena_sql($configuracion, $this->accesoOracle,"facultadCarrera",$resultado[0][3]);
                        $registroCarrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql ,"busqueda");
                        
                        
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"fechaPago",$registroCarrera[0][0]);
			$resultado=$this->ejecutarSQL($configuracion,$this->acceso_db, $cadena_sql,"busqueda");	
			
			$fechaPagoOrd=$resultado[0][0];	
			$fechaPagoExt=$resultado[0][1];	


								
		$html='Los siguientes recibos estan listos para generar, una vez los genere el proceso no podr&aacute; ser revertido';
		$html.='<hr>';
		$html.='<form  enctype="multipart/form-data" method="POST" action="index.php" name="generar_recibos" >';		
		$html.='<center><table>';	
			$html.='<tr class="texto_subtitulo_gris">';			
			$html.='<td>';	
			$html.='<b>Fecha Ord</b>';
			$html.='</td>';	
			$html.='<td>';	
			$html.='<b>Fecha Ext</b>';
			$html.='</td>';				
			$html.='<td>';	
			$html.='<b>Periodo a pagar</b>';
			$html.='</td>';				
			$html.='<td>';		
			$html.='<b>Observacion</b>';
			$html.='</td>';	
			$html.='<td>';		
			$html.='<b>Confirmar</b>';
			$html.='</td>';												
			$html.='</tr>';		
				
		while(isset($periodosaPagar[$i])){
		
			$annoPago=substr($periodosaPagar[$i],0,4);
			$perPago=substr($periodosaPagar[$i],4,1);
		
			$html.='<tr class="texto_subtitulo_gris">';			
			$html.='<td>';	
			$html.=$fechaPagoOrd;
			$html.='</td>';	
			$html.='<td>';	
			$html.=$fechaPagoExt;
			$html.='</td>';				
			$html.='<td>';	
			$html.=$annoPago."/".$perPago;
			$html.='</td>';	
			$html.='<td>';					
			$html.="TERM.MATERIAS PERIODO ".$annoPago."/".$perPago;
			$html.='</td>';	
			$html.='<td>';					
			$html.='<input type="checkbox" size="10" value="'.$periodosaPagar[$i].'" name="reciboperiodo'.$periodosaPagar[$i].'"/>';
			$html.='</td>';												
			$html.='</tr>';	

		$i++;	
		}
		$html.='</table></center>';
		
			$html.='<input type="hidden" value="'.$codigo.'" name="estudiante"/>';			
			$html.='<input type="hidden" value="admin_solicitudTerminacion" name="action"/>';
			$html.='<input type="hidden" value="admin_solicitudTerminacion" name="confirmar"/>';
			$html.='<input type="hidden" value="admin_solicitudTerminacion" name="generar"/>';	
			
			$html.='<center><table>';
			$html.='<tr>';								
			$html.='<td><input type="submit" onclick="document.forms[\'generar_recibos\'].submit()" tabindex="2" name="consultar" value="Generar"/><td/>';
//			$html.='<td><input type="button" onclick="document.forms[\'generar_recibos\'].submit()" tabindex="2" name="consultar" value="Cancelar"/><td/>';					
			$html.='</tr>';	
			$html.='</table></center>';
			$html.='</form>';		
		echo $html;
		
	
		
	}
			
	function generarRecibosTerminacion($configuracion,$codigo,$periodosaPagar)
	{
			///////////////////////////////////CODIGO/////////////////////////////
			$parametro[0]=$codigo;
	

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
//se adiciona para consultar la facultad a la que pertenece el proyecto
                        $cadena_sql=$this->sql->cadena_sql($configuracion, $this->accesoOracle,"facultadCarrera",$parametro[1]);
                        $registroCarrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql ,"busqueda");
                        
                        
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"fechaPago",$registroCarrera[0][0]);
			$resultado=$this->ejecutarSQL($configuracion,$this->acceso_db, $cadena_sql,"busqueda");	
			
			$parametro[5]=$resultado[0][0];	
                        $parametro[11]=$resultado[0][1];

									
			
			
		foreach($periodosaPagar as $valor) 
		{

			///////////////////////////////////SECUENCIA/////////////////////////////				
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"secuencia");
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	

		
			$parametro[6]=$resultado[0][0];	
			//$parametro[6]=100;
			
			//////año pago/////////////
			$parametro[7]=substr($valor,0,4);
			
			//////periodo pago//////////
			$parametro[8]=substr($valor,4,1);
			
			$parametro[9]=$this->usuario;	
			
			$parametro[10]="TERM.MATERIAS PERIODO ".$parametro[7]."/".$parametro[8];

			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizaracestmat",$parametro);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"insertarSolicitud",$parametro);
			$resultado=$this->ejecutarSQL($configuracion,$this->acceso_db, $cadena_sql, "");	
			
	
						
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarCuotaTerminacion",$parametro);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");		
	
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConceptoSeguro",$parametro);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");	

			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConceptoMatricula",$parametro);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
  
		}	
		$this->redireccionarInscripcion($configuracion,'exitoGenerados');		
				
				
	}			
		
	function redireccionarInscripcion($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		switch($opcion)
		{
			case "verPeriodos":
				$variable="pagina=admin_solicitud_terminacion";
				$variable.="&estudiante=".$valor;
				$variable.="&opcion=verPeriodos";
				
			break;	
		
			case "confirmarPeriodos":
				$variable="pagina=admin_solicitud_terminacion";
				$variable.="&estudiante=".$valor[1];
				$variable.="&confirmar=confirmar";				
				$variable.="&periodos=".$valor[0];
				
			break;	
			case "exitoGenerados":
				$variable="pagina=admin_solicitud_terminacion";
				$variable.="&opcion=exito";			
			
			break;			
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);

		//header("Location: ".$indice.$variable);
		
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();		
		
	}		
		
}

?>
