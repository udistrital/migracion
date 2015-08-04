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
<!--link href="../script/estinx.css" rel="stylesheet" type="text/css"-->
<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY>

<?php

	fu_cabezote("NOTAS PARCIALES");

	$estcod = $_SESSION['usuario_login'];

	require_once(dir_script.'msql_notaspar_est.php');
        $registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
        //verifica registro de notas parciales del periodo actual, si no existe consulta del periodo anterior
        if(!$registro){
            $registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul_per_anterior,"busqueda");
        }
        $nombre=$registro[0][1];
	//---------------------------
	print'<br><br>
	  <table width="100%" align="center" border="0" cellspacing="0">
	    <tr>
	      <td align="right">'.$registro[0][0].'</td>
	      <td><strong>'. htmlentities($nombre).'</strong></td>
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
	      <td>'.$registro[0][30].'-'.$registro[0][31].'</td>
	    </tr>
	  </table>';
?>
<table width="100%" align="center" border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="#E4E5DB">
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
  <tr bgcolor="#E4E5DB">
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
		
		
		    echo '<tr><td width="30%" align="left" bgcolor="#F4F5EB" class="small">'.htmlentities($registro[$i][7]).'</td> 
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
  //cierra_bd($consulta,$oci_conecta);
?>
</table>
<?php 

	$print = "javascript:popUpWindow('print_est_notaspar.php?estcod=$estcod', 'yes', 0, 0, 850, 450)";
	print'<center><input type="submit" value="Imprimir Notas Parciales" onClick="'.$print.'" style="cursor:pointer"></center>'; ?>

<?php 
	$cod_consul = "SELECT NOB_COD, INITCAP(NOB_NOMBRE) FROM ACNOTOBS WHERE NOB_COD IN(0,1,3,10,11,18,19,20) ORDER BY NOB_COD";
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

	  
	print'<div align="right"><table width="23%" border="1">
		<tr class="tr"><td colspan="2" align="center">Observaciones de Notas (<b>Obs</b>)</td></tr>';
	$i=0;
	while(isset($registro[$i][0])){
	     print'<tr><td align="right">'.$registro[$i][0].'</td>
	     <td>'.$registro[$i][1].'</td></tr>';
		$i++;
 	}

	print'</table></div>';


?>
</BODY>
</HTML>
