<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
?>
<html>
<head>
<title>Administración de Mensajes</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
fu_tipo_user(4);
fu_cabezote("PROYECTOS CURRICULARES");
require_once('msql_coor_carreras.php');
if($rows != 1) { header("Location: ../err/err_sin_registros.php"); exit;}

echo'<form name="LIS_CRA" method="POST" action="../generales/frm_envia_correo_grupo.php" target="lismsg">
<div align="center"><table border="0" width="400">
<tr><td width="320" align="right">
	<select size="1" name="cracod" style="font-size: 10pt; font-family: Tahoma">
	<option value="" selected>Seleccione el Proyecto Curricular, Haga clic en Consultar.</option>\n';
	do{
	   echo'<option value="'.OCIresult($qry_cra, 1).'">'.OCIresult($qry_cra, 1).'--'.OCIresult($qry_cra, 2).'</option>\n';
	}while(OCIFetch($qry_cra));
	echo'</select>
	</td><td width="80" align="left"><input type="submit" value="Consultar" name="B1"></td></tr>
</table></div></form>';
cierra_bd($qry_cra, $oci_conecta);
?>
</body>
</html>