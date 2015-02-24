<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(20);

ob_start();
fu_cabezote("USUARIOS DE C&Oacute;NDOR");

$UsuAct = "select cla_tipo_usu,usutipo_tipo,count(cla_codigo)
	from geclaves,geusutipo
	where usutipo_cod = cla_tipo_usu
	and cla_estado = 'A'
	group by cla_tipo_usu,usutipo_tipo
	order by cla_tipo_usu";
$rowasuact = $conexion->ejecutarSQL($configuracion,$accesoOracle,$UsuAct,"busqueda");
?>
<html>
<head>
<title>Usuarios</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/ventana.js"></script>
</head>
<body>
<p>&nbsp;</p>

<table width="40%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3" class="Estilo1" align="center">RELACI&Oacute;N DE USUARIOS ACTIVOS EN CONDOR</td>
  </tr>
  <tr class="tr">
    <td align="center">Tipo</td>
    <td align="center">Nombre</td>
    <td align="center">Total</td>
  </tr>
<?php
$i = 0;
while(isset($rowasuact[$i][0]))
{
  	print'<tr class="td">
		<td align="center">'.$rowasuact[$i][0].'</td>
		<td>'.$rowasuact[$i][1].'</td>
		<td align="right">'.$rowasuact[$i][2].'</td>
	</tr>';
	$tot = $i + $rowasuact[$i][2];
	$i++;
}
print'
<tr>
    <td colspan="2" align="right">Total de Usuarios:</td>
    <td align="right">'.$tot.'</td>
  </tr>
</table><p>&nbsp;</p>';

?>
</body>
</html>