<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'NumeroVisitas.php');
fu_tipo_user(31);

ob_start();
if($Nro == 1){
   $pmenu='rec_pag_menu_uno.php';
   $pagpal = '../generales/cambiar_mi_clave.php';
}
elseif($Dia == 30){
       $pmenu='rec_pag_menu_uno.php';
       $pagpal = '../generales/cambiar_mi_clave.php';
}
elseif($Mod == ""){
       $pmenu='rec_pag_menu_uno.php';
       $pagpal = '../generales/cambiar_mi_clave.php';
}
else{
     $pmenu = 'rec_pag_menu.php';
     $pagpal = 'rec_pag_principal.php';
}
?>
<html>
<head>
<title>Rector</title>
</head>
<frameset framespacing="1" border="0" rows="73,20,*" frameborder="0" style="border-style:ridge; border-color:#E4E5DB">
  <frame name="superior" scrolling="no" noresize target="contenido" src="../generales/cabezote.php">
  <frame name="superior1" src="rec_nombre.php" scrolling="no" target="_self">
  <frameset cols="162,*">
    <frame name="contenido" target="principal" src="<? print $pmenu; ?>" scrolling="auto" noresize="noresize" style="border-style:solid; border-width:2; border-collapse:collapse; border-color:#E4E5DB">
    <frame name="principal" target="_self" src="<? print $pagpal; ?>" scrolling="auto" noresize="noresize" style="border-style:solid; border-width:2; border-collapse:collapse; border-color:#E4E5DB">
  </frameset>
  <noframes>
  <body>
  <p>Esta página usa marcos, pero su explorador no los admite.</p>
  </body>
  </noframes>
</frameset>
</html>
<?php ob_end_flush(); ?>