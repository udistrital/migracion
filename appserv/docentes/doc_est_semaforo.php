<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'class_tiempo_carga.php'); 
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
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</HEAD>
<BODY>

<?php
fu_cabezote("PLAN DE ESTUDIO");

$estcod = $_REQUEST['estcod'];

require_once(dir_script.'msql_semaforo.php');
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
$row = $consulta;

$carrera = $consulta[0][3];

echo'<p>&nbsp;</p><table border="0" width="90%" align="center" cellspacing="0" cellpadding="2">
     <tr><td align="right">'.$consulta[0][0].'</td>
     <td align="left"><strong>'.$consulta[0][1].'</strong></td>
     <td align="right">Documento de Identidad: </td>
     <td align="left">'.$consulta[0][2].'</td></tr><tr>
  
     <td align="right">'.$consulta[0][3].'</td>
     <td align="left"><strong>'.$consulta[0][4].'</strong></td>
     <td align="right">Promedio: </td>
     <td align="left">'.$consulta[0][5].'</td></tr>
  
     <td align="right"></td>
     <td align="left"></td>
     <td align="right">Pensum: </td>
     <td align="left">'.$consulta[0][6].'</td></tr></table><p></p>';
?>
  <table border="0" width="90%" align="center" cellspacing="0" cellpadding="2">
  <tr class="tr">
    <td align="center">C&oacute;digo</td>
	<td align="center">Asignatura</td>
    <td align="center">Sem</td>
	<td align="center">Nota</td>
	<td align="center">Observaci&oacute;n</td>
  </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	 	<td align="right">
	 	 <a href="doc_est_requisito_asig.php?asicod='.$consulta[$i][7].'&cracod='.$carrera.'" onMouseOver="link();return true;" onClick="link();return true;" title="Requisitos de la asignatura">'.$consulta[$i][7].'</a></td>
	     <td>'.$consulta[$i][8].'</td>
         <td align="center">'.$consulta[$i][9].'</td>
	     <td align="center">'.$consulta[$i][10].'</td>
	     <td align="laft"><span class="Estilo3">'.$consulta[$i][11].'</span></td></tr>';
$i++;
}
?>
</table>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');
$tiempo = new getmicrotime;
print'<div align="center">';
echo $tiempo->vertiempo();
print'</div>';
?>
</BODY>
</HTML>