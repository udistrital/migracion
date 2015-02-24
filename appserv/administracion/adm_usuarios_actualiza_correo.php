<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
fu_tipo_user(20);

require_once('msql_consulta_correos_est.php');

if($Rowcorreo != 1) $accion = "";
else $accion = "prog_actualiza_correos.php";

ob_start();
print'<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0">
<caption>ACTUALIZACIÓN DE CORREOS CON EL CORREO INSTITUCIONAL</caption>
<tr class="tr">
  <td align="center">No.</td>
  <td align="center">Código</td>
  <td align="center">Nombre</td>
  <td align="center">Correo Personal</td>
  <td align="center">Correo Institucional</td>
  <td align="center">Estado</td>
</tr>';
$nro=1;
do{
   print'<tr class="td">
   <td align="right">'.$nro.'</td>
   <td align="right">'.OCIResult($Qrycorreo,1).'</td>
   <td align="left">'.OCIResult($Qrycorreo,2).'</td>
   <td align="left">'.OCIResult($Qrycorreo,3).'</td>
   <td align="left">'.OCIResult($Qrycorreo,4).'</td>
   <td align="center">'.OCIResult($Qrycorreo,5).'</td></tr>';
	$nro++;
}while(OCIFetch($Qrycorreo));
cierra_bd($Qrycorreo, $oci_conecta);
print'</table><p></p>
<center><form action="'.$accion.'" method="post" name="actnoest">
<input name="bactnoest" type="submit" value="Actualizar Correos" class="button" '.$evento_boton.'>
</form></center>';
ob_end_flush();
?>