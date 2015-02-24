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
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class registro_noticia implements funcionRegistro
{
	//@ Método costructor que crea el objeto sql de la clase sql_noticia
	function __construct($configuracion)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		
		$this->sql=new sql_noticia();
	}
	
	// @ Método que crea el formulario que recepciona los datos para crear una nueva noticia
	function nuevoRegistro($configuracion,$tema,$acceso_db)
	{
		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();
		$datos="";
		$contador=0;
		$formulario="registro_noticia";
		$tab=1;
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
	?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $formulario?>'>
<table class='bloquelateral' align='center' width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td>
<table align='center' width='100%' cellpadding='7' cellspacing='1'>
	<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
		<td bgcolor='<? echo $tema->celda ?>'>
			Tipo Noticia:
		</td>
		<td bgcolor='<? echo $tema->celda ?>'><?
			include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
			$html=new html();
			$busqueda="SELECT id_tipo,valor FROM ".$configuracion["prefijo"]."variable WHERE tipo='NOTICIA' ORDER BY id_tipo";
			$mi_cuadro=$html->cuadro_lista($busqueda,'id_tipo',$configuracion,1,0,FALSE,$tab++);
			echo $mi_cuadro;
			?>	
		</td>
	</tr>
	<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
		<td bgcolor='<? echo $tema->celda ?>'>
			T&iacute;tulo
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<input type='text' name='titulo_noticia' size='40' maxlength='200' tabindex='<? echo $tab++ ?>' >
		</td>
	</tr>
	<tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
		<td bgcolor='<? echo $tema->celda ?>'>
			Cuerpo de la Noticia
		</td>
		<td bgcolor='<? echo $tema->celda ?>'>
			<textarea id='noticia' name='noticia' cols='50' rows='2' tabindex='<? echo $tab++ ?>' ></textarea>
			<script type="text/javascript">
				mis_botones='<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/';
				archivo_css='<? echo $configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/basico/estilo.php" ?>';
				editor_html('noticia', 'bold italic underline | left center right | number bullet indent outdent | undo redo  | color hilite rule | link wikilink image table | word clean html spellcheck');
			</script>
		</td>
	</tr>
	<tr align='center'>
		<td colspan='2' rowspan='1'>
			<input type='hidden' name='action' value='registro_noticia'>
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
	
	
	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
	{
		
	}
	
	
	function mostrarRegistro($configuracion,$tema,$id_entidad, $acceso_db, $formulario)
	{
				
	}
		
	function corregirRegistro()
	{
	
	}
	//@ Método que rescata los valores de variables y los guarda en una cadena, se conecta con la clase sql enviandole la informacion rescatada,recibe y ejecuta la senetencia sql
	function procesarRegistro($configuracion)
	{
	
		$this->nueva_sesion=new sesiones($configuracion);
		$this->nueva_sesion->especificar_enlace($this->enlace);
		$this->esta_sesion=$this->nueva_sesion->numero_sesion();
		//Rescatar el valor de la variable usuario de la sesion
		$registro=$this->nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
		if($registro)
		{
			
			$usuario=$registro[0][0];
		}
		
		
		$variable[0]=$_REQUEST["id_tipo"];
		$variable[1]=$_REQUEST["titulo_noticia"];
		$variable[2]=$_REQUEST["noticia"];
		$variable[3]=$usuario;	
		
		$this->cadena_sql=$this->sql->cadena_sql($configuracion,"insertar",$variable);
		$this->conectarDB($configuracion);
		$resultado=$this->acceso_db->ejecutar_acceso_db($this->cadena_sql);	
		if($resultado==true)
		{
			unset($_REQUEST['action']);		
			$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
			$variable="pagina=adminNoticia";
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
}
?>
