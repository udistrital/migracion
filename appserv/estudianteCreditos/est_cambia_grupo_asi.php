<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('valida_http_referer.php');
//require_once('valida_adicion.php');
require_once('valida_estudiante_activo.php');
require_once('valida_estudiante_nuevo.php');
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
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</HEAD>
<BODY>

<?php

	if($_GET['asicod'] == "") die("<center><font face='Tahoma' size='3' color='#FF0000'><b>No tiene asignaturas inscritas.</font></center>");

	$asicod = $_GET['asicod'];
	$asigru_ant = $_GET['asigru_ant'];

	require_once(dir_script.'NombreAsignatura.php');
	$cod_consul = "SELECT EMH_ASI_COD, 
		   	EMH_NRO, 
			EMH_CUPO, 
			EMH_HORARIO 
			FROM v_acestmathorario
			WHERE EMH_ASI_COD = $asicod
			AND EHM_CRA_COD = (SELECT est_cra_cod FROM acest WHERE est_cod=".$_SESSION['usuario_login'].") 
			ORDER BY 2";

	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
	

	echo'<div align="center">
		 <table border="0" width="515" cellspacing="0" cellpadding="2">
		 <tr class="tr"><td width="500" colspan="2" align="center"><span class="Estilo1">CAMBIO DE GRUPO</span></td></tr>
	     <tr><td width="282" align="right"><span class="Estilo2">Período Académico:</span></td>
	     <td width="218" align="left"><span class="Estilo2">'.$ano.'-'.$per.'</span></td></tr>
		 <tr>
	       <td colspan="2" align="center" class="Estilo5">'.$Asignatura.'</td>
	       </tr>
		 </table></div>';
?>
  <div align="center">
  <table border="1" cellpadding="0" cellspacing="0" width="515">
    <tr bgcolor="#E4E5DB">
      <td width="97" align="center" rowspan="2">Cambio Gr.</td>
	  <td align="center" rowspan="2"></td>
      <td width="61" align="center" rowspan="2">Grupo</td>
      <td width="62" align="center" rowspan="2">Cupo Disponible</td>
      <td width="259" align="center" colspan="7">HORARIO</td>
    </tr>
    <tr bgcolor="#E4E5DB">
      <td width="37" align="center">LU</td>
      <td width="37" align="center">MA</td>
      <td width="37" align="center">MI</td>
      <td width="37" align="center">JU</td>
      <td width="37" align="center">VI</td>
      <td width="37" align="center">SA</td>
      <td width="37" align="center">DO</td>
    </tr>
<?php


	$i=0;
	while(isset($registro[$i][0])){
	   if($registro[$i][2]<=0){
		  $boton = '<input type="image" SRC="../img/g_okay.png" name="B1" alt ="No Hay Cupo" disabled>';
		  $cup_disp = '<font color="#FF0000"><b>'.$registro[$i][2].'</b></font>';
	   }
	   else{
			$boton = '<input type="image" SRC="../img/s_okay.png" name="B1" alt ="Cambiar" enabled>';
			$cup_disp = $registro[$i][2];
	   }
	   
	   print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	   <td width="97" align="center" height="10">
		   <form method="POST" action="prg_cambia_grupo.php" name="add">
		   '.$boton.'</td>
	   <td align="center" height="10">
		   <input name="asicod" type="hidden" value="'.$registro[$i][0].'">
		   <input name="asigru_ant" type="hidden" value="'.$_GET['asigru_ant'].'">
		   <input name="asigru_nue" type="hidden" value="'.$registro[$i][1].'">
		   <input name="estcod" type="hidden" value="'.$_SESSION['usuario_login'].'">
		   <input name="cupo" type="hidden" value="'.$registro[$i][2].'"></form></td>
	   
	   <td width="61" align="center">'.$registro[$i][1].'</td>
	   <td width="62" align="center">'.$cup_disp.'</td>
	   <td width="288" colspan="7" align="center">'.$registro[$i][3].'</td></tr>';
	  $i++; 
	}





?>
</table>
</div>
<?php 
print'<br>';

?>
</BODY>
</HTML>
