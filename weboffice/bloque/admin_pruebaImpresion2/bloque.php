<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/****************************************************************************
* @name          bloque.php 
* @revision      Última revisión 2 de junio de 2007
*****************************************************************************
* @subpackage   admin_recibo
* @package	bloques
* @copyright    
* @version      0.3
* @link		N/D
* @description  Bloque principal para la administración de medicamentoes
*
******************************************************************************/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/codigoBarras.class.php");
	
$codigoBarras= new codigoBarras($configuracion);
	
con_registro_recibo($configuracion,$codigoBarras);



/****************************************************************
*  			Funciones				*
****************************************************************/



function con_registro_recibo($configuracion,$codigoBarras)
{
	$funcion1="FN";
	$ia1='415';
	$ia2='8020';
	$ia3='3900';
	$ia4='96';
	$codigoEstudiante='000000000001';//12 Digitos
	$codigoConsecutivo='000001';     //6 Digitos
	
	$codigoInstitucion='7709998000421'; //13 Digitos
	
	$valorPagar='0000124506';//10 Digitos
	$fechaPago='20080814'; 	//8 Digitos yyyymmdd
	
	
	//Detalles de la Composicion del Codigo de Barras para la Universidad
	echo "<table class='tabla_organizacion'>";
	echo "<tr>";
	echo "<td>";
	echo "Detalle del C&oacute;digo de Barras para la Universidad<hr class='hr_subtitulo'>";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>";
	echo "<table align='center' class='tabla_basico bloquecentralcuerpo'>";
	echo "<tr class='bloquecentralencabezado'><td class='centrar'>Campo</td><td class='centrar'>Longitud</td><td class='centrar'>Descripci&oacute;n</td><td>Ejemplo</td></tr>";
	echo "<tr><td>Inicio C</td><td></td><td>C&oacute;digo 106 de EAN/UCC128</td><td></td></tr>";
	echo "<tr><td>FUNCION1</td><td></td><td>C&oacute;digo 102 de EAN/UCC128</td><td></td></tr>";
	echo "<tr><td>IA1</td><td class='centrar'>3</td><td>415</td><td>415</td></tr>";
	echo "<tr><td>N&uacute;mero de Localizaci&oacute;n</td><td class='centrar'>13</td><td>7709998000421</td><td></td></tr>";
	echo "<tr><td>IA2</td><td class='centrar'>4</td><td>8020</td><td>8020</td></tr>";
	echo "<tr><td>Referencia No 1</td><td class='centrar'>12</td><td>C&oacute;digo del Estudiante</td><td>020032016021</td></tr>";
	echo "<tr><td>Referencia No 2</td><td class='centrar'>6</td><td>Identificaci&oacute;n del recibo</td><td>022207</td></tr>";
	echo "<tr><td>IA3</td><td class='centrar'>4</td><td>3900</td><td>3900</td></tr>";
	echo "<tr><td>Valor a pagar</td><td class='centrar'>10</td><td>Entero</td><td>0000324506</td></tr>";
	echo "<tr><td>IA4</td><td class='centrar'>2</td><td>96</td><td>96</td></tr>";
	echo "<tr><td>Fecha</td><td class='centrar'>8</td><td>AAAAMMDD</td><td>20080207</td></tr>";
	echo "</table>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	
	
	
	$ia2='8020';
	$ia3='3900';
	$ia4='96';
	$codigoEstudiante='020032016021';	//12 Digitos
	$codigoConsecutivo='022207';      	//6 Digitos
	$codigoInstitucion='7709998000421'; 	//13 Digitos
	$valorPagar='0000324506'; 		//10 Digitos
	$fechaPago='20080207';  		//8 Digitos yyyymmdd
	
	
	//Generar la imagen del codigo de barras
	$codigo=$funcion1;
	$codigo.=$ia1;
	$codigo.=$codigoInstitucion;
	$codigo.=$ia2;
	$codigo.=$codigoEstudiante;
	$codigo.=$codigoConsecutivo;
	$codigo.=$funcion1;
	$codigo.=$ia3;
	$codigo.=$valorPagar;
	$codigo.=$funcion1;
	$codigo.=$ia4;
	$codigo.=$fechaPago;
	
	
	generarCodigoBarras($codigoBarras, $codigo, $configuracion);
		
	?><table align="center">
	<tr class="centrar">
	<td class="centrar">
	<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["documento"].'/codigo.png'; ?>">
	</td>
	</tr>
	</table><?
	
}

function generarCodigoBarras($codigoBarras, $codigo, $configuracion)
{
	$codigoBarras->altoSimbolo(55);
	//$bar->setFont("arial");
	$codigoBarras->escalaSimbolo(0.5);
	$codigoBarras->colorSimbolo("#000000","#FFFFFF");

	
	$return = $codigoBarras->generar($codigo,'png',$configuracion["raiz_documento"].$configuracion["documento"]."/codigo");
	if($return==false)
	{
		$codigoBarras->error(true);
	}
}


?>