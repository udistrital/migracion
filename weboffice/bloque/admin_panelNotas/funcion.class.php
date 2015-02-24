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
class funciones_admin_panelNotas extends funcionGeneral
{
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		
		$this->cripto=new encriptar();

	}
	

	//Muestra la lista de los cursos programados con la disponibilidad de cupos.
	function vercontrolNotas($configuracion, $accesoOracle,$acceso_db)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
		if($this->usuario)
		{
			$usuario=$this->usuario;
		}
		else
		{
			$usuario=$_REQUEST['usuario'];
		}
						
		if($usuario=="")
		{
			echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
			EXIT;
		}
		$valor[0]=$usuario;
		$valor[10]=$_REQUEST['nivel'];
		$valor[3]=$_REQUEST['carrera'];
		$valor[4]=$_REQUEST['nomcra'];
		$valor[1]=$_REQUEST['ano'];
		$valor[2]=$_REQUEST['per'];

		$cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"controlnotas",$valor);
		$registros=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
		$cuentaNotas=count($registros);
		if(!is_array($registros))
		{
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
			$cadena='No hay asignaturas programadas en '.$valor[4] .', para el Periodo Acad&eacute;mico '.$valor[1].' - '.$valor[2].'.';
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
			$variable="pagina=adminReportesExcelCoordinador";
			$variable.="&opcion=controlNotas";
			$variable.="&no_pagina=true";
			$variable.="&carrera=".$valor[3]."";
			$variable.="&ano=".$valor[1]."";
			$variable.="&periodo=".$valor[2]."";
			$variable.="&nomcra=".$valor[4]."";
			$variable=$cripto->codificar_url($variable,$configuracion);
			echo $indice.$variable."'";
			echo "target='_blank'";
			echo "title='Generar reporte en Excel'>Generar Excel";
			echo "</a></td></tr>";
			echo "</table>";
			?>
			<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0" >
				<tr>
					<td>	
						<table class="formulario" align="center">
							<tr>
								<td class="cuadro_brown" colspan="5">
									<br>
									<ul>
										<li> Posicione el cursor sobre el nombre de la Asignatura, para ver el Docente responsable.</li>
									</ul>
								</td>
							</tr>
							<!--tr>
								<td>
									<p><a href="https://condor.udistrital.edu.co/appserv/manual/plan_trabajo.pdf">
									<img border="0" alt=" " src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pdfito.png"?>" />
									Ver Manual de Usuario.</a></p>
								</td>
							</tr-->
							<tr class="texto_subtitulo">
								<td class="" colspan="5" align="center">
									<p><span class="texto_negrita">CONTROL DE NOTAS DE <?echo $valor[4];?><br> PERIODO ACAD&Eacute;MICO <?echo $valor[1].' - '.$valor[2];?></span></p>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table class="contenidotabla centrar">
				<tr>
					<td>
						<fieldset>
							<legend>
								Control de notas
							</legend>
							<table class="contenidotabla" border="1">
								<tr class="cuadro_color">
									<td class="cuadro_plano centrar">
										C&oacute;digo
									</td>
									<td class="cuadro_plano centrar">
										Asignatura
									</td>
									<td class="cuadro_plano centrar">
										Grupo
									</td>
									<td class="cuadro_plano centrar">
										No. inscritos
									</td>
									<td class="cuadro_plano centrar">
										Parcial 1
									</td>
									<td class="cuadro_plano centrar">
										Parcial 2
									</td>
									<td class="cuadro_plano centrar">
										Parcial 3
									</td>
									<td class="cuadro_plano centrar">
										Parcial 4
									</td>
									<td class="cuadro_plano centrar">
										Parcial 5
									</td>
									<td class="cuadro_plano centrar">
										Parcial 6
									</td>
									<td class="cuadro_plano centrar">
										Exa.
									</td>
									<td class="cuadro_plano centrar">
										Def.
									</td>
								</tr>  
								
								<? 
									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
									include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
																									
									setlocale(LC_MONETARY, 'en_US');
									$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
									$cripto=new encriptar();
									for($i=0; $i<=$cuentaNotas-1; $i++)
									{
										$title= 'Docente: '. $registros[$i][1].'<br>Identificaci&oacute;n: '.$registros[$i][2].'<br> Tel&eacute;fono: '.$registros[$i][3].'<br> Celular: '.$registros[$i][4].'<br> Correo: '.$registros[$i][5].'<br> Correo Ins.: '.$registros[$i][18];
										$valor[4]=$registros[$i][0];
										echo '<tr>';
										echo '<td align="center">'.$registros[$i][6].'</td>';
										?>
										<td class="cuadro_plano centrar" onmouseover="toolTip('<BR><?echo $title;?>&nbsp;&nbsp;&nbsp;',this)" >
										<div class="centrar">
											<span id="toolTipBox" width="500" ></span>
										</div>
										<?
										echo $registros[$i][7].'</td>';
										echo '<td align="center">'.$registros[$i][8].'</td>';
										echo '<td align="center">'.$registros[$i][9].'</td>';
										echo '<td align="center">'.$registros[$i][10].'</td>';
										echo '<td align="center">'.$registros[$i][11].'</td>';
										echo '<td align="center">'.$registros[$i][12].'</td>';
										echo '<td align="center">'.$registros[$i][13].'</td>';
										echo '<td align="center">'.$registros[$i][14].'</td>';
										echo '<td align="center">'.$registros[$i][15].'</td>';
										echo '<td align="center">'.$registros[$i][16].'</td>';
										echo '<td align="center">'.$registros[$i][17].'</td>';  
										echo '</tr>';    
									}  
								?>
								
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
						
			<?
		}
	}
	
 /*_________________________________________________________________________________________________
		
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
			case "msgErrores":
				$variable="pagina=registro_plan_trabajo";
				$variable.="&opcion=mensajes";
				$variable.="&mensaje=".$valor[9];
				$variable.="&valor=".$valor[1];
				$variable.="&clave=".$valor[2];
				$variable.="&nivel=".$valor[10];
				break;
			case "formgrado":
				$variable="pagina=registro_plan_trabajo";
				$variable.="&nivel=".$valor[10];
				break;
			case "registroExitoso":
				$variable="pagina=adminListaCursos";
				$variable.="&opcion=registroAcuerdo";
				$variable.="&mensaje=".$valor[1];
				break;
							
		}
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
		
}
	

?>

