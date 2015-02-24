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

//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class ver_noticia implements funcionRegistro
{
	//@ Método costructor que crea los objetos sql_verNoticia para crear sentencias sql.
	function __construct($configuracion)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->sql=new sql_verNoticia();
		
	}
	
	function nuevoRegistro($configuracion,$tema,$acceso_db)
	{
		
	}
	
	
	function editarRegistro($configuracion,$tema,$id,$acceso_db,$formulario)
	{
		
	}
	
	// @ Método que permita ver las caracteristicas de la noticia, invocando la funcion ver_noticia 
	function mostrarRegistro($configuracion,$tema,$id, $acceso_db, $registro)
	{ 
		$this->ver_noticia($configuracion,$tema,$registro);				
				
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

//@ Método forma el foemulario que permite ver las caracteristicas de un registro de las noticias.
	function ver_noticia($configuracion,$tema,$registro)
	{ 
		?>
		<table align='center' width='100%' cellpadding='7' cellspacing='1'>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
					TITULO:
				</td>
				<td bgcolor='<? echo $tema->celda ?>' style="font-size:150%" colspan="3">
					<? echo $registro[0][2] ?>
		
				</td>
			</tr>
			<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
				<td bgcolor='<? echo $tema->celda ?>'>
					TIPO:
				</td>
				<td bgcolor='<? echo $tema->celda ?>' colspan="3">
		
					<? 
					$this->var[0]=$registro[0][1];
					$this->cadena_sql=$this->sql->cadena_sql($configuracion,"select",$this->var);
					//echo $this->cadena_sql;
					$this->tipo=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
					
					echo $this->tipo[0][0] ?>
				</td>
			</tr>
	
		<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
			<td bgcolor='<? echo $tema->celda ?>'>
				PUBLICADO:
			</td>
			<td bgcolor='<? echo $tema->celda ?>'>
				<? echo date("d/m/Y",$registro[0][4]) ?>
			</td>
			<td bgcolor='<? echo $tema->celda ?>'>
				POR:
			</td>
			<td bgcolor='<? echo $tema->celda ?>'>
				<? 
				$this->var[0]=$registro[0][5];
				$this->cadena_sql=$this->sql->cadena_sql($configuracion,"usuario",$this->var);
					//echo $this->cadena_sql;
				$this->user=$this->acceso_db->ejecutarAcceso($this->cadena_sql,"busqueda");
				echo $this->user[0][0] ?>
			</td>

				<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
			<td bgcolor='<? echo $tema->celda ?>'>
				CONTENIDO:
			</td>
			<td bgcolor='<? echo $tema->celda ?>' colspan="3">
				<? echo $registro[0][3] ?>
			</td>
	
		</tr>

	</table>
	
		<?				
			
	}
}
?>
