<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
if (!$_REQUEST['tipo']) {
    $_REQUEST['tipo'] = $_SESSION['usuario_nivel'];
}
if($_REQUEST['tipo']==110){
fu_tipo_user(110);
}elseif($_REQUEST['tipo']==114){
fu_tipo_user(114);
}else{
fu_tipo_user(4);
}

?>
<html>
<head>
<title></title>
</head>
<frameset rows="24%,*" framespacing="0" border="0" frameborder="0">
  <noframes>
  <body>
  <p>Esta página usa marcos, pero su explorador no los admite.</p>
  </noframes>
  <frame name="superior" src="coor_consulta_datos_est.php?tipo=<?php echo $_REQUEST['tipo'];?>" scrolling="auto">
  <frame name="inferior" src="coor_pag_est_nula.php" scrolling="auto">
</frameset>
</frameset>
</body>
</html>
