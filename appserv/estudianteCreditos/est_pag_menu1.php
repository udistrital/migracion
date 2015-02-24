<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(51);
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
<a href="est_pag_menu.php" target="_self"><span class="link">Contraer el Men&uacute;</span></a>
<script>
//Variables de configuracin
between=24 //Espacios(pixel) entre menus y submenus
mainheight=24 //Espacios entre los item del men�
subheight=21 //Espacios entre los item del submen
pxspeed=15 //Velocidad de animacin del men�
timspeed=15 //Timer Velocidad de animacin
menuy=25 //Margen superior
menux=7 //Margen izquierda del men�
<? 
$plan = '../palndeestudio/pe_'.$_SESSION["carrera"].'.pdf';
if(!file_exists($plan))
   $plan = '../palndeestudio/sin_plan.pdf';
?>

//Images del men�
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
makeMenu('top','Datos Personales')
	makeMenu('sub','Actualizar','est_actualiza_dat.php','principal')
	makeMenu('sub','Actualizar datos snies','est_actualiza_dat_snies.php','principal')
	makeMenu('sub','Detalle de Matr&iacute;cula','est_detalle_matricula.php','principal')
	

//Menu 1
makeMenu('top','Asignaturas')
	makeMenu('sub','Inscritas','est_fre_asi_ins.php','principal')
	makeMenu('sub','Adicionar y Cancelar','est_fre_inscripcion.php','principal')
	//makeMenu('sub','Adicionar Electivas','est_fre_inscripcion_electivas.php','principal')
	makeMenu('sub','Vacacionales','est_asi_ins_curvac.php','principal')
	makeMenu('sub','Horarios','est_fre_horarios.php','principal')
	makeMenu('sub','Cursos Programados','est_lis_asignaturas.php','principal')

//Menu 2
makeMenu('top','Notas')
  makeMenu('sub','Parciales','est_notaspar.php','principal')
  makeMenu('sub','Vacacionales','est_notas_curvac.php','principal')
  makeMenu('sub','Hist&oacute;rico','est_notas.php','principal')
  makeMenu('sub','Plan de Estudio','est_semaforo.php','principal')

//Menu 3
makeMenu('top','Docentes')
  makeMenu('sub','Contactar Docentes','est_adm_correos_doc.php','principal')
  makeMenu('sub','Evaluaci&oacute;n docentes','../err/valida_evadoc.php','principal')
  //makeMenu('sub','Evaluaci&oacute;n piloto','../err/valida_evadoc_prueba.php','principal')
  //makeMenu('sub','Evaluaci�n','../ev06/evaluacion.php','principal')

//Menu 4
makeMenu('top','Servicios')
  makeMenu('sub','Calendario Acad&eacute;mico','<?echo $CalAcad?>','principal')
  makeMenu('sub','Estatuto Estudiantil','../generales/estaturo_est.pdf','principal')
  makeMenu('sub','Derechos Pecuniarios','../generales/derechos_pecuniarios.php','principal')
  makeMenu('sub','Plan de Estudios','<? print $plan; ?>','principal')
  makeMenu('sub','Trabajos de Grado','../generales/gen_fac_trabgrado.php','principal')

//Menu 5
makeMenu('top','Clave')
  makeMenu('sub','Cambiar mi Clave','../generales/cambiar_mi_clave.php','principal')

//Menu 6
makeMenu('top','Salir')
  makeMenu('sub','Salir de Esta P&aacute;gina','../conexion/salir.php','_top')
  makeMenu('sub','Contraer el Men&uacute;','est_pag_menu.php','_self')

//Ejecucin del men
onload=SlideMenuInit;
</script>
<?php
$foto = est_foto.$_SESSION['usuario_login'].'.jpg';

if(!file_exists($foto)) {
	$foto="../img/sinfoto.png";
	$imgfoto='<img border="0" src="'.$foto.'" width="130" height="100"  alt="Sin fotografia almacenada">';
}
else{ $imgfoto='<img border="0" src="'.$foto.'" width="130" height="100" alt="Fotograf&iacute;a del Estudiante">'; }

echo'<br><br><br><br><br><br><br><br><br><br><br><br><br><p align="center">'.$imgfoto.'</p>';
require_once('../usuarios/usuarios.php');

echo'<p>&nbsp;</p><div align="left"><img src="../img/gris.png" width="150" height="1" border="0"><br>
<a href="http://www.udistrital.edu.co/portal/dependencias/administrativas/tipica.php?id=10" target="_blank">
<img src="../img/oas_block.gif" alt="Oficina Asesora de Sistemas" width="23" height="18" border="0"><span class="Estilo1">Oficina Asesora de Sistemas</span></a><br>
<img src="../img/gris.png" width="150" height="1" border="0"><br>
<a href="est_mapa.php" target="principal">
<img src="../img/mapa.png" width="20" height="18" border="0" alt="Mapa del Sitio"><span class="Estilo1">Mapa del Sitio</span></a><br>
<img src="../img/gris.png" width="150" height="1" border="0"><br></div>';
ob_end_flush();
?>
</body>
</html>