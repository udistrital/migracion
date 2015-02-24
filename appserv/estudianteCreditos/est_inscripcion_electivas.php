<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once('valida_adicion.php');
require_once('valida_estudiante_activo.php');
require_once('valida_estudiante_nuevo.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_cabezote.php');

fu_tipo_user(51);
?>
<HTML>
<HEAD>
<TITLE>Estudiantes</TITLE>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
</HEAD>
<BODY topmargin="0" leftmargin="0">

<?php
ob_start();

fu_cabezote("ADICIÓN DE ELECTIVAS");
$estcod = $_SESSION['usuario_login'];

$estcracod = OCIParse($oci_conecta, "SELECT est_cra_cod FROM acest WHERE est_cod = $estcod");
OCIExecute($estcracod) or die(ora_errorcode());
$rowcra= OCIFetch($estcracod);
$estcra = OCIResult($estcracod, 1);
OCIFreeCursor($estcracod);

//consulta las asignaturas a adicionar.
require_once('msql_consulta_electivas.php');

echo'<p align="center"><span class="Estilo14">Período Académico:</span> '.$ano.'-'.$per.'</p><p></p>';

echo'<center><span class="Estilo5">Haga clic en el nombre de la asignatura para ver los grupos disponibles.</span></center>';
echo'<table width="515 border="1" align="center"">
	<tr class="tr">
      <td width="93" align="center">Código</td>
      <td width="363" align="center">Asignatura</td></tr>';
do{
   echo'<tr>
   <td width="93" align="right">'.OCIResult($asicod, 1).'</td>
   <td width="363" align="left"><a href="est_grupos_inscripcion.php?asicod='.OCIResult($asicod, 1).'&sem='.OCIResult($asicod, 3).'&pen='.OCIResult($asicod, 4).'" target="inferior" onMouseOver="link();return true;" onClick="link();return true;" title="Electivas Disponibles">'.OCIResult($asicod, 2).'</a></td></tr>';
}while(OCIFetch($asicod));
echo'</table><p>&nbsp;</p>';
cierra_bd($asicod,$oci_conecta);
ob_end_flush();
?>
</BODY>
</HTML>