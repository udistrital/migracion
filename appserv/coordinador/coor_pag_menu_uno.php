<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(4);
?>
<html>
<head>
<title>Clave</title>
<link href="../script/estilo_menu.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../script/clicder.js"></script>
<script language="JavaScript" type="text/javascript" src="../script/BorraLink.js"></script>
<script language="JavaScript" type="text/javascript" src="../script/SlideMenu.js"></script>
<style type="text/css">
<!--
.Estilo1 {
	color: #CC0000;
	font-weight: bold;
	font-family: Tahoma;
	font-size: 12px;
}
-->
</style>
</head>

<body>
<br><br><br>
<p align="center"> 
<script language="javascript" type="text/javascript">
//Variables de configuracin
between=27 //Espacios(pixel) entre menus y submenus
mainheight=25 //Espacios entre los item del men
subheight=21 //Espacios entre los item del submen
pxspeed=15 //Velocidad de animacin del men
timspeed=15 //Timer Velocidad de animacin
menuy=20 //Margen superior
menux=3 //The left placement of the menu

//Images del men
level0_regular="../img/level0_regular.png"
level0_round="../img/level0_round.png"
level1_regular="../img/level1_regular.png"
level1_round="../img/level1_round.png"
level1_sub="../img/level1_sub.png"
level1_sub_round="../img/level1_sub_round.png"
level1_round2="../img/level1_round2.png"
level2_regular="../img/level2_regular.png"
level2_round="../img/level2_round.png"

//Leave this line
preLoadBackgrounds(level0_regular,level0_round,level1_regular,level1_round,level1_sub,level1_sub_round,level1_round2,level2_regular,level2_round)

//Estos son los 3 niveles del men
//top = Main menus
//sub = Sub menus
//sub2 = SubSub menus

//makeMenu('TYPE','TEXT','LINK','TARGET', 'END (THE LAST MENU)')

//Menu 0 
makeMenu('top','Nueva Sesión')
  makeMenu('sub','Iniciar Nueva Sesión','../conexion/salir.php','_top')

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
    <td><p align="justify" class="clSlide Estilo1">Por su seguridad, este es un buen momento para cambiar la clave . </p>
      <p align="justify" class="clSlide Estilo1">Para poder disfrutar de las utilidades que le brinda el sistema de informaci&oacute;n C&oacute;ndor, por favor cambie la clave e inicie una nueva sesi&oacute;n.</p></td>
  </tr>
</table>
</body>
</html>