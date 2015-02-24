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
fu_cabezote("POBLACI&Oacute;N DE ADMITIDOS POR LOCALIDAD"); 

require_once('msql_ano_periodo.php');
$RowAnoPeriodo = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryAnoPeriodo,"busqueda");

print'<div align="center"><br>
  <span class="Estilo5">Seleccione el per&iacute;odo</span>
	<form name="PoblacionAsp" method="post" action="'. $_SERVER['PHP_SELF'] .'">
		<select name="anoper">';
				$AnoPer = $RowAnoPeriodo[0][0];
				$i=0;
				while(isset($RowAnoPeriodo[$i][0]))
				{
					echo'<option value="'.$RowAnoPeriodo[$i][0].'">'.$RowAnoPeriodo[$i][0].'</option>\n';
				$i++;
				}
				print'</select>
			<input type="submit" name="Button1" value="Consultar" style="cursor:pointer" title="Ejecutar la consulta">
	</form>
    <table width="478" border="0" align="center">
    <tr>
      <td><p style="line-height: 100%" align="justify">De acuerdo al per&iacute;odo seleccionado, se despliega la informaci&oacute;n del total de la poblaci&oacute;n de admitidos por localidad.
	  <br><br><b>Nota.</b> No est&aacute;n incluidos los admitidos de reintegro.</td>
    </tr>
  </table>
  <p></p>
</div><br>';

if(empty($_REQUEST['anoper']))
{
	$_REQUEST['anoper'] = $AnoPer;
}

if(!empty($_REQUEST['anoper']))
{
	$Anio = substr($_REQUEST['anoper'],0,4);
	$Peri = substr($_REQUEST['anoper'],5,1);
	$cont = 0;
		print'<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
		<caption>POBLACI&Oacute;N DE ADMITIDOS POR LOCALIDAD - PER&Iacute;ODO ACAD&Eacute;MICO '.$Anio.'-'.$Peri.'</caption>
		<tr>
			<td>';
			require_once('esta_poblacion_adm_localidad.php');
			print'</td>
	
			<td>';
			require_once('gen_graf_esta_poblacion_adm_localidad.php');
			print'</td>
		</tr>
		</table>
		<p align="center"><input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:150" title="Clic par imprimir el reporte"></p>';
}
?>
</body>
</html>