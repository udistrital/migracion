<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(33);
?>
<html>
<head>
<link href="../script/estilo_menu.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../script/clicder.js"></script>
<script language="JavaScript" type="text/javascript" src="../script/BorraLink.js"></script>
<script language="JavaScript" type="text/javascript" src="../script/SlideMenu.js"></script>
<style type="text/css">
</style>
</head>

<body>
<br><br><br>
<p align="center"> 
<script language="javascript" type="text/javascript">
//Estos son los 3 niveles del men
//top = Main menus
//sub = Sub menus
//sub2 = SubSub menus

//makeMenu('TYPE','TEXT','LINK','TARGET', 'END (THE LAST MENU)')

//Menu 0 
makeMenu('top','Nueva Sesion')
  makeMenu('sub','Iniciar Nueva Sesion','../conexion/salir.php','_top')

//Ejecucin del men
onload=SlideMenuInit;
</script>
<br><br>
<br>
<br>
<br>
<br>
<br>
<br>
<table width="150" border="0" align="center" cellpadding="2" cellspacing="3">
  <tr>
    <td><p align="justify" class="Estilo1">Por su seguridad, este es un buen momento para cambiar la clave . </p>
      <p align="justify" class="Estilo1">Para poder disfrutar de las utilidades que le brinda el sistema de informaci&oacute;n C&oacute;ndor, por favor cambie la clave e inicie una nueva sesi&oacute;n.</p></td>
  </tr>
</table>
</body>
</html>