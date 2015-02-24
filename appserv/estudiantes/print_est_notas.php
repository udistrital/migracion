<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

	fu_tipo_user(51);

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<HTML>
<HEAD>
<TITLE>Estudiante</TITLE>
<script language="JavaScript" src="../script/clicder.js"></script>
<link href="../script/print_estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY background="../img/dnvpt.gif">

<?php
ob_start();
fu_print_cabezote("HIST&Oacute;RICO DE NOTAS");

	$estcod = $_SESSION['usuario_login'];
	$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=$estcod ","busqueda");
	$carrera = $registroCarrera[0][0];


	require_once(dir_script.'msql_hisnotas.php');
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
	

	echo'<p>&nbsp;</p>
	<table border="0" align="center" width="95%" cellspacing="1" cellpadding="1">
	   <tr><td align="right">'.$registro[0][0].'</td>
	   <td align="left"><strong>'.$registro[0][1].'</strong></td>
	   <td align="right">Documento de Identidad:</td>
	   <td align="left">'.$registro[0][2].'</td></tr><tr>';

	echo'<td align="right">'.$registro[0][3].'</td>
	   <td align="left">'.$registro[0][4].'</td>
	   <td align="right">Promedio:</td>
	   <td align="left">'.$registro[0][5].'</td></tr></table>';
	   
?>
  <table border="1" width="98%"align="center" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center">C&oacute;digo</td>
	<td align="center">Asignatura</td>
    <td align="center">Sem</td>
	<td align="center">A&ntilde;o</td>
    <td align="center">Per</td>
    <td align="center">Nota</td>
    <td align="center">Observaciones</td>
  </tr>
<?php
	$i=0;
	while(isset($registro[$i][0])){
	     print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		 <td align="right">'.$registro[$i][6].'</td>
		 <td align="left">'.$registro[$i][7].'</td>
	         <td align="center">'.$registro[$i][9].'</td>
	         <td align="center">'.$registro[$i][10].'</td>
	         <td align="center">'.$registro[$i][11].'</td>
		 <td align="center">'.$registro[$i][12].'</td>
		 <td align="left">'.$registro[$i][13].'</td></tr>'; 
		 $i++;
	}
?>
<tr><td colspan="7" align="right" style="font-size:9px">Dise&ntilde;&oacute;: Oficina Asesora de Sistemas</td></tr>
</table>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');

?>
<br><br>
</BODY>
</HTML>
