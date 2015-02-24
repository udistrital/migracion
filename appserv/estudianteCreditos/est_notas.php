<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_pie_pag.php');
include_once("../clase/multiConexion.class.php");

	fu_tipo_user(51);

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">

<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY>

<?php

	fu_cabezote("HISTÓRICO DE NOTAS");	

	$estcod = $_SESSION['usuario_login'];


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
  <div align="center">
  <table border="0" width="95%" cellspacing="0" cellpadding="2">
  <tr class="tr">
    <td align="center">C&oacute;digo</td>
	<td align="center">Asignatura</td>
    <td align="center">Sem</td>
	<td align="center">Año</td>
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
</table>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');
	$print = "javascript:popUpWindow('print_est_notas.php?estcod=$estcod', 'yes', 0, 0, 820, 650)";
	echo'<input type="submit" value="Imprimir Histórico de Notas" onClick="'.$print.'" style="cursor:pointer">';
 ?>
</div><br><br>

</BODY>
</HTML>
