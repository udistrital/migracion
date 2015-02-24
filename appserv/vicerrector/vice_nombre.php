<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');
?>
<html>
<head>
<link href="../script/estilo_nombre.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/fecha.js"></script>
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/saludo.js"></script>
<body background="../img/bgnomusu.png">
<?PHP
fu_tipo_user(32);
$usuario = $_SESSION["usuario_login"];
$nivel = $_SESSION["usuario_nivel"];

$QryNombre = OCIParse($oci_conecta, "SELECT fua_invierte_nombre(emp_nombre),emp_nro_iden,emp_cod
									   FROM peemp
			   						  WHERE emp_nro_iden = $usuario
				 					    AND emp_estado = 'A'");
OCIExecute($QryNombre) or die(Ora_ErrorCode());
$RowNombre = OCIFetch($QryNombre);
$Nombre = OCIResult($QryNombre, 1);
$Email  = OCIResult($QryNombre, 2);
$_SESSION["fun_cod"] = OCIResult($QryNombre, 3);
if($RowNombre != 1) die('<center><h3>No hay registros para esta consulta.</h3></center>');
echo'<table border="0" width="100%" align="right" cellpadding="0" height="11">
    <tr>
      <td width="33%" height="9" align="center"><span class="fec"><SCRIPT>dia()</SCRIPT></span></td>
      <td width="34%" height="9" align="center"><span class="fun">VICERRECTOR</b></span></td>
      <td width="33%" height="9" align="center"><span class="nom">'.$Nombre.'</span></td>
    </tr>
  </table>';
cierra_bd($QryNombre, $oci_conecta);
?>
</body>
</html>