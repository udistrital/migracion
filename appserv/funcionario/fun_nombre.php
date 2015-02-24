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
<body background="../img/bgnomusu.png">
<?PHP
fu_tipo_user(24);
$usuario = $_SESSION['usuario_login'];
$nivel = $_SESSION["usuario_nivel"];

ob_start();
require_once(dir_script.'NombreUsuario.php');
if($RowNombre != 1) { header("Location: ../err/err_sin_registros.php"); exit; }

echo'<div align="right">
  <table border="0" width="100%" cellpadding="0" height="11">
    <tr>
      <td width="33%" height="9" align="center"><span class="fec"><SCRIPT>dia();</SCRIPT></span></td>
      <td width="33%" height="9" align="center"><span class="fun">'.$Cargo.'</span></td>
      <td width="33%" height="9" align="center"><span class="nom">'.$Nombre.'</span></td>
    </tr>
  </table>
</div>';
ob_end_flush();
?>
</body>
</html>