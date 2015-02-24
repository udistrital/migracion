<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

fu_tipo_user(51);

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY topmargin="2">

<?php

	$asicod = $_GET['asicod'];
	require_once(dir_script.'NombreAsignatura.php');
	require_once(dir_script.'msql_est_asi_hor.php');
	
	
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

?>
  <table border="1" width="70%" align="center" cellspacing="0" cellpadding="0">
  <caption class="button"><?php echo $Asignatura.' - Grupo: '.$_GET['asigr']; ?></caption>
  <tr align="center" class="tr">
	<td>D&iacute;a</td>
    	<td>Hora</td>
    	<td>Sal&oacute;n</td>
	<td>Sede</td>
  </tr>
<?php
 $i=0; 
while(isset($registro[$i][0])){
     print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	 <td>'.$registro[$i][0].'</td>
   	 <td align="center">'.$registro[$i][1].'-'.$registro[$i][2].'</td> 
	 <td align="center">'.$registro[$i][3].'</td>
	 <td>'.$registro[$i][4].'</td></tr>'; 
         $i++;
  }

?>
</table>

</BODY>
</HTML>