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

class funciones_admin_consultasAsesor extends funcionGeneral
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
		$this->accesoOracle=$this->conectarDB($configuracion,"asesor");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="admin_consultasAsesor";
		//$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'lugar')";
		
	}
	
	//Formulario para generar la consulta de los datos básicos de los estudiantes, ya sea por código, cédula o nombre.
	function consultaDatosBasicosEstudiantes($configuracion, $accesoOracle,$acceso_db)
	{
		?>
		<script>
			function mostrar_div(elemento) {

			    if(elemento.value=="cod") {
				document.getElementById("campo_docIdentidad").style.display = "none";
				document.getElementById("campo_codigo").style.display = "block";
				document.getElementById("campo_nombre").style.display = "none";
				document.forms[0].palabraEA.value='';
			    }else if(elemento.value=="doc") {
				document.getElementById("campo_codigo").style.display = "none";
				document.getElementById("campo_docIdentidad").style.display = "block";
				document.getElementById("campo_nombre").style.display = "none";
				document.forms[0].codigoEA.value='';
			    }else if(elemento.value=="nom") {
				document.getElementById("campo_codigo").style.display = "none";
				document.getElementById("campo_docIdentidad").style.display = "none";
				document.getElementById("campo_nombre").style.display = "block";
				document.forms[0].codigoEA.value='';
			    }else {
				document.getElementById("campo_codigo").style.display = "block";
			    }

			}
		    </script>
		<?
		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
			<div id="campo_documento">
				<table class="sigma_borde centrar" width="100%">
					<caption class="sigma centrar">
						SELECCIONE LA OPCI&Oacute;N PARA BUSCAR LOS DATOS B&Aacute;SICOS DE LOS ESTUDIANTES
					</caption>
					<tr>
						<td class="cuadro_brown" colspan="3">
							<br>
							<ul>
								<li> Para consultar los datos b&aacute;sicos de un estudiante, elija c&oacute;mo lo va a buscar:</li>
									<ul>
										<li>
											 Digitando el c&oacute;digo del estudiante.
										</li>
										<li>
											 Digitando el n&uacute;mero de documento de identificacion del estudiante.
										</li>
										<li>
											 Digitando los nombres y/o apellidos del estudiante.
										</li>
									</ul>
								<li>Una vez haya elegido la opci&oacute;n, haga click en el bot&oacute;n "Buscar".</li>
							</ul>
						</td>
						
					</tr>
					<tr class="sigma">
						<td class="sigma derecha" width="20%">
							C&oacute;digo del estudiante<br>
							Documento de identidad<br>
							Apellido y/o  Nombre
						</td>
						<td class="sigma centrar" width="2%">
							<input type="radio" name="codigorad" value="cod" checked onclick="javascript:mostrar_div(this)"><br>
							<input type="radio" name="codigorad" value="doc" onclick="javascript:mostrar_div(this)">
							<input type="radio" name="codigorad" value="nom" onclick="javascript:mostrar_div(this)">
						</td>
						<td  class="sigma centrar">
							<div align="center" id="campo_codigo">
								<table class="sigma centrar" width="80%" border="0">
									<tr>
										<td class="sigma centrar" colspan="2">
											<font size="1">Digite el c&oacute;digo del estudiante</font><br>
											<input type="text" name="codigo" value="" size="15" maxlength="15">
										</td>
										<td class="sigma centrar" rowspan="2">
											<input type="hidden" name="opcion" value="buscador">
											<input type="hidden" name="action" value="<? echo $this->formulario ?>">
											<small><input class="boton" type="submit" value=" Buscar "></small>
										</td>
									</tr>
								</table>
							</div>
							<div align="center" id="campo_docIdentidad" style="display:none">
								<table class="sigma centrar"  width="80%" border="0" >
									<tr>
										<td class="sigma centrar" colspan="3">
											<font size="1">Digite n&uacute;mero de documento de identidad del estudiante</font><br>
											<input type="text" name="documento" value="" size="17" maxlength="17">
										</td>
										<td class="sigma centrar" rowspan="2">
											<input type="hidden" name="opcion" value="buscador">
											<input type="hidden" name="action" value="<? echo $this->formulario ?>">
											<small><input class="boton" type="submit" value=" Buscar "></small>
										</td>
									</tr>
								</table>
							</div>
							<div align="center" id="campo_nombre" style="display:none">
								<table class="sigma centrar"  width="80%" border="0" >
									<tr>
										<td class="sigma centrar" colspan="3">
											<font size="1">Digite el apellido y/o nombre del estudiante:</font><br>
											<input type="text" name="nombre" value="" size="30" maxlength="30">
										</td>
										<td class="sigma centrar" rowspan="2">
											<input type="hidden" name="opcion" value="buscador">
											<input type="hidden" name="action" value="<? echo $this->formulario ?>">
											<small><input class="boton" type="submit" value=" Buscar "></small>
										</td>
									</tr>
								</table>
							</div>
						</td>

					</tr>
				</table>   
			</div>
		</form>
		<?
	}

	//Rescata los datos básicos del estudiante
	function rescatarDatos($configuracion, $accesoOracle,$acceso_db)
	{
		unset($valor);
		$valor[0]=$_REQUEST['codigo'];
		$valor[1]=$_REQUEST['documento'];
		if($_REQUEST['nombre']=="")
		{      
			$valor[2]=$_REQUEST['nombre'];
		}
		else
		{
			$cadena="%".$_REQUEST['nombre']."%";
			$valor[2] = str_replace(" ","%",$cadena);
			//echo $valor[2]."<br>";
		} 

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosEstudiantes",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		  
		if(is_array($resultado))
		{	
			?>
			<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
				<tr>
					<td>
						<table class="formulario" align="center">
							<caption class="sigma centrar">
								DATOS B&Aacute;SICOS DE(L) (LOS) ESTUDIANTE(S):
							</caption>
							<tr class="cuadro_color">
								<td class="cuadro_plano centrar">
									Nombre y Apellido
								</td>
								<td class="cuadro_plano centrar">
									Identificaci&oacute;n
								</td>
								<td class="cuadro_plano centrar">
									Facultad
								</td>
								<td class="cuadro_plano centrar">
									Carrera
								</td>
								<td class="cuadro_plano centrar">
									C&oacute;digo
								</td>
								<td class="cuadro_plano centrar">
									Estado
								</td>
								<td class="cuadro_plano centrar">
									Tel&eacute;fono 1
								</td>
								<td class="cuadro_plano centrar">
									Tel&eacute;fono 2
								</td>
								<td class="cuadro_plano centrar">
									Correo
								</td>
								<td class="cuadro_plano centrar">
									Correo institucional
								</td>  
							</tr>
						<?
						for($i=0; $i<=count($resultado); $i++)
						{
							?>
							<tr>
								<td align="center">
									<? echo $resultado[$i][0]." ".$resultado[$i][1];?>
								</td>
								<td align="center">
									<? echo $resultado[$i][2];?>
								</td>
								<td align="center">
									<? echo $resultado[$i][3];?>
								</td>
								<td align="center">
									<? echo $resultado[$i][4];?>
								</td>
								<td align="center">
									<? echo $resultado[$i][5];?>
								</td>
								<td align="center">
									<? echo $resultado[$i][6];?>
								</td>
								<td align="center">
									<? echo $resultado[$i][7];?>
								</td>
								<td align="center">
									<? echo $resultado[$i][8];?>
								</td>
								<td align="center">
									<? echo $resultado[$i][9];?>
								</td>
								<td align="center">
									<? echo $resultado[$i][10];?>
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
		else
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena="No hay registros para esta consulta.<br>";
			
			$cadena.="<br><a href='javascript:window.history.back()'>.::Regresar::.</a>";		
			alerta::sin_registro($configuracion,$cadena);
		}
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
			case "formgrado":
				$variable="pagina=adminGestionHorarios";
				$variable.="&proyecto=".$valor[0];
				break;
			case "mostrardatos":
				$variable="pagina=adminConsultasAsesor";
				$variable.="&opcion=mostrarDatos";
				$variable.="&codigo=".$valor[0];
				$variable.="&documento=".$valor[1];
				$variable.="&nombre=".$valor[2];
				break;
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

