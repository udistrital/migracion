<?PHP
function fu_ins_cabezote(){
	require_once('dir_relativo.cfg');
	require_once(dir_conect.'conexion.php');
	require_once(dir_conect.'cierra_bd.php');

	require_once('../../generales/capa_condor.html');
	require_once('../general/msql_ano_per.php');
	
	$esc  = '<img src="../../img/20cw03002.png" border="0" alt="Universidad Distrital Francisco José de Caldas">';
	$cdr = "<embed width='54' height='53' src='../../img/cdr.swf'>";
	
	print'<table border="0" width="100%" align="center">
	<tr bgcolor="#E6E6DE">
	   <td width="25%" align="center" valign="top">
	   <a href="http://www.udistrital.edu.co" target="_blank">'.$esc.'</a></td>
	   <td width="65%" valign="middle" align="center"><span class="StoryTitle">INSTRUCTIVO PARA EL PROCESO DE INSCRIPCIONES<br>
	   PROGRAMAS DE PREGRADO<br><br>'.$periodo.'</span></td>
	   <td width="10%" valign="middle" align="center" title="Descripción">'.$cdr.'<br>
	   <a class="CapLink" onClick="MostrarCapa(\'Cóndor\',120,580)" title="Haga clic para más información"><span class="CONDOR">CÓNDOR</span></a>
	   </td></tr></table>';
	require_once('menu.php');
}
?>