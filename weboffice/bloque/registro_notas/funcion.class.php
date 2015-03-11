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

class funciones_registroNotasDocentes extends funcionGeneral
{
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		
		$this->cripto=new encriptar();
		$this->tema=$tema;
		$this->sql=$sql;
		
		//Conexion ORACLE
		$this->accesoOracle=$this->conectarDB($configuracion,"docente");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="registro_notas";
                $this->verificar='';
		//$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'lugar')";
		
	}
		
	//Rescata la lista de estudiantes inscritos en cada una de las asignaruras registradas al docente.
	function verListaClase($configuracion)
	{
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$_REQUEST['usuario'];
		}

		unset($valor);
		$valor[0]=$usuario;
		$nivel=$_REQUEST['nivel'];
		$valor[4]=$_REQUEST['nivel'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "carreras",$valor);
		$resultCarreras=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");    
		$cuenta=count($resultCarreras);

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "acasperieventos",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");   
		$cuentaR=count($resultado);  
   
		if(is_array($resultCarreras))
		{
			 for($k=0; $k<=$cuenta-1; $k++)
			 {
				for($m=0; $m<=$cuentaR-1; $m++)
				{
					if($resultCarreras[$k][0] == $resultado[$m][0])
					{
						$valor[10]=$resultado[$m][1];    
					}
				}
			 }  
		}
		else
		{
			$valor[10]='A';
		}
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];    

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "listaClase",$valor);
		$resultLista=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		if(!is_array($resultLista))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="Estimado Docente, usted no tiene carga registrada en ".$_REQUEST['nivel']."<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);	
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
										<li> Para digitar notas, haga clic en el nombre de la asignatura.</li>
									</ul>
								</td>
							</tr>
						</table>
						<br>
						<table class="formulario" align="center">
							<tr  class="bloquecentralencabezado">
								<td colspan="5" align="center">
									<p><span class="texto_negrita">CAPTURA DE NOTAS <? echo $nivel.' PERIODO '.$ano.'-'.$per; ?></span></p>
								</td>
							</tr>
							<tr>
								<td colspan="5">
									Per&iacute;odo Acad&eacute;mico:  <? echo $ano.'-'.$per; ?>
								</td>
							</tr>
							
							
							<tr align='center'>
								<td align="center" height="10">C&oacute;digo</td>
								<td align="center" height="10">Asignatura</td>
								<td align="center" height="10">Grupo</td>
								<td align="center" height="10">Inscritos</td>
								<td align="center" height="10">Carrera</td>
							</tr>
							<tr class="bloquecentralcuerpo">
								<?php
								$i=0;
								while(isset($resultLista[$i][0]))
								{
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
//								$total=count($resultado);
									
								setlocale(LC_MONETARY, 'en_US');
								$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
								$cripto=new encriptar();
								
								$menu=new navegacion();
									if($resultLista[$i][12]=='PREGRADO' || $resultLista[$i][12]=='EXTENSION')
									{
                                                                            if($resultLista[$i][8] !=(isset($resultLista[$i-1][8])?$resultLista[$i-1][8]:'') || $resultLista[$i][10] !=(isset($resultLista[$i-1][10])?$resultLista[$i-1][10]:'') || $resultLista[$i][4]!=(isset($resultLista[$i-1][4])?$resultLista[$i-1][4]:'')){
                                                                                echo '<tr>
                                                                                    <td align="center">
                                                                                            '.$resultLista[$i][8].'
                                                                                    </td>';
                                                                                    if($resultLista[$i][11]>0){
                                                                                            echo "<td class='cuadro_plano centrar'>
                                                                                                    <a href='";
                                                                                                    $variable="pagina=registro_notasDocente";
                                                                                                    $variable.="&opcion=dignotasPregrado";
                                                                                                    $variable.="&asig=".$resultLista[$i][8]."";
                                                                                                    $variable.="&nivel=".$resultLista[$i][12]."";
                                                                                                    $variable.="&id_grupo=".$resultLista[$i][10]."";
                                                                                                    $variable.="&grupo=".$resultLista[$i][13]."";
                                                                                                    $variable.="&carrera=".$resultLista[$i][4]."";
                                                                                                    $variable.="&periodo=".$valor[10]."";
                                                                                                    $variable.="&otro=";
                                                                                                    $variable=$cripto->codificar_url($variable,$configuracion);
                                                                                                    echo $indice.$variable."'";
                                                                                                    echo "title='Digitar notas'>".$resultLista[$i][9]."</a>
                                                                                            </td>";
                                                                                    }else{
                                                                                        echo "<td class='cuadro_plano centrar'>
                                                                                                    ".$resultLista[$i][9]."
                                                                                            </td>";
                                                                                    }
                                                                                    echo '<td align="center">'.$resultLista[$i][13].'</td>
                                                                                    <td align="center">'.$resultLista[$i][11].'</td>
                                                                                    <td align="left"><span class="Estilo3">'.$resultLista[$i][5].'</span></td></tr>';
                                                                            }
									}
									else
									{
										echo '<tr>
										<td align="center">
											'.$resultLista[$i][8].'
										</td>';
										if($resultLista[$i][11]>0){
                                                                                     echo "<td class='cuadro_plano centrar'>
											<a href='";
											$variable="pagina=registro_notasDocente";
											$variable.="&opcion=dignotasPosgrado";
											$variable.="&asig=".$resultLista[$i][8]."";
											$variable.="&nivel=".$resultLista[$i][12]."";
											$variable.="&id_grupo=".$resultLista[$i][10]."";
											$variable.="&grupo=".$resultLista[$i][13]."";
											$variable.="&carrera=".$resultLista[$i][4]."";
											$variable.="&periodo=".$valor[10]."";  
											$variable.="&otro=";
											$variable=$cripto->codificar_url($variable,$configuracion);
											echo $indice.$variable."'";
											echo "title='Digitar notas'>".$resultLista[$i][9]."</a>
                                                                                    </td>";
                                                                                        }else{
                                                                                        echo "<td class='cuadro_plano centrar'>
                                                                                                    ".$resultLista[$i][9]."
                                                                                            </td>";
                                                                                    }
										echo '<td align="center">'.$resultLista[$i][13].'</td>
										<td align="center">'.$resultLista[$i][11].'</td>
										<td align="left"><span class="Estilo3">'.$resultLista[$i][5].'</span></td></tr>';
									}
								$i++;
								}
								?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?
		}
	}
	
	//Rescata la lista de estudiantes,  y dependiendo si las fechas de captura de notas estan habilitadas, muestra el formulario para capturar de los porcentajes y las notas, si no, solamente muestra la lista con los porcentajes y las notas digitadas .
	function digitarNotasPregrado($configuracion)
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
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}				
		$calendario=$this->validaCalendario("",$configuracion);
		//$observaciones=$this->notasobservaciones();guardarNotas
		$valor[0]=$usuario;
		$valor[1]=$_REQUEST['asig'];
		$valor[2]=$_REQUEST['id_grupo'];
		$valor[3]=$_REQUEST['carrera'];
		$valor[4]=$_REQUEST['nivel'];

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "carreras",$valor);
		$resultCarreras=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");    
		$cuenta=count($resultCarreras);

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "acasperieventos",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");   
		$cuentaR=count($resultado);  
		
		$valor[10]=$_REQUEST['periodo'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
				
		$valor[5]=$ano;
		$valor[6]=$per;
				
		$verifica=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "notasparciales",$valor);
		$resultverifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $verifica, "busqueda");
		$valor[7]=count($resultverifica);
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
		$rowfechoy=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$fechahoy = $rowfechoy[0][0];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechasDigNotas",$valor);
		$rowfecnot=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuenta=count($rowfecnot);
		
		$confechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"validaFechas",$valor);
		$rowfechas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confechas, "busqueda");
		$fecini = $rowfechas[0][0];
		$fecfin = $rowfechas[0][1];
		$fecha = $rowfechas[0][2];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"notasobs",$valor);
		$rowobs=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$tab='1';
		?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
                        <input type='hidden' name='nivel' value='<? echo $valor[4]?>'>
                        <input type='hidden' name='action' value='<? echo $this->formulario ?>'>
                        <input type='hidden' name='asig' value='<? echo $valor[1] ?>'>
                        <input type='hidden' name='id_grupo' value='<? echo $valor[2] ?>'>
                        <input type='hidden' name='periodo' value='<? echo $valor[10] ?>'>
                        <input type='hidden' name='cra' value='<? echo $resultverifica[0][33] ?>'>
                        <input type='hidden' name='periodo' value='<? echo $valor[10] ?>'>
                        <input type='hidden' name='cuenta' value='<? echo $valor[7] ?>'>
                        <input type='hidden' name='opcion' value='grabar'>

			<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0">
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
									<td class="cuadro_brown" colspan="5">
										<br>
										<ul>
											<li> Para calcular el acumulado, las definitivas o imprimir el listado, por favor grabe las notas digitadas.</li>
											<li> La suma de los porcentajes 1, 2, 3 , 4, 5, 6 y LAB, no debe superar el 70%.</li>
											<li> El 100% de los porcentajes se calcula con la suma de los porcentajes, mas el porcentaje del ex&aacute;men que corresponde al 30%.</li>
 											<li> En caso de modificación de notas o porcentajes de las mismas, no olvide grabar.</li>
											<li> Se informa a los docentes que para poder realizar la autoevaluación docentes, deben registrar la totalidad de las notas en el sistema, incluyendo la nota del exámen, la cual es obligatoria. Para los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0, excepto en la casilla de la habilitación.</li>
											<li> El n&uacute;mero de fallas no se tiene en cuenta para el c&aacute;lculo del acumulado ni de la nota definitiva.</li>
											<li> En las notas digite siempre un n&uacute;mero entero. Ejemplo: Para 0.5 digite 5  -  Para 5,0 digite 50.  Para 3,7 digite 37.</li>
											<li> La columna correspondiente a OBS (observaciones), es &uacute;nicamente para notas cualitativas, por lo tanto no debe digitar notas cuantitativas, ya que no se calcular&aacute; la nota definitiva.</li>
										</ul>
									</td>
									
								</tr>
							<tr class="cuadro_color">
								<?
								$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechasDigNotas",$valor);
								$reg2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
								
								$fechas_capturaNotas='Fechas l&iacute;mites  para captura de notas <br><br> Porcentaje 1: '.$reg2[0][2].'<br> Porcentaje 2: '.$reg2[0][4].'<br> Porcentaje 3: '.$reg2[0][6].'<br> Porcentaje 4: '.$reg2[0][8]
								.'<br> Porcentaje 5: '.$reg2[0][10].'<br> Porcentaje 6: '.$reg2[0][12].'<br> Ex&aacute;men: '.$reg2[0][16].'<br> Habilitaci&oacute;n: '.$reg2[0][18].'<br><br>Haga Click para ver m&aacute;s <br>';
								?>
								<td align="center" valign="middle" colspan="5" onmouseover="toolTip('<BR><?echo $fechas_capturaNotas;?>&nbsp;&nbsp;&nbsp;',this)" >
									<div class="centrar">
										<span id="toolTipBox" width="300" ></span>
									</div>
									<?
									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
//									$total=count($resultado);
										
									setlocale(LC_MONETARY, 'en_US');
									$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
									$cripto=new encriptar();
									
									echo "<a href='";
									$variable="pagina=registro_notasDocente";
									$variable.="&opcion=verfechas";
									$variable.="&asig=".$valor[1];
									$variable.="&grupo=".$valor[2];
									$variable.="&carrera=".$valor[3];
									$variable.="&nivel=".$valor[4];
									$variable.="&periodo=".$valor[10];
									//$variable.="&no_pagina=true";
									$variable=$cripto->codificar_url($variable,$configuracion);
									echo $indice.$variable."'";
									//echo "title='Haga Click aqu&iacute; para ver fechas de digitaci&oacute;n de notas'>";
									?>
									<center>
									<b>Ver fechas de captura de notas</b>
									</center>
									</a>
								</td>
							</tr>
							<tr class="texto_subtitulo">
								<td class="" colspan="5" align="center">
									<p><span class="texto_negrita">CAPTURA DE NOTAS PARCIALES <? echo $valor[4].' PERIODO '.$ano.'-'.$per; ?> </span></p>
								</td>
							</tr>
							<tr class="cuadro_color">
								<?
								echo '<td class="" align="left">'.$resultverifica[0][4].'</td>
								<td class="" >'.$resultverifica[0][5].'</td>
								<td class="" align="center"><b>Grupo</b></td>
								<td class="" align="center"><b>Inscritos</b></td>
								<td class="" align="center"><b>Periodo</b></td>
							</tr>
							<tr class="cuadro_color">
								<td class="" align="left">'.$resultverifica[0][0].'</td>
								<td class="" align="left">'.$resultverifica[0][1].'</td>
								<td class="" align="center">'.(isset($resultverifica[0][36])?$resultverifica[0][36]:'').'</td>
								<td class="" align="center">'.$resultverifica[0][32].'</td>
								<td class="" align="center">'.$resultverifica[0][2].'-'.$resultverifica[0][3].'</td>';
								?>
							</tr>
							<tr class="bloquecentralcuerpo">
							</tr>
						</table>
						
						<table class="formulario" align="center">
							<tr  class="texto_subtitulo">
								<td colspan="15" align="center">
									<p><span class="texto_negrita">Captura de porcentajes</span></p>
								</td>
							</tr>
							<tr class="cuadro_color">
								<td colspan="2" class="cuadro_plano centrar"></td>
								<td colspan="7" class="cuadro_plano centrar">Porcentajes 1 - 6 + LAB</td>
								<td class="cuadro_plano centrar">EXA</td>
								<td class="cuadro_plano centrar">HAB</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">SUM</td>
							</tr>
							<tr class="formulario">
								<td colspan="2"> </td>
								<td class="cuadro_plano centrar" align="center" colspan="7">70%</td>
								<td align="center">30%</td>
								<td align="center">70%</td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center">100%</td>
							</tr>
							</tr>
							<tr class="cuadro_color">
								<td colspan="2"> </td>
								<td class="cuadro_plano centrar" align="center" colspan="3">Corte 1</td>
								<td class="cuadro_plano centrar" align="center" colspan="4">Corte 2</td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center"></td>
							</tr>
							<tr class="cuadro_color">
								<td colspan="2" class="cuadro_plano centrar">Porcentaje de Notas</td>
								<td class="cuadro_plano centrar">%1</td>
								<td class="cuadro_plano centrar">%2</td>
								<td class="cuadro_plano centrar">%3</td>
								<td class="cuadro_plano centrar">%4</td>
								<td class="cuadro_plano centrar">%5</td>
								<td class="cuadro_plano centrar">%6</td>
								<td class="cuadro_plano centrar">LAB</td>
								<td class="cuadro_plano centrar">EXA</td>
								<td class="cuadro_plano centrar">HAB</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">SUM</td>
							</tr>
							<tr class="formulario">
								<td colspan="2" align="center"> </td>
								<?
								if(($fechahoy < $rowfecnot[0][1]) || ($fechahoy > $rowfecnot[0][2]) || ($rowfecnot[0][1] == " ") || ($rowfecnot[0][2] == " ") || ($fechahoy > $rowfecnot[0][19])){
									echo '<td align="center">'.$resultverifica[0][11].'</td>
									<input type="hidden" name="par1" id="par1" value="'.$resultverifica[0][11].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p1" id="p1" value="'.$resultverifica[0][11].'" maxlength="2" size="1" style="text-align:right"></td>';	
								}
								if(($fechahoy < $rowfecnot[0][3]) || ($fechahoy > $rowfecnot[0][4]) || ($rowfecnot[0][3] == " ") || ($rowfecnot[0][4] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][13].'</td>
									<input type="hidden" name="par2" id="par2" value="'.$resultverifica[0][13].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p2" id="p2" value="'.$resultverifica[0][13].'" maxlength="2" size="1" style="text-align:right"></td>';
								}
								if(($fechahoy < $rowfecnot[0][5]) || ($fechahoy > $rowfecnot[0][6]) || ($rowfecnot[0][5] == " ") || ($rowfecnot[0][6] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][15].'</td>
									<input type="hidden" name="par3" id="par3" value="'.$resultverifica[0][15].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p3" id="p3" value="'.$resultverifica[0][15].'" maxlength="2" size="1" style="text-align:right"></td>';
								}
								if(($fechahoy < $rowfecnot[0][7]) || ($fechahoy > $rowfecnot[0][8]) || ($rowfecnot[0][7] == " ") || ($rowfecnot[0][8] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][17].'</td>
									<input type="hidden" name="par4" id="par4" value="'.$resultverifica[0][17].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p4" id="p4" value="'.$resultverifica[0][17].'" maxlength="2" size="1" style="text-align:right"></td>';
								}
								if(($fechahoy < $rowfecnot[0][9]) || ($fechahoy > $rowfecnot[0][10]) || ($rowfecnot[0][9] == " ") || ($rowfecnot[0][10] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][19].'</td>
									<input type="hidden" name="par5" id="par5" value="'.$resultverifica[0][19].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p5" value="'.$resultverifica[0][19].'" maxlength="2" size="1" style="text-align:right"></td>';	
								}
								if(($fechahoy < $rowfecnot[0][11]) || ($fechahoy > $rowfecnot[0][12]) || ($rowfecnot[0][11] == " ") || ($rowfecnot[0][12] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][21].'</td>
									<input type="hidden" name="par6" id="par6" value="'.$resultverifica[0][21].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p6" id="p6" value="'.$resultverifica[0][21].'" maxlength="2" size="1" style="text-align:right"></td>';	
								}
								if(($fechahoy < $rowfecnot[0][13]) || ($fechahoy > $rowfecnot[0][14]) || ($rowfecnot[0][13] == " ") || ($rowfecnot[0][14] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][27].'</td>
									<input type="hidden" name="plab" id="plab" value="'.$resultverifica[0][27].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="pl" id="pl" value="'.$resultverifica[0][27].'" maxlength="2" size="1" style="text-align:right"></td>';
								}
								if(($fechahoy < $rowfecnot[0][15]) || ($fechahoy > $rowfecnot[0][16]) || ($rowfecnot[0][15] == " ") || ($rowfecnot[0][16] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][23].'</td>
									<input type="hidden" name="pexa" id="pexa" value="'.$resultverifica[0][23].'">';
								}
								else
								{
									echo '<td align="center"> <input type="text" name="pe" id="pe" value="'.$resultverifica[0][23].'"  maxlength="2" size="1" style="text-align:right"></td>';
									
								}
								if(($fechahoy < $rowfecnot[0][17]) || ($fechahoy > $rowfecnot[0][18]) || ($rowfecnot[0][17] == " ") || ($rowfecnot[0][18] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">70</td>';
								}
								else
								{
								echo '<td width="26" align="center"><input type="text" name="ph" id="ph" value="70" readonly size="1" style="text-align:right" ></td>';
							
								}
								
								if ($resultverifica[0][34]<=100)
								{
									echo '<td></td>
									<td></td>
									<td></td>
									<td align="center">'.$resultverifica[0][34].'%</td>
									</tr>
									<tr  class="texto_subtitulo">
										<td colspan="15" align="center">
											<p><span class="texto_negrita">Captura de notas</span></p>
										</td>
									</tr>
									<tr class="cuadro_color">
										<td align="center">CODIGO</td>
										<td align="center">NOMBRE</td>
										<td align="center">P1</td>
										<td align="center">P2</td>
										<td align="center">P3</td>
										<td align="center">P4</td>
										<td align="center">P5</td>
										<td align="center">P6</td>
										<td align="center">LAB</td>
										<td align="center">EXA</td>
										<td align="center">HAB</td>
										<td align="center">No. Fallas</td>
										<td align="center">ACU</td>
										<td align="center">OBS</td>
										<td align="center">DEF</td>
									</tr>';
									if($fechahoy < $fecini || $fechahoy > $fecfin)
									{
										$i=0;
										while(isset($resultverifica[$i][0]))
										{
											echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
											<td align="right">'.$resultverifica[$i][7].'</td>
												<td>'.$resultverifica[$i][8].'</td>
												<td align="center">'.$resultverifica[$i][10].'</td>
												<td align="center">'.$resultverifica[$i][12].'</td>
												<td align="center">'.$resultverifica[$i][14].'</td>
												<td align="center">'.$resultverifica[$i][16].'</td>
												<td align="center">'.$resultverifica[$i][18].'</td>
												<td align="center">'.$resultverifica[$i][20].'</td>
												<td align="center">'.$resultverifica[$i][22].'</td>
												<td align="center">'.$resultverifica[$i][24].'</td>
												<td align="center">'.$resultverifica[$i][26].'</td>
												<td align="center">'.$resultverifica[$i][31].'</td>
												<td align="center">'.$resultverifica[$i][29].'</td>
												<td align="center">'.$resultverifica[$i][28].'</td>
											</tr>';
										$i++;
										}
									}
									else
									{
										$i=0;
										while(isset($resultverifica[$i][0]))
										{
											echo'<tr>
											<td align="left">'.$resultverifica[$i][7].'</td>
											<td>'.$resultverifica[$i][8].'</td>';
											if(($fechahoy < $rowfecnot[0][1]) || ($fechahoy > $rowfecnot[0][2]) || ($rowfecnot[0][1] == " ") || ($rowfecnot[0][2] == " ") || ($fechahoy > $rowfecnot[0][19])){
												echo '<td align="center">'.$resultverifica[$i][10].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_1'.$i.'" name="nota_1'.$i.'" onBlur="val_nota(\'nota_1'.$i.'\')" value="'.$resultverifica[$i][10].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
												
											}
											if(($fechahoy < $rowfecnot[0][3]) || ($fechahoy > $rowfecnot[0][4]) || ($rowfecnot[0][3] == " ") || ($rowfecnot[0][4] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][12].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_2'.$i.'" name="nota_2'.$i.'" onBlur="val_nota(\'nota_2'.$i.'\')" value="'.$resultverifica[$i][12].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][5]) || ($fechahoy > $rowfecnot[0][6]) || ($rowfecnot[0][5] == " ") || ($rowfecnot[0][6] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][14].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_3'.$i.'" name="nota_3'.$i.'" onBlur="val_nota(\'nota_3'.$i.'\')" value="'.$resultverifica[$i][14].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][7]) || ($fechahoy > $rowfecnot[0][8]) || ($rowfecnot[0][7] == " ") || ($rowfecnot[0][8] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][16].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_4'.$i.'" name="nota_4'.$i.'" onBlur="val_nota(\'nota_4'.$i.'\')" value="'.$resultverifica[$i][16].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][9]) || ($fechahoy > $rowfecnot[0][10]) || ($rowfecnot[0][9] == " ") || ($rowfecnot[0][10] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][18].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_5'.$i.'" name="nota_5'.$i.'" onBlur="val_nota(\'nota_5'.$i.'\')" value="'.$resultverifica[$i][18].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][11]) || ($fechahoy > $rowfecnot[0][12]) || ($rowfecnot[0][11] == " ") || ($rowfecnot[0][12] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][20].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_6'.$i.'" name="nota_6'.$i.'" onBlur="val_nota(\'nota_6'.$i.'\')" value="'.$resultverifica[$i][20].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][13]) || ($fechahoy > $rowfecnot[0][14]) || ($rowfecnot[0][13] == " ") || ($rowfecnot[0][14] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][24].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="lab_1'.$i.'" name="lab_1'.$i.'" onBlur="val_nota(\'lab_1'.$i.'\')" value="'.$resultverifica[$i][24].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][15]) || ($fechahoy > $rowfecnot[0][16]) || ($rowfecnot[0][15] == " ") || ($rowfecnot[0][16] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][22].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="exa_1'.$i.'" name="exa_1'.$i.'" onBlur="val_nota(\'exa_1'.$i.'\')" value="'.$resultverifica[$i][22].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)" ></td>';
												
											}
											if(($fechahoy < $rowfecnot[0][17]) || ($fechahoy > $rowfecnot[0][18]) || ($rowfecnot[0][17] == " ") || ($rowfecnot[0][18] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][26].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="hab_1'.$i.'" name="hab_1'.$i.'" onBlur="val_nota(\'hab_1'.$i.'\')" value="'.$resultverifica[$i][26].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											echo '<td align="center"><input type="text" id="nf_1'.$i.'" name="nf_1'.$i.'" onBlur="val_nota(\'nf_1'.$i.'\')" value="'.$resultverifica[$i][35].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>
											<td align="center">'.$resultverifica[$i][31].'</td>
											<td>
												<select style="width:48px" id="obs_1'.$i.'" name="obs_1'.$i.'" value="'.$resultverifica[$i][29].'">
												<option>'.$resultverifica[$i][29].'</option>';
												$j=0;
												while(isset($rowobs[$j][0]))
												{
													echo '<option value='.$rowobs[$j][0].'>'.$rowobs[$j][0].' ' .$rowobs[$j][1].'</option>';
												$j++;
												}
												echo '</select>
											</td>
											<td align="center">'.$resultverifica[$i][28].'</td>
											<input type="hidden" name="cod_'.$i.'" size="10%" id="codigo" value="'.$resultverifica[$i][7].'" readonly style="text-align:right">
											<input type="hidden" name="nivel" size="10%" id="nivel" value="'.$valor[4].'" readonly style="text-align:right">
											</tr>';
										$i++;
										}
									}
								}
								else
								{
									echo "<p>La suma de los porcentajes no debe superar el 100%</p>";
								}
								
								
								?>
								
							<tr align='center'>
								<td colspan="16">
									<table class="tablaBase">
										<tr>
											<td align="center">
												<input value="Grabar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
											</td>
											<!--td align="center">
												<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
												<input type="submit" name="notdef" value="Calcular Acumulado">
											</td-->
											<td align="center">
												<input type='hidden' name='nivel' value='<? echo $valor[4]?>'>
												<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
											</td>
										</tr>
										<tr>
											<td align="center" colspan="3">
												<?
												include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
												include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
//												$total=count($resultado);
													
												setlocale(LC_MONETARY, 'en_US');
												$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
												$cripto=new encriptar();
												
												echo "<a href='";
												$variable="pagina=registro_notasDocente";
												$variable.="&opcion=reportes";
												$variable.="&asig=".$valor[1];
												$variable.="&id_grupo=".$valor[2];
												$variable.="&carrera=".$valor[3];
												$variable.="&nivel=".$valor[4];
												$variable.="&periodo=".$valor[10];
												$variable.="&docente=".$valor[0]; 
												//$variable.="&no_pagina=true";
												$variable=$cripto->codificar_url($variable,$configuracion);
												echo $indice.$variable."'";
												echo "title='Haga Click aqu&iacute; para ir a reporte de notas'>";
												?>
												<center><img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/reporte.png" border="0"></center>
												Ir a reporte de notas
												</a>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>		
						
		<?
		
	}
	
	function digitarNotasPosgrado($configuracion)
	{
		$sbgc='';
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
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		if($usuario=="")
		{
			echo "¡SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}	
						
		$calendario=$this->validaCalendario("",$configuracion);
		//$observaciones=$this->notasobservaciones();guardarNotas
		
		$valor[10]=$_REQUEST['periodo'];    

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$valor[0]=$usuario;
		$valor[1]=$_REQUEST['asig'];
		$valor[2]=$_REQUEST['id_grupo'];
		$valor[3]=$_REQUEST['carrera'];
		$valor[4]=$_REQUEST['nivel'];
		$valor[5]=$ano;
		$valor[6]=$per;
				
		$verifica=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "notasparciales",$valor);
		$resultverifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $verifica, "busqueda");
		$valor[7]=count($resultverifica);
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
		$rowfechoy=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$fechahoy = $rowfechoy[0][0];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechasDigNotas",$valor);
		$rowfecnot=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuenta=count($rowfecnot);
		
		$confechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"validaFechas",$valor);
		$rowfechas=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confechas, "busqueda");
		$fecini = $rowfechas[0][0];
		$fecfin = $rowfechas[0][1];
		$fecha = $rowfechas[0][2];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"notasobs",$valor);
		$rowobs=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$tab=1;	
		?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
                    <input type='hidden' name='nivel' value='<? echo $valor[4]?>'>
                    <input type='hidden' name='action' value='<? echo $this->formulario ?>'>
                    <input type='hidden' name='asig' value='<? echo $valor[1] ?>'>
                    <input type='hidden' name='id_grupo' value='<? echo $valor[2] ?>'>
                    <input type='hidden' name='cra' value='<? echo $resultverifica[0][33] ?>'>
                    <input type='hidden' name='cuenta' value='<? echo $valor[7] ?>'>
                    <input type='hidden' name='periodo' value='<? echo $valor[10] ?>'>
                    <input type='hidden' name='opcionpos' value='grabar'>
			<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
									<td class="cuadro_brown" colspan="5">
										<br>
										<ul>
											<li> Para calcular el acumulado, las definitivas o imprimir el listado, por favor grabe las notas digitadas.</li>
											<li> El 100% de los porcentajes se calcula con la suma de los porcentajes, mas el porcentaje del ex&aacute;men que corresponde al 30%.</li>
 											<li> En caso de modificación de notas o porcentajes de las mismas, no olvide grabar y recalcular el acumulado.</li>
											<li> Se informa a los docentes que para poder realizar la autoevaluación docentes, deben registrar la totalidad de las notas en el sistema, incluyendo la nota del exámen, la cual es obligatoria. Para los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0, excepto en la casilla de la habilitación.</li>
											<li> El n&uacute;mero de fallas no se tiene en cuenta para el c&aacute;lculo del acumulado ni de la nota definitiva.</li>
											<li> En las notas digite siempre un n&uacute;mero entero. Ejemplo: Para 0.5 digite 5  -  Para 5,0 digite 50.  Para 3,7 digite 37.</li>
											<li> La columna correspondiente a OBS (observaciones), es &uacute;nicamente para notas cualitativas, por lo tanto no debe digitar notas cuantitativas, ya que no se calcular&aacute; la nota definitiva.</li>
										</ul>
									</td>
								</tr>
							<tr class="cuadro_color">
								<?
								$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechasDigNotas",$valor);
								$reg2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
								
								$fechas_capturaNotas='Fechas l&iacute;mites  para captura de notas <br><br> Porcentaje 1: '.$reg2[0][2].'<br> Porcentaje 2: '.$reg2[0][4].'<br> Porcentaje 3: '.$reg2[0][6].'<br> Porcentaje 4: '.$reg2[0][8]
								.'<br> Porcentaje 5: '.$reg2[0][10].'<br> Porcentaje 6: '.$reg2[0][12].'<br> Ex&aacute;men: '.$reg2[0][16].'<br> Habilitaci&oacute;n: '.$reg2[0][18].'<br><br>Haga Click para ver m&aacute;s <br>';
								?>
								<td align="center" valign="middle" colspan="5" onmouseover="toolTip('<BR><?echo $fechas_capturaNotas;?>&nbsp;&nbsp;&nbsp;',this)" >
									<div class="centrar">
										<span id="toolTipBox" width="300" ></span>
									</div>
									<?
								
									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
//									$total=count($resultado);
										
									setlocale(LC_MONETARY, 'en_US');
									$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
									$cripto=new encriptar();
									
									echo "<a href='";
									$variable="pagina=registro_notasDocente";
									$variable.="&opcion=verfechas";
									$variable.="&asig=".$valor[1];
									$variable.="&id_grupo=".$valor[2];
									$variable.="&carrera=".$valor[3];
									$variable.="&nivel=".$valor[4];
									$variable.="&periodo=".$valor[10];
									//$variable.="&no_pagina=true";
									$variable=$cripto->codificar_url($variable,$configuracion);
									echo $indice.$variable."'";
									?>
									<center>
									Ver fechas de captura de notas
									</center>
									</a>
								</td>
							</tr>
							<tr class="texto_subtitulo">
								<td class="" colspan="5" align="center">
									<p><span class="texto_negrita">CAPTURA DE NOTAS PARCIALES <? echo $valor[4].' PERIODO '.$ano.'-'.$per; ?></span></p>
								</td>
							</tr>
							<tr class="cuadro_color">
								<?
								echo '<td class="" align="left">'.$resultverifica[0][4].'</td>
								<td class="" >'.$resultverifica[0][5].'</td>
								<td class="" align="center"><b>Grupo</b></td>
								<td class="" align="center"><b>Inscritos</b></td>
								<td class="" align="center"><b>Periodo</b></td>
							</tr>
							<tr class="cuadro_color">
								<td class="" align="left">'.$resultverifica[0][0].'</td>
								<td class="" align="left">'.$resultverifica[0][1].'</td>
								<td class="" align="center">'.$resultverifica[0][36].'</td>
								<td class="" align="center">'.$resultverifica[0][32].'</td>
								<td class="" align="center">'.$resultverifica[0][2].'-'.$resultverifica[0][3].'</td>';
								?>
							</tr>
							<tr class="bloquecentralcuerpo">
							</tr>
						</table>
						
						<table class="formulario" align="center">
							<tr  class="texto_subtitulo">
								<td colspan="15" align="center">
									<p><span class="texto_negrita">Captura de porcentajes</span></p>
								</td>
							</tr>
							<!--tr class="cuadro_color">
								<td colspan="2" class="cuadro_plano centrar"></td>
								<td colspan="7" class="cuadro_plano centrar">Porcentajes 1 - 6 + LAB</td>
								<td class="cuadro_plano centrar">EXA</td>
								<td class="cuadro_plano centrar">HAB</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">SUM</td>
							</tr>
							<tr class="formulario">
								<td colspan="2"> </td>
								<td class="cuadro_plano centrar" align="center" colspan="7">70%</td>
								<td align="center">30%</td>
								<td align="center">70%</td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center">100%</td>
							</tr>
							</tr>
							<tr class="cuadro_color">
								<td colspan="2"> </td>
								<td class="cuadro_plano centrar" align="center" colspan="3">Corte 1</td>
								<td class="cuadro_plano centrar" align="center" colspan="4">Corte 2</td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center">&nbsp;&nbsp;&nbsp;</td>
								<td align="center"></td>
							</tr-->
							<tr class="cuadro_color">
								<td colspan="2" class="cuadro_plano centrar">Porcentaje de Notas</td>
								<td class="cuadro_plano centrar">%1</td>
								<td class="cuadro_plano centrar">%2</td>
								<td class="cuadro_plano centrar">%3</td>
								<td class="cuadro_plano centrar">%4</td>
								<td class="cuadro_plano centrar">%5</td>
								<td class="cuadro_plano centrar">%6</td>
								<td class="cuadro_plano centrar">LAB</td>
								<td class="cuadro_plano centrar">EXA</td>
								<td class="cuadro_plano centrar">HAB</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
								<td class="cuadro_plano centrar">SUM</td>
							</tr>
							<tr class="formulario">
								<td colspan="2" align="center"> </td>
								<?
								if(($fechahoy < $rowfecnot[0][1]) || ($fechahoy > $rowfecnot[0][2]) || ($rowfecnot[0][1] == " ") || ($rowfecnot[0][2] == " ") || ($fechahoy > $rowfecnot[0][19])){
									echo '<td align="center">'.$resultverifica[0][11].'</td>
									<input type="hidden" name="par1" id="par1" value="'.$resultverifica[0][11].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p1" id="p1" value="'.$resultverifica[0][11].'" maxlength="2" size="1" style="text-align:right"></td>';	
								}
								if(($fechahoy < $rowfecnot[0][3]) || ($fechahoy > $rowfecnot[0][4]) || ($rowfecnot[0][3] == " ") || ($rowfecnot[0][4] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][13].'</td>
									<input type="hidden" name="par2" id="par2" value="'.$resultverifica[0][13].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p2" id="p2" value="'.$resultverifica[0][13].'" maxlength="2" size="1" style="text-align:right"></td>';
								}
								if(($fechahoy < $rowfecnot[0][5]) || ($fechahoy > $rowfecnot[0][6]) || ($rowfecnot[0][5] == " ") || ($rowfecnot[0][6] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][15].'</td>
									<input type="hidden" name="par3" id="par3" value="'.$resultverifica[0][15].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p3" id="p3" value="'.$resultverifica[0][15].'" maxlength="2" size="1" style="text-align:right"></td>';
								}
								if(($fechahoy < $rowfecnot[0][7]) || ($fechahoy > $rowfecnot[0][8]) || ($rowfecnot[0][7] == " ") || ($rowfecnot[0][8] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][17].'</td>
									<input type="hidden" name="par4" id="par4" value="'.$resultverifica[0][17].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p4" id="p4" value="'.$resultverifica[0][17].'" maxlength="2" size="1" style="text-align:right"></td>';
								}
								if(($fechahoy < $rowfecnot[0][9]) || ($fechahoy > $rowfecnot[0][10]) || ($rowfecnot[0][9] == " ") || ($rowfecnot[0][10] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][19].'</td>
									<input type="hidden" name="par5" id="par5" value="'.$resultverifica[0][19].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p5" value="'.$resultverifica[0][19].'" maxlength="2" size="1" style="text-align:right"></td>';	
								}
								if(($fechahoy < $rowfecnot[0][11]) || ($fechahoy > $rowfecnot[0][12]) || ($rowfecnot[0][11] == " ") || ($rowfecnot[0][12] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][21].'</td>
									<input type="hidden" name="par6" id="par6" value="'.$resultverifica[0][21].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="p6" id="p6" value="'.$resultverifica[0][21].'" maxlength="2" size="1" style="text-align:right"></td>';	
								}
								if(($fechahoy < $rowfecnot[0][13]) || ($fechahoy > $rowfecnot[0][14]) || ($rowfecnot[0][13] == " ") || ($rowfecnot[0][14] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][27].'</td>
									<input type="hidden" name="plab" id="plab" value="'.$resultverifica[0][27].'">';
								}
								else
								{
									echo '<td width="25" align="center"><input type="text" name="pl" id="pl" value="'.$resultverifica[0][27].'" maxlength="2" size="1" style="text-align:right"></td>';
								}
								if(($fechahoy < $rowfecnot[0][15]) || ($fechahoy > $rowfecnot[0][16]) || ($rowfecnot[0][15] == " ") || ($rowfecnot[0][16] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">'.$resultverifica[0][23].'</td>
									<input type="hidden" name="pexa" id="pexa" value="'.$resultverifica[0][23].'">';
								}
								else
								{
									echo '<td align="center"><input type="text" name="pe" id="pe" value="'.$resultverifica[0][23].'" maxlength="2" size="1" style="text-align:right"></td>';
									
								}
								
								if(($fechahoy < $rowfecnot[0][17]) || ($fechahoy > $rowfecnot[0][18]) || ($rowfecnot[0][17] == " ") || ($rowfecnot[0][18] == " ") || ($fechahoy > $rowfecnot[0][19]))
								{
									echo '<td align="center">70</td>';
								}
								else
								{
								echo '<td width="26" align="center"><input type="text" name="ph" id="ph" value="70" readonly size="1" style="text-align:right" '.$sbgc.'></td>';
							
								}
								
								if ($resultverifica[0][34]<=100)
								{
									echo '<td></td>
									<td></td>
									<td></td>
									<td align="center">'.$resultverifica[0][34].'%</td>
									</tr>
									<tr  class="texto_subtitulo">
										<td colspan="15" align="center">
											<p><span class="texto_negrita">Captura de notas</span></p>
										</td>
									</tr>
									<tr class="cuadro_color">
										<td align="center">CODIGO</td>
										<td align="center">NOMBRE</td>
										<td align="center">P1</td>
										<td align="center">P2</td>
										<td align="center">P3</td>
										<td align="center">P4</td>
										<td align="center">P5</td>
										<td align="center">P6</td>
										<td align="center">LAB</td>
										<td align="center">EXA</td>
										<td align="center">HAB</td>
										<td align="center">No. Fallas</td>
										<td align="center">ACU</td>
										<td align="center">OBS</td>
										<td align="center">DEF</td>
									</tr>';
									if($fechahoy < $fecini || $fechahoy > $fecfin)
									{
										$i=0;
										while(isset($resultverifica[$i][0]))
										{
											echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
											<td align="right">'.$resultverifica[$i][7].'</td>
												<td>'.$resultverifica[$i][8].'</td>
												<td align="center">'.$resultverifica[$i][10].'</td>
												<td align="center">'.$resultverifica[$i][12].'</td>
												<td align="center">'.$resultverifica[$i][14].'</td>
												<td align="center">'.$resultverifica[$i][16].'</td>
												<td align="center">'.$resultverifica[$i][18].'</td>
												<td align="center">'.$resultverifica[$i][20].'</td>
												<td align="center">'.$resultverifica[$i][22].'</td>
												<td align="center">'.$resultverifica[$i][24].'</td>
												<td align="center">'.$resultverifica[$i][26].'</td>
												<td align="center">'.$resultverifica[$i][31].'</td>
												<td align="center">'.$resultverifica[$i][29].'</td>
												<td align="center">'.$resultverifica[$i][28].'</td>
											</tr>';
										$i++;
										}
									}
									else
									{
										$i=0;
										while(isset($resultverifica[$i][0]))
										{
											echo'<tr>
											<td align="left">'.$resultverifica[$i][7].'</td>
											<td>'.$resultverifica[$i][8].'</td>';
											if(($fechahoy < $rowfecnot[0][1]) || ($fechahoy > $rowfecnot[0][2]) || ($rowfecnot[0][1] == " ") || ($rowfecnot[0][2] == " ") || ($fechahoy > $rowfecnot[0][19])){
												echo '<td align="center">'.$resultverifica[$i][10].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_1'.$i.'" name="nota_1'.$i.'" onBlur="val_nota(\'nota_1'.$i.'\')" value="'.$resultverifica[$i][10].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
												
											}
											if(($fechahoy < $rowfecnot[0][3]) || ($fechahoy > $rowfecnot[0][4]) || ($rowfecnot[0][3] == " ") || ($rowfecnot[0][4] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][12].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_2'.$i.'" name="nota_2'.$i.'" onBlur="val_nota(\'nota_2'.$i.'\')" value="'.$resultverifica[$i][12].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][5]) || ($fechahoy > $rowfecnot[0][6]) || ($rowfecnot[0][5] == " ") || ($rowfecnot[0][6] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][14].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_3'.$i.'" name="nota_3'.$i.'" onBlur="val_nota(\'nota_3'.$i.'\')" value="'.$resultverifica[$i][14].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][7]) || ($fechahoy > $rowfecnot[0][8]) || ($rowfecnot[0][7] == " ") || ($rowfecnot[0][8] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][16].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_4'.$i.'" name="nota_4'.$i.'" onBlur="val_nota(\'nota_4'.$i.'\')" value="'.$resultverifica[$i][16].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][9]) || ($fechahoy > $rowfecnot[0][10]) || ($rowfecnot[0][9] == " ") || ($rowfecnot[0][10] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][18].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_5'.$i.'" name="nota_5'.$i.'" onBlur="val_nota(\'nota_5'.$i.'\')" value="'.$resultverifica[$i][18].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][11]) || ($fechahoy > $rowfecnot[0][12]) || ($rowfecnot[0][11] == " ") || ($rowfecnot[0][12] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][20].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="nota_6'.$i.'" name="nota_6'.$i.'" onBlur="val_nota(\'nota_6'.$i.'\')" value="'.$resultverifica[$i][20].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][13]) || ($fechahoy > $rowfecnot[0][14]) || ($rowfecnot[0][13] == " ") || ($rowfecnot[0][14] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][24].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="lab_1'.$i.'" name="lab_1'.$i.'" onBlur="val_nota(\'lab_1'.$i.'\')" value="'.$resultverifica[$i][24].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											if(($fechahoy < $rowfecnot[0][15]) || ($fechahoy > $rowfecnot[0][16]) || ($rowfecnot[0][15] == " ") || ($rowfecnot[0][16] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][22].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="exa_1'.$i.'" name="exa_1'.$i.'" onBlur="val_nota(\'exa_1'.$i.'\')" value="'.$resultverifica[$i][22].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)" ></td>';
												
											}
											if(($fechahoy < $rowfecnot[0][17]) || ($fechahoy > $rowfecnot[0][18]) || ($rowfecnot[0][17] == " ") || ($rowfecnot[0][18] == " ") || ($fechahoy > $rowfecnot[0][19]))
											{
												echo '<td align="center">'.$resultverifica[$i][26].'</td>';
											}
											else
											{
												echo '<td align="center"><input type="text" id="hab_1'.$i.'" name="hab_1'.$i.'" onBlur="val_nota(\'hab_1'.$i.'\')" value="'.$resultverifica[$i][26].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>';
											}
											echo '<td align="center"><input type="text" id="nf_1'.$i.'" name="nf_1'.$i.'" onBlur="val_nota(\'nf_1'.$i.'\')" value="'.$resultverifica[$i][35].'" size="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>
											<td align="center">'.$resultverifica[$i][31].'</td>
											<td>
												<select style="width:48px" id="obs_1'.$i.'" name="obs_1'.$i.'" value="'.$resultverifica[$i][29].'">
												<option>'.$resultverifica[$i][29].'</option>';
												$j=0;
												while(isset($rowobs[$j][0]))
												{
													echo '<option value='.$rowobs[$j][0].'>'.$rowobs[$j][0].' ' .$rowobs[$j][1].'</option>';
												$j++;
												}
												echo '</select>
											</td>
											<td align="center">'.$resultverifica[$i][28].'</td>
											<input type="hidden" name="cod_'.$i.'" size="10%" id="codigo" value="'.$resultverifica[$i][7].'" readonly style="text-align:right">
											<input type="hidden" name="nivel" size="10%" id="nivel" value="'.$valor[4].'" readonly style="text-align:right">
											</tr>';
										$i++;
										}
									}
								}
								else
								{
									echo "<p>La suma de los porcentajes no debe superar el 100%</p>";
								}
								
								
								?>
								
							<tr align='center'>
								<td colspan="16">
									<table class="tablaBase">
										<tr>
											
											<td align="center">
												
												<input value="Grabar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
											</td>
											<!--td align="center">
												<input type='hidden' name='nivel' value='<? echo $valor[4]?>'>
												<input type="submit" name="notdef" value="Calcular Acumulado">
											</td-->
											<td align="center">
												<input type='hidden' name='nivel' value='<? echo $valor[4]?>'>
												<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
											</td>
										</tr>
										<tr>
											<td align="center" colspan="3">
												<?
												include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
												include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
//												$total=count($resultado);
													
												setlocale(LC_MONETARY, 'en_US');
												$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
												$cripto=new encriptar();
												
												echo "<a href='";
												$variable="pagina=registro_notasDocente";
												$variable.="&opcion=reportes";
												$variable.="&asig=".$valor[1];
												$variable.="&id_grupo=".$valor[2];
												$variable.="&carrera=".$valor[3];
												$variable.="&nivel=".$valor[4];
												$variable.="&periodo=".$valor[10];
												$variable.="&docente=".$valor[0];
												//$variable.="&no_pagina=true";
												$variable=$cripto->codificar_url($variable,$configuracion);
												echo $indice.$variable."'";
												echo "title='Haga Click aqu&iacute; para ir a reporte de notas'>";
												?>
												<center><img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/reporte.png" border="0"></center>
												Ir a reporte de notas
												</a>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>		
						
		<?
	}
	
	
	//Contiene las validaciones y las sentencias SQL que guarda las notas en el sistema.
	function guardarNotasPregrado($configuracion)
	{
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
                
		unset($valor);
		$valor[0]=$usuario;
		$valor[1]=$_REQUEST['asig'];
		$valor[2]=$_REQUEST['id_grupo'];
		$valor[4]=$_REQUEST['nivel'];
		$valor[10]=$_REQUEST['periodo'];
                
		$verifica=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "notasparciales",$valor);
		$resultverifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $verifica, "busqueda");
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		//Rescatamos todos los parámetros en un arreglo, hacemos un recorrido por cada uno de ellos,
		//los comparamos con los valores de los porcentajes para hacer realizar las diferentes validaciones. 
                foreach($_REQUEST as $clave=>$valor)
		{
			if($clave=='p1'||$clave=='p2'||$clave=='p3'||$clave=='p4'||$clave=='p5'||$clave=='p6'||$clave=='pl'||$clave=='pe')
			{
				if((!is_numeric($valor))&&($valor!=NULL))
				{
					unset($valor);
					$valor[0]=1; //Mensaje 1
					$valor[1]=$clave;
					$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					
				}
				if(($_REQUEST['p1']=='0') || ($_REQUEST['p2']=='0') || ($_REQUEST['p3']=='0') || ($_REQUEST['p4']=='0') || ($_REQUEST['p5']=='0') || ($_REQUEST['p6']=='0'))
				{
					unset($valor);
					$valor[0]=15; //Mensaje 15
					$valor[1]=$clave;
					$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
				}
								
				//Sumamos los valores de los porcentajes pregrado	
				$valorP1=isset($_REQUEST['p1'])?($_REQUEST['p1'])*1:'';
				$valorP2=isset($_REQUEST['p2'])?($_REQUEST['p2'])*1:'';
				$valorP3=isset($_REQUEST['p3'])?($_REQUEST['p3'])*1:'';
				$valorP4=isset($_REQUEST['p4'])?($_REQUEST['p4'])*1:'';
				$valorP5=isset($_REQUEST['p5'])?($_REQUEST['p5'])*1:'';
				$valorP6=isset($_REQUEST['p6'])?($_REQUEST['p6'])*1:'';
				$valorPl=isset($_REQUEST['pl'])?($_REQUEST['pl'])*1:'';
				$valorPe=isset($_REQUEST['pe'])?($_REQUEST['pe'])*1:'';
				
				$valorpar1=isset($_REQUEST['par1'])?($_REQUEST['par1'])*1:'';
				$valorpar2=isset($_REQUEST['par2'])?($_REQUEST['par2'])*1:'';
				$valorpar3=isset($_REQUEST['par3'])?($_REQUEST['par3'])*1:'';
				$valorpar4=isset($_REQUEST['par4'])?($_REQUEST['par4'])*1:'';
				$valorpar5=isset($_REQUEST['par5'])?($_REQUEST['par5'])*1:'';
				$valorpar6=isset($_REQUEST['par6'])?($_REQUEST['par6'])*1:'';
				$valorplab=isset($_REQUEST['plab'])?($_REQUEST['plab'])*1:'';
				
				$acupor = $valorP1+$valorP2+$valorP3+$valorP4+$valorP5+$valorP6+$valorPl+$valorPe;
				$acupor1= $valorP1+$valorP2+$valorP3+$valorP4+$valorP5+$valorP6+$valorPl;
				$acupor2= $valorpar1+$valorpar2+$valorpar3+$valorpar4+$valorpar5+$valorpar6+$valorplab;
				$acupor3= $resultverifica[0][11]+$resultverifica[0][13]+$resultverifica[0][15]+$resultverifica[0][17]+$resultverifica[0][19]+$resultverifica[0][21]+$resultverifica[0][25];
				$acupor4= $acupor1+$acupor2;
				
				if($acupor>100){
					unset($valor);
					$valor[0]=2; //Mensaje 2
					$valor[1]=$clave;
                                        $valor[2]=$clave;
					$valor[10]=$_REQUEST['periodo'];
					$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
				}
				else if($acupor1>70 || $acupor2>70 || $acupor4>70)
				{
					unset($valor);
					$valor[0]=3; //Mensaje 3
					$valor[1]=$clave;
					$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					
				}
				else if ($acupor2<0)
				{
					unset($valor);
					$valor[0]=5; //Mensaje 5
					$valor[1]=$clave;
					$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);	
				}
				else
				{
					$valor[10]=$_REQUEST['periodo'];
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
					$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
					
					$ano=$resultAnioPer[0][0];
					$per=$resultAnioPer[0][1];
										
					unset($valor);
					$valor[0]=$usuario;
					$valor[1]=$_REQUEST['asig'];
					$valor[2]=$_REQUEST['id_grupo'];

                                        $qrypor="UPDATE ";
					$qrypor.="ACCURSOS ";
					$qrypor.="SET ";
					if(isset($_REQUEST['p1'])&&$_REQUEST['p1']!='')
					{
						$qrypor.="CUR_PAR1 = ".$_REQUEST['p1'].", ";	
					}
					if(isset($_REQUEST['p2'])&&$_REQUEST['p2']!='')
					{
						$qrypor.="CUR_PAR2 = ".$_REQUEST['p2'].", ";
					}
					if(isset($_REQUEST['p3'])&&$_REQUEST['p3']!='')
					{
						$qrypor.="CUR_PAR3 = ".$_REQUEST['p3'].", ";
					}
					if(isset($_REQUEST['p4'])&&$_REQUEST['p4']!='')
					{
						$qrypor.="CUR_PAR4 = ".$_REQUEST['p4'].", ";
					}
					if(isset($_REQUEST['p5'])&&$_REQUEST['p5']!='')
					{
						$qrypor.="CUR_PAR5 = ".$_REQUEST['p5'].", ";
					}
					if(isset($_REQUEST['p6'])&&$_REQUEST['p6']!='')
					{
						$qrypor.="CUR_PAR6 = ".$_REQUEST['p6'].", ";
					}
					if(isset($_REQUEST['pl'])&&$_REQUEST['pl']!='')
					{
						$qrypor.="CUR_LAB = ".$_REQUEST['pl'].", ";
					}
					if(isset($_REQUEST['pe'])&&$_REQUEST['pe']!='')
					{
						$qrypor.="CUR_EXA = ".$_REQUEST['pe'].", ";
					}
					$varPh=isset($_REQUEST['ph'])?$_REQUEST['ph']:'';
					//$qrypor.=",CUR_HAB = ".$varPh." ";
                                        $qrypor=  trim($qrypor," ,");
					$qrypor.=" WHERE ";
					$qrypor.="CUR_APE_ANO = '".$ano."' ";
					$qrypor.="AND ";
					$qrypor.="CUR_APE_PER = '".$per."' ";
					$qrypor.="AND ";
					$qrypor.="CUR_ASI_COD = '".$valor[1]."' ";
					$qrypor.="AND ";
					$qrypor.="CUR_ID ='".$valor[2]."'";
					
					$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qrypor, "qrypor");
				}
				
			}
		}

		for ($i=0; $i<$resultverifica[0][32]; $i++)
		{
			$ac = ($resultverifica[$i][31]);
			if($ac > 0 && $_REQUEST[sprintf('obs_1%d', $i)]==19){
				unset($valor);
				$valor[0]=6; //Mensaje 6
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);	
			}
			if($ac > 0 && $_REQUEST[sprintf('obs_1%d', $i)]==20){
				unset($valor);
				$valor[0]=7; //Mensaje 7
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			
			//Validamos que lasnotas digitadas sean de tipo numerico, y que esten entre 0 y 50.
			
			foreach($_REQUEST as $clave=>$valor)
			{
				
				$nota_1=isset($_REQUEST[sprintf('nota_1%d', $i)])?$_REQUEST[sprintf('nota_1%d', $i)]:"";
				$nota_2=isset($_REQUEST[sprintf('nota_2%d', $i)])?$_REQUEST[sprintf('nota_2%d', $i)]:"";
				$nota_3=isset($_REQUEST[sprintf('nota_3%d', $i)])?$_REQUEST[sprintf('nota_3%d', $i)]:"";
				$nota_4=isset($_REQUEST[sprintf('nota_4%d', $i)])?$_REQUEST[sprintf('nota_4%d', $i)]:"";
				$nota_5=isset($_REQUEST[sprintf('nota_5%d', $i)])?$_REQUEST[sprintf('nota_5%d', $i)]:"";
				$nota_6=isset($_REQUEST[sprintf('nota_6%d', $i)])?$_REQUEST[sprintf('nota_6%d', $i)]:"";
				$lab_1=isset($_REQUEST[sprintf('lab_1%d', $i)])?$_REQUEST[sprintf('lab_1%d', $i)]:"";
				$exa_1=isset($_REQUEST[sprintf('exa_1%d', $i)])?$_REQUEST[sprintf('exa_1%d', $i)]:"";
				$hab_1=isset($_REQUEST[sprintf('hab_1%d', $i)])?$_REQUEST[sprintf('hab_1%d', $i)]:"";

				if((($valor==$nota_1))
				||(($valor==$nota_2))
				||(($valor==$nota_3))
				||(($valor==$nota_4))
				||(($valor==$nota_5))
				||(($valor==$nota_6))
				||(($valor==$lab_1))
				||(($valor==$exa_1))
				||(($valor==$hab_1)))
				{
					
					
					$longitud = strlen($valor); 
					if((!is_numeric($valor))&&($valor!=NULL))
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=8; //Mensaje 8
						$valor[1]=$nonum;
						$valor[2]=$clave;
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					}
					elseif($longitud>=3)
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=4; //Mensaje 4
						$valor[1]=$nonum;
						$valor[2]=$clave;
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					}
					elseif(($valor<0)&&($valor!=NULL))
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=9; //Mensaje 9
						$valor[1]=$nonum;
						$valor[2]=$clave;
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					}
					
					elseif(($valor>=51)&&($valor!=NULL))
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=10; //Mensaje 10
						$valor[1]=$nonum;
						$valor[2]=$clave;
						
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
						
					}
					
					else
					{
						$cierto=1;	
					}
				}
				if(isset($_REQUEST[sprintf('nf_1%d', $i)]))
				{
					if(!is_numeric($_REQUEST[sprintf('nf_1%d', $i)]))
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=13; //Mensaje 13
						$valor[1]=$nonum;
						$valor[2]=$clave;
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					}
					elseif($_REQUEST[sprintf('nf_1%d', $i)]>=99 || $_REQUEST[sprintf('nf_1%d', $i)]<0)
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=14; //Mensaje 14
						$valor[1]=$nonum;
						$valor[2]=$clave;
						
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					}
					else
					{
						$cierto=2;
					}	
				}
			}
			
			if(($valorP1=="") && ($_REQUEST[sprintf('nota_1%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);	
			}
			elseif(($valorP2=="") && ($_REQUEST[sprintf('nota_2%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			elseif(($valorP3=="") && ($_REQUEST[sprintf('nota_3%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			elseif(($valorP4=="") && ($_REQUEST[sprintf('nota_4%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			elseif(($valorP5=="") && ($_REQUEST[sprintf('nota_5%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			elseif(($valorP6=="") && ($_REQUEST[sprintf('nota_6%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			elseif(($valorPl=="") && ($lab_1!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			else
			{
				$cierto=3;	
			}
			
			unset($valor);
			$valor[0]=$usuario;
			$valor[1]=$_REQUEST['asig'];
			$valor[2]=$_REQUEST['id_grupo'];
			$valor[3]=$resultverifica[0][33];
			
			if($cierto==1 || $cierto==2 || $cierto==3)
			{

				$consulta = "UPDATE ACINS SET ";
				//Nota 1
				if(isset($_REQUEST[sprintf('nota_1%d', $i)]))
				{
					if(($nota_1=="")||( isset($nonum) && $nota_1==$nonum ))
					{
						$consulta.= "INS_NOTA_PAR1=NULL,"; 
					}
					else
					{
						$consulta.= "INS_NOTA_PAR1 ='".$_REQUEST[sprintf('nota_1%d', $i)]."', ";
					}
				}
				
				//Nota 2 
				if(isset($_REQUEST[sprintf('nota_2%d', $i)]))
				{
					if(($nota_2=="")||( isset($nonum) && $nota_2==$nonum ))
					{
						$consulta.= "INS_NOTA_PAR2 =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_PAR2 ='".$_REQUEST[sprintf('nota_2%d', $i)]."', ";
					}
				}
				
				//Nota 3 
				if(isset($_REQUEST[sprintf('nota_3%d', $i)]))
				{
					if(($nota_3=="")||( isset($nonum) && $nota_3==$nonum ))
					{
						$consulta.= "INS_NOTA_PAR3 =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_PAR3 ='".$_REQUEST[sprintf('nota_3%d', $i)]."', ";
					}
				}
				
				//Nota 4
				if(isset($_REQUEST[sprintf('nota_4%d', $i)]))
				{
					if(($nota_4=="")||( isset($nonum) && $nota_4==$nonum ))
					{
						$consulta.= "INS_NOTA_PAR4 =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_PAR4 ='".$_REQUEST[sprintf('nota_4%d', $i)]."', ";
					}
				}
				//Nota 5 
				if(isset($_REQUEST[sprintf('nota_5%d', $i)]))
				{
					if(($nota_5=="")||( isset($nonum) && $nota_5==$nonum ))
					{
						$consulta.= "INS_NOTA_PAR5 =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_PAR5 ='".$_REQUEST[sprintf('nota_5%d', $i)]."', ";
					}
				}
				//Nota 6
				if(isset($_REQUEST[sprintf('nota_6%d', $i)]))
				{
					if(($nota_6=="")||( isset($nonum) && $nota_6==$nonum ))
					{
						$consulta.= "INS_NOTA_PAR6 =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_PAR6 ='".$_REQUEST[sprintf('nota_6%d', $i)]."', ";
					}
				}
				//Laboratorio 
				if(isset($_REQUEST[sprintf('lab_1%d', $i)]))
				{
					if(($lab_1=="")||( isset($nonum) && $lab_1==$nonum ))
					{
						$consulta.= "INS_NOTA_LAB =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_LAB ='".$_REQUEST[sprintf('lab_1%d', $i)]."', ";
					}
				}
				//ex�men 
				if(isset($_REQUEST[sprintf('exa_1%d', $i)]))
				{
					if(($exa_1=="")||( isset($nonum) && $exa_1==$nonum ))
					{
						$consulta.= "INS_NOTA_EXA =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_EXA ='".$_REQUEST[sprintf('exa_1%d', $i)]."', ";
					}
				}
				//habilitacion
				if(isset($_REQUEST[sprintf('hab_1%d', $i)]))
				{
					if(($_REQUEST[sprintf('hab_1%d', $i)]=="")||($_REQUEST[sprintf('hab_1%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_HAB =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_HAB ='".$_REQUEST[sprintf('hab_1%d', $i)]."', ";
					}
				}
				//Numero de fallas
				if(isset($_REQUEST[sprintf('nf_1%d', $i)]))
				{
					if(($_REQUEST[sprintf('nf_1%d', $i)]=="")||(isset($nonum) && $_REQUEST[sprintf('nf_1%d', $i)]==$nonum))
					{
						$consulta.= "INS_TOT_FALLAS =0 ,";
					}
					else
					{
						$consulta.= "INS_TOT_FALLAS ='".$_REQUEST[sprintf('nf_1%d', $i)]."', ";
					}
				}
				//bac
				/*if($ac=="")
				{
					$consulta.= "INS_NOTA_ACU =NULL ,";
				}
				else
				{
					$consulta.= "INS_NOTA_ACU ='".$ac."', ";
				}
				if(isset($_REQUEST[sprintf('acu_%d', $i)]))
				{
					if(($_REQUEST[sprintf('acu_%d', $i)]=="")||($_REQUEST[sprintf('acu_%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_ACU =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_ACU ='".$_REQUEST[sprintf('acu_%d', $i)]."', ";
					}
				}*/
			
				//observaciones
				if($_REQUEST[sprintf('obs_1%d', $i)]=="")
				{
					$consulta.= "INS_OBS = 0 ,";
				}
				else
				{
					$consulta.= "INS_OBS = '".$_POST[sprintf('obs_1%d', $i)]."', ";
				}
				$consulta.= "INS_USUARIO = '".$valor[0]."' ";
				$consulta.=" WHERE ";
				$consulta.="INS_ANO = '".$ano."' ";
				$consulta.="AND ";
				$consulta.="INS_PER = ".$per." ";
				$consulta.="AND ";
				$consulta.="INS_ASI_COD ='".$valor[1]."' ";
				$consulta.="AND ";
				$consulta.="INS_GR ='".$valor[2]."' ";
				$consulta.="AND ";
				$consulta.="INS_EST_COD ='".$_REQUEST[sprintf('cod_%d',$i)]."'";
				
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $consulta, "consulta");
				//$afectados=$this->totalAfectados($configuracion,$this->accesoOracle); //Esta linea es para verificar si se guardaron los registros en la base de datos;
				
				if(isset($resultado))
				{
					$cierto=4;
				}
			}
		}
			
		//Cálculo del aculmulado y nota definitiva
		unset($valor);
		$valor[0]=$usuario;
		$valor[1]=$_REQUEST['asig'];
		$valor[2]=$_REQUEST['id_grupo'];
		$valor[3]=$_REQUEST['cra'];
		$valor[4]=$_REQUEST['nivel'];
		$valor[5]=$ano;
		$valor[6]=$per;
		//$valor[7]=$_REQUEST['carrera'];
		$valor[10]=$_REQUEST['periodo'];
		$calc= "BEGIN pck_pr_notaspar.pra_calnotdef_cur(".$valor[5].", ".$valor[6].", ".$valor[1].", ".$valor[2]."); END; ";
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $calc, "calc");
		
		if($cierto==4)
		{
			$valor[4]=$_REQUEST['nivel'];
			$valor[10]=$_REQUEST['periodo'];
			$this->redireccionarInscripcion($configuracion,"registroexitosoPregrado",$valor);
		}
	}
	
	
	//Contiene las validaciones y las sentencias SQL que guarda las notas en el sistema.
	function guardarNotasPosgrado($configuracion)
	{
            $nonum='';
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		unset($valor);
		$valor[0]=$usuario;
		$valor[1]=$_REQUEST['asig'];
		$valor[2]=$_REQUEST['id_grupo'];
		$valor[10]=$_REQUEST['periodo'];
		$verifica=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "notasparciales",$valor);
		$resultverifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $verifica, "busqueda");
		
		//Rescatamos todos los parámetros en un arreglo, hacemos un recorrido por cada uno de ellos,
		//los comparamos con los valores de los porcentajes para hacer realizar las diferentes validaciones. 	
		foreach($_REQUEST as $clave=>$valor)
		{
			if($clave=='p1'||$clave=='p2'||$clave=='p3'||$clave=='p4'||$clave=='p5'||$clave=='p6'||$clave=='pl'||$clave=='pe')
			{
				if((!is_numeric($valor))&&($valor!=NULL))
				{
					unset($valor);
					$valor[0]=1; //Mensaje 1
					$valor[1]=$clave;
					$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					
				}
				
				//Sumamos los valores de los porcentajes posgrado				
				$valorP1=isset($_REQUEST['p1'])?($_REQUEST['p1'])*1:'';
				$valorP2=isset($_REQUEST['p2'])?($_REQUEST['p2'])*1:'';
				$valorP3=isset($_REQUEST['p3'])?($_REQUEST['p3'])*1:'';
				$valorP4=isset($_REQUEST['p4'])?($_REQUEST['p4'])*1:'';
				$valorP5=isset($_REQUEST['p5'])?($_REQUEST['p5'])*1:'';
				$valorP6=isset($_REQUEST['p6'])?($_REQUEST['p6'])*1:'';
				$valorPl=isset($_REQUEST['pl'])?($_REQUEST['pl'])*1:'';
				$valorPe=isset($_REQUEST['pe'])?($_REQUEST['pe'])*1:'';
				
				$valorpar1=isset($_REQUEST['par1'])?($_REQUEST['par1'])*1:'';
				$valorpar2=isset($_REQUEST['par2'])?($_REQUEST['par2'])*1:'';
				$valorpar3=isset($_REQUEST['par3'])?($_REQUEST['par3'])*1:'';
				$valorpar4=isset($_REQUEST['par4'])?($_REQUEST['par4'])*1:'';
				$valorpar5=isset($_REQUEST['par5'])?($_REQUEST['par5'])*1:'';
				$valorpar6=isset($_REQUEST['par6'])?($_REQUEST['par6'])*1:'';
				$valorplab=isset($_REQUEST['plab'])?($_REQUEST['plab'])*1:'';
				$valorpexa=isset($_REQUEST['pexa'])?($_REQUEST['pexa'])*1:'';

                                $acupor = $valorP1+$valorP2+$valorP3+$valorP4+$valorP5+$valorP6+$valorPl+$valorPe;
				$acupor1 = $valorP1+$valorP2+$valorP3+$valorP4+$valorP5+$valorP6+$valorPl+$valorPe;
				$acupor2 = $valorpar1+$valorpar2+$valorpar3+$valorpar4+$valorpar5+$valorpar6+$valorplab+$valorpexa;
				$acupor3 = $resultverifica[0][11]+$resultverifica[0][13]+$resultverifica[0][15]+$resultverifica[0][17]+$resultverifica[0][19]+$resultverifica[0][21]+$resultverifica[0][25];
				$acupor4 = $acupor1+$acupor2;
				
				if($acupor>100){
					unset($valor);
					$valor[0]=2; //Mensaje 2
					$valor[1]=$clave;
					$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
				}
				else if($acupor1>100 || $acupor2>100 || $acupor4>100)
				{
					unset($valor);
					$valor[0]=2; //Mensaje 3
					$valor[1]=$clave;
					$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					
				}
				else if ($acupor2<0)
				{
					unset($valor);
					$valor[0]=5; //Mensaje 5
					$valor[1]=$clave;
					$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);	
				}
				else
				{
					$valor[10]=$_REQUEST['periodo'];
					$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
					$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
					$ano=$resultAnioPer[0][0];
					$per=$resultAnioPer[0][1];
					unset($valor);
					$valor[0]=$usuario;
					$valor[1]=$_REQUEST['asig'];
					$valor[2]=$_REQUEST['id_grupo'];
					
					$qrypor="UPDATE ";
					$qrypor.="ACCURSOS ";
					$qrypor.="SET ";
					if(isset($_REQUEST['p1'])&&$_REQUEST['p1']!='')
					{
						$qrypor.="CUR_PAR1 = ".$_REQUEST['p1'].", ";	
					}
					if(isset($_REQUEST['p2'])&&$_REQUEST['p2']!='')
					{
						$qrypor.="CUR_PAR2 = ".$_REQUEST['p2'].", ";
					}
					if(isset($_REQUEST['p3'])&&$_REQUEST['p3']!='')
					{
						$qrypor.="CUR_PAR3 = ".$_REQUEST['p3'].", ";
					}
					if(isset($_REQUEST['p4'])&&$_REQUEST['p4']!='')
					{
						$qrypor.="CUR_PAR4 = ".$_REQUEST['p4'].", ";
					}
					if(isset($_REQUEST['p5'])&&$_REQUEST['p5']!='')
					{
						$qrypor.="CUR_PAR5 = ".$_REQUEST['p5'].", ";
					}
					if(isset($_REQUEST['p6'])&&$_REQUEST['p6']!='')
					{
						$qrypor.="CUR_PAR6 = ".$_REQUEST['p6'].", ";
					}
					if(isset($_REQUEST['pl'])&&$_REQUEST['pl']!='')
					{
						$qrypor.="CUR_LAB = ".$_REQUEST['pl'].", ";
					}
					if(isset($_REQUEST['pe'])&&$_REQUEST['pe']!='')
					{
						$qrypor.="CUR_EXA = ".$_REQUEST['pe'].", ";
					}
					//$qrypor.="CUR_HAB = '".$_REQUEST['ph']."' ";
                                        $qrypor=  trim($qrypor," ,");

                                        $qrypor.=" WHERE ";
					$qrypor.="CUR_APE_ANO = '".$ano."' ";
					$qrypor.="AND ";
					$qrypor.="CUR_APE_PER = '".$per."' ";
					$qrypor.="AND ";
					$qrypor.="CUR_ASI_COD = '".$valor[1]."' ";
					$qrypor.="AND ";
					$qrypor.="CUR_ID ='".$valor[2]."'";
					
					$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qrypor, "qrypor");
				}
				
			}
		}
		for ($i=0; $i<$resultverifica[0][32]; $i++)
		{
			$ac = ($resultverifica[$i][31]);
			if($ac > 0 && $_REQUEST[sprintf('obs_1%d', $i)]==19){
				unset($valor);
				$valor[0]=6; //Mensaje 6
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);	
			}
			if($ac > 0 && $_REQUEST[sprintf('obs_1%d', $i)]==20){
				unset($valor);
				$valor[0]=7; //Mensaje 7
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			
			//Validamos que lasnotas digitadas sean de tipo num�rico, y que esten entre 0 y 50.
			foreach($_REQUEST as $clave=>$valor)
			{
				$nota_1=isset($_REQUEST[sprintf('nota_1%d', $i)])?$_REQUEST[sprintf('nota_1%d', $i)]:"";
				$nota_2=isset($_REQUEST[sprintf('nota_2%d', $i)])?$_REQUEST[sprintf('nota_2%d', $i)]:"";
				$nota_3=isset($_REQUEST[sprintf('nota_3%d', $i)])?$_REQUEST[sprintf('nota_3%d', $i)]:"";
				$nota_4=isset($_REQUEST[sprintf('nota_4%d', $i)])?$_REQUEST[sprintf('nota_4%d', $i)]:"";
				$nota_5=isset($_REQUEST[sprintf('nota_5%d', $i)])?$_REQUEST[sprintf('nota_5%d', $i)]:"";
				$nota_6=isset($_REQUEST[sprintf('nota_6%d', $i)])?$_REQUEST[sprintf('nota_6%d', $i)]:"";
				$lab_1=isset($_REQUEST[sprintf('lab_1%d', $i)])?$_REQUEST[sprintf('lab_1%d', $i)]:"";
				$exa_1=isset($_REQUEST[sprintf('exa_1%d', $i)])?$_REQUEST[sprintf('exa_1%d', $i)]:"";
				$hab_1=isset($_REQUEST[sprintf('hab_1%d', $i)])?$_REQUEST[sprintf('hab_1%d', $i)]:"";
				
				
				if((($valor==$nota_1))
				||(($valor==$nota_2))
				||(($valor==$nota_3))
				||(($valor==$nota_4))
				||(($valor==$nota_5))
				||(($valor==$nota_6))
				||(($valor==$lab_1))
				||(($valor==$exa_1))
				||(($valor==$hab_1)))
				{
					$longitud = strlen($valor); 
					if((!is_numeric($valor))&&($valor!=NULL)&&(!is_int($valor)))
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=8; //Mensaje 8
						$valor[1]=$nonum;
						$valor[2]=$clave;
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					}
					
					elseif($longitud>=3)
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=4; //Mensaje 4
						$valor[1]=$nonum;
						$valor[2]=$clave;
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					}
					
					elseif(($valor<0)&&($valor!=NULL))
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=9; //Mensaje 9
						$valor[1]=$nonum;
						$valor[2]=$clave;
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					}
					
					elseif(($valor>=51)&&($valor!=NULL))
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=10; //Mensaje 10
						$valor[1]=$nonum;
						$valor[2]=$clave;
						
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
						
					}
					
					else
					{
						$cierto=1;	
					}
				}
				if(isset($_REQUEST[sprintf('nf_1%d', $i)]))
				{
					if(!is_numeric($_REQUEST[sprintf('nf_1%d', $i)]))
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=13; //Mensaje 13
						$valor[1]=$nonum;
						$valor[2]=$clave;
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					}
					elseif($_REQUEST[sprintf('nf_1%d', $i)]>=99 || $_REQUEST[sprintf('nf_1%d', $i)]<0)
					{
						$nonum=$valor;
						unset($valor);
						$valor[0]=14; //Mensaje 14
						$valor[1]=$nonum;
						$valor[2]=$clave;
						
						$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
					}
					else
					{
						$cierto=2;
					}	
				}
			}
			
			if(($_REQUEST['p1']=="") && ($_REQUEST[sprintf('nota_1%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);	
			}
			elseif(($_REQUEST['p2']=="") && ($_REQUEST[sprintf('nota_2%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			elseif(($_REQUEST['p3']=="") && ($_REQUEST[sprintf('nota_3%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			elseif(($_REQUEST['p4']=="") && ($_REQUEST[sprintf('nota_4%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			elseif(($_REQUEST['p5']=="") && ($_REQUEST[sprintf('nota_5%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			elseif(($_REQUEST['p6']=="") && ($_REQUEST[sprintf('nota_6%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			elseif(($_REQUEST['pl']=="") && ($_REQUEST[sprintf('lab_1%d', $i)]!=""))
			{
				unset($valor);
				$valor[0]=12; //Mensaje 12
				$this->redireccionarInscripcion($configuracion,"msgErrores",$valor);
			}
			else
			{
				$cierto=3;	
			}
			
			unset($valor);
			$valor[0]=$usuario;
			$valor[1]=$_REQUEST['asig'];
			$valor[2]=$_REQUEST['id_grupo'];
			$valor[3]=$resultverifica[0][33];
			
			if($cierto==1 || $cierto==2 || $cierto==3)
			{
				$consulta = "UPDATE ACINS SET ";
				//Nota 1
				if(isset($_REQUEST[sprintf('nota_1%d', $i)]))
				{
					if(($_REQUEST[sprintf('nota_1%d', $i)]=="")||($_REQUEST[sprintf('nota_1%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_PAR1 =NULL,"; 
					}
					else
					{
						$consulta.= "INS_NOTA_PAR1 ='".$_REQUEST[sprintf('nota_1%d', $i)]."', ";
					}
				}
				
				//Nota 2 
				if(isset($_REQUEST[sprintf('nota_2%d', $i)]))
				{
					if(($_REQUEST[sprintf('nota_2%d', $i)]=="")||($_REQUEST[sprintf('nota_2%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_PAR2 =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_PAR2 ='".$_REQUEST[sprintf('nota_2%d', $i)]."', ";
					}
				}
				
				//Nota 3 
				if(isset($_REQUEST[sprintf('nota_3%d', $i)]))
				{
					if(($_REQUEST[sprintf('nota_3%d', $i)]=="")||($_REQUEST[sprintf('nota_3%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_PAR3 =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_PAR3 ='".$_REQUEST[sprintf('nota_3%d', $i)]."', ";
					}
				}
				
				//Nota 4
				if(isset($_REQUEST[sprintf('nota_4%d', $i)]))
				{
					if(($_REQUEST[sprintf('nota_4%d', $i)]=="")||($_REQUEST[sprintf('nota_4%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_PAR4 =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_PAR4 ='".$_REQUEST[sprintf('nota_4%d', $i)]."', ";
					}
				}
				//Nota 5 
				if(isset($_REQUEST[sprintf('nota_5%d', $i)]))
				{
					if(($_REQUEST[sprintf('nota_5%d', $i)]=="")||($_REQUEST[sprintf('nota_5%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_PAR5 =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_PAR5 ='".$_REQUEST[sprintf('nota_5%d', $i)]."', ";
					}
				}
				//Nota 6
				if(isset($_REQUEST[sprintf('nota_6%d', $i)]))
				{
					if(($_REQUEST[sprintf('nota_6%d', $i)]=="")||($_REQUEST[sprintf('nota_6%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_PAR6 =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_PAR6 ='".$_REQUEST[sprintf('nota_6%d', $i)]."', ";
					}
				}
				//Laboratorio 
				if(isset($_REQUEST[sprintf('lab_1%d', $i)]))
				{
					if(($_REQUEST[sprintf('lab_1%d', $i)]=="")||($_REQUEST[sprintf('lab_1%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_LAB =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_LAB ='".$_REQUEST[sprintf('lab_1%d', $i)]."', ";
					}
				}
				//ex�men 
				if(isset($_REQUEST[sprintf('exa_1%d', $i)]))
				{
					if(($_REQUEST[sprintf('exa_1%d', $i)]=="")||($_REQUEST[sprintf('exa_1%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_EXA =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_EXA ='".$_REQUEST[sprintf('exa_1%d', $i)]."', ";
					}
				}
				//habilitacion
				if(isset($_REQUEST[sprintf('hab_1%d', $i)]))
				{
					if(($_REQUEST[sprintf('hab_1%d', $i)]=="")||($_REQUEST[sprintf('hab_1%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_HAB =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_HAB ='".$_REQUEST[sprintf('hab_1%d', $i)]."', ";
					}
				}
				//Numero de fallas
				if(isset($_REQUEST[sprintf('nf_1%d', $i)]))
				{
					if(($_REQUEST[sprintf('nf_1%d', $i)]=="")||($_REQUEST[sprintf('nf_1%d', $i)]==$nonum))
					{
						$consulta.= "INS_TOT_FALLAS =0 ,";
					}
					else
					{
						$consulta.= "INS_TOT_FALLAS ='".$_REQUEST[sprintf('nf_1%d', $i)]."', ";
					}
				}
				//bac
				/*if($ac=="")
				{
					$consulta.= "INS_NOTA_ACU =NULL ,";
				}
				else
				{
					$consulta.= "INS_NOTA_ACU ='".$ac."', ";
				}
				if(isset($_REQUEST[sprintf('acu_%d', $i)]))
				{
					if(($_REQUEST[sprintf('acu_%d', $i)]=="")||($_REQUEST[sprintf('acu_%d', $i)]==$nonum))
					{
						$consulta.= "INS_NOTA_ACU =NULL ,";
					}
					else
					{
						$consulta.= "INS_NOTA_ACU ='".$_REQUEST[sprintf('acu_%d', $i)]."', ";
					}
				}*/
			
				//observaciones
				if($_REQUEST[sprintf('obs_1%d', $i)]=="")
				{
					$consulta.= "INS_OBS = 0 ,";
				}
				else
				{
					$consulta.= "INS_OBS = '".$_POST[sprintf('obs_1%d', $i)]."', ";
				}
				$consulta.= "INS_USUARIO = '".$valor[0]."' ";
				$consulta.=" WHERE ";
				$consulta.="INS_ANO = '".$ano."' ";
				$consulta.="AND ";
				$consulta.="INS_PER = ".$per." ";
				$consulta.="AND ";
				$consulta.="INS_ASI_COD ='".$valor[1]."' ";
				$consulta.="AND ";
				$consulta.="INS_GR ='".$valor[2]."' ";
				$consulta.="AND ";
				$consulta.="INS_EST_COD ='".$_REQUEST[sprintf('cod_%d',$i)]."'";
				
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $consulta, "consulta");
				//$afectados=$this->totalAfectados($configuracion,$this->accesoOracle); //Esta linea es para verificar si se guardaron los registros en la base de datos;
				
				if(isset($resultado))
				{
					$cierto=4;
				}
			}
		}
			
		//Cálculo del aculmulado y nota definitiva
		unset($valor);
		$valor[0]=$usuario;
		$valor[1]=$_REQUEST['asig'];
		$valor[2]=$_REQUEST['id_grupo'];
		$valor[3]=$_REQUEST['cra'];
		$valor[4]=$_REQUEST['nivel'];
		$valor[5]=$ano;
		$valor[6]=$per;
		//$valor[7]=$_REQUEST['carrera'];
		$valor[10]=$_REQUEST['periodo'];
		$calc= "BEGIN pck_pr_notaspar.pra_calnotdef_cur(".$valor[5].", ".$valor[6].", ".$valor[1].", ".$valor[2]."); END; ";
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $calc, "calc");
		
		if($cierto==4)
		{
			$valor[4]=$_REQUEST['nivel'];
			$valor[10]=$_REQUEST['periodo'];
			$this->redireccionarInscripcion($configuracion,"registroexitosoPosgrado",$valor);
		}
	}
		
	//Muestra los mensajes de errores que se puedan presentar.	
	function mensajesErrores($configuracion)
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
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
					$regresar="<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-1)' style='cursor:pointer;'>";
					$regresar.='<img src="';
					$regresar.= $configuracion["host"].$configuracion["site"].$configuracion["grafico"];
					$regresar.= '/back.png" border="0" style="cursor:pointer;" title="Click para regresar">';
					$regresar.= '<br>Regresar</a></center>';
					
					if($_REQUEST['mensaje']==1)
					{
						$valor[0]= $_REQUEST['mensaje'];
						$cadena="El porcentaje digitado, correspondiente a " .$_REQUEST['valor']. ",  NO es un valor num&eacute;rico.";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==2)
					{
						$cadena="Revise los porcentajes de las notas, superan el 100%.";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==3)
					{
						$cadena="Revise los porcentajes 1, 2, 3, 4 , 5, 6 y LAB. Recuerde que la suma no debe superar el 70%.";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==4)
					{
						$cadena="El valor de la nota " .$_REQUEST['valor']. ",  NO es correcta, recuerde que debe ser digitada sin puntos (.) y sin comas (,).";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==5)
					{
						$cadena="Revise los porcentajes, no pueden ser inferior a 0.";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==6)
					{
						$cadena="La observaci&oacute;n 19 es solo para notas cualitativas.";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==7)
					{
						$cadena="La observaci&oacute;n 20 es solo para notas cualitativas.";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==8)
					{
						$cadena="La nota <b>".$_REQUEST['valor']."</b> no es un valor num&eacute;rico.";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==9)
					{
						$cadena="La nota <b>".$_REQUEST['valor']."</b> es menor que 0, no es permitida.";;
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==10)
					{
						$cadena="La nota <b>".$_REQUEST['valor']."</b> es mayor que 50, no es permitida.";;
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==11)
					{
						$cadena="<p>La nota <b>".$_REQUEST['valor']."</b> Registro exitoso.</p>";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==12)
					{
						$cadena="Falta digitar alg&uacute;n porcentaje, revise las columnas donde haya digitado notas.";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==13)
					{
						$cadena="El n&uacute;mero digitado para las fallas, no es num&eacute;rico.";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==14)
					{
						$cadena="<p>El n&uacute;mero digitado para las fallas, no puede ser menor a 0, ni mayor a 99.</p>";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					if($_REQUEST['mensaje']==15)
					{
						$cadena="<p>Los porcentejas no pueden tener valor igual a 0 (cero).</p>";
						alerta::sin_registro($configuracion,$cadena,$regresar);
					}
					?>	
				</td>
			</tr>
		</table><?
	}
	
	//Calcula el valor de la nota definitiva.
	function calculoDefinitiva($configuracion)
	{
		$valor[10]=$_REQUEST['periodo'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
						
		unset($valor);
		$valor[0]=$usuario;
		$valor[1]=$_REQUEST['asig'];
		$valor[2]=$_REQUEST['id_grupo'];
		$valor[3]=$_REQUEST['cra'];
		$valor[4]=$_REQUEST['nivel'];
		$valor[5]=$ano;
		$valor[6]=$per;
		//$valor[7]=$_REQUEST['carrera'];
		
		$calc= "BEGIN pck_pr_notaspar.pra_calnotdef_cur(".$valor[5].", ".$valor[6].", ".$valor[1].", ".$valor[2]."); END; ";
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $calc, "calc");
		if(isset($resultado) && $_REQUEST['nivel']=='PREGRADO')
		{
			$this->redireccionarInscripcion($configuracion,"registroexitosoPregrado",$valor);
		}
		if(isset($resultado) && $_REQUEST['nivel']=='POSGRADO')
		{
			$this->redireccionarInscripcion($configuracion,"registroexitosoPosgrado",$valor);
		}
	}

	
	function notasPerAnterior($configuracion)
	{
		$this->usuario=$_REQUEST['usuario'];

		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		
		if($_REQUEST['nivel']=='ANTERIOR')
		{
			$todos="PREGRADO','POSGRADO','EXTENSION','MAESTRIA','DOCTORADO";
			//unset($valor);
			$valor[0]=$usuario;
			$valor[4]=$todos;
		}
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "carreras",$valor);
		$resultCarreras=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");    
		$cuenta=count($resultCarreras);

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "acasperieventos",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");   
		$cuentaR=count($resultado);  
   
		$valor[10]='P';
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];    

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "listaClase",$valor);
		$resultLista=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if(!is_array($resultLista))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="Estimado Docente, usted no tiene carga registrada en ".$_REQUEST['nivel']."<br>";

			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);	
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
										<li> Para ver el reporte de notas, haga click en el nombre de la asignatura.</li>
									</ul>
								</td>
							</tr>
						</table>
						<br>
						<table class="formulario" align="center">
							<tr  class="bloquecentralencabezado">
								<td colspan="5" align="center">
									<p><span class="texto_negrita">REPORTE DE NOTAS <? echo $nivel.' PERIODO '.$ano.'-'.$per; ?></span></p>
								</td>
							</tr>
							<tr>
								<td colspan="5">
									Carga Acad&eacute;mica  <? echo $ano.'-'.$per.':'; ?>
								</td>
							</tr>
							
							
							<tr align='center'>
								<td align="center" height="10">C&oacute;digo</td>
								<td align="center" height="10">Asignatura</td>
								<td align="center" height="10">Grupo</td>
								<td align="center" height="10">Inscritos</td>
								<td align="center" height="10">Carrera</td>
							</tr>
							<tr class="bloquecentralcuerpo">
								<?php
								$i=0;
								while(isset($resultLista[$i][0]))
								{
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
								include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
//								$total=count($resultado);
									
								setlocale(LC_MONETARY, 'en_US');
								$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
								$cripto=new encriptar();
								
								$menu=new navegacion();
									if($resultLista[$i][12]=='PREGRADO' || $resultLista[$i][12]=='EXTENSION')
									{
										echo '<tr>
										<td align="left">
											'.$resultLista[$i][8].'
										</td>';
										echo "<td class='cuadro_plano centrar'>
											<a href='";
											$variable="pagina=registro_notasDocente";
											$variable.="&opcion=reportes";
											$variable.="&usuario=".$usuario;
											$variable.="&asig=".$resultLista[$i][8];
											$variable.="&id_grupo=".$resultLista[$i][10];
											$variable.="&carrera=".$resultLista[$i][4];
											$variable.="&nivel=".$resultLista[$i][12];
											$variable.="&periodo=".$valor[10];
											//$variable.="&no_pagina=true";
											$variable=$cripto->codificar_url($variable,$configuracion);
											echo $indice.$variable."'";
											echo "title='Digitar notas'>".$resultLista[$i][9]."</a>
										</td>";
										echo '<td align="center">'.$resultLista[$i][10].'</td>
										<td align="center">'.$resultLista[$i][11].'</td>
										<td align="left"><span class="Estilo3">'.$resultLista[$i][5].'</span></td></tr>';
									}
									else
									{
										echo '<tr>
										<td align="left">
											'.$resultLista[$i][8].'
										</td>';
										echo "<td class='cuadro_plano centrar'>
											<a href='";
											$variable="pagina=registro_notasDocente";
											$variable.="&opcion=reportes";
											$variable.="&usuario=".$usuario;
											$variable.="&asig=".$resultLista[$i][8]."";
											$variable.="&nivel=".$resultLista[$i][12]."";
											$variable.="&id_grupo=".$resultLista[$i][10]."";
											$variable.="&carrera=".$resultLista[$i][4]."";
											$variable.="&periodo=".$valor[10]."";  
											$variable.="&otro=";
											$variable=$cripto->codificar_url($variable,$configuracion);
											echo $indice.$variable."'";
											echo "title='Digitar notas'>".$resultLista[$i][9]."</a>
										</td>";
										echo '<td align="center">'.$resultLista[$i][10].'</td>
										<td align="center">'.$resultLista[$i][11].'</td>
										<td align="left"><span class="Estilo3">'.$resultLista[$i][5].'</span></td></tr>';
									}
								$i++;
								}
								?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?
		}
	}
 
	//Imprime el repore de notas.
	function ReporteNotas($configuracion)
	{
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$_REQUEST['usuario'];
		}
		
		$valor[10]=$_REQUEST['periodo'];
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		if(isset($_REQUEST['docente']))
		{
			$valor[0]=$_REQUEST['docente'];
		}
		else
		{
		      $valor[0]=$_REQUEST['usuario'];
		}
		$valor[1]=$_REQUEST['asig'];
		$valor[2]=$_REQUEST['id_grupo'];
		$valor[3]=$_REQUEST['carrera'];
		$valor[4]=$_REQUEST['nivel'];
		$valor[5]=$ano;
		$valor[6]=$per;
				
		$verifica=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "notasparciales",$valor);
		$resultverifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $verifica, "busqueda");
		?>
		<table class="formulario" align="center">
			<tr class="texto_subtitulo">
				<td class="" colspan="5" align="center">
					<p><span class="texto_negrita">REPORTE DE NOTAS PARCIALES PERIODO ACAD&Eacute;MICO <? echo $ano.'-'.$per; ?> </span></p>
				</td>
			</tr>
			<tr class="cuadro_color">
				<?
				echo '<td class="" align="left">'.$resultverifica[0][4].'</td>
				<td class="" >'.$resultverifica[0][5].'</td>
				<td class="" align="center"><b>Grupo</b></td>
				<td class="" align="center"><b>Inscritos</b></td>
				<td class="" align="center"><b>Periodo</b></td>
			</tr>
			<tr class="cuadro_color">
				<td class="" align="left">'.$resultverifica[0][0].'</td>
				<td class="" align="left">'.$resultverifica[0][1].'</td>
				<td class="" align="center">'.$resultverifica[0][36].'</td>
				<td class="" align="center">'.$resultverifica[0][32].'</td>
				<td class="" align="center">'.$resultverifica[0][2].'-'.$resultverifica[0][3].'</td>';
				?>
			</tr>
			<tr class="bloquecentralcuerpo">
			</tr>
		</table>
		<table class="formulario" align="center">
			<tr class="cuadro_color">
				<td colspan="2" class="cuadro_plano centrar">Porcentaje de Notas</td>
				<td class="cuadro_plano centrar">%1</td>
				<td class="cuadro_plano centrar">%2</td>
				<td class="cuadro_plano centrar">%3</td>
				<td class="cuadro_plano centrar">%4</td>
				<td class="cuadro_plano centrar">%5</td>
				<td class="cuadro_plano centrar">%6</td>
				<td class="cuadro_plano centrar">LAB</td>
				<td class="cuadro_plano centrar">EXA</td>
				<td class="cuadro_plano centrar">HAB</td>
				<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
				<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
				<td class="cuadro_plano centrar">&nbsp;&nbsp;&nbsp;</td>
				<td class="cuadro_plano centrar">SUM</td>
			</tr>
			<tr class="formulario">
				<td colspan="2" align="center"> </td>
				<?
					echo '<td align="center">'.$resultverifica[0][11].'</td>
					<td align="center">'.$resultverifica[0][13].'</td>
					<td align="center">'.$resultverifica[0][15].'</td>
					<td align="center">'.$resultverifica[0][17].'</td>
					<td align="center">'.$resultverifica[0][19].'</td>
					<td align="center">'.$resultverifica[0][21].'</td>
					<td align="center">'.$resultverifica[0][27].'</td>
					<td align="center">'.$resultverifica[0][23].'</td>
					<td align="center">70</td>
					<td></td>
					<td></td>
					<td></td>
					<td align="center">'.$resultverifica[0][34].'%</td>
					</tr>
					<tr class="cuadro_color">
						<td align="center">CODIGO</td>
						<td align="center">NOMBRE</td>
						<td align="center">P1</td>
						<td align="center">P2</td>
						<td align="center">P3</td>
						<td align="center">P4</td>
						<td align="center">P5</td>
						<td align="center">P6</td>
						<td align="center">LAB</td>
						<td align="center">EXA</td>
						<td align="center">HAB</td>
						<td align="center">No.<br>fallas</td>
						<td align="center">ACU</td>
						<td align="center">OBS</td>
						<td align="center">DEF</td>
					</tr>';
					
						$i=0;
						while(isset($resultverifica[$i][0]))
						{
							echo'<tr>
							<td align="center">'.$resultverifica[$i][7].'</td>
							<td>'.$resultverifica[$i][8].'</td>';
								echo '<td align="center">'.$resultverifica[$i][10].'</td>
								<td align="center">'.$resultverifica[$i][12].'</td>
								<td align="center">'.$resultverifica[$i][14].'</td>
								<td align="center">'.$resultverifica[$i][16].'</td>
								<td align="center">'.$resultverifica[$i][18].'</td>
								<td align="center">'.$resultverifica[$i][20].'</td>
								<td align="center">'.$resultverifica[$i][24].'</td>
								<td align="center">'.$resultverifica[$i][22].'</td>
								<td align="center">'.$resultverifica[$i][26].'</td>
								<td align="center">'.$resultverifica[$i][35].'</td>
								<td align="center">'.$resultverifica[$i][31].'</td>
								<td align="center">'.$resultverifica[$i][29].'</td>
								<td align="center">'.$resultverifica[$i][28].'</td>
							
							</tr>';
						$i++;
						}
				
								
				?>
				
			<tr align='center'>
				<td colspan="15">
					<table class="tablaBase">
						<tr>
							<td colspan="2">
							</td>
						</tr>
						<tr>
							<td align="center">
								<p align="center">_________________________</p>
							</td>
							<td align="center">
							<p align="center">_________________________</p>
							</td>
						</tr>
						<tr>
							<td align="center"><p align="center"><font size="2" face="Tahoma">Firma del Docente</td>
							<td align="center"><p align="center"><font size="2" face="Tahoma">Recibido</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		
		<table class="formulario">
			<tr>
				<td colspan="14" class="tabla_alerta">
					<center><input name="button" type="image" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/impresora.gif" border="0" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer;" title="Click par imprimir el reporte"></center>
				</td>
			</tr>
		</table>
		<?
	}
	
	function fechasNotas($configuracion)
	{
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechaactual",'');
		$rowfechoy=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$fechoy = $rowfechoy[0][0];
		
		$valor[10]=$_REQUEST['periodo'];    
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$valor[0]=$usuario;
		$valor[1]=$_REQUEST['asig'];
		$valor[2]=$_REQUEST['id_grupo'];
		$valor[3]=$_REQUEST['carrera'];
		$valor[4]=$_REQUEST['nivel'];
		$valor[5]=$ano;
		$valor[6]=$per;
				
		$valor[3]=$_REQUEST['carrera'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"fechasDigNotas",$valor);
		$reg2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		
		if(($fechoy < $reg2[0][1]) || ($fechoy > $reg2[0][2]) || ($reg2[0][1] == " ") || ($reg2[0][2] == " "))
		{
			$msgpar1 = '<span class="texto_subtitulo_rojo">CERRADO</span>';
		}
		else
		{
			$msgpar1 = '<span class="texto_subtitulo_verde">ABIERTO</span>';
		}
		
		if(($fechoy < $reg2[0][3]) || ($fechoy > $reg2[0][4]) || ($reg2[0][3] == " ") || ($reg2[0][4] == " "))
		{
			$msgpar2 = '<span class="texto_subtitulo_rojo">CERRADO</span>';
		}
		else
		{
			$msgpar2 = '<span class="texto_subtitulo_verde">ABIERTO</span>';
		}
		
		if(($fechoy < $reg2[0][5]) || ($fechoy > $reg2[0][6]) || ($reg2[0][5] == " ") || ($reg2[0][6] == " "))
		{
			$msgpar3 = '<span class="texto_subtitulo_rojo">CERRADO</span>';
		}
		else
		{
			$msgpar3 = '<span class="texto_subtitulo_verde">ABIERTO</span>';
		}
		
		if(($fechoy < $reg2[0][7]) || ($fechoy > $reg2[0][8]) || ($reg2[0][7] == " ") || ($reg2[0][8] == " "))
		{
			$msgpar4 = '<span class="texto_subtitulo_rojo">CERRADO</span>';
		}
		else
		{ 
		 	$msgpar4 = '<span class="texto_subtitulo_verde">ABIERTO</span>';
		}
		
		if(($fechoy < $reg2[0][9]) || ($fechoy > $reg2[0][10]) || ($reg2[0][9] == " ") || ($reg2[0][10] == " "))
		{
			$msgpar5 = '<span class="texto_subtitulo_rojo">CERRADO</span>';
		}
		else
		{
			$msgpar5 = '<span class="texto_subtitulo_verde">ABIERTO</span>';
		}
		
		if(($fechoy < $reg2[0][11]) || ($fechoy > $reg2[0][12]) || ($reg2[0][11] == " ") || ($reg2[0][12] == " "))
		{
			$msgpar6 = '<span class="texto_subtitulo_rojo">CERRADO</span>';
		}
		else
		{
			$msgpar6 = '<span class="texto_subtitulo_verde">ABIERTO</span>';
		}
		
		if(($fechoy < $reg2[0][13]) || ($fechoy > $reg2[0][14]) || ($reg2[0][13] == " ") || ($reg2[0][14] == " "))
		{
			$msglab = '<span class="texto_subtitulo_rojo">CERRADO</span>';
		}
		else
		{
			$msglab = '<span class="texto_subtitulo_verde">ABIERTO</span>';
		}
		
		if(($fechoy < $reg2[0][15]) || ($fechoy > $reg2[0][16]) || ($reg2[0][15] == " ") || ($reg2[0][16] == " "))
		{
			$msgexa = '<span class="texto_subtitulo_rojo">CERRADO</span>';
		}
		else
		{
			$msgexa = '<span class="texto_subtitulo_verde">ABIERTO</span>';
		}
		
		if(($fechoy < $reg2[0][17]) || ($fechoy > $reg2[0][18]) || ($reg2[0][17] == " ") || ($reg2[0][18] == " "))
		{
			$msghab = '<span class="texto_subtitulo_rojo">CERRADO</span>';
		}
		else
		{
			$msghab = '<span class="texto_subtitulo_verde">ABIERTO</span>';
		}
		?>
		<table class="Cuadricula">
		<caption class="texto_negrita">FECHAS DE DIGITACI&Oacute;N DE NOTAS PARCIALES</caption>
		<tr class="cuadro_color">
			<td width="76" align="center">PARCIALES</td>
			<td width="106" align="center">FECHA INICIAL</td>
			<td width="94" align="center">FECHA FINAL</td>
			<td width="100" align="center">PERMISOS</td>
		</tr>
		<?php
		$i=0;
		while(isset($reg2[$i][0]))
		{
			echo '<tr bordercolorlight="#CCCCCC">
				<td width="76" align="center">P1</td>
				<td width="106" align="center">'.$reg2[$i][1].'</td>
				<td width="94" align="center">'.$reg2[$i][2].'</td>
				<td width="100" align="center">'.$msgpar1.'</td>
			</tr>
			
			<tr bordercolorlight="#CCCCCC">
				<td width="76" align="center">P2</td>
				<td width="106" align="center">'.$reg2[$i][3].'</td>
				<td width="94" align="center">'.$reg2[$i][4].'</td>
				<td width="100" align="center">'.$msgpar2.'</td>
			</tr>
			
			<tr bordercolorlight="#CCCCCC">
				<td width="76" align="center">P3</td>
				<td width="106" align="center">'.$reg2[$i][5].'</td>
				<td width="94" align="center">'.$reg2[$i][6].'</td>
				<td width="100" align="center">'.$msgpar3.'</td>
			</tr>
			
			<tr bordercolorlight="#CCCCCC">
				<td width="76" align="center">P4</td>
				<td width="106" align="center">'.$reg2[$i][7].'</td>
				<td width="94" align="center">'.$reg2[$i][8].'</td>
				<td width="100" align="center">'.$msgpar4.'</td>
			</tr>
			
			<tr bordercolorlight="#CCCCCC">
				<td width="76" align="center">P5</td>
				<td width="106" align="center">'.$reg2[$i][9].'</td>
				<td width="94" align="center">'.$reg2[$i][10].'</td>
				<td width="100" align="center">'.$msgpar5.'</td>
			</tr>
			
			<tr bordercolorlight="#CCCCCC">
				<td width="76" align="center">P6</td>
				<td width="106" align="center">'.$reg2[$i][11].'</td>
				<td width="94" align="center">'.$reg2[$i][12].'</td>
				<td width="100" align="center">'.$msgpar6.'</td>
			</tr>
			
			<tr bordercolorlight="#CCCCCC">
				<td width="76" align="center">LAB</td>
				<td width="106" align="center">'.$reg2[$i][13].'</td>
				<td width="94" align="center">'.$reg2[$i][14].'</td>
				<td width="100" align="center">'.$msglab.'</td>
			</tr>
			
			<tr bordercolorlight="#CCCCCC">
				<td width="76" align="center">EXA</td>
				<td width="106" align="center">'.$reg2[$i][15].'</td>
				<td width="94" align="center">'.$reg2[$i][16].'</td>
				<td width="100" align="center">'.$msgexa.'</td>
			</tr>
			
			<tr bordercolorlight="#CCCCCC">
				<td width="76" align="center">HAB</td>
				<td width="106" align="center">'.$reg2[$i][17].'</td>
				<td width="94" align="center">'.$reg2[$i][18].'</td>
				<td width="100" align="center">'.$msghab.'</td>
			</tr>';
		$i++;
		}
		?>
		<tr>
			<td colspan='4'>
			<center><font face='Arial' size='2' color='#FF0000'><a OnClick='history.go(-1)' style='cursor:pointer;'>
			<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/back.png" border="0" style="cursor:pointer;" title="Click para regresar">
			<br>Regresar</a></center>
			</td>
		</tr>
		</table>
		<?
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
	
	//Valida que la captura de notas esten dentro de las fechas establecidas.
	function validaCalendario($variable,$configuracion)
	{
		//Valida las fechas del calendario
		
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$this->identificacion;
		}
		$valor[10]=$_REQUEST['periodo'];
		$valor[4]=$usuario;
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$valor[0]=$usuario;
		$valor[1]=$_REQUEST['asig'];
		$valor[2]=$_REQUEST['id_grupo'];
		$valor[3]=$_REQUEST['carrera'];
		$valor[4]=$_REQUEST['nivel'];
		$valor[5]=$ano;
		$valor[6]=$per;
		$valor[10]=$_REQUEST['periodo'];

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosUsuario",$usuario);
		@$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$totreg=count($resultado);
						
		$confec = "SELECT TO_NUMBER(TO_CHAR(CURRENT_TIMESTAMP,'YYYYMMDD'),'99999999')";
		@$rows=$this->ejecutarSQL($configuracion, $this->accesoOracle, $confec, "busqueda");
		$fechahoy =$rows[0][0];
						
		$qryFechas=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "validaFechas",$valor);
		@$calendario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $qryFechas, "busqueda");
		$FormFecIni = $calendario[0][0];
		$FormFecFin = $calendario[0][1];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"anioper",$valor);
		$resultAnioPer=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$ano=$resultAnioPer[0][0];
		$per=$resultAnioPer[0][1];
		
		$valor[0]=$usuario;
		$valor[1]=$_REQUEST['asig'];
		$valor[2]=$_REQUEST['id_grupo'];
		$valor[3]=$_REQUEST['carrera'];
		$valor[4]=$_REQUEST['nivel'];
		$valor[5]=$ano;
		$valor[6]=$per;
		$valor[10]=$_REQUEST['periodo'];

		$QryCierreSem=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "cierreSemestre",$valor);
		$RowCierreSem=$this->ejecutarSQL($configuracion, $this->accesoOracle, $QryCierreSem, "busqueda");
                	if( $calendario[0][0] == "" ||  $calendario[0][1] == "")
			{
		
				die('<br><br><p align="center"><b><font color="#0000FF" size="3">No se han programado fechas para digitaci&oacute;n de notas parciales.</font></p>');
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
										<p align="center"><font color="red"><b>El proceso de digitaci&oacute;n de notas, ser&aacute; del '.$FormFecIni.' <br>hasta el '.$FormFecFin.'.</b></font></p>
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
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
//				$total=count($resultado);
					
				setlocale(LC_MONETARY, 'en_US');
				$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
				$cripto=new encriptar();
				$valor[1]=$_REQUEST['asig'];
				$valor[2]=$_REQUEST['id_grupo'];
				$valor[3]=$_REQUEST['carrera'];
				$valor[4]=$_REQUEST['nivel'];
				$valor[10]=$_REQUEST['periodo'];
				
				echo '<table width="60%" height="40%" border="0" cellpadding="5" cellspacing="0" align="center">
					<tr>
						<td><fieldset style="padding:20;">
							<table width="80%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
								<tr  class="bloquecentralcuerpo">
									<td valign="top">
									<div><h3><center>Aviso</center></h3></div>
									<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
										<p align="justify">&nbsp;</p>
										<p align="center"><font color="red"><b>El proceso de digitaci&oacute;n de notas, termin&oacute; el '.$FormFecFin.'<br>en este momento, solo podr&aacute; ';
										 echo "<a href='";
										$variable="pagina=registro_notasDocente";
										$variable.="&opcion=reportes";
										$variable.="&usuario=".$valor[0];
										$variable.="&asig=".$valor[1];
										$variable.="&id_grupo=".$valor[2];
										$variable.="&carrera=".$valor[3];
										$variable.="&nivel=".$valor[4];
										$variable.="&periodo=".$valor[10];
										//$variable.="&no_pagina=true";
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para imprimir el reporte'><b>IMPRIMIR</b></a>";
										echo ' el reporte de notas.</b></font></p>
										<p align="justify">&nbsp;</p>
									</fieldset>
									NOTA: Para imprimir el reporte de notas, haga Click en '; 
									echo "<a href='";
										$variable="pagina=registro_notasDocente";
										$variable.="&opcion=reportes";
										$variable.="&usuario=".$valor[0];  
										$variable.="&asig=".$valor[1];
										$variable.="&id_grupo=".$valor[2];
										$variable.="&carrera=".$valor[3];
										$variable.="&nivel=".$valor[4];
										$variable.="&periodo=".$valor[10];  
										//$variable.="&no_pagina=true";
										$variable=$cripto->codificar_url($variable,$configuracion);
										echo $indice.$variable."'";
										echo "title='Haga Click aqu&iacute; para imprimir el reporte'><b>IMPRIMIR</b></a>";
									echo '</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
				
				
				//$reporte=$this->ReporteNotas($configuracion);
				exit;
			}
			elseif($RowCierreSem[0][0] == 'S')
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
										<p align="center"><font color="red"><b>En este momento no se pueden digitar mas notas, el semestre fue cerrado. <br>P&oacute;ngase en contacto con el Coordinador del Proyecto Curricular.</b></font></p>
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
                                $variable.="&periodo=".$valor[10];
				break;
			case "registroexitosoPregrado":
				$variable="pagina=registro_notasDocente";	
				$variable.="&opcion=dignotasPregrado";
				$variable.="&asig=".$valor[1];
				$variable.="&id_grupo=".$valor[2];
				$variable.="&carrera=".$valor[3];
				$variable.="&nivel=".$valor[4];
				$variable.="&periodo=".$valor[10];
				break;
			case "registroexitosoPosgrado":
				$variable="pagina=registro_notasDocente";	
				$variable.="&opcion=dignotasPosgrado";
				$variable.="&asig=".$valor[1];
				$variable.="&id_grupo=".$valor[2];
				$variable.="&carrera=".$valor[3];
				$variable.="&nivel=".$valor[4];
				$variable.="&periodo=".$valor[10];
				break;
			case "formgrado":
				$variable="pagina=registro_notasDocente";
				$variable.="&nivel=".$valor[4];
				$variable.="&periodo=".$valor[10];
				break;
				
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}

    function enlaceEncuesta($configuracion) {
//        require_once("../clase/config.class.php");
//        require_once("../clase/encriptar.class.php");
//        $esta_configuracion=new config();
//	$configuracion=$esta_configuracion->variable("../");
//        $cripto=new encriptar();
        $indiceAcademico= $configuracion["host"]."/academicopro/index.php?";
        
        //Encuesta
	$variable="pagina=admin_encuestaDocentes";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=Encuesta";
        $variable.="&tipoUser=30";
        $variable.="&modulo=Docente";
	$variable.="&aplicacion=Condor";
	$variable=$this->cripto->codificar_url($variable,$configuracion);
	$enlaceEncuesta=$indiceAcademico.$variable;
        
        $enlace = "<br><div align='center' >"; 
        $enlace .= "<a href='".$enlaceEncuesta."'>";
        $enlace .= "<img alt='Encuesta-diagn&oacute;stico de necesidades' src='".$configuracion["host"]."/academicopro".$configuracion["grafico"]."/encuesta.png'>";
        $enlace .= "<br><font color='red'>Se&ntilde;or Docente </font> por favor diligencie la Encuesta-diagn&oacute;stico de necesidades <br>e intereses de formaci&oacute;n docente";
        $enlace .= "</a></div>";
        $enlace .= "<br>";
        echo $enlace;
    } 
}
	

?>

