<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(24);
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body>
<?php
$usuario = $_SESSION['usuario_login'];
require_once(dir_script.'NumeroVisitas.php');

echo'<p>&nbsp;</p><div align="center">
  <table border="0" width="500" cellpadding="0" cellspacing="2">
  <tr><td width="200" align="left" height="9" colspan="2"></td>
  <td width="300" align="right" height="9" colspan="2"><span class="Estilo7">Visita No. '.$Nro.' de '.$Tot.' desde 28-Jun-2006</span></td></tr></table>
  <p></p>
  <table border="0" width="500" cellpadding="0">
    <tr>
      <td width="100%" align="center" height="9" colspan="2">
        <hr noShade class="hr">
      </td>
    </tr>
    <tr>
      <td width="67%" height="258" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
        <p align="justify" style="line-height: 100%">En esta p&aacute;gina usted podr&aacute;: Revisar su datos al igual que los de sus beneficiarios, ver su hist&oacute;rico de cargos, revisar sus novedades de n&oacute;mina, ver e imprimir el desprendible de pago y cambiar la clave.</p>
      
        <p align="justify" style="line-height: 100%">Con un efectivo control por parte de los usuarios, la informaci&oacute;n podr&aacute; ser completa y real, por lo que se sugiere que revise con especial cuidado y reporte a la Divisi&oacute;n de Recursos Humanos, cualquier inquietud o correcci&oacute;n que considere necesaria.</p>
		
		<p align="justify" style="line-height: 100%">La manera segura de salir de esta p&aacute;gina, es haciendo clic en el v&iacute;nculo &quot;<strong><a href="../conexion/salir.php" target="_top" onMouseOver="link();return true;" onClick="link();return true;" title="Salida segura">Salir/Salir de Esta P&aacute;gina</a></strong>&quot;. De esta forma nos aseguramos que otras personas no puedan ver sus datos.</p>
		
	  </td>
    </tr>
    <tr>
      <td width="100%" align="center" height="1">
        <hr noShade class="hr">
      </td>
    </tr>
    
  </table>
</div><br><br>';
?>
</body>
</html>