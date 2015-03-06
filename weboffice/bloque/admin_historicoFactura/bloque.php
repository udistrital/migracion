<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/****************************************************************************
* @name          bloque.php 
* @revision      Última revisión 05 de mayo de 2009
*****************************************************************************
* @subpackage   admin_recibo
* @package	bloques
* @copyright    
* @version      0.3
* @link		N/D
* @description  Bloque principal para la administración de solicitudes de recibo de pago
*
******************************************************************************/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
//Se incluye para manejar los mensajes de error
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
$conexion=new dbConexion($configuracion);
$accesoOracle=$conexion->recursodb($configuracion,"estudiante");
$enlace=$accesoOracle->conectar_db();

$conexion2=new dbConexion($configuracion);
$accesoGestion=$conexion2->recursodb($configuracion,"recibos");
$enlaceMysql=$accesoGestion->conectar_db();

//Rescatar los datos generales
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosGenerales.class.php");
$datoBasico=new datosGenerales();

$anno=$datoBasico->rescatarDatoGeneral($configuracion, "anno", "", $accesoOracle);
$periodo=$datoBasico->rescatarDatoGeneral($configuracion, "per", "", $accesoOracle);

//Pagina a donde direcciona el menu
$pagina="registro_recibo";

$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
if ($enlace)
{
	if(isset($_REQUEST["opcion"]))
	{
		$nueva_sesion=new sesiones($configuracion);
		$nueva_sesion->especificar_enlace($enlace);
		$esta_sesion=$nueva_sesion->numero_sesion();
		//Rescatar el valor de la variable usuario de la sesion
		$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"usuario");
		if($registro)
		{
			
			$el_usuario=$registro[0][0];
		}
		$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
		if($registro)
		{
			
			$usuario=$registro[0][0];
                        
		}
		$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"identificacion");
		if($registro)
		{
			
			$usuarioIdentificacion=$registro[0][0];
		}
		else
		{
			$usuarioIdentificacion="0";		
		}
		
		
			switch($_REQUEST["opcion"])
			{
				case "historico":
					$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $usuario,"historico");
					break;
				
				case "detallePago":
					$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $usuario,"detallePago");
					break;
					
				case "reciboActual":
					$valor[0]=$usuario;
					$valor[1]=$anno;
					$valor[2]=$periodo;
					$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"recibosActual");
					break;	
				case "diferirMatricula":
					$valor[0]=$usuario;
					$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"validaFechaDiferido");
					break;
							
			}

			$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");		

		
			if($_REQUEST["opcion"]<>"diferirMatricula"){

				if(!is_array($registro))
				{	
					
					$cadena="En la actualidad no tiene ningún recibo registrado para imprimir.";
					$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
					alerta::sin_registro($configuracion,$cadena);	
                                        con_registro_beneficiario($configuracion,$registro,$acceso_db,$accesoOracle,$accesoGestion,$usuario);
							
				}
				else
				{
					$campos=count($registro);
					$variable["pagina"]="registro_recibo";
					$variable["opcion"]=$_REQUEST["opcion"];				
					switch($_REQUEST["opcion"])
					{
						case "historico":
							con_registro_historico($configuracion,$registro,$campos,$tema,$acceso_db);
							break;
						
						case "detallePago":
							con_registro_detalle($configuracion,$registro,$campos,$tema,$acceso_db,$accesoOracle);
							break;
							
						case "reciboActual":
							con_registro_actual($configuracion,$registro,$campos,$tema,$acceso_db,$accesoOracle,$accesoGestion,$usuario,$anno,$periodo);
                                                        con_registro_beneficiario($configuracion,$registro,$acceso_db,$accesoOracle,$accesoGestion,$usuario);
							break;
						
					}
					
					
				}
			}
			else{
				if(!isset($_REQUEST['action'])){
					if($registro[0][0]=='N')
					{	
						unset($valor);
						$valor[0]=$usuario;
						$valor[1]=$anno;
						$valor[2]=$periodo;
						$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"fechasDiferido");
						$registrofec=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
						
						$cadena="Las fechas para diferir su matrícula estarán habilitadas desde el ".$registrofec[0][0]." hasta el ".$registrofec[0][1]." .";
						//$cadena="En la actualidad no se encuentran habilitadas las fechas para diferir su matricula.";
						$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
						alerta::sin_registro($configuracion,$cadena);	
					}
					else
					{

						$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"nivelCarrera");
						$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
	
						if($registro[0][0]=="PREGRADO"){
							diferir_matricula($configuracion,$valor,$accesoOracle);
						}else{
							$cadena="Este procedimiento aplica solo para estudiantes de pregrado";
							$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
							alerta::sin_registro($configuracion,$cadena);	
						}	
					}
				}
				else{
					$valor[1]=$_REQUEST['diferido'];
					guardar_diferido($configuracion,$valor,$accesoOracle);
				}
			}
		
	}
	
}



/****************************************************************
*  			Funciones				*
****************************************************************/

function diferir_matricula($configuracion,$valor,$accesoOracle){

	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"detalleDiferido");
	$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");

        $html='';
	//el registr[0][0]  si el estudiante tiene o no su matricula diferida
	//el registr[0][1]  si el estudiante tiene o no derecho a diferir su matricula

		if($registro[0][1]=='N')
		{	
			
			$cadena="No puede diferir matrícula porque es menor a medio salario mínimo legal vigente.";
			$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
			alerta::sin_registro($configuracion,$cadena);	
		}
		else
		{	
			$html.='<form enctype="multipart/form-data" method="POST" action="index.php" name="admin_historicoFactura">';
			$html.='<br><br><table  class="formulario"  align="center">';
			$html.='<tr class="bloquecentralcuerpobeige">';
			$html.='<td>';
			$html.='Desea diferir su matr&iacute;cula?';
			$html.='</td>';

				$base=array(array('S','S'),array('N','N'));
				$select=new html();
				$opcion=$select->cuadro_lista($base,"diferido",$configuracion,$registro[0][0],1,100);
			$html.='<td>';
			$html.=$opcion;	
			$html.='</td>';
			$html.='</tr>';

			if($registro[0][0]=='S'){
				$mensaje='Su matr&iacute;cula para el pr&oacute;ximo semestre esta diferida.';
			}else{
				$mensaje='Su matr&iacute;cula para el pr&oacute;ximo semestre no esta diferida.';
			}

			$html.='<tr class="bloquecentralcuerpobeige">';
			$html.='<td class="cuadro_plano cuadro_brown"><br>';
			$html.=$mensaje;
			$html.='<br></td>';
			$html.='</tr>';

			$html.='</table>';
			$html.='<input type="hidden" value="admin_historicoFactura" name="action"/>';
			$html.='<input type="hidden" value="diferirMatricula" name="opcion"/>';
			$html.='</form>';
		}

	$html.='<br><center>';
	$html.='<input type="button" style="cursor: pointer;" title="Información del diferido de la matricula" onclick="javascript:window.open(\'http://oasdes.udistrital.edu.co/development/desarrolloweb/estudiantes/ay_inf_diferido.php\', \'yes\', \'width=550,height=450,scrollbars=YES\');" value="M&aacute;s Informaci&oacute;n"/>';
	//$html.='<input type="button" style="cursor: pointer;" title="Deudor" onclick="javascript:popUpWindow(\'est_deudor.php\', \'no\', 200, 200, 750, 400)" value="Deudor" name="Deudor"/>';
	//$html.='<input type="button" style="cursor: pointer;" title="Observaciones" onclick="javascript:popUpWindow(\'est_observaciones.php\', \'no\', 200, 200, 750, 400)" value="Observaciones" name="Observaciones"/>';
	$html.='</center>';

	echo $html;



}


function guardar_diferido($configuracion,$valor,$accesoOracle){

	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"actualizaDiferido");
	$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"");

	
	redireccionarInscripcion($configuracion,"adminPago",$valor);
		/*$cadena=$cadena_sql;
		$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
		alerta::sin_registro($configuracion,$cadena);	*/
}

function redireccionarInscripcion($configuracion, $opcion, $valor="")
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	unset($_REQUEST['action']);
	$cripto=new encriptar();
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
	
	switch($opcion)
	{
		case "adminPago":

			$variable="pagina=adminPago";
			$variable.="&opcion=diferirMatricula";
		break;	
	}
	
	$variable=$cripto->codificar_url($variable,$configuracion);
	echo "<script>location.replace('".$indice.$variable."')</script>"; 
	exit();		
	
}
	

function mensaje()
{
switch($_REQUEST["opcion"])
			{
				case "historico":
				?>.:: Hist&oacute;rico de Pago de Matr&iacute;cula
<hr class="hr_subtitulo">
	<table align="center" class="tablaMarcoGeneral">
	<tbody>
		<tr>
			<td >
				<table class="tablaMarco">
					<tbody>
						<tr class="bloquecentralcuerpo">
							<td valign="top" colspan=2>
							<p>La siguiente tabla muestra su historial de pagos. Est&aacute; construida a partir de los registros de pago que diariamente el 
							banco envia a la Universidad. Con estos datos la instituci&oacute;n comprueba que las entidades
							financieras efectivamente recaudan el valor que usted como estudiante debe pagar.</p>
							<p>Cualquier diferencia entre el valor aqui reportado y el valor que usted a consignado por favor
							rep&oacute;rtelo para tomar las medidas correspondientes.
							</p>								
							
						</tr>
					</tbody>
				</table>

			</td>
		</tr>
	</tbody>
</table><?
					break;
			}

}

function con_registro_historico($configuracion,$registro,$campos,$tema,$acceso_db)
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
	setlocale(LC_MONETARY, 'en_US');
	
?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
	<tbody>
		<tr>
			<td >
				<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
					<tr class="texto_subtitulo">
						<td>
						<? mensaje(); ?>
						</td>
					</tr>
					<tr>
						<td>		
							<table class="contenidotabla">
								<tr class="cuadro_color">
									<td class="cuadro_plano centrar">
									Recibo de Pago
									</td>
									<td class="cuadro_plano centrar">
									Banco
									</td>
									<td class="cuadro_plano centrar">
									Sucursal
									</td>
									<td class="cuadro_plano centrar">
									Fecha de pago
									</td>
									<td colspan="2" class="cuadro_plano centrar">
									Valor Pagado
									</td>
								</tr>	
					<?
	$totalPago=0;
	for($contador=0;$contador<$campos;$contador++)
	{
			$totalPago+=$registro[$contador][4];
				?>	
								<tr>
									<td class="cuadro_plano centrar">
									<span class="texto_negrita"><? echo $registro[$contador][5]?></span>
									</td>
									<td class="cuadro_plano centrar">
									<?echo $registro[$contador][1] ?>
									</td>
									<td class="cuadro_plano centrar">
									<?echo $registro[$contador][2] ?>
									</td>
									<td class="cuadro_plano centrar">
									<?echo $registro[$contador][3] ?>	
									</td>
									<td class="cuadro_plano derecha">
									<?echo money_format('$ %!.0i',$registro[$contador][4]) ?>
									</td>
								</tr>
	<?
	}
								
	?>							<tr class="cuadro_color">
									<td colspan=4  class="cuadro_plano derecha">
										<span class="texto_negrita">Valor Total Registrado</span>
									</td>
									<td  class="cuadro_plano derecha">
										<?echo money_format('$ %!.0i',$totalPago) ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table><?
}

function con_registro_actual($configuracion,$registro,$campos,$tema,$acceso_db,$accesoOracle,$accesoGestion,$usuario,$anno,$periodo)
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
	setlocale(LC_MONETARY, 'en_US');
	
?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
	<tbody>
		<tr>
			<td >
				<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
					<tr class="texto_subtitulo">
						<td>
						<span  class="texto_subtitulo">.:: Recibos de Pago Actuales</span>
						<hr class="hr_subtitulo">
						</td>
					</tr>
					<tr>
						<td><?
							for($contador=0;$contador<$campos;$contador++)
							{
								?>		<table class="contenidotabla">
												<tr>
													<td class="cuadro_color cuadro_plano centrar" colspan="2">
													<span class="texto_negrita">Comprobante de Pago</span>
													</td>
													<td class="cuadro_plano centrar"><?
													//echo "<br>->imp".$registro[$contador][12];
																										
													if($registro[$contador][12]==1)//Recibo bloqueado
													{
														//Verificar el estado del estudiante
														
														$estado=verificarEstado($configuracion,$accesoOracle,$registro[$contador][1]);
														
														
														if($estado==TRUE)
														{
															
															//Desbloquear Recibo
															$variable=array($registro[$contador][0],$registro[$contador][1]);
															desbloquearRecibo($configuracion,$accesoOracle,$variable);
															$registro[$contador][12]=0;
														}
														//echo "<br>->est=(".$estado.")";
													
													}
		
													if($registro[$contador][12]<>1)//Recibo desbloqueado
													{
														$reciboanterior=$registro[$contador][7]-1;
                                                                                                                $indiceReciboAnterior=obtenerIndiceReciboAnterior($registro,$reciboanterior);
                
														if($registro[$contador][7]>1 && $registro[$indiceReciboAnterior][13]=='N')
														{
															echo "Para descargar este recibo, primero debe pagar el recibo correspondiente a la <b>cuota No. ".$reciboanterior."</b><br>";
														}
														else
														{
                                                                                                                    $datosRegistro=array(   'anio'=>$registro[$contador][5],
                                                                                                                                            'periodo'=>$registro[$contador][6],
                                                                                                                                            'usuario'=>$usuario,
                                                                                                                                            'tipo_usuario'=>51,
                                                                                                                                            'tipo_terminos'=>1,
                                                                                                                                            'anio_pago'=>$registro[$contador][14],
                                                                                                                                            'periodo_pago'=>$registro[$contador][15],
                                                                                                                                            
                                                                                                                        );
                                                                                                                    $aceptacion = consultarAceptacionDeTerminos($configuracion,$datosRegistro,$accesoGestion);
                                                                                                                    $recibo_matricula = validarReciboMatricula($configuracion,$registro[$contador],$accesoOracle);
                                                                                                                    if(((isset($aceptacion[0][5])?$aceptacion[0][5]:'')==1 && $anno==$datosRegistro['anio_pago'] && $periodo==$datosRegistro['periodo_pago'] ) || $recibo_matricula<>'ok'|| $registro[$contador][13]=='S'  || ($anno.$periodo<>$datosRegistro['anio_pago'].$datosRegistro['periodo_pago'] )){
                                                                                                                                  ?>
                                                                                                                                  <a target="_blank" href="<?		
															  $variable="pagina=imprimirFactura";
															  //Codigo del Estudiante
															  $variable.="&opcion=imprimir";
															  $variable.="&no_pagina=true";
															  $variable.="&factura=".$registro[$contador][0];
															  $variable=$cripto->codificar_url($variable,$configuracion);
															  echo $indice.$variable;		
															  ?>">
															  <img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfPequenno.png"?>" />
                                                                                                                                  Recibo PDF</a>
                                                                                                                                  <?
                                                                                                                        
                                                                                                                        }else{
                                                                                                                                    ?>
                                                                                                                                  <a href="<?		
                                                                                                                                  $pagina = $configuracion["host"] . "/academicopro/index.php?";
                                                                                                                                    $variable = "pagina=registro_aceptarTerminosMatricula";
                                                                                                                                    $variable.="&opcion=confirmacion";
                                                                                                                                    $variable.="&action=loginCondor";
                                                                                                                                    $variable.="&tipoUser=51";
                                                                                                                                    $variable.="&modulo=Estudiante";
                                                                                                                                    $variable.="&aplicacion=Condor";
                                                                                                                                    $variable.="&usuario=".$usuario;
                                                                                                                                    $variable.="&anio_recibo=".$registro[$contador][5];
                                                                                                                                    $variable.="&per_recibo=".$registro[$contador][6];
                                                                                                                                    $variable=$cripto->codificar_url($variable,$configuracion);
                                                                                                                                  echo $pagina.$variable;		
                                                                                                                                  ?>"><font color='red'>
                                                                                                                                  Para descargar el recibo debe Aceptar los T&eacute;rminos y condiciones de Recibo</a></font>
                                                                                                                                  <?
														}
                                                                                                                    
                                                                                                                          
													}
													}
													else
													{ //Recibo Bloqueado
														$verificarEstado=TRUE;
														$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $registro[0][1],"deudaEstudiante");
														$rescatarDeuda=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
														
														if(!is_array($rescatarDeuda)){
													?>
																				
																<b>RECIBO BLOQUEADO</b><br>POR ESTADO PRUEBA ACADEMICA
																<br>
																<a href="<?		
																  $variable="pagina=adminReciboAceptaAcuerdo";
																  $variable.="&opcion=diferirMatricula";
																  $variable=$cripto->codificar_url($variable,$configuracion);
																  echo $indice.$variable;		
																  ?>">
																  <img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/kwrite.png"?>" /><br/>
																  <b>Ingrese aqui para Desbloquear</b></a><br/><br/>
					
														<?}else{
																?>
																<b>RECIBO BLOQUEADO</b><br>POR REGISTRAR DEUDAS VIGENTES
																<br>
																Consulte a la Biblioteca o Laboratorios seg&uacute;n sea el caso.<br>
																
																
																<?
														}
													}
													
													?></td>
												</tr>
												<tr>
													<td  class="cuadro_plano centrar" colspan="2">
													<span class="texto_negrita">Cuota No  <? echo $registro[$contador][7] ?></span>
													</td>
													<td class="cuadro_plano centrar">
													Periodo generado: <?echo $registro[$contador][5] ?> - <?echo $registro[$contador][6] ?><br>
													Periodo al que corresponde el recibo: <?echo $registro[$contador][14] ?> - <?echo $registro[$contador][15] ?>
													</td>
												</tr>	
												<tr class="cuadro_color">
													<td class="cuadro_plano centrar">
													Tipo de pago
													</td>
													<td class="cuadro_plano centrar">
													Fecha Pago
													</td>
													<td class="cuadro_plano centrar">
													Total a Pagar
													</td>
												</tr>	
												<?
												//Valido que el recibo sea de matrícula o por otra referencia
												unset($valor);
												$valor[0]=$registro[$contador][1];
												$valor[1]=$registro[$contador][5];
												$valor[2]=$registro[$contador][6];
												$valor[3]=$registro[$contador][0];
												$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"recibosActualEcaes");
												//echo $cadena_sql;
												$registroRef=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");
												$totreg=count($registroRef);
												//echo "nnnn".$registroRef[0][0]."<br>";
												
												for($i=0;$i<$totreg;$i++)
												{
													if ($registroRef[$i][0]==14)
													{
														echo '<tr> ';
														echo '	<td class="cuadro_plano centrar"> ';
														echo '	<span class="texto_negrita">ECAES</span> ';
														echo '	</td> ';
														echo '	<td class="cuadro_plano centrar"> ';
														echo		$registro[$i][10];
														echo '	</td> ';
														echo '	<td class="cuadro_plano centrar"> ';
														echo		money_format('$ %!.0i',$registroRef[$i][1]);
														echo '	</td> ';
														echo '</tr> ';
													}
													else
													{
														echo '<tr>';
														echo '	<td class="cuadro_plano centrar">';
														echo '	<span class="texto_negrita">Ordinario</span>';
														echo '	</td>';
														echo '	<td class="cuadro_plano centrar">';
														echo	 $registro[$contador][10];
														echo '	</td>';
														echo '	<td class="cuadro_plano centrar">';
														echo 	money_format('$ %!.0i',$registro[$contador][3]); echo ' + seguro';
														echo '	</td>';
														echo '</tr>';
														echo '<tr>';
														echo '	<td class="cuadro_plano centrar">';
														echo '	<span class="texto_negrita">Extraordinario</span>';
														echo '	</td>';
														echo '	<td class="cuadro_plano centrar">';
														echo 	$registro[$contador][11];
														echo '	</td>';
														echo '	<td class="cuadro_plano centrar">';
														echo 	money_format('$ %!.0i',$registro[$contador][4]); echo ' + Seguro';
														echo '	</td>';
														echo '</tr>';
													}
												}
												?>
                                                                                                <tr>
                                                                                                    <td colspan='3' class="cuadro_plano centrar">
                                                                                                       <?
                                                                                                       
                                                                                                                        if(((isset($aceptacion[0][5])?$aceptacion[0][5]:'')==1 && $anno==$datosRegistro['anio_pago'] && $periodo==$datosRegistro['periodo_pago'] ) || $recibo_matricula<>'ok' || $registro[$contador][13]=='S' || ($anno.$periodo<>$datosRegistro['anio_pago'].$datosRegistro['periodo_pago'] )){
                                                                                                                   
                                                                                                                            enlacePagoEnLinea($configuracion,$registro,$contador);

                                                                                                                        }else{
                                                                                                                                    ?>
                                                                                                                                  <a href="<?		
                                                                                                                                  $pagina = $configuracion["host"] . "/academicopro/index.php?";
                                                                                                                                    $variable = "pagina=registro_aceptarTerminosMatricula";
                                                                                                                                    $variable.="&opcion=confirmacion";
                                                                                                                                    $variable.="&action=loginCondor";
                                                                                                                                    $variable.="&tipoUser=51";
                                                                                                                                    $variable.="&modulo=Estudiante";
                                                                                                                                    $variable.="&aplicacion=Condor";
                                                                                                                                    $variable.="&usuario=".$usuario;
                                                                                                                                    $variable.="&anio_recibo=".$registro[$contador][5];
                                                                                                                                    $variable.="&per_recibo=".$registro[$contador][6];
                                                                                                                                    $variable=$cripto->codificar_url($variable,$configuracion);
                                                                                                                                  echo $pagina.$variable;		
                                                                                                                                  ?>"><font color='red'>
                                                                                                                                  Para Pagar el recibo debe Aceptar los T&eacute;rminos y condiciones de Recibo
                                                                                                                                  </font></a>
                                                                                                                                  <?
                                                                                                                        }  
                                                                                                         
                                                                                                       ?>
                                                                                                    </td>
                                                                                                </tr>						
											</table>
											<hr class="hr_subtitulo">
							<?
							}
								
	?>						
						</td>
					</tr>
				</table><?
	
	if(1)
	{
	
		//Buscar estado
		$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $registro[0][1],"infoEstudiante");
		//echo $cadena_sql;
		$registroEstudiante=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
		if(is_array($registroEstudiante))
		{

		
		
		
		
		
			echo '<br>';
			echo '<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >';
			echo '	<tr class="texto_subtitulo">';
			echo '		<td>';
			echo '			<span  class="texto_subtitulo">.:: Estado Actual</span>';
			echo '			<hr class="hr_subtitulo">';
			echo '		</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<td class="cuadro_color cuadro_plano centrar">';
			echo $registroEstudiante[0][6];
			echo '		</td>';
			echo '	</tr>';
			if($registroEstudiante[0][5]=='J'||$registroEstudiante[0][5]=='B'){
			echo '	<tr>';
			echo '		<td class="cuadro_color cuadro_plano centrar">';
			//echo '<a href="documento/formato_pa.pdf">> <b>DESCARGAR FORMATO PRUEBA ACAD&Eacute;MICA</b> <</a>';
			echo '		</td>';
			echo '	</tr>';
			}			
			
			echo '</table>';
			
			
		}		
			
		//Buscar deudas
		$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $registro[0][1],"deudaEstudiante");
		//echo $cadena_sql;
		$registroDeuda=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
		if(is_array($registroDeuda))
		{?>	<br>
			<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
				<tr class="texto_subtitulo">
					<td>
					<span  class="texto_subtitulo">.:: Deudas Registradas</span>
					<hr class="hr_subtitulo">
					</td>
				</tr>
				<tr>
					<td><?
			$contador=0;			
			while($registroDeuda[$contador][0])
			{
				?>		<table class="contenidotabla">
								<tr>
									<td class="cuadro_color cuadro_plano centrar">
									<? echo $registroDeuda[$contador][7] ?>
									</td>
									<td class="cuadro_color cuadro_plano centrar">
									<? echo $registroDeuda[$contador][2] ?>
									</td>
								</tr>
						</table>
						<hr class="hr_subtitulo">
					</td>
				</tr>
			</table>	
			<?
				$contador++;
			}
										
			
		}
	
	}	?></td>
		</tr>
	</tbody>
</table>

			<br>
		
			<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
				<tr>
					<td class="cuadro_azul cuadro_plano centrar">
						<b>¡ PAGUE &Uacute;NICAMENTE EN BANCO DE OCCIDENTE !</b>
					</td>
				</tr>
			</table>
                        <?
                        
}

function con_registro_detalle($configuracion,$registro,$campos,$tema,$acceso_db,$accesoOracle)
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
	setlocale(LC_MONETARY, 'en_US');
	
	//Buscar descuentos
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $registro[0][0],"descuentos");
	$registroCertificado=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
	
	//Buscar Exenciones
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $registro[0][0],"exencion");
	$registroExencion=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
					
					
	
?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
	<tbody>
		<tr>
			<td >
				<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
					<tr>
						<td>
						<span  class="texto_subtitulo">.:: Detalles del Valor de Matr&iacute;cula</span>
						<hr class="hr_subtitulo">
						</td>
					</tr>
					<tr>
						<td>		
							<table align="center" class="contenidotabla2">
								<tr class="cuadro_color">
									<td class="cuadro_plano centrar">
									Detalle
									</td>
									<td class="cuadro_plano centrar">
									Valor
									</td>
								</tr>	
								<tr>
									<td class="cuadro_plano derecha">
									<span class="texto_negrita">Matr&iacute;cula</span>
									</td>
									<td class="cuadro_plano centrar">
									<? echo money_format('$ %!.0i',$registro[0][4]) ?>
									</td>
								</tr><?
								
								if($registro[0][4] != $registro[0][1])
								{
								?><tr>
									<td class="cuadro_plano derecha">
									<span class="texto_negrita">Matr&iacute;cula Reliquidada</span>
									</td>
									<td class="cuadro_plano centrar">
									<? echo money_format('$ %!.0i',$registro[0][1]) ?>
									</td>
								</tr><?
								}
							?>
								<tr>
									<td class="cuadro_plano derecha">
									<span class="texto_negrita">Seguro Estudiantil*</span>
									</td>
									<td class="cuadro_plano centrar">
									<? echo money_format('$ %!.0i',$registro[0][2]) ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
						<span  class="texto_subtitulo">.:: Descuentos</span>
						<hr class="hr_subtitulo">
						<p class="bloquecentralcuerpo">Con las Leyes 403 de 1997 y 815 de 2003: "se otorga el descuento del diez por ciento (10%) en el valor de la 
						matr&iacute;cula a que tiene derecho el estudiante de Instituci&oacute;n Oficial de Educaci&oacute;n Superior, 
						como beneficio por el ejercicio del sufragio..".</p>
						</td>
					</tr>
					<tr>
						<td class="centrar"><?
					if(is_array($registroCertificado))
					{	
						
					?>		<table align="center" class="contenidotabla2">
								<tr class="cuadro_color">
									<td class="cuadro_plano centrar">
									Detalle
									</td>
									<td class="cuadro_plano centrar">
									Valor
									</td>
								</tr>	
								<tr>
									<td class="cuadro_plano derecha">
									<span class="texto_negrita">Descuento por Certificado Electoral</span>
									</td>
									<td class="cuadro_plano centrar">
									10%
									</td>
								</tr>
							</table><?
					}
					else
					{?><table align="center" class="contenidotabla2">
							<tr class="cuadro_color">
								<td  class="cuadro_plano centrar">
								Actualmente no tiene registrado el Certificado Electoral. <br>El descuento no aplica a su matr&iacute;cula
								</td>
							</tr>
						</table>
					<?}	?></td>
					</tr>
										<tr>
						<td>
						<span  class="texto_subtitulo">.:: Exenciones</span>
						<hr class="hr_subtitulo">
						<p class="bloquecentralcuerpo">Teniendo como base el acuerdo 004 del 25 de enero del 2006 la Universidad reconoce a sus estudiantes
						algunas exenciones en la matr&iacute;cula.</p>
						</td>
					</tr>
					<tr>
						<td class="centrar"><?
					if(is_array($registroExencion) && $registroExencion[0][2]>0 )
					{	
						
					?>		<table align="center" class="contenidotabla2">
								<tr class="cuadro_color">
									<td class="cuadro_plano centrar">
									Motivo Exenci&oacute;n
									</td>
									<td class="cuadro_plano centrar">
									Porcentaje
									</td>
								</tr>	
								<tr>
									<td class="cuadro_plano derecha">
									<span class="texto_negrita"><? echo $registroExencion[0][1] ?></span>
									</td>
									<td class="cuadro_plano centrar">
									<? echo $registroExencion[0][2] ?>
									</td>
								</tr>
							</table><?
					}
					else
					{?><table align="center" class="contenidotabla2">
							<tr class="cuadro_color">
								<td  class="cuadro_plano centrar">
								Actualmente no tiene registrada ninguna exenci&oacute;n.
								</td>
							</tr>
						</table>
					<?}	?></td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table><?
}

function cadena_busqueda_recibo($configuracion, $acceso_db, $valor,$opcion="")
{
	$valor=$acceso_db->verificar_variables($valor);
	
	switch($opcion)
	{

		case "nivelCarrera":
			$cadena_sql="select tra_nivel ";
			$cadena_sql.="from actipcra,accra,acest ";
			$cadena_sql.="where tra_cod= cra_tip_cra ";
			$cadena_sql.="and cra_cod= est_cra_cod ";
			$cadena_sql.="and est_cod=".$valor[0];
			break;

		case "actualizaDiferido":
			$cadena_sql="UPDATE acest ";
			$cadena_sql.="SET est_diferido='".$valor[1]."' ";
			$cadena_sql.="WHERE est_cod=".$valor[0];
			break;

		case "validaFechaDiferido":
			$cadena_sql="SELECT fua_verifica_fecha(est_cra_cod::smallint,10::smallint) ";
			$cadena_sql.="FROM acest ";
			$cadena_sql.="WHERE est_cod=".$valor[0];
			break;
		case "detalleDiferido":
			$cadena_sql="SELECT est_diferido,fua_verifica_diferido(est_cod) ";
			$cadena_sql.="FROM acest ";
			$cadena_sql.="WHERE est_cod=".$valor[0];
			break;
		case "historico":
			$cadena_sql="SELECT ";
			$cadena_sql.="RBA_BAN_COD, ";
			$cadena_sql.="BAN_NOMBRE, ";
			$cadena_sql.="RBA_OFICINA, ";
			$cadena_sql.="RBA_DIA||' de '||MES_ABREV||' de '||RBA_ANO FECHA, ";
			$cadena_sql.="RBA_VALOR, ";
			$cadena_sql.="RBA_SECUENCIA ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACRECBAN, ";
			$cadena_sql.="ACBANCO, ";
			$cadena_sql.="GEMES ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="RBA_COD = ".$valor." ";
			$cadena_sql.="AND ";
			$cadena_sql.="BAN_COD = RBA_BAN_COD ";
			$cadena_sql.="AND ";
			$cadena_sql.="BAN_ESTADO = 'A' ";
			$cadena_sql.="AND ";
			$cadena_sql.="MES_COD = RBA_MES ";
			$cadena_sql.="ORDER BY 3,4,5 DESC";
			break;
			
		case "detallePago":
			$cadena_sql="SELECT ";
			$cadena_sql.="emb_est_cod, ";
			$cadena_sql.="emb_valor_matricula, ";
			$cadena_sql.="vlr_seguro, ";
			$cadena_sql.="est_nombre, ";
			$cadena_sql.="est_valor_matricula ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACEST, ";
			$cadena_sql.="V_ACESTMATBRUTO,";
			$cadena_sql.="v_valor_seguro ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="emb_est_cod =".$valor." ";
			$cadena_sql.="AND ";
			$cadena_sql.="emb_est_cod = est_cod ";
			break;
			
		case "descuentos":
			$cadena_sql="SELECT ";
			$cadena_sql.="cer_est_cod ";
			$cadena_sql.="FROM ";
			$cadena_sql.="accerelectoral ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="cer_est_cod = $valor";
			break;			
		
		case "exencion":
			$cadena_sql="SELECT ";
			$cadena_sql.="emb_est_cod, ";
			$cadena_sql.="Pck_Pr_Detalle_Matricula.Fua_Ver_Motivo_Exento(emb_est_cod) mot_exe, ";
			$cadena_sql.="Pck_Pr_Detalle_Matricula.Fua_Ver_Valor_Exepcion(emb_est_cod) val_exe ";
			$cadena_sql.="FROM ";
			$cadena_sql.="V_ACESTMATBRUTO ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="emb_est_cod = $valor";
			break;	
			
			
		case "recibosActual":
			$cadena_sql="SELECT ";
			$cadena_sql.="ema_secuencia, ";
			$cadena_sql.="ema_est_cod, ";
			$cadena_sql.="ema_cra_cod, ";
			$cadena_sql.="ema_valor, ";
			$cadena_sql.="ema_ext, ";
			$cadena_sql.="ema_ano, ";
			$cadena_sql.="ema_per, ";
			$cadena_sql.="ema_cuota, ";
			$cadena_sql.="ema_fecha, ";
			$cadena_sql.="ema_estado, ";
			$cadena_sql.="TO_CHAR(EMA_FECHA_ORD, 'DD-Mon-YYYY'), ";
			$cadena_sql.="TO_CHAR(EMA_FECHA_EXT, 'DD-Mon-YYYY'), ";
			$cadena_sql.="EMA_IMP_RECIBO, ";
			$cadena_sql.="ema_pago, "; //13
			$cadena_sql.="ema_ano_pago, ";
			$cadena_sql.="ema_per_pago ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACESTMAT ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="EMA_EST_COD = ".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="EMA_ANO = ".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="EMA_PER = ".$valor[2]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="EMA_ESTADO='A'";
			$cadena_sql.="ORDER BY ema_cuota asc";
// 			$cadena_sql.="AND ";
// 			$cadena_sql.="EMA_SECUENCIA<25246 ";
				
			break;
			
		case "recibosActualEcaes":
			$cadena_sql="SELECT ";
			$cadena_sql.="AER_REFCOD, ";
			$cadena_sql.="AER_VALOR ";	
			$cadena_sql.="FROM ";
			$cadena_sql.="ACESTMAT, ACREFEST ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="EMA_EST_COD = ".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="EMA_ANO = ".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="EMA_PER = ".$valor[2]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="EMA_SECUENCIA = AER_SECUENCIA ";
			$cadena_sql.="AND ";
			$cadena_sql.="AER_ANO = ".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="AER_SECUENCIA = ".$valor[3]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="EMA_ESTADO='A' ";
			$cadena_sql.="AND ";
			$cadena_sql.="AER_REFCOD NOT IN (2,3,4)";	
			break;
			
		case "infoEstudiante":
			//En ORACLE
			$cadena_sql="SELECT ";
			$cadena_sql.="est_cod, ";
			$cadena_sql.="est_nro_iden, ";
			$cadena_sql.="est_nombre, ";
			$cadena_sql.="est_cra_cod, ";
			$cadena_sql.="est_diferido, ";
			$cadena_sql.="est_estado_est, ";
			$cadena_sql.="estado_descripcion ";
			$cadena_sql.="FROM ";
			$cadena_sql.="acest, ";
			$cadena_sql.="acestado ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="est_cod =".$valor." ";
			$cadena_sql.="AND  ";
			$cadena_sql.="estado_cod = est_estado_est ";
			
			
			break;
			
		case "deudaEstudiante":
			//En ORACLE
			$cadena_sql="SELECT ";
			$cadena_sql.="deu_est_cod, ";
			$cadena_sql.="deu_cpto_cod, ";
			$cadena_sql.="deu_material, ";
			$cadena_sql.="deu_ano, ";
			$cadena_sql.="deu_per, ";
			$cadena_sql.="deu_estado, ";
			$cadena_sql.="deu_estado, ";
			$cadena_sql.="cpto_nombre ";
			$cadena_sql.="FROM ";
			$cadena_sql.="acconcepto, ";
			$cadena_sql.="acdeudores ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="deu_est_cod =".$valor." ";
			$cadena_sql.="AND ";
			$cadena_sql.="cpto_cod = deu_cpto_cod";
			
			break;
			
		case "desbloquear":
			$cadena_sql="UPDATE ";
			$cadena_sql.="ACESTMAT ";
			$cadena_sql.="SET ";
			$cadena_sql.="EMA_IMP_RECIBO = 0 ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="ema_secuencia = ".$valor[0]." ";
			$cadena_sql.="AND ";				
			$cadena_sql.="ema_est_cod = ".$valor[1]." ";
				
			break;
		
		case "fechasDiferido":
			$cadena_sql="SELECT ";
			$cadena_sql.="TO_CHAR(ace_fec_ini,'DD/MM/YYYY'), ";
			$cadena_sql.="TO_CHAR(ace_fec_fin,'DD/MM/YYYY') ";
			$cadena_sql.="FROM acest, accaleventos ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="est_cra_cod=ace_cra_cod ";
			$cadena_sql.="AND ";
			$cadena_sql.="est_cod=".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="ace_anio= ".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="ace_periodo= ".$valor[2]." ";	
			$cadena_sql.="AND ";
			$cadena_sql.="ace_cod_evento=10 ";
			break;
						
                case 'AceptacionDeTerminos':
                        $cadena_sql="SELECT ";
                        $cadena_sql.=" con_anio,";
                        $cadena_sql.=" con_per,";
                        $cadena_sql.=" con_codigo_usuario,";
                        $cadena_sql.=" con_tpc_id,";
                        $cadena_sql.=" con_usutipo_cod,";
                        $cadena_sql.=" con_aceptacion,";
                        $cadena_sql.=" con_fecha,";
                        $cadena_sql.=" con_estado";
                        $cadena_sql.=" FROM  sga_acepta_condiciones";
                        $cadena_sql.=" WHERE con_anio='".$valor['anio']."'";
                        $cadena_sql.=" AND con_per='".$valor['periodo']."'";
                        $cadena_sql.=" AND con_codigo_usuario='".$valor['usuario']."'";
                        $cadena_sql.=" AND con_usutipo_cod='".$valor['tipo_usuario']."'";
                        $cadena_sql.=" AND con_tpc_id='".$valor['tipo_terminos']."'";
                        $cadena_sql.=" AND con_estado='1'";

                        break;
                    
                case 'conceptosRecibo':
                        $cadena_sql="SELECT ";
                        $cadena_sql.=" aer_ano,";
                        $cadena_sql.=" aer_secuencia,";
                        $cadena_sql.=" aer_bancod,";
                        $cadena_sql.=" aer_refcod,";
                        $cadena_sql.=" aer_valor";
                        $cadena_sql.=" FROM  acrefest";
                        $cadena_sql.=" WHERE aer_ano='".$valor['anio']."'";
                        $cadena_sql.=" AND aer_secuencia='".$valor['secuencia']."'";
                        break;
            
                case 'periodoAnterior':
                        $cadena_sql="SELECT ";
                        $cadena_sql.=" ape_ano, ";
                        $cadena_sql.=" ape_per ";
                        $cadena_sql.=" FROM acasperi";
                        $cadena_sql.=" WHERE ape_estado='P'";
                        break;
            
         
                case 'identificacionEstudiante':
                        $cadena_sql="SELECT ";
                        $cadena_sql.=" est_nro_iden ";
                        $cadena_sql.=" FROM  acest";
                        $cadena_sql.=" WHERE est_cod='".$valor."'";
                        break;
            
         
                case 'beneficiarioMatriculaMonitoria':
                        $cadena_sql="SELECT ";
                        $cadena_sql.=" bmm_anio,";
                        $cadena_sql.=" bmm_per,";
                        $cadena_sql.=" bmm_nro_iden,";
                        $cadena_sql.=" bmm_nombre_beneficiario,";
                        $cadena_sql.=" bmm_concepto,";
                        $cadena_sql.=" bmm_pin,";
                        $cadena_sql.=" bmm_vlr_bruto,";
                        $cadena_sql.=" bmm_ordenador,";
                        $cadena_sql.=" bmm_estado";
                        $cadena_sql.=" FROM  sga_beneficiarios_mat_mon";
                        $cadena_sql.=" WHERE bmm_anio='".$valor['anio']."'";
                        $cadena_sql.=" AND bmm_per='".$valor['periodo']."'";
                        $cadena_sql.=" AND bmm_nro_iden='".$valor['identificacion']."'";
                        break;
            
         
		default:
			$cadena_sql="";
			break;
	}
	//echo $cadena_sql."<br>";
	return $cadena_sql;
}

function ejecutar_admin_recibo($cadena_sql,$conexion,$tipo)
{
	//echo $cadena_sql;
	$resultado= $conexion->ejecutarAcceso($cadena_sql,$tipo);
	return $resultado;
}



function verificarEstado($configuracion,$accesoOracle,$valor)
{
	
	//Buscar estado
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"infoEstudiante");
	//echo $cadena_sql;
	$registroEstudiante=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
	if(is_array($registroEstudiante))
	{
		if($registroEstudiante[0][5]=="J")
		{
			return FALSE;
		}
	}
	else
	{
		return TRUE;
	
	}		
			
	//Buscar deudasselect * from acestmat where ema_est_cod=20032025075
	$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valor,"deudaEstudiante");

	$registroDeuda=ejecutar_admin_recibo($cadena_sql,$accesoOracle,"busqueda");	
	
	if(is_array($registroDeuda))
	{
		return FALSE;
	}
		//echo $cadena_sql;
	return TRUE;
									
}

function desbloquearRecibo($configuracion,$conexion,$variable)
{
	$cadena_sql=cadena_busqueda_recibo($configuracion,$conexion,$variable,"desbloquear");
	
	
	$resultado=ejecutar_admin_recibo($cadena_sql,$conexion, "");
	return $resultado;
}

        /**
 * Función que muestra el enlace para pagar en linea un recibo de pago
 * @param array $configuracion
 * @param array $registro
 */
function enlacePagoEnLinea($configuracion,$registro,$contador){
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$variable='';
        $nueva_sesion=new sesiones($configuracion);
        $registro_sesion=$nueva_sesion->rescatar_valor_sesion($configuracion,"identificacion");
        if($registro_sesion)
        {

                $usuarioIdentificacion=$registro_sesion[0][0];
        }
        else
        {
                $usuarioIdentificacion="0";		
        }
        
        $indiceAcademico=$configuracion["host"]."/academicopro/index.php?";
        $variable="pagina=admin_pagoEnLinea";
        $variable.="&usuario=".$usuarioIdentificacion;
        $variable.="&factura=".$registro[$contador][0];
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        if($registro[$contador][12]<>1)//Recibo desbloqueado
        {
            if($registro[$contador][13]=='N'){
                $reciboanterior=$registro[$contador][7]-1;
                $indiceReciboAnterior=obtenerIndiceReciboAnterior($registro,$reciboanterior);
                if($registro[$contador][7]>1 && $registro[$indiceReciboAnterior][13]=='N')
                {
                        echo "<font color='red'>Para PAGAR EN LINEA este recibo, primero debe pagar el recibo correspondiente a la <b>cuota No. ".$reciboanterior."</b><br></font>";
                }
                else{
                        $fecha_hoy = strtotime('now');

                        $fecha_ord = str_replace('/', '-', $registro[$contador][10]);
                        $fecha_ord = strtotime($fecha_ord);

                        $fecha_extra = str_replace('/', '-', $registro[$contador][11]);
                        $fecha_extra = strtotime($fecha_extra);
                        if($fecha_hoy <= $fecha_ord || $fecha_hoy <= $fecha_extra){
                        ?><a href="<?	echo $indiceAcademico.$variable;?>">
                          <img border="0" alt="PAGO EN LÍNEA" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/BotonPSE.jpg"?>" />
                          </a><?
                        }else{
                            echo "<font color='red'>RECIBO VENCIDO</font>";
                        }

                }
            }elseif($registro[$contador][13]=='S'){
                echo "RECIBO YA CANCELADO";
            }
        }

                  
        
        }

        /**
         * Función para buscar el indice del arreglo del recibo anterior
         * @param <array> $recibos
         * @param int $reciboanterior
         * @return type
         */
        function obtenerIndiceReciboAnterior($recibos,$reciboanterior){
            $indice='';
            foreach ($recibos as $key => $recibo) {
                if ($recibo[7]==$reciboanterior){
                    $indice = $key;
                    break;
                }
            }    
            return $indice;
        }

    /**
     * Función para consultar si existe la aceptación de terminos y condiciones de pago
     * @param <array> $configuracion
     * @param <array> $datosRegistro
     * @param type $accesoGestion
     * @return <array>
     */
    function consultarAceptacionDeTerminos($configuracion,$datosRegistro,$accesoGestion){
        $cadena_sql=cadena_busqueda_recibo($configuracion, $accesoGestion, $datosRegistro,"AceptacionDeTerminos");
        $resultado= $accesoGestion->ejecutarAcceso($cadena_sql,"busqueda");
	return $resultado;
        
    }
    
    /**
     * Función para validar si el recibo es por matrícula
     * @param <array> $configuracion
     * @param <array> $registro
     * @param type $accesoOracle
     * @return string
     */
    function validarReciboMatricula($configuracion,$registro,$accesoOracle){
            $band='';
            $conceptos = consultarConceptosRecibo($configuracion,$registro[0],$registro[5],$accesoOracle);
            if(is_array($conceptos)){
                foreach ($conceptos as $concepto) {
                    if($concepto[3]==1){
                        $band='ok';
                    }

                }
            }
            return $band;
    }
    
    /**
     * Función para consultar los conceptos de un recibo
     * @param <array> $configuracion
     * @param int $secuencia
     * @param int $anio_recibo
     * @param type $accesoOracle
     * @return <array>
     */
    function consultarConceptosRecibo($configuracion,$secuencia,$anio_recibo,$accesoOracle){
        $datos = array('secuencia'=>$secuencia,
                        'anio'=>$anio_recibo);
        $cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $datos,"conceptosRecibo");
        $resultado= $accesoOracle->ejecutarAcceso($cadena_sql,"busqueda");
	return $resultado;
        
    }
    
    /**
     * Función para consultar el período anterior
     * @param type $configuracion
     * @param type $accesoOracle
     * @return type
     */
    function consultarPeriodoAnterior($configuracion,$accesoOracle){
       
        $cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, '',"periodoAnterior");
        $resultado= $accesoOracle->ejecutarAcceso($cadena_sql,"busqueda");
	return $resultado;
        
    }
    
    /**
     * Función para consultar el numero de identificacion de un estudiante
     * @param type $configuracion
     * @param type $codigo
     * @param type $accesoOracle
     * @return type
     */
    function consultarIdentificacionEstudiante($configuracion,$codigo,$accesoOracle){
       
        $cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $codigo,"identificacionEstudiante");
        $resultado= $accesoOracle->ejecutarAcceso($cadena_sql,"busqueda");
	return $resultado;
        
    }
    
    /**
     * Función para consultar si un estudiante es beneficiario de matricula de honor o monitoria
     * @param type $configuracion
     * @param type $datosRegistro
     * @param type $accesoGestion
     * @return type
     */
    function consultarBeneficiario($configuracion,$datosRegistro,$accesoGestion){
        $cadena_sql=cadena_busqueda_recibo($configuracion, $accesoGestion, $datosRegistro,"beneficiarioMatriculaMonitoria");
        $resultado= $accesoGestion->ejecutarAcceso($cadena_sql,"busqueda");
	return $resultado;
        
    }
    
    /**
     * Función para consultar y mostrar la relacion de beneficiario de monitorias o matriculas de honor de n estudiante
     * @param type $configuracion
     * @param type $registro
     * @param type $acceso_db
     * @param type $accesoOracle
     * @param type $accesoGestion
     * @param int $usuario
     */
    function con_registro_beneficiario($configuracion,$registro,$acceso_db,$accesoOracle,$accesoGestion,$usuario)
    {
            $registroEstudiante=  consultarIdentificacionEstudiante($configuracion, $usuario, $accesoOracle);
            $registroPeriodoAnt=  consultarPeriodoAnterior($configuracion, $accesoOracle);
            if(is_array($registroEstudiante) && is_array($registroPeriodoAnt)){
                    //Buscar beneficiario matriculas de honor o monitorias
                    $datos=array('anio'=>$registroPeriodoAnt[0][0],
                            'periodo'=>$registroPeriodoAnt[0][1],
                            'identificacion'=>$registroEstudiante[0][0]);
                    $registroBeneficiario=  consultarBeneficiario($configuracion, $datos, $accesoGestion);
                    if(is_array($registroBeneficiario))
                    {
                                ?>
                                <table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
                                                <tr class="texto_subtitulo">
                                                        <td>
                                                        <span  class="texto_subtitulo">.:: Cobros por Mátrículas de honor o Monitorías</span>
                                                        <hr class="hr_subtitulo">
                                                        </td>
                                                </tr>
                                                <tr >
                                                        <td>
                                                            <table class="contenidotabla">
                                                                <?
                                                                    foreach ($registroBeneficiario as $beneficiario) {
                                                                ?>
                                                                        <tr><td class="cuadro_plano centrar"><font color="red">
                                                                                <br>Usted tiene un cobro pendiente de $<? echo number_format($beneficiario[6], 2);?> por concepto de <? echo $beneficiario[4];?>. Su número de PIN asignado es <? echo $beneficiario[5];?>. <br>Por favor acercarse a la Oficina del Banco de Occidente con el número de PIN y su documento de identidad para efectuar el retiro.
                                                                                <br>
                                                                                </font>
                                                                            </td>
                                                                        </tr>
                                                                 <?
                                                                        }
                                                                ?>
                                                            </table>
                                                            
                                                        </td>
                                                </tr>
                                </table>
                                <?
                    }
            }
    }
?>
