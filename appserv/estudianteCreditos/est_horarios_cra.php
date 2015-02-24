<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
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
</HEAD>
<BODY>

<?php

	$estcod = $_SESSION['usuario_login'];
	$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=$estcod ","busqueda");
	$carrera = $registroCarrera[0][0];
		
	$semestre = $HTTP_POST_VARS['semestre'];

	if($HTTP_POST_VARS['semestre'] == 0)
	   $nomsem = 'ELECTIVAS ';
	elseif($HTTP_POST_VARS['semestre'] == 1)
		   $nomsem = 'DE PRIMER SEMESTRE';
	elseif($HTTP_POST_VARS['semestre'] == 2)
		   $nomsem = 'DE SEGUNDO SEMESTRE';
	elseif($HTTP_POST_VARS['semestre'] == 3)
		   $nomsem = 'DE TERCER SEMESTRE';
	elseif($HTTP_POST_VARS['semestre'] == 4)
		   $nomsem = 'DE CUARTO SEMESTRE';
	elseif($HTTP_POST_VARS['semestre'] == 5)
		   $nomsem = 'DE QUINTO SEMESTRE';
	elseif($HTTP_POST_VARS['semestre'] == 6)
		   $nomsem = 'DE SEXTO SEMESTRE';
	elseif($HTTP_POST_VARS['semestre'] == 7)
		   $nomsem = 'DE SEPTIMO SEMESTRE';
	elseif($HTTP_POST_VARS['semestre'] == 8)
		   $nomsem = 'DE OCTAVO SEMESTRE';
	elseif($HTTP_POST_VARS['semestre'] == 9)
		   $nomsem = 'DE NOVENO SEMESTRE';
	elseif($HTTP_POST_VARS['semestre'] == 10)
		   $nomsem = 'DE DECIMO SEMESTRE';
	elseif($HTTP_POST_VARS['semestre'] == 11)
		   $nomsem = 'DE DECIMO PRIMER SEMESTRE';
	elseif($HTTP_POST_VARS['semestre'] == 12)
		   $nomsem = 'DE DECIMO SEGUNDO SEMESTRE';
		   


	require_once(dir_script.'msql_horarios.php');
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$HorCra,"busqueda");
	

	//echo $HorCra;

?>
  <table width="95%" border="0" align="center" cellpadding="2" cellspacing="1">
  <caption><samp class="Estilo1">HORARIO DE LAS ASIGNATURAS <? print $nomsem.'  :  '.$ano.'-'.$per; ?></samp></caption>
    <tr class="td">
      <td rowspan="2" align="center"><samp class="Estilo2">C&oacute;digo</samp></td>
      <td rowspan="2" align="center"><samp class="Estilo2">Nombre De La Asignatura</samp></td>
      <td rowspan="2" align="center"><samp class="Estilo2">Gru</samp></td>
      <td align="center" colspan="4"><samp class="Estilo1">HORARIO</samp></td>
    </tr>
    <tr class="td">
      <td align="center"><samp class="Estilo2">D&iacute;a</samp></td>
      <td align="center"><samp class="Estilo2">Hora</samp></td>
      <td align="center"><samp class="Estilo2">Sal&oacute;n</samp></td>
      <td align="center"><samp class="Estilo2">Sede</samp></td>
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
	      <td align="left">'.$registro[$i][7].'</td></tr>';
	      $i++;
}


?>
</table><br>
<?PHP 
$print = "javascript:popUpWindow('print_horarios.php?semestre=$semestre', 'yes', 0, 0, 790, 500)";
echo'<center><input type="submit" value="Imprimir horarios del '.$HTTP_POST_VARS['semestre'].' semestre" onClick="'.$print.'" style="cursor:pointer"></center>'; 
?>
<br>

</BODY>
</HTML>
