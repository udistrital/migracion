<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionRegistro.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/log.class.php");
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class admin_diccionario implements funcionRegistro
{
	//@ Método costructor que crea los objetos sql_adminNoticia para crear sentencias sql, un objeto cripto de la clse encriptar, para codificar y decodificar enlaces y cadenas.
	function __construct($configuracion)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$this->cripto=new encriptar();
		$this->tema=$tema;
		$this->sql=new sql_adminDiccionario();
	}
	
	function nuevoRegistro($configuracion,$tema,$acceso_db)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();
		$this->nuevoObjeto($configuracion,$tema,$cripto);
		
	}
	
	
	function editarRegistro($configuracion,$tema,$id,$acceso_db,$formulario)
	{	
		$this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar",$id);
		//echo $this->cadena_sql;
		$registro=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
		$this->editar_registro_noticia($configuracion,$registro,$tema,$this->cripto);
	}
	
	// @ Método que crea el formulario que recepciona los datos para crear una nueva noticia
	function mostrarRegistro($configuracion,$tema,$id, $acceso_db, $formulario)
	{ 
	//Pagina a donde direcciona el menu
	$pagina="adminDiccionario";
	$this->conectarDB($configuracion);
	//@Obtene
		$this->cadena_sql=$this->sql->cadena_sql($configuracion,"completa","");
		 $this->count_sql=$this->sql->cadena_sql($configuracion,"contar","");
	
		//echo $this->count_sql;
		$regis=$this->acceso_db->ejecutarAcceso($this->count_sql,"busqueda");
		
		$this->cadena_hoja=$this->cadena_sql;
	  //Si no se viene de una hoja anterior
	 	if(!isset($_REQUEST["hoja"]))
			{
			$_REQUEST["hoja"]=1;
			}
			
		//echo $this->cadena_hoja;
		
		$this->cadena_hoja.=" LIMIT ".(($_REQUEST["hoja"]-1)*$configuracion['registro']).",".$configuracion['registro'];		
		//echo $this->cadena_hoja;
		$registro=$this->acceso_db->ejecutarAcceso($this->cadena_hoja,"busqueda");
		
		if(is_array($registro))
		{	
			$campos=$regis[0][0];
			$hojas=ceil($campos/$configuracion['registro']);
		}
		else
		{
			$hojas=1;
		}
			
			
		//Rescatar una hoja específica
		
				if(!is_array($registro))
				{	
					
					$cadena="En la actualidad no hay Objetos Registrados.";
					$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
					alerta::sin_registro($configuracion,$cadena);	
				}
				else
				{
					$campos=count($registro);
					$variable["pagina"]="adminDiccionario";
					$variable["opcion"]=$_REQUEST["opcion"];
					$variable["hoja"]=$_REQUEST["hoja"];
					$variable["tipo"]=$id;									
							
					$menu=new navegacion();
					if($hojas>1)
					{
						$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$variable);
					}
					$this->lista_objeto($configuracion,$registro,$campos,$tema,$this->cripto);
					$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$variable);
				}
				
	}
				
	function corregirRegistro()
	{
	
	}
	
	function verRegistro($configuracion,$tema,$id,$acceso_db,$formulario)
	{	$this->conectarDB($configuracion);
		$this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_id",$id);
		//echo $this->cadena_sql;
		$registro=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
		$this->verObjeto($configuracion,$registro,$tema,$this->cripto);
		if(isset($_REQUEST['id_objeto']))
			{$this->cadena_sql=$this->sql->cadena_sql($configuracion,"relacionado",$id);
				$this->count_sql=$this->sql->cadena_sql($configuracion,"contar_relacionado",$id);
				//echo $this->count_sql;
				$regis=$this->acceso_db->ejecutarAcceso($this->count_sql,"busqueda");
				
				$this->cadena_hoja=$this->cadena_sql;
			//Si no se viene de una hoja anterior
				if(!isset($_REQUEST["hoja"]))
					{
					$_REQUEST["hoja"]=1;
					}
					
				//echo $this->cadena_hoja;
				
				$this->cadena_hoja.=" LIMIT ".(($_REQUEST["hoja"]-1)*$configuracion['registro']).",".$configuracion['registro'];		
				//echo $this->cadena_hoja;
				$registro=$this->acceso_db->ejecutarAcceso($this->cadena_hoja,"busqueda");
				
				if(is_array($registro))
				{	
					$campos=$regis[0][0];
					$hojas=ceil($campos/$configuracion['registro']);
				}
				else
				{
					$hojas=1;
				}
				$campos=count($registro);
				$variable["pagina"]="adminDiccionario";
				$variable["opcion"]=$_REQUEST["opcion"];
				$variable["hoja"]=$_REQUEST["hoja"];
				$variable["id_objeto"]=$_REQUEST['id_objeto'];					
				$menu=new navegacion();
				if($hojas>1)
				{
					$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$variable);
				}
				$this->lista_objeto($configuracion,$registro,$campos,$tema,$this->cripto);
				$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$variable);

			}

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

	//Metodo para listar los objetos registrados
	function lista_objeto($configuracion,$registro,$campos,$tema,$cripto)
	{ 
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
		//setlocale(LC_MONETARY, 'en_US');
		
	?><table width="95%" align="center" border="0" cellpadding="10" cellspacing="0" >
		<tbody>
			<tr>
				<td >
				<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
					<tr class="texto_subtitulo">
						<td>	<?
							if(!isset($_REQUEST['id_objeto']))
								{ 
								 echo "Objetos Diccionario de Datos";
								}
							else	{ echo "Objetos Relacionados";
								}	
							?>
							
							<hr class="hr_subtitulo">
						</td>
					</tr>
					<tr>
				<td>		
				<table class="contenidotabla">
					<tr class="cuadro_color">
						<td class="cuadro_plano centrar">
						Nombre
						</td>
						<td class="cuadro_plano centrar">
						Tipo
						</td>
						<td class="cuadro_plano centrar">
						Etiqueta
						</td>
						<td colspan="2" class="cuadro_plano centrar">
						Opciones
						</td>
					</tr>	
				<?
		for($contador=0;$contador<$campos;$contador++)
		{	$this->tact=$registro[$contador][2];
			$this->tipo_sql=$this->sql->cadena_sql($configuracion,"select",$this->tact);
			//echo "<br>".$this->tipo_sql;
			$this->tipo=$this->acceso_db->ejecutarAcceso($this->tipo_sql,"busqueda");
				?>	
					<tr>
						<td class="cuadro_plano">
						<a href="<?
						$variable="pagina=adminDiccionario";
						$variable.="&opcion=ver";
						$variable.="&id_objeto=".$registro[$contador][0];
						$variable=$cripto->codificar_url($variable,$configuracion);
						echo $indice.$variable;	
						?>">
							<?echo $registro[$contador][1] ?>
						</a>	
						</td>
						<td class="cuadro_plano">
							<? 
							echo $this->tipo[0][0] ?>
						</td>
						<td class="cuadro_plano">
							<? echo $registro[$contador][3] ?>
						</td>
						<td align="center" width="10%" class="cuadro_plano" colspan="2">
						<a href="<?
						$variable="pagina=adminDiccionario";
						$variable.="&opcion=editar";
						$variable.="&id_objeto=".$registro[$contador][0];
						$variable=$cripto->codificar_url($variable,$configuracion);
						echo $indice.$variable;	
						?>"><img width="24" heigth="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/editar.png"?>" alt="Editar este registro" title="Editar este registro" border="0" />	
						<a href="<?
						$variable="pagina=borrar_registro";
						$variable.="&opcion=diccionario";
						$variable.="&registro=".$registro[$contador][0];
						$redireccion="";		
						reset ($_REQUEST);
						while (list ($clave, $val) = each ($_REQUEST)) 
						{
							$redireccion.="&".$clave."=".$val;
							
						}
						
						$variable.="&redireccion=".$cripto->codificar_url($redireccion,$configuracion);
						
						$variable=$cripto->codificar_url($variable,$configuracion);
						
						echo $indice.$variable;	
						?>"><img width="24" heigth="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/boton_borrar.png"?>" alt="Borrar el registro" title="Borrar el registro" border="0" /></a>	
						
						</td>
					</tr>
		<?
		}
	
		?>		</table>
				</td>
				</tr>
				</table>
				</td>
			</tr>
		</tbody>
	</table><?
	}


	function nuevoObjeto($configuracion,$tema,$cripto)
	{
		$datos="";
		$contador=0;
		$formulario="admin_activo";
		$tab=1;
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
	?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $formulario?>'>
		<table class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0'>
		<tr>
		<td>
		<table align='center' width='100%' cellpadding='7' cellspacing='1'>
			<tr class="texto_subtitulo">
				<td colspan="2">	
				Crear nuevo Objeto				<?
					if(isset($_REQUEST['id_objeto']))
					{ 
						 $this->conectarDB($configuracion);
					   $this->cadena_sql=$this->sql->cadena_sql($configuracion,"buscar_id",$_REQUEST['id_objeto']);
					   //echo $this->cadena_sql;
					   $regis=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
					     echo ' Relacionado con el Objeto "'.$regis[0][1].'"';
					}
						
					?>
					
				</td>
			</tr>
			<tr class="texto_subtitulo">
				<td colspan="2">	
					<hr class="hr_subtitulo">
				</td>
			</tr>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
					Tipo:
				</td>
				<td bgcolor='<? echo $tema->celda ?>'><?
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
					$html=new html();
					$busqueda="SELECT id_tipo,valor FROM ".$configuracion["prefijo"]."variable WHERE tipo='TIPO_OBJETO' ORDER BY valor";
					$tipo=$html->cuadro_lista($busqueda,'id_tipo',$configuracion,1,0,FALSE,$tab++);
					echo $tipo;
					?>	
				</td>
			</tr>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
					Nombre:
				</td>
				<td bgcolor='<? echo $tema->celda ?>'>
					<input type='text' name='nombre' size='40' maxlength='100' tabindex='<? echo $tab++ ?>' >
				</td>
			</tr>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
					Etiqueta:
				</td>
				<td bgcolor='<? echo $tema->celda ?>'>
					<input type='text' name='etiqueta' size='40' maxlength='100' tabindex='<? echo $tab++ ?>' >
				</td>
			</tr>
			<tr align='center'>
				<td colspan='2' rowspan='1'>
				<?	if(isset($_REQUEST['id_objeto']))
						{?>
						<input type='hidden' name='id_objeto' value='<?echo $_REQUEST['id_objeto']?>'>
					<?	}
					
				?>
					<input type='hidden' name='action' value='admin_diccionario'>
					<input type='hidden' name='opcion' value='nuevo'>
					<input name='aceptar' value='Aceptar' type='submit'><br>
				</td>
			</tr>
		</table>
		</td>
		</tr>
		</table>
		</form>
	<?

	}


function editar_registro_noticia($configuracion,$registro,$tema,$cripto)
	{ 
		$datos="";
		$contador=0;
		$formulario="admin_activo";
		$tab=1;
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		
	?>
	<form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET' action='index.php' name='<? echo $formulario?>'>
		<table class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0'>
		<tr>
		<td>
		<table align='center' width='100%' cellpadding='7' cellspacing='1'>	
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
						Tipo Noticia:
				</td>

				<td bgcolor='<? echo $tema->celda ?>' colspan="3">
					<input type='hidden' name='id_noticia' value='<? echo $registro[0][0] ?>' size='11' maxlength='11' tabindex='<? echo $tab++ ?>' >
					<?
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
					$html=new html();
					$busqueda="SELECT id_tipo,valor FROM ".$configuracion["prefijo"]."variable WHERE tipo='NOTICIA' ORDER BY id_tipo";
					$mi_cuadro=$html->cuadro_lista($busqueda,'id_tipo',$configuracion,$registro[0][1],0,FALSE,$tab++);
					echo $mi_cuadro;
					?>	
				</td>echo "con obj".$_REQUEST["id_objeto"];exit;
			</tr>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
					Publicado:
				</td>
				<td bgcolor='<? echo $tema->celda ?>'>
					<? echo date("d/m/Y",$registro[0][4]); ?>
				</td>
				<td bgcolor='<? echo $tema->celda ?>'>
					por:
				</td>
				<td bgcolor='<? echo $tema->celda ?>'>
				<? 
				$this->var[0]=$registro[0][5];
				$this->cadena_sql=$this->sql->cadena_sql($configuracion,"usuario",$this->var);
					//echo $this->cadena_sql;
				$this->user=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
				echo $this->user[0][0] ?>
				</td>
			</tr>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
					Titulo:
				</td>
				<td bgcolor='<? echo $tema->celda ?>' colspan='3'>
					<input type='text' name='titulo_noticia' value='<? echo $registro[0][2] ?>' size='40' maxlength='200' tabindex='<? echo $tab++ ?>' >
				</td>
			</tr>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>' >
					Cuerpo de la Noticia
				</td>
				<td bgcolor='<? echo $tema->celda ?>' colspan="3">
					<textarea id='noticia' name='noticia' cols='55' rows='2' tabindex='<? echo $tab++ ?>' ><? echo $registro[0][3] ?></textarea>
					<script type="text/javascript">
						mis_botones='<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/';
						archivo_css='<? echo $configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/basico/estilo.php" ?>';
						editor_html('noticia', 'bold italic underline | left center right | number bullet indent outdent | undo redo  | color hilite rule | link wikilink image table | word clean html spellcheck');
					</script>
				</td>
			</tr>
			
			<tr align='center'>
				<td colspan='4' rowspan='1' align='center'>
					<table align='center' width='50%'>
					<tr align='center'>
					<td>	<input type='hidden' name='opcion' value='editar'>
						<input type='hidden' name='action' value='admin_noticia'>
						<input name='aceptar' value='Aceptar' type='submit'><br>
					</td>
					<td>	<input name='cancelar' value='Cancelar' type='submit'><br>
					</td>
					</tr>
					</table>
				</td>
			</tr>
		</table>
		</td>
		</tr>
		</table>
		</form>

	<?
	}
	
	
	//@ Método que muestra los datos de un objeto y sus objetos relacionados
	function verObjeto($configuracion,$registro,$tema,$cripto)
	{ 
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
		//setlocale(LC_MONETARY, 'en_US');
		$this->tact=$registro[0][2];
		$this->tipo_sql=$this->sql->cadena_sql($configuracion,"select",$this->tact);
		//echo "<br>".$this->tipo_sql;
		$this->tipo=$this->acceso_db->ejecutarAcceso($this->tipo_sql,"busqueda");
	?><table class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0'>
		<tr>
		<td>
		<table align='center' width='100%' cellpadding='7' cellspacing='1'>			<tr class="texto_subtitulo">
				<td colspan="2">	
				Objeto		
				<hr class="hr_subtitulo">
				</td>
			</tr>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
					nombre:
				</td>
				<td bgcolor='<? echo $tema->celda ?>'>
					<? echo $registro[0][1] ?>
				</td>
			</tr>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
					tipo:
				</td>
				<td bgcolor='<? echo $tema->celda ?>'>
					<? echo $this->tipo[0][0] ?>
				</td>
			</tr>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
					etiqueta:
				</td>
				<td bgcolor='<? echo $tema->celda ?>'>
					<? echo $registro[0][3] ?>
				</td>
			</tr>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
					fecha Registro:
				</td>
				<td bgcolor='<? echo $tema->celda ?>'>
					<? echo	date("d/m/Y",$registro[0][4])?>
				</td>
			</tr>
		</table>
		</td>
		</tr>
		</table>
		<?
	}
	
	
//Metodo que guarda el registro de los objetos, determinando el nivel y la fecha.	
 function guardarObjeto($configuracion)
	{	
		$log_us=new log();
		$this->conectarDB($configuracion);
		//VARIABLES PARA EL REGISTRO DE OBJETO
		$variable[0]=$_REQUEST["nombre"];
		$variable[1]=$_REQUEST["id_tipo"];
		$variable[2]=$_REQUEST["etiqueta"];
		$variable[3]=time();	
		
		//VARIABLES PARA EL LOG
		$registro[0]="CREAR";
		$registro[2]="OBJETO";
		$registro[3]=$variable[0];
		$registro[4]=$variable[3];
		
		if (!isset($_REQUEST["id_objeto"]))
			{
			//Guarda el registro del objeto con nivel 1
			 $variable[4]='1';
			 $this->cadena_sql=$this->sql->cadena_sql($configuracion,"insertar",$variable);
			 //echo $this->cadena_sql;
			 $resultado=$this->acceso_db->ejecutar_acceso_db($this->cadena_sql);
			 
			 //recupera el id del objeto para el logger
			 $this->recupera_sql=$this->sql->cadena_sql($configuracion,"recuperar",$variable);
			 $recuperado=$this->acceso_db->ejecutarAcceso($this->recupera_sql,"busqueda");	
			 $registro[1]= $recuperado[0][0];
			 $registro[5]="Se registra el Objeto ".$registro[3]." con nivel ".$variable[4]." sin relaciones";
			 $log_us->log_usuario($registro,$configuracion);
			}
		else	{//si llega un id_objeto, recupera el nivel del objeto y Guarda el registro del objeto con nivel inferior al recuperado
				$this->recupera_sql=$this->sql->cadena_sql($configuracion,"recuperar_nivel",$_REQUEST["id_objeto"]);
				$nivel=$this->acceso_db->ejecutarAcceso($this->recupera_sql,"busqueda");
				
				$variable[4]=($nivel[0][0]+1);	
				$this->cadena_sql=$this->sql->cadena_sql($configuracion,"insertar",$variable);
			 	//echo $this->cadena_sql;
				$resultado=$this->acceso_db->ejecutar_acceso_db($this->cadena_sql);
				
				$this->recupera_sql=$this->sql->cadena_sql($configuracion,"recuperar",$variable);
				$recuperado=$this->acceso_db->ejecutarAcceso($this->recupera_sql,"busqueda");
				
				//variables para realizar la relacion de los objetos	
				$id_objeto2= $recuperado[0][0];
				$relacion[0]=$_REQUEST["id_objeto"];
				$relacion[1]=$id_objeto2;
				$relacion[2]=$variable[3];
				$relacion[3]='JERARQUIA';
				$this->cadena_sql=$this->sql->cadena_sql($configuracion,"relaciona",$relacion);
			 	//echo $this->cadena_sql;
				$resultado=$this->acceso_db->ejecutar_acceso_db($this->cadena_sql);
				
				//para guardar datos el log_usuario
				$registro[1]= $recuperado[0][0];
			 	$registro[5]="Se registra el Objeto ".$registro[3]." con nivel ".$variable[4]." y relacion con el objeto ".$nivel[0][1];
			 	$log_us->log_usuario($registro,$configuracion);
		
			}	
	
		if($resultado==true)
		{
			unset($_REQUEST['action']);		
			$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=adminDiccionario";
			if (isset($_REQUEST["id_objeto"]))
				{$variable.="&id_objeto=";
				 $variable.=$_REQUEST["id_objeto"];
				 $variable.="&opcion=ver";
				}
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$cripto=new encriptar();
			$variable=$cripto->codificar_url($variable,$configuracion);
			
			echo "<script>location.replace('".$pagina.$variable."')</script>";   
		
		}
		else
		{
			echo "<h1>Imposible guardar el registro</h1>";
			
		}
		
	}	
	

}
?>
