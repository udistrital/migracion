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

class funciones_admin_consultasAdmisiones extends funcionGeneral
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
		$this->accesoOracle=$this->conectarDB($configuracion,"admisiones");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
					
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this->formulario="admin_consultasAdmisiones";
		//$this->verificar="control_vacio(".$this->formulario.",'codigo')";
		//$this->verificar.="&& control_vacio(".$this->formulario.",'lugar')";
		
	}
	
	//Formulario para generar la consulta de los datos básicos de los estudiantes, ya sea por código, cédula o nombre.
	function consultaDatosAspirantes($configuracion, $accesoOracle,$acceso_db)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		?>
		<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
			<div id="campo_documento">
				<table class="sigma_borde centrar" width="100%">
					<caption class="sigma centrar">
						DATOS DE ASPIRANTES INSCRITOS
					</caption>
					<tr>
						<table class="sigma centrar" width="100%" border="0">
							<tr>
								<td class="sigma centrar" colspan="2">
									<font size="1">Digite el n&uacute;mero de la Credencial</font><br>
								</td>
							</tr>
							<tr>
								<td class="sigma centrar" colspan="2">
									<input type="text" name="credencial" value="" size="15" maxlength="15">
								</td>
							</tr>
							<tr>
								<td class="sigma centrar" rowspan="2">
									<input type="hidden" name="opcion" value="buscador">
									<input type="hidden" name="action" value="<? echo $this->formulario ?>">
									<small><input class="boton" type="submit" value=" Buscar "></small>
								</td>
							</tr>
						</table>

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
		$valor[0]=$_REQUEST['credencial'];
		
		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"datosAspirantes",$valor);
		$resultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		
		if(is_array($resultado))
		{	
			?>
			<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
				<tr>
					<td>
						<table class="formulario" align="center">
							<tr>
								<td class="cuadro_brown" colspan="2">
									<center><a href='javascript:window.history.back()'>.::Realizar otra consulta::.</a></center>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									A&ntilde;o
								</td>
								<td align="center">
									<? echo $resultado[0][0];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Perido acad&eacute;mico
								</td>
								<td align="center">
									<? echo $resultado[0][1];?>
								</td>
							</tr>  
							<tr>
								<td class="cuadro_plano centrar">
									Credencial
								</td>
								<td align="center">
									<? echo $resultado[0][2];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Nombres y Apellidos
								</td>
								<td align="center">
									<? echo $resultado[0][3];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Email
								</td>
								<td align="center">
									<? echo $resultado[0][4];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Tel&eacute;fono
								</td>
								<td align="center">
									<? echo $resultado[0][5];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Tipo de inscripci&oacute;n
								</td>
								<td align="center">
									<? echo $resultado[0][6];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									SNP
								</td>
								<td align="center">
									<? echo $resultado[0][7];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Localidad
								</td>
								<td align="center">
									<? echo $resultado[0][8];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Estrato
								</td>
								<td align="center">
									<? echo $resultado[0][9];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Biolog&iacute;a
								</td>
								<td align="center">
									<? echo $resultado[0][10];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Qu&iacute;mica
								</td>
								<td align="center">
									<? echo $resultado[0][11];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									F&iacute;sica
								</td>
								<td align="center">
									<? echo $resultado[0][12];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Sociales
								</td>
								<td align="center">
									<? echo $resultado[0][13];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Aptitud Verbal
								</td>
								<td align="center">
									<? echo $resultado[0][14];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Espa&ntilde;ol y Literatura
								</td>
								<td align="center">
									<? echo $resultado[0][15];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Aptitud Matem&aacute;tica
								</td>
								<td align="center">
									<? echo $resultado[0][16];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Conocimiento Matem&aacute;tico
								</td>
								<td align="center">
									<? echo $resultado[0][17];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Filosof&iacute;a
								</td>
								<td align="center">
									<? echo $resultado[0][18];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Historia
								</td>
								<td align="center">
									<? echo $resultado[0][19];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Geograf&iacute;a
								</td>
								<td align="center">
									<? echo $resultado[0][20];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Idioma
								</td>
								<td align="center">
									<? echo $resultado[0][21];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Interdisciplinario
								</td>
								<td align="center">
									<? echo $resultado[0][22];?>
								</td>
							</tr>
							<tr>
								<td class="cuadro_plano centrar">
									Ciencias Sociales
								</td>
								<td align="center">
									<? echo $resultado[0][23];?>
								</td>
							</tr>
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
				$variable="pagina=adminConsultasAdmisiones";
				$variable.="&proyecto=".$valor[0];
				break;
			case "mostrardatos":
				$variable="pagina=adminConsultasAdmisiones";
				$variable.="&opcion=mostrarDatos";
				$variable.="&credencial=".$valor[0];
				break;
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

