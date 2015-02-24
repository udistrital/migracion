<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(20);
?>
<html>
<head>
<title>Pagina nueva 2</title>
</head>
<frameset rows="22%,*" framespacing="0" border="0" frameborder="0">
  <frame name="superior" src="adm_consulta_datos_est.php" scrolling="no">
  <frame name="inferior" src="adm_pag_null.php" scrolling="auto">
  <noframes>
  <body>
  <p>Esta página usa marcos, pero su explorador no los admite.</p>
  </body>
  </noframes>
</frameset>
</html>