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
		$this->acceso_db=$this->conectarDB($configuracion,"");
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
                if($this->nivel==4){
                    $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
                }elseif($this->nivel==110){
                    $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
                }elseif($this->nivel==114){
                    $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
                }else{
                    echo "NO TIENE PERMISOS PARA ESTE MODULO";
                }

	}
	
	
	function nuevoRegistro($configuracion,$acceso_db)
	{}
	
   	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
   	{}
   	
    	function corregirRegistro()
    	{}
	
	function mostrarRegistro($configuracion,$registro, $total, $opcion="",$valor)
    	{
		
		switch($opcion)
		{
			case "multiplesCarreras":
				$this->multiplesCarreras($configuracion,$registro, $total, $valor);
				break;
				
			case "generado":
				$this->reciboGenerado($configuracion,$registro, $total,$valor);
		
		}
	}
	
		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
	function consultarCarrera($configuracion)
	{
		//Conexion ORACLE
		$accesoOracle=$this->conectarDB($configuracion,"coordinador");
		$conexion=$accesoOracle;
		
		$annoActual=$this->datosGenerales($configuracion,$conexion, "anno") ;
		$periodoActual=$this->datosGenerales($configuracion,$conexion, "per") ;
		
		if(isset($_REQUEST["carrera"]))
		{
			$variable[0]=$_REQUEST["carrera"];
		}
		else
		{
			$variable[0]=99999;
		}
		$variable[1]=$annoActual;
		$variable[2]=$periodoActual;
                $_REQUEST["orden"]=(isset($_REQUEST["orden"])?$_REQUEST["orden"]:'');
                
		if(isset($_REQUEST["opcion"]))
		{
			switch($_REQUEST["opcion"])
			{
				case "generado":
					//Se seleccionan los recibos bloqueados en ACESTMAT de acuerdo a los criterios de busqueda
					switch($_REQUEST["accion"])
					{
						case "listaCompleta":
							
							//Paginacion
							//Obtener el total de registros
							$cadena_sql=$this->sql->cadena_sql($configuracion,$accesoOracle,"totalGenerado",$variable);						
							$registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
							if(is_array($registro))
							{
								$totalRegistros=$registro[0][0];
							}
							else
							{
								$totalRegistros=0;
							}
							
							//Obtener el numero de pagina
							$this->totalPaginas=ceil($totalRegistros/$configuracion['registro']);
							
							//Obtener el numero de la pagina
							if(!isset($_REQUEST["hoja"]))
							{
								$this->paginaActual=1;
							}
							else
							{
								$this->paginaActual=$_REQUEST["hoja"];
							}
							$variable[3]=$this->paginaActual;
							
							//$_REQUEST["orden"]='codigo';
							
							if($_REQUEST["orden"]=='secuencia' || !$_REQUEST["orden"] ){
								$variable[4]='ema_secuencia desc';
							}
							if($_REQUEST["orden"]=='codigo'){
								$variable[4]='ema_est_cod desc';
							}
							if($_REQUEST["orden"]=='nombre'){
								$variable[4]='trim(est_nombre) asc';
							}							
							//Obtener la pagina especifica
							$cadena_sql=$this->sql->cadena_sql($configuracion,$accesoOracle,"generadoCompleto",$variable);
							
						break;					
					}
				break;
				
				default:
				break;
			}
			$registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
			if(is_array($registro))
			{
				//Obtener el total de registros
				$totalRegistros=$this->totalRegistros($configuracion, $conexion);
				
				unset($valor);
				
				$valor[0]=$_REQUEST["orden"];
				$this->mostrarRegistro($configuracion,$registro, $totalRegistros, "generado",$valor);
				
				
			}
			else
			{
				
			}
		}
		else
		{
		
		}
		
	}
	
	function reciboGenerado($configuracion,$registro,$total,$variable)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		
		setlocale(LC_MONETARY, 'en_US');
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		$menu=new navegacion();
		
			$parametro="pagina=admin_generados";
			$parametro.="&hoja=1";
			$parametro.="&opcion=generado";
			$parametro.="&accion=listaCompleta";
			$parametro.="&carrera=".$registro[0][2];
			$parametro.="&orden=secuencia";
			$parametro=$cripto->codificar_url($parametro,$configuracion);
		
			$parametro2="pagina=admin_generados";
			$parametro2.="&hoja=1";
			$parametro2.="&opcion=generado";
			$parametro2.="&accion=listaCompleta";
			$parametro2.="&carrera=".$registro[0][2];
			$parametro2.="&orden=codigo";
			$parametro2=$cripto->codificar_url($parametro2,$configuracion);

			$parametro3="pagina=admin_generados";
			$parametro3.="&hoja=1";
			$parametro3.="&opcion=generado";
			$parametro3.="&accion=listaCompleta";
			$parametro3.="&carrera=".$registro[0][2];
			$parametro3.="&orden=nombre";
			$parametro3=$cripto->codificar_url($parametro3,$configuracion);
										
		
		$variableNavegacion["pagina"]="admin_generados";
		$variableNavegacion["opcion"]="generado";	
		$variableNavegacion["accion"]="listaCompleta";
		$variableNavegacion["carrera"]=$registro[0][2];
		$variableNavegacion["orden"]=$variable[0];
		
		?>
                <script type="text/javascript" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"];?>/datatables/js/jquery.js"></script>
                <script type="text/javascript" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"];?>/datatables/js/jquery.dataTables.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#tabla').dataTable();
                    })
                </script>
                <link type="text/css" href="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"];?>/datatables/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
                <table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<td>
								Recibos Generados<br>
								<hr class="hr_subtitulo">
								</td>
							</tr>

						
							<tr>
								<td>
									<span class='bloquelateralayuda'>Haga click sobre el titulo de la columna para cambiar el orden de los registros.<span>
									<table class='contenidotabla' id="tabla">
                                                                            <thead>
										<tr class='cuadro_color'>
											<td class='cuadro_plano centrar'><a href='<?echo $indice.$parametro?>'>Recibo</a></td>
											<td class='cuadro_plano centrar'><a href='<?echo $indice.$parametro2?>'>C&oacute;digo</a></td>
											<td class='cuadro_plano centrar'><a href='<?echo $indice.$parametro3?>'>Nombre</a></td>
											<td class='cuadro_plano centrar'>Valor</td>
											<td class='cuadro_plano centrar'>Vencimiento</td>
										</tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?
                                                                                    for($contador=0;$contador<$total;$contador++)
                                                                                    {
                                                                                            echo "<tr>\n";
                                                                                            echo "<td class='cuadro_plano centrar'>".$registro[$contador][0]."</td>\n";
                                                                                            //echo "<td class='cuadro_plano'><a href='".$indice.$parametro."'>".$registro[$contador][1]."</a></td>\n";
                                                                                            echo "<td class='cuadro_plano'><a href='";		
                                                                                            $variable="pagina=factura_coordinador";
                                                                                            //Codigo del Estudiante
                                                                                            $variable.="&opcion=imprimir";
                                                                                            $variable.="&no_pagina=true";
                                                                                            $variable.="&factura=".$registro[$contador][0];
                                                                                            $variable=$cripto->codificar_url($variable,$configuracion);
                                                                                            echo $indice.$variable."'";		
                                                                                            echo ">".$registro[$contador][1]."</a></td>\n";
                                                                                            echo "<td class='cuadro_plano'>".$registro[$contador][13]."</td>\n";
                                                                                            echo "<td class='cuadro_plano centrar'>".money_format('$ %!.0i',$registro[$contador][3])."</td>\n";
                                                                                            echo "<td class='cuadro_plano centrar'>".$registro[$contador][10]."</td>\n";
                                                                                            echo "</tr>";
                                                                                    }
                                                                            ?>
									</tbody>
                                                                        </table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<p class="textoNivel0">La tabla anterior muestra el consolidado de recibos que se han generado 
						por parte de los estudiantes.</p>
						<p class="textoNivel0">Por favor realice click sobre el nombre del proyecto curricular que desee revisar.</p>
						
					</td>
				</tr>
			</tbody>
		</table>
		
		<?
	
	
	}
	
	/*--------------------------------------------------------------------------------------------------------------------------
	* @name          funcion consultarGeneradoEstudiante
	* @author        Karen Palacios
	* @revision      Última revisión 11 de agosto de 2009
	/*--------------------------------------------------------------------------------------------------------------------------
	* @author			Oficina Asesora de Sistemas
	* @link			N/D
	* @description  	Consulta recibos generados para un estudiante
	*				
	*				
	*
	/*--------------------------------------------------------------------------------------------------------------------------*/	
	
	function consultarGeneradoEstudiante($configuracion)
	{
		
		$valor[0]=$_REQUEST["estudiante"];
		$valor[1]=$this->usuario;
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"verificaEstudianteCoordinador",$valor);
		
	  	$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");

		
		if(is_array($registro)){
			$valor[0]=$registro[0][0];
			$valor[1]=$registro[0][1];
			$valor[2]=$_REQUEST["anno_per"];
			
			unset($_REQUEST['action']);
			unset($_REQUEST['estudiante']);
			unset($_REQUEST['anno_per']);
			unset($_REQUEST['carrera']);
					
			$this->redireccionarInscripcion($configuracion, "generadoEstudiante",$valor);
		}else{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="El estudiante ".$valor[0]." no pertenece a su Coordinaci&oacute;n.<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);	
		}

	}	


	function recibosGeneradosest($configuracion,$codigo,$anno_per)
	{

		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		

		$select=new html();
		
		$busqueda=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"periodosRecibos","");	
		
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
		
		$periodos=$select->cuadro_lista($resultado,"anno_per",$configuracion,$anno_per,1,100);
		
		
		$annoActual=$this->datosGenerales($configuracion,$this->accesoOracle, "anno") ;
		$periodoActual=$this->datosGenerales($configuracion,$this->accesoOracle, "per") ;
		
		$variable[0]=$codigo;
		$variable[1]=$annoActual;
		$variable[2]=$periodoActual;
				
		if($anno_per!=""){
		
			$variable[1]=substr($anno_per,0,4);
			$variable[2]=substr($anno_per,4,1);
		}

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"recibosGeneradosEstudiante",$variable);
	  	$registro=$this->ejecutarSQL($configuracion,$this->accesoOracle, $cadena_sql,"busqueda");
	  	
			if(!is_array($registro))
			{
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
				$cadena="El estudiante ".$codigo." no tiene recibos generados para este periodo.<br>";
				$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";	
				alerta::sin_registro($configuracion,$cadena);
			}
			else{	
				$html='<form enctype="multipart/form-data"  name="admin_generados" action="index.php" method="POST"><table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >';
				$html.='<tr class="texto_subtitulo">';
				$html.='<td>';
				$html.='Historico de Recibos Generados para el estudiante:<br><br>';
				$html.='C&oacute;digo:'.$registro[0][1].'<br>';
				$html.='Nombre:'.$registro[0][15].'<br>';
				$html.='<hr class="hr_subtitulo"><br>';
				$html.='</td>';
				$html.='<td rospan="2">';
				$html.='Periodo:<br>'.$periodos;
				$html.='</td>';								
				$html.='</tr>';
				$html.='</table>';
				
				
				
				$html.='<table class="formulario">';
				
				$html.='<tr class="texto_negrita">';
				$html.='<td rowspan="2">';
				$html.='Secuencia';
				$html.='</td>';
				$html.='<td colspan="2">';
				$html.='Ordinario';
				$html.='</td>';
				$html.='<td colspan="2">';
				$html.='Extraordinario';
				$html.='</td>';
				$html.='<td colspan="3">';
				$html.='Estado';
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
				$html.='Recibo';
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
					if($registro[$i][9]=='Activo'){
					$html.='<tr class="texto_subtitulo_verde">';
					}
					if($registro[$i][9]=='Inactivo'){
					$html.='<tr class="texto_subtitulo_rojo">';
					}					
					$html.='<td>';
					$html.=$registro[$i][0];
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
					$html.=$registro[$i][10];
					$html.='</td>';	
					$html.='<td>';
					$html.=$registro[$i][11];
					$html.='</td>';	
					$html.='<td>';
					$html.=$registro[$i][9];
					$html.='</td>';																	
					$html.='</tr>';	
				$i++;	
				}	
								
					$html.='</table>';

					$html.='<input type="hidden" name="estudiante" value="'.$codigo.'">';
					$html.='<input type="hidden" name="action" value="admin_generados">';
					$html.='<input type="hidden" name="opcion" value="generadoEstudiante">';
					$html.='<input type="hidden" name="bla" value="mmmmm">';	
										
					$html.='</form>';				
				echo $html;
			
			}
	}		
	
	
	//Funcion para mostrar el estado de la deuda del estudiante
		
	function multiplesCarreras($configuracion,$registro, $total, $variable)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		//Conexion ORACLE
		$conexion=$this->conectarDB($configuracion,"coordinador");
		?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<td>
								Recibos Generados por Proyecto Curricular<br>
								<hr class="hr_subtitulo">
								</td>
							</tr>
						
							<tr>
								<td>
									<table class='contenidotabla'>
										<tr class='cuadro_color'><td class='cuadro_plano centrar''>Total</td><td class='cuadro_plano centrar'>Proyecto Curricular</td></tr><?
		for($contador=0;$contador<$total;$contador++)
		{
			$variable[0]=$registro[$contador][0];
			$cadena_sql=$this->sql->cadena_sql($configuracion,$conexion, "totalCarreraBloqueado",$variable);
			$registroCarrera=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
			if(is_array($registroCarrera))
			{
				if($registroCarrera[0][0]>0)
				{
					//Con enlace a la busqueda
					$parametro="pagina=admin_generados";
					$parametro.="&hoja=1";
					$parametro.="&opcion=generado";
					$parametro.="&accion=listaCompleta";
					$parametro.="&carrera=".$registro[$contador][0];
					$parametro=$cripto->codificar_url($parametro,$configuracion);
					echo "<tr><td class='cuadro_plano centrar'>".$registroCarrera[0][0]."</td><td class='cuadro_plano'><a href='".$indice.$parametro."'>".$registro[$contador][1]."</a></td></tr>";
				}
				else
				{
					echo "<tr><td class='cuadro_plano centrar'>".$registroCarrera[0][0]."</td><td class='cuadro_plano'>".$registro[$contador][1]."</td></tr>";
				}
			}
			
		}?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<p class="textoNivel0">La tabla anterior muestra el consolidado de recibos que se han generado 
						por parte de los estudiantes.</p>
						<p class="textoNivel0">Por favor realice click sobre el nombre del proyecto curricular que desee revisar.</p>
						</td>
				</tr>
			</tbody>
		</table>
		
		<?
	}
			
		function desbloquearRecibos($configuracion,$conexion)
		{
			foreach($_REQUEST as $clave => $valor) 
			{
				if(substr($clave,0,strlen("codDesbloquear"))=="codDesbloquear")
				{
					
					$variable[0]=$_REQUEST["codDesbloquear_".substr($clave,(strlen("codDesbloquear_")))];
					$variable[1]=$_REQUEST["recDesbloquear_".substr($clave,(strlen("recDesbloquear_")))];
					
					$cadena_sql=$this->sql->cadena_sql($configuracion,$conexion, "desbloquear",$variable);
					$registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "");
					$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
					$variable="pagina=admin_generados";
					$variable.="&opcion=generado";
					$variable.="&accion=listaCompleta";
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
					$cripto=new encriptar();
					$variable=$cripto->codificar_url($variable,$configuracion);
					echo "<script>location.replace('".$pagina.$variable."')</script>"; 
					
				};
				
				
				
			} 	
		
		}
		
		function estadistica($configuracion,$contador)
		{
		
		//Estadisticas de recibos solicitados, impresos, en proceso de impresion, anulados y pagados
		//
		//
		
		//1. Rescatar los consolidados de recibos
		
		$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $valor,"estadistica");
		
		
		?><table style="text-align: left;" border="0"  cellpadding="5" cellspacing="0" class="bloquelateral" width="100%">
			<tr>
				<td >
					<table cellpadding="10" cellspacing="0" align="center">
						<tr class="bloquecentralcuerpo">
							<td valign="middle" align="right" width="10%">
								<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/info.png" border="0" />
							</td>
							<td align="left">
								Actualmente hay <b><? echo $contador ?> usuarios</b> registrados.
							</td>
						</tr>
						<tr class="bloquecentralcuerpo">
							<td align="right" colspan="2" >
								<a href="<?
								echo $configuracion["site"].'/index.php?page='.enlace('admin_dir_dedicacion').'&registro='.$_REQUEST['registro'].'&accion=1&hoja=0&opcion='.enlace("mostrar").'&admin='.enlace("lista"); 
								
								?>">Ver m&aacute;s informaci&oacute;n >></a>
							</td>
						</tr>
					</table> 
				</td>
			</tr>  
		</table>
		<?}
		
		
		
		function calcular_pago($configuracion,$acceso_db, $accesoOracle, $valores)
		{
			//1. Verificar pago inicial y reliquidado
			$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valores,"datosEstudiante");
			$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle);	
			if(is_array($registro))
			{
				
				$valor_matricula=$registro[0][2];
				$valor_reliquidado=$valor_matricula;
				$valor_original=$registro[0][1];
				unset($registro);
				
				//2. Rescatar exenciones del estudiante
				$descripcion="";
				$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $valores,"exencionSolicitud");		
				$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);
				if(is_array($registro))
				{
					
					//3. Calcular el pago de acuerdo a las exenciones y construir las observaciones
					for($i=0;$i<count($registro);$i++)
					{
						$esta_exencion=(100-$registro[$i][7])/100;
						$valor_matricula=$valor_matricula*$esta_exencion;
						$descripcion=$descripcion." ".$registro[$i][8];
					}
					
				}
				$matricula[0]=$valor_matricula;
				$matricula[1]=$descripcion;
				$matricula[2]=$valor_original;
				$matricula[3]=$valor_reliquidado;
				
				return $matricula;
					
			}
			
		
		}
		
		
	function redireccionarInscripcion($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			
			unset($_REQUEST['action']);
			unset($_REQUEST['estudiante']);
			unset($_REQUEST['anno_per']);
			unset($_REQUEST['carrera']);			
			
		$cripto=new encriptar();
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
		switch($opcion)
		{
			case "generadoEstudiante":
				$variable="pagina=admin_generados";
				$variable.="&hoja=1";
				$variable.="&opcion=generadoEstudiante";
				$variable.="&estudiante=".$valor[0];
				$variable.="&carrera=".$valor[1];
				$variable.="&anno_per=".$valor[2];
				unset($valor);
				
			break;	
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);


		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();		
		
	}		
		
}

?>

