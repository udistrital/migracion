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

class funciones_registroInscripcionGrado extends funcionGeneral
{

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
		$this->accesoOracle=$this->conectarDB($configuracion,"oracle");
		
		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		
		//Datos de sesion
		
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		
		$this-<formulario="registro_inscripcion";
		$this->verificar="control_vacio(".$formulario.",'nombre')";
		$this->verificar.="&& control_vacio(".$formulario.",'apellido')";
		$this->verificar.="&& control_vacio(".$formulario.",'correo')";
		$this->verificar.="&& longitud_cadena(".$formulario.",'nombre',3)";
		$this->verificar.="&& longitud_cadena(".$formulario.",'apellido',3)";
		$this->verificar.="&& verificar_correo(".$formulario.",'correo')";
		
	}
	
	
	function nuevo_registro($configuracion,$conexion)
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
	
   	function editar_registro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
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

   	
    	function corregirRegistro()
    	{}
	
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
	
		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
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
	
	function confirmarInscripcion($configuracion ,$acceso_db,$enlace)
	{
		//Revisar si el identificador existe.
		//Pasar de la tabla borrador a la tabla definitiva... 
		//Si han cancelado entonces borrar borrador y redireccionar al indice...
		$cadena_sql=sqlRegistroUsuario($configuracion, "rescatarBorrador",$_REQUEST['identificador']);
		$campos=$acceso_db->registro_db($cadena_sql,0);
		$registro=$acceso_db->obtener_registro_db();
		if($campos>0)
		{
				$valor[0]=$registro[0][0];
				//Borrar registros anteriores TODO implementar UPDATE
				$cadena_sql=sqlRegistroUsuario($configuracion, "borrarUsuario",$valor);
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
				
				$cadena_sql=sqlRegistroUsuario($configuracion, "borrarUsuarioDatos",$valor);
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
				
				$cadena_sql=sqlRegistroUsuario($configuracion, "borrarUsuarioDocumento",$valor);
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
				
				$valor[0]=$registro[0][0];
				$valor[1]=$registro[0][1];
				$valor[2]=$registro[0][2];
				$valor[3]=$registro[0][3];
				$valor[4]=time();
				
				$cadena_sql=sqlRegistroUsuario($configuracion, "insertarUsuario",$valor);
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql); 
				
				unset($valor);
				$valor[0]=$registro[0][0];
				$valor[1]=$registro[0][7];
				$valor[2]=$registro[0][8];
				$valor[3]=$registro[0][9];
				$valor[4]=$registro[0][10];
				$valor[5]=$registro[0][11];
				$valor[6]=$registro[0][12];
				$valor[7]=$registro[0][13];
				$valor[8]=time();
				
				$cadena_sql=sqlRegistroUsuario($configuracion, "insertarUsuarioDatos",$valor);	
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);	
				
				unset($valor);
				$valor[0]=$registro[0][0];
				$valor[1]=$registro[0][5];
				$valor[2]=$registro[0][6];
				$valor[3]=$registro[0][4];
				$valor[4]=time();
				
				$cadena_sql=sqlRegistroUsuario($configuracion, "insertarUsuarioDocumento",$valor);	
				$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);	
				
				
				unset($valor);
				$valor[0]=$registro[0][0];
				$valor[1]=time();
				$valor[2]=$registro[0][14];
				$valor[3]=$registro[0][15];
				$valor[4]=$registro[0][16];
				$valor[5]=time();
				
				//Actualizar Inscripciones
				$cadena_sql=sqlRegistroUsuario($configuracion, "actualizarInscripcion",$valor);
				$resultado=$acceso_db->ejecutarAcceso($cadena_sql,"");
				
				$cadena_sql=sqlRegistroUsuario($configuracion, "insertarInscripcionGrado",$valor);	
				$resultado=$acceso_db->ejecutarAcceso($cadena_sql,"");	
				
				if($resultado==TRUE)
				{
					$ultimoInsertado=$acceso_db-> ultimo_insertado($enlace);
					$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador1",$valor);
					$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
					$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador2",$valor);
					$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
					$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador3",$valor);
					$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
					$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador4",$valor);
					$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
					
					redireccionarInscripcion($configuracion, "exitoInscripcion",$ultimoInsertado);
				}
				else
				{
					exit;
				}
		}
	}
	
	function enviar_correo($configuracion)
	{
	
		$destinatario=$configuracion["correo"];
		$encabezado="Nuevo Usuario ".$configuracion["titulo"];
		
		$mensaje="Administrador:\n";
		$mensaje.=$_REQUEST['nombre']." ".$_REQUEST['apellido']."\n";
		$mensaje.="Correo Electronico:".$_REQUEST['correo']."\n";
		$mensaje.="Telefono:".$_REQUEST['telefono']."\n\n";
		$mensaje.="Ha solicitado acceso a ".$configuracion["titulo"]."\n\n";
		$mensaje.="Por favor visite la seccion de administracion para gestionar esta peticion.\n";
		$mensaje.="_____________________________________________________________________\n";
		$mensaje.="Por compatibilidad con los servidores de correo, en este mensaje se han omitido a\n";
		$mensaje.="proposito las tildes.";
		
		$correo= mail($destinatario, $encabezado,$mensaje) ;
		
		
		$destinatario=$_REQUEST['correo'];
		$encabezado="Solicitud de Confirmacion ".$configuracion["titulo"];
		
		
		$mensaje="Hemos recibido una solicitud para acceder al portal web\n";
		$mensaje.=$configuracion["titulo"];
		$mensaje.="en donde se referencia esta direccion de correo electronico.\n\n";
		$mensaje.="Si efectivamente desea inscribirse a nuestra comunidad por favor seleccione el siguiente enlace:\n";	
		$mensaje="En caso contrario por favor omita el contenido del presente mensaje.";
		$mensaje.="_____________________________________________________________________\n";
		$mensaje.="Por compatibilidad con los servidores de correo en este mensaje se han omitido a\n";
		$mensaje.="proposito las tildes.";
		$mensaje.="_____________________________________________________________________\n";
		$mensaje.="Si tiene inquietudes por favor envie un correo a: ".$configuracion["correo"]."\n";
		
		$correo= mail($destinatario, $encabezado,$mensaje) ;
	
	
	}
	
	
	function nuevoUsuario($configuracion,$acceso_db, $accesoOracle)
	{
		//Verificar existencia del usuario 
		//$cadena_sql=sqlRegistroUsuario($configuracion, "datosEstudiante",$_REQUEST['registro']);	
		//$unUsuario=$accesoOracle->ejecutarAcceso($cadena_sql,"busqueda");
		$unUsuario[0]=1;
		if(is_array($unUsuario))
		{
			
			//Valores a ingresar
			if(isset($_REQUEST['codigo']))
			{
				$elUsuario=$_REQUEST['codigo'];
			}
			else
			{
				$elUsuario=$_REQUEST['registro'];
			}
			
			
			$valor[0]=$elUsuario;
			$valor[1]=$_REQUEST['nombre'];
			$valor[2]=$_REQUEST['apellido'];
			$valor[3]=$_REQUEST['sexo'];
			
			$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador1",$valor);
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
			$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador2",$valor);
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
			$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador3",$valor);
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
			$cadena_sql=sqlRegistroUsuario($configuracion, "eliminarBorrador4",$valor);
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
			
			$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorrador",$valor);
			//exit;
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
					
			unset($valor);
			$valor[0]=$elUsuario;
			$valor[1]=$_REQUEST['direccion'];
			$valor[2]=$_REQUEST['pais'];
			$valor[3]=$_REQUEST['region'];
			$valor[4]=$_REQUEST['ciudad'];
			$valor[5]=$_REQUEST['telefono'];
			$valor[6]=$_REQUEST['celular'];
			$valor[7]=$_REQUEST['correo'];
			
			$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorradorDatos",$valor);	
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);	
			
			unset($valor);
			$valor[0]=$elUsuario;
			$valor[1]=$_REQUEST['identificacion'];
			$valor[2]=$_REQUEST['ciudadIdentificacion'];
			$valor[3]=$_REQUEST['id_tipo_documento'];
			
			$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorradorDocumento",$valor);	
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);	
			
			
			unset($valor);
			$valor[0]=$elUsuario;
			$valor[1]=$_REQUEST['tituloTrabajo'];
			$valor[2]=$_REQUEST['directorTrabajo'];
			$valor[3]=$_REQUEST['tipoTrabajo'];
			
			$cadena_sql=sqlRegistroUsuario($configuracion, "insertarBorradorinscripcionGrado",$valor);	
			$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);	
			
			if($resultado==TRUE)
			{
				if(!isset($_REQUEST["admin"]))
				{
					//enviar_correo($configuracion);
					if(isset($_REQUEST['codigo']))
					{
					
						reset($_REQUEST);
						while(list($clave,$value)=each($_REQUEST))
						{
							unset($_REQUEST[$clave]);
								
						}
						redireccionarInscripcion($configuracion, "confirmacionCoordinador",$valor[0]);
					}
					else
					{
					
						reset($_REQUEST);
						while(list($clave,$value)=each($_REQUEST))
						{
							unset($_REQUEST[$clave]);
								
						}
						redireccionarInscripcion($configuracion, "confirmacion",$valor[0]);
					}
				}
				else
				{
					
					redireccionarInscripcion($configuracion,"administracion");		
					
				}
			}
			else
			{
				exit;
			}
		}
		else
		{
			echo "<table align=center><tr><td><h3>IMPOSIBLE GUARDAR EL FORMULARIO</h3></td></tr></table>";	
		}
	}
		
		
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
				
			case "confirmacion":
				$variable="pagina=confirmacionInscripcionGrado";
				$variable.="&opcion=confirmar";
				$variable.="&identificador=".$valor;
				break;
			
			case "confirmacionCoordinador":
				$variable="pagina=confirmacionInscripcionCoordinador";
				$variable.="&opcion=confirmar";
				$variable.="&sinCodigo=1";
				$variable.="&identificador=".$valor;
				break;
				
			case "corregirUsuario":			
				$variable="pagina=registroInscripcionCorregir";
				$variable.="&opcion=corregir";
				$variable.="&identificador=".$valor;
				break;
				
			case "exitoInscripcion":
				if(isset($_REQUEST['sinCodigo']))
				{
					$variable="pagina=exitoInscripcionSecretario";
				}
				else
				{
					$variable="pagina=exitoInscripcion";
				}
				
				$variable.="&identificador=".$valor;
				$variable.="&opcion=verificar";
				break;
				
			case "principal":
				$variable="pagina=index";
				break;
			
			
			
		}
		
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>"; 
		exit();
	}
	
	function usuarioAntiguo($configuracion,$acceso_db)
	{
		$valor=$_REQUEST['solicitud'];
		$cadena_sql=sqlRegistroUsuario($configuracion, "inscripcionGrado",$valor);	
		$acceso_db->registro_db($cadena_sql,0);
		$registro=$acceso_db->obtener_registro_db();
		$campos=$acceso_db->obtener_conteo_db();
		if($campos>0)
		{
		
			
			unset($valor);
			if($resultado==TRUE)
			{
				if(!isset($_REQUEST["admin"]))
				{
					enviar_correo($configuracion);
					reset($_REQUEST);
					while(list($clave,$valor)=each($_REQUEST))
					{
						unset($_REQUEST[$clave]);
							
					}
					
					redireccionarInscripcion($configuracion, "indice");
					
				}
				else
				{
					redireccionarInscripcion($configuracion,"administracion");
				}
			}
			else
			{
				
			}
							
							
		}
		else
		{
			echo "<h1>Error de Acceso</h1>Por favor contacte con el administrador del sistema.";				
		}
	}

	

?>

