<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('valida_http_referer.php');
require_once('valida_adicion.php');
require_once('valida_estudiante_activo.php');
require_once('valida_estudiante_nuevo.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");


fu_tipo_user(51);

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
	
?>
<HTML>
<HEAD>
<TITLE>Estudiantes</TITLE>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY>

<?php
	fu_cabezote("ADICI&Oacute;N DE ASIGNATURAS");
	$estcod = $_SESSION['usuario_login'];

	$estcracod = OCIParse($oci_conecta, "SELECT est_cra_cod FROM acest WHERE est_cod = $estcod");
	
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$estcracod,"busqueda");
	
	
	$estcra = $registro[0][0];
	
	
	
	//consulta las asignaturas a adicionar.
	require_once('msql_consulta_asi.php');
	
	$registroAsignaturas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$asicod,"busqueda");
	//echo $asicod;

	echo'<p align="center"><span class="Estilo14">Periodo Acad&eacute;mico:</span> '.$ano.'-'.$per.'</p><p></p>';
		

		 
	echo'<center>Haga clic en el nombre de la asignatura para ver los grupos disponibles.</center>';
	echo'<table width="80%" border="1" align="center"">
		<tr class="tr">
	      <td width="10%" align="center">C&oacute;digo</td>
	      <td width="80%" align="center">Asignatura</td>
	      <td width="10%" align="center">Sem.</tr>';	 
	$i=0;
	while(isset($registroAsignaturas[$i][0])){
	   echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	   <td align="right">'.$registroAsignaturas[$i][0].'</td>
	   <td align="left"><a href="est_grupos_inscripcion.php?asicod='.$registroAsignaturas[$i][0].'&sem='.$registroAsignaturas[$i][2].'&pen='.$registroAsignaturas[$i][3].'" target="inferior" title="Asignaturas Disponibles">'.$registroAsignaturas[$i][1].'</a></td>
	   <td align="center">'.$registroAsignaturas[$i][2].'</td></tr>';
	$i++;
	}
	
	
	echo'</table><br>';

?>
</BODY>
</HTML>
