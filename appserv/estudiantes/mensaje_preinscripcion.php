<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(51);
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<link href="../script/estinx.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
</head>
<body>

<?php
ob_start();
$usuario = $_SESSION['usuario_login'];
$carrera = $_SESSION['carrera'];

require_once(dir_script.'msql_est_estado.php');
//require_once(dir_script.'NumeroVisitas.php');
require_once('msql_est_consulta_msg.php');

echo'<br><div align="center">
<a href="#" onClick="javascript:popUpWindow(\'add_can.php\', \'no\', 100, 100, 350, 133)">Fechas de Adici&oacute;n y Cancelaci&oacute;n</a>
<p></p>
  <table border="0" width="500" cellpadding="0" cellspacing="2">
  <tr><td width="200" align="left" height="9" colspan="2" class="'.$Estilo.'">'.$Estado.'</td>
  <td width="300" align="right" height="9" colspan="2"><span class="Estilo7">Oficina asesora de Sistemas</span></td></tr></table>
<caption>&nbsp;</caption>
  <table border="0" width="500" cellpadding="0">
    <tr>
      <td width="100%" align="center" height="9" colspan="2">
	  <hr noshade class="hr">
      </td>
    </tr>';
	echo'<tr><td width="67%" height="258" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
        <p align="justify" style="line-height: 100%">En este momento el sistema no registra preinscripci&oacute;n de su PROYECTO CURRICULAR, por lo tanto la opci&oacute;n de adiciones y cancelaciones se habilitar&aacute; cuando este proceso se haya realizado.</p>
        
        <p align="justify" style="line-height: 100%">Para mayor informaci&oacute;n consulte con su PROYECTO CURRICULAR.</p>
		
        </td></tr>';

echo'<tr>
      <td width="100%" align="center" height="1">
        <hr noshade class="hr">
      </td>
    </tr>
	<tr>
	  <td width="100%" align="center" height="40">
	  <p align="center">Con el fin de evitar suplantaciones, la Oficina Asesora de Sistemas recomienda cambiar la clave periodicamente.</p>
	  </td>
	</tr>
  </table>
</div><p></p>';
fu_pie();
ob_end_flush();
?>
</body>
</html>
