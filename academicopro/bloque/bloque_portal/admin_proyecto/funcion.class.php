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
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class admin_proyecto implements funcionRegistro
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
		$this->sql=new sql_adminProyecto();
	}
	
	function nuevoRegistro($configuracion,$tema,$acceso_db)
	{
		
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
	$pagina="adminProyecto";
	//Rescatar los recibos que se encuentran en proceso de impresion
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
					
					$cadena="En la actualidad no hay Proyectos Registrados.";
					$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
					alerta::sin_registro($configuracion,$cadena);	
				}
				else
				{
					$campos=count($registro);
					$variable["pagina"]="adminProyecto";
					$variable["opcion"]=$_REQUEST["opcion"];
					$variable["hoja"]=$_REQUEST["hoja"];
					$variable["tipo"]=$id;									
							
					$menu=new navegacion();
					if($hojas>1)
					{
						$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$variable);
					}
					$this->lista_proyecto($configuracion,$registro,$campos,$tema,$this->cripto);
					$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$variable);
				}
				
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


	function lista_proyecto($configuracion,$registro,$campos,$tema,$cripto)
	{ 
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
		//setlocale(LC_MONETARY, 'en_US');
		
	?><table width="95%" align="center" border="0" cellpadding="10" cellspacing="0" >
		<tbody>
			<tr>
				<td >
					<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
						<tr class="texto_subtitulo">
							<td>
							Proyectos
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
						<td class="cuadro_plano centrar">
						ID
						</td>
						<td class="cuadro_plano centrar">
						Nombre
						</td>
						<td class="cuadro_plano centrar">
						Descripci&oacute;n
						</td>
						<td colspan="2" class="cuadro_plano centrar">
						Opciones
						</td>
					</tr>	
				<?
		for($contador=0;$contador<$campos;$contador++)
		{	$this->var[0]=$registro[$contador][1];
			$this->cadena_sql=$this->sql->cadena_sql($configuracion,"select",$this->var);
			//echo $this->cadena_sql;
			$this->tipo=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
				?>	
					<tr>
						<td class="cuadro_plano">
							<span class="texto_negrita"><? echo $registro[$contador][0]?></span>
						</td>
						<td class="cuadro_plano">
						<a href="<?
						$variable="pagina=adminProyecto";
						$variable.="&opcion=ver";
						$variable.="&id_proyecto=".$registro[$contador][0];
						$variable=$cripto->codificar_url($variable,$configuracion);
						echo $indice.$variable;	
						?>">
							<?echo $registro[$contador][1] ?>
						</a>	
						</td>
						<td class="cuadro_plano">
							<? echo $registro[$contador][2] ?>
						</td>
						<td align="center" width="10%" class="cuadro_plano" colspan="2">
						<a href="<?
						$variable="pagina=adminProyecto";
						$variable.="&opcion=editar";
						$variable.="&id_proyecto=".$registro[$contador][0];
						$variable=$cripto->codificar_url($variable,$configuracion);
						echo $indice.$variable;	
						?>"><img width="24" heigth="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/editar.png"?>" alt="Editar este registro" title="Editar este registro" border="0" />	
						<a href="<?
						$variable="pagina=borrar_registro";
						$variable.="&opcion=proyecto";
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
	
		?>						</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table><?
	}

function editar_registro_noticia($configuracion,$registro,$tema,$cripto)
	{ 
		$datos="";
		$contador=0;
		$formulario="admin_proyecto";
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
				</td>
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
	
	//@ Método que rescata los valores de variables y los guarda en una cadena, se conecta con la clase sql enviandole la informacion rescatada,recibe y ejecuta la senetencia sql para modificar una noticia
	function procesarRegistro($configuracion)
	{
		$variable[0]=$_REQUEST["id_noticia"];
		$variable[1]=$_REQUEST["id_tipo"];
		$variable[2]=$_REQUEST["titulo_noticia"];
		$variable[3]=$_REQUEST["noticia"];
		
		$this->cadena_sql=$this->sql->cadena_sql($configuracion,"editar",$variable);
		//echo $this->cadena_sql;
		$this->conectarDB($configuracion);
		$resultado=$this->acceso_db->ejecutar_acceso_db($this->cadena_sql);	
		//echo $resultado; exit;
		if($resultado==true)
		{
			unset($_REQUEST['action']);		
			$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=adminNoticia";
			$variable.="&opcion=mostrar";
			$variable.="&tipo=".$_REQUEST["id_tipo"];
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
			$this->cripto=new encriptar();
			$variable=$this->cripto->codificar_url($variable,$configuracion);
			
			echo "<script>location.replace('".$pagina.$variable."')</script>";   
		
		}
		else
		{
			echo "<h1>Imposible Modificar la Noticia</h1>";
			
		}
		
	}
	
	//@ Método que muestra mensaje de alerta de borrado de registrosnoticia
	function ver_proyecto($configuracion,$registro,$tema,$cripto)
	{ 
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
		//setlocale(LC_MONETARY, 'en_US');
		
	?><table width="95%" align="center" border="0" cellpadding="10" cellspacing="0" >
		<tbody>
			<tr>
				<td >
					<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
						<tr class="texto_subtitulo">
							<td>
							Artefactos Proyecto <?echo $registro[0][1] ?>
							<hr class="hr_subtitulo">
							</td>
						</tr>
						<tr>
	<?
			//$registro=$this->nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
			$this->conectarDB($configuracion);
			$this->cadena_sql=$this->sql->cadena_sql($configuracion,"docs",$registro[0][0]);
			//echo $this->cadena_sql;
			$resultado=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
			
			$this->count_sql=$this->sql->cadena_sql($configuracion,"nro_docs",$registro[0][0]);
			//echo $this->count_sql;
			$regis=$this->acceso_db->ejecutarAcceso($this->count_sql,"busqueda");
			$campos=$regis[0][0];
			?>
			<td>		
				<table class="contenidotabla">
					<tr class="cuadro_color">
						<td class="cuadro_plano centrar">
						Nombre
						</td>
						<td class="cuadro_plano centrar">
						Descripci&oacute;n
						</td>
						<td colspan="3" class="cuadro_plano centrar">
						Opciones
						</td>
					</tr>
			<? 
			for($contador=0;$contador<$campos;$contador++)
				{?>	<tr>
						<td class="cuadro_plano"> <?echo $resultado[$contador][1] ?> 
						</td>
						<td class="cuadro_plano"><?echo $resultado[$contador][2] ?>
						</td>
						<td align="center" width="10%" class="cuadro_plano centrar" >
						<a href="<?echo $resultado[$contador][3] ?>" target="popup" onClick="window.open(this.href, this.target, 'width=700,height=500'); return false;">
						<img width="24" heigth="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/importante.png"?>" alt="<?echo "Ver ".$resultado[$contador][1] ?>" title="<?echo $resultado[$contador][1] ?>" border="0" /></a>
						</td>
					</tr>
	
				<? }	?>
					</table>
				</td>
				</tr>
			</table>
			</td>
		</tr>
		</tbody>
	</table><?
	}
	

}
?>
