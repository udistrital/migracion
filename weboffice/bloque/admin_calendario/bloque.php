<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2007                                      #
#    paulo_cesar@etb.net.co                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 26 de junio de 2005
*****************************************************************************
* @subpackage   admin_usuario
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Bloque principal para la administración de usuarios
*
******************************************************************************/
?><?
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
if (is_resource($enlace))
{
	if(isset($_GET['accion']))
	{
		$variable="";
		//Envia todos los datos que vienen con GET
		reset ($_GET);
		while (list ($clave, $val) = each ($_GET)) 
		{
			
			if($clave!='page')
			{
				$variable.="&".$clave."=".$val;
				//echo $clave;
			}
		}
		
		switch($_GET['accion'])
		{	
			//Todos los usuarios
			case '1':
				$cadena_hoja="SELECT ";
				$cadena_hoja.="id_criterio,";
				$cadena_hoja.="nombre, ";
				$cadena_hoja.="tipo_documento ";
				$cadena_hoja.="FROM ".$configuracion["prefijo"]."criterio_edu "; 
				$cadena_hoja.="ORDER BY tipo_documento,id_criterio ";
				
				$cadena_sql="SELECT ";
				$cadena_sql.="id_criterio,";
				$cadena_sql.="nombre,";
				$cadena_sql.="tipo_documento ";
				$cadena_sql.="FROM ".$configuracion["prefijo"]."criterio_edu "; 
				$cadena_sql.="ORDER BY tipo_documento,id_criterio ";
				$cadena_sql.=" LIMIT ".($_GET["hoja"]*$configuracion['registros']).",".$configuracion['registros'];
				//echo $cadena_sql;
				break;
			
			default:
				$cadena_hoja="SELECT ";
				$cadena_hoja.="id_criterio,";
				$cadena_hoja.="nombre, ";
				$cadena_hoja.="tipo_documento ";
				$cadena_hoja.="FROM ".$configuracion["prefijo"]."criterio_edu "; 
				$cadena_hoja.="ORDER BY tipo_documento,id_criterio ";
				
				$cadena_sql="SELECT ";
				$cadena_sql.="id_criterio,";
				$cadena_sql.="nombre,";
				$cadena_sql.="tipo_documento ";
				$cadena_sql.="FROM ".$configuracion["prefijo"]."criterio_edu "; 
				$cadena_sql.="ORDER BY tipo_documento,id_criterio ";
				$cadena_sql.=" LIMIT ".($_GET["hoja"]*$configuracion['registros']).",".$configuracion['registros'];
				//echo $cadena_sql;
				break;
					
			
		}
	}
	else
	{
	
		$cadena_hoja="SELECT ";
		$cadena_hoja.="id_criterio,";
		$cadena_hoja.="nombre, ";
		$cadena_hoja.="tipo_documento ";
		$cadena_hoja.="FROM ".$configuracion["prefijo"]."criterio_edu "; 
		$cadena_hoja.="ORDER BY tipo_documento,id_criterio ";
		
		$cadena_sql="SELECT ";
		$cadena_sql.="id_criterio,";
		$cadena_sql.="nombre,";
		$cadena_sql.="tipo_documento ";
		$cadena_sql.="FROM ".$configuracion["prefijo"]."criterio_edu "; 
		$cadena_sql.="ORDER BY tipo_documento,id_criterio ";
		$cadena_sql.=" LIMIT ".($_GET["hoja"]*$configuracion['registros']).",".$configuracion['registros'];
		//echo $cadena_sql;
		
	}		
	//echo $cadena_sql;
	$acceso_db->registro_db($cadena_hoja,0);
	$registro=$acceso_db->obtener_registro_db();
	$campos=$acceso_db->obtener_conteo_db();
	if($campos>0)
	{
		$hoja=ceil($campos/$configuracion['registros'])-1;
		//echo $hoja;
	}
	else
	{
		$hoja=0;
	
	}
	$acceso_db->registro_db($cadena_sql,0);
	$registro=$acceso_db->obtener_registro_db();
	$campos=$acceso_db->obtener_conteo_db();
	if($campos==0)
	{
		?>
<table style="text-align: left;" border="0"  cellpadding="5" cellspacing="0" class="bloquelateral" width="100%">
  <tbody>
    <tr class="mensajealertaencabezado">
      <td >Actualmente no hay criterios registrados en el sistema</td>
    </tr>
    </tbody>
</table><?
		
	}
	else
	{
/*Si existen criterios en el sistema*/
?><script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1 px" class="bloquelateral">
<tr align="center" class="bloquecentralencabezado">
<td >Criterio</td>
<? //<td>Correo</td> ?>
<td>Tipo</td>
<td colspan="3">Opciones</td>
</tr>
	<?
		for($contador=0;$contador<$campos;$contador++)
		{
			?>
<?/*Campo oculto con el id_usuario para poder realizar la actualización de la información*/?>							
<tr class="bloquecentralcuerpo" onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
<td bgcolor="<? echo $tema->celda ?>"><? echo $registro[$contador][1] ?>
<input type="hidden" name= "hoja" value="<?echo $_GET["hoja"] ?>">
<input type="hidden" name= "accion" value="<?echo $_GET["accion"] ?>">
<?/*Campos ocultos para dar continuidad al formulario actual*/?>
<input type="hidden" name= "criterio_<? echo $contador ?>" value="<?echo $registro[$contador][0] ?>">
</td>
<? /*<td class="celdatabla"><? echo $registro[$contador][2] ?></td>*/?>
<td align="center" bgcolor="<? echo $tema->celda ?>"><? echo $registro[$contador][2] ?></td>
<td align="center" bgcolor="<? echo $tema->celda ?>">
<a href="<?
$opcion=$configuracion["site"].'/index.php?page='.enlace('admin_evidencias_edu');
$opcion.=$variable; 
$opcion.="&criterio=".$registro[$contador][0];
echo $opcion;
?>">Evidencias</a>
</td>
<td align="center" bgcolor="<? echo $tema->celda ?>">
<a href="<?
$opcion=$configuracion["site"].'/index.php?page='.enlace('registro_criterio_edu');
$opcion.=$variable; 
$opcion.="&opcion=editar";
$opcion.="&registro=".$registro[$contador][0];
echo $opcion;
?>">
<img width="24" height="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/boton_editar.png" alt="Editar Usuario" title="Editar Usuario" border="0" /></a>
</td>
<td align="center" bgcolor="<? echo $tema->celda ?>">
<a href="<?
	$opcion=$configuracion["site"].'/index.php?page='.enlace('borrar_criterio');
	$opcion.=$variable; 
	$opcion.="&opcion=criterio";
	$opcion.="&registro=".$registro[$contador][0];
	echo $opcion;
?>">
<img width="24" height="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/boton_borrar.png" alt="Borrar usuario del sistema" title="Borrar usuario del sistema" border="0" /></A>
</td>	
</tr><?}?>
</table><br>
<?
// Botones de navegacion
?><br>
<table width="100%" cellpadding="2" cellspacing="2" class="bloquelateral">
<tr class="bloquecentralcuerpo">
	<td align="left" class="celdatabla" width="33%">
	<?
		if($_GET["hoja"]>0)
		{
	?>
	<a title="Pasar a la p&aacute;gina No <? echo $_GET["hoja"] ?>" href="<?
	$variable="";
	
	//Envia todos los datos que vienen con GET
	reset ($_GET);
	while (list ($clave, $val) = each ($_GET)) {
		
		if($clave!='page' && $clave!='hoja')
		{
			$variable.="&".$clave."=".$val;
			//echo $clave;
		}
		else
		{
			if($clave=='hoja')
			{
				$variable.="&".$clave."=".($val-1);
				//echo $variable;
			}
			
		}
		
	}
	
	$opcion=$configuracion["site"].'/index.php?page='.enlace('admin_usuario');
	$opcion.=$variable;
	
	 
	 echo $opcion;
	
	
	

?>"><< Anterior</a>
	<?	} 
	?>
	</td>
	<td align="center" class="celdatabla">
	Hoja <? echo ($_GET["hoja"]+1) ?> de <? echo ($hoja+1) ?>
	</td>
	<td align="right" class="celdatabla" width="33%">
	<?
		if($_GET["hoja"]<$hoja)
		{
	?>
	<a title="Pasar a la p&aacute;gina No <? echo $_GET["hoja"]+2 ?>" href="<?
	$variable="";
	
	//Envia todos los datos que vienen con GET
	reset ($_GET);
	while (list ($clave, $val) = each ($_GET)) {
		
		if($clave!='page' && $clave!='hoja')
		{
			$variable.="&".$clave."=".$val;
			//echo $clave;
		}
		else
		{
			if($clave=='hoja')
			{
				$variable.="&".$clave."=".($val+1);
				//echo $variable;
			}
			
		}
		
	}
	
	$opcion=$configuracion["site"].'/index.php?page='.enlace('admin_usuario');
	$opcion.=$variable;
	
	 
	 echo $opcion;

?>">Siguiente>></a>
<?
	}
?>
	</td>
</tr>
</table>
<?			
  }
}
?>
