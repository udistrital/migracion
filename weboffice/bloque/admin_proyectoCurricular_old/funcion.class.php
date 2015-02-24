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
			$parametro.="&xajax=nbcPrimario|nbcSecundario|justificaEst|propeCra|acreCra|procesar_formulario|cancelaAcre";
			$parametro.="&xajax_file=proyecto";
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
		$this->verificar.="control_vacio_divs(".$this->formulario.",'fecha_registro','datosbasicos','".$divs."')";
		$this->verificar.="&&fecha_divs(".$this->formulario.",'fecha_registro','".date("d/m/Y",time())."','datosbasicos','".$divs."')";
		$this->verificar.="&&control_vacio_divs(".$this->formulario.",'resol_creacion','datosbasicos','".$divs."')";
		$this->verificar.="&& seleccion_valida_divs(".$this->formulario.",'area_cod1','infonbc','".$divs."')";
		$this->verificar.="&& seleccion_valida_divs(".$this->formulario.",'nbc_cod1','infonbc','".$divs."')";
		$this->verificar.="&& seleccion_valida_divs(".$this->formulario.",'area_esp1','infonbc','".$divs."')";
		$this->verificar.="&& control_vacio_divs(".$this->formulario.",'creditos','infoduracion','".$divs."')";
		$this->verificar.="&& control_vacio_divs(".$this->formulario.",'titulo','infoadicional','".$divs."')";
		$this->verificar.="&& control_vacio_divs(".$this->formulario.",'urlcra','infoadicional','".$divs."')";
		$this->verificar.="&& control_vacio_divs(".$this->formulario.",'urlasp','infoadicional','".$divs."')";
		$this->verificar.="&& control_vacio_divs(".$this->formulario.",'urlpro','infoadicional','".$divs."')";
		$this->verificar.="&& seleccion_valida_divs(".$this->formulario.",'tipoacre','infoacreditacion','".$divs."')";
		$this->verificar.="&& control_vacio_divs(".$this->formulario.",'acreresol','infoacreditacion','".$divs."')";
		$this->verificar.="&& control_vacio_divs(".$this->formulario.",'acrefecha','infoacreditacion','".$divs."')";
		$this->verificar.="&&fecha_divs(".$this->formulario.",'acrefecha','".date("d/m/Y",time())."','infoacreditacion','".$divs."')";
		$this->verificar.="&& control_vacio_divs(".$this->formulario.",'acrevida','infoacreditacion','".$divs."')";

		
		//$this->verificar.="&& longitud_cadena(".$this->formulario.",'fecha',3)";seleccion_valida(formulario,control)
		//$this->verificar.="&& verificar_correo(".$this->formulario.",'descripcion')";	seleccion_valida(formulario,control)
		


		
		?>
		<script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
		<hr>
		
		<table width='80%' height="45" valign="top" >		
		<tr>	
		<td width='80%'><br></td>		
		<td style='text-align:center'><a href='javascript:muestra_div("datosbasicos","<? echo $divs ?>")' id='btdatosbasicos' class='mostrar'>Datos B&aacute;sicos</a></td>
		<td style='text-align:center'><a href='javascript:muestra_div("infonbc","<? echo $divs ?>")' id='btinfonbc' class='mostrar'>Núcleos Conocimiento</a></td>
		<td style='text-align:center'><a href='javascript:muestra_div("infoduracion","<? echo $divs ?>")' id='btinfoduracion' class='mostrar'>Duraci&oacute;n</a></td>
		<td style='text-align:center'><a href='javascript:muestra_div("infoadicional","<? echo $divs ?>")' id='btinfoadicional' class='mostrar'>Informaci&oacute;n. Complemetaria</a></td>
		<td style='text-align:center'><a href='javascript:muestra_div("infoacreditacion","<? echo $divs ?>")' id='btinfoacreditacion' class='mostrar'>Acreditaci&oacute;n</a></td>
		</tr>	
		<tr>
			<td colspan="5"><font color="red" size="-2"  ><br>Todos los campos marcados con ( * ) son obligatorios. <br></font></td>
		</tr>
		</table>
		
		<div width='80%' class='tabs' id='datosbasicos'>
		<table width='80%'  class='formulario'  align='center'>
		<tr class='bloquecentralcuerpobeige'><td  colspan='3'><hr class='hr_subtitulo'/>DATOS BASICOS DEL PROYECTO CURRICULAR<hr class='hr_subtitulo'/></td></tr>		
		<tr>
			  <td width='30%'><?
				$texto_ayuda="<b>Codigo Interno del proyecto.</b>";
				?><span onmouseover="return escape('<? echo $texto_ayuda?>')" class="texto_negrita">C&oacute;digo Interno:</span>
			  </td>
			  <td>
				 <input type='hidden' id='carrera' name='carrera' value='<? echo $registro[0][0] ?>'>
				 <? echo $registro[0][0] ?>
			 </td>
		</tr>
		<tr>
	  	   <td width='30%'><?
				$texto_ayuda="<b>Nombre Completo registrado ante el ICFES.</b>";
				?>	<span onmouseover="return escape('<? echo $texto_ayuda?>')" class="texto_negrita">Nombre:</span>
			</td>
			<td>
				 <? echo $registro[0][1] ?>
			</td>
		</tr>
		<tr>
			<td width='30%'><?
				$texto_ayuda="<b>Nombre de no m&aacute;s de 20 caracteres.</b> ";
				$texto_ayuda.="Con el que identifica el proyecto internamente.<br>";
				$texto_ayuda.="En la generaci&oacute;n de informes se utiliza el nombre completo registrado ante el ICFES.";
			?>	<span onmouseover="return escape('<? echo $texto_ayuda?>')">Nombre Corto:</span>
			</td>
			<td>
				 <? echo $registro[0][2] ?>
			</td>
		</tr>
		<tr>
		    <td width='30%'><?
				$texto_ayuda="<b>N&uacute;mero de registro ICFES del programa.</b> ";
				?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Registro ICFES:</span>
			</td>
			<td>
			 	<? echo $registro[0][3] ?>
			</td>
		</tr>	
		<tr>
			<td width='30%'><?	
				$texto_ayuda="<b>Fecha de registro del programa por parte del ICFES.</b><br> ";
				$texto_ayuda.="Fecha en formato: dd/mm/aaaa.<br>";
				?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">Fecha Registro ICFES:</span>
			</td>
			<td><script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>/funciones.js" type="text/javascript" language="javascript"></script>
				<input type='text' name='fecha_registro' value='<? echo $registro[0][4] ?>' size='12' maxlength='25' tabindex='<? echo $tab++ ?> 'readonly="readonly">
				
				<a href="javascript:muestraCalendario('<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"] ?>','<? echo $this->formulario;?>','fecha_registro')">
				<img border="0" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/cal.png"?>" width="24" height="24" alt="DD-MM-YYYY"></a>
			</td>
		</tr>	
		<tr>
			<td width='30%'><?
				$texto_ayuda="<b>Acto administrativo de creaci&oacute;n del proyecto.</b><br> ";
				$texto_ayuda.="Nombre y n&uacute;mero del documento mediante el cual se cre&oacute; el proyecto.</b><br> ";
				?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">Norma Interna de Creaci&oacute;n:</span>
			</td>
			<td>
				<input type='text' name='resol_creacion' value='<? echo $registro[0][5] ?>' size='60' maxlength='50' tabindex='<? echo $tab++ ?>' >
			</td>
		</tr>		
		<tr>
		    <td width='30%'><?
				$texto_ayuda="<b>Facultad;</b><br>";
				$texto_ayuda.="Sede de la universidad en la que se dicta el proyecto<br>";
				?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Facultad:</span>
			</td>
			<td>
				<? echo $registro[0][6] ?>
			</td>
		</tr>
		<tr>
		    <td width='30%'><?
				$texto_ayuda="<b>Nivel acad&eacute;mico del proyecto.</b><br>";
				$texto_ayuda.="Corresponde al aprobado por el ICFES y puede ser entre otros:<b> Tecnolog&iacute;a, Pregrado, Especializaci&oacute;n, Maestr&iacute;a, Doctorado.</b>";
				?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Nivel Acad&eacute;mico:</span>
			</td>
			<td>
				<? echo $registro[0][7] ?>
			</td>
		</tr>	
		<tr>
		   <td width='30%'><?
				$texto_ayuda="<b>Nivel de Formación del proyecto</b><br>";
				$texto_ayuda.="Tecnol&oacute;logico, Ingenieria, Especialización, etc.<br>";
				?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Formación:</span>
		   </td>
		   <td>
				<? echo $registro[0][9] ?>
		   </td>
		</tr>	
		<tr>
		   <td width='30%'><?
				$texto_ayuda="<b>Estado</b><br>";
				$texto_ayuda.="Estado Actual del proyecto (Activo, Inactivo) .<br>";
				?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Estado:</span>
		   </td>
		   <td><?
					$estado[0][0]="A";
					$estado[0][1]="ACTIVO";
					$estado[1][0]="I";
					$estado[1][1]="INACTIVO";
					$configuracion["ajax_function"]="xajax_justificaEst";
		            $configuracion["ajax_control"]="estado";
					
					$est_cuadro1=$html->cuadro_lista($estado,'estado',$configuracion,$registro[0][10],3,FALSE,$tab++,'estado');
					echo $est_cuadro1;
					?>
					<input type='hidden' name='estado' value='<? echo $registro[0][10] ?>'>
					
			</td>
		</tr>
		<tr>
			<td width='30%'><?
				$texto_ayuda="<b>Justificación</b><br>";
				$texto_ayuda.="Justificación del estado Inactivo del proyecto.<br>";
				?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Justificaci&oacute;n:</span>
			</td>
			<td><div id="divjust">
			    <?   $busqueda="SELECT just_cod, just_desc FROM mntac.justifica ORDER BY just_desc";
			    	// echo $busqueda;
			    	 $justifica=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
					if($registro[0][10]=="I" && $registro[0][11]!=0)
						{$just_est=$html->cuadro_lista($justifica,'justifica',$configuracion,$registro[0][11],0,FALSE,$tab++,'justifica');}
					elseif($registro[0][10]=="I" && $registro[0][11]==0)
						{$just_est=$html->cuadro_lista($justifica,'justifica',$configuracion,0,0,FALSE,$tab++,'justifica');}	
					else
						{$just_est=$html->cuadro_lista($justifica,'justifica',$configuracion,-1,3,FALSE,$tab++,'justifica');}	
					echo $just_est;
				?></div><!-- cierra divjust-->
			</td>
		  </tr>
		</table>
		</div>
<!-- /-----------finaliza div de datos basicos------------/ -->		
				
		<div class='tabs' id='infonbc'>
		<table class='formulario' align='center'>
			<tr class='bloquecentralcuerpobeige'><td  colspan='3'><hr class='hr_subtitulo'/>NUCLEO BASICO DE CONOCIMIENTO <? echo $registro[0][1] ?><hr class='hr_subtitulo'/></td></tr>
				<?$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"nbc",$registro[0][0]);
				//echo "<br>".$cadena_sql;
				$nbc=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
				//$totalnbc=$this->totalRegistros($configuracion, $this->acceso_db);
				//echo "total ".$totalnbc;
				?>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>&Aacute;rea Aconocimiento</b><br>";
					$texto_ayuda.="&Aacute;rea de principal de conocimiento en la que se ubica el proyecto ";
					$texto_ayuda.=" (INGENIERIA, ARQUITECTURA, URBANISMO Y AFINES; MATEMATICAS Y CIENCIAS NATURALES; etc) .<br>";
					?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">&Aacute;rea Conocimiento Primaria:</span>
				</td>
				<td><?
					$busqueda="SELECT area_cod, area_nombre FROM mntac.areacon ORDER BY area_nombre";
					//echo $busqueda;
					$area1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
					$configuracion["ajax_function"]="xajax_nbcPrimario";
				    $configuracion["ajax_control"]="area_con1";
					if($nbc[0][1]!=0)
						{$con_cuadro1=$html->cuadro_lista($area1,'area_cod1',$configuracion,$nbc[0][1],2,FALSE,$tab++,'area_con1');}
					else
						{$con_cuadro1=$html->cuadro_lista($area1,'area_cod1',$configuracion,-1,2,FALSE,$tab++,'area_con1');}	
					echo $con_cuadro1;
					?>	
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>N&uacute;cleo Primario</b><br>";
					$texto_ayuda.="N&uacute;cleo principal de conocimiento en la que se ubica el proyecto ";
					$texto_ayuda.=" (ADMINISTRACI&Oacute;N, EDUCACI&Oacute;N, ARQUITECTURA, etc) .<br>";
					?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">N&uacute;cleo Primario:</span>
				</td>
				<td>
				<div id="divnbc1"><?
						$busqueda="SELECT nbc_cod, nbc_cod_nombre FROM mntac.nbc WHERE nbc_cod_area=".$nbc[0][1]." ORDER BY nbc_cod_nombre";
						//echo 	$busqueda;
						if($nbc[0][0]!=0)
							{   $nucleo1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
								$nbc_cuadro=$html->cuadro_lista($nucleo1,'nbc_cod1',$configuracion,$nbc[0][0],0,FALSE,$tab++,'nbc1');}
						else
							{   $nucleo1="";
								$nbc_cuadro=$html->cuadro_lista($nucleo1,'nbc_cod1',$configuracion,-1,0,FALSE,$tab++,'nbc1');}	
						echo $nbc_cuadro;?>
				</div>
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>&Aacute;rea Espec&iacute;fica Primaria</b><br>";
					$texto_ayuda.="&Aacute;rea espec&iacute;fica de trabajo, principal en la que se enmarca el proyecto";
					$texto_ayuda.=" (Educaci&oacute;n superior profesional, Educaci&oacute;n superior tecnol&oacute;gica, etc) .<br>";
					?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">&Aacute;rea Especifica Primaria:</span>
				</td>
				<td><?
					$busqueda="SELECT aesp_cod, aesp_nombre FROM mntac.areaesp ORDER BY aesp_nombre";
					//echo $busqueda;
					$espec1=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
					if($nbc[0][2]!=0)
						{$esp_cuadro1=$html->cuadro_lista($espec1,'area_esp1',$configuracion,$nbc[0][2],0,FALSE,$tab++,'area_esp1');}
					else
						{$esp_cuadro1=$html->cuadro_lista($espec1,'area_esp1',$configuracion,-1,0,FALSE,$tab++,'area_esp1');}	
					echo $esp_cuadro1;
					?>	
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>&Aacute;rea Conocimiento</b><br>";
					$texto_ayuda.="&Aacute;rea de Secundaria de conocimiento en la que se ubica el proyecto";
					$texto_ayuda.=" (INGENIERIA, ARQUITECTURA, URBANISMO Y AFINES; MATEMATICAS Y CIENCIAS NATURALES; etc) .<br>";
					?><span onmouseover="return escape('<? echo $texto_ayuda?>')">&Aacute;rea Conocimineto Secundaria:</span>
				</td>
				<td><?
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
					$html=new html();
					$busqueda="SELECT area_cod, area_nombre FROM mntac.areacon ORDER BY area_nombre";
					//echo $busqueda;
					$area2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
					$configuracion["ajax_function"]="xajax_nbcSecundario";
				    $configuracion["ajax_control"]="area_con2";
					if($nbc[1][1]!=0)
						{$con_cuadro2=$html->cuadro_lista($area2,'area_cod2',$configuracion,$nbc[1][1],2,FALSE,$tab++,'area_con2');}
					else
						{$con_cuadro2=$html->cuadro_lista($area2,'area_cod2',$configuracion,0,2,FALSE,$tab++,'area_con2');}	
					echo $con_cuadro2;
					?>	
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>N&uacute;cleo Secundario</b><br>";
					$texto_ayuda.="N&uacute;cleo principal de conocimiento en la que se ubica el proyecto ";
					$texto_ayuda.=" (ADMINISTRACI&Oacute;N, EDUCACI&Oacute;N, ARQUITECTURA, etc) .<br>";
					?><span onmouseover="return escape('<? echo $texto_ayuda?>')">N&uacute;cleo Secundario:</span>
				</td>
				<td>
				<div id="divnbc2"><?
					$html=new html();
										
					$busqueda="SELECT nbc_cod, nbc_cod_nombre FROM mntac.nbc WHERE nbc_cod_area=".$nbc[1][1]." ORDER BY nbc_cod_nombre";
					//echo 	$busqueda;
					
					if($nbc[1][0]!=0)
						{   $nucleo2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
							$nbc_cuadro2=$html->cuadro_lista($nucleo2,'nbc_cod2',$configuracion,$nbc[1][0],0,FALSE,$tab++,'nbc2');}
					else
						{   $nucleo2="";
							$nbc_cuadro2=$html->cuadro_lista($nucleo2,'nbc_cod2',$configuracion,-1,0,FALSE,$tab++,'nbc2');}	
					echo $nbc_cuadro2;?>
				</div>
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>&Aacute;rea Espec&iacute;fica Secundaria</b><br>";
					$texto_ayuda.="&Aacute;rea espec&iacute;fica de trabajo en la que se enmarca el proyecto";
					$texto_ayuda.=" (Educaci&oacute;n superior profesional, Educaci&oacute;n superior tecnol&oacute;gica, etc) .<br>";
					?><span onmouseover="return escape('<? echo $texto_ayuda?>')">&Aacute;rea Espec&iacute;fica Secundaria:</span>
				</td>
				<td><?
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
					$html=new html();
					$busqueda="SELECT aesp_cod, aesp_nombre FROM mntac.areaesp ORDER BY aesp_nombre";
					//echo $busqueda;
					$espec2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
					if($nbc[1][2]!=0)
						{$esp_cuadro2=$html->cuadro_lista($espec2,'area_esp2',$configuracion,$nbc[1][2],0,FALSE,$tab++,'area_esp2');}
					else
						{$esp_cuadro2=$html->cuadro_lista($espec2,'area_esp2',$configuracion,-1,0,FALSE,$tab++,'area_esp2');}	
					echo $esp_cuadro2;
					?>	
				</td>
			</tr>
		</table>
		</div>
		<!-- /------------Cierra el div para el area de conocimientos------------------------/ -->
		
		<div class='tabs' id='infoduracion'>
		<table  class='formulario'  align='center'>
			<tr class='bloquecentralcuerpobeige'><td  colspan='3'><hr class='hr_subtitulo'/>DURACI&Oacute;N PROYECTO CURRICULAR  <? echo $registro[0][1] ?><hr class='hr_subtitulo'/></td></tr>
			<tr>
				<td width='30%'><?								$texto_ayuda="<b>Nombre de los periodos en que se divide el programa.</b><br>";
					$texto_ayuda.="Usualmente se expresa en t&eacute;rminos de tiempo. Ejemplo: ";
					$texto_ayuda.="<b>Bimestre, Trimestre, Semestre, A&ntilde;o,etc.</b> ";
					?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Periodicidad:</span>
				</td>
				<td>
					<input type='hidden' name='periodicidad' value='<? echo $registro[0][12] ?>' size='20' maxlength='15' tabindex='<? echo $tab++ ?>' onKeyPress="return solo_texto(event)">
				<? echo $registro[0][12] ?>
			</td>
			</tr>	
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>N&uacute;mero de periodos que componen el plan de estudios.</b>";
					?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Duraci&oacute;n:<br></span>
				</td>
				<td>
					<input type='hidden' name='duracion' value='<? echo $registro[0][13] ?>' size='20' maxlength='2' tabindex='<? echo $tab++ ?>' onKeyPress="return solo_numero(event)" >
					<? echo $registro[0][13] ?>
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>Jornada en que se desarrolla el plan de estudios.</b><br>";
					$texto_ayuda.="Corresponde a la jornada aprobada por el ICFES y puede ser entre otras:<b>Diurna, nocturna</b>";
					?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">Jornada:</span>
				</td>
				<td><?
						$jorn[0][0]="DIURNA";
						$jorn[0][1]="DIURNA";						
						$jorn[1][0]="NOCTURNA";
						$jorn[1][1]="NOCTURNA";
						
						if($registro[0][14]=="NOCTURNA")
							{$jornada=$html->cuadro_lista($jorn,'jornada',$configuracion,"NOCTURNA",0,FALSE,$tab++,'jornada');}
						else
							{$jornada=$html->cuadro_lista($jorn,'jornada',$configuracion,"DIURNA",0,FALSE,$tab++,'jornada');}	
						echo $jornada;
						?>
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>Modalidad en la que se dicta el plan de estudios.</b><br>";
					$texto_ayuda.="Corresponde a la modalidad aprobada por el ICFES y puede ser entre otras:<b>Presencial, semi presencial, a distancia.</b>";
					?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Modalidad:</span>
				</td>
				<td>
				<?
					$busqueda="SELECT met_cod, met_nombre FROM mntac.metodologia ORDER BY met_nombre";
					//echo $busqueda;
					$metodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
					if($registro[0][15]!=0)
						{$mod_cuadro=$html->cuadro_lista($metodo,'metodo',$configuracion, $registro[0][15] ,0,FALSE,$tab++,'metodo');}
					else
						{$mod_cuadro=$html->cuadro_lista($metodo,'metodo',$configuracion,1,0,FALSE,$tab++,'metodo');}	
					echo $mod_cuadro;
					?>	
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>N&uacute;mero de Creditos.</b><br>";
					$texto_ayuda.="Cantidad de creditos asignados al proyectos curricular.</b>";
					?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">Creditos:</span>
				</td>
				<td>
					<input type='text' name='creditos' value='<? echo $registro[0][16] ?>' size='20' maxlength='4' tabindex='<? echo $tab++ ?>' onKeyPress="return solo_numero(event)" >
				</td>
			</tr>
		</table>
		</div>		
<!--// Cierra el div de duracion del proyecto//-->

		<div class='tabs' id='infoadicional'>
		<table class='formulario' align='center'>
			<tr class='bloquecentralcuerpobeige'><td  colspan='3'><hr class='hr_subtitulo'/>DATOS COMPLEMENTARIOS  <? echo $registro[0][1] ?><hr class='hr_subtitulo'/></td></tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>T&iacute;tulo que otorga el programa.</b><br>";
					$texto_ayuda="T&iacute;tulo general para el reporte.</b><br>";
					?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">T&iacute;tulo:</span>
				</td>
				<td>
					<input type='text' name='titulo' value='<? echo $registro[0][17] ?>' size='60' maxlength='200' tabindex='<? echo $tab++ ?>' >
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>URL del proyecto curricular.</b><br>";
					$texto_ayuda.="Direcci&oacute;n electr&oacute;nica de la pagina Web del Proyecto.";
					?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">URL Proyecto:</span>
				</td>
				<td>
					<input type='text' name='urlcra' value='<? echo $registro[0][18]  ?>' size='60' maxlength='200' tabindex='<? echo $tab++ ?>'>
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>URL del perfil del Aspirante.</b><br>";
					$texto_ayuda.="Direcci&oacute;n electr&oacute;nica de la pagina donde se encunetra el Perfil de los aspirantes.";
					?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">URL Perfil Aspirante:</span>
				</td>
				<td>
					<input type='text' name='urlasp' value='<? echo $registro[0][19]  ?>' size='60' maxlength='200' tabindex='<? echo $tab++ ?>' >
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>URL del perfil Profesional del Estudiante.</b><br>";
					$texto_ayuda.="Direcci&oacute;n electr&oacute;nica de la pagina donde se encunetra el Perfil Profesional del estudiante.";
					?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">URL Perfil Profesional:</span>
				</td>
				<td>
					<input type='text' name='urlpro' value='<? echo $registro[0][20]  ?>' size='60' maxlength='200' tabindex='<? echo $tab++ ?>' >
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>Ciclo Propedeutico</b><br>";
					$texto_ayuda.="Indica si el programa pertenece a ciclo propedéutico .<br>";
					?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Ciclo Propedéutico:</span>
				</td>
				<td><?
					$estado[0][0]="N";
					$estado[0][1]="NO";
					$estado[1][0]="S";
					$estado[1][1]="SI";
					$configuracion["functionjs"]="facultad_propedeutico()";
					
					$est_cuadro1=$html->cuadro_lista($estado,'propedeutico',$configuracion,$registro[0][21],4,FALSE,$tab++,'propedeutico');
					echo $est_cuadro1;
							?>
				</td>
			</tr>
			<tr>
				<td width='30%'><?
					$texto_ayuda="<b>Proyecto Curricular Superior.</b><br>";
					$texto_ayuda.="En caso de proyectos en ciclo propedeutico, selecciona el proyectos curricular que corresponde a la continuaci&oacute;n del ciclo propedeutico<br>";
					?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Proyecto Nivel Superior:</span>
				</td>
				<td>
				<div id="divporpCra">
					<?
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
					echo $cra_prop;?>
				</div>
				</td>
			</tr>
		</table>	
		</div>				
<!-- //--------------Cierra el div de informacion complementaria--------------------- //--> 				
		
		<div class='tabs' id='infoacreditacion'>
			<div class='tabs' id='acrednal'>
			<table class='formulario' align='center'>		
			<?  $acrenal[0]=$registro[0][0];
			    $acrenal[1]=1;
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"acreditacion",$acrenal);
			//echo $cadena_sql;
		  	   $acreditacion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
			?>
				<tr class='bloquecentralcuerpobeige'>
					<td colspan='3'>
					<hr class='hr_subtitulo'/>
					<table class='formulario' align='center'>
						<tr>
							<td width="60%">
							ULTIMA ACREDITACI&Oacute;N NACIONAL <? echo $registro[0][1] ?>
							</td>
							<td align="right">
							<?
							 $ruta="pagina=proyectoCurricular&hoja=1&opcion=acreditacion";
							 $ruta.="&carrera=".$registro[0][0];
						     $ruta=$this->cripto->codificar_url($ruta,$configuracion);
						     $ruta=$indice.$ruta;		
							?>
							<a  href="javascript:abrir_emergenteUbicada('<? echo $ruta;?>','Acreditaciones','600','300','100','350')">
							<b>Ver Acreditaciones</b>
							<img border="0" hspace="2" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/viewdoc.png"?>" width="24" height="24">
							</a>
							</td>
							<td align="right">
							<a  href="javascript:nueva_acre('divBoton','1','<? echo $registro[0][0]?>','acredext')">
							<b>Ingresar nueva Acreditaci&oacute;n</b>
							<img border="0" hspace="2" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/new.png"?>" width="24" height="24">
							</a>
							</td>
						</tr>
					</table>	
					<hr class='hr_subtitulo'/>
					</td>
				</tr>
				<tr>
					<td width='30%'><?$texto_ayuda="<b>Tipo de Acreditaci&oacute;n.</b><br>";
						$texto_ayuda.="Ultimo tipo de acreditaci&oacute;n obtenido por el proyecto.";
						?><font color="red" >*</font><span  onmouseover="return escape('<? echo $texto_ayuda?>')">Tipo Acreditaci&oacute;n:</span>
					</td>
					<td>
						<?
						$busqueda="SELECT acre_cod_tipo, acre_nombre FROM mntac.acretipo WHERE acre_nivel<=2 AND estado='A' ";
						//echo $busqueda;
						$tipoacre=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
						if($acreditacion[0][1]!=0)
							{$tipo_acre=$html->cuadro_lista($tipoacre,'tipoacre',$configuracion,$acreditacion[0][1],0,FALSE,$tab++,'tipoacre');}
						else
							{$tipo_acre=$html->cuadro_lista($tipoacre,'tipoacre',$configuracion,-1,0,FALSE,$tab++,'tipoacre');}	
						echo $tipo_acre;
						?>
					</td>
				</tr>
				<tr>
					<td width='30%'><?$texto_ayuda="<b>Resoluci&oacute;n.</b><br>";
						$texto_ayuda.="Acto Administrativo con el que se concede la acreditaci&oacute;n al proyecto curricular. ";
						?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">Resoluci&oacute;n:</span>
					</td>
					<td>
						<input type='text' name='acreresol' value='<? echo $acreditacion[0][2] ?>' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' >
					</td>
				</tr>
				<tr>
					<td width='30%'><?$texto_ayuda="<b>Fecha Acreditaci&oacute;n.</b><br>";
						$texto_ayuda.="Fecha en la que se concedio la acreditaci&onacute;n al proyecto curricular.<br> ";
						$texto_ayuda.="Fecha en formato: dd/mm/aaaa.<br>";
						?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">Fecha Acreditaci&oacute;n:</span>
					</td>
					<td>
						<input type='text' name='acrefecha' value='<? echo $acreditacion[0][3] ?>' size='12' maxlength='25' tabindex='<? echo $tab++ ?>' readonly="readonly">
						<a href="javascript:muestraCalendario('<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>','<? echo $this->formulario;?>','acrefecha')">
						<img border="0" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/cal.png"?>" width="24" height="24" alt="DD-MM-YYYY"></a>
					</td>
				</tr>
				<tr>
					<td width='30%'><?$texto_ayuda="<b>Duraci&oacute;n Acreditaci&oacute;n.</b><br>";
						$texto_ayuda.="N&uacute;mero en años del tiempo que dura la acreditaci&oacute;n del proyecto curricular. ";
						?><font color="red" >*</font><span onmouseover="return escape('<? echo $texto_ayuda?>')">Duraci&oacute;n (Años):</span>
					</td>
					<td>
						<input type='text' name='acrevida' value='<? echo $acreditacion[0][4] ?>' size='12' maxlength='2' tabindex='<? echo $tab++ ?>' onKeyPress="return solo_numero(event)" >
						<input type='hidden' name='entidad' value=''>
						<input type='hidden' name='acrecra' value='<? echo $acreditacion[0][0] ?>'>
					</td>
				</tr>
			</table>	
			</div>
			<div class='tabs' id='acredext'>
			<table class='formulario' align='center'>		
			<? $acrext[0]=$registro[0][0];
			   $acrext[1]=3;
			   $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"acreditacion",$acrext);
			//echo $cadena_sql;
		  	   $acreditacion=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
			?>
				<tr class='bloquecentralcuerpobeige'>
					<td colspan='3'>
					<hr class='hr_subtitulo'/>ULTIMA ACREDITACI&Oacute;N INTERNACIONAL <? echo $registro[0][1] ?>
					<hr class='hr_subtitulo'/>
					</td>
				</tr>
				<tr>
					<td width='30%'><?$texto_ayuda="<b>Tipo de Acreditaci&oacute;n.</b><br>";
						$texto_ayuda.="Ultimo tipo de acreditaci&oacute;n internacional otorgado al proyecto Curricular.";
						?><span  onmouseover="return escape('<? echo $texto_ayuda?>')">Tipo Acreditaci&oacute;n:</span>
					</td>
					<td>
						<?
						$busqueda="SELECT acre_cod_tipo, acre_nombre FROM mntac.acretipo WHERE acre_nivel>=2 AND estado='A' ";
						//echo $busqueda;
						$tipoacre2=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
						if(!$acreditacion[0][0])
							{$tipo_acre2=$html->cuadro_lista($tipoacre2,'tipoacreInt',$configuracion,$acreditacion[0][0],0,FALSE,$tab++,'tipoacreInt');}
						else
							{$tipo_acre2=$html->cuadro_lista($tipoacre2,'tipoacreInt',$configuracion,-1,3,FALSE,$tab++,'tipoacreInt');}	
						echo $tipo_acre2;
						?>
					</td>
				</tr>
				<tr>
					<td width='30%'><?$texto_ayuda="<b>Resoluci&oacute;n.</b><br>";
						$texto_ayuda.="Acto Administrativo con el que se concede la acreditaci&oacute;n al proyecto curricular. ";
						?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Resoluci&oacute;n:</span>
					</td>
					<td>
						<input type='text' name='acreresolInt' value='<? echo $acreditacion[0][1] ?>' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' readonly="readonly">
					</td>
				</tr>
				<tr>
					<td width='30%'><?$texto_ayuda="<b>Fecha Acreditaci&oacute;n.</b><br>";
						$texto_ayuda.="Fecha en la que se concedio la acreditaci&onacute;n internacional al proyecto curricular.<br> ";
						$texto_ayuda.="Fecha en formato: dd/mm/aaaa.<br>";
						?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Fecha Acreditaci&oacute;n:</span>
					</td>
					<td>
						<input type='text' name='acrefechaInt' value='<? echo $acreditacion[0][2] ?>' size='12' maxlength='25' tabindex='<? echo $tab++ ?>' readonly="readonly">
						<a href="javascript:muestraCalendario('<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>','<? echo $this->formulario;?>','acrefechaInt')">
						<img border="0" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/cal.png"?>" width="24" height="24" alt="DD-MM-YYYY"></a>
					</td>
				</tr>
				<tr>
					<td width='30%'><?$texto_ayuda="<b>Duraci&oacute;n Acreditaci&oacute;n.</b><br>";
						$texto_ayuda.="N&uacute;mero en años del tiempo que dura la acreditaci&oacute;n internacional del proyecto curricular. ";
						?><span onmouseover="return escape('<? echo $texto_ayuda?>')">Duraci&oacute;n (Años):</span>
					</td>
					<td>
						<input type='text' name='acrevidaInt' value='<? echo $acreditacion[0][3] ?>' size='12' maxlength='2' tabindex='<? echo $tab++ ?>' onKeyPress="return solo_numero(event)" readonly="readonly">
					</td>
				</tr>
				<tr>
					<td width='30%'>
					<?
					$texto_ayuda="<b>Entidad Acreditaci&oacute;n.</b><br>";
					$texto_ayuda.="Entidad Internaciónal que otorgo la acreditaci&oacute;n al proyecto curricular. ";
					?>
					<span  onmouseover="return escape('<? echo $texto_ayuda?>')">Entidad Acreditadora:</span>
					</td>
					<td>
						<input type='text' name='EntidadInt'size='40' maxlength='100' onKeyPress="return solo_texto(event)" readonly="readonly">
					</td>		
				</tr>
			</table>	
			</div>
		</div>
<!-- // ----------Cierra el div de acreditacion---------//  -->	
		<div class='tabs' id="divBoton">
		<table align='center'>
		  <tr align='center'>
			<td colspan='2' rowspan='1'>
				<input type='hidden' name='usuario' value='<? echo $_REQUEST["usuario"] ?>'>
				<input type='hidden' name='action' value='admin_proyectoCurricular'>
				<input type='hidden' name='cod_cra' value='<? echo $registro[0][0] ?>'>	
				<input value="Guardar" name="aceptar" tabindex='<?= $tab++ ?>' type="button" onclick="if(<?= $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
			    <input name='cancelar' value='Cancelar' type='submit'>
				<br>
			</td>
 		  </tr>
		</table>
		</div>
		</form>
		<div class='tabs' id="divacre"></div>	
<?
	
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

