<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

fu_tipo_user(51);

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<HTML>
<HEAD><TITLE>Requisitos</TITLE>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?php
	$cod_consul = "SELECT req_cod,asi_nombre,req_sem
			FROM ACREQ, ACASI, ACEST 
			WHERE req_cod = asi_cod 
			AND req_cra_cod =".$_GET['cracod']."
			AND req_asi_cod =".$_GET['asicod']."
			AND req_estado = 'A'
			AND req_pen_nro=est_pen_nro
			AND est_cod=".$_SESSION['usuario_login']."
			ORDER BY req_cod";
				 	
				 	
	$registroReq=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
	//echo $cod_consul;
	
	if(!is_array($registroReq)) die('<center><h3>La asignatura no tiene requisitos.</h3></center>');

	$asicod = $_GET['asicod'];
	require_once(dir_script.'NombreAsignatura.php');

?>
  <p>&nbsp;</p>
  <table width="95%" border="1" align="center" cellspacing="0" cellpadding="3">
  <caption><? echo '<span class="Estilo5">REQUISITOS DE: '.$_GET['asicod'].' - '.$Asignatura; ?></span></caption>
  <tr class="tr">	
  <td align="center">C&oacute;digo</td>
  <td align="center">Nombre de la Asignatura</td>
  <td align="center">Sem.</td>
  </tr>
<?php
	$i=0;
	while(isset($registroReq[$i][0])){
	     echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		 <td align="right">'.$registroReq[$i][0].'</td>
	     <td align="left">'.$registroReq[$i][1].'</td>
		 <td align="left">'.$registroReq[$i][2].'</td></tr>';  
	$i++;	 
	}
?>
</table>
<p align="center"><input name="Bc" type="button" value="Cerrar" onClick="javascript:window.close()" style="width:120; cursor:pointer"></p>
</BODY>
</HTML>
