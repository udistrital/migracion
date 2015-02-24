<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);
?>
<html>
<head>
<title>Lista de Valores</title>
<link href="../script/estilo_ay.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
function RetornaValor(valor){
	var retorno = '<? echo $_REQUEST["httpR"] ?>';
	window.opener.document.forms[0].elements[retorno].value = valor;
	window.close();
}
</script>
</head>
<body>
<?php
if($_REQUEST['httpS']=="" || $_REQUEST['httpD']=="" || $_REQUEST['httpH']==""){
   print '<p align="justify" class="error">error:<br><br>Para consultar salones disponibles, antes debe seleccionar: el "d&iacute;a", la "hora" y la "sede".</p>'; exit;
}
$sedcod = $_REQUEST['httpS'];
require_once(dir_script.'msql_sed_nombre.php');

$cod_consul = "SELECT sal_cod, sal_descrip, sal_capacidad
  		FROM gesalon x
  		WHERE sal_sed_cod = ".$_REQUEST['httpS']."
    		AND sal_estado ='A'
		AND NOT EXISTS (SELECT hor_sed_cod,hor_sal_cod
                FROM achorario
                WHERE hor_sed_cod = x.sal_sed_cod
                AND hor_sal_cod = x.sal_cod
                AND hor_ape_ano = $ano
                AND hor_ape_per = $per
                AND hor_dia_nro = ".$_REQUEST['httpD']."
                AND hor_hora    = ".$_REQUEST['httpH'].")
    		ORDER BY sal_cod";
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

for ($i=0; $i<count($resultado);$i++)
{
	$registro[$i][1]=UTF8_DECODE($consulta[$i][1]);
}

echo '<table border="0" width="300" align="center">
<caption>SALONES DISPONIBLES DE LA SEDE</td></tr>
<tr><td align="center" colspan="3" width="300"><span class="Estilo1">'.$nom_sede.'</span></caption>
<tr class="tr"><td width="50" align="center"><span class="Estilo2">C&oacute;digo</span></td>
<td width="200" align="center"><span class="Estilo2">Descripci&oacute;n</span></td>
<td width="50" align="center"><span class="Estilo2">Cap.</span></td></tr>';
$i=0;
while(isset($consulta[$i][0]))
{
	$registro[$i][1]=UTF8_DECODE($consulta[$i][1]);
	echo'<tr>
   	<td width="50" align="right"><b>'.$consulta[$i][0].'</b></td>
   	<td width="200" align="left"><a href="javascript:RetornaValor('.$consulta[$i][0].')" title="C&oacute;digo del sal&oacute;n">'.$registro[$i][1].'</a></td>
   	<td width="50" align="center">'.$consulta[$i][2].'</td>
   	</tr>';
$i++;
}
?>
</table>
</body>
</html>