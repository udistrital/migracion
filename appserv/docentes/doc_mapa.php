<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(30);
ob_start();
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
     <td width="22"><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="doc_actualiza_dat.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Actualizar</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" border=0 alt="" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="3" height="15"><span class="Estilo5">Plan de Trabajo</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="doc_adm_pt.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Gestionar</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2">Reglamentaci&oacute;n</td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td valign="middle"><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td valign="middle"><A class=clSlideSub2Links onmouseover="link();return true;" onclick="link();return true;" href="estdfocen.pdf" 
target="principal">Estatuto Del Profesor</A></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20></td>
     <td width="20" valign="middle"><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td width="158" valign="middle"><a href="doc_circular003_pt.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Circular 003</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20></td>
     <td valign="middle"><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 alt="" width=10 height=20></td>
     <td valign="middle"><a href="doc_circular008_pt.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Circular 008</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" border=0 alt="" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="3" height="15"><span class="Estilo5">Asignaci&oacute;n Acad.</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="doc_fre_carga.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Asignaturas</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Auto Evaluaci&oacute;n</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="../err/valida_evadoc.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Auto Evaluaci&oacute;n</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="doc_obsevaciones.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Observaciones de Est.</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="../informes/rresultados_uni_prom_20061.pdf" target="principal">Resultados</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="3" height="15"><span class="Estilo5">Captura de Notas</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="doc_curso.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Pregrado</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="doc_carga_curvac.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Vacacionales</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="doc_carga_pos.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Posgrados</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" border=0 alt="" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Servicios</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="2"><a href="../generales/gen_uso_condor.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Accesos a C&oacute;ndor</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=37&Itemid=77" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Calendario Acad&eacute;mico</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="doc_frm_cra_carga.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Contactar Docentes</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="../generales/derechos_pecuniarios.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Derechos Pecuniarios</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="../generales/estaturo_est.pdf" target="principal">Estatuto Estudiantil</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="../generales/gen_est_abhl.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Estudiantes Activos</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><a href="http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=37&Itemid=77" target="principal" onMouseOver="link();return true;" onClick="link();return true;"><img src="../img/lin2.gif" border=0 width=10 height=20></a></td>
     <td colspan="2"><a href="../generales/modes_grado.pdf" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Modalidades Trabajos de Grado</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" border=0 alt="" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="3" class="Estilo5">Clave</td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="../generales/cambiar_mi_clave.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Cambiar mi Clave</a></td>
   </tr>
   <tr>
     <td colspan="3"><img src="../img/espacio.gif" border=0 alt="" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Salir</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 alt="" width=10 height=20></td>
     <td colspan="2"><a href="../conexion/salir.php" target="_top" onMouseOver="link();return true;" onClick="link();return true;">Salir de Esta P&aacute;gina</a></td>
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