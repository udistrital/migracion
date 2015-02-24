<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
?>
<html>
<head>
<title>Administración de Mensajes</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body style="margin-top:0; margin-bottom:0 ">
<?php
fu_cabezote("PROYECTOS CURRICULARES");
require_once('../administracion/msql_carreras.php');
if($rows != 1) die('<center><h3>No hay registros para esta consulta.</h3></center>');

echo'<form name="LIS_CRA" method="POST" action="gen_est_activos.php" target="lismsg">
<div align="center"><table border="0" width="400">
<tr><td width="320" align="right">
	<select size="1" name="cracod" style="font-size: 10pt; font-family: Tahoma">
	<option value="" selected>Seleccione el Proyecto Curricular, Haga clic en Consultar.</option>\n';
	$Cra = OCIResult($qry_cra, 1);
	do{
	   echo'<option value="'.OCIResult($qry_cra, 1).'">'.OCIResult($qry_cra, 1).'--'.OCIResult($qry_cra, 2).'</option>\n';
	}while(OCIFetch($qry_cra));
	echo'</select>
	</td><td width="80" align="left"><input type="submit" value="Consultar" name="B1" style="cursor:pointer" title="Ejecutar la consulta"></td></tr>
</table></div></form>';

cierra_bd($qry_cra, $oci_conecta);
?>
</body>
</html>