<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Karen Palacios
* @revision      Última revisión 21 de enero de 2010
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		menu_coordinador
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
* @author			Karen Palacios/
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque que contiene los enlaces a las diferentes secciones del
*				modulo recibos de pago->coordinador
*
/*--------------------------------------------------------------------------------------------------------------------------*/

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
						<b>Men&uacute; Bloqueados</b>
						</td>
					</tr>	
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_solicitud";
							$variable.="&opcion=lote";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Desbloquear Recibos en Lote</a>
							
						</td>
					</tr>								
					<tr class="centralcuerpo">
						<td>
						<b>:::::::::::::::::::::::::::</b>
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=registro_recibo_lote";
							$variable.="&accion=1";
							$variable.="&hoja=1";
							$variable.="&mostrar=nuevo";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Ir al Inicio</a>
							
						</td>
					</tr>
					<tr class="centralcuerpo">
						<td>
						<b>:::::::::::::::::::::::::::</b>
						</td>
					</tr>
										
																			
				</table>
				
			</td>
		</tr>
	</tbody>
</table>
