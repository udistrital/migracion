<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
fu_tipo_user(4);
fu_cabezote("CURSOS PROGRAMADOS - DISPONIBILIDAD DE CUPOS");
ob_start();
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY>
<p></p>
<?php
require_once('coor_lis_desp_carrera.php');

include_once(dir_script.'class_nombres.php');
$nom = new Nombres;
print'<div align="center" class="Estilo5">'.$nom->NombreCarrera($_POST['cracod']).'</div>';
?>
<table width="750" border="0" align="center" cellpadding="2" cellspacing="0">
<caption><samp class="Estilo5">CURSOS PROGRAMADOS Y DISPONIBILIDAD DE CUPOS, PERÍODO ACADÉMICO<? print $nomsem.'  :  '.$ano.'-'.$per; ?></samp></caption>
  <tr class="tr">
    <td width="72" align="center">Código</td>
    <td width="502" align="center">Asignatura</td>
    <td width="32" align="center" title="Grupo">Gru</td>
    <td width="32" align="center" title="Semestre">Sem</td>
    <td width="31" align="center" title="Cupo">Cup</td>
    <td width="26" align="center" title="Nro. de inscritos">Ins</td>
    <td width="25" align="center" title="Cupos disponibles">Dis</td>
  </tr>

<?php
$LisAsi = OCIParse($oci_conecta, "SELECT cur_asi_cod,
									   	 asi_nombre,
									   	 cur_nro,
									   	 cur_semestre,
									   	 cur_nro_cupo,
									   	 cur_nro_ins,
									   	 (cur_nro_cupo - cur_nro_ins)
									FROM accurso,acasi,acasperi
								   WHERE ape_ano = cur_ape_ano
								  	 AND ape_per = cur_ape_per
								  	 AND ape_estado = 'A'
								  	 AND cur_estado = 'A'
								  	 AND cur_asi_cod = asi_cod
								  	 AND asi_estado = 'A'
								  	 AND cur_semestre != 0
								  	 AND cur_cra_cod = ".$_POST['cracod']."
								  	ORDER BY 4,1,3");
OCIExecute($LisAsi) or die(ora_errorcode());
$RowLisAsi = OCIFetch($LisAsi);

do{
   print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
    <td align="right">'.OCIResult($LisAsi, 1).'</td>
    <td align="left">'.OCIResult($LisAsi, 2).'</td>
    <td align="center">'.OCIResult($LisAsi, 3).'</td>
    <td align="center"><b>'.OCIResult($LisAsi, 4).'</b></td>
    <td align="center">'.OCIResult($LisAsi, 5).'</td>
    <td align="center">'.OCIResult($LisAsi, 6).'</td>
    <td align="center">'.OCIResult($LisAsi, 7).'</td>
  </tr>';
}while(OCIFetch($LisAsi));
cierra_bd($LisAsi,$oci_conecta);
?>
</table>
<?php fu_pie(); 
ob_end_flush();
?>
</BODY>
</HTML>