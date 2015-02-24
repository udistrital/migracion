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

$formulario="registro_inscripcion";
$verificar="control_vacio(".$formulario.",'nombre')";
$verificar.="&& control_vacio(".$formulario.",'apellido')";
$verificar.="&& control_vacio(".$formulario.",'correo')";
$verificar.="&& longitud_cadena(".$formulario.",'nombre',3)";
$verificar.="&& longitud_cadena(".$formulario.",'apellido',3)";
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
			case "verificar":
				verificar_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db);
				break;
			
			case "confirmar":
				confirmar_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db);
				break;		
			case "nuevo":
				nuevo_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab);
				break;
			case "editar":
				editar_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db);
				break;
			case "corregir":
				corregir_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db);
				break;
			case "mostrar":
				mostrar_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db);
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

function verificar_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db)
{
	
	if(isset($_REQUEST["identificador"]))
	{
		$cadena_sql=sqlhtmlUsuario($configuracion, "codigoUsuario",$_REQUEST["identificador"]);
		$registroUsuario=accesodbhtmlUsuario($acceso_db, $cadena_sql);
		if(is_array($registroUsuario))
		{
			
			$_REQUEST["identificador"]=$registroUsuario[0][0];
		}
	}
	else
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
		$esta_sesion=new sesiones($configuracion);
		$registroUsuario=$esta_sesion->rescatar_valor_sesion($configuracion,"identificacion");
		if(is_array($registroUsuario))
		{
			
			$_REQUEST["identificador"]=$registroUsuario[0][0];
		}
	}
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$cadena_sql=sqlhtmlUsuario($configuracion, "inscripcionGrado");
	$registro=accesodbhtmlUsuario($acceso_db, $cadena_sql);
	
	//Si el usuario ya existe
	if(is_array($registro))
	{
		mostrar_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db, $registro, $cripto, "actualizar");
	}
	else
	{
		nuevo_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab);
	}
	
//Si no existe muestra nuevo


}


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
					</tr><?  
					if(isset($_REQUEST['sinCodigo']))
					{
					?><tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>C&oacute;digo:<br>
						</td>
						<td bgcolor="<? echo $tema->celda ?>">
							<input maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="codigo"><br>
						</td>
					</tr><?
					}
					?><tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
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
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>Sexo:<br>
						</td>
						<td bgcolor="<? echo $tema->celda ?>">
							<input type="radio" name="sexo"  value="0">Femenino
							<input type="radio" name="sexo" value="1">Masculino
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
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Tel&eacute;fono Celular:
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input type='text' name='celular' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' >
						</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="1"><br>Informaci&oacute;n Trabajo de Grado/Pasantia<hr class="hr_subtitulo"></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>Tipo:<br>
						</td>
						<td bgcolor="<? echo $tema->celda ?>">
							<input type="radio" name="tipoTrabajo"  value="0" checked="checked"> Trabajo de Grado
							<input type="radio" name="tipoTrabajo" value="1"> Pasantia
						</td>
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
						<td colspan="2">
							<table class="tablaBase">
								<tr>
									<td align="center">
										<input type='hidden' name='action' value='<? echo $formulario ?>'>
										<input value="Enviar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $verificar; ?>){document.forms['<? echo $formulario?>'].submit()}else{false}"><br>								
									</td>
									<td align="center">
										<input name='cancelar' value='Cancelar' type="button" onclick="document.forms['<? echo $formulario?>'].submit()"><br>
									</td>
								</tr>
							</table>
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



function editar_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db)
{
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
	$esta_sesion=new sesiones($configuracion);
	$registroUsuario=$esta_sesion->rescatar_valor_sesion($configuracion,"identificacion");
	if(is_array($registroUsuario))
	{
		
		$_REQUEST["identificador"]=$registroUsuario[0][0];
	}
	$datos="";
	
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	
	$cadena_sql=sqlhtmlUsuario($configuracion, "inscripcionGrado");

	$registro=accesodbhtmlUsuario($acceso_db, $cadena_sql);
	
	if(is_array($registro))
	{
		htmlEditar($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db, $registro,$cripto, $datos);	
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
	
	$cadena_sql=sqlhtmlUsuario($configuracion, "inscripcionBorrador");

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


function htmlEditar($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db, $registro,$cripto, $datos)
{
$contador=0;

?><form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $formulario?>'>
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
							<input maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="nombre" value="<? echo $registro[0][1]?>"><br>
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>Apellidos:<br>
						</td>
						<td bgcolor="<? echo $tema->celda ?>">
							<input maxlength="80" size="40" tabindex="<? echo $tab++ ?>" name="apellido" value="<? echo $registro[0][2]?>">
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>Sexo:<br>
						</td>
						<td bgcolor="<? echo $tema->celda ?>"><?
							if($registro[0][3]==0)
							{?>
							<input type="radio" name="sexo"  value="0" checked>Femenino
							<input type="radio" name="sexo" value="1">Masculino
							<?}
							else
							{?>
							<input type="radio" name="sexo"  value="0">Femenino
							<input type="radio" name="sexo" value="1" checked>Masculino
							<?}
						
						?></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Tipo de Documento
						</td>
						<td bgcolor='<? echo $tema->celda ?>'><?
						include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");
						$html=new html();
						$busqueda="SELECT id_tipo,tipo FROM ".$configuracion["prefijo"]."tipo_documento ORDER BY id_tipo";
						$mi_cuadro=$html->cuadro_lista($busqueda,'id_tipo_documento',$configuracion,$registro[0][4],0,FALSE,$tab++);
						echo $mi_cuadro;
						?>	
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>No Identificaci&oacute;n
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input type='text' name='identificacion' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' value="<? echo $registro[0][5]?>">
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Lugar de Expedici&oacute;n
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input type='text' name='ciudadIdentificacion' size='30' maxlength='50' tabindex='<? echo $tab++ ?>'  value="<? echo $registro[0][6]?>">
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
							<input type='text' name='direccion' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="<? echo $registro[0][7]?>">
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
						
						$mi_cuadro=$html->cuadro_lista($busqueda,"pais",$configuracion,$registro[0][8],2,FALSE,$tab++,"pais");
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
						$busqueda.="id_pais=".$registro[0][8]." ";
						$busqueda.="AND ";
						$busqueda.="tipo=1 ";
						$busqueda.="ORDER BY nombre";
						
						$configuracion["ajax_function"]="xajax_region";
						$configuracion["ajax_control"]="region";
						
						$mi_cuadro=$html->cuadro_lista($busqueda,"region",$configuracion,$registro[0][9],2,FALSE,$tab++,"region");
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
						$busqueda.="id_pais=".$registro[0][8]." ";
						$busqueda.="AND ";
						$busqueda.="id_padre=".$registro[0][9]." ";						
						$busqueda.="AND ";
						$busqueda.="tipo=2 ";
						$busqueda.="ORDER BY nombre";						
						$mi_cuadro=$html->cuadro_lista($busqueda,'ciudad',$configuracion,$registro[0][10],0,FALSE,$tab++);
						echo $mi_cuadro;
						?></div>
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Correo Electr&oacute;nico
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input type='text' name='correo' size='40' maxlength='100' tabindex='<? echo $tab++ ?>' value="<? echo $registro[0][13]?>">
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Tel&eacute;fono
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input type='text' name='telefono' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' value="<? echo $registro[0][11]?>">
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Tel&eacute;fono Celular:
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
							<input type='text' name='celular' size='40' maxlength='50' tabindex='<? echo $tab++ ?>' value="<? echo $registro[0][12]?>">
						</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="1"><br>Informaci&oacute;n Trabajo de Grado/Pasantia<hr class="hr_subtitulo"></td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $fila ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $fila ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $fila++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor="<? echo $tema->celda ?>">
							<font color="red">*</font>Tipo:<br>
						</td>
						<td bgcolor="<? echo $tema->celda ?>"><?
							if($registro[0][16]==0)
							{?>
							
							<input type="radio" name="tipoTrabajo"  value="0" checked="checked"> Trabajo de Grado
							<input type="radio" name="tipoTrabajo" value="1"> Pasantia
							<?}
							else
							{?>
							
							<input type="radio" name="tipoTrabajo"  value="0" > Trabajo de Grado
							<input type="radio" name="tipoTrabajo" value="1" checked="checked"> Pasantia
							<?}
						?></td>
					</tr>
					<tr  valign="top" onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>T&iacute;tulo
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
						<textarea id='tituloTrabajo' name='tituloTrabajo' cols='50' rows='2' tabindex='<? echo $tab++ ?>' ><? echo $registro[0][14]?></textarea>
						</td>
					</tr>
					<tr  onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
						<td bgcolor='<? echo $tema->celda ?>'>
							<font color="red">*</font>Director (Nombre Completo)
						</td>
						<td bgcolor='<? echo $tema->celda ?>'>
						<input type='text' name='directorTrabajo' size='40' maxlength='255' tabindex='<? echo $tab++ ?>' value="<? echo $registro[0][15]?>">
						</td>
					</tr>
					<tr align='center'>
						<td colspan="2">
							<table class="tablaBase">
								<tr><?
								$datos.="&solicitud=".$registro[0][17];
								
								$datos=$cripto->codificar($datos,$configuracion);	
										
								?>	<td align="center">
										<input type='hidden' name='formulario' value="<? echo $datos ?>">
										<input type='hidden' name='action' value='<? echo $formulario ?>'>
										<input value="Enviar" name="aceptar" tabindex='<? echo $tab++ ?>' type="button" onclick="if(<? echo $verificar; ?>){document.forms['<? echo $formulario?>'].submit()}else{false}"><br>								
									</td>
									<td align="center">
										<input name='cancelar' value='Cancelar' type="button" onclick="document.forms['<? echo $formulario?>'].submit()"><br>
									</td>
								</tr>
							</table>
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
<table class="tablaMarcoGeneral">
	<tbody>
		<tr>
			<td align="center" valign="middle">
				<table style="width: 100%; text-align: left;" border="0" cellpadding="5" cellspacing="1" class="bloquecentralcuerpo">
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
								C&oacute;digo:<br>
							</td>
							<td class="texto_negrita">
								<? echo $_REQUEST["identificador"] ?>
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
							$cadena_sql=sqlhtmlUsuario($configuracion, "tipoDocumento",$registro[0][4]);
							$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
							if(is_array($registro2))
							{
								echo cadenas::formatohtml($registro2[0][0])." ";
								unset($registro2);
							}
							else
							{
								echo "Identificaci&oacute;n";
							}
							?></td>
							<td class="texto_negrita"><?
							echo $registro[0][5]." ";
							
							?></td>
						</tr>
						<tr>
							<td>
								Lugar de Expedici&oacute;n:<br>
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][6]?>
							</td>
						</tr>
						<tr>
							<td>
								Sexo:
							</td>
							<td class="texto_negrita"><?
							
							if($registro[0][4]==0)
							{
								echo "Femenino ";
							}
							else
							{
								echo "Masculino";
							}
							
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
								<? echo $registro[0][7] ?>
							</td>
						</tr>
						<tr>
							<td>
								Pa&iacute;s/Depto/Ciudad
							</td>
							<td class="texto_negrita"><?
							
								$cadena_sql=sqlhtmlUsuario($configuracion, "pais",$registro[0][8]);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo $registro2[0][0]." / ";
									unset($registro2);
								}
								
								$valor[0]=$registro[0][8];
								$valor[1]=$registro[0][9];
								$cadena_sql=sqlhtmlUsuario($configuracion, "region",$valor);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo cadenas::formatohtml($registro2[0][0])." / ";
									unset($registro2);
								}
								
								$valor[0]=$registro[0][10];
								$valor[1]=$registro[0][9];
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
								Tel&eacute;fono
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][11] ?>
							</td>
						</tr>
						
						<tr >
							<td>
								Tel&eacute;fono Celular
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][12] ?>
							</td>
						</tr>
						<tr >
							<td>
								Correo Electr&oacute;nico
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][13] ?>
							</td>
						</tr>
						<tr>
							<td colspan="2" rowspan="1"><br>Informaci&oacute;n Trabajo de Grado/Pasantia<hr class="hr_subtitulo"></td>
						</tr>
						<tr >
							<td>
								Trabajo de Grado
							</td>
							<td class="texto_negrita"><?
								echo $registro[0][14] 
								
							
							?></td>
						</tr>
						<tr>
							<td>
								Director
							</td>
							<td class="texto_negrita"><?
								echo $registro[0][15] 
							?>
							</td>
						</tr>
						<tr>
							<td>
								Tipo de Trabajo
							</td>
							<td class="texto_negrita">
								<? 
								if($registro[0][16]==0)
								{
									echo "Trabajo de Grado";
								}
								else
								{
									echo "Pasantia";
								} 
								?>
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
								if(isset($_REQUEST['sinCodigo']))
								{
									$datos.="&sinCodigo=1";
								}
								
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


function mostrar_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab,$acceso_db, $registro, $cripto, $datos)
{

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");


?>
<table class="tablaMarcoGeneral">
	<tbody>
		<tr>
			<td align="center" valign="middle">
				<table style="width: 100%; text-align: left;" border="0" cellpadding="5" cellspacing="1" class="bloquecentralcuerpo">
					<tbody>
						<tr class="bloquecentralencabezado">
							<td colspan="2" rowspan="1">Datos de Suscripci&oacute;n</td>
						</tr>
						<tr>
							<td colspan="2" class="centrar">
							<span class="texto_negrita">Inscripci&oacute;n a Grado No: <? echo $registro[0][17] ?></span><hr class="hr_subtitulo">
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
							$cadena_sql=sqlhtmlUsuario($configuracion, "tipoDocumento",$registro[0][4]);
							$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
							if(is_array($registro2))
							{
								echo cadenas::formatohtml($registro2[0][0])." ";
								unset($registro2);
							}
							else
							{
								echo "Identificaci&oacute;n";
							}
							?></td>
							<td class="texto_negrita"><?
							echo $registro[0][5]." ";
							
							?></td>
						</tr>
						<tr>
							<td>
								Lugar de Expedici&oacute;n:<br>
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][6]?>
							</td>
						</tr>
						<tr>
							<td>
								Sexo:
							</td>
							<td class="texto_negrita"><?
							
							if($registro[0][4]==0)
							{
								echo "Femenino ";
							}
							else
							{
								echo "Masculino";
							}
							
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
								<? echo $registro[0][7] ?>
							</td>
						</tr>
						<tr>
							<td>
								Pa&iacute;s/Depto/Ciudad
							</td>
							<td class="texto_negrita"><?
							
								$cadena_sql=sqlhtmlUsuario($configuracion, "pais",$registro[0][8]);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo $registro2[0][0]." / ";
									unset($registro2);
								}
								
								$valor[0]=$registro[0][8];
								$valor[1]=$registro[0][9];
								$cadena_sql=sqlhtmlUsuario($configuracion, "region",$valor);
								$registro2=accesodbhtmlUsuario($acceso_db, $cadena_sql);	
								if(is_array($registro2))
								{
									echo cadenas::formatohtml($registro2[0][0])." / ";
									unset($registro2);
								}
								
								$valor[0]=$registro[0][10];
								$valor[1]=$registro[0][9];
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
								Tel&eacute;fono
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][11] ?>
							</td>
						</tr>
						
						<tr >
							<td>
								Tel&eacute;fono Celular
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][12] ?>
							</td>
						</tr>
						<tr >
							<td>
								Correo Electr&oacute;nico
							</td>
							<td class="texto_negrita">
								<? echo $registro[0][13] ?>
							</td>
						</tr>
						<tr>
							<td colspan="2" rowspan="1"><br>Informaci&oacute;n Trabajo de Grado/Pasantia<hr class="hr_subtitulo"></td>
						</tr>
						<tr >
							<td>
								Trabajo de Grado
							</td>
							<td class="texto_negrita"><?
								echo $registro[0][14] 
								
							
							?></td>
						</tr>
						<tr>
							<td>
								Director
							</td>
							<td class="texto_negrita"><?
								echo $registro[0][15] 
							?>
							</td>
						</tr>
						<tr>
							<td>
								Tipo de Trabajo
							</td>
							<td class="texto_negrita">
								<? 
								if($registro[0][16]==0)
								{
									echo "Trabajo de Grado";
								}
								else
								{
									echo "Pasantia";
								} 
								?>
							</td>
						</tr>	
						<tr>
							<td colspan="2">
							<hr class="hr_subtitulo">
							</td>
						</tr><?
							if($datos=="actualizar")
							{
								$datos="&confirmacion=1";
								$datos.="&identificador=".$registro[0][0];
								
								$datos=$cripto->codificar($datos,$configuracion);	
								
						?>	
							<tr>
							<td colspan="2" class="centrar">
								<a href="<?		
								$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
								$variable="pagina=registro_inscripcionGrado";
								//Codigo del Estudiante
								$variable.="&opcion=editar";
								$variable.="&xajax=datos_basicos";
								//La pagina de incripcion utiliza ajax y registra la funcion 
								$variable.="&xajax=pais|region|paisFormacion|regionFormacion";
								$variable.="&xajax_file=inscripcion";
								$variable=$cripto->codificar_url($variable,$configuracion);
								echo $indice.$variable;		
								?>"> Editar Informaci&oacute;n</a>
							</td>
						</tr><?
						}
					?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<?}

function sqlhtmlUsuario($configuracion, $opcion, $valor="")
{
	switch($opcion)
	{
	
		case "inscripcionBorrador":
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.`codigo`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.`nombre`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.`apellido`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.`sexo`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDocumento.`tipo`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDocumento.`numero`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDocumento.`lugar`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`direccion`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`pais`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`region`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`ciudad`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`telefono`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`celular`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos.`correo`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorInscripcionGrado.`nombreTrabajo`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorInscripcionGrado.`director`, ";
			$cadena_sql.=$configuracion["prefijo"]."borradorInscripcionGrado.`tipoTrabajo` ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario, ".$configuracion["prefijo"]."borradorUsuarioDocumento, "; 
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuarioDatos, ".$configuracion["prefijo"]."borradorInscripcionGrado ";
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo='".$_REQUEST["identificador"]."' ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo=".$configuracion["prefijo"]."borradorUsuarioDocumento.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo=".$configuracion["prefijo"]."borradorUsuarioDatos.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."borradorUsuario.codigo=".$configuracion["prefijo"]."borradorInscripcionGrado.codigo ";
			$cadena_sql.="LIMIT 1";	
			//echo $cadena_sql;exit;
			break;
			
			
		case "inscripcionGrado":
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`codigo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`nombre`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`apellido`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`sexo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`tipo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`numero`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`lugar`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`direccion`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`pais`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`region`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`ciudad`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`telefono`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`celular`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`correo`, ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`nombreTrabajo`, ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`director`, ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`tipoTrabajo`, ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`idInscripcion` ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."usuario, ".$configuracion["prefijo"]."usuarioDocumento, "; 
			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos, ".$configuracion["prefijo"]."inscripcionGrado ";
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo='".$_REQUEST["identificador"]."' ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."usuarioDocumento.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."usuarioDatos.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."inscripcionGrado.codigo ";
			$cadena_sql.="ORDER BY ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.fecha DESC ";	
			$cadena_sql.="LIMIT 1";	
			//echo $cadena_sql;
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
			
		case "codigoUsuario":
			$cadena_sql="SELECT ";
			$cadena_sql.="codigo ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="idInscripcion=".$valor." ";
			$cadena_sql.="LIMIT 1";
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