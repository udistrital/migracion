<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);
?>
<HTML>
<HEAD><TITLE>Observaciones de la evaluaci&oacute;n anterior</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script> 
</HEAD>
<BODY>

<?php   
fu_cabezote("OBSERVACIONES DE LA EVALUACION DOCENTE");

$QryAnio = "SELECT ape_ano,ape_per
	FROM acasperi
	WHERE ape_ano >= 2005
	AND trim(to_char(ape_ano,'9999'))||trim(to_char(ape_per,'9')) != '20051'
	AND ape_per != 2
	AND ape_estado NOT IN('A','X')
	ORDER BY 1 DESC";
	
$RowMes=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryAnio,"busqueda");

$a = $RowMes[0][0];
$p = $RowMes[0][1];

print'<center><form name="AnoPer" method="POST" action="'.$_SERVER['PHP_SELF'].'">
Seleccione un per&iacute;odo: <select size="1" name="Per">
<option value="" selected>Per&iacute;odos</option>';
$i=0;
while(isset($RowMes[$i][0]))
{
	echo'<option value="'.$RowMes[$i][0].'-'.$RowMes[$i][1].'">'.$RowMes[$i][0].'-'.$RowMes[$i][1].'</option>';
$i++;
}
print'</select>
<input type="submit" name="Submit" value="Consultar" style="cursor:pointer"></form></center>';
//cierra_bd($QryAnio,$oci_conecta);
$_REQUEST['Per']=(isset($_REQUEST['Per'])?$_REQUEST['Per']:'');
if(substr($_REQUEST['Per'],0,4)=="" || substr($_REQUEST['Per'],5,1)==""){
   $anio = $a;
   $peri = $p;
}else{
	  $anio = substr($_REQUEST['Per'],0,4);
	  $peri = substr($_REQUEST['Per'],5,1);
}

require_once('msql_observaciones.php');
$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
$registroCatedra=$conexion->ejecutarSQL($configuracion,$accesoOracle,$consultaCatedra,"busqueda");
?>
<p align="center">Las observaciones aqu&iacute; publicadas, son mostradas tal cual fueron digitadas por los estudiantes.</p>
  <table width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  <caption class="Estilo5">Observaciones hechas por los estudiantes en el proceso de evaluaci&oacute;n docente</caption>
  <tr>
    <td colspan="3" align="center"><b><span class="Estilo10">Per&iacute;odo Acad&eacute;mico Consultado:&nbsp;</span><? echo $registro[0][3].'-'.$registro[0][4];?></b></td>
    </tr>
  <tr class="tr">
    <td align="center">Asignatura</td>
	<td width="2%" align="center">#</td>
    <td width="60%" align="center">Observaciones</td>
  </tr>
<?php
$asiCod=(isset($asiCod)?$asiCod:'');
if(is_array($registro))
{
	$i=0;
	while(isset($registro[$i][0]))
	{
		$asiNom = $registro[$i][1];
		if($asiCod != $registro[$i][0])
		{
			$j=1;
			$asiNom = $registro[$i][1].' - Grupo '.$registro[$i][5];
		}
		else
		{
		$asiNom = "";
		}
		print '<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td valign="top" align="left">'.$asiNom.'</td>
		<td valign="top" align="center">'.$j.'</td>
		<td><p align="justify">'.ucfirst(strtolower($registro[$i][2])).'</p></td></tr>';
		$asiCod = $registro[$i][0];
		$j++;
		$i++;
	}
}
if(is_array($registroCatedra))
{
	$i=0;
	while(isset($registroCatedra[$i][0]))
	{
		$asiNom = $registroCatedra[$i][1];
		if($asiCod != $registroCatedra[$i][0])
		{
			$j=1;
			$asiNom = $registroCatedra[$i][1].' - Grupo '.$registroCatedra[$i][5];
		}
		else
		{
		$asiNom = "";
		}
		print '<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td valign="top" align="left">'.$asiNom.'</td>
		<td valign="top" align="center">'.$j.'</td>
		<td><p align="justify">'.ucfirst(strtolower($registroCatedra[$i][2])).'</p></td></tr>';
		$asiCod = $registroCatedra[$i][0];
		$j++;
		$i++;
	}
}
?>
</table>
<p></p>
</BODY>
</HTML>