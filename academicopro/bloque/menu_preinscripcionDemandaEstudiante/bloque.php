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
						<b>:.</b> Men&uacute;
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<br>
						<a href="<?		
							$variable="pagina=admin_consultarPreinscripcionDemandaEstudiante";
                                                        $variable.="&opcion=consultar";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
                                                        ?>"> Iniciar Pre-inscripci&oacute;n por Demanda</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<br>
						<a href="<?		
                                                        $variable="pagina=registro_reporteError";
                                                        $variable.="&opcion=mensajeError";;
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
                                                        ?>"> Reportar problema</a>
							
						</td>
					</tr>
					
					
					<tr class="bloquelateralcuerpo">
						<td>
						<br>
						<hr class="hr_subtitulo">
						<br>							
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>