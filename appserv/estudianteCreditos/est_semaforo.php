<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'class_tiempo_carga.php'); 
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
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
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
</HEAD>
<BODY>

<?php

fu_cabezote("PLAN DE ESTUDIO");

	$estcod = $_SESSION['usuario_login'];
	$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=$estcod ","busqueda");
	$carrera = $registroCarrera[0][0];	



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
  <div align="center">
  <table border="0" width="95%" cellspacing="0" cellpadding="2">
  <tr class="tr">
    <td align="center">C&oacute;digo</td>
	<td align="center">Asignatura</td>
    <td align="center">Sem</td>
	<td align="center">Nota</td>
	<td align="center">Observaci&oacute;n</td>
  </tr>
<?php
	$i=0;
	while(isset($registro[$i][0])){
	     echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		 <td align="right">
		 <a href="#" onClick="javascript:popUpWindow(\'est_requisito_asig.php?asicod='.$registro[$i][7].'&cracod='.$carrera.'\', \'yes\', 100, 100, 600, 300)" onMouseOver="link();return true;" onClick="link();return true;" title="Requisitos de la asignatura">'.$registro[$i][7].'</a></td>
		 <td>'.$registro[$i][8].'</td>
		 <td align="center">'.$registro[$i][9].'</td>
		 <td align="center">'.$registro[$i][10].'</td>
		 <td align="laft"><span class="Estilo3">'.$registro[$i][11].'</span></td></tr>';
	$i++;	 
	}

?>
</table>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');
	$tiempo = new getmicrotime; 
	echo $tiempo->vertiempo();
	$print = "javascript:popUpWindow('print_est_semaforo.php?estcod=$estcod', 'yes', 0, 0, 850, 650)";
	echo'<br><input type="submit" value="Imprimir Plan de Estudio" onClick="'.$print.'" style="cursor:pointer">';
?>
</div><br><br>

</BODY>
</HTML>
