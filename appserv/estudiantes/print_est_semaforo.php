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
<BODY>

<?php

	fu_print_cabezote("PLAN DE ESTUDIO");

	$estcod = $_SESSION['usuario_login'];
	$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=$estcod ","busqueda");
	$carrera = $registroCarrera[0][0];
	
	
	
	$print = "javascript:popUpWindow('print_est_semaforo.php?estcod=$estcod', 'yes', 0, 0, 850, 650)";
	
	require_once(dir_script.'msql_semaforo.php');
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
	

	echo'<p>&nbsp;</p>
		<table border="0" width="95%" align="center" cellspacing="0" cellpadding="1">
	     <tr><td align="right">'.$registro[0][0].'</td>
	     <td align="left"><b>'.$registro[0][1].'</b></td>
	     <td align="right">Documento de Identidad:</td>
	     <td align="left">'.$registro[0][2].'</td></tr><tr>
	  
	     <td align="right">'.$registro[0][3].'</td>
	     <td align="left">'.$registro[0][4].'</td>
	     <td align="right">Promedio:</td>
	     <td align="left">'.$registro[0][5].'</td></tr>
	  
	     <td align="right"></td>
	     <td align="left"></td>
	     <td align="right">Pensum:</td>
	     <td align="left">'.$registro[0][6].'</td></tr></table>';
	?>



  <table border="1" width="95%" align="center" cellspacing="0" cellpadding="2" background="../img/dnvpt.gif">
  <tr>
    <td align="center">Código</td>
	<td align="center">Asignatura</td>
    <td align="center">Sem</td>
	<td align="center">Nota</td>
	<td align="center">Observación</td>
  </tr>
<?php
		$i=0;
		while(isset($registro[$i][0])){
		     echo'<tr><td align="right">'.$registro[$i][7].'</td>
			  <td>'.$registro[$i][8].'</td>
			  <td align="center">'.$registro[$i][9].'</td>
			  <td align="center">'.$registro[$i][10].'</td>
			  <td align="laft">'.$registro[$i][11].'</td></tr>';
		$i++;
		}
?>
<tr><td colspan="5" align="right" style="font-size:9px">Dise&ntilde;o: Oficina Asesora de Sistemas</td></tr>
</table>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');

?>

</BODY>
</HTML>
