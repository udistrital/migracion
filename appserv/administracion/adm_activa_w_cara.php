<?PHP
require(dir_conect.'conexion.php');
$qry_cra = OCIParse($oci_conecta, "SELECT cra_cod, cra_abrev
  									 FROM accra
 									WHERE cra_cod NOT IN(0,999) 
									  AND cra_estado = 'A'
								 ORDER BY cra_cod ASC");
OCIExecute($qry_cra) or die(Ora_ErrorCode());
$rows = OCIFetch($qry_cra);

echo'<div align="center">
<span class="Estilo2">ACTIVAR USUARIOS POR CARRERA</span>
<form name="LisCra" method="post" action="prog_activa_w_cara.php">
<select size="1" name="cracod">
<option value="" selected class="Estilo11">Seleccione el Proyecto Curricular y haga clic en "Activar"</option>';

do{
   echo'<option value="'.OCIResult($qry_cra, 1).'">'.OCIResult($qry_cra, 1).'-'.OCIResult($qry_cra, 2).'</option>\n';
}while(OCIFetch($qry_cra));
OCIFreeCursor($qry_cra);
   
echo'</select>

<INPUT TYPE="Submit" VALUE="Activar" style="cursor:pointer">

</form></div>';
?>