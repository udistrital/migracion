<?

function nbcPrimario($tipo)
{	
	//rescata el valor de la configuracion
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
	//$conexion_db=$conexion->conectarDB($configuracion,"");
	

	$html=new html();

	
	$busqueda="SELECT nbc_cod, nbc_cod_nombre FROM mntac.nbc WHERE nbc_cod_area=".$tipo." ORDER BY nbc_cod_nombre";
	//echo $busqueda;
	$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
	$nbc_cuadro=$html->cuadro_lista($resultado,'nbc_cod1',$configuracion,1,0,FALSE,$tab++,'nbc1');
	//se crea el objeto xajax para enviar la respuesta
	$respuesta = new xajaxResponse();
	//Se asignan los valores al objeto y se envia la respuesta	
	$respuesta->addAssign("divnbc1","innerHTML",$nbc_cuadro);
	return $respuesta;	
	
}

function nbcSecundario($tipo)
{	
	//rescata el valor de la configuracion
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
	//$conexion_db=$conexion->conectarDB($configuracion,"");
	

	$html=new html();

	$busqueda="SELECT nbc_cod, nbc_cod_nombre FROM mntac.nbc WHERE nbc_cod_area=".$tipo." ORDER BY nbc_cod_nombre";
	//echo $busqueda;
	$resultado=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
	$nbc_cuadro2=$html->cuadro_lista($resultado,'nbc_cod2',$configuracion,1,0,FALSE,$tab++,'nbc2');
	//se crea el objeto xajax para enviar la respuesta
	$respuesta = new xajaxResponse();
	//Se asignan los valores al objeto y se envia la respuesta	
	$respuesta->addAssign("divnbc2","innerHTML",$nbc_cuadro2);
	return $respuesta;	
	
}

function justificaEst($tipo)
{	
	//rescata el valor de la configuracion
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
	//$conexion_db=$conexion->conectarDB($configuracion,"");
	

	$html=new html();

	$busqueda="SELECT just_cod, just_desc FROM mntac.justifica ORDER BY just_desc";
	//echo $busqueda;
	$justifica=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
	
	if($tipo=="I")
		{$just_est=$html->cuadro_lista($justifica,'justifica',$configuracion,0,0,FALSE,$tab++,'justifica');}	
	else
		{$just_est=$html->cuadro_lista($justifica,'justifica',$configuracion,-1,3,FALSE,$tab++,'justifica');}
	
		//se crea el objeto xajax para enviar la respuesta
	$respuesta = new xajaxResponse();
	//Se asignan los valores al objeto y se envia la respuesta	
	$respuesta->addAssign("divjust","innerHTML",$just_est);
	return $respuesta;	
	
}


function propeCra($carrera, $propedeutico)
{	
	//rescata el valor de la configuracion
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
	//$conexion_db=$conexion->conectarDB($configuracion,"");
	

	$html=new html();

	$busqueda="SELECT cra_cod, cra_nombre FROM mntac.accra WHERE cra_dep_cod=(SELECT cra_dep_cod FROM mntac.accra WHERE cra_cod=".$carrera.") ORDER BY cra_nombre";
	//echo $busqueda;
	$cra_prope=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
	
	if($propedeutico!="N")
		{$cra_prop=$html->cuadro_lista($cra_prope,'craprop',$configuracion,0,0,FALSE,$tab++,'craprop');}
	else
		{$cra_prop=$html->cuadro_lista($cra_prope,'craprop',$configuracion,-1,3,FALSE,$tab++,'craprop');}	

	//se crea el objeto xajax para enviar la respuesta
	$respuesta = new xajaxResponse();
	//Se asignan los valores al objeto y se envia la respuesta	
	$respuesta->addAssign("divporpCra","innerHTML",$cra_prop);
	return $respuesta;	
	
}


function acreCra($tipo,$carrera)
{	//rescata el valor de la configuracion
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		
		
	$conexion=new funcionGeneral();
	$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
	//$conexion_db=$conexion->conectarDB($configuracion,"");
	$html=new html();
	if($tipo==1)
		{$acred="NACIONAL";}
	else{$acred="INTERNACIONAL";}
	
	
	//echo $busqueda;
	$formulario="admin_acreditacion";
	$verificar="control_vacio(".$formulario.",'acreresol2')";
	$verificar.="&& control_vacio(".$formulario.",'acrefecha2')";
	$verificar.="&& control_vacio(".$formulario.",'acrevida2')";
	
	//se crea el formulario para enviarlo y mostrarlo por xajax
	$cadena_html="
	<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' name='".$formulario."' id='".$formulario."'>
	<table class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0'>
	<tr>
	<td>
	<table class='formulario' align='center'>
		<tr class='texto_subtitulo'>
				<td colspan='3'>
				AGREGAR ACREDITACI&Oacute;N ".$acred."	
				<hr class='hr_subtitulo'>
			</tr>
			<tr>
				<td width='30%'>";
				    $texto_ayuda="<b>Tipo de Acreditaci&oacute;n.</b><br>";
					$texto_ayuda.="Ultimo tipo de acreditaci&oacute;n obtenido por el proyecto.";
					$cadena_html.="<font color='red' >*</font><span  onmouseover=\"return escape('".$texto_ayuda."')\">Tipo Acreditaci&oacute;n:</span>
				</td>
				<td>";
					$busqueda="SELECT acre_cod_tipo, acre_nombre FROM mntac.acretipo WHERE acre_nivel=".$tipo." AND estado='A' ";
					//echo $busqueda;
					$tipoacre2=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
					$tipo_acre=$html->cuadro_lista($tipoacre2,'tipoacre2',$configuracion,4,0,FALSE,$tab++,'tipoacre2');
					$cadena_html.=$tipo_acre."
		   		</td>
			</tr>
			<tr>
				<td width='30%'>";
				$texto_ayuda="<b>Resoluci&oacute;n.</b><br>";
				$texto_ayuda.="Acto Administrativo con el que se concede la acreditaci&oacute;n al proyecto curricular. ";
				$cadena_html.="<font color='red' >*</font><span  onmouseover=\"return escape('".$texto_ayuda."')\">Resoluci&oacute;n:</span>
				</td>
				<td>
					<input type='text' name='acreresol2' size='40' maxlength='50'>
				</td>
			</tr>		
			<tr>
				<td width='30%'>";
					$texto_ayuda="<b>Fecha Acreditaci&oacute;n.</b><br>";
					$texto_ayuda.="Fecha en la que se concedio la acreditaci&onacute;n al proyecto curricular.<br> ";
					$texto_ayuda.="Fecha en formato: dd/mm/aaaa.<br>";
					$cadena_html.="<font color='red' >*</font><span  onmouseover=\"return escape('".$texto_ayuda."')\">Fecha Acreditaci&oacute;n:</span>
				</td>
				<td>
					<input type='text' name='acrefecha2' size='12' maxlength='25' readonly=\"readonly\">
					<a href=\"javascript:muestraCalendario('".$configuracion['host'].$configuracion['site'].$configuracion['javascript']."','".$formulario."','acrefecha2')\">
					<img border='0' src='".$configuracion['host'].$configuracion['site'].$configuracion['grafico']."/cal.png'  width='24' height='24' alt='DD-MM-YYYY'></a>
				</td>
			</tr>
			<tr>
				<td width='30%'>";
					$texto_ayuda="<b>Duraci&oacute;n Acreditaci&oacute;n.</b><br>";
					$texto_ayuda.="N&uacute;mero en años del tiempo que dura la acreditaci&oacute;n del proyecto curricular. ";
					$cadena_html.="<font color='red' >*</font><span  onmouseover=\"return escape('".$texto_ayuda."')\">Duraci&oacute;n (Años):</span>
				</td>
				<td>
					<input type='text' name='acrevida2'size='12' maxlength='2' onKeyPress=\"return solo_numero(event)\">
				</td>
			</tr>
			<tr>";
			if($tipo==1)
				{
				$cadena_html.="<td colspan='2'>
				<input type='hidden' name='Entidad2' id='Entidad2' value=''>
				</td>";
				}
			else
				{
				$cadena_html.="<td width='30%'>";
				$texto_ayuda="<b>Entidad Acreditaci&oacute;n.</b><br>";
				$texto_ayuda.="Entidad Internaciónal que otorgo la acreditaci&oacute;n al proyecto curricular. ";
				$cadena_html.="<font color='red' >*</font><span  onmouseover=\"return escape('".$texto_ayuda."')\">Entidad:</span>
				</td>
				<td>
					<input type='text' name='Entidad2'size='12' maxlength='2' onKeyPress=\"return solo_texto(event)\">
				</td>";
				}	
						
$cadena_html.="</tr>	
			<tr align='center'>
				<td colspan='2' rowspan='1'>
				<input type='hidden' name='id_carrera' value='".$carrera."'>	
				<input type='hidden' name='tipo' id='tipo' value='".$tipo."'>
				<input value='Guardar Acreditación' name='aceptar' type='button' onclick=\"if(".$verificar."){xajax_procesar_formulario(xajax.getFormValues('".$formulario."')),muestra_capa('divBoton',0),muestra_capa('acredext',0)}else{false}\">
				<input value='Cancelar Acreditación' name='cancelar' type='button' onclick=\"cancela_capa('divBoton',0,'acredext',0)\"/>
			</td>
		</tr>
	</table>
	</td>
	</tr>
	</table>
	</form>
	";
	
	//se crea el objeto xajax para enviar la respuesta
	$respuesta = new xajaxResponse();
	//Se asignan los valores al objeto y se envia la respuesta	
	$respuesta->addAssign("divacre","innerHTML",$cadena_html);
	return $respuesta;
}

function procesar_formulario($form_entrada)
{	
	//rescata el valor de la configuracion
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
	include_once("sql.class.php");
	
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";

	$sql=new sql_xajax();
	$conexion=new funcionGeneral();
	
	$conexionOracle=$conexion->conectarDB($configuracion,"coordinador");
	//$conexion_db=$conexion->conectarDB($configuracion,"");
	$html=new html();
	$tab=0;
	$busqueda_sql=$sql->cadena_sql($configuracion,"buscar_max","");
	//echo $busqueda_sql;		
	$cod=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda_sql, "busqueda");
	//echo "<br>".$cod[0][0]; 
	
	//sentencia que agrega la nueva variable	
	
	$acredita[0]=($cod[0][0]+1);
	$acredita[1]=$form_entrada["id_carrera"];
	$acredita[2]=$form_entrada["tipoacre2"];
	$acredita[3]="To_date('".$form_entrada["acrefecha2"]."','dd/mm/yyyy')";
	$acredita[4]=htmlentities($form_entrada["acreresol2"], ENT_NOQUOTES, 'UTF-8');
	//$acredita[4]=$form_entrada["acreresol2"];
	$acredita[5]=$form_entrada["acrevida2"];
	$acredita[6]=$form_entrada["acrentidad2"];
		
	$guarda_sql=$sql->cadena_sql($configuracion,"guarda_acre",$acredita);
	//echo $guarda_sql;
	@$newacred=$conexion->ejecutarSQL($configuracion, $conexionOracle, $guarda_sql,"");	

	$actuali_sql=$sql->cadena_sql($configuracion,"actualiza_acred",$acredita);
	//echo $guarda_sql;
	@$actcred=$conexion->ejecutarSQL($configuracion, $conexionOracle,$actuali_sql,"");	
	
	$acred_sql=$sql->cadena_sql($configuracion,"busca_acred",$acredita[0]);
	//echo $acred_sql;
	@$acreditacion=$conexion->ejecutarSQL($configuracion, $conexionOracle,$acred_sql,"busqueda");	

	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();

	$cadena_html="
		<table class='formulario' align='center'>
			<tr class='bloquecentralcuerpobeige'>
				<td colspan='3'>
				<hr class='hr_subtitulo'/>
					<table class='formulario' align='center'>
						<tr>
							<td width='60%'>
							ULTIMA ACREDITACI&Oacute;N NACIONAL ".$acreditacion[0][0]."
							</td>
							<td align='right'>";
							
							 $ruta="pagina=proyectoCurricular&hoja=1&opcion=acreditacion";
							 $ruta.="&carrera=".$acreditacion[0][2];
						     $ruta=$cripto->codificar_url($ruta,$configuracion);
						     $ruta=$indice.$ruta;		
							
							$cadena_html.="<a href=\"javascript:abrir_emergenteUbicada('".$ruta."','Acreditaciones','600','300','100','350')\">
							<b>Ver Acreditaciones</b>
							<img border='0' hspace='2' src='".$configuracion['host'].$configuracion['site'].$configuracion['grafico']."/viewdoc.png' width='24' height='24'>
							</a>
							</td>
							<td align='right'>
							<a href=\"javascript:nueva_acre('divBoton','1','".$acreditacion[0][2]."','acredext')\">
							<b>Ingresar Nueva Acreditaci&oacute;n</b>
							<img border='0' src='".$configuracion['host'].$configuracion['site'].$configuracion['grafico']."/new.png' width='24' height='24'>
							</a>
							</td>				
						</tr>
					</table>		
				<hr class='hr_subtitulo'/>
				</td>
			</tr>
			<tr>
				<td width='30%'>";
				    $texto_ayuda="<b>Tipo de Acreditaci&oacute;n.</b><br>";
					$texto_ayuda.="Ultimo tipo de acreditaci&oacute;n obtenido por el proyecto.";
					$cadena_html.="<span  onmouseover=\"return escape('".$texto_ayuda."')\">Tipo Acreditaci&oacute;n:</span>
				</td>
				<td>";
					$busqueda="SELECT acre_cod_tipo, acre_nombre FROM mntac.acretipo WHERE acre_nivel<=2 AND estado='A' ";
					//echo $busqueda;
					$tipoacre=$conexion->ejecutarSQL($configuracion, $conexionOracle, $busqueda, "busqueda");
					if($acreditacion[0][3]!=0)
						{$tipo_acre=$html->cuadro_lista($tipoacre,'tipoacre',$configuracion,$acreditacion[0][3],0,FALSE,$tab++,'tipoacre');}
					else
						{$tipo_acre=$html->cuadro_lista($tipoacre,'tipoacre',$configuracion,-1,0,FALSE,$tab++,'tipoacre');}	
			$cadena_html.=$tipo_acre."
                 </td>
			</tr>
			<tr>
				<td width='30%'>";
				$texto_ayuda="<b>Resoluci&oacute;n.</b><br>";
				$texto_ayuda.="Acto Administrativo con el que se concede la acreditaci&oacute;n al proyecto curricular. ";
				$cadena_html.="<span  onmouseover=\"return escape('".$texto_ayuda."')\">Resoluci&oacute;n:</span>
				</td>
				<td>
					<input type='text' name='acreresol' size='40' maxlength='50' value='".$acreditacion[0][4]."'>
				</td>
			</tr>		
			<tr>
				<td width='30%'>";
					$texto_ayuda="<b>Fecha Acreditaci&oacute;n.</b><br>";
					$texto_ayuda.="Fecha en la que se concedio la acreditaci&onacute;n al proyecto curricular.<br> ";
					$texto_ayuda.="Fecha en formato: dd/mm/aaaa.<br>";
					$cadena_html.="<span  onmouseover=\"return escape('".$texto_ayuda."')\">Fecha Acreditaci&oacute;n:</span>
				</td>
				<td>
					<input type='text' name='acrefecha' size='12' maxlength='25' readonly=\"readonly\" value='".$acreditacion[0][5]."'>
					<a href=\"javascript:muestraCalendario('".$configuracion['host'].$configuracion['site'].$configuracion['javascript']."','".$formulario."','acrefecha')\">
					<img border='0' src='".$configuracion['host'].$configuracion['site'].$configuracion['grafico']."/cal.png'  width='24' height='24' alt='DD-MM-YYYY'></a>
				</td>
			</tr>
			<tr>
				<td width='30%'>";
					$texto_ayuda="<b>Duraci&oacute;n Acreditaci&oacute;n.</b><br>";
					$texto_ayuda.="N&uacute;mero en años del tiempo que dura la acreditaci&oacute;n del proyecto curricular. ";
					$cadena_html.="<span  onmouseover=\"return escape('".$texto_ayuda."')\">Duraci&oacute;n (Años):</span>
				</td>
				<td>
					<input type='text' name='acrevida'size='12' maxlength='2' onKeyPress=\"return solo_numero(event)\" value='".$acreditacion[0][6]."'>
					<input type='hidden' name='entidad' value=''>
					<input type='hidden' name='acrecra' value='".$acreditacion[0][1]."'>
				</td>
			</tr>		
	</table>

	";
//	cancelaAcre();

	$respuesta = new xajaxResponse();
	$respuesta->addAssign("divacre","innerHTML","");
	$respuesta->addAssign("acrednal","innerHTML",$cadena_html);	
	//tenemos que devolver la instanciación del objeto xajaxResponse
	return $respuesta;
}

function cancelaAcre()
{
	//rescata el valor de la configuracion
	require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable(); 
	//Buscar un registro que coincida con el valor
	
	$respuesta = new xajaxResponse();

	$respuesta->addAssign("divacre","innerHTML","");
	//tenemos que devolver la instanciación del objeto xajaxResponse
	return $respuesta;
}
?>
