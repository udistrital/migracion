<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(24);
?>
<html>
<head>
<title>Desprendibles de Pago</title>
</head>
<frameset rows="45%,55%" frameborder="0" framespacing="0" border="0">
  <frame name="superior" src="fun_consulta_desp.php" noresize>
  <frame name="inferior" src="fun_desp_null.php" noresize>
  <noframes>
  <body>
  <p>Esta p&aacute;gina usa marcos, pero su explorador no los admite.</p>
  </body>
  </noframes>
</frameset>
</html>