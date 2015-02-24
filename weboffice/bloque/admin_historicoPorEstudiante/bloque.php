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
* @author      	Karen Palacios
* @link		N/D
* @description  Menu principal
* @usage        
*****************************************************************************/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
?>
<br>
<form enctype="multipart/form-data" method="POST" action="index.php" name="consulta_generados">

	<table align="center"  class="bloquelateral">
		<tbody>
                        <tr class="texto_subtitulo_gris"><td>Historico por Estudiante</td><tr>

                        <tr >
			<td width='90%'>
			<span class="bloquelateralcuerpo">Código:</span>
			<input type="text" size='10' name="estudiante"/>
			<input type="hidden" value="admin_generados" name="action"/>
			<input type="hidden" value="generadoEstudiante" name="opcion"/>
			<input type="button" onclick="document.forms['consulta_generados'].submit()" tabindex="2" name="consultar" value="Consultar"/><br/>								

			</td>

		</tr>

	</table>						

</form>	
