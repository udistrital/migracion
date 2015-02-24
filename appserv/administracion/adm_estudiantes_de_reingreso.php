<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
require_once('msql_estudiantes_de_reingreso.php');
fu_tipo_user(20);

if($RowEstR != 1) $accion = $_SERVER['PHP_SELF'];
else $accion = "prog_activa_reintegros.php";

ob_start();
print'<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0">
<caption>ESTUDIANTES DE REINGRESO PARA ACTIVARLOS EN CÓNDOR</caption>
<tr class="tr">
<td align="center">No.</td>
  <td align="center">Código</td>
  <td align="center">Nombre</td>
  <td align="center">Proyecto Curricular</td>
  <td align="center">Est. E.</td>
  <td align="center">Cóndor</td>
</tr>';
$nro=1;
do{
   print'<tr class="td">
	<td align="right">'.$nro.'</td>
	<td align="right">'.OCIResult($QryEstR,1).'</td>
	<td align="left">'.OCIResult($QryEstR,2).'</td>
	<td align="left">'.OCIResult($QryEstR,3).'</td>
	<td align="center">'.OCIResult($QryEstR,4).'</td>
	<td align="center">'.OCIResult($QryEstR,5).'</td></tr>';
	$nro++;
}while(OCIFetch($QryEstR));
cierra_bd($QryEstR, $oci_conecta);
print'</table><p></p>';
if(isset($_GET['error_login'])){
   $error=$_GET['error_login'];
   echo"<center><font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
		<img src='../img/asterisco.gif'>$error_login_ms[$error]</font></center>";
}
print'<form name="form1" method="post" action="'.$accion.'">
	<div align="center"><input type="submit" name="Submit" value="Activar en Cóndor" class="button" '.$evento_boton.'>
	<input name="tipo" type="hidden" value="51"></div>
</form>';
ob_end_flush();
?>
</body>
</html>