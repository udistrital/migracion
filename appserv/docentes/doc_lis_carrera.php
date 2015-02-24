<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'msql_ano_per.php');

ob_start();
?>
<html>
<body  bgcolor="#E8E8D0">
<p align="center"><font face="Tahoma" size="2" color="#FF3300"><b>LISTADO DE CARRERAS</b></font>
<p>

<?php
$cedula = $_SESSION['usuario_login'];

$datos = OCIParse($oci_conecta, "SELECT DISTINCT(car_cra_cod),cra_nombre
  								   FROM accarga,accra
 								  WHERE car_cra_cod = cra_cod
   									AND cra_estado = 'A'
   									AND car_ape_ano = $ano
   									AND car_ape_per = $per
   									AND car_doc_nro_iden = $cedula
   									AND car_estado = 'A'");
ociexecute($datos);
$row = OCIFetch($datos);

echo'</p>
<div align="center"><table border="0" width="100%"><tr><td width="100%" align="center">
<select size="1" name="CRANOM">';
do{
   echo'<option value="'.OCIResult($datos, 1).'" selected>'.OCIResult($datos, 2).'</option>\n';
}while(OCIFetch($datos));
echo'</select>
</td></tr></table></div>';
cierra_bd($datos, $oci_conecta);
ob_end_flush();
?>
</body>
</html>