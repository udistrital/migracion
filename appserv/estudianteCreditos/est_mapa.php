<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(51);
fu_cabezote("MAPA DEL SITIO");
ob_start();
?>
<html>
<head>
<title>Mapa</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
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
     <td colspan="2"><span class="Estilo5">Datos Personales</span></td>
   </tr>
   <tr>
     <td width="22"><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td width="178"><a href="est_actualiza_dat.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Actualizar</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="est_detalle_matricula.php" target="principal">Detalle de Matr&iacute;cula</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2" height="15"><span class="Estilo5">Asignaturas</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td height="18"><a href="est_fre_asi_ins.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Inscritas</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td height="18"><a href="est_fre_inscripcion.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Adicionar y Cancelar</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td height="18"><a href="est_asi_ins_curvac.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Vacacionales</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="est_fre_horarios.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Horarios</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><A class=clSlideSubLinks onmouseover="link();return true;" 
onclick="swmenu(1,4,-1); if(bw.ie || bw.ns6) this.blur(); " 
href="est_lis_asignaturas.php" target=principal>Cursos Programados</A></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Notas</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="est_notaspar.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Parciales</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="est_notas_curvac.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Vacacionales</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="est_notas.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Hist&oacute;rico</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Plan de Estudio</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="est_semaforo.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Plan de Estudio</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Docentes</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="../err/valida_evadoc.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Evaluaci&oacute;n</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Servicios</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=37&Itemid=77" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Calendario Acad&eacute;mico</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="est_adm_correos_doc.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Contactar Docentes</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../generales/derechos_pecuniarios.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Derechos Pecuniarios</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../generales/estaturo_est.pdf" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Estatuto Estudiantil</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="../generales/modes_grado.pdf" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Modalidades Trabajos de Grado</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Clave</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="../generales/cambiar_mi_clave.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Cambiar mi Clave</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Salir</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="../conexion/salir.php" target="_top" onMouseOver="link();return true;" onClick="link();return true;">Salir de Esta P&aacute;gina</a></td>
   </tr>
</table>
<br>
 </fieldset>
</td></tr></table>
</body>
<? fu_pie(); 
ob_end_flush();
?>
</html>