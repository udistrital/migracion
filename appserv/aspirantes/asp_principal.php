<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_general.'msql_ano_per.php');
//require_once(dir_general.'asp_pie_pagAdm.php');
require_once(dir_general.'fecha_inscripcion.php');
//require_once(dir_general.'valida_usuario_prog.php');
require_once(dir_general.'valida_inscripcion.php');
include_once("../clase/multiConexion.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(50);

?>
<html>
<head>
<title>Aspirantes</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../script/BorraLink.js"></script>
</head>

<body>

<p align="center" class="Estilo6">PROCESO DE ADMISIONES<br><? print $periodo; ?></p>

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="3">
<caption><span class="Estilo3"><? print 'Fecha inicial: '.$FormFecIni.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Fecha final: '.$FormFecFin; ?></span>
</caption>
	<tr>
		<td width="50%" align="justify">
			<fieldset style="margin:3; padding:2">
			<p align="justify">Se&ntilde;or aspirante, es preciso leer cuidadosamente el <b><a href="../instructivo/index.php" target="_blank">instructivo</a></b> Oficial de Admisiones antes de realizar la inscripci&oacute;n. Tenga en cuenta que una vez grabada la informaci&oacute;n, esta no podr&aacute; ser modificada.</p>
		
			<p align="justify">Recuerde que el mal diligenciamiento del formulario de inscripci&oacute;n y/o el suministro de datos erroneos,  ser&aacute;n causal de la anulaci&oacute;n de la inscripci&oacute;n.</p>
		
			<p align="justify">Diligencie cuidadosamente la informaci&oacute;n solicitada  y cerci&oacute;rese de la exactitud de todos los datos consignados.</p>
		
			<p align="justify">Los datos consignados en el formulario ser&aacute;n guardados bajo la gravedad del juramento, y en el momento de grabar la informaci&oacute;n equivale a la firma de la inscripci&oacute;n.</p>
		
			<p align="justify">La informaci&oacute;n registrada a trav&eacute;s de la inscripci&oacute;n es confidencial y s&oacute;lo puede ser utilizada por la Universidad. Toda la informaci&oacute;n aqu&iacute; consignada est&aacute; sujeta a verificaci&oacute;n.</p>
			<p align="justify">Para continuar con la inscripci&oacute;n haga click en el men&uacute; que se encuentra ubicado en la parte izquierda de la pantalla, en la opci&oacute;n inscripci&oacute;n.</p>
			</fieldset>
		</td>
	</tr>
</table>

<p></p>
</fieldset>
<p></p>

</body>
</html>
