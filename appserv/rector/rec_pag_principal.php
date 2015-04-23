<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(31);
$usuario = $_SESSION['usuario_login'];
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
require_once(dir_script.'NumeroVisitas.php');

echo'<div align="center"><br><br>
  <table border="0" width="500" cellpadding="0">
  <tr>
  <td width="200" align="left" height="9" colspan="2"></td>
  <td width="300" align="right" height="9" colspan="2"><span class="Estilo7">Visita N� '.$Nro.' de '.$Tot.' desde 28-Jun-2006</span></td></tr></table>
  <p></p>
  <table border="0" width="500" cellpadding="0">
    <tr>
      <td width="100%" align="center" height="9" colspan="2">
        <hr noshade class="hr">
      </td>
    </tr>
    <tr>
      <td width="67%" height="200" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
         <p align="justify" style="line-height: 100%">Si tiene m�s de un tipo de usuario como: (Decano, Coordinador � Docente),
		 haga clic en el usuario deseado, en la lista &quot;<span class="Estilo5">Cambiar a Usuario</span>&quot;.<br><br>
		 Si cambia su correo electr�nico, no olvide actualizarlo en la p�gina de 
         actualizaci�n de datos, haciendo clic en el men� &quot;Datos Personales&quot;.             

         <p style="line-height: 100%" align="justify">Cuando 
         actualice alguna informaci�n, no olvide grabar. La forma segura de salir de esta p&aacute;gina, 
         es haciendo 
clic en el hiperv&iacute;nculo &quot;<a href="../conexion/salir.php" target="_top"><strong>Salir</strong></a>&quot;. 
         <p style="line-height: 100%" align="justify">De 
         esta forma nos aseguramos que otras personas no puedan manipular sus 
         datos.
      </td>
    </tr>
    <tr>
      <td width="100%" align="center" height="1">
        <hr noshade class="hr">
      </td>
    </tr>
    <tr>
      <td width="100%" align="center" height="1">
	  <b>Por seguridad, cambie su clave con frecuencia!!</b></td>
    </tr>
  </table>
</div><br><br><br><br><br>';
fu_pie(); ?>
</body>
</html>