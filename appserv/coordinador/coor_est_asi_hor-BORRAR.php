<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY topmargin="2">

<?php
fu_tipo_user(4);

$asicod = $_GET['asicod'];
require_once(dir_script.'NombreAsignatura.php');
	
require_once(dir_script.'msql_est_asi_hor.php');
$consulta = OCIParse($oci_conecta,$cod_consul);
OCIExecute($consulta, OCI_DEFAULT);
$row = OCIFetch($consulta);
?>
  <p>&nbsp;</p>
  <table border="1" width="690" align="center" cellspacing="0" cellpadding="0">
  <caption><?php echo $Asignatura; ?></caption>
  <tr class="tr">
	<td width="5%" align="center">Día</td>
    <td width="4%" align="center">Hora Ini.</td>
    <td width="4%" align="center">Hora Fin</td>
    <td width="4%" align="center">Salón</td>
	<td width="4%" align="center">Sede</td>
  </tr>
<?php
  do{
     echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	 <td width="5%" align="center">'.OCIResult($consulta, 1).'</td>
     <td width="4%" align="center">'.OCIResult($consulta, 2).'</td>
	 <td width="4%" align="center">'.OCIResult($consulta, 3).'</td>
	 <td width="4%" align="center">'.OCIResult($consulta, 4).'</td>
	 <td width="4%" align="center">'.OCIResult($consulta, 5).'</td></tr>'; 
  }while(OCIFetch($consulta));
  cierra_bd($consulta,$oci_conecta);
?>
</table>
<p>&nbsp;</p>
<?php fu_pie(); ?>
</BODY>
</HTML>