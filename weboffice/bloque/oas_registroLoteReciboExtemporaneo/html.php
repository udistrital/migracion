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

Última revisión 24 de octubre de 2007

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Formulario para el registro de un archivo de bloques
* @usage        
*******************************************************************************/ 
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

$formulario="registro_lote_recibo";
$verificar="control_vacio(".$formulario.",'archivo')";

include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");

registrar_lote_recibo($configuracion,$tema,$fila,$tab,$formulario,$verificar);

/*==========================================================*/
/*                    Funciones                             */
/*==========================================================*/

function registrar_lote_recibo($configuracion,$tema,$fila,$tab,$formulario,$verificar)
{	
?><script src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
<form enctype='multipart/form-data' method="POST" action="index.php" name="<? echo $formulario?>" onsubmit="">
<table class="tablaMarco">
	<tbody>
		<tr>
			<td align="center" valign="middle">
				<table style="width: 100%; text-align: left;" border="0" cellpadding="6" cellspacing="0">
					<tr class="bloquecentralcuerpo">
						<td colspan="2" rowspan="1">
							<span class="encabezado_normal">Carga de registros por lotes. (Grupos de registros)</span>
							<hr class="hr_division">
						</td>		
					</tr>	
					<tr class="bloquecentralcuerpo">
						<td>
							<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="1">
								<tr class="bloquecentralcuerpo">
									<td>
										Archivo
									</td>
									<td>
										<input type="file" name="archivo" tabindex="<? echo $tab++ ?>">
									</td>
								</tr>
								<tr class="bloquecentralcuerpo">
									<td colspan="2">
										<hr class="hr_division">
									</td>									
								</tr>							
								<tr align="center">
									<td colspan="2">
										<table width="80%" align="center" border="0">
											<tr>
												<td align="center">
													<input type="hidden" name="action" value="registro_lote_recibo">
													<input type="submit" value="Aceptar" title="Aceptar" />
												</td>
												<td align="center">
													<input type="submit" value="Cancelar" title="Cancelar" />
												</td>
											</tr>
										</table>	
									</td>
								</tr>	
							</table>
						</td>	
					</tr>									
				</table>
			</td>
		</tr>							
	</tbody>
</table>
</form><?	
}

?>
