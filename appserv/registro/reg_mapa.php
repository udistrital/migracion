<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(33);

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
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
  <tr><td>
  <fieldset style="padding:10">
<br>
<table width="200" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
   <!-- <tr>
     <td colspan="2"><span class="Estilo5">Datos Personales</span></td>
   </tr>
   <tr>
     <td width="22"><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td width="178"><a href="reg_actualiza_dat.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Actualizar</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td> 
   </tr> -->
   <tr>
     <td colspan="2"><span class="Estilo5">Procesos</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="https://occired1.bancodeoccidente.com.co/bancacorporativa/" target="principal">Bajar Pagos</a></td>
   </tr>
  <!-- <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="reg_lee_archivo.php" target="principal">Cargar Reporte Pagos</a></td>
   </tr> 
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="reg_carga_tab_pagos.php" target="principal">Cargar Tabla Pagos</a></td>
   </tr>-->
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="http://bari.icfes.gov.co/resultados/sniee_ind_res_ies.htm" target="principal">Bajar ICFES</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="reg_encripta_claves_de_aspirantes.php" target="principal">Encriptar Claves</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Consultas</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="reg_consulta_referencia.php" target="principal">Ref.Bancaria</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="reg_snp_acaspw.php" target="principal">SNP Aspirantes</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="reg_snp_transferencia.php" target="principal">SNP Transferencia</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Aspirantes</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../estadistica/index_poblacion_asp_estrato.php" target="principal">Poblaci&oacute;n por Estrato</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="../estadistica/index_poblacion_asp_sexo.php" target="principal">Poblaci&oacute;n por Sexo</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="../estadistica/index_poblacion_asp_localidad.php">Poblaci&oacute;n por Localidad</a></td>
   </tr>
   <tr>
     <td colspan="2"><img src="../img/espacio.gif" width=10 height=20></td>
   </tr>
   <tr>
     <td colspan="2"><span class="Estilo5">Admitidos</span></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td><a href="reg_codif_anoper.php" target="principal">Codificados</a></td>
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
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin2.gif" border=0 width=10 height=20></td>
     <td><a href="../estadistica/index_poblacion_adm_localidad.php" target="principal">Poblaci&oacute;n por Localidad</a></td>
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
     <td><a href="../estadistica/index_poblacion_activa_sexo.php" target="principal">Activos por Sexo</a></td>
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
     <td colspan="3"><span class="Estilo5">Estadística</span></td>
   </tr>
    <tr>
      <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
      <td colspan="3"><a href="reg_uso_diario.php" target="principal">Accesos Diarios</a></td>
    </tr>
    <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="3"><a href="reg_uso_inscripcion.php" target="principal">Accesos de Aspirantes</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="3"><a href="reg_asp_anoper.php" target="principal">Aspirantes</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="3"><a href="reg_adm_anoper.php" target="principal">Admitidos</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="3"><a href="reg_codif_anoper.php" target="principal">Codificados</a></td>
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
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="3"><a href="reg_inscritos_por_facultad.php" target="principal">Inscritos por Facultad</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="3"><a href="reg_inscritos_por_carrera.php" target="principal">Inscritos por Carrera</a></td>
   </tr>
   <tr>
     <td><img src="../img/espacio.gif" width=10 height=20><img src="../img/lin1.gif" border=0 width=10 height=20></td>
     <td colspan="3"><a href="reg_relacion_consignaciones.php" target="principal">Valores Consignados</a></td>
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