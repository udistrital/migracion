<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(31);
fu_cabezote("MAPA DEL SITIO");
ob_start();
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
     <td colspan="2"><span class="Estilo5">Datos Personales</span></td>
   </tr>
   <tr>
     <td width="22"><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td width="178"><a href="rec_actualiza_dat.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Actualizar</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Aspirantes</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../generales/gen_inscritos_por_facultad.php" target="principal">Proceso Actual</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="rec_asp_anoper.php" target="principal">Por A&ntilde;o y Per&iacute;odo</a></td>
   </tr>
   <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../estadistica/index_poblacion_asp_estrato.php" target="principal">Poblaci&oacute;n por Estrato</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../estadistica/index_poblacion_asp_sexo.php" target="principal">Poblaci&oacute;n por Sexo</a></td>
   <tr>
     <td width="22"><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td width="178"><a href="../estadistica/index_poblacion_asp_localidad.php" target="principal">Poblaci&oacute;n por Localidad </a></td>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2" height="15"><span class="Estilo5">Admitidos</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="rec_adm_anoper.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Por A&ntilde;o y Per&iacute;odo </a></td>
   </tr>
    <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../estadistica/index_poblacion_adm_estrato.php" target="principal">Poblaci&oacute;n por Estrato</a></td>
   </tr>
    <tr>
      <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
      <td><a href="../estadistica/index_poblacion_adm_sexo.php" target="principal">Poblaci&oacute;n por Sexo</a></td>
    </tr>
    <tr>
     <td width="22"><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td width="178"><a href="../estadistica/index_poblacion_adm_localidad.php" target="principal">Poblaci&oacute;n por Localidad </a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2" height="15"><span class="Estilo5">Est. Codificados</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="rec_codif_anoper.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Por A&ntilde;o y Per&iacute;odo</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Estudiantes</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../generales/gen_est_abhl.php" target="principal">Activos</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../generales/gen_fra_cuenta_est.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Con Asignaturas Ins.</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../estadistica/index_poblacion_activa_estrato.php" target="principal">Activos por Estrato</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="../estadistica/index_poblacion_activa_sexo.php" target="principal">Activos por Sexo </a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Evaluaci&oacute;n Doc.</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="../informes/rresultados_uni_prom_20061.pdf" target="principal">Resultados</a></td>
   </tr>
    <tr>
      <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
    </tr>
    <tr>
     <td colspan="2"><span class="Estilo5">Servicios</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../generales/gen_uso_condor.php" target="principal">Accesos a C&oacute;ndor</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=37&Itemid=77" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Calendario Acad&eacute;mico</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../generales/derechos_pecuniarios.php" target="principal">Derechos Pecuniarios</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="../generales/estaturo_est.pdf" target="principal">Estatuto Estudiantil</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="3"><span class="Estilo5">Estadísticas</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="3"><a href="../estadistica/esta_uso_condor.php" target="principal">Accesos a C&oacute;ndor</a></td>
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
     <td colspan="3"><a href="../estadistica/esta_tot_proyectos.php" target="principal">Proy. Curriculares</a></td>
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
     <td><a href="../conexion/salir.php" target="_top" onMouseOver="link();return true;" onClick="link();return true;">Salir de esta P&aacute;gina</a></td>
   </tr>
</table>
<br>
</fieldset>
</td></tr></table>
</body>
<? 
ob_end_flush();
fu_pie(); ?>
</html>