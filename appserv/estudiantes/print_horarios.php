<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');

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
	
	fu_print_cabezote("CONSULTA DE HORARIOS");
	
	$estcod=$_SESSION['usuario_login'];
	$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=$estcod ","busqueda");
	$carrera = $registroCarrera[0][0];
	$semestre = $HTTP_GET_VARS['semestre'];

	if($HTTP_GET_VARS['semestre'] == 0)
	   $nomsem = 'ELECTIVAS ';
	elseif($HTTP_GET_VARS['semestre'] == 1)
		   $nomsem = 'DE PRIMER SEMESTRE';
	elseif($HTTP_GET_VARS['semestre'] == 2)
		   $nomsem = 'DE SEGUNDO SEMESTRE';
	elseif($HTTP_GET_VARS['semestre'] == 3)
		   $nomsem = 'DE TERCER SEMESTRE';
	elseif($HTTP_GET_VARS['semestre'] == 4)
		   $nomsem = 'DE CUARTO SEMESTRE';
	elseif($HTTP_GET_VARS['semestre'] == 5)
		   $nomsem = 'DE QUINTO SEMESTRE';
	elseif($HTTP_GET_VARS['semestre'] == 6)
		   $nomsem = 'DE SEXTO SEMESTRE';
	elseif($HTTP_GET_VARS['semestre'] == 7)
		   $nomsem = 'DE SEPTIMO SEMESTRE';
	elseif($HTTP_GET_VARS['semestre'] == 8)
		   $nomsem = 'DE OCTAVO SEMESTRE';
	elseif($HTTP_GET_VARS['semestre'] == 9)
		   $nomsem = 'DE NOVENO SEMESTRE';
	elseif($HTTP_GET_VARS['semestre'] == 10)
		   $nomsem = 'DE DECIMO SEMESTRE';
	elseif($HTTP_GET_VARS['semestre'] == 11)
		   $nomsem = 'DE DECIMO PRIMER SEMESTRE';
	elseif($HTTP_GET_VARS['semestre'] == 12)
		   $nomsem = 'DE DECIMO SEGUNDO SEMESTRE';

	require_once(dir_script.'msql_horarios.php');
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$HorCra,"busqueda");
	
?>
  <table width="95%" border="1" align="center" cellpadding="2" cellspacing="0" style="border-collapse:collapse">
    <tr>
      <td colspan="7" align="center">HORARIO DE LAS ASIGNATURAS <? print $nomsem.'  :  '.$ano.'-'.$per; ?></td>
    </tr>
    <tr>
      <td rowspan="2" align="center">C&oacute;digo</td>
      <td rowspan="2" align="center">Nombre De La Asignatura</td>
      <td rowspan="2" align="center">Gru</td>
      <td align="center" colspan="4">HORARIO</td>
    </tr>
    <tr>
      <td align="center">D&iacute;a</td>
      <td align="center">Hora</td>
      <td align="center">Sal&oacute;n</td>
      <td align="center">Sede</td>
    </tr>
<?php

	$i=0;
	while(isset($registro[$i][0])){
	 		 echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		      <td align="right">'.$registro[$i][1].'</td>
		      <td align="left">'.$registro[$i][2].'</td>
		      <td align="center">'.$registro[$i][3].'</td>
		      <td align="left">'.$registro[$i][5].'</td>
		      <td align="right">'.$registro[$i][9].'</td>
		      <td align="center">'.$registro[$i][6].'</td>
		      <td align="left">'.$registro[$i][8].'</td></tr>';
		      $i++;
	}


?>
<tr><td colspan="7" align="right" style="font-size:9px">Diseñ&oacute;: Oficina Asesora de Sistemas</td></tr>
</table><br>
</BODY>
</HTML>
