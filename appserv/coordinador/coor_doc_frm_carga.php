<html>
<head>
</head>
<frameset rows="50%,*" framespacing="0" frameborder="no" border=".5">
  <noframes>
  <body>
  <? $_POST['cedula']=$_GET['CD']; ?>
  <p>Esta página usa marcos, pero su explorador no los admite.</p>
  </body>
  </noframes>
  <frame name="carga" src="coor_doc_carga.php?cedula= <? echo $_GET['CD'] ?>" scrolling="auto" noresize>
  <frame name="horasi" src="coor_asi_hor_nula.php">
</frameset>
</html>