<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 12 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		menu_coordinador
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
* @author			Paulo Cesar Coronado/
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
						<b>:.</b> Administraci&oacute;n de fechas admisiones
						</td>
					</tr>
					
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=adminAdmisiones";
							$variable.="&opcion=adminFechasInsRes";
							$variable.="&nivel=X";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Fechas inscripci&oacute;n y resultados</a>
							
						</td>
					</tr> 
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_impresionAdm";
							$variable.="&opcion=adminFechasRecibos";
							$variable.="&nivel=X";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Fechas de pago matr&iacute;cula</a>
							
						</td>
					</tr> 
					</tr> 
					<tr class="centralcuerpo">
						<td>
						<b>:.</b> Administraci&oacute;n instructivo
						</td>
					</tr>
					
					<tr class="bloquelateralcuerpo">
						<td>
						<hr class="hr_division">							
						</td>
					</tr>
					
				</table>
			</td>
		</tr>
	</tbody>
</table>
