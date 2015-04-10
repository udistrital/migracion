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

fu_tipo_user(4);
if(isset($_REQUEST['usuarios_sesion'])){
session_name($usuarios_sesion);}
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
fu_cabezote("OBSERVACIONES DE LA EVALUACI&Oacute;N DOCENTE");

$QryAnio = "SELECT ape_ano,ape_per
	FROM acasperi
	WHERE ape_ano >= 2005
	AND ape_ano::text||ape_per::text != '20051'
	AND ape_per != 2
	AND ape_estado != 'X'
	ORDER BY 1 DESC";
$RowAnio = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryAnio,"busqueda");
$a = $RowAnio[0][0];
$p = $RowAnio[0][1];

//echo '<center><form name="AnoPer" method="POST" action="/appserv/coordinador/coor_doc_obsevaciones.php">		

print'<center><form name="AnoPer" method="POST" action="'.$_SERVER['PHP_SELF'].'">		
Seleccione un per&iacute;odo: <select size="1" name="Per">
<option value="" selected>Per&iacute;odos</option>';
$i=0;
while(isset($RowAnio[$i][0]))
{
	echo'<option value="'.$RowAnio[$i][0].'-'.$RowAnio[$i][1].'">'.$RowAnio[$i][0].'-'.$RowAnio[$i][1].'</option>';
$i++;
}
print'</select>
<input type="submit" name="Submit" value="Consultar"></form></center>';
$perio=isset($_REQUEST['Per'])?$_REQUEST['Per']:"";
if(substr($perio,0,4)=="" || substr($perio,5,1)==""){
   $anio = $a;
   $peri = $p;
}else{
	  $anio = substr($_REQUEST['Per'],0,4);
	  $peri = substr($_REQUEST['Per'],5,1);
}

if(isset($_REQUEST['HrefCCDoc'])) $_SESSION['ccfun'] = $_REQUEST['HrefCCDoc'];

include_once(dir_script.'class_nombres.php');
$NomDoc = new Nombres;

require_once('msql_doc_observaciones.php');
$registro = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
//echo "mmm".$consulta;
/*if(!is_array($registro))
{
	echo "No hay registros".$consulta."<br>";
}*/
?>
<p></p>
  <p align="center"><h3>DOCENTE: <? print $NomDoc->rescataNombre($_SESSION['ccfun'],"NombreCarera");?></h3></p>
  <p align="center" class="Estilo2">Las observaciones aqu&iacute; publicadas, son mostradas tal cual fueron digitadas por los estudiantes.</p>
  <table width="95%"  border="1" align="center" cellpadding="2" cellspacing="0">
  <caption class="Estilo5">Observaciones hechas por los estudiantes en el proceso de evaluaci&oacute;n docente</caption>
  <tr>
    <td colspan="3" align="center"><b><span class="Estilo10">Per&iacute;odo Acad&eacute;mico Consultado:&nbsp;</span><? print $registro[0][3].'-'.$registro[0][4];?></b></td>
    </tr>
  <tr class="tr">
    <td align="center">Asignatura</td>
	<td width="2%" align="center">#</td>
    <td width="60%" align="center">Observaciones</td>
  </tr>
<?php
$i=0;
while(isset($registro[$i][0]))
{
	$asiNom = $registro[$i][1];
	if($asiCod != $registro[$i][0])
	{
		$asiNom = $registro[$i][1].' - Grupo '.$registro[$i][5];
	}
	else
	{
		$asiNom = "";
	}
	
	print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td valign="top" align="left">'.$asiNom.'</td>
	<td valign="top" align="center">'.$i.'</td>
	<td><p align="justify">'.ucfirst(strtolower($registro[$i][2])).'</p></td></tr>';
	$asiCod = $registro[$i][0];
$i++;
}
?>
</table>
</BODY>
</HTML>