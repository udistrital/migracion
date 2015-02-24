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
?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
	<tbody>
		<tr>
			<td >
				<table align="center" border="0" cellpadding="5" cellspacing="0" class="bloquelateral_2" width="100%">
					<tr class="menuHorizontal">	
						<td class="cuadro_color">
							Sec. Acad&eacute;mico
						</td>
					</tr>
					<tr class="menuHorizontal">
						<td class="cuadro_color">
							::.. Men&uacute; ..::
						</td>
					</tr>

					<tr class="bloquelateralcuerpo">
						<td>
						<a class="linkHorizontal" href="<?		
							$variable="pagina=sec_registro_recibo";
							$variable.="&accion=1";
							$variable.="&hoja=1";
							$variable.="&mostrar=nuevo";
							$variable.="&xajax=datos_basicos";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Agregar Solicitud </a>
							
						</td>

					</tr>
					<tr class="bloquelateralcuerpo">
					<td >.:Recibos Extemporaneos:.<br><hr></td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a class="linkHorizontal" href="<?		
							$variable="pagina=sec_recibos_en_solicitud";
							$variable.="&accion=listaExtemporaneos";
							$variable.="&hoja=1";
							$variable.="&opcion=solicitado";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Recibos en Solicitud </a>
							
						</td>

					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a class="linkHorizontal" href="<?		
							$variable="pagina=sec_registro_recibo_lote";
							$variable.="&accion=1";
							$variable.="&hoja=1";
							$variable.="&mostrar=nuevo";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Solicitud en Lote </a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a class="linkHorizontal" href="<?		
							$variable="pagina=sec_administar_recibo";
							$variable.="&accion=1";
							$variable.="&hoja=1";
							$variable.="&mostrar=lista";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Consolidados </a>
							
						</td>
					</tr>
	
					
					</table>
			</td>
		</tr>
	</tbody>
</table>
