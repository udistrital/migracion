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

class funciones_adminInscripcionGrado extends funcionGeneral
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
		$this->accesoOracle=$this->conectarDB($configuracion,"secretarioacad");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="admin_inscripcionGrado";
		$this->verificar="control_vacio(".$this->formulario.",'descripcion')";
		$this->verificar.="&& control_vacio(".$this->formulario.",'rel_des')";
	}
			
	////Total inscritos a grados.
	function mostrarRegistro($configuracion,$registro, $total, $opcion="",$valor)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
		$registroUsuario=$this->verificarUsuario();
			
		$contador=0;	
		$tab=0;
		
		//unset($valor);
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
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$valor[0]=$usuario;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "verificaEstudiantesSecretario",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		if(!is_array($resultado))
		{	
			
			$cadena="En la actualidad no existen inscritos para esta ceremonia de grado.";
			$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
			alerta::sin_registro($configuracion,$cadena);	
		}
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
			$total=count($resultado);			
			setlocale(LC_MONETARY, 'en_US');
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$cripto=new encriptar();
			echo "<table><tr><td>
			<a href='";
			$variable="pagina=adminReporteGrados";
			$variable.="&opcion=imprimir";
			$variable.="&no_pagina=true";
			$variable.="&usuario=".$valor[0]."";
			$variable=$cripto->codificar_url($variable,$configuracion);
			echo $indice.$variable."'";
			echo "target='_blank'";
			echo "title='Consultar formulario de inscripci&oacute;n'>Generar Excel";
			echo "</a></td></tr>";
			echo "</table>";
			echo "<table class='formulario' align='center'>
					<tr  class='bloquecentralencabezado'>
							<td colspan='16'>
								
							</td>
						</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='16' align='center'>
								<p><span class='texto_negrita'>Listado de inscritos </span></p>
							</td>
						</tr>
					<tr class='cuadro_color'>
						<td class='cuadro_plano centrar''>
							Id
						</td>
						<td class='cuadro_plano centrar''>
							C&oacute;digo
						</td>
						<td class='cuadro_plano centrar''>
							Nombre
						</td>
						</td>
						<td class='cuadro_plano centrar''>
							Apellido
						</td>
						<td class='cuadro_plano centrar''>
							Identificaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Tipo de identificaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Lugar de expedici&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Proyecto
						</td>
						<td class='cuadro_plano centrar''>
							Trabajo de Grado
						</td>
						</td>
						<td class='cuadro_plano centrar''>
							Director
						</td>
						<td class='cuadro_plano centrar''>
							No. de acta de sustentaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Direcci&oacute;n
						</td>
						</td>
						<td class='cuadro_plano centrar''>
							Tel&eacute;fono
						</td>
						<td class='cuadro_plano centrar''>
							Celular
						</td>
						<td class='cuadro_plano centrar''>
							Correo
						</td>
						<td class='cuadro_plano centrar''>
							Sexo
						</td>
					</tr>";
					
					for($i=0;$i<$total;$i++)
					{	
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
						echo "<td class='cuadro_plano centrar'>
						<a href='";
						$variable="pagina=adminInscritoGrado";
						$variable.="&opcion=formularioInscripcion";
						//$variable.="&no_pagina=true";
						$variable.="&codigo=".$resultado[$i][1]."";
						$variable=$cripto->codificar_url($variable,$configuracion);
						echo $indice.$variable."'";
						echo "target='_blank'";
						echo "title='Consultar formulario de inscripci&oacute;n'>".$resultado[$i][1].
						"</a>
						</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][2]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][4]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][5]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][6]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][7]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][8]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][9]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][10]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][11]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][12]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][13]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][14]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][15]."</td>";
						echo "</td>";
					
					echo "</tr>";	
						
					}
			echo "</table>";
		}		
	}
	//Consulta el listado de carreras por secretario academico.
	function listadoProyecto ($configuracion, $accesoOracle,$acceso_db)
	{		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
		$registroUsuario=$this->verificarUsuario();
			
		$contador=0;	
		$tab=0;
		
		//unset($valor);
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
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$valor[0]=$usuario;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaProyectos",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		if(!is_array($resultado))
		{	
			
			$cadena="En la actualidad no existen inscritos para esta ceremonia de grado.";
			$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
			alerta::sin_registro($configuracion,$cadena);	
		}
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
			$total=count($resultado);			
			setlocale(LC_MONETARY, 'en_US');
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$cripto=new encriptar();
			
			
			
			echo "<table class='formulario' align='center'>
					<tr  class='bloquecentralencabezado'>
							<td colspan='3'>
								
							</td>
						</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='3' align='center'>
								<p><span class='texto_negrita'>Listado de inscritos por Proyecto </span></p>
							</td>
						</tr>
					<tr class='cuadro_color'>
						<td class='cuadro_plano centrar''>
							C&oacute;digo Cra.
						</td>
						<td class='cuadro_plano centrar''>
							Carrera
						</td>
						</td>
						<td class='cuadro_plano centrar''>
							No. Inscritos
						</td>
					</tr>";
					
					for($i=0;$i<$total;$i++)
					{	
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
						echo "<td class='cuadro_plano centrar'>
						<a href='";
						$variable="pagina=adminInscritoGrado";
						$variable.="&opcion=listadoTotalCarrera";
						//$variable.="&no_pagina=true";
						$variable.="&carrera=".$resultado[$i][0]."";
						$variable=$cripto->codificar_url($variable,$configuracion);
						echo $indice.$variable."'";
						//echo "target='_blank'";
						echo "title='Consultar estudiantes inscritos'>".$resultado[$i][1].
						"</a>
						</td>";
						$valor[1]=$resultado[$i][0];
						$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "contarRegistros",$valor);
						$resultadoConReg=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
						$total1=count($resultadoConReg);
						for($j=0;$j<$total1;$j++)
						{
							echo "<td class='cuadro_plano centrar'>".$resultadoConReg[$j][0]."</td>";
						}
						echo "</tr>";	
						
					}
			echo "</table>";
		}
	}
	
	//Consulta las inscripciones para grados por carrera.
	function listadoCarrera($configuracion,$registro, $total, $opcion="",$valor)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
		$usuario=$_REQUEST['carrera'];
		
		$valor[0]=$usuario;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "verificaEstudiantesCarrera",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		if(!is_array($resultado))
		{	
			
			$cadena="En la actualidad no existen inscritos para esta ceremonia de grado.";
			$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
			alerta::sin_registro($configuracion,$cadena);	
		}
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
			$total=count($resultado);			
			setlocale(LC_MONETARY, 'en_US');
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$cripto=new encriptar();
			
			echo "<table class='formulario' align='center'>
					<tr  class='bloquecentralencabezado'>
							<td colspan='16'>
								
							</td>
						</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='16' align='center'>
								<p><span class='texto_negrita'>Listado de inscritos </span></p>
							</td>
						</tr>
					<tr class='cuadro_color'>
						<td class='cuadro_plano centrar''>
							Id
						</td>
						<td class='cuadro_plano centrar''>
							C&oacute;digo
						</td>
						<td class='cuadro_plano centrar''>
							Nombre
						</td>
						<td class='cuadro_plano centrar''>
							Apellido
						</td>
						<td class='cuadro_plano centrar''>
							Identificaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Tipo de identificaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Lugar de expedici&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Proyecto
						</td>
						<td class='cuadro_plano centrar''>
							Trabajo de Grado
						</td>
						
						<td class='cuadro_plano centrar''>
							Director
						</td>
						<td class='cuadro_plano centrar''>
							No. de acta de sustentaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Direcci&oacute;n
						</td>
						</td>
						<td class='cuadro_plano centrar''>
							Tel&eacute;fono
						</td>
						<td class='cuadro_plano centrar''>
							Celular
						</td>
						<td class='cuadro_plano centrar''>
							Correo
						</td>
						<td class='cuadro_plano centrar''>
							Sexo
						</td>
					</tr>";
					
					for($i=0;$i<$total;$i++)
					{	
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
						echo "<td class='cuadro_plano centrar'>
						<a href='";
						$variable="pagina=adminInscritoGrado";
						$variable.="&opcion=formularioInscripcion";
						//$variable.="&no_pagina=true";
						$variable.="&codigo=".$resultado[$i][1]."";
						$variable=$cripto->codificar_url($variable,$configuracion);
						echo $indice.$variable."'";
						echo "target='_blank'";
						echo "title='Consultar formulario de inscripci&oacute;n'>".$resultado[$i][1].
						"</a>
						</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][2]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][4]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][5]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][6]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][7]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][8]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][9]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][10]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][11]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][12]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][13]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][14]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][15]."</td>";
						echo "</td>";
					
					echo "</tr>";	
						
					}
			echo "</table>";
		}
	}
	
	//Rescata la inscripción para grado realizada por el estudiante.
	function mostrarFormulario($configuracion,$registro, $total, $opcion="",$valor)
	{
		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");	
		?>
		<script language="Javascript">
		function imprSelec(nombre)
		{
		var ficha = document.getElementById(nombre);
		var ventimp = window.open(' ', 'popimpr');
		ventimp.document.write( ficha.innerHTML );
		ventimp.document.close();
		ventimp.print( );
		ventimp.close();
		}
		</script>
		<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<table class="formulario" align="center">
						
					</table>
					<br>
					<DIV ID="seleccion">
						<table class="formulario" align="center">
							<tr  class="bloquecentralencabezado">
								<td colspan="3" align="center">
									<p><span class="texto_negrita">Inscripci&oacute;n para Grado</span></p>
								</td>
							</tr>
							<tr>
								<td colspan="3" rowspan="1"><br>Datos de suscripci&oacute;n<hr class="hr_subtitulo"></td>
							</tr>
							
							<?
							//unset($valor);
							$usuario=$_REQUEST['codigo'];
							$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "muestraInscripcion",$usuario);
							@$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
								
							if(is_array($resultado))
							{
								echo '<tr>
									<td>
										Nombre:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][1].'
									</td>
								</tr>
								<tr>
									<td>
										C&oacute;digo:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][0].'
									</td>
								</tr>
								<tr>
									<td>
										Identificaci&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][2].'
									</td>
								</tr>
								<tr>
									<td>
										Tipo de identificaci&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][3].'
									</td>
								</tr>
								<tr>
									<td>
										Lugar de expedici&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][9].'
									</td>
								</tr>
								<tr>
									<td>
										Carrera:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][5].'
									</td>
								</tr>
								<tr>
									<td colspan="3" rowspan="1"><br>Datos de contacto<hr class="hr_subtitulo"></td>
								</tr>
								<tr>
									<td>
										Direcci&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][6].'
									</td>
								</tr>
								<tr>
									<td>
										Ciudad de residencia:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][10].'
									</td>
								</tr>
								<tr>
									<td>
										Tel&eacute;fono:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][7].'
									</td>
								</tr>
								<tr>
									<td>
										Tel&eacute;fono celular:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][11].'
									</td>
								</tr>
								<tr>
									<td>
										Correo electr&oacute;nico:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][8].'
									</td>
								</tr>
								<tr>
									<td colspan="3" rowspan="1"><br>
										Informacion trabajo de grado / pasant&iacute;a<hr class="hr_subtitulo">
									</td>
								</tr>
								<tr>
									<td>
										Trabajo de grado:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][12].'
									</td>
								</tr>
								<tr>
									<td>
										Director:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][13].'
									</td>
								</tr>
								<tr>
									<td>
										Tipo de trabajo:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][14].'
									</td>
								</tr>
								<tr>
									<td>
										No. de acta de sustentaci&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][15].'
									</td>
								</tr>
								<tr>
									<td>
										Fecha de inscripci&oacute;n:
									</td>
									<td class="izquierda texto_negrita" colspan="2">
										'.$resultado[0][17].'
									</td>
								</tr>';
								
								$valor[0]=$resultado[0][0];
								$codigo=$resultado[0][0];
								$carrera=$resultado[0][4];
							}
							else
							{
								echo "Imposible mostrar los datos de registro";
							
							}
								//echo "Usuario :".$registroUsuario[0][1]
							?>
							<tr align='center'>
								<td colspan="3">
									
								</td>
							</tr>
						</table>
					</DIV>
			</tr>
			<tr>
				</td>
				<td class="tabla_alerta">
				<a href="javascript:imprSelec('seleccion')" >
				<center><img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/impresora.gif" border="0"/><br>
				Imprimir la inscripci&oacute;n a grado.</center><br><br>
				</a>
				</td>
			</tr>
		</table>
		<?
	}
	
	function reporteExcel($valor)
	{
		//include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
		//$registroUsuario=$this->verificarUsuario();
			
		//$contador=0;	
		//$tab=0;
		
		//unset($valor);
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
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$valor[0]=$usuario;
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "verificaEstudiantesSecretario",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		if(!is_array($resultado))
		{	
			
			/*$cadena="En la actualidad no existen inscritos para esta ceremonia de grado.";
			$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
			alerta::sin_registro($configuracion,$cadena);	*/
		}
		else
		{
			/*include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
			$total=count($resultado);			
			setlocale(LC_MONETARY, 'en_US');
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$cripto=new encriptar();*/
			//header('Content-type: application/vnd.ms-excel');
			echo "<script>parent.location.href='Content-type: application/vnd.ms-excel'</script>"; 
			//header("Content-Disposition: attachment; filename=archivo.xls");
			echo "<script>parent.location.href='Content-Disposition: attachment; filename=archivo.xls'</script>"; 
			//header("Pragma: no-cache");
			echo "<script>parent.location.href='Pragma: no-cache'</script>";
			header("Expires: 0");
			//echo "<script>parent.location.href='Expires: 0'</script>";
			echo "<table class='formulario' align='center'>
					<tr  class='bloquecentralencabezado'>
							<td colspan='16'>
								
							</td>
						</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='16' align='center'>
								<p><span class='texto_negrita'>Listado de inscritos </span></p>
							</td>
						</tr>
					<tr class='cuadro_color'>
						<td class='cuadro_plano centrar''>
							Id
						</td>
						<td class='cuadro_plano centrar''>
							C&oacute;digo
						</td>
						<td class='cuadro_plano centrar''>
							Nombre
						</td>
						</td>
						<td class='cuadro_plano centrar''>
							Apellido
						</td>
						<td class='cuadro_plano centrar''>
							Identificaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Tipo de identificaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Lugar de expedici&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Proyecto
						</td>
						<td class='cuadro_plano centrar''>
							Trabajo de Grado
						</td>
						</td>
						<td class='cuadro_plano centrar''>
							Director
						</td>
						<td class='cuadro_plano centrar''>
							No. de acta de sustentaci&oacute;n
						</td>
						<td class='cuadro_plano centrar''>
							Direcci&oacute;n
						</td>
						</td>
						<td class='cuadro_plano centrar''>
							Tel&eacute;fono
						</td>
						<td class='cuadro_plano centrar''>
							Celular
						</td>
						<td class='cuadro_plano centrar''>
							Correo
						</td>
						<td class='cuadro_plano centrar''>
							Sexo
						</td>
					</tr>";
					
					for($i=0;$i<$total;$i++)
					{	
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
						echo "<td class='cuadro_plano centrar'>";
						//$variable="pagina=adminInscritoGrado";
						//$variable.="&opcion=formularioInscripcion";
						//$variable.="&no_pagina=true";
						//$variable.="&codigo=".$resultado[$i][1]."";
						//$variable=$cripto->codificar_url($variable,$configuracion);
						//echo $indice.$variable."'";
						//echo "target='_blank'";
						echo  $resultado[$i][1];
						echo "</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][2]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][4]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][5]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][6]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][7]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][8]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][9]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][10]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][11]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][12]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][13]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][14]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][15]."</td>";
						echo "</td>";
					
					echo "</tr>";	
						
					}
			echo "</table>";
		}		
	}

	//Formulario para consultar el reporte de promedi de egresados por fecha.
	function consultaPromedioEgresados($configuracion, $accesoOracle,$acceso_db)
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
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$valor[0]=$usuario;
		?>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
			<tr>
				<td>	
					<div style="position: absolute; display: none; left: 158px; top: 32px;" class="calendar"><table style="visibility: visible;" cellpadding="0" cellspacing="0"><thead><tr><td class="button" colspan="1"><div unselectable="on">?</div></td><td style="cursor: move;" class="title" colspan="6">Septiembre, 2009</td><td class="button" colspan="1"><div unselectable="on">×</div></td></tr><tr class="headrow"><td class="button nav" colspan="1"><div unselectable="on">«</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8249;</div></td><td class="button" colspan="4"><div unselectable="on">Hoy</div></td><td class="button nav" colspan="1"><div unselectable="on">&#8250;</div></td><td class="button nav" colspan="1"><div unselectable="on">»</div></td></tr><tr class="daynames"><td class="name wn">sem</td><td class="day name">Lun</td><td class="day name">Mar</td><td class="day name">Mie</td><td class="day name">Jue</td><td class="day name">Vie</td><td class="name day weekend">S&aacute;b</td><td class="name day weekend">Dom</td></tr></thead><tbody><tr class="daysrow"><td class="day wn">36</td><td class="emptycell">&nbsp;</td><td class="day">1</td><td class="day">2</td><td class="day">3</td><td class="day">4</td><td class="day weekend">5</td><td class="day weekend">6</td></tr><tr class="daysrow"><td class="day wn">37</td><td class="day">7</td><td class="day">8</td><td class="day">9</td><td class="day">10</td><td class="day">11</td><td class="day weekend">12</td><td class="day weekend">13</td></tr><tr class="daysrow"><td class="day wn">38</td><td class="day">14</td><td class="day">15</td><td class="day">16</td><td class="day">17</td><td class="day">18</td><td class="day weekend">19</td><td class="day weekend">20</td></tr><tr class="daysrow"><td class="day wn">39</td><td class="day">21</td><td class="day">22</td><td class="day">23</td><td class="day">24</td><td class="day">25</td><td class="day selected today weekend">26</td><td class="day weekend">27</td></tr><tr class="daysrow"><td class="day wn">40</td><td class="day">28</td><td class="day">29</td><td class="day">30</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr><tr class="emptyrow"><td class="day wn">41</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td><td class="emptycell">&nbsp;</td></tr></tbody><tfoot><tr class="footrow"><td style="cursor: move;" class="ttip" colspan="8">Seleccionar fecha</td></tr></tfoot></table><div style="display: none;" class="combo"><div class="label">Ene</div><div class="label">Feb</div><div class="label">Mar</div><div class="label">Abr</div><div class="label">May</div><div class="label">Jun</div><div class="label">Jul</div><div class="label">Ago</div><div class="label">Sep</div><div class="label">Oct</div><div class="label">Nov</div><div class="label">Dic</div></div><div style="display: none;" class="combo"><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div><div class="label"></div></div></div>
					<table class="formulario" align="center">
						<tr>
							<td class="cuadro_brown">
								<ul>
									<li>Para consultar un reporte, seleccione el Proyecto Curricular, haga click en el campo de "Fecha de Grado", una vez se despliegue el calendario, seleccione la fecha, haga click en "Consultar".</li>
								</ul>
							</td>
						</tr>
						<tr class="texto_subtitulo">
							<td class="" align="center">
								<p><span class="texto_negrita">Consulta reporte promedio egresados por fecha grado.</span></p>
							</td>
						</tr>
					</table>
					<table class="contenidotabla centrar">
						<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario?>'>
						<tr>
							<td align="center">
								<fieldset>
									<legend>
										Consultar reporte egresados por fecha de grado
									</legend>
									<table class="formulario">
										<tr>
											<td>
												<font color="red">*</font>Proyecto Curricular
											</td>
											<td>
												<?
												include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
												$html=new html();
																		
												$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "consultaTotalProyectos",$valor);
												$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
												if(!is_array($resultado))
												{
													echo "No tiene Proyectos Curriculares registrados";
												}
												else
												{
													for ($i=0; $i<count($resultado);$i++)
													{
														$registro[$i][0]=$resultado[$i][0];
														$registro[$i][1]=UTF8_DECODE($resultado[$i][1]);
													}
													$mi_cuadro=$html->cuadro_lista($registro,'proyecto',$configuracion,1,3,FALSE,$tab++,"proyecto",100);
													
													echo $mi_cuadro;
												}
												?>
											</td>
										</tr>
										<tr>
											<td>
												<font color="red">*</font>Fecha de Grado
											</td>
											<td>
												<input type='text' size='8' value='' name='fecha' id='fecha'/>(dd/mm/aaaa)
											</td>
										</tr>
										<script type="text/javascript">
											Calendar.setup({
												inputField:"fecha",
												ifFormat:"%d/%m/%Y",
												button:"fecha"
											})
										</script>
										<tr align='center'>
											<td colspan="16">
												<table class="tablaBase">
													<tr>
														
														<td align="center">
															<input type='hidden' name='usuario' value='<? echo $valor[0] ?>'>
															<input type='hidden' name='action' value='<? echo $this->formulario ?>'>
															<input type='hidden' name='consultar' value='grabar'>
															<input value="Consultar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario?>'].submit()}else{false}"/><br>
														</td>
														<!--td align="center">
															<input type='hidden' name='nivel' value='<? //echo $valor[4]?>'>
															<input type="submit" name="notdef" value="Calcular Acumulado">
														</td-->
														<td align="center">
															<input type='hidden' name='usuario' value='<? echo $valor[0]?>'>
															<input name='cancelar' value='Cancelar' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
														</td>
													</tr>
													<tr class="bloquecentralcuerpo">
														<td colspan="3" rowspan="1">
															Los campos marcados con <font color="red">*</font> deben ser diligenciados obligatoriamente.<br><br>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</fieldset>	
							</td>
						</tr>
						</form>
					</table>
				</td>
			</tr>
		</table>
		<?
	}
	
	//Ejecula la consulta de reporte de promedio de egresados por fecha
	function ejecutarConsultaPromedioEgresados($configuracion, $accesoOracle,$acceso_db)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
		//$registroUsuario=$this->verificarUsuario();
			
		$contador=0;	
		$tab=0;
		
		unset($valor);
		$valor[0]=$_REQUEST['usuario'];
		$valor[1]=$_REQUEST['proyecto'];
		$valor[2]=$_REQUEST['fecha'];  
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "promedioEgresados",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		if(!is_array($resultado))
		{	
			
			$cadena="No existen registros de promedio de EGRESADOS para la fecha seleccionada.";
			$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
			alerta::sin_registro($configuracion,$cadena);	
		}
		else
		{
			$this->redireccionarInscripcion($configuracion,"promedEgresados",$valor);
		}
	}


	function reportePromedioEgresados($configuracion, $accesoOracle,$acceso_db)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
		//$registroUsuario=$this->verificarUsuario();
			
		$contador=0;	
		$tab=0;
		
		//unset($valor);
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
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		unset($valor);
		$valor[0]=$_REQUEST['usuario'];
		$valor[1]=$_REQUEST['proyecto'];
		$valor[2]=$_REQUEST['fecha'];  
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "promedioEgresados",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		if(!is_array($resultado))
		{	
			
			$cadena="No existen registros de promedio de EGRESADOS para la fecha seleccionada.";
			$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
			alerta::sin_registro($configuracion,$cadena);	
		}
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
			$total=count($resultado);			
			setlocale(LC_MONETARY, 'en_US');
			$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
			$cripto=new encriptar();
			echo "<table><tr><td>
			<a href='";
			$variable="pagina=adminReporteGrados";
			$variable.="&opcion=promedioEgresado";
			$variable.="&no_pagina=true";
			$variable.="&usuario=".$valor[0]."";
			$variable.="&proyecto=".$valor[1]."";
			$variable.="&fecha=".$valor[2]."";
			$variable=$cripto->codificar_url($variable,$configuracion);
			echo $indice.$variable."'";
			echo "target='_blank'";
			echo "title='Generar archivo en Excel'>Generar Excel";
			echo "</a></td></tr>";
			echo "</table>";
			echo "<table class='formulario' align='center'>
					<tr  class='bloquecentralencabezado'>
							<td colspan='16'>
								
							</td>
						</tr>
					<tr  class='bloquecentralencabezado'>
							<td colspan='16' align='center'>
								<p><span class='texto_negrita'>Reporte de promedios de egresados </span></p>
							</td>
						</tr>
					<tr class='cuadro_color'>
						<td class='cuadro_plano centrar''>
							C&oacute;d. Carrera
						</td>
						<td class='cuadro_plano centrar''>
							Nombre Carrera
						</td>
						<td class='cuadro_plano centrar''>
							Fecha de Grado
						</td>
						</td>
						<td class='cuadro_plano centrar''>
							C&oacute;digo Estudiante
						</td>
						<td class='cuadro_plano centrar''>
							Nombre Estudiante
						</td>
						<td class='cuadro_plano centrar''>
							Promedio
						</td>
					</tr>";
					
					for($i=0;$i<$total;$i++)
					{	
						echo "<tr>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][0]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][1]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][2]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][3]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][4]."</td>";
						echo "<td class='cuadro_plano centrar'>".$resultado[$i][5]."</td>";
						echo "</td>";
					
					echo "</tr>";	
						
					}
			echo "</table>";
		}		
	}
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
	function datosUsuario()
	{
		$registro=$this->verificarUsuario();
		if(is_array($registro))
		{
			?><table class="formulario" align="center">
						<tr  class="bloquecentralencabezado">
							<td colspan="2">
								<p><span class="texto_negrita">Datos Registrados del Usuario</span></p>
							</td>
						</tr>
						<tr >
							<td>
								Nombre:
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][1] ?>
							</td>
						</tr>
						<tr >
							<td>
								identificaci&oacute;n:
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][0] ?>
							</td>
						</tr>
			</table>
			<?
		}
		else
		{
			return false;
		
		}
	
	}	
	
	function verificarUsuario()
	{
		$configuracion = (isset($configuracion)?$configuracion:'');
		//Verificar existencia del usuario 	
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle, "datosUsuarios",$this->identificacion);
		$unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if(is_array($unUsuario))
		{
			return $unUsuario;			
		}
		else
		{
			$cadena_sql=$this->sql->cadena_sql($configuracion,$this->acceso_db, "datosUsuarios",$this->usuario);
			$unUsuario=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");	
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
			
			case "principal":
				$variable="pagina=index";
				break;
				break;
			case "promedEgresados":
				$variable="pagina=adminInscritoGrado";
				$variable.="&opcion=reportePromEgresados";
				$variable.="&usuario=".$valor[0];
				$variable.="&proyecto=".$valor[1];
				$variable.="&fecha=".$valor[2];
				break;
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

