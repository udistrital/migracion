<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
if(!$_REQUEST['tipo']){
    $_REQUEST['tipo']=$_SESSION['usuario_nivel'];
}

if($_REQUEST['tipo']==110){
    fu_tipo_user(110);
    $tipo=110; 
}elseif($_REQUEST['tipo']==114){
    fu_tipo_user(114);
    $tipo=114; 
}else{
fu_tipo_user(4);
    $tipo=4; 
}

?>
<html>
<head>
<title>Administraci&oacute;n de Mensajes</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
fu_cabezote("HORARIOS");

include(dir_script.'class_nombres.php');
$nom = new Nombres;

require_once('coor_lis_desp_carrera.php');

if($_REQUEST['cracod'])
{
   require_once('msql_coor_cursos.php');
   $rowCur = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCur,"busqueda");
	 
   echo'<form name="asi" method="POST" action="coor_cra_hor.php">
   <table border="0" width="400" align="center">
   <caption><span class="Estilo5">PROYECTO CURRICULAR: '.$nom->rescataNombre($_REQUEST['cracod'],"NombreCarrera").'<BR>GRUPOS PROGRAMADOS PARA EL PER&Iacute;ODO ACAD&Eacute;MICO '.$ano.'-'.$per.'</span></caption>
   <td width="320" align="center">
   <select size="1" name="asicod" style="font-size: 10pt; font-family: Tahoma">
   <option value="" selected>Seleccione la asignatura, Haga clic en Consultar.</option>\n';
  	$i=0;
	while(isset($rowCur[$i][0]))
	{
		echo'<option value="'.$rowCur[$i][0].'-'.$rowCur[$i][3].'-'.$rowCur[$i][2].'">'.$rowCur[$i][0].'--'.$rowCur[$i][1].'--'.$rowCur[$i][3].'</option>\n';
	$i++;
	}
   echo'</select>
   </td><td width="80" align="left"><input type="submit" value="Consultar" name="B1" style="cursor:pointer"></td></tr>
   </table>
   <input name="url_asi" type="hidden" value="url_asi">
   <input name="cracod" type="hidden" value="'.$_REQUEST['cracod'].'"></form>';
}

if($_REQUEST['asicod']){

   $cad = $_REQUEST['asicod'];
   $porciones = explode("-", $cad);
   $Asi = $porciones[0];
   $AsiNom = $nom->rescataNombre($Asi,"NombreAsignatura");
   
   $Grupo = $porciones[2];
   $idGrupo = $porciones[3];

   require_once('msql_coor_horario.php');
   $RowHor = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryHor,"busqueda");
   
   print'<br><br><br><br>
   <table width="600" border="1" align="center" cellpadding="2" cellspacing="0">
   <caption><span class="Estilo12">(Cód.:'.$Asi.'- Grupo:'.$Grupo.')    '.$AsiNom.' - '.$idGrupo.'</span></caption>
   <tr><td width="600" colspan="5" align="center"><span class="Estilo5">HORARIO</span></td></tr>
	<tr class="tr">
    <td width="70" align="center">D&iacute;a</td>
    <td width="80" align="center">Hora</td>
    <td width="250" colspan="2" align="center">Sal&oacute;n</td>
    <td width="200" align="center">Sede</td>
   </tr>';
   	$i=0;
	while(isset($RowHor[$i][0]))
	{
		print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td>'.$RowHor[$i][0].'</td>
		<td align="center">'.$RowHor[$i][5].'</td>
		<td width="35" align="center">'.$RowHor[$i][1].'</td>
		<td width="265">'.$RowHor[$i][2].'</td>
		<td>'.$RowHor[$i][3].'</td>
		</tr>';
	$i++;
	}
 print'</table>';
}
?>
</body>
</html>