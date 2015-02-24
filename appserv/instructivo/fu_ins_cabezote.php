<?PHP
function fu_ins_cabezote(){
	require_once('dir_relativo.cfg');
	//require_once(dir_conect.'valida_pag.php');
	require_once('../generales/capa_condor.html');
	require_once(dir_general.'msql_ano_per.php');
	include_once("../clase/multiConexion.class.php");
		
	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion('default');
	
	print'<table border="0" width="100%" align="center">
	<tr bgcolor="#E6E6DE">
	    <td width="65%" valign="middle" align="center"><span class="StoryTitle">INSTRUCTIVO PARA EL PROCESO DE INSCRIPCIONES<br>
	   PROGRAMAS DE PREGRADO<br><br>'.$periodo.'</span></td>
	 </tr></table> <br>';
	 require_once('menu.php');
}
?>