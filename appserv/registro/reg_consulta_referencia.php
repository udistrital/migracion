<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(33);

fu_cabezote("CONSULTA DE PAGOS");
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body onLoad="this.document.conref.ref.focus()">

<form action="<? $_SERVER['PHP_SELF']?>" method="post" name="conref">
<table width="50%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div align="right">Nro. Referencia</div></td>
    <td><input type="text" name="ref" style="text-align:right"></td>
    <td><input name="Leer" type="submit" value="Consultar" title="Ejecutar Consulta" style="width:150; height:20;cursor:pointer"></td>
  </tr>
  
  <tr>
    <td><div align="right">Nro. Identificaci&oacute;n</div></td>
    <td><input type="text" name="doc" style="text-align:right"></td>
    <td><input name="Leer" type="submit" value="Consultar" title="Ejecutar Consulta" style="width:150; height:20;cursor:pointer"></td>
  </tr>
</table>
</form>

</center>

<?PHP	
if(!empty($_REQUEST['ref'])){
   require_once('msql_referencia.php');
   require_once('reg_tab_resultado.php');
}
elseif(!empty($_REQUEST['doc'])){
	   require_once('msql_identificacion.php');
	   require_once('reg_tab_resultado.php');
}
elseif(!empty($_REQUEST['ref']) && !empty($_REQUEST['doc'])){
	   die('<h3>Solo puede consultar un dato a la vez.</h3>');
	   exit;
}	
?>
</body>
</html>