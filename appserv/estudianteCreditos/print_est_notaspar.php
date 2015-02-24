<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
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
	
	fu_print_cabezote("NOTAS PARCIALES");

	$estcod = $_SESSION['usuario_login'];
	$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=$estcod ","busqueda");
	$carrera = $registroCarrera[0][0];

	require_once(dir_script.'msql_notaspar_est.php');
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

	//---------------------------
	print'<br><p align="center">
	  <table width="100%" align="center" border="1" cellspacing="0">
	    <tr>
	      <td align="right">'.$registro[0][0].'</td>
	      <td><strong>'.htmlentities($registro[0][1]).'</strong></td>
	      <td align="right">Identificaci&oacute;n: </td>
	      <td>'.$registro[0][2].'</td>
	    </tr>
	    <tr>
	      <td align="right">'.$registro[0][3].'</td>
	      <td>'.UTF8_DECODE($registro[0][4]).'</td>
	      <td align="right">Promedio: </td>
	      <td>'.$registro[0][5].'</td>
	    </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
	      <td align="right"><b>Per&iacute;odo Acad&eacute;mico</b>:</td>
	      <td>'.$ano.'-'.$per.'</td>
	    </tr>
	  </table></p>';
?>


<table width="100%" align="center" border="1" cellspacing="0" cellpadding="2">
  <tr>
	<td width="30%" align="center" rowspan="2">Asignatura</td>
    <td width="4%" rowspan="2" align="center">Gr</td>
	<td width="8%" align="center" colspan="2" class="small">Parcial 1</td>
	<td width="8%" align="center" colspan="2" class="small">Parcial 2</td>
	<td width="8%" align="center" colspan="2" class="small">Parcial 3</td>
	<td width="8%" align="center" colspan="2" class="small">Parcial 4</td>
	<td width="8%" align="center" colspan="2" class="small">Parcial 5</td>
	<td width="8%" align="center" colspan="2" class="small">Parcial 6</td>
	<td width="8%" align="center" colspan="2" class="small">Lab</td>
	<td width="8%" align="center" colspan="2" class="small">Exa</td>
	<td width="8%" align="center" colspan="2" class="small">Hab</td>
	<td width="4%" align="center" rowspan="2" class="small">Acu</td>
	<td width="4%" align="center" rowspan="2" class="small">Def</td>
	<td width="4%" align="center" rowspan="2" class="small">Obs</td>
  </tr>
  <tr>
	<td width="4%" align="center" class="small">%</td>
	<td width="4%" align="center" class="small">Nota</td>
	<td width="4%" align="center" class="small">%</td>
        <td width="4%" align="center" class="small">Nota</td>
	<td width="4%" align="center" class="small">%</td>
        <td width="4%" align="center" class="small">Nota</td>
	<td width="4%" align="center" class="small">%</td>
        <td width="4%" align="center" class="small">Nota</td>
        <td width="4%" align="center" class="small">%</td>
        <td width="4%" align="center" class="small">Nota</td>
	<td width="4%" align="center" class="small">%</td>
	<td width="4%" align="center" class="small">Nota</td>
	<td width="4%" align="center" class="small">%</td>
	<td width="4%" align="center" class="small">Nota</td>
	<td width="4%" align="center" class="small">%</td>
	<td width="4%" align="center" class="small">Nota</td>
	<td width="4%" align="center" class="small">%</td>
	<td width="4%" align="center" class="small">Nota</td>
  </tr>
<?php
	$i=0;
	while(isset($registro[$i][0])){
		     print'<tr><td width="30%" align="left" bgcolor="#F4F5EB" class="small">'.htmlentities($registro[$i][7]).'</td> 
		         <td width="4%" align="center" bgcolor="#F4F5EB">'.$registro[$i][32].'</td>
			 <td width="4%" align="center" bgcolor="#F4F5EB" class="small">'.$registro[$i][9].'</td>
		         <td width="4%" align="right" bgcolor="#E4E5DB" class="Estilo13">'.$registro[$i][10].'</td>
			 <td width="4%" align="center" bgcolor="#F4F5EB" class="small">'.$registro[$i][11].'</td> 
		         <td width="4%" align="right" bgcolor="#E4E5DB" class="Estilo13">'.$registro[$i][12].'</td> 
			 <td width="4%" align="center" bgcolor="#F4F5EB" class="small">'.$registro[$i][13].'</td> 
			 <td width="4%" align="right" bgcolor="#E4E5DB" class="Estilo13">'.$registro[$i][14].'</td>
			 <td width="4%" align="center" bgcolor="#F4F5EB" class="small">'.$registro[$i][15].'</td> 
			 <td width="4%" align="right" bgcolor="#E4E5DB" class="Estilo13">'.$registro[$i][16].'</td>
			 <td width="4%" align="center" bgcolor="#F4F5EB" class="small">'.$registro[$i][17].'</td> 
			 <td width="4%" align="right" bgcolor="#E4E5DB" class="Estilo13">'.$registro[$i][18].'</td>
			 <td width="4%" align="center" bgcolor="#F4F5EB" class="small">'.$registro[$i][19].'</td> 
			 <td width="4%" align="right" bgcolor="#E4E5DB" class="Estilo13">'.$registro[$i][20].'</td>
			 <td width="4%" align="center" bgcolor="#F4F5EB" class="small">'.$registro[$i][21].'</td> 
			 <td width="4%" align="right" bgcolor="#E4E5DB" class="Estilo13">'.$registro[$i][22].'</td>
			 <td width="4%" align="center" bgcolor="#F4F5EB" class="small">'.$registro[$i][23].'</td> 
			 <td width="4%" align="right" bgcolor="#E4E5DB" class="Estilo13">'.$registro[$i][24].'</td>
			 <td width="4%" align="left" bgcolor="#F4F5EB" class="small">'.$registro[$i][25].'</td>
			 <td width="4%" align="right" bgcolor="#E4E5DB" class="Estilo13">'.$registro[$i][26].'</td>
			 <td width="4%" align="right" bgcolor="#F4F5EB" class="Estilo13">'.$registro[$i][27].'</td>
			 <td width="4%" align="right" bgcolor="#E4E5DB" class="Estilo13">'.$registro[$i][28].'</td>
			 <td width="4%" align="center" bgcolor="#F4F5EB">'.$registro[$i][29].'</td></tr>'; 
		$i++;
 	 }
?>
<tr><td colspan="22" align="right" style="font-size:9px">Dise&ntilde;o: Oficina Asesora de Sistemas</td></tr>
</table></div><br><br>
<?PHP require_once(dir_script.'msg_doc_no_valido.php');

?>
</BODY>
</HTML>
