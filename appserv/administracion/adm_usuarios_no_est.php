<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
fu_tipo_user(20);

$QryUnoEst = OCIParse($oci_conecta, "SELECT CLA_CODIGO,
				 	   				        CLA_CLAVE,
					   				        USUTIPO_TIPO,
									        CLA_TIPO_USU,
					   				        CLA_ESTADO
	  			  				      FROM geclaves,geusutipo
 				 				     WHERE USUTIPO_COD = cla_tipo_usu
   								       AND CLA_TIPO_USU != 51
									   AND CLA_ESTADO = 'W'
									 ORDER BY 1");
OCIExecute($QryUnoEst) or die(Ora_ErrorCode());
$RowUnoEst = OCIFetch($QryUnoEst);

if($RowUnoEst != 1) $accion = "";
else $accion = "prog_activa_usuarios_no_est.php";

ob_start();
print'<table width="60%" border="1" align="center" cellpadding="2" cellspacing="0">
<caption>USUARIOS DIFERNTE A ESTUDIANTES en W<BR>ACTIVARLOS DE INMEDIATO</caption>
<tr class="tr">
  <td align="center">No.</td>
  <td align="center">Código</td>
  <td align="center">Tipo</td>
  <td align="center">Estado</td>
</tr>';
$nro=1;
do{
   print'<tr class="td">
	<td align="right">'.$nro.'</td>
	<td align="right">'.OCIResult($QryUnoEst,1).'</td>
	<td align="left">'.OCIResult($QryUnoEst,3).'</td>
	<td align="center">'.OCIResult($QryUnoEst,5).'</td></tr>';
	$nro++;
}while(OCIFetch($QryUnoEst));
cierra_bd($QryUnoEst, $oci_conecta);
print'</table><p></p>
<center><form action="'.$accion.'" method="post" name="actnoest">
<input name="bactnoest" type="submit" value="Activar" class="button" '.$evento_boton.'>
</form></center>';
ob_end_flush();
?>