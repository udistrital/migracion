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

$this->nivel=$_REQUEST['nivel'];
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
						<b>:.</b> SaberPro
						</td>
					</tr>					
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=admin_inscripcion_ECAES";
							$variable.="&opcion=nuevo";
							$variable.="&nivel=".$this->nivel;
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Inscripci&oacute;n a SaberPro</a>
							
						</td>
					</tr>
                                        <?
                                        if($this->nivel==4){
                                        ?>
					<tr class="bloquelateralcuerpo">
						<td>

						<a href="<?		
							$variable="no_pagina=adminReportes";
							$variable.="&tipoUser=4";
							$variable.="&nivel=A";
							$variable.="&opcReporte=8";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;			
							?>"> Lista 70%</a>
							
						</td>
					</tr>
					
					
					
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable='no_pagina=admin_consultar_listado'; 
							$variable.='&opcion=InscritosECAES';
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Listado Inscritos </a>
							
						</td>
					</tr>
                                        <?
                                        }
                                        ?>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable='pagina=admin_recibos_ECAES'; //65
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Generar Recibos </a>
							
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
