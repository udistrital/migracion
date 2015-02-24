<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
index.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Menu principal
* @usage        
*****************************************************************************/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
$cripto=new encriptar();
?><table align="center" class="tablaMarcoLateral">
	<tbody>
		<tr>
			<td >
				<table align="center" border="0" cellpadding="5" cellspacing="0" class="bloquelateral_2">
					<tr class="centralcuerpo">
						<td>
						<b>::.</b> Men&uacute;
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=registro_blogdev";
							$variable.="&xajax=datos_basicos";
							$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
							$variable.="&xajax_file=blogdev";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Registrar Bit&aacute;cora</a>
							
						</td>
					</tr>	
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=registro_blogdev";
							$variable.="&opcion=mostrar";
							$variable.="&xajax=datos_basicos";
							$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
							$variable.="&xajax_file=blogdev";
							$variable.="&accion=listaCompleta";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Bit&aacute;coras registradas</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=registro_blogdev";
							$variable.="&opcion=generar";
							$variable.="&xajax=datos_basicos";
							$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
							$variable.="&xajax_file=blogdev";
							$variable.="&accion=buscarBitacora";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Consultar Bit&aacute;cora</a>
							
						</td>
					</tr>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<hr class="hr_subtitulo">							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<b>:.</b> Administraci&oacute;n de formularios
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<hr style="border:1px dotted khaki;" />
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=blog_objeto";
							$variable.="&xajax=datos_basicos";
							$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
							$variable.="&xajax_file=blogdev";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Crear objetos</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=blog_objeto";
							$variable.="&opcion=mostrar";
							$variable.="&xajax=datos_basicos";
							$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
							$variable.="&xajax_file=blogdev";
							$variable.="&accion=listaCompleta";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;			
							?>">Consultar objetos</a>
							
						</td>
					</tr>
					
					<tr class="bloquelateralcuerpo">
						<td>
						<hr style="border:1px dotted khaki;" />
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_blogdev";
							$variable.="&xajax=datos_basicos";
							$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
							$variable.="&xajax_file=blogdev";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Registrar aplicaciones</a>
							
						</td>
					</tr>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_blogdev";
							$variable.="&opcion=mostrar";
							$variable.="&xajax=datos_basicos";
							$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
							$variable.="&xajax_file=blogdev";
							$variable.="&accion=listaCompleta";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;			
							?>">Consultar aplicaciones</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<hr style="border:1px dotted khaki;" />
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=blog_clase_objetos";
							$variable.="&xajax=datos_basicos";
							$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
							$variable.="&xajax_file=blogdev";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Registrar clase de objetos</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=blog_clase_objetos";
							$variable.="&opcion=mostrar";
							$variable.="&xajax=datos_basicos";
							$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
							$variable.="&xajax_file=blogdev";
							$variable.="&accion=listaCompleta";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;			
							?>">Consultar clase de objetos</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<hr style="border:1px dotted khaki;" />
						</td>
					</tr>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=blog_tipo_objeto";
							$variable.="&xajax=datos_basicos";
							$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
							$variable.="&xajax_file=blogdev";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Registrar tipo de objetos</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=blog_tipo_objeto";
							$variable.="&opcion=mostrar";
							$variable.="&xajax=datos_basicos";
							$variable.="&xajax=clasedeObjeto|tipodeObjeto|Objeto";
							$variable.="&xajax_file=blogdev";
							$variable.="&accion=listaCompleta";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;			
							?>">Consultar tipo de objetos</a>
							
						</td>
					</tr>
					
					<tr class="bloquelateralcuerpo">
						<td>
						<hr class="hr_subtitulo">							
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>