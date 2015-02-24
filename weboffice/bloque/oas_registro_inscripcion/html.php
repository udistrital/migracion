<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado 2004 - 2006                                      #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
html.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 1 de Noviembre de 2006

****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Formulario de registro de usuarios
* @usage        Toda pagina tiene un id_pagina que es propagado por cualquier 
*               metodo. 
*****************************************************************************/
?><?

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
//Variables

$formulario="registro_usuario";
$verificar="control_vacio(".$formulario.",'nombre')";
$verificar.="&& control_vacio(".$formulario.",'apellido')";
$verificar.="&& control_vacio(".$formulario.",'correo')";
$verificar.="&& control_vacio(".$formulario.",'usuario')";
$verificar.="&& control_vacio(".$formulario.",'clave')";
$verificar.="&& comparar_contenido(".$formulario.",'clave','clave_2')";
$verificar.="&& longitud_cadena(".$formulario.",'nombre',3)";
$verificar.="&& longitud_cadena(".$formulario.",'apellido',3)";
$verificar.="&& longitud_cadena(".$formulario.",'clave',5)";
$verificar.="&& longitud_cadena(".$formulario.",'usuario',4)";
$verificar.="&& verificar_correo(".$formulario.",'correo')";

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbms.class.php");
$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
if (is_resource($enlace))
{
	
	if(isset($_REQUEST['opcion']))
	{
		$accion=$_REQUEST['opcion'];
		
		switch($accion)
		{
			case "confirmar":
				confirmar_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db);
				break;		
			case "nuevo":
				nuevo_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab);
				break;
			case "editar":
				editar_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab);
				break;
			case "corregir":
				corregir_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db);
				break;
			case "mostrar":
				mostrar_registro($configuracion,$tema,$accion);
				break;
		}
	}
	else
	{
		$accion="nuevo";
		nuevo_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab);
	}	
}
/****************************************************************************************
*				Funciones						*
****************************************************************************************/


function nuevo_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab)
{
	$contador=0;	
?><script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $formulario?>'>
	<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
		<tr>
			<td>
				<table class="formulario" align="center">
					<tr  class="bloquecentralencabezado">
						<td colspan="2">
							<p><span class="texto_negrita">Formulario de Inscripci&oacute;n para Grado</span></p>
						</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="1"><br>Datos Personales<hr class="hr_subtitulo"></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>Nombres:<br>
						</td>
						<td bgcolor="<? echo $tema->celda ?>">
							<input maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="nombre"><br>
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>Apellidos:<br>
						</td>
						<td bgcolor="<? echo $tema->celda ?>">
							<input maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="apellido">
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Tipo de Documento
						</td>
						<td bgcolor='<? echo $tema->celda ?>'><?
						include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
						$html=new html();
						$busqueda="SELECT id_tipo,tipo FROM ".$configuracion["prefijo"]."tipo_documento ORDER BY id_tipo";
						$mi_cuadro=$html->cuadro_lista($busqueda,'id_tipo_documento',$configuracion,1,0,FALSE,$tab++);
						echo $mi_cuadro;
						?>	
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>No Identificaci&oacute;n
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input type='text' name='identificacion' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Lugar de Expedici&oacute;n
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input type='text' name='ciudadIdentificacion' size='30' maxlength='50' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="1"><br>Datos de Contacto<hr class="hr_subtitulo"></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Direcci&oacute;n
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input type='text' name='direccion' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Pa&iacute;s
						</td>
						<td bgcolor='<? echo $tema->celda ?>'><?
						include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
						$html=new html();
						$busqueda="SELECT ";
						$busqueda.="isonum, ";
						$busqueda.="nombre ";
						$busqueda.="FROM ";
						$busqueda.=$configuracion["prefijo"]."pais ";
						$busqueda.="ORDER BY nombre";
						
						$configuracion["ajax_function"]="xajax_pais";
						$configuracion["ajax_control"]="pais";
						
						$mi_cuadro=$html->cuadro_lista($busqueda,"pais",$configuracion,170,2,FALSE,$tab++,"pais");
						echo $mi_cuadro;
						?></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Departamento/Provincia/Estado
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
						<div id="divRegion"><?
						$busqueda="SELECT ";
						$busqueda.="id_localidad, ";
						$busqueda.="nombre ";
						$busqueda.="FROM ";
						$busqueda.=$configuracion["prefijo"]."localidad ";
						$busqueda.="WHERE ";
						$busqueda.="id_pais=170 ";
						$busqueda.="AND ";
						$busqueda.="tipo=1 ";
						$busqueda.="ORDER BY nombre";
						
						$configuracion["ajax_function"]="xajax_region";
						$configuracion["ajax_control"]="region";
						
						$mi_cuadro=$html->cuadro_lista($busqueda,"region",$configuracion,5,2,FALSE,$tab++,"region");
						echo $mi_cuadro;
						?></div>
						</td>
					</tr>							
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Ciudad
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
						<div id="divCiudad"><?
						
						$busqueda="SELECT ";
						$busqueda.="id_localidad, ";
						$busqueda.="nombre ";
						$busqueda.="FROM ";
						$busqueda.=$configuracion["prefijo"]."localidad ";
						$busqueda.="WHERE ";
						$busqueda.="id_pais=170 ";
						$busqueda.="AND ";
						$busqueda.="id_padre=5 ";						
						$busqueda.="AND ";
						$busqueda.="tipo=2 ";
						$busqueda.="ORDER BY nombre";						
						$mi_cuadro=$html->cuadro_lista($busqueda,'ciudad',$configuracion,1,0,FALSE,$tab++);
						echo $mi_cuadro;
						?></div>
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Correo Electr&oacute;nico
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input type='text' name='correo' size='40' maxlength='100' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Tel&eacute;fono
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input type='text' name='telefono' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="1"><br>Informaci&oacute;n Trabajo de Grado/Pasantia<hr class="hr_subtitulo"></td>
					</tr>
					<tr  valign="top" onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>T&iacute;tulo
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
						<textarea id='tituloTrabajo' name='tituloTrabajo' cols='50' rows='2' tabindex='<? echo $tab++ ?>' ></textarea>
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Director (Nombre Completo)
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
						<input type='text' name='directorTrabajo' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr align='center'>
						<td>
							<input type='hidden' name='action' value='<? echo $formulario ?>'>
							<input value="Enviar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $verificar; ?>){document.forms['<? echo $formulario?>'].submit()}else{false}"><br>								
						</td>
						<td align="center">
							<input name='cancelar' value='Cancelar' type="button" onclick="document.forms['<? echo $formulario?>'].submit()"><br>
						</td>
					</tr>
					<tr class="bloquecentralcuerpo">
						<td colspan="2" rowspan="1">
							Los campos marcados con <font color="red">*</font> deben ser diligenciados obligatoriamente.<br><br>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>						
<?
}



function corregir_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db)
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$datos="";
	$cadena_sql=sqlhtmlUsuario($configuracion, "registroBorrador");

	$registro=accesodbhtmlUsuario($acceso_db, $cadena_sql);
	
	if(is_array($registro))
	{
		htmlCorregir($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db, $registro,$cripto, $datos);	
	}
	
}


function confirmar_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db)
{

	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$datos="";
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
	$nueva_sesion=new sesiones($configuracion);
	$esta_sesion=$nueva_sesion->numero_sesion();
	
	$cadena_sql=sqlhtmlUsuario($configuracion, "registroBorrador");

	$registro=accesodbhtmlUsuario($acceso_db, $cadena_sql);
	
	if(is_array($registro))
	{
		htmlConfirmar($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db, $registro,$cripto, $datos);	
	}
	else
	{
		echo "Imposible mostrar los datos de Inscripci&oacute;n";
	}
	
}

/****************************************************************************************
*				HTML       						*
****************************************************************************************/


function htmlCorregir($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db, $registro,$cripto, $datos)
{
$contador=0;

?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $formulario?>'>
	<table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
		<tr>
			<td>
				<table class="formulario" align="center">
					<tr  class="bloquecentralencabezado">
						<td colspan="2">
							<p><span class="texto_negrita">Formulario para Correcci&oacute;n de Datos de Inscripci&oacute;n</span></p>
						</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="1"><br>Datos Personales<hr class="hr_subtitulo"></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>Nombres:
						</td>
						<td bgcolor="<? echo $tema->celda ?>">
							<input value="<? echo $registro[0][1] ?>" maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="nombre">
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>Apellidos:<br>
						</td>
						<td bgcolor="<? echo $tema->celda ?>">
							<input value="<? echo $registro[0][2] ?>" maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="apellido">
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Tipo de Documento
						</td>
						<td bgcolor='<? echo $tema->celda ?>'><?
						include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
						$html=new html();
						$busqueda="SELECT id_tipo,tipo FROM ".$configuracion["prefijo"]."tipo_documento ORDER BY id_tipo";
						$mi_cuadro=$html->cuadro_lista($busqueda,'id_tipo_documento',$configuracion,$registro[0][3],0,FALSE,$tab++);
						echo $mi_cuadro;
						?>	
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>No Identificaci&oacute;n
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input <?
							if($registro[0][8]=="Documento ya registrado")
							{
								echo "class='celdaAviso' ";
							} ?> value="<? echo $registro[0][4] ?>" type='text' name='identificacion' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="1"><br>Datos de Contacto<hr class="hr_subtitulo"></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Direcci&oacute;n
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input value="<? echo $registro[0][5] ?>" type='text' name='direccion' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Pa&iacute;s
						</td>
						<td bgcolor='<? echo $tema->celda ?>'><?
						include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
						$html=new html();
						$configuracion["ajax_function"]="xajax_pais";
						$configuracion["ajax_control"]="pais";
						$busqueda=sqlhtmlUsuario($configuracion, "paises");	
						$mi_cuadro=$html->cuadro_lista($busqueda,"pais",$configuracion, $registro[0][7],2,FALSE,$tab++,"pais");
						echo $mi_cuadro;
						?></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Departamento/Provincia/Estado
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
						<div id="divRegion"><?
						$configuracion["ajax_function"]="xajax_region";
						$configuracion["ajax_control"]="region";
						$busqueda=sqlhtmlUsuario($configuracion,"regiones",$registro[0][7]);
						$mi_cuadro=$html->cuadro_lista($busqueda,"region",$configuracion,$registro[0][13],2,FALSE,$tab++,"region");
						echo $mi_cuadro;
						?></div>
						</td>
					</tr>							
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Ciudad
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
						<div id="divCiudad"><?
						$valor[0]=$registro[0][7];
						$valor[1]=$registro[0][13];
						
						$busqueda=sqlhtmlUsuario($configuracion, "ciudades",$valor);
						$mi_cuadro=$html->cuadro_lista($busqueda,'ciudad',$configuracion,$registro[0][6],0,FALSE,$tab++);
						echo $mi_cuadro;
						?></div>
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							Correo Electr&oacute;nico
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input <? 
							if($registro[0][8]=="Correo ya registrado")
							{
								echo "class='celdaAviso' ";
							}
							?> value="<? echo $registro[0][8] ?>" type='text' name='correo' size='40' maxlength='100' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							Tel&eacute;fono
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input value="<? echo $registro[0][9] ?>" type='text' name='telefono' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="1"><br>Informaci&oacute;n Acad&eacute;mica<hr class="hr_subtitulo"></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							Formaci&oacute;n Acad&eacute;mica
						</td>
						<td bgcolor='<? echo $tema->celda ?>'><?
						$busqueda=sqlhtmlUsuario($configuracion, "formaciones");
						$mi_cuadro=$html->cuadro_lista($busqueda,'formacion',$configuracion,$registro[0][14],0,FALSE,$tab++);
						echo $mi_cuadro;
						?></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							Area de Desempeño
						</td>
						<td bgcolor='<? echo $tema->celda ?>'><?
						$busqueda=sqlhtmlUsuario($configuracion, "desempennos");
						$mi_cuadro=$html->cuadro_lista($busqueda,'areaDesempenno',$configuracion,$registro[0][15],0,FALSE,$tab++);
						echo $mi_cuadro;
						?></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							Instituci&oacute;n
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input value="<? echo $registro[0][16] ?>" type='text' name='institucion' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							Pa&iacute;s
						</td>
						<td bgcolor='<? echo $tema->celda ?>'><?
							$html=new html();
							$configuracion["ajax_function"]="xajax_pais";
							$configuracion["ajax_control"]="pais";
							$busqueda=sqlhtmlUsuario($configuracion, "paises");	
							$mi_cuadro=$html->cuadro_lista($busqueda,"paisFormacion",$configuracion, $registro[0][19],2,FALSE,$tab++,"pais");
							echo $mi_cuadro;
						?></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							Departamento/Provincia/Estado
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
						<div id="divRegionFormacion"><?
						
						$configuracion["ajax_function"]="xajax_regionFormacion";
						$configuracion["ajax_control"]="regionFormacion";
						$busqueda=sqlhtmlUsuario($configuracion,"regiones",$registro[0][19]);
						$mi_cuadro=$html->cuadro_lista($busqueda,"regionFormacion",$configuracion,$registro[0][18],2,FALSE,$tab++,"region");
						echo $mi_cuadro;
						?></div>
						</td>
					</tr>		
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							Ciudad
						</td>
						<td bgcolor='<? echo $tema->celda ?>'><div id="divCiudadFormacion"><?
						
						$valor[0]=$registro[0][19];
						$valor[1]=$registro[0][18];
						
						$busqueda=sqlhtmlUsuario($configuracion, "ciudades",$valor);
						$mi_cuadro=$html->cuadro_lista($busqueda,'ciudadFormacion',$configuracion,$registro[0][17],0,FALSE,$tab++);
						echo $mi_cuadro;
						?></div>
						</td>
					</tr>					
					<tr>
						<td colspan="2" rowspan="1"><br>Datos para la autenticaci&oacute;n<hr class="hr_subtitulo"></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>Usuario:<br>
						</td>
						<td bgcolor="<? echo $tema->celda ?>">
							<input <?
						if($registro[0][10]=="Usuario ya registrado")
							{
								echo " class='celdaAviso' ";
							}
						?> value="<? echo $registro[0][10] ?>" maxlength="50" size="30" tabindex="<? echo $tab++; ?>" name="usuario">
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>Clave:
						</td>
						<td bgcolor="<? echo $tema->celda ?>">
							<input maxlength="50" size="30" tabindex="<? echo $tab++; ?>" name="clave"  type="password">
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
						<font color="red">*</font>Reescriba la clave:<br>
						</td>
						<td bgcolor="<? echo $tema->celda ?>">
							<input maxlength="50" size="30" tabindex="<? echo $tab++; ?>" name="clave_2" type="password">
						</td>
					</tr>
					<tr align='center'>
						<td>
							<input type='hidden' name='action' value='<? echo $formulario ?>'>
							<input value="Enviar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $verificar; ?>){document.forms['<? echo $formulario?>'].submit()}else{false}"><br>								
						</td>
						<td align="center">
							<input name='cancelar' value='Cancelar' type="button" onclick="document.forms['<? echo $formulario?>'].submit()"><br>
						</td>
					</tr>
					<tr class="bloquecentralcuerpo">
						<td colspan="2" rowspan="1">
							Los campos marcados con <font color="red">*</font> deben ser diligenciados obligatoriamente.<br><br>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<?
}
function htmlConfirmar($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db, $registro, $cripto, $datos)
{

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");


?>
<form method="post" action="index.php" name="<? echo $formulario?>">
<table width="100%" cellpadding="12" cellspacing="0"  align="center">
	<tbody>
		<tr>
			<td align="center" valign="middle">
				<table style="width: 100%; text-align: left;" border="0" cellpadding="5" cellspacing="1" class=bloquelateral>
					<tbody>
						<tr class="bloquecentralencabezado">
							<td colspan="2" rowspan="1">Confirmar Datos de Suscripci&oacute;n</td>
						</tr>
						<tr>
							<td colspan="2" class="centrar">
							Si los datos mostrados son correctos por favor seleccione el bot&oacute;n <span class="texto_negrita">Aceptar</span> de lo contrario regrese al formulario y corrija.<br>
							El bot&oacute;n de Cancelar suspende el proceso de inscripci&oacute;n.<hr class="hr_subtitulo">
							</td>
						</tr>		
						<tr>
							<td>
								Nombre Completo:<br>
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][1]." ".$registro[0][2] ?>
							</td>
						</tr>
						<tr>
							<td><?
							$cadena_sql=sqlhtmlUsuario($configuracion, "tipoDocumento",$registro[0][3]);
							$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
							if(is_array($registro2))
							{
								echo cadenas::formatohtml($registro2[0][0])." ";
								unset($registro2);
							}
							else
							{
								echo "Identificaci&oacute;n";							}
							?></td>
							<td class="texto_negrita"><?
							echo $registro[0][4]." ";
							
							?></td>
						</tr>
						<tr>
							<td colspan="2" rowspan="1"><br>Datos de Contacto<hr class="hr_subtitulo"></td>
						</tr>
						<tr>
							<td>
								Direcci&oacute;n
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][5] ?>
							</td>
						</tr>
						<tr>
							<td>
								Pa&iacute;s/Depto/Ciudad
							</td>
							<td class="texto_negrita"><?
							
								$cadena_sql=sqlhtmlUsuario($configuracion, "pais",$registro[0][7]);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo $registro2[0][0]." / ";
									unset($registro2);
								}
								
								$valor[0]=$registro[0][7];
								$valor[1]=$registro[0][13];
								$cadena_sql=sqlhtmlUsuario($configuracion, "region",$valor);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo cadenas::formatohtml($registro2[0][0])." / ";
									unset($registro2);
								}
								
								$valor[0]=$registro[0][6];
								$valor[1]=$registro[0][13];
								$cadena_sql=sqlhtmlUsuario($configuracion, "ciudad",$valor);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo cadenas::formatohtml($registro2[0][0]);
									unset($registro2);
								}
								
							
							?></td>
						</tr>
						<tr >
							<td>
								Correo Electr&oacute;nico
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][8] ?>
							</td>
						</tr>
						<tr >
							<td>
								Tel&eacute;fono
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][9] ?>
							</td>
						</tr>
						<tr>
							<td colspan="2" rowspan="1"><br>Informaci&oacute;n Acad&eacute;mica<hr class="hr_subtitulo"></td>
						</tr>
						<tr >
							<td>
								Formaci&oacute;n Acad&eacute;mica
							</td>
							<td class="texto_negrita"><?
								
								$cadena_sql=sqlhtmlUsuario($configuracion, "formacion",$registro[0][14]);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo cadenas::formatohtml($registro2[0][0]);
									unset($registro2);
								}
							
							?></td>
						</tr>
						<tr>
							<td>
								Area de Desempe&ntilde;o
							</td>
							<td class="texto_negrita"><?
							
								$cadena_sql=sqlhtmlUsuario($configuracion, "desempenno",$registro[0][15]);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo cadenas::formatohtml($registro2[0][0]);
									unset($registro2);
								}
							
							?></td>
						</tr>
						<tr>
							<td>
								Instituci&oacute;n
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][16]; ?>
							</td>
						</tr>
						<tr>
							<td>
								Pa&iacute;s/Depto/Ciudad
							</td>
							<td class="texto_negrita"><?
							
								$cadena_sql=sqlhtmlUsuario($configuracion, "pais",$registro[0][19]);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo cadenas::formatohtml($registro2[0][0])." / ";
									unset($registro2);
								}
								else
								{
									echo "Imposible determinar.".$registro[0][19];
								}
								
								$valor[0]=$registro[0][19];
								$valor[1]=$registro[0][18];
								$cadena_sql=sqlhtmlUsuario($configuracion, "region",$valor);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo cadenas::formatohtml($registro2[0][0])." / ";
									unset($registro2);
								}
							
								$valor[0]=$registro[0][17];
								$valor[1]=$registro[0][18];
								$cadena_sql=sqlhtmlUsuario($configuracion, "ciudad",$valor);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo cadenas::formatohtml($registro2[0][0]);
									unset($registro2);
								}
							
							?></div>
							</td>
						</tr>					
						<tr>
							<td colspan="2" rowspan="1"><br>Datos para la autenticaci&oacute;n<hr class="hr_subtitulo"></td>
						</tr>
						<tr>
							<td>
								Usuario:
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][10] ?>
							</td>
						</tr>	
						<tr>
							<td colspan="2">
							<hr class="hr_subtitulo">
							</td>
						</tr>			
						<tr><?
								$datos.="&confirmacion=1";
								$datos.="&identificador=".$_REQUEST["identificador"];
								
								$datos=$cripto->codificar($datos,$configuracion);	
							
					?>	<td colspan="2">
							<table  style="width: 100%; text-align: left;" border="0" cellpadding="5" cellspacing="1" class=bloquelateral>
								<tr>
									<td  align='center'>
										<input type='hidden' name='formulario' value="<? echo $datos ?>">
										<input type='hidden' name='action' value='<? echo $formulario ?>'>
										<input value="Enviar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="document.forms['<? echo $formulario?>'].submit()">						
									</td>
									<td align="center">
										<input name='cancelar' value='Cancelar' type="button" onclick="document.forms['<? echo $formulario?>'].submit()">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
</form>
<?}

function sqlhtmlUsuario($configuracion, $opcion, $valor="")
{
	switch($opcion)
	{
	
		case "registroBorrador":
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`identificador`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`nombre`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`apellido`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`id_tipo_documento`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`identificacion`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`direccion`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`ciudad`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`pais`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`correo`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`telefono`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`usuario`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`clave`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`asociado`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.`region`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_formacion_borrador.`id_formacion`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_formacion_borrador.`id_area_desempenno`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_formacion_borrador.`institucion`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_formacion_borrador.`ciudad`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_formacion_borrador.`region`, ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_formacion_borrador.`pais` ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador, ".$configuracion["prefijo"]."registrado_formacion_borrador ";; 
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.identificador='".$_REQUEST["identificador"]."' ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."registrado_borrador.identificador=".$configuracion["prefijo"]."registrado_formacion_borrador.identificador ";
			$cadena_sql.="LIMIT 1";	
			//echo $cadena_sql;exit;
			break;
			
		case "tipoDocumento":
			$cadena_sql="SELECT ";
			$cadena_sql.="tipo ";
			$cadena_sql.="FROM ".$configuracion["prefijo"]."tipo_documento ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id_tipo=".$valor;			
			break;
		
		case "pais":
			$cadena_sql="SELECT ";
			$cadena_sql.="nombre ";
			$cadena_sql.="FROM ".$configuracion["prefijo"]."pais ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="isonum=".$valor;			
			break;
		
		case "paises":
			$cadena_sql="SELECT ";
			$cadena_sql.="isonum, ";
			$cadena_sql.="nombre ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."pais ";
			$cadena_sql.="ORDER BY nombre";
			break;
			
		case "region":
			$cadena_sql="SELECT ";
			$cadena_sql.="nombre ";
			$cadena_sql.="FROM ".$configuracion["prefijo"]."localidad ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id_pais=".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="id_localidad=".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="id_padre=0 ";
			$cadena_sql.="AND ";
			$cadena_sql.="tipo=1 ";
									
			break;
		
		case "regiones":
			$cadena_sql="SELECT ";
			$cadena_sql.="id_localidad, ";
			$cadena_sql.="nombre ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."localidad ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id_pais=".$valor." ";
			$cadena_sql.="AND ";
			$cadena_sql.="tipo=1 ";
			$cadena_sql.="ORDER BY nombre";
			break;
					
		case "ciudad":
			$cadena_sql="SELECT ";
			$cadena_sql.="nombre ";
			$cadena_sql.="FROM ".$configuracion["prefijo"]."localidad ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id_localidad=".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="id_padre=".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="tipo=2 ";									
			break;
		
		case "ciudades":
			$cadena_sql="SELECT ";
			$cadena_sql.="id_localidad, ";
			$cadena_sql.="nombre ";
			$cadena_sql.="FROM ".$configuracion["prefijo"]."localidad ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id_pais=".$valor[0]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="id_padre=".$valor[1]." ";
			$cadena_sql.="AND ";
			$cadena_sql.="tipo=2 ";	
			$cadena_sql.="ORDER BY nombre";								
			break;
			
		case "formacion":
			$cadena_sql="SELECT ";
			$cadena_sql.="tipo ";
			$cadena_sql.="FROM ".$configuracion["prefijo"]."formacion ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id_formacion=".$valor." ";
			$cadena_sql.="LIMIT 1 ";			
			break;
			
		case "formaciones":
			$cadena_sql="SELECT ";
			$cadena_sql.="id_formacion, ";
			$cadena_sql.="tipo ";
			$cadena_sql.="FROM ".$configuracion["prefijo"]."formacion ";
			$cadena_sql.="ORDER BY id_formacion ";			
			break;	
		
		
		case "desempenno":
			$cadena_sql="SELECT ";
			$cadena_sql.="nombre ";
			$cadena_sql.="FROM ".$configuracion["prefijo"]."area_desempenno ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id_area_desempenno=".$valor." ";
			$cadena_sql.="LIMIT 1 ";			
			break;
		
		case "desempennos":
			$cadena_sql="SELECT ";
			$cadena_sql.="id_area_desempenno, ";
			$cadena_sql.="etiqueta ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."area_desempenno ";
			$cadena_sql.="ORDER BY ";
			$cadena_sql.="id_area_desempenno";
			break;		
		default:
			$cadena_sql="error";
			break;
	}
	//echo $cadena_sql;
	return $cadena_sql;
}


function accesodbhtmlUsuario($acceso_db, $cadena_sql)
{
	$total=$acceso_db->registro_db($cadena_sql,0);
	if($total>0)
	{
		$registro=$acceso_db->obtener_registro_db();
		return $registro;
	}
	else
	{
		return false;
	}	
}
