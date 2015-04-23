<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'mensaje_error.inc.php');
fu_tipo_user(33);
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
  <td width="300" align="right" height="9" colspan="2"><span class="Estilo7">Visita No. '.$Nro.' de '.$Tot.' desde 28-Jun-2006</span></td></tr></table>
  <p></p>
  <table border="0" width="500" cellpadding="0">
    <tr>
      <td width="100%" align="center" height="9" colspan="2">
        <hr noshade class="hr">
      </td>
    </tr>
    <tr>
      <td width="67%" height="200">
         <p align="justify" style="line-height: 100%">Si tiene m&aacute;s de un tipo de usuario como: (Decano, Coordinador o Docente),
		 haga clic en el usuario deseado, en la lista &quot;<span class="Estilo5">Cambiar a Usuario</span>&quot;.<br><br>
		 Si cambia su correo electr&oacute;nico, no olvide actualizarlo en la p&aacute;gina de 
         actualizaci&oacute;n de datos, haciendo clic en el men&uacute; &quot;Datos Personales&quot;.             

         <p style="line-height: 100%" align="justify">Cuando 
         actualice alguna informaci&oacute;n, no olvide grabar. La forma segura de salir de esta p&aacute;gina, 
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
</div><br>';
if(isset($_REQUEST['error_login'])){
   $error=$_REQUEST['error_login'];
   echo"<center><font face='Tahoma' size='2' color='#FF0000'><b>$error_login_ms[$error]</b></font></center>";
}
?>
</body>
</html>