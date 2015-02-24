<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<html>
<head>
<title>Aspirantes por a&ntilde;o y per&iacute;odo</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<?PHP 
fu_cabezote("POBLACI&Oacute;N DE ESTUDIANTES ACTIVOS POR SEXO"); 

$Anio = substr($_REQUEST['anoper'],0,4);
$Peri = substr($_REQUEST['anoper'],5,1);
$cont = 0;
print'<p>&nbsp;</p><p>&nbsp;</p><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
<caption>POBLACI&OACUTE;N DE ESTUDIANTES ACTIVOS POR SEXO</caption>
  <tr>
	<td>';
	require_once('esta_poblacion_activa_sexo.php');
	print'</td>
	
	<td>';
	require_once('gen_graf_esta_poblacion_activa_sexo.php');
	print'</td>
  </tr>
</table>
<p align="center"><input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:150" title="Clic par imprimir el reporte"></p>';
?>
</body>
</html>