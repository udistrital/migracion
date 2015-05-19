<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(72);
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
  <td width="300" align="right" height="9" colspan="2">Perfil Asistente de Recursos Humanos</td></tr></table>
  <p></p>
  <table border="0" width="500" cellpadding="0">
    <tr>
      <td width="100%" align="center" height="9" colspan="2">
        <hr noShade class="hr">
      </td>
    </tr>
    <tr>
      <td width="67%" height="258" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
        <p align="justify" style="line-height: 100%">En esta p&aacute;gina usted podr&aacute;: Consultar y procesar informaci&oacute;n Correspondiente a la División de Recursos Humanos.</p>
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