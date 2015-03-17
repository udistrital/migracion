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

class funciones_registroActualizaDatos  extends funcionGeneral
{

	function __construct($configuracion, $sql)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		
		$this->cripto=new encriptar();
		$this->sql=$sql;
		$this->accesoOracle=$this->conectarDB($configuracion,"estudiante");
		$this->acceso_db=$this->conectarDB($configuracion,"");
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		$this->indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$this->formulario="admin_actualiza_datos";

	}

	function Caracter($cadena)
	{

		if(is_array($cadena)){
			foreach($cadena[0] as $clave){
			//echo "<br>C|".$clave;
			$clave=str_replace("?","&Ntilde;",$clave);
		//	echo "<br>R|".$clave;
			}
		}

	return $cadena;
	}	
	
	function nuevoRegistro($configuracion)
	{

		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"registroCompleto",$this->usuario);
		$registroEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"Pension",$this->usuario);
		$pension=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"Colegio",$this->usuario);
		$colegio=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

		//echo $cadena_sql;
		
		//$registroEstudiante=$this->ejecutarSQL($configuracion, $this->accesoOracle, "select mun_cod,mun_nombre from gemunicipio","busqueda");
		/*foreach($registroUsuario as $clave=>$valor){
			echo "<br>VAL['".$clave."']=".$valor.".";
		}*/

		$contador=0;	
		$tab=0;
		$size=50;
		
		/***************************************SELECTS******************************************************/

				$select=new html();
				$busqueda="SELECT ";
				$busqueda.="mun_cod,mun_nombre||'   ('||dep_nombre||')' ";
				$busqueda.="FROM ";
				$busqueda.="mntge.gemunicipio,mntge.gedepartamento ";
				$busqueda.="WHERE dep_cod=mun_dep_cod ";				
				$busqueda.="ORDER BY mun_nombre ASC";	

				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");

				$lugarexp=$select->cuadro_lista($resultado,"lugarexp",$configuracion,$registroEstudiante[0][78],0,100);				
				
								

				
				$select=new html();
				$busqueda="SELECT ";
				$busqueda.="discap_code, ";
				$busqueda.="discap_descr ";
				$busqueda.="FROM ";
				$busqueda.="mntge.gediscapacidad ";
				$busqueda.="ORDER BY discap_descr ASC ";				
				
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
				$discapacidad=$select->cuadro_lista($resultado,"discapacidad",$configuracion,$registroEstudiante[0][46],0,100);

				
				$select=new html();
				$busqueda="SELECT ";
				$busqueda.="casi_id, ";
				$busqueda.="casi_descripcion ";
				$busqueda.="FROM ";
				$busqueda.="mntge.gecatsisben ";
				$busqueda.="ORDER BY casi_descripcion ASC ";				
				
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
				$sisben=$select->cuadro_lista($resultado,"sisben",$configuracion,$registroEstudiante[0][48],0,100);

				$select=new html();
				
				$busqueda="SELECT ";
				$busqueda.="CODIGOCOLEGIO,";
				$busqueda.="NOMBREOFICIAL||'-'||JORNADA ";
				$busqueda.="FROM ";
				$busqueda.="mntge.gecolegio ";

				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
				$colegios=$select->cuadro_lista($resultado,"colegio",$configuracion,1,1,100,0,"",400);	


				$select=new html();
				
				$busqueda="SELECT ";
				$busqueda.="TEC_CODIGO,";
				$busqueda.="TEC_NOMBRE ";
				$busqueda.="FROM ";
				$busqueda.="mntge.getipescivil ";
				
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
				$estadocivil=$select->cuadro_lista($resultado,"estadocivil",$configuracion,$registroEstudiante[0][4],0,100);		

				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				$select=new html();
				
				$busqueda="SELECT ";
				$busqueda.="MET_CODE,";
				$busqueda.="MET_DESCR ";
				$busqueda.="FROM ";
				$busqueda.="mntge.gemetbachillerato ";
				
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
				$metbachillerato=$select->cuadro_lista($resultado,"metbachillerato",$configuracion,$registroEstudiante[0][51],0,100);	
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$select=new html();
				
				$busqueda="SELECT ";
				$busqueda.="IDI_CODE,";
				$busqueda.="IDI_DESCR ";
				$busqueda.="FROM ";
				$busqueda.="mntge.geidiomabachillerato ";
				
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
				$idiomabachillerato=$select->cuadro_lista($resultado,"idiomabachillerato",$configuracion,$registroEstudiante[0][52],0,100);	

				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


				$select=new html();
	
				$busqueda="SELECT ";
				$busqueda.="prg_cod,";
				$busqueda.="prg_nombre ";
				$busqueda.="FROM ";
				$busqueda.="mntge.geprog ";
				$busqueda.="ORDER BY prg_nombre ASC ";
				
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
				$cradeseada=$select->cuadro_lista($resultado,"cradeseada",$configuracion,$registroEstudiante[0][50],0,100);		

				$select=new html();	
				$busqueda="SELECT ";
				$busqueda.="DISTINCT(ins_cod),";
				$busqueda.="ins_nombre ";
				$busqueda.="FROM ";
				$busqueda.="mntge.geinst ";
				$busqueda.="ORDER BY ins_nombre ASC ";
				
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
				$insdeseada=$select->cuadro_lista($resultado,"insdeseada",$configuracion,$registroEstudiante[0][75],0,100);	
				
				$busqueda="SELECT ";
				$busqueda.="nies_code,";
				$busqueda.="nies_descripcion ";
				$busqueda.="FROM ";
				$busqueda.="mntge.genivescolar ";
				$busqueda.="ORDER BY nies_code ASC ";
				
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $busqueda, "busqueda");
				
				$select=new html();
				$niveledupadre=$select->cuadro_lista($resultado,"niveledupadre",$configuracion,$registroEstudiante[0][14],0,100);	

				$select=new html();
				$niveledumadre=$select->cuadro_lista($resultado,"niveledumadre",$configuracion,$registroEstudiante[0][17],0,100);	

				$select=new html();
				$niveleduconyugue=$select->cuadro_lista($resultado,"niveleduconyugue",$configuracion,$registroEstudiante[0][20],0,100);

				
				$select=new html();
				$costeaestudio=$select->cuadro_lista($resultado,"costeaestudio",$configuracion,$registroEstudiante[0][26],0,100);	
			
				$select=new html();
				$base=array(array('F','F'),array('M','M'));
				$sexo=$select->cuadro_lista($base,"sexo",$configuracion,$registroEstudiante[0][3],0,100,0,"",50);	
				

				$base=array(array('0','0'),array('1','1'),array('2','2'),array('3','3'),array('4','4'),array('5','5'),array('6','6'),array('7','7'),array('8','8'),array('9','9'),array('10','10'),array('11','11'),array('12','12'),array('13','13'),array('14','14'),array('15','15'),array('16','16'),array('17','17'),array('18','18'),array('19','19'),array('20','20'));

				$select=new html();
				$numhermanos=$select->cuadro_lista($base,"numhermanos",$configuracion,$registroEstudiante[0][59],0,100);

				$select=new html();
				$poshermanos=$select->cuadro_lista($base,"poshermanos",$configuracion,$registroEstudiante[0][60],0,100);	
						
				$select=new html();
				$numedusuphermanos=$select->cuadro_lista($base,"numedusuphermanos",$configuracion,$registroEstudiante[0][61],0,100);
			
				$select=new html();
				$numgrupfam=$select->cuadro_lista($base,"numgrupfam",$configuracion,$registroEstudiante[0][54],0,100);		

				$base=array(array('1','1'),array('2','2'),array('3','3'),array('4','4'),array('5','5'),array('6','6'));
				
				$select=new html();
				$estrato=$select->cuadro_lista($base,"estrato",$configuracion,$registroEstudiante[0][30],0,100);	
		
		/*****************************************************************************************************/
	?>	

		
<?
		/*echo "<pre>";
		var_dump($registroEstudiante);
		echo "</pre>";*/
		$html_1="<form align='right' action='index.php' name='datos_basicos' method='POST' id='datos_basicos'>";
		$html_1.="<table style='width:100%'  align='center'>";
		$html_1.="<tr ><td  colspan='3'><hr class='hr_subtitulo'/>DATOS BASICOS<hr class='hr_subtitulo'/></td></tr>";		
		$html_1.="	<tr >";
		$html_1.="		<td width='30%'>C&oacute;digo:<br><span class='texto_negrita'>".$registroEstudiante[0][0]."</span></td>";
		$html_1.="		<td>Nombre:<br><span class='texto_negrita'>".$registroEstudiante[0][67]."</span></td>";		
		$html_1.="	</tr>";
		$html_1.="	<tr>";
		$html_1.="		<td>Tipo Identificaci&oacute;n:</td>";
		$html_1.="		<td><span class='texto_negrita'>".$registroEstudiante[0][69]."</span></td>";
		$html_1.="	</tr>";
		$html_1.="	<tr >";
		$html_1.="		<td>Proyecto:</td>";
		$html_1.="		<td><span class='texto_negrita'>".$registroEstudiante[0][73]."</span></td>";
		$html_1.="	</tr>";		
		$html_1.="	<tr>";
		$html_1.="		<td>Correo Personal:</td>";
		$html_1.="		<td><input type='text' class='required email' value='".$registroEstudiante[0][35]."' name='email' id='email' size='$size'></td>";
		$html_1.="	</tr>";
		$html_1.="	<tr >";
		$html_1.="		<td>Correo intitucional:</td>";
		$html_1.="		<td><span class='texto_negrita'>".$registroEstudiante[0][38]."</span></td>";
		$html_1.="	</tr>";
		$html_1.="	<tr>";
		$html_1.="		<td>Direcci&oacute;n:</td>"; //direccion $registroEstudiante[0][70]
		$html_1.="		<td>";
		$html_1.="<input type='text'  value='".$registroEstudiante[0][70]."' name='direccion' id='direccion' size='$size'></td>";
		$html_1.="	</tr>";
		$html_1.="	<tr >";
		$html_1.="		<td>Tel&eacute;fono:<br><input type='text'  class='required digits' minlength='7'   value='".$registroEstudiante[0][71]."' name='telefono' size='15px'></td>";
		$html_1.="		<td>Zona Postal:<br><input type='text' maxlength='4' value='".$registroEstudiante[0][72]."' name='zonapostal' size='15px'></td>";
		$html_1.="	</tr>";
		$html_1.="	<tr >";
		$html_1.="		<td width='30%'>Estado Civil:</td>";
		$html_1.="		<td>".$estadocivil."</td>";
		$html_1.="	</tr>";
		$html_1.="	<tr >";
		$html_1.="		<td width='30%'>Tipo de sangre:"; //36
						$html_1.="<select name='tiposangre' id='tiposangre' class='required'>";
						$html_1.="	<option value=''>[Seleccione]</option>";
									$selected=$registroEstudiante[0][36]=='O'?"selected":"";
						$html_1.="	<option value='O' $selected>O</option>";
									$selected=$registroEstudiante[0][36]=='A'?"selected":"";
						$html_1.="	<option value='A' $selected>A</option>";
									$selected=$registroEstudiante[0][36]=='B'?"selected":"";
						$html_1.="	<option value='B' $selected>B</option>";
									$selected=$registroEstudiante[0][36]=='AB'?"selected":"";
						$html_1.="	<option value='AB' $selected>AB</option>";
						$html_1.="</select>";
		$html_1.="		</td>";
		$html_1.="		<td>RH:<br><input type='text' class='required' maxlength='1'  value='".$registroEstudiante[0][37]."' name='rh' size='10'></td>";
		$html_1.="	</tr>";
		$html_1.="	<tr >";
		$html_1.="		<td>Fecha de nacimiento:<br><input type='text' class='required date' value='".$registroEstudiante[0][1]."' name='fechanacimiento' size='15px'></td>";
		$html_1.="		<td>Sexo:<br>".$sexo."</td>";
		$html_1.="	</tr>";
                $html_1.="	<tr >";
		$html_1.="<td><blink>
                    <font color=red size=3><b>Fecha Grado Secundaria: <font color=black size=1><br> (DD/MM/AAAA)</b></font></blink><br><input type='text' class='required date' value='".$registroEstudiante[0][82]."' name='gradoColegio' size='15px'></td>";
		$html_1.="		";
		$html_1.="	</tr>";
	
		//si no existe un municipio de procedencia colocamos el selector de departamentos
		if($registroEstudiante[0][74]<>""){
			$html_1.="	<tr >";
			$html_1.="		<td><div id='divdepprocedencia'></div></td>";
			$html_1.="		<td><div id='divmunprocedencia'>".$this->rescatarMunicipios($configuracion,"",$registroEstudiante[0][74])."</div></td>";
			$html_1.="	</tr>";	
		}else{
			$html_1.="	<tr >";
			$html_1.="		<td><div id='divdepprocedencia'>".$this->rescatarDepartamentos($configuracion)."</div></td>";		
			$html_1.="		<td><div id='divmunprocedencia'></div></td>";
			$html_1.="	</tr>";			
		}
	
		if($registroEstudiante[0][80]<>""){
			$html_1.="	<tr >";
			$html_1.="		<td><div id='divlocviv'></div></td>";
			$html_1.="		<td><div id='divbarviv'>".$this->rescatarBarrios($configuracion,"",$registroEstudiante[0][80])."</div></td>";
			$html_1.="	</tr>";	
		}else{
			$html_1.="	<tr >";
			$html_1.="		<td><div id='divlocviv'>".$this->rescatarLocalidades($configuracion)."</div></td>";		
			$html_1.="		<td><div id='divbarviv'></div></td>";
			$html_1.="	</tr>";			
		}
		
		
		$html_1.="</table>";	
		$html_1.="<br/>";
		
		
		/*$html_1.="<input type='hidden' name='action' value='".$this->formulario."'>";	
		$html_1.="<input type='hidden' name='opcion' value='editar'>";					//"
		$html_1.="<input type='hidden' name='dato' value='datos_basicos'>";	*/
		
		$parametro="action=".$this->formulario;
        $parametro.="&opcion=editar"; 
		$parametro.="&dato=datos_basicos";
        $parametro=$this->cripto->codificar_url($parametro,$configuracion);
		$html_1.="<input type='hidden' name='formulario' value='{$parametro}'>";	
		
		
		$html_1.="<input value='Guardar Datos B&aacute;sicos' name='aceptar' class='SUBMIT ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' type='submit'/><br>";
		$html_1.="</form>";		

//-----------------------------------------------------------------------------------------------------------------------------------
		$html_2="<form  action='index.php' name='info_familiar' method='POST' id='info_familiar'>";
		$html_2.="<table style='width:100%'  align='center'>";
		$html_2.="<tr  ><td ><hr class='hr_subtitulo'/>INFORMACION FAMILIAR<hr class='hr_subtitulo'/></td></tr>";	
		$html_2.="</table>";
		$html_2.="<table  style='width:100%'   align='center'>";			
		$html_2.="	<tr>";
					if($registroEstudiante[0][12]=='S'){ $checkSI='checked';  $checkNO='';} elseif($registroEstudiante[0][12]=='N'){$checkNO='checked';  $checkSI='';}  else{$checkNO='';  $checkSI='';}
		$html_2.="		<td  style='width:33%'>Trabaja?:<br> Si: <input type='radio' class='required' name='trabaja' value='S' ".$checkSI." > No: <input type='radio' name='trabaja' value='N' ".$checkNO." ></td>";

					if($registroEstudiante[0][15]=='S'){ $checkSI='checked';  $checkNO='';} elseif($registroEstudiante[0][15]=='N'){$checkNO='checked';  $checkSI='';}  else{$checkNO='';  $checkSI='';}
		$html_2.="		<td>Trabaja padre:<br> Si: <input type='radio' class='required'  name='trabajapadre' value='S' ".$checkSI." > No: <input type='radio' name='trabajapadre' value='N' ".$checkNO."></td>";
		
					if($registroEstudiante[0][18]=='S'){ $checkSI='checked';  $checkNO='';} elseif($registroEstudiante[0][18]=='N'){$checkNO='checked';  $checkSI='';}  else{$checkNO='';  $checkSI='';}
		$html_2.="		<td>Trabaja madre?:<br> Si: <input type='radio' class='required' name='trabajamadre' value='S' ".$checkSI." > No: <input type='radio' name='trabajamadre' value='N' ".$checkNO." ></td>";
		
		$html_2.="	</tr>";

		$html_2.="	<tr >";
		
					if($registroEstudiante[0][19]=='S'){ $checkSI='checked';  $checkNO='';} elseif($registroEstudiante[0][19]=='N'){$checkNO='checked';  $checkSI='';}  else{$checkNO='';  $checkSI='';}
		$html_2.="		<td>Vive con conyugue?:<br> Si: <input type='radio' name='viveconyugue' value='S' ".$checkSI." > No: <input type='radio' name='viveconyugue' value='N' ".$checkNO." ></td>";
		
					if($registroEstudiante[0][21]=='S'){ $checkSI='checked';  $checkNO='';} elseif($registroEstudiante[0][21]=='N'){$checkNO='checked';  $checkSI='';}  else{$checkNO='';  $checkSI='';}
		$html_2.="		<td>Trabaja conyugue?:<br> Si: <input type='radio' name='trabajaconyugue' value='S' ".$checkSI." > No: <input type='radio' name='trabajaconyugue' value='N' ".$checkNO." ></td>";

		$html_2.="		<td>Con quien vive:<br/>";
		
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarParientes");
							$parientes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
							$i=0;
							$html_2.="<select name='vivecon' id='vivecon' class='required'>";
							$html_2.="<option value=''>[Seleccione]</option>";
							while(isset($parientes[$i][0])){
								if($parientes[$i][0]==$registroEstudiante[0][6]){ $selected="selected"; }else{ $selected=""; }
								$html_2.="<option value=".$parientes[$i][0]." $selected >".$parientes[$i][1]."</option>";
							$i++;
							}
							$html_2.="</select>";
	
		$html_2.="		</td>";
		$html_2.="	</tr>";
		$html_2.="</table><br>";		
		
		$html_2.="<table  style='width:100%'   align='center'>";		
		
		$html_2.="	<tr >";
		$html_2.="		<td>Nivel de educacion del conyugue?:</td>";
		$html_2.="		<td>".$niveleduconyugue."</td>";
		$html_2.="	</tr>";		
		$html_2.="	<tr >";
		$html_2.="		<td>No.personas de grupo Familiar?:</td>";
		$html_2.="		<td>".$numgrupfam."</td>";
		$html_2.="	</tr>";
		$html_2.="	<tr >";
		$html_2.="		<td>Nivel Educativo Padre?:</td>";
		$html_2.="		<td>".$niveledupadre."</td>";
		$html_2.="	</tr>";
		$html_2.="	<tr >";
		$html_2.="		<td>Nivel Educativo Madre?:</td>";
		$html_2.="		<td>".$niveledumadre."</td>";
		$html_2.="	</tr>";
		$html_2.="	<tr >";
		$html_2.="		<td>Ocupaci&oacute;n Padre?:</td>";
		$html_2.="		<td><input type='text' maxlength='200' class='required' value='".$registroEstudiante[0][58]."'  name='ocupadre' size='$size'></td>";
		$html_2.="	</tr>";
		$html_2.="	<tr >";
		$html_2.="		<td>Ocupaci&oacute;n Madre?:</td>";
		$html_2.="		<td><input type='text' maxlength='200' class='required' value='".$registroEstudiante[0][66]."' name='ocumadre' size='$size'></td>";
		$html_2.="	</tr>";
		$html_2.="	<tr >";
		$html_2.="		<td>N&uacute;mero de Hermanos?:</td>";
		$html_2.="		<td>".$numhermanos."</td>";
		$html_2.="	</tr>";
		$html_2.="	<tr >";
		$html_2.="		<td>Posici&oacute;n entre sus hermanos?:</td>";
		$html_2.="		<td>".$poshermanos."</td>";
		$html_2.="	</tr>";
		$html_2.="	<tr >";
		$html_2.="		<td>No. de Hermanos con Educacion superior?:</td>";
		$html_2.="		<td>".$numedusuphermanos."</td>";
		$html_2.="	</tr>";
		$html_2.="</table>";
		$html_2.="<br/>";
		/*$html_2.="<input type='hidden' name='action' value='".$this->formulario."'>";	
		$html_2.="<input type='hidden' name='opcion' value='editar'>";	
		$html_2.="<input type='hidden' name='dato' value='info_familiar'>";	*/

		$parametro="action=".$this->formulario;
        $parametro.="&opcion=editar"; 
		$parametro.="&dato=info_familiar";
        $parametro=$this->cripto->codificar_url($parametro,$configuracion);
		$html_2.="<input type='hidden' name='formulario' value='{$parametro}'>";	
		
		
		$html_2.="<input value='Guardar Informaci&oacute;n Familiar' name='aceptar' class='SUBMIT ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' type='submit'/><br>";
		$html_2.="</form>";	
		

//-----------------------------------------------------------------------------------------------------------------------------------
		$html_3="<form  action='index.php' name='info_academica' method='POST' id='info_academica'>";
		$html_3.="<table   style='width:100%'  align='center'>";
		$html_3.="<tr  ><td  colspan='3'><hr class='hr_subtitulo'/>INFORMACION ACADEMICA<hr class='hr_subtitulo'/></td></tr>";		
		$html_3.="<tr   ><td  colspan='2' align='right'><span class='texto_negrita'>Educaci&oacute;n media</span><hr class='hr_subtitulo'/></td></tr>";	

		if(!is_array($colegio) || $colegio[0][4]==0 ){
			$html_3.="	</table>";
			$html_3.="	<div id='divMunColegio' ></div>";

			$html_3.="	<div id='divColegio' ></div>";
		}
		else{
			$html_3.="	<tr >";
			$html_3.="		<td width='25%'>Colegio:</td>";
			$html_3.="		<td>".$colegio[0][0]."</td>";
			$html_3.="	</tr>";
			$html_3.="	<tr >";
			$html_3.="		<td>Jornada:</td>";
			$html_3.="		<td>".$colegio[0][1]."</td>";
			$html_3.="	</tr>";
			$html_3.="	<tr >";
			$html_3.="		<td>Calendario:</td>";
			$html_3.="		<td>".$colegio[0][2]."</td>";
			$html_3.="	</tr>";
			$html_3.="	<tr >";
			$html_3.="		<td>Caracter:</td>";
			$html_3.="		<td>".$colegio[0][3]."</td>";
			$html_3.="	</tr>";

		}
		
		$html_3.="<table  style='width:100%'  align='center'>";
		$html_3.="	<tr>";
		$html_3.="		<td>Metodolog&iacute;a del Bachillerato:</td>";
		$html_3.="		<td>".$metbachillerato."</td>";
		$html_3.="	</tr>";
		$html_3.="	<tr >";
		$html_3.="		<td>Idioma:</td>";
		$html_3.="		<td>".$idiomabachillerato."</td>";
		$html_3.="	</tr>";
		$html_3.="	<tr >";
					if($registroEstudiante[0][53]=='S'){ $checkSI='checked';  $checkNO='';} elseif($registroEstudiante[0][53]=='N'){$checkNO='checked';  $checkSI='';}  else{$checkNO='';  $checkSI='';}
		$html_3.="		<td>Valido su Bachillerato:</td>";
		$html_3.="		<td>Si: <input type='radio' class='required' name='validobachill' value='S' ".$checkSI." > No: <input type='radio' name='validobachill' value='N' ".$checkNO." ></td>";
		$html_3.="	</tr>";
		$html_3.="	<tr  ><td  colspan='2' align='right'><span class='texto_negrita'>Educaci&oacute;n superior</span><hr class='hr_subtitulo'/></td></tr>";	
		$html_3.="	<tr>";
		$html_3.="		<td>Carrera deseada?:</td>";
		$html_3.="		<td>".$cradeseada."</td>";
		$html_3.="	</tr>";
		$html_3.="	<tr>";
		$html_3.="		<td>Razon Carrera Deseada?:</td>";
		$html_3.="		<td><input maxlength='200' type='text' class='required' name='razoncarrera' value='".$registroEstudiante[0][49]."' size='$size'></td>";
		$html_3.="	</tr>";
		$html_3.="	<tr>";
		$html_3.="		<td>Codigo Institucion Deseada?:</td>";
		$html_3.="		<td>".$insdeseada."</td>";
		$html_3.="	</tr>";
		$html_3.="	<tr>";
		$html_3.="		<td>Raz&oacute;n Institucion Deseada?:</td>";
		$html_3.="		<td><input type='text' class='required'  maxlength='200'  name='razoninstitucion' value='".$registroEstudiante[0][65]."'  size='$size'></td>";
		$html_3.="	</tr>";
		$html_3.="</table>";
		$html_3.="<br/>";
		/*$html_3.="<input type='hidden' name='action' value='".$this->formulario."'>";	
		$html_3.="<input type='hidden' name='opcion' value='editar'>";
		$html_3.="<input type='hidden' name='dato' value='info_academica'>";*/	


		$parametro="action=".$this->formulario;
        $parametro.="&opcion=editar"; 
		$parametro.="&dato=info_academica";
        $parametro=$this->cripto->codificar_url($parametro,$configuracion);
		$html_3.="<input type='hidden' name='formulario' value='{$parametro}'>";
		
		
		$html_3.="<input value='Guardar Informaci&oacute;n Acad&eacute;mica' name='aceptar' class='SUBMIT ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' type='submit' /><br>";
		$html_3.="</form>";	

//-----------------------------------------------------------------------------------------------------------------------------------
		$html_4="<form  action='index.php' name='info_socioeco' method='POST' id='info_socioeco'>";
		$html_4.="<table  style='width:100%' align='center'>";
		$html_4.="<tr  ><td  colspan='3'><hr class='hr_subtitulo'/>INFORMACION SOCIO-ECONOMICA<hr class='hr_subtitulo'/></td></tr>";		
		$html_4.="	<tr >";
		$html_4.="		<td>Quien Costea sus Estudios?:</td>";
		$html_4.="		<td>";
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarParientes");
							$parientes=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
							$i=0;
							$html_4.="<select name='costeaestudio' id='costeaestudio' class='required'>";
							$html_4.="<option value=''>[Seleccione]</option>";
							while(isset($parientes[$i][0])){
								if($parientes[$i][0]==$registroEstudiante[0][26]){ $selected="selected"; }else{ $selected=""; }
								$html_4.="<option value=".$parientes[$i][0]." $selected >".$parientes[$i][1]."</option>";
							$i++;
							}
							$html_4.="</select>";
	
		$html_4.="		</td>";
		$html_4.="	</tr>";
		$html_4.="	<tr >";
		$html_4.="		<td>Ingresos mensuales de quien costea sus estudios?:</td>";
		$html_4.="		<td><input type='text' name='ingcosteaestudios' maxlength='8' class='required digits' id='ingcosteaestudios' value='".$registroEstudiante[0][27]."'  size='$size'></td>";
		$html_4.="	</tr>";
		$html_4.="	<tr>";
		$html_4.="		<td>Categoria Sisben:</td>";
		$html_4.="		<td>".$sisben."</td>";
		$html_4.="	</tr>";
		$html_4.="	<tr>";
		$html_4.="		<td>Estrato Sociecon&oacute;mico?:</td>";
		$html_4.="		<td>".$estrato."</td>";
		$html_4.="	</tr>";
		$html_4.="	<tr>";
		$html_4.="		<td>Valor pension mensual del colegio?:</td>";
		$html_4.="		<td>".$pension[0][10]."</td>";
		$html_4.="	</tr>";
		$html_4.="	<tr >";
		$html_4.="		<td>Valor matricula del colegio?:</td>";
		$html_4.="		<td><input  class='required digits' maxlength='7' type='text' name='matriculacolegio' id='matriculacolegio' value='".$registroEstudiante[0][25]."'   size='$size'></td>";
		$html_4.="	</tr>";
		$html_4.="	<tr>";
		$html_4.="		<td>Numero de aportantes?:</td>";
		$html_4.="		<td><input  class='required digits'  maxlength='2' type='text' name='numeroaportantes' value='".$registroEstudiante[0][55]."'   size='$size'></td>";
		$html_4.="	</tr>";
		$html_4.="	<tr>";
		$html_4.="		<td>Ingresos mensuales familiares?:</td>";
		$html_4.="		<td><input type='text'  class='required digits'  maxlength='22' name='ingresosfamiliares'  id='ingresosfamiliares' value='".$registroEstudiante[0][56]."'   size='$size'></td>";
		$html_4.="	</tr>";
		$html_4.="	<tr >";
		$html_4.="		<td>En que tipo de casa vive?:</td>";
		$html_4.="		<td>";
							$check=$registroEstudiante[0][7]==1?'checked':'';
		$html_4.="			Propia: <input type='radio' class='required' name='viviendapropia' value='1' ".$check." >";
							$check=$registroEstudiante[0][7]==2?'checked':'';
		$html_4.="			Familiar: <input type='radio' name='viviendapropia' value='2' ".$check." >";
							$check=$registroEstudiante[0][7]==3?'checked':'';
		$html_4.="			Arriendo: <input type='radio' name='viviendapropia' value='3' ".$check." >";
							$check=$registroEstudiante[0][7]==4?'checked':'';
		$html_4.="			Cupo U: <input type='radio' name='viviendapropia' value='4' ".$check." >";
		$html_4.="		</td>";
		$html_4.="	</tr>";
		$html_4.="</table>";
		$html_4.="<br/>";
		/*$html_4.="<input type='hidden' name='action' value='".$this->formulario."'>";	
		$html_4.="<input type='hidden' name='opcion' value='editar'>";	
		$html_4.="<input type='hidden' name='dato' value='info_socioeco'>";	*/	
		
		
		$parametro="action=".$this->formulario;
        $parametro.="&opcion=editar"; 
		$parametro.="&dato=info_socioeco";
        $parametro=$this->cripto->codificar_url($parametro,$configuracion);
		$html_4.="<input type='hidden' name='formulario' value='{$parametro}'>";
		
		
		$html_4.="<input value='Guardar Informaci&oacute;n Socio-Econ&oacute;mica' name='aceptar' class='SUBMIT ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' type='submit'/><br>";
		$html_4.="</form>";	

//-----------------------------------------------------------------------------------------------------------------------------------
		$html_5="<form  action='index.php' name='info_adicional' method='POST' id='info_adicional'>";
		$html_5.="<table  style='width:100%'   align='center'>";
		$html_5.="<tr  ><td  colspan='3'><hr class='hr_subtitulo'/>INFORMACION ADICIONAL<hr class='hr_subtitulo'/></td></tr>";		
		$html_5.="	<tr>";
		$html_5.="		<td>Grupo &eacute;tnico al cual pertenece:</td>";
		$html_5.="		<td>";
		
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarEtnias");
							$etnias=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
							$i=0;
							$html_5.="<select name='etnia' id='etnia' class='required'>";
							$html_5.="<option value=''>[Seleccione]</option>";
							while(isset($etnias[$i][0])){
								if($etnias[$i][0]==$registroEstudiante[0][40]){ $selected="selected"; }else{ $selected=""; }
								$html_5.="<option value=".$etnias[$i][0]." $selected >".$etnias[$i][1]."</option>";
							$i++;
							}
							$html_5.="</select>";
	
		$html_5.="		</td>";
		$html_5.="	</tr>";
		$html_5.="	<tr >";
		$html_5.="		<td>Resguardo indigena al cual pertenece:</td>";
		$html_5.="		<td>";
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarResguardos");
							$resguardos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
							$i=0;
							$html_5.="<select name='resguardo' id='resguardo' class='required'>";
							$html_5.="<option value='999'>NINGUNO</option>";
							while(isset($resguardos[$i][0])){
								if($resguardos[$i][0]==$registroEstudiante[0][41]){ $selected="selected"; }else{ $selected=""; }
								$html_5.="<option value=".$resguardos[$i][0]." $selected >".$resguardos[$i][1]."</option>";
							$i++;
							}
							$html_5.="</select>";
		$html_5.="		</td>";
		$html_5.="	</tr>";
		$html_5.="	<tr>";
						if($registroEstudiante[0][42]=='S'){ $checkSI='checked';  $checkNO='';} elseif($registroEstudiante[0][42]=='N'){$checkNO='checked';  $checkSI='';}  else{$checkNO='';  $checkSI='';}
		$html_5.="		<td>Es usted v&iacute;ctima del conflicto armado?:</td>";
		$html_5.="		<td>Si: <input type='radio' name='victima' value='S' ".$checkSI." > No: <input type='radio' name='victima' value='N' ".$checkNO." ></td>";
		$html_5.="	</tr>";
		$html_5.="</table>";

		$html_5.="<table  style='width:100%'  align='center'>";
		$html_5.="	<tr >";
					if($registroEstudiante[0][34]=='S'){ $checkSI='checked';  $checkNO='';} elseif($registroEstudiante[0][34]=='N'){$checkNO='checked';  $checkSI='';}  else{$checkNO='';  $checkSI='';}
		$html_5.="		<td width='40%'>Proviene de area rural:</td>";
		$html_5.="		<td>Si: <input class='required' type='radio' name='provienearearural' value='S' ".$checkSI." > No: <input type='radio' name='provienearearural' value='N' ".$checkNO." ></td>";
		$html_5.="	</tr>";
		
		
		$html_5.="	<tr>";
		$html_5.="		<td>Capacidades excepcionales:</td>";
		$html_5.="		<td>";
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarCapacidades");
							$capacidades=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
							$i=0;
							$html_5.="<select name='capacidad' id='capacidad' class='required'>";
							$html_5.="<option value=''>[Seleccione]</option>";
							while(isset($capacidades[$i][0])){
								if($capacidades[$i][0]==$registroEstudiante[0][47]){ $selected="selected"; }else{ $selected=""; }
								$html_5.="<option value=".$capacidades[$i][0]." $selected >".$capacidades[$i][1]."</option>";
							$i++;
							}
							$html_5.="</select>";
		$html_5.="		</td>";
		$html_5.="	</tr>";
		$html_5.="	<tr >";
		$html_5.="		<td>Tipo de discapacidad:</td>";
		$html_5.="		<td>".$discapacidad."</td>";
		$html_5.="	</tr>";
				
		$html_5.="	<tr>";
		$html_5.="		<td>Modo de transporte m&aacute;s frecuente:</td>";
		$html_5.="		<td>";
							$html_5.="<select name='modotransporte' id='modotransporte' class='required'>";
							$html_5.="<option value=''>[Seleccione]</option>";
							$selected=($registroEstudiante[0][81]==1)?"selected":"";
							$html_5.="<option value='1' $selected>P&uacute;blico</option>";
							$selected=($registroEstudiante[0][81]==2)?"selected":"";
							$html_5.="<option value='2' $selected>Privado</option>";
							$selected=($registroEstudiante[0][81]==3)?"selected":"";
							$html_5.="<option value='3' $selected>Transmilenio</option>";
							$html_5.="</select>";
		$html_5.="		</td>";
		$html_5.="	</tr>";
		
		$html_5.="</table>";
		$html_5.="<br/>";
		/*$html_5.="<input type='hidden' name='action' value='".$this->formulario."'>";	
		$html_5.="<input type='hidden' name='opcion' value='editar'>";	
		$html_5.="<input type='hidden' name='dato' value='info_adicional'>";*/	
		
		$parametro="action=".$this->formulario;
        $parametro.="&opcion=editar"; 
		$parametro.="&dato=info_adicional";
        $parametro=$this->cripto->codificar_url($parametro,$configuracion);
		$html_5.="<input type='hidden' name='formulario' value='{$parametro}'>";
		
		$html_5.="<input value='Guardar Informaci&oacute;n Adicional' name='aceptar' class='SUBMIT ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' type='submit'/><br>";
		$html_5.="</form>";	
	
//-----------------------------------------------------------------------------------------------------------------------------------		
	
	
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/tabs.class.php");
		$tabs=new tabs($configuracion);	
		$tabs->addJquery=false;
		
		$tabs->tab($html_1,"Datos b&aacute;sicos");
		$tabs->tab($html_2,"Info.familiar");
		$tabs->tab($html_3,"Info.Acad&eacute;mica");
		$tabs->tab($html_4,"Info.Socioecon&oacute;mica");
		$tabs->tab($html_5,"Info.Adicional");			

		if(isset($_REQUEST['mensaje'])){
			echo "<div class='ui-state-error ui-corner-all' style='font-size:11pt; padding:10px'>".$_REQUEST['mensaje']."</div>";
		}

		echo $tabs->armar_tabs($configuracion);
		

	}
	
	
	function confirmarRegistro($configuracion,$accion){
		echo "SU REGISTRO SE HA GUARDADO EXITOSAMENTE";
	}

	function validaRegistro($registro){
		foreach($registro as $valor){
			if($valor==""){
				return false;
			}
		}
		return true;
	}
	
	function guardar($configuracion,$formulario,$valor){
	
		switch($formulario){
			case "datos_basicos":
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actDatosBasicosEST",$valor);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
				
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actDatosBasicosEOT",$valor);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
				
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarDatosBasicos",$this->usuario);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
				$registro=$this->validaRegistro($resultado[0]);
				$variable[0]=$this->usuario;
				$variable[1]="EOT_INF_BASICA";
				$variable[2]=$registro<>true?"N":"S";
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actBandera",$variable);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
				
			break;
			case "info_familiar":
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actInfFamiliar",$valor);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
				
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarInfFamiliar",$this->usuario);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
				$registro=$this->validaRegistro($resultado[0]);
				$variable[0]=$this->usuario;
				$variable[1]="EOT_INF_FAMILIAR";
				$variable[2]=$registro<>true?"N":"S";
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actBandera",$variable);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
				
			break;		
			case "info_academica":
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actInfAcademica",$valor);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
				
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarInfAcademica",$this->usuario);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
				$registro=$this->validaRegistro($resultado[0]);
				$variable[0]=$this->usuario;
				$variable[1]="EOT_INF_ACADEMICA";
				$variable[2]=$registro<>true?"N":"S";
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actBandera",$variable);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
				
				
			break;
			case "info_socioeco":
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actInfSocio",$valor);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
				
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarInfSocioeco",$this->usuario);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
				$registro=$this->validaRegistro($resultado[0]);
				$variable[0]=$this->usuario;
				$variable[1]="EOT_INF_SOCIOECONOMICA";
				$variable[2]=$registro<>true?"N":"S";
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actBandera",$variable);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
				
			break;	
			case "info_adicional":
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actInfAdicional",$valor);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
				
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarDatosBasicos",$this->usuario);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
				$registro=$this->validaRegistro($resultado[0]);
				$variable[0]=$this->usuario;
				$variable[1]="EOT_INF_ADICIONAL";
				$variable[2]=$registro<>true?"N":"S";
				$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"actBandera",$variable);
				$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"");
			break;				
		}

		$this->redireccionarInscripcion($configuracion,"admin_actualiza_datos");
		
	
	}
		

		
	function redireccionarInscripcion($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		unset($_REQUEST['action']);
		$cripto=new encriptar();
		
		switch($opcion)
		{
			case "admin_actualiza_datos":
				$variable="no_pagina=admin_actualiza_datos";
				$variable.="&opcion=nuevo";
				//$variable.="&identificador=".$valor;
			break;	
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$this->indice.$variable."')</script>"; 
		exit();		
		
	}

	function encapsularURL($configuracion, $opcion, $valor="")
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		unset($_REQUEST['action']);
		$cripto=new encriptar();
		
		switch($opcion)
		{
			case "admin_actualiza_datos":
				$variable="no_pagina=admin_actualiza_datos";
				$variable.="&jxajax=rescatarMunicipio";
				$variable.="&valor=rescatarMunicipio";
			break;	
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);
		
		return $this->indice.$variable;
		
	}	

   /*******************************FUNCIONES XAJAX*********************************/
   
	function rescatarMunicipios($configuracion,$departamento="",$municipio=""){
	
		if($municipio<>""){
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarMunicipio",$municipio);
			$municipios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	
			$selected="SELECTED";
		}else{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarMunicipios",$departamento);
			$municipios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
			$selected="";
		}
		
		$i=0;
		 
		$html="Municipio de procedencia:<br/>";
		$html.="<select name='munprocedencia' id='munprocedencia' class='required' >"; 
		$html.="<option value=''>[Seleccione]</option>";
		while(isset($municipios[$i][0])){
			$html.="<option value=".$municipios[$i][0]." $selected >".$municipios[$i][1]."</option>";
		$i++;
		}
		$html.="</select>";
		$html.="<input type='button' value='Modificar Dato' onclick='cargarDatos(\"#divdepprocedencia\",\"rescatarDepartamentos\")' class='ui-button ui-widget ui-state-default' style='padding: 0;'>";
		return $html;
	}

	
	function rescatarDepartamentos($configuracion){
	
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarDepartamentos");
		$departamentos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		$i=0;
		$html="Departamento de procedencia:<br/>";
		$html.="<select name='depprocedencia' id='depprocedencia' onchange='cargarDatos(\"#divmunprocedencia\",\"rescatarMunicipio\",\"#depprocedencia\")' class='required'>";
		$html.="<option value='' >[Seleccione]</option>";
		while(isset($departamentos[$i][0])){
			$html.="<option value=".$departamentos[$i][0]." >".$departamentos[$i][1]."</option>";
		$i++;
		}
		$html.="</select>";
		
		return $html;
	}

	
	function rescatarLocalidades($configuracion){
	
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarLocalidades");
		$localidades=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
		$i=0;
		$html="Localidad de residencia:<br/>";
		$html.="<select name='locvive' id='locvive' onchange='cargarDatos(\"#divbarviv\",\"rescatarBarrio\",\"#locvive\")' class='required'>";
		$html.="<option value='' >[Seleccione]</option>";
		while(isset($localidades[$i][0])){
			$html.="<option value=".$localidades[$i][0]." >".$localidades[$i][1]."</option>";
		$i++;
		}
		$html.="</select>";
		
		return $html;
	}
	

	function rescatarBarrios($configuracion,$localidad="",$barrio=""){
	
		if($barrio<>""){
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarBarrio",$barrio);
			$barrios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");	
			$selected="SELECTED";
		}else{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"rescatarBarrios",$localidad);
			$barrios=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
			$selected="";
		}
		
		$i=0;
		 
		$html="Barrio de residencia:<br/>";
		$html.="<select name='barviv' id='barviv' class='required' >"; 
		$html.="<option value=''>[Seleccione]</option>";
		while(isset($barrios[$i][0])){
			$html.="<option value=".$barrios[$i][0]." $selected >".$barrios[$i][1]."</option>";
		$i++;
		}
		$html.="</select>";
		$html.="<input type='button' value='Modificar Dato' onclick='cargarDatos(\"#divlocviv\",\"rescatarLocalidades\")' class='ui-button ui-widget ui-state-default' style='padding: 0;'>";
		return $html;
	}
}
	

?>

