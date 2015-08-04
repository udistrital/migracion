<?PHP
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

fu_tipo_user(34);
?>
<HTML>
<HEAD>
<TITLE></TITLE>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/ventana.js"></script>
</HEAD>
<BODY>
</BODY> 
<?php
ob_start();
fu_cabezote("CONTROL DE DIGITACI&Oacute;N DE NOTAS");
$usuario = $_SESSION["usuario_login"];

include_once(dir_script.'class_nombres.php');
$NomCra = new Nombres;

require_once('coor_lis_desp_carrera.php');

if($_REQUEST['cracod']){
	 $cod_consul = "SELECT INP_ASI_COD,
			asi_nombre,
			INP_NRO,
			INP_NRO_INS,
			INP_PAR1,
			INP_PAR2,
			INP_PAR3,
			INP_PAR4,
			INP_PAR5,
			INP_EXA,
			INP_DEF
			FROM v_acinsnotpar,acasi
			WHERE asi_cod = inp_asi_cod
			AND inp_cra_cod = ".$_REQUEST['cracod']." ORDER BY 2,3";
	
	 $consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
	 
	 if(!is_array($consulta))
	 {
	 	echo "<script>location.replace('../err/err_sin_registros.php')</script>";	
	 	exit;
	 }
	 
	 
	$i=1;
	while(isset($row_cra[$i][0]))
	{
		$cracod=$row_cra[$i][0];
		
		if($cracod==$_REQUEST['cracod']){
			$nombrecarrera=$row_cra[$i][1];
		}
	$i++;
	}
?>
	<div align="center"><h3>PROYECTO CURRICULAR: <? print $nombrecarrera;?>
	<BR>PER&Iacute;ODO ACAD&Eacute;MICO <? print $ano.'-'.$per;?></h3></div>
	 <table width="95%" border="1" align="center" cellpadding="1" cellspacing="0">
	 <caption>Haga clic en el nombre de la asignatura para ver datos del docente responsable</caption>
  	 <tr class="tr">
 	 <td align="center">Asignatura</td>
 	 <td align="center">Grupo</td>
 	 <td align="center">Inscritos</td>
 	 <td align="center">Par1</td>
 	 <td align="center">Par2</td>
 	 <td align="center">Par3</td>
 	 <td align="center">Par4</td>
 	 <td align="center">Par5</td>
 	 <td align="center">Exa</td>
 	 <td align="center">Def</td>
     </tr>
<?php
	
	$i=0;
	while(isset($consulta[$i][0]))
	{
		//$VerDoc.$i = "javascript:popUpWindow('print_ver_doc_control_notas.php?a=".$consulta[$i][0]."&g=".$consulta[$i][2]."&c=".$_REQUEST['cracod']."', 'yes', 120, 400, 740, 20)";
		print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td align="left"><a href="#" onClick="javascript:popUpWindow(\'print_ver_doc_control_notas.php?a='.$consulta[$i][0]."&g=".$consulta[$i][2]."&c=".$_REQUEST['cracod'].'\', \'yes\', 100, 100, 600, 300)" onMouseOver="link();return true;" onClick="link();return true;" title="Ver docente responsable">'.$consulta[$i][1].'</a></td>
		<td align="center">'.$consulta[$i][2].'</td>
		<td align="center">'.$consulta[$i][3].'</td>
		<td align="center">'.$consulta[$i][4].'</td>
		<td align="center">'.$consulta[$i][5].'</td>
		<td align="center">'.$consulta[$i][6].'</td>
		<td align="center">'.$consulta[$i][7].'</td>
		<td align="center">'.$consulta[$i][8].'</td>
		<td align="center">'.$consulta[$i][9].'</td>
		<td align="center">'.$consulta[$i][10].'</td></tr>';
	$i++;
	}
}
?>
</table>
<p></p>
</BODY>
</HTML>