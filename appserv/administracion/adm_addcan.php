<html>
<body>
<?PHP
require_once('msql_log_addcan.php');

print'<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <th colspan="5" scope="row" align="center">LOG DEL PROCESO DE ADICIÓN Y CANCELACIÓN DE ASIGNATURAS</th>
  </tr>
  <tr>
    <td align="right">'.OCIResult($addcan,1).'</td>
    <td colspan="4">'.OCIResult($addcan,2).'</td>
  </tr>
  <tr align="center">
    <td>Parámetro</td>
    <td>Direcci&oacute;n IP</td>
    <td>Transacción</td>
    <td>Fecha</td>
    <td>Hora</td>
  </tr>';
do{
  print'<tr>
    <td>'.OCIResult($addcan,3).'</td>
    <td>'.OCIResult($addcan,4).'</td>
    <td>'.OCIResult($addcan,5).'</td>
    <td>'.OCIResult($addcan,6).'</td>
    <td>'.OCIResult($addcan,7).'</td>
  </tr>';
}while($Rowaddcan = OCIFetch($addcan));
OCIFreeCursor($addcan);
?>
</table>
</body>
</html>