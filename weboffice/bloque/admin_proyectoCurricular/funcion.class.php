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

class funciones_adminProyectoCurricular extends funcionGeneral
{

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
		$this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
	
	
	
		}
	
	
	function nuevoRegistro($configuracion,$acceso_db)
	{}
	
   	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
   	{}
   	
   	function corregirRegistro()
    	{}
	
	function listaRegistro($configuracion,$id_registro)
	
    	{	$variable[0]=$id_registro;
    		$variable[1]=1;
    		
    		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"listar_acred",$variable);
			//echo "<br>".$cadena_sql;
			$registro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
			$total=$this->totalRegistros($configuracion, $this->accesoOracle);
			$this->verAcreditacion($configuracion,$registro, $total, $id_registro);
			
		}
		


	function mostrarRegistro($configuracion,$registro, $totalRegistros, $opcion, $variable)
    	{	
		switch($opcion)
		{
			case "multiplesCarreras":
				$this->multiplesCarreras($configuracion,$registro, $totalRegistros, $variable);
				break;
		
		}
		
	}
	
		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/

	
function consultarCarrera($configuracion,$id_carrera)
	{	//busca si existen registro de datos complementarios en la abse de datos 
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"complemento",$id_carrera);
		//echo "<br>".$cadena_sql;
		$complemento=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		//echo "<br>".$complemento[0][0];
		if($complemento[0][0]==0)
			{	//inserta datos base complementarios para que permita cargar los datos.
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"inserta_complemento",$id_carrera);
				//echo "<br>".$cadena_sql;
				@$complemento=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
			}
		
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carrera",$id_carrera);
		//echo "<br>".$cadena_sql;
		$registro=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
	
		$this->form_editar($configuracion,$registro,$this->tema,"");
		
	}

function multiplesCarreras($configuracion,$registro, $total, $variable)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		?><table width="80%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<td>
								Proyectos Curriculares a Cargo<br>
								<hr class="hr_subtitulo">
								</td>
							</tr>
							<tr>
								<td>
									<table class='contenidotabla'>
										<tr class='cuadro_color'>
											<td class='cuadro_plano centrar ancho10' >C&oacute;digo</td>
											<td class='cuadro_plano centrar'>Nombre </td>
										</tr><?
		for($contador=0;$contador<$total;$contador++)
		{
			
			$parametroSolicitud[0]=$registro[$contador][0];
			$parametroSolicitud[1]=$this->datosBasico["anno"];
			$parametroSolicitud[2]=$this->datosBasico["periodo"];
			
			//Con enlace a la busqueda
			$parametro="pagina=proyectoCurricular";
			$parametro.="&hoja=1";
			$parametro.="&opcion=consultar";
			$parametro.="&accion=consultar";
			$parametro.="&carrera=".$registro[$contador][0];
			//$parametro.="&xajax=nbcPrimario|nbcSecundario|justificaEst|propeCra|acreCra|procesar_formulario|cancelaAcre";
			//$parametro.="&xajax_file=proyecto";
			$parametro=$cripto->codificar_url($parametro,$configuracion);
			echo "<tr> 
					 <td class='cuadro_plano centrar'>".$registro[$contador][0]."</td>
					 <td class='cuadro_plano'><a href='".$indice.$parametro."'>".$registro[$contador][1]."</a></td>
				  </tr>";
		
			
		}?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class='cuadro_plano cuadro_brown'>
						<p class="textoNivel0">Por favor realice click sobre el nombre del proyecto curricular que desee consultar.</p>
					</td>
				</tr>
			</tbody>
		</table>
		<?
	}
		
function verAcreditacion($configuracion,$registro, $total, $id_carrera)
	{
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"carrera",$id_carrera);
		//echo "<br>".$cadena_sql;
		$carrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
				
		
		?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<td>
								Acreditaciones Proyecto Curricular <? echo $carrera[0][1]?><br>
								<hr class="hr_subtitulo">
								</td>
							</tr>
							<tr>
								<td>
									<table class='contenidotabla'>
										<tr class='cuadro_color'>
											<td class='cuadro_plano centrar ancho20' >Tipo</td>
											<td class='cuadro_plano centrar'>Resoluci&oacute;n </td>
											<td class='cuadro_plano centrar'>Fecha </td>
											<td class='cuadro_plano centrar'>Años Duraci&oacute;n  </td>
										</tr><?
		for($contador=0;$contador<$total;$contador++)
		{
			echo "<tr> 
					 <td class='cuadro_plano centrar'>".$registro[$contador][0]."</td>
					 <td class='cuadro_plano'>".$registro[$contador][1]."</td>
					 <td class='cuadro_plano'>".$registro[$contador][2]."</td>
					 <td class='cuadro_plano'>".$registro[$contador][3]."</td>
				  </tr>";
		
			
		}?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<?
	}


	
function form_editar($configuracion,$registro,$tema,$estilo)
		{$indice=$configuracion["host"].$configuracion["site"]."/index.php?";

		/*****************************************************************************************************/
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		$html=new html();
		//array con los nombres de la divs, para a ser desplegadas	
		$divs="datosbasicos,infonbc,infoduracion,infoadicional,infoacreditacion";

		$tab=1;
		$this->formulario="admin_proyectoCurricular";
		$this->verificar='control_vacio('.$this->formulario.', "fecha_registro")';
		$this->verificar.='&&control_vacio('.$this->formulario.', "resol_creacion")';
		//$this->verificar.="&&fecha_divs(".$this->formulario.",'fecha_registro','".date("d/m/Y",time())."','datosbasicos','".$divs."')";
	//	$this->verificar.="&& seleccion_valida_divs(".$this->formulario.",'area_cod1','infonbc','".$divs."')";
	//	$this->verificar.="&& seleccion_valida_divs(".$this->formulario.",'nbc_cod1','infonbc','".$divs."')";
	//	$this->verificar.="&& seleccion_valida_divs(".$this->formulario.",'area_esp1','infonbc','".$divs."')";
		$this->verificar.='&& control_vacio('.$this->formulario.', "creditos")';
		$this->verificar.='&& control_vacio('.$this->formulario.', "titulo")';
		$this->verificar.='&& control_vacio('.$this->formulario.', "urlcra")';
		$this->verificar.='&& control_vacio('.$this->formulario.', "urlasp")';
		$this->verificar.='&& control_vacio('.$this->formulario.', "urlpro")';
		//$this->verificar.="&& seleccion_valida_divs(".$this->formulario.",'tipoacre','infoacreditacion','".$divs."')";
		$this->verificar.='&& control_vacio('.$this->formulario.', "acreresol")';
		$this->verificar.='&& control_vacio('.$this->formulario.', "acrefecha")';
		//$this->verificar.="&&fecha_divs(".$this->formulario.",'acrefecha','".date("d/m/Y",time())."','infoacreditacion','".$divs."')";
		$this->verificar.='&& control_vacio('.$this->formulario.', "acrevida")';
		//$this->verificar.="&& longitud_cadena(".$this->formulario.",'fecha',3)";seleccion_valida(formulario,control)
		//$this->verificar.="&& verificar_correo(".$this->formulario.",'descripcion')";	seleccion_valida(formulario,control)
		
               
                $html_0="<style> body{ font-size: 11px; border: none;  background-color:white;}  table tr { font-size: 11px; border: none; width:95%;} </style>";
		$html_0.="<form method='POST' action='index.php' name='".$this->formulario."' id='".$this->formulario."'>";
		$html_0.="<hr>";
                
		$html_1.="<div width='80%' id='datosbasicos'>";
		$html_1.="<table width='80%'  class='formulario'  align='center'>";
		$html_1.="<tr class='bloquecentralcuerpobeige'><td  colspan='3'><hr class='hr_subtitulo'/>DATOS BASICOS DEL PROYECTO CURRICULAR<hr class='hr_subtitulo'/></td></tr>";
		$html_1.="<tr>";
		$html_1.="	  <td width='30%'>";
                $html_1.="<script src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."'/tooltip.js' type='text/javascript' language='javascript'></script>";

                $texto_ayuda="<b>Codigo Interno del proyecto.</b>";
		
                $html_1.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."', SHADOW, true, TITLE, 'C&oacute;digo Interno', PADDING, 9)\" >C&oacute;digo Interno:</span>";
		$html_1.="	  </td>";
		$html_1.="	  <td>";
		$html_1.="		 <input type='hidden' id='carrera' name='carrera' value=".$registro[0][0].">";
                $html_1.=$registro[0][0];
		$html_1.="	 </td>";
		$html_1.="</tr>";
		$html_1.="<tr>";
	  	$html_1.="   <td width='30%'>";

                $texto_ayuda="Nombre Completo registrado ante el ICFES.";
		
                $html_1.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."', SHADOW, true, TITLE, 'Nombre', PADDING, 6)\">Nombre:</span>";
		$html_1.="	</td>";
		$html_1.="	<td>".$registro[0][1];
		$html_1.="</td>";
		$html_1.="</tr>";
		$html_1.="<tr>";
		$html_1.="<td width='30%'>";

		$texto_ayuda="<b>Nombre de no m&aacute;s de 20 caracteres.</b> ";
		$texto_ayuda.="Con el que identifica el proyecto internamente.<br>";
		$texto_ayuda.="En la generaci&oacute;n de informes se utiliza el nombre completo registrado ante el ICFES.";
		
                $html_1.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."', SHADOW, true, TITLE, 'Nombre Corto', PADDING, 9)\">Nombre Corto:</span>";
		$html_1.="	</td>";
		$html_1.="	<td>".$registro[0][2];
		$html_1.="	</td>";
		$html_1.="</tr>";
		$html_1.="<tr>";
		$html_1.="    <td width='30%'>";

                $texto_ayuda="<b>N&uacute;mero de registro ICFES del programa.</b> ";
		
                $html_1.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."', SHADOW, true, TITLE, 'Registro ICFES', PADDING, 9)\">Registro ICFES:</span>";
		$html_1.="  </td>";
		$html_1.="	<td>".$registro[0][3];
		$html_1.="	</td>";
		$html_1.="</tr>";
		$html_1.="<tr>";
		$html_1.="	<td width='30%'>";

		$texto_ayuda="<b>Fecha de registro del programa por parte del ICFES.</b><br> ";
		$texto_ayuda.="Fecha en formato: dd/mm/aaaa.<br>";
		
                $html_1.="<font color='red' >*</font>";
                $html_1.="    <span onmouseover=\"javascript:Tip('".$texto_ayuda."', SHADOW, true, TITLE, 'Fecha Registro ICFES', PADDING, 9)\">Fecha Registro ICFES:</span>";
		$html_1.="	</td>";
		$html_1.="	<td><script src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/funciones.js' type='text/javascript' language='javascript'></script>";
		$html_1.="	<input type='text' id='fecha_registro' name='fecha_registro' value='".$registro[0][4]."' size='12' maxlength='25' readonly='readonly'>";
		$html_1.="  <a href=\"javascript:muestraCalendario('".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."','".$this->formulario."','fecha_registro')\">";
		$html_1.="	<img border='0' src='".$configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/cal.png' width='24' height='24' alt='DD-MM-YYYY'></a>";
		$html_1.="	</td>";
		$html_1.="</tr>";
		$html_1.="<tr>";
		$html_1.="	<td width='30%'>";

                $texto_ayuda="<b>Acto administrativo de creaci&oacute;n del proyecto.</b><br> ";
		$texto_ayuda.="Nombre y n&uacute;mero del documento mediante el cual se cre&oacute; el proyecto.</b><br> ";
		
                $html_1.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Norma Interna de Creaci&oacute;n:', PADDING, 9)\">Norma Interna de Creaci&oacute;n:</span>";
		$html_1.="	</td>";
		$html_1.="	<td>";
		$html_1.="		<input type='text' name='resol_creacion' id='resol_creacion' value='".$registro[0][5]."' size='60' maxlength='50' tabindex='".$tab++."'>";
		$html_1.="	</td>";
		$html_1.="</tr>";
		$html_1.="<tr>";
		$html_1.="    <td width='30%'>";

                
		$texto_ayuda.="Sede de la universidad en la que se dicta el proyecto<br>";
		
                $html_1.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."')'>Facultad:</span>";
		$html_1.="	</td>";
		$html_1.="	<td>".$registro[0][6];
		$html_1.="	</td>";
		$html_1.="</tr>";
		$html_1.="<tr>";
		$html_1.="    <td width='30%'>";

		
		$texto_ayuda.="Corresponde al aprobado por el ICFES y puede ser entre otros:<b> Tecnolog&iacute;a, Pregrado, Especializaci&oacute;n, Maestr&iacute;a, Doctorado.</b>";
		
                $html_1.="<span onmouseover='return escape(".$texto_ayuda.",SHADOW, true, TITLE, 'Facultad:', PADDING, 9)\">Nivel Acad&eacute;mico:</span>";
		$html_1.="	</td>";
		$html_1.="	<td>".$registro[0][7];
		$html_1.="	</td>";
		$html_1.="</tr>	";
		$html_1.="<tr>";
		$html_1.="   <td width='30%'>";

		$texto_ayuda="<b>Nivel de Formación del proyecto</b><br>";
		$texto_ayuda.="Tecnol&oacute;logico, Ingenieria, Especialización, etc.<br>";
		
                $html_1.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Formaci&oacute;n:', PADDING, 9)\">Formación:</span>";
                $html_1.="</td>";
		$html_1.="   <td>".$registro[0][9];
		$html_1.="   </td>";
		$html_1.="</tr>	";
		$html_1.="<tr>";
		$html_1.="   <td width='30%'>";

		
		$texto_ayuda="Estado Actual del proyecto (Activo, Inactivo) .<br>";
		
                $html_1.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Estado:', PADDING, 9)\">Estado:</span>";
		$html_1.="   </td>";
		$html_1.="   <td>";

                $estado[0][0]="A";
		$estado[0][1]="ACTIVO";
		$estado[1][0]="I";
		$estado[1][1]="INACTIVO";
                $configuracion["ajax_function"]="xajax_justificaEst";
		$configuracion["ajax_control"]="estado";
					
		$est_cuadro1=$html->cuadro_lista($estado,'estado',$configuracion,$registro[0][10],3,FALSE,$tab++,'estado');
		
                $html_1.="".$est_cuadro1."";
		$html_1.="<input type='hidden' name='estado' value='".$registro[0][10]."'>";
		$html_1.="</td>";
		$html_1.="</tr>";
		$html_1.="<tr>";
		$html_1.="      <td width='30%'>";

                
		$texto_ayuda="Justificación del estado Inactivo del proyecto.<br>";
		
                $html_1.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Justificaci&oacute;n:', PADDING, 9)\">Justificaci&oacute;n:</span>";
		$html_1.="	</td>";
		$html_1.="	<td><div id='divjust'>";

                $busqueda="SELECT just_cod, just_desc FROM mntac.justifica ORDER BY just_desc";
		$justifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
                    if($registro[0][10]=="I" && $registro[0][11]!=0)
                        {$just_est=$html->cuadro_lista($justifica,'justifica',$configuracion,$registro[0][11],0,FALSE,$tab++,'justifica');}
                    elseif($registro[0][10]=="I" && $registro[0][11]==0)
                        {$just_est=$html->cuadro_lista($justifica,'justifica',$configuracion,0,0,FALSE,$tab++,'justifica');}
                    else
                        {$just_est=$html->cuadro_lista($justifica,'justifica',$configuracion,-1,3,FALSE,$tab++,'justifica');}
                
                $html_1.="".$just_est."";
		$html_1.="</div><!-- cierra divjust-->";
		$html_1.="	</td>";
		$html_1.="  </tr>";
		$html_1.="</table>";
		$html_1.="</div>";

 //-----------finaliza div de datos basicos------------//

                
		
		$html_2="<div class='tabs'>";
		$html_2.="<table class='formulario' align='center'>";
                $html_2.="<tr class='bloquecentralcuerpobeige'>";
                $html_2.="<td  colspan='3'>";
                $html_2.="<hr class='hr_subtitulo'/>NUCLEO BASICO DE CONOCIMIENTO ".$registro[0][1]." <hr class='hr_subtitulo'/></td></tr>";

                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"nbc",$registro[0][0]);
                $nbc=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		$html_2.="<tr>";
		$html_2.="   <td width='30%' onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Verificar Requisito', PADDING, 9)\">";

                $texto_ayuda="&Aacute;rea de principal de conocimiento en la que se ubica el proyecto <br>";
		$texto_ayuda.=" (INGENIERIA, ARQUITECTURA, URBANISMO Y AFINES; MATEMATICAS Y CIENCIAS NATURALES; etc) .";
		
                $html_2.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, '&Aacute;rea Conocimiento Primaria', PADDING, 9)\">&Aacute;rea Conocimiento Primaria:</span>";
		$html_2.="  </td>";
		$html_2.="  <td>";

                $busqueda="SELECT area_cod, area_nombre FROM mntac.areacon ORDER BY area_nombre";
		$area1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");

                $configuracion["ajax_function"]="xajax_nbcPrimario";
                $configuracion["ajax_control"]="area_con1";
		if($nbc[0][1]!=0)
                    {$con_cuadro1=$html->cuadro_lista($area1,'area_cod1',$configuracion,$nbc[0][1],2,FALSE,$tab++,'area_con1',400);}
		else
                    {$con_cuadro1=$html->cuadro_lista($area1,'area_cod1',$configuracion,-1,2,FALSE,$tab++,'area_con1',400);}
		$html_2.=$con_cuadro1;
                
                $html_2.="</td>";
		$html_2.="	</tr>";
		$html_2.="	<tr>";
		$html_2.="		<td width='30%'>";

		$texto_ayuda="N&uacute;cleo principal de conocimiento en la que se ubica el proyecto<br> ";
		$texto_ayuda.=" (ADMINISTRACI&Oacute;N, EDUCACI&Oacute;N, ARQUITECTURA, etc) .<br>";
		
                $html_2.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'N&uacute;cleo Primario', PADDING, 9)\">N&uacute;cleo Primario:</span>";
		$html_2.="		</td>";
		$html_2.="		<td>";
		$html_2.="		<div id='divnbc1'>";

                $busqueda="SELECT nbc_cod, nbc_cod_nombre FROM mntac.nbc WHERE nbc_cod_area=".$nbc[0][1]." ORDER BY nbc_cod_nombre";
		if($nbc[0][0]!=0)
		{   $nucleo1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
                    $nbc_cuadro=$html->cuadro_lista($nucleo1,'nbc_cod1',$configuracion,$nbc[0][0],0,FALSE,$tab++,'nbc1',400);

                }
		else
		{   $nucleo1="";
                    $nbc_cuadro=$html->cuadro_lista($nucleo1,'nbc_cod1',$configuracion,-1,0,FALSE,$tab++,'nbc1',400);

                }
                $html_2.=$nbc_cuadro;
		$html_2.="</div>";
		$html_2.="		</td>";
		$html_2.="	</tr>";
		$html_2.="	<tr>";
		$html_2.="		<td width='30%'>";

               
		$texto_ayuda="&Aacute;rea espec&iacute;fica de trabajo, principal en la que se enmarca el proyecto<br>";
		$texto_ayuda.=" (Educaci&oacute;n superior profesional, Educaci&oacute;n superior tecnol&oacute;gica, etc) .<br>";
		
                $html_2.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, '&Aacute;rea Especifica Primaria', PADDING, 9)\">&Aacute;rea Especifica Primaria:</span>";
		$html_2.="		</td>";
		$html_2.="		<td>";

                $busqueda="SELECT aesp_cod, aesp_nombre FROM mntac.areaesp ORDER BY aesp_nombre";
		$espec1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
		if($nbc[0][2]!=0)
                    {$esp_cuadro1=$html->cuadro_lista($espec1,'area_esp1',$configuracion,$nbc[0][2],0,FALSE,$tab++,'area_esp1',400);}
		else
                    {$esp_cuadro1=$html->cuadro_lista($espec1,'area_esp1',$configuracion,-1,0,FALSE,$tab++,'area_esp1',400);}
		
                $html_2.=$esp_cuadro1;
			
		$html_2.="		</td>";
		$html_2.="	</tr>";
		$html_2.="	<tr>";
		$html_2.="		<td width='30%'>";

                $texto_ayuda="&Aacute;rea de Secundaria de conocimiento en la que se ubica el proyecto<br>";
		$texto_ayuda.=" (INGENIERIA, ARQUITECTURA, URBANISMO Y AFINES; MATEMATICAS Y CIENCIAS NATURALES; etc) .<br>";
		
                $html_2.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'N&uacute;cleo Primario', PADDING, 9)\">&Aacute;rea Conocimineto Secundaria:</span>";
		$html_2.="		</td>";
		$html_2.="		<td>";

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		$html=new html();
		$busqueda="SELECT area_cod, area_nombre FROM mntac.areacon ORDER BY area_nombre";
		$area2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
		$configuracion["ajax_function"]="xajax_nbcSecundario";
		$configuracion["ajax_control"]="area_con2";

                if($nbc[1][1]!=0)
                    {$con_cuadro2=$html->cuadro_lista($area2,'area_cod2',$configuracion,$nbc[1][1],2,FALSE,$tab++,'area_con2',400);}
                else
                    {$con_cuadro2=$html->cuadro_lista($area2,'area_cod2',$configuracion,0,2,FALSE,$tab++,'area_con2',400);}

                $html_2.=$con_cuadro2;
		$html_2.="		</td>";
		$html_2.="	</tr>";
		$html_2.="	<tr>";
		$html_2.="	<td width='30%'>";

		$texto_ayuda="N&uacute;cleo principal de conocimiento en la que se ubica el proyecto<br> ";
		$texto_ayuda.=" (ADMINISTRACI&Oacute;N, EDUCACI&Oacute;N, ARQUITECTURA, etc) .<br>";
		
                $html_2.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'N&uacute;cleo Secundario', PADDING, 9)\">N&uacute;cleo Secundario:</span>";
		$html_2.="		</td>";
		$html_2.="		<td>";
		$html_2.="		<div id='divnbc2'>";

                $html=new html();
										
		$busqueda="SELECT nbc_cod, nbc_cod_nombre FROM mntac.nbc WHERE nbc_cod_area=".$nbc[0][1]." ORDER BY nbc_cod_nombre";

                if($nbc[0][1]!=0)
		{   $nucleo2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
                    $nbc_cuadro2=$html->cuadro_lista($nucleo2,'nbc_cod2',$configuracion,$nbc[1][0],0,FALSE,$tab++,'nbc2',400);}
		else
		{   $nucleo2="";
                    $nbc_cuadro2=$html->cuadro_lista($nucleo2,'nbc_cod2',$configuracion,-1,0,FALSE,$tab++,'nbc2',400);}

    		$html_2.=$nbc_cuadro2;
		
                $html_2.="</div>";
		$html_2.="		</td>";
		$html_2.="	</tr>";
		$html_2.="	<tr>";
		$html_2.="          <td width='30%'>";

		
		$texto_ayuda="&Aacute;rea espec&iacute;fica de trabajo en la que se enmarca el proyecto<br>";
		$texto_ayuda.=" (Educaci&oacute;n superior profesional, Educaci&oacute;n superior tecnol&oacute;gica, etc) .<br>";
		
                $html_2.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, '&Aacute;rea Espec&iacute;fica Secundaria', PADDING, 9)\">&Aacute;rea Espec&iacute;fica Secundaria:</span>";
		$html_2.="		</td>";
		$html_2.="		<td>";

		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		$html=new html();
		$busqueda="SELECT aesp_cod, aesp_nombre FROM mntac.areaesp ORDER BY aesp_nombre";
		//echo $busqueda;
		$espec2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
		if($nbc[1][2]!=0)
                    {$esp_cuadro2=$html->cuadro_lista($espec2,'area_esp2',$configuracion,$nbc[1][2],0,FALSE,$tab++,'area_esp2',400);}
                else
		{$esp_cuadro2=$html->cuadro_lista($espec2,'area_esp2',$configuracion,-1,0,FALSE,$tab++,'area_esp2',400);}
		
                $html_2.=$esp_cuadro2;
		$html_2.="		</td>";
		$html_2.="	</tr>";
		$html_2.="</table>";
		$html_2.="</div>";

                
		//------------Cierra el div para el area de conocimientos------------------------/ -->

           
		$html_3="<div class='tabs'>";
		$html_3.="<table  class='formulario'  align='center'>";
		$html_3.="	<tr class='bloquecentralcuerpobeige'><td  colspan='3'><hr class='hr_subtitulo'/>DURACI&Oacute;N PROYECTO CURRICULAR  ".$registro[0][1]." <hr class='hr_subtitulo'/></td></tr>";
		$html_3.="	<tr>";
		$html_3.="		<td width='30%'>";

                $texto_ayuda="<b>Nombre de los periodos en que se divide el programa.</b><br>";
		$texto_ayuda.="Usualmente se expresa en t&eacute;rminos de tiempo. Ejemplo: ";
		$texto_ayuda.="<b>Bimestre, Trimestre, Semestre, A&ntilde;o,etc.</b> ";
		
                $html_3.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Periodicidad', PADDING, 9)\">Periodicidad:</span>";
		$html_3.="		</td>";
		$html_3.="		<td>";
		$html_3.="			<input type='hidden' name='periodicidad' value='".$registro[0][12]."' size='20' maxlength='15' tabindex='".$tab++."' onKeyPress='return solo_texto(event)'>";
		$html_3.=		$registro[0][12];
		$html_3.="	</td>";
		$html_3.="	</tr>";
		$html_3.="	<tr>";
		$html_3.="		<td width='30%'>";

                $texto_ayuda="N&uacute;mero de periodos que componen el plan de estudios.";
		
                $html_3.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Duraci&oacute;n', PADDING, 9)\">Duraci&oacute;n:<br></span>";
		$html_3.="		</td>";
		$html_3.="		<td>";
		$html_3.="			<input type='hidden' name='duracion' value='".$registro[0][13]."' size='20' maxlength='2' tabindex='".$tab++."' onKeyPress='return solo_numero(event)'>";
		$html_3.=			$registro[0][13];
		$html_3.="		</td>";
		$html_3.="	</tr>";
		$html_3.="	<tr>";
		$html_3.="		<td width='30%'>";

                $texto_ayuda="<b>Jornada en que se desarrolla el plan de estudios.</b><br>";
		$texto_ayuda.="Corresponde a la jornada aprobada por el ICFES y <br>puede ser entre otras:<b>Diurna, nocturna</b>";
		
                $html_3.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Jornada', PADDING, 9)\">Jornada:</span>";
		$html_3.="		</td>";
		$html_3.="		<td>";

                $jorn[0][0]="DIURNA";
		$jorn[0][1]="DIURNA";						
		$jorn[1][0]="NOCTURNA";
		$jorn[1][1]="NOCTURNA";

                if($registro[0][14]=="NOCTURNA")
                    {$jornada=$html->cuadro_lista($jorn,'jornada',$configuracion,"NOCTURNA",0,FALSE,$tab++,'jornada');}
		else
                    {$jornada=$html->cuadro_lista($jorn,'jornada',$configuracion,"DIURNA",0,FALSE,$tab++,'jornada');}
		
                $html_3.=$jornada;
		$html_3.="		</td>";
		$html_3.="	</tr>";
		$html_3.="	<tr>";
		$html_3.="		<td width='30%'>";

                $texto_ayuda="<b>Modalidad en la que se dicta el plan de estudios.</b><br>";
		$texto_ayuda.="Corresponde a la modalidad aprobada por el ICFES y puede ser entre otras:<br><b>Presencial, semi presencial, a distancia.</b>";
		
                $html_3.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Modalidad', PADDING, 9)\">Modalidad:</span>";
		$html_3.="		</td>";
		$html_3.="		<td>";

                $busqueda="SELECT met_cod, met_nombre FROM mntac.metodologia ORDER BY met_nombre";
		//echo $busqueda;
		$metodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
		if($registro[0][15]!=0)
                    {$mod_cuadro=$html->cuadro_lista($metodo,'metodo',$configuracion, $registro[0][15] ,0,FALSE,$tab++,'metodo');}
		else
                    {$mod_cuadro=$html->cuadro_lista($metodo,'metodo',$configuracion,1,0,FALSE,$tab++,'metodo');}
		
                $html_3.=$mod_cuadro;
		$html_3.="		</td>";
		$html_3.="	</tr>";
		$html_3.="	<tr>";
		$html_3.="		<td width='30%'>";

                $texto_ayuda="<b>N&uacute;mero de Creditos.</b><br>";
		$texto_ayuda.="Cantidad de creditos asignados al proyectos curricular.</b>";
		
                $html_3.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Creditos', PADDING, 9)\">Creditos:</span>";
		$html_3.="		</td>";
		$html_3.="		<td>";
		$html_3.="			<input type='text' name='creditos' value='".$registro[0][16]."' size='20' maxlength='4' tabindex='".$tab++."' onKeyPress='return solo_numero(event)' >";
		$html_3.="		</td>";
		$html_3.="	</tr>";
		$html_3.="</table>";
		$html_3.="</div>";

    //--------------------------------------------------- Cierra el div de duracion del proyecto//-->


		$html_4="<div class='tabs' >";
		$html_4.="<table class='formulario' align='center'>";
		$html_4.="	<tr class='bloquecentralcuerpobeige'><td colspan='3'><hr class='hr_subtitulo'/>DATOS COMPLEMENTARIOS  ".$registro[0][1]." <hr class='hr_subtitulo'/></td></tr>";
                $html_4.="<tr>";
		$html_4.="		<td width='30%'>";

                $texto_ayuda="<b>T&iacute;tulo que otorga el programa.</b><br>";
		$texto_ayuda.="T&iacute;tulo general para el reporte.</b><br>";
		
                $html_4.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Titulo', PADDING, 9)\">T&iacute;tulo:</span>";
		$html_4.="		</td>";
		$html_4.="		<td>";
		$html_4.="			<input type='text' name='titulo' value='".$registro[0][17]."' size='60' maxlength='200' tabindex='".$tab++."' >";
		$html_4.="		</td>";
		$html_4.="	</tr>";
		$html_4.="	<tr>";
		$html_4.="		<td width='30%'>";

                
		$texto_ayuda="Direcci&oacute;n electr&oacute;nica de la pagina Web del Proyecto.";
		
                $html_4.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'URL Proyecto', PADDING, 9)\">URL Proyecto:</span>";
		$html_4.="		</td>";
		$html_4.="		<td>";
		$html_4.="			<input type='text' name='urlcra' value='".$registro[0][18]."' size='60' maxlength='200' tabindex='".$tab++."'>";
		$html_4.="		</td>";
		$html_4.="	</tr>";
		$html_4.="	<tr>";
		$html_4.="		<td width='30%'>";

		
		$texto_ayuda="Direcci&oacute;n electr&oacute;nica de la pagina donde se encunetra el Perfil de los aspirantes.";
		
                $html_4.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'URL Aspirante', PADDING, 9)\">URL Perfil Aspirante:</span>";
		$html_4.="		</td>";
		$html_4.="		<td>";
		$html_4.="			<input type='text' name='urlasp' value='".$registro[0][19]."' size='60' maxlength='200' tabindex='".$tab++."'>";
		$html_4.="		</td>";
		$html_4.="	</tr>";
		$html_4.="	<tr>";
		$html_4.="		<td width='30%'>";

                
		$texto_ayuda="Direcci&oacute;n electr&oacute;nica de la pagina donde se encunetra <br>el Perfil Profesional del estudiante.";
		
                $html_4.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'URL Perfil Profesional', PADDING, 9)\">URL Perfil Profesional:</span>";
		$html_4.="		</td>";
		$html_4.="		<td>";
		$html_4.="			<input type='text' name='urlpro' value='".$registro[0][20]."' size='60' maxlength='200' tabindex='".$tab++."'>";
		$html_4.="		</td>";
		$html_4.="	</tr>";
		$html_4.="	<tr>";
		$html_4.="		<td width='30%'>";

                
		$texto_ayuda="Indica si el programa pertenece a ciclo propedéutico .<br>";
		
                $html_4.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Ciclo Proped&eacute;utico', PADDING, 9)\">Ciclo Propedéutico:</span>";
		$html_4.="		</td>";
		$html_4.="		<td>";

                $estado[0][0]="N";
		$estado[0][1]="NO";
		$estado[1][0]="S";
		$estado[1][1]="SI";
		$configuracion["functionjs"]="facultad_propedeutico()";
		$est_cuadro1=$html->cuadro_lista($estado,'propedeutico',$configuracion,$registro[0][21],4,FALSE,$tab++,'propedeutico');

                $html_4.=$est_cuadro1;
		
		$html_4.="		</td>";
		$html_4.="	</tr>";
		$html_4.="	<tr>";
		$html_4.="		<td width='30%'>";

                $texto_ayuda="<b>Proyecto Curricular Superior.</b><br>";
		$texto_ayuda.="En caso de proyectos en ciclo propedeutico, selecciona el proyectos curricular que corresponde a la continuaci&oacute;n del ciclo propedeutico<br>";
		
                $html_4.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Nivel Superior', PADDING, 9)\">Proyecto Nivel Superior:</span>";
		$html_4.="		</td>";
		$html_4.="		<td>";
		$html_4.="		<div id='divporpCra'>";
					

                $busqueda="SELECT cra_cod, cra_nombre FROM mntac.accra WHERE cra_dep_cod=(SELECT cra_dep_cod FROM mntac.accra WHERE cra_cod=".$registro[0][0].") ORDER BY cra_nombre";
		//echo $busqueda;
		$cra_prope=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
		if($registro[0][22]!=0 && $registro[0][21]!=0)
		{
                    $cra_prop=$html->cuadro_lista($cra_prope,'craprop',$configuracion,-1,3,FALSE,$tab++,'craprop');}
		elseif($registro[0][22]!=0 && $registro[0][21]==0)
		{
                    $cra_prop=$html->cuadro_lista($cra_prope,'craprop',$configuracion,$registro[0][22],0,FALSE,$tab++,'craprop');}
		else
		{
                    $cra_prop=$html->cuadro_lista($cra_prope,'craprop',$configuracion,-1,3,FALSE,$tab++,'craprop');}
		$html_4.=$cra_prop;

		$html_4.="</div>";
		$html_4.="		</td>";
		$html_4.="	</tr>";
		$html_4.="</table>";
		$html_4.="</div>";
 //--------------Cierra el div de informacion complementaria--------------------- //--> 				
		
		$html_5="<div class='tabs'>";
		$html_5="<div class='tabs' id='acrednal'>";
		$html_5.="	<table class='formulario' align='center'>";

                $acrenal[0]=$registro[0][0];
		$acrenal[1]=1;
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"acreditacion",$acrenal);
		$acreditacion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		
                $html_5.="<tr class='bloquecentralcuerpobeige'>";
		$html_5.="			<td colspan='3'>";
		$html_5.="			<hr class='hr_subtitulo'/>";
		$html_5.="			<table class='formulario' align='center'>";
                $html_5.="<tr>";
                $html_5.="          <td width='60%'>";
		$html_5.="              ULTIMA ACREDITACI&Oacute;N NACIONAL ".$registro[0][1]."";
		$html_5.="          </td>";
		$html_5.="          <td align='right'>";
							

                $ruta="pagina=proyectoCurricular&hoja=1&opcion=acreditacion";
		$ruta.="&carrera=".$registro[0][0];
		$ruta=$this->cripto->codificar_url($ruta,$configuracion);
		$ruta=$indice.$ruta;		
		
                $html_5.="<a  href=\"javascript:abrir_emergenteUbicada('".$ruta."','Acreditaciones','600','300','100','350')\">";
		$html_5.="      <b>Ver Acreditaciones</b>";
		$html_5.="      <img border='0' hspace='2' src='".$configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/viewdoc.png' width='24' height='24'>";
		$html_5.="</a>";
                $html_5.="</td>";
		$html_5.="<td align='right'>";
		$html_5.="<a  href=\"javascript:nueva_acre('divBoton','1','".$registro[0][0]."','acredext')\">";
		$html_5.="<b>Ingresar nueva Acreditaci&oacute;n</b>";
		$html_5.="  <img border='0' hspace='2' src='".$configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/new.png' width='24' height='24'>";
		$html_5.="</a>";
		$html_5.="</td>";
		$html_5.="</tr>";
		$html_5.="  </table>";
		$html_5.="<hr class='hr_subtitulo'/>";
		$html_5.="			</td>";
		$html_5.="		</tr>";
		$html_5.="		<tr>";
		$html_5.="			<td width='30%'>";
                
                
                $texto_ayuda="Ultimo tipo de acreditaci&oacute;n obtenido por el proyecto.";
					
                $html_5.="<font color='red' >*</font><span  onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Tipo de Acreditaci&oacute;n', PADDING, 9)\">Tipo Acreditaci&oacute;n:</span>";
		$html_5.="			</td>";
		$html_5.="			<td>";
						
		$busqueda="SELECT acre_cod_tipo, acre_nombre FROM mntac.acretipo WHERE acre_nivel<=2 AND estado='A' ";
		//echo $busqueda;
		$tipoacre=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
		if($acreditacion[0][1]!=0)
                    {$tipo_acre=$html->cuadro_lista($tipoacre,'tipoacre',$configuracion,$acreditacion[0][1],0,FALSE,$tab++,'tipoacre');}
		else
                	{$tipo_acre=$html->cuadro_lista($tipoacre,'tipoacre',$configuracion,-1,0,FALSE,$tab++,'tipoacre');}							
		
		$html_5.=$tipo_acre;
		$html_5.="			</td>";
		$html_5.="		</tr>";
		$html_5.="		<tr>";
		$html_5.="			<td width='30%'>";

                
		$texto_ayuda="Acto Administrativo con el que se concede la <br>acreditaci&oacute;n al proyecto curricular. ";
		
                $html_5.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Resoluci&oacute;n', PADDING, 9)\">Resoluci&oacute;n:</span>";
		$html_5.="		</td>";
		$html_5.="			<td>";
		$html_5.="				<input type='text' name='acreresol' value='".$acreditacion[0][2]."' size='40' maxlength='50' tabindex='".$tab++."'>";
		$html_5.="			</td>";
		$html_5.="		</tr>";
		$html_5.="		<tr>";
		$html_5.="			<td width='30%'>";

                
		$texto_ayuda="Fecha en la que se concedio la acreditaci&oacute;n al proyecto curricular.<br> ";
		$texto_ayuda.="Fecha en formato: dd/mm/aaaa.<br>";
		
                $html_5.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Fecha Acreditaci&oacute;n', PADDING, 9)\">Fecha Acreditaci&oacute;n:</span>";
		$html_5.="			</td>";
		$html_5.="			<td>";
		$html_5.="				<input type='text' name='acrefecha' value='".$acreditacion[0][3]."' size='12' maxlength='25' tabindex='".$tab++."' readonly='readonly'>";
		$html_5.="				<a href=\"javascript:muestraCalendario('".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."','".$this->formulario."','acrefecha')\">";
		$html_5.="				<img border='0' src='".$configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/cal.png' width='24' height='24' alt='DD-MM-YYYY'></a>";
		$html_5.="			</td>";
		$html_5.="		</tr>";
		$html_5.="		<tr>";
		$html_5.="			<td width='30%'>";

                
		$texto_ayuda="N&uacute;mero en años del tiempo que dura la acreditaci&oacute;n del proyecto curricular. ";
		
                $html_5.="<font color='red' >*</font><span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Duraci&oacute;n', PADDING, 9)\">Duraci&oacute;n (Años):</span>";
		$html_5.="			</td>";
		$html_5.="			<td>";
		$html_5.="				<input type='text' name='acrevida' value='".$acreditacion[0][4]."' size='12' maxlength='2' tabindex='".$tab++."' onKeyPress='return solo_numero(event)' >";
		$html_5.="				<input type='hidden' name='entidad' value=''>";
		$html_5.="				<input type='hidden' name='acrecra' value='<? echo $acreditacion[0][0] ?>'>";
		$html_5.="			</td>";
		$html_5.="		</tr>";
		$html_5.="	</table>";
		$html_5.="	</div>";
		$html_5.="	<div class='tabs' id='acredext'>";
		$html_5.="	<table class='formulario' align='center'>";

                $acrext[0]=$registro[0][0];
		$acrext[1]=3;
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"acreditacion",$acrext);
		//echo $cadena_sql;
		$acreditacion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		
		$html_5.="          <tr class='bloquecentralcuerpobeige'>";
		$html_5.="			<td colspan='3'>";
		$html_5.="			<hr class='hr_subtitulo'/>ULTIMA ACREDITACI&Oacute;N INTERNACIONAL ".$registro[0][1]."";
		$html_5.="			<hr class='hr_subtitulo'/>";
		$html_5.="			</td>";
		$html_5.="		</tr>";
		$html_5.="		<tr>";
		$html_5.="			<td width='30%'>";

               
		$texto_ayuda="Ultimo tipo de acreditaci&oacute;n internacional otorgado al proyecto Curricular.";
		
                $html_5.="<span  onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Tipo de Acreditaci&oacute;n', PADDING, 9)\">Tipo Acreditaci&oacute;n:</span>";
		$html_5.="			</td>";
		$html_5.="			<td>";
						

                $busqueda="SELECT acre_cod_tipo, acre_nombre FROM mntac.acretipo WHERE acre_nivel>=2 AND estado='A' ";
		//echo $busqueda;
		$tipoacre2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
		if(!$acreditacion[0][0])
                    {$tipo_acre2=$html->cuadro_lista($tipoacre2,'tipoacreInt',$configuracion,$acreditacion[0][0],0,FALSE,$tab++,'tipoacreInt');}
		else
                    {$tipo_acre2=$html->cuadro_lista($tipoacre2,'tipoacreInt',$configuracion,-1,3,FALSE,$tab++,'tipoacreInt');}
		
                $html_5.=$tipo_acre2;

		$html_5.="	</td>";
		$html_5.="		</tr>";
		$html_5.="		<tr>";
		$html_5.="			<td width='30%'>";

               
		$texto_ayuda="Acto Administrativo con el que se concede la acreditaci&oacute;n al proyecto curricular. ";
		
                $html_5.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Resoluci&oacute;n', PADDING, 9)\">Resoluci&oacute;n:</span>";
		$html_5.="			</td>";
		$html_5.="			<td>";
		$html_5.="				<input type='text' name='acreresolInt' value='".$acreditacion[0][1]."' size='40' maxlength='50' tabindex='".$tab++."' readonly='readonly'>";
		$html_5.="			</td>";
		$html_5.="		</tr>";
		$html_5.="		<tr>";
		$html_5.="			<td width='30%'>";

                
		$texto_ayuda="Fecha en la que se concedio la acreditaci&oacute;n internacional al proyecto curricular.<br> ";
		$texto_ayuda.="Fecha en formato: dd/mm/aaaa.<br>";
		
                $html_5.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Fecha de Acreditaci&oacute;n', PADDING, 9)\">Fecha Acreditaci&oacute;n:</span>";
		$html_5.="			</td>";
		$html_5.="			<td>";
		$html_5.="				<input type='text' name='acrefechaInt' value='".$acreditacion[0][2]."' size='12' maxlength='25' tabindex='".$tab++."' readonly='readonly'>";
		$html_5.="				<a href=\"javascript:muestraCalendario('".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."','".$this->formulario."','acrefechaInt')\">";
		$html_5.="				<img border='0' src='".$configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/cal.png' width='24' height='24' alt='DD-MM-YYYY'></a>";
		$html_5.="			</td>";
		$html_5.="		</tr>";
		$html_5.="		<tr>";
		$html_5.="			<td width='30%'>";

                
		$texto_ayuda="N&uacute;mero en años del tiempo que dura la acreditaci&oacute;n internacional del proyecto curricular. ";
		
                $html_5.="<span onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Duraci&oacute;n Acreditaci&oacute;n', PADDING, 9)\">Duraci&oacute;n (Años):</span>";
		$html_5.="			</td>";
		$html_5.="			<td>";
		$html_5.="				<input type='text' name='acrevidaInt' value='".$acreditacion[0][3]."' size='12' maxlength='2' tabindex='".$tab++."' onKeyPress='return solo_numero(event)' readonly='readonly'>";
		$html_5.="			</td>";
		$html_5.="		</tr>";
		$html_5.="		<tr>";
		$html_5.="			<td width='30%'>";
					

                
		$texto_ayuda="Entidad Internaciónal que otorgo la acreditaci&oacute;n al proyecto curricular. ";
		
		$html_5.="<span  onmouseover=\"javascript:Tip('".$texto_ayuda."',SHADOW, true, TITLE, 'Entidad Acreditadora', PADDING, 9)\">Entidad Acreditadora:</span>";
		$html_5.="			</td>";
		$html_5.="			<td>";
		$html_5.="				<input type='text' name='EntidadInt'size='40' maxlength='100' onKeyPress='return solo_texto(event)' readonly='readonly'>";
		$html_5.="			</td>";
		$html_5.="		</tr>";
		$html_5.="	</table>";
		$html_5.="	</div>";
		$html_5.="</div>";
 // ----------Cierra el div de acreditacion---------//  -->

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/tabs.class.php");
		$tabs=new tabs($configuracion);

		$tabs->tab($html_1,"Datos B&aacute;sicos");
		$tabs->tab($html_2,"N&uacute;cleos Conocimiento");
		$tabs->tab($html_3,"Duraci&oacute;n");
		$tabs->tab($html_4,"Info. Complemetaria");
		$tabs->tab($html_5,"Acreditaci&oacute;n");

		$html_0.=$tabs->armar_tabs($configuracion);

                
		$html_0.="<div class='tabs' id='divBoton'>";
		$html_0.="<table align='center'>";
		$html_0.="<tr align='center'>";
		$html_0.="<td colspan='2' rowspan='1'>";
		$html_0.="<input type='hidden' name='usuario' value='".$_REQUEST["usuario"]."'>";
		$html_0.="<input type='hidden' name='action' value='admin_proyectoCurricular'>";
		$html_0.="<input type='hidden' name='cod_cra' value='".$registro[0][0]."'>";
		$html_0.="<input value='Guardar' name='aceptar' type='button'  onclick='if(".$this->verificar."){ document.forms[\"".$this->formulario."\"].submit(); }else{ false }'  >";
		$html_0.="<input name='cancelar' value='Cancelar' type='submit'>";
		$html_0.="<br>";
		$html_0.="</td>";
 		$html_0.="  </tr>";
		$html_0.="</table>";
		$html_0.="</div>";
		$html_0.="</form>";
		$html_0.="<div class='tabs' id='divacre'></div>";

                echo $html_0;

	
	}//fin de la funcion form_editar
	
	
	function confirmarRegistro($configuracion,$accion)
	{
		echo "SU REGISTRO SE HA GUARDADO EXITOSMENTE";
		echo "<br>identificador=".$_REQUEST['identificador'];	
	}
	
	
function editar_carrera($configuracion)
	{
		
		$this->revisarFormulario();
		$min = array("á", "é", "í", "ó", "ú");
		$may = array("Á", "Í", "Í", "Ó", "Ú");
		//rescata los valores para actualizar los datos basico
		//----------------------------------------------------
		$accra[0]=$_REQUEST['cod_cra'];
		$accra[1]="To_date('".$_REQUEST['fecha_registro']."','dd/mm/YYYY')";
		$accra[2]=htmlentities($_REQUEST['resol_creacion'], ENT_NOQUOTES, 'UTF-8');
		$accra[3]=$_REQUEST['estado'];
		$accra[4]=$_REQUEST['justifica'];
		$accra[5]=strtoupper($_REQUEST['jornada']);
		
		
		//ejecuta la actualizacion de la carrera
		$carrera_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualizabasico",$accra);
		//echo $carrera_sql;exit;
		@$carrera=$this->ejecutarSQL($configuracion, $this->accesoOracle, $carrera_sql,"");
				
  
  		// rescarta valores y guarda informacion sobre el nucleo de conocimiento
  		//------------------------------------------------------------------------		
	
		$nivel=1;//variable nivel para el nbc
		do
		{	if(isset($_REQUEST['nbc_cod'.$nivel]))
			{
				$nbc[0]=$_REQUEST['nbc_cod'.$nivel];
				$nbc[1]=$accra[0];
				$nbc[2]=$_REQUEST['area_esp'.$nivel];
				$nbc[3]=$nivel;
				
				//busca si existen registro de datos EN EL NUCLEO BASICO DE CONOCIMIENTO 
				$carrera[0]=$accra[0];
				$carrera[1]=$nivel;
				
				$nbc_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"nucleo",$carrera);
				//echo "<br>".$nbc_sql;
				@$nucleo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $nbc_sql, "busqueda");
			
				if($nucleo[0][0]==0)
					{	//inserta datos base del nucleo basico de conocimiento.
						$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"inserta_nucleo",$nbc);
						//echo "<br>".$cadena_sql;
						@$nucleo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
					}
				else
					{//actualiza datos base del nucleo basico de conocimiento.
						$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualiza_nucleo",$nbc);
						//echo "<br>".$cadena_sql;
						@$nucleo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "");
					}	
					
			}	
			$nivel++;
		}
		while($nivel<=2);
	
		//rescata los valores para actualizar los datos basico del tipo de carrera
		//-------------------------------------------------------------------------
		$actipcra[0]=$accra[0];
		$actipcra[1]=strtoupper($_REQUEST['periodicidad']);
		$actipcra[2]=$_REQUEST['duracion'];
							
		//ejecuta la actualizacion de la carrera
		//$tipo_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualiza_tipo",$actipcra);
		//	echo $tipo_sql;
		//@$acreditacion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $tipo_sql,"");		
		


		//rescata los valores para actualizar los datos basico del tipo de carrera
		//-------------------------------------------------------------------------
		//echo $_REQUEST['metodo']." ".$_REQUEST['titulo']." ".$_REQUEST['propedeutico']." ".$_REQUEST['craprop']." ".$_REQUEST['creditos']." ".$_REQUEST['urlcra']." ".$_REQUEST['urlasp']." ".$_REQUEST['urlpro'];

								
		$complecra[0]=$accra[0];
		$complecra[1]=$_REQUEST['metodo'];
		$complecra[2]=htmlentities(strtoupper(str_replace( $min,$may, $_REQUEST['titulo'] )), ENT_NOQUOTES, 'UTF-8');
		$complecra[3]=$_REQUEST['propedeutico'];
		$complecra[4]=$_REQUEST['craprop'];
		$complecra[5]=$_REQUEST['creditos'];
		$complecra[6]=htmlentities($_REQUEST['urlcra'], ENT_NOQUOTES, 'UTF-8');
		$complecra[7]=htmlentities($_REQUEST['urlasp'], ENT_NOQUOTES, 'UTF-8');
		$complecra[8]=htmlentities($_REQUEST['urlpro'], ENT_NOQUOTES, 'UTF-8');
							
		//ejecuta la actualizacion de la carrera
		$comple_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualiza_complemento",$complecra);
		//echo $comple_sql;
		@$complemento=$this->ejecutarSQL($configuracion, $this->accesoOracle, $comple_sql,"");	
				
	
		//rescata los valores para actualizar los datos de acreditacion
		//-------------------------------------------------------------------------
		 
		if($_REQUEST['tipoacre']!=4)
			{//rescata los valores de la acreditacion del proyecto
				$acred[0]=$_REQUEST['acrecra'];
				$acred[1]=$accra[0];
				$acred[2]=$_REQUEST['tipoacre'];
				$acred[3]="To_date('".$_REQUEST['acrefecha']."','dd/mm/YYYY')";
				$acred[4]=htmlentities($_REQUEST['acreresol'], ENT_NOQUOTES, 'UTF-8');
				$acred[5]=$_REQUEST['acrevida'];
				$acred[6]=$_REQUEST['entidad'];
				
					
				if($acred[0]==0)
					{		$busqueda_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_max","");
							//echo $busqueda_sql;		
							$cod=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda_sql, "busqueda");
							//echo "<br>".$cod[0][0]; 
							$acred[0]=($cod[0][0]+1);
					
						//inserta datos base de autoevaluacion.
						$acred_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"inserta_acred",$acred);
						//echo "<br>".$acred_sql;exit;
						@$acredita=$this->ejecutarSQL($configuracion, $this->accesoOracle, $acred_sql, "");
					}
				
				//actualiza datos base de autoevaluacion.
					
				$acred_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualiza_acred",$acred);
				//echo "<br>".$acred_sql;exit;
				@$nucleo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $acred_sql, "");
				
		
			}	
			
			if($_REQUEST['tipoacreInt']!=4 && $_REQUEST['tipoacreInt']>0)
			{//rescata los valores de la acreditacion del proyecto
				$acredint[0]=$_REQUEST['acrecraInt'];
				$acredint[1]=$accra[0];
				$acredint[2]=$_REQUEST['tipoacreInt'];
				$acredint[3]="To_date('".$_REQUEST['acrefechaInt']."','dd/mm/YYYY')";
				$acredint[4]=htmlentities($_REQUEST['acreresolInt'], ENT_NOQUOTES, 'UTF-8');
				$acredint[5]=$_REQUEST['acrevidaInt'];
				$acredint[6]=htmlentities($_REQUEST['entidadInt'], ENT_NOQUOTES, 'UTF-8');
				
		
				if($acredint[0]==0)
					{		$busqueda_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_max","");
							//echo $busqueda_sql;		
							$cod=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda_sql, "busqueda");
							//echo "<br>".$cod[0][0]; 
											
							$acredint[0]=($cod[0][0]+1);
					
						//inserta datos base de autoevaluacion.
						$acred_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"inserta_acred",$acredint);
						//echo "<br>".$acred_sql;exit;
						@$acredita=$this->ejecutarSQL($configuracion, $this->accesoOracle, $acred_sql, "");
					}
					
				$acred_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actualiza_acred",$acredint);
				//echo "<br>".$acred_sql;exit;
				@$nucleo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $acred_sql, "");
					
			}		
		
		$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
		$variable="pagina=ProyectoCurricular";
		$variable.="&opcion=consultar";
		$variable.="&carrera=".$_REQUEST['cod_cra'];
		$variable.="&xajax=nbcPrimario|nbcSecundario|justificaEst|propeCra|acreCra|procesar_formulario|cancelaAcre";
		$variable.="&xajax_file=proyecto";
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$this->cripto=new encriptar();
		$variable=$this->cripto->codificar_url($variable,$configuracion);
	echo "<script>location.replace('".$pagina.$variable."')</script>";
	
	}


	
}
	

?>

