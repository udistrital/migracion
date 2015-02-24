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
						<b>:.</b> Solicitud
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_solicitud_individual";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Solicitud Reintegros</a>
							
						</td>
					</tr>
					<!--tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_solicitud_individual";
							$variable.="&opcion=solicitudIndividual";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Solicitud Individual</a>
							
						</td>
					</tr-->  
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=registro_recibo_lote";
							$variable.="&accion=1";
							$variable.="&hoja=1";
							$variable.="&mostrar=nuevo";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Solicitud en Lote</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_solicitud_terminacion";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Terminaci&oacute;n de Materias</a>
							
						</td>
					</tr>	
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable='pagina=admin_solicitud_Extemporaneo';
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Extempor&aacute;neos</a>
							
						</td>
					</tr>															
				</table>
				<br>
				<table align="center" border="0" cellpadding="5" cellspacing="0" class="bloquelateral_2">
					<tr class="centralcuerpo">
						<td>
						<b>:.</b> Recibos
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_solicitud";
							$variable.="&hoja=1";
							$variable.="&opcion=bloqueado";
							$variable.="&accion=listaCompleta";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Bloqueados</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_recibo";
							$variable.="&hoja=1";
							$variable.="&opcion=solicitado";
							$variable.="&accion=listaCompleta";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Solicitados</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_generados";
							$variable.="&hoja=1";
							$variable.="&opcion=generado";
							$variable.="&accion=listaCompleta";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Generados</a>
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_recibo";
							$variable.="&hoja=1";
							$variable.="&opcion=generado";
							$variable.="&accion=listaCompleta";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Descargados</a>
						</td>
					</tr>
				</table>
				<br>
				<table align="center" border="0" cellpadding="5" cellspacing="0" class="bloquelateral_2">
					<tr class="centralcuerpo">
						<td>
						<b>:.</b> Centro de Control
						</td>
					</tr>					
					<tr class="bloquelateralcuerpo">
						<td>
							<a href="<?		
							$variable="pagina=admin_recibo";
							$variable.="&hoja=1";
							$variable.="&opcion=historico";
							$variable.="&accion=listaCompleta";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Hist&oacute;rico</a>
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
							<a href="<?		
							$variable="pagina=adminConsultaRecibos";
							$variable.="&hoja=1";
							$variable.="&opcion=consultaProyectos";
							$variable.="&aplicacion=Condor";
							$variable.="&nivel=A";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Env&iacute;o correos</a>
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
