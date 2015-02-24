<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionRegistro.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class lista_noticia implements funcionRegistro
{
	//@ Método costructor que crea los objetos sql_listaNoticia para crear sentencias sql, un objeto cripto de la clse encriptar, para codificar y decodificar enlaces y cadenas.
	function __construct($configuracion)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->sql=new sql_listaNoticia();
		$this->cripto=new encriptar();
	}
	
	function nuevoRegistro($configuracion,$tema,$acceso_db)
	{
		
	}
	
	
	function editarRegistro($configuracion,$tema,$id,$acceso_db,$formulario)
	{
		
	}
	
	// @ Método que crea el las tablas para visualizar los diferentes tipos de noticias, en la pagina principal del portal
	function mostrarRegistro($configuracion,$tema,$id, $acceso_db, $formulario)
	{ 
	//Pagina a donde direcciona el menu
	//$pagina="adminNoticia";
	//Rescatar los recibos que se encuentran en proceso de impresion
	$this->conectarDB($configuracion);
	//@Obtene
	$this->cadena1_sql=$this->sql->cadena_sql($configuracion,"general","GENERAL");
	$registro1=$this->acceso_db->ejecutarAcceso($this->cadena1_sql,"busqueda");
	$this->cadena2_sql=$this->sql->cadena_sql($configuracion,"general","VERSIONES");
	$registro2=$this->acceso_db->ejecutarAcceso($this->cadena2_sql,"busqueda");
	$this->cadena3_sql=$this->sql->cadena_sql($configuracion,"general","AVANCE");
	$registro3=$this->acceso_db->ejecutarAcceso($this->cadena3_sql,"busqueda");
	//echo $this->cadena1_sql;		
	//echo $this->cadena1_sql;		
		//Rescatar una hoja específica

	/*if(!is_array($registro1) ||!is_array($registro2) || !is_array($registro3 ))
	{	
		
		$cadena="En la actualidad no hay noticias publicadas.";
		$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
		alerta::sin_registro($configuracion,$cadena);	
	}
	else
	{*/
		?>
		<table width="100%" align="center" border="1" cellpadding="10" cellspacing="0">
		<tbody>
		<tr>
			<td width="33%">
			<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
				<tr class="texto_subtitulo">
					<td>
					Informaci&oacute;n General
					<hr class="hr_subtitulo">
					</td>
				</tr>
				<tr>
				<td width="33%">
				<div style="overflow-y:auto; height:90; width:100%;">
				<?
				  if(!is_array($registro1))
					{	
		
						$cadena="En la actualidad no hay informació publicada.";
						$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
						alerta::sin_registro($configuracion,$cadena);	
					}
					else 
					{
						$campos=count($registro1); $this->lista_registro_noticia($configuracion,$registro1,$campos,$tema,$acceso_db);
					}?>

				</div>
				</td>
				</tr>
			</table>
			</td>
			<td width="33%">
			<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
				<tr class="texto_subtitulo">
					<td>
					Versiones liberadas
					<hr class="hr_subtitulo">
					</td>
				</tr>
				<tr>
				<td width="33%">
				<div style="overflow-y:auto; height:90; width:100%;">
				<?
				  if(!is_array($registro2))
					{	
		
						$cadena="En la actualidad no hay versiones liberadas.";
						$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
						alerta::sin_registro($configuracion,$cadena);	
					}
					else 
					{
						$campos=count($registro2); $this->lista_registro_noticia($configuracion,$registro2,$campos,$tema,$acceso_db);
					}?>

				</div>
				</td>
				</tr>
			</table>
			</td>
			<td width="33%">
			<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
				<tr class="texto_subtitulo">
					<td>
					Ultimos Avances DWARE
					<hr class="hr_subtitulo">
					</td>
				</tr>
				<tr valign="up">
				<td width="33%">
				<div style="overflow-y:auto; height:90; width:100%;">
				<?
				  if(!is_array($registro3))
					{	
		
						$cadena="No hay avances a la fecha.";
						$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
						alerta::sin_registro($configuracion,$cadena);	
					}
					else 
					{
						$campos=count($registro3); $this->lista_registro_noticia($configuracion,$registro3,$campos,$tema,$acceso_db);
					}?>

				</div> 
				</td>
				</tr>
			</table>
			<tbody>
			</td>
			</tr>
		</table>
		<?								
			
				
	}
		
	function corregirRegistro()
	{
	
	}
	
	

	//@ Método realiza la conexion con la base de datos.
	function conectarDB($configuracion)
	{
		$this->acceso_db=new dbms($configuracion);
		$this->enlace=$this->acceso_db->conectar_db();
		if (is_resource($this->enlace))
		{
				return $this->acceso_db;
		}
		else
		{
			die("Imposible conectarse a la base de datos");
		}
	}	

//@ Método que permite la visualizacion de las noticias publicadas en el portal.
	function lista_registro_noticia($configuracion,$registro,$campos,$tema,$acceso_db)
	{ 
		
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
		//setlocale(LC_MONETARY, 'en_US');
	for($contador=0;$contador<$campos;$contador++)
	{	
	?>
	
	<table width="100%" heigth="90" align="center" border="0" cellpadding="0" cellspacing="0" >
		<tbody>
			<tr>
				<td >
					<table width="100%" border="0" align="center" cellpadding="2 px" cellspacing="1px" >
						<tr class="texto_subtitulo">
							<td>
							<hr class="hr_subtitulo">
							</td>
						</tr>
						<tr>
	<?
			//$registro=$this->nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
			if($registro)
			{
				
				$usuario=$registro[0][0];
			}
			
			?>
			<td>		
				<table class="contenidotabla">
					<tr class="cuadro_color">
						<td class="cuadro_plano">
						<font size="4">
						<a href="<?		
							$variable="pagina=index";
							$variable.="&opcion=mostrar";
							$variable.="&tipo=".$_REQUEST['tipo'];
							$variable.="&id=".$registro[$contador][0];
							$variable=$this->cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"><? echo $registro[$contador][2] ?></a>
						
						</font><br>
						Publicada: <? echo date("d/m/Y",$registro[$contador][4]) ?>
						</td>
					</tr>	
				<?
			$this->var[0]=$registro[$contador][1];
			$this->cadena_sql=$this->sql->cadena_sql($configuracion,"select",$this->var);
			//echo $this->cadena_sql;
			$this->tipo=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
				?>	
					<tr>
						<td class="cuadro_plano">
							<? echo substr($registro[$contador][3],0,150)."..."; ?>
						</td>
					</tr>
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
	}
}
?>
