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
                    exit;
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
		

	function consultarPeriodos($configuracion)
	{

		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/listado.class.php");
		$milista=new listado($configuracion);
                $html='';
	

		$milista->setNumRegistros(10);
		$milista->setTabla('mntac.acestecaes','oracle');
		$milista->setRelacion('acest','eca_cod=est_cod');
		$milista->setRelacion('accra','cra_cod=est_cra_cod');
		$milista->setColumna('carrera','cra_nombre');		
		$milista->setColumna('codigo','est_cod');
		$milista->setColumna('recibo','eca_genero_recibo');
                if($this->nivel==4){
		$milista->setFiltro('cra_emp_nro_iden=',$this->usuario);	
		
                }elseif($this->nivel==114||$this->nivel==110){
                    $proyectos=$this->validacion->consultarProyectosAsistente($this->usuario,$this->nivel,$this->accesoOracle,$this->configuracion,$this->acceso_db);
                    if(is_array($proyectos)){
                        $cadena_proyectos='';
                        foreach ($proyectos as $key => $proyecto) {
                            $resultado[$key][0]=$proyecto[0];
                            $resultado[$key][1]=$proyecto[4];
                            $resultado[$key][2]=$this->usuario;
                            if(!$cadena_proyectos){
                                $cadena_proyectos="(".$proyecto[0];
                            }else{
                                $cadena_proyectos.=", ".$proyecto[0];
                            }
                        }
                        $cadena_proyectos.=")";
                    }
                    $milista->setFiltro('cra_cod IN',$cadena_proyectos);	
		
                }
		$milista->setFiltro('eca_genero_recibo=',"'N'");		
		$milista->setFiltro('eca_estado=',"'A'");	
		$milista->setFiltro('eca_ano=',$this->datosGenerales($configuracion,$this->accesoOracle, "anno"));
		$milista->setFiltro('eca_per=',$this->datosGenerales($configuracion,$this->accesoOracle, "per"));
		$milista->setCheck('codigos',1);	
		
			$html.='<form enctype="multipart/form-data" method="POST" action="index.php" name="consulta_recibos">';
			$html.=$milista->armarListado($configuracion,$this->accesoOracle);
			$html.='		<input type="hidden" value="confirmar" name="confirmar"/>';			
			$html.='		<input type="hidden" value="admin_recibosECAES" name="action"/>';
			$html.='		<br>';			
			$html.='		<center><input type="submit" onclick="document.forms[\'consulta_recibos\'].submit()" tabindex="2" name="consultar" value="Solicitar Recibos"/></center><br/>';		
			$html.='		</td>';
			$html.='	</tr>';
			$html.='</table>';						
			$html.='</form>';

				echo $html;
				
	}
		
	
	function confirmarPeriodos($configuracion,$estudiantes)
	{

		$i=0;
		
			///////////////////////////////////FECHAS/////////////////////////////			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"fechaPago");
			$resultado=$this->ejecutarSQL($configuracion,$this->acceso_db, $cadena_sql,"busqueda");	
			
			$fechaPago=$resultado[0][0];	


								
		$html='Los siguientes recibos estan listos para generar, una vez los genere el proceso no podr&aacute; ser revertido';
		$html.='<hr>';
		$html.='<form  enctype="multipart/form-data" method="POST" action="index.php" name="generar_recibos" >';		
		$html.='<center><table class="contenidotabla">';	
			$html.='<tr class="cuadro_plano">';			
			$html.='<td>';	
			$html.='<b>Codigo</b>';
			$html.='</td>';	
			$html.='</tr>';		
				
		while(isset($estudiantes[$i])){
		
		
			$html.='<tr class="cuadro_plano">';			
			$html.='<td>';	
			$html.=$estudiantes[$i];
			$html.='</td>';	
			$html.='</tr>';	
			$codigos=$estudiantes[$i]."#";
		$i++;	
		}
		$html.='</table></center>';
		
			$html.='<input type="hidden" value="'.$codigos.'" name="estudiantes"/>';			
			$html.='<input type="hidden" value="admin_recibosECAES" name="action"/>';
			$html.='<input type="hidden" value="admin_recibosECAES" name="confirmar"/>';
			$html.='<input type="hidden" value="admin_recibosECAES" name="generar"/>';	
			
			$html.='<center><table>';
			$html.='<tr>';								
			$html.='<td><input type="submit" onclick="document.forms[\'generar_recibos\'].submit()" tabindex="2" name="consultar" value="Generar"/><td/>';
//			$html.='<td><input type="button" onclick="document.forms[\'generar_recibos\'].submit()" tabindex="2" name="consultar" value="Cancelar"/><td/>';					
			$html.='</tr>';	
			$html.='</table></center>';
			$html.='</form>';		
		echo $html;
		
	
		
	}
			
	function generarRecibosECAES($configuracion,$estudiantes)
	{


			$parametro[2]=$this->datosGenerales($configuracion,$this->accesoOracle, "anno") ;
			$parametro[3]=$this->datosGenerales($configuracion,$this->accesoOracle, "per") ;

			
		foreach($estudiantes as $valor) 
		{			
			///////////////////////////////////CODIGO/////////////////////////////
			$parametro[0]=$valor;
	


			///////////////////////////////////CARRERA/////////////////////////////
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carreraEstudiante",$parametro[0]);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	
			
			$parametro[1]=$resultado[0][0];	
			
			//echo "<br><br>".$cadena_sql;	
			
			///////////////////////////////////VALOR SEGURO/////////////////////////////
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"valorECAES");
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	
			$parametro[4]=$resultado[0][0];	
			$parametro[12]=$resultado[0][1];	
			//echo"<br><br>SSS". $cadena_sql;				
			///////////////////////////////////FECHAS/////////////////////////////			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaPago");
			$resultado=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");	
			
			//var_dump($resultado);
			$parametro[5]=$resultado[0][0];	
			$parametro[11]=$resultado[0][1];	
			//echo "<br><br>".$cadena_sql;		
	
			///////////////////////////////////SECUENCIA/////////////////////////////				
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"secuencia");
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	
		
			$parametro[6]=$resultado[0][0];	
				
			
			
			//////año pago/////////////
			$parametro[7]=$parametro[2];
			
			//////periodo pago//////////
			$parametro[8]=$parametro[3];
			
			$parametro[9]=$this->usuario;	
			
			$parametro[10]="PAGO ECAES";					
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db,"insertarSolicitud",$parametro);
			$resultado=$this->ejecutarSQL($configuracion,$this->acceso_db, $cadena_sql, "");	
			
			//echo "<br><br>".$cadena_sql;	
						
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarCuotaECAES",$parametro);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");		
			
			//echo "<br><br>".$cadena_sql;	
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConceptoECAES",$parametro);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");	
			
			//echo "<br><br>".$cadena_sql;
			
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"insertarConceptoMatricula",$parametro);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");		

			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizarEstadoPago",$parametro);
			$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
													
			//echo "<br><br>".$cadena_sql;
			
		}							
                if($this->nivel==4){
		$this->redireccionarInscripcion($configuracion,'exitoGenerados');		
                }else{
                    $this->redireccionarInscripcion($configuracion,'exitoGeneradosAsistente');		
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
				$variable="pagina=admin_recibos_ECAES";
				$variable.="&estudiante=".$valor;
				$variable.="&opcion=verPeriodos";
				
			break;	
		
			case "confirmarPeriodos":
				$variable="pagina=admin_recibos_ECAES";
				$variable.="&confirmar=confirmar";				
				$variable.="&estudiantes=".$valor[0];
				
			break;	
			case "exitoGenerados":
				$variable="pagina=admin_consultar_listado";
				$variable.="&opcion=InscritosECAES";			
			
			break;			
			case "exitoGeneradosAsistente":
				$variable="pagina=admin_recibos_ECAES";
				$variable.="&nivel=".$this->nivel;
			
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
