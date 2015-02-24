<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(20);
ob_start();

$QryEstW = OCIParse($oci_conecta, "SELECT cra_cod, 
									 cra_abrev, 
									 est_cod, 
									 est_nombre, 
									 est_estado_est, 
									 cla_estado 
								FROM accra, acest, geclaves 
							   WHERE cra_cod = est_cra_cod 
								 AND est_cod = cla_codigo 
								 AND cla_estado = 'W' 
							  ORDER BY cra_cod, est_cod ASC");
OCIExecute($QryEstW) or die(Ora_ErrorCode());
$RowEstW = OCIFetch($QryEstW);

print'<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0">
<caption>INTENTO DE INGRESO IRREGULAR A CÓNDOR</caption>
<tr class="tr">
<td align="center">No.</td>
  <td align="center">Código</td>
  <td align="center">Nombre</td>
  <td align="center">Proyecto Curricular</td>
  <td align="center">Cóndor</td>
</tr>';
$nro=1;
do{
   print'<tr class="td" onClick="this.className=\'raton_arr\'" onDblClick="this.className=\'raton_aba\'">
	<td align="right">'.$nro.'</td>
	<td align="right">'.OCIResult($QryEstW,3).'</td>
	<td align="left">'.OCIResult($QryEstW,4).'</td>
	<td align="left">'.OCIResult($QryEstW,2).'</td>
	<td align="center">'.OCIResult($QryEstW,6).'</td></tr>';
	$nro++;
}while(OCIFetch($QryEstW));
cierra_bd($QryEstW, $oci_conecta);
print'</table><p></p>';
require_once('adm_activa_w_cara.php');
ob_end_flush();
?>