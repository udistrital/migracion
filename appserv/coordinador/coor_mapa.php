<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(4);
fu_cabezote("MAPA DEL SITIO");
?>
<html>
<head>
<title>Mapa</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body>
<br>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
<br>
<table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
     <td colspan="3"><span class="Estilo5">Datos Personales</span></td>
   </tr>
   <tr>
     <td width="20"><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="coor_actualiza_dat.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Actualizar</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=10></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Docentes</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="coor_frm_docentes.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Listado de Docentes</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="2">Evaluaci&oacute;n</td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin3.gif" border=0 width=10 height=20></td>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../err/valida_evadoc.php" target="principal">Evaluar Docentes</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin3.gif" border=0 width=10 height=20></td>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="coor_observaciones_doc.php" target="principal">Obser. de Estudiantes</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin3.gif" border=0 width=10 height=20></td>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="../informes/rresultados_uni_prom_20061.pdf" target="principal">Resultados</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="coor_correos_doc.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Enviar Correo</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="2">Plan de Trabajo </td>
     </tr>
   <tr>
     <td>&nbsp;</td>
     <td width="20"><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td width="160"><A class="clSlideSub2Links" onmouseover="link();return true;" onclick="link();return true;" href="coor_doc_digito_pt.php" target="principal">Ver Plan de Trabajo</A></td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><A class="clSlideSub2Links" onmouseover="link();return true;" onclick="link();return true;" href="../docentes/estdfocen.pdf" target="principal">Estatuto Del Profesor</A></td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><A class="clSlideSub2Links" onmouseover="link();return true;" onclick="link();return true;" href="../docentes/doc_circular003_pt.php" target="principal">Circular 003</A></td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><A class="clSlideSub2Links" onmouseover="link();return true;" onclick="link();return true;" href="../docentes/doc_circular008_pt.php" target=principal>Circular 008</A></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=10></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Estudiantes</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="../generales/gen_est_abhl.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Activos</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="coor_frm_datos_est.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;"> </a><a href="coor_frm_est_asiins.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Asignaturas Inscritas</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="2"> <a href="coor_est_activos.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Con Asig. Inscritas</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="coor_frm_datos_est.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Datos B&aacute;sicos</a></td>
   <tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=10></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Cursos Programa.</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="coor_lis_asignaturas.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Disponibilidad Cupos</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=10></td>
   </tr>
   <tr>
   
     <td colspan="3"><span class="Estilo5">Control de Notas</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="2"><A href="coor_fec_notaspar.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Fec. Notas Parciales</A></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="coor_control_notas.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Notas Digitadas</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=10></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Servicios</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="../generales/gen_uso_condor.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Accesos a C&oacute;ndor</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=37&Itemid=77" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Calendario Acad&eacute;mico</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="../generales/derechos_pecuniarios.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Derechos Pecuniarios</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="../generales/estaturo_est.pdf" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Estatuto Estudiantil</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="coor_cra_hor.php">Horarios</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="../generales/modes_grado.pdf" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Modalidades Trabajos de Grado</a> </td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=10></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Admon. de Noticias</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="coor_frm_msg.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Noticias</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=10></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Estadísticas</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="3"><a href="../estadistica/index_desercion.php" target="principal">Deserci&oacute;n</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="3"><a href="../estadistica/index_tot_empleados.php" target="principal">Funcionarios</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="3"><a href="../generales/gen_inscritos_por_facultad.php" target="principal">Proceso Admisiones</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=10></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Pensum</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="coor_actualiza_pen.php.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Actualización</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=10></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Clave</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="../generales/cambiar_mi_clave.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Cambiar mi Clave</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=10></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Salir</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="../conexion/salir.php" target="_top" onMouseOver="link();return true;" onClick="link();return true;">Salir de Esta P&aacute;gina</a></td>
   </tr>
</table>
<br>
</fieldset>
</td></tr></table>
</body>
<? fu_pie(); ?>
</html>