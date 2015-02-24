<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(31);
ob_start();
?>
<html>
<head>
<link href="../script/estilo_menu.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>

<body style="margin-top:0">
<p align="center"> 
<a href="rec_pag_menu.php" target="_self"><span class="link">Contraer el Menú</span></a>
<script>
//Variables de configuracin
between=24 //Espacios(pixel) entre menus y submenus
mainheight=24 //Espacios entre los item del menú
subheight=21 //Espacios entre los item del submen
pxspeed=15 //Velocidad de animacin del menú
timspeed=15 //Timer Velocidad de animacin
menuy=25 //Margen superior
menux=7 //Margen izquierda del menú

//Images del menú
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

//Estos son los 3 niveles del menú
//top = Main menus
//sub = Sub menus
//sub2 = SubSub menus

//makeMenu('TYPE','TEXT','LINK','TARGET', 'END (THE LAST MENU)')

//Menu 0 
makeMenu('top','Datos Personales')
	makeMenu('sub','Actualizar','rec_actualiza_dat.php','principal')
	
//Menu 1    
makeMenu('top','Aspirantes')
  makeMenu('sub','Proceso Actual','../generales/gen_inscritos_por_facultad.php','principal')
  makeMenu('sub','Por Año y Período','rec_asp_anoper.php','principal')
  makeMenu('sub','Población por Estrato','../estadistica/index_poblacion_asp_estrato.php','principal')
  makeMenu('sub','Población por Sexo','../estadistica/index_poblacion_asp_sexo.php','principal')
  makeMenu('sub','Población por Loc.','../estadistica/index_poblacion_asp_localidad.php','principal')
  
//Menu 2    
makeMenu('top','Admitidos') 
  makeMenu('sub','Por Año y Período','rec_adm_anoper.php','principal')
  makeMenu('sub','Población por Estrato','../estadistica/index_poblacion_adm_estrato.php','principal')
  makeMenu('sub','Población por Sexo','../estadistica/index_poblacion_adm_sexo.php','principal')
  makeMenu('sub','Población por Loc.','../estadistica/index_poblacion_adm_localidad.php','principal')

//Menu 3    
makeMenu('top','Est. Codificados') 
  makeMenu('sub','Por Año y Período','rec_codif_anoper.php','principal')
  
//Menu 4    
makeMenu('top','Estudiantes')
  makeMenu('sub','Activos','../generales/gen_est_abhl.php','principal')
  makeMenu('sub','Con Asignaturas Ins.','../generales/gen_fra_cuenta_est.php','principal')
  makeMenu('sub','Activos por Estrato','../estadistica/index_poblacion_activa_estrato.php','principal')
  makeMenu('sub','Activos por Sexo','../estadistica/index_poblacion_activa_sexo.php','principal')

//Menu 5    
makeMenu('top','Evaluación Doc.')
  makeMenu('sub','Resultados','../informes/rresultados_uni_prom_20061.pdf','principal')

//Menu 6
makeMenu('top','Servicios')
  makeMenu('sub','Accesos a Cóndor','../generales/gen_uso_condor.php','principal')
  makeMenu('sub','Calendario Académico','<?echo $CalAcad?>','principal')
  makeMenu('sub','Derechos Pecuniarios ','../generales/derechos_pecuniarios.php','principal')
  makeMenu('sub','Estatuto Estudiantil','../generales/estaturo_est.pdf','principal')
  makeMenu('sub','Trabajos de Grado','../generales/gen_fac_trabgrado.php','principal')
  
//Menu 7
makeMenu('top','Estadísticas')
  makeMenu('sub','Accesos a Cóndor','../estadistica/esta_uso_condor.php','principal')
  makeMenu('sub','Deserción','../estadistica/index_desercion.php','principal')
  makeMenu('sub','Funcionarios','../estadistica/index_tot_empleados.php','principal')
  makeMenu('sub','Proy. Curriculares','../estadistica/esta_tot_proyectos.php','principal')
   
//Menu 8
makeMenu('top','Clave')
  makeMenu('sub','Cambiar mi Clave','../generales/cambiar_mi_clave.php','principal')

//Menu 9
makeMenu('top','Salir')
  makeMenu('sub','Salir de Esta Página','../conexion/salir.php','_top')
  makeMenu('sub','Contraer el Menú','rec_pag_menu.php','_self')

//Ejecucin del menú
onload=SlideMenuInit;
</script>
<?
echo'<br><br><br><br><br><br><br><br><br><br><br><br><br>';
require_once('../usuarios/usuarios.php');
echo'<p>&nbsp;</p><div align="left"><img src="../img/gris.png" width="150" height="1" border="0"><br>
<a href="http://www.udistrital.edu.co/portal/dependencias/administrativas/tipica.php?id=10" target="_blank" onMouseOver="link();return true;" onClick="link();return true;">
<img src="../img/oas_block.gif" alt="Oficina Asesora de Sistemas" width="23" height="18" border="0"><span class="Estilo1">Oficina Asesora de Sistemas</span></a><br>
<img src="../img/gris.png" width="150" height="1" border="0"><br>
<a href="rec_mapa.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">
<img src="../img/mapa.png" width="20" height="18" border="0" alt="Mapa del Sitio"><span class="Estilo1">Mapa del Sitio</span></a><br>
<img src="../img/gris.png" width="150" height="1" border="0"><br></div>';
ob_end_flush();
?>
</body>
</html>