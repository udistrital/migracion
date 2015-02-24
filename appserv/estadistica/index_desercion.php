<?PHP
require_once('dir_relativo.cfg');
require_once(dir_script.'fu_cabezote.php');
?>
<HTML>
<HEAD>
<TITLE>Estadisticas</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?php
fu_cabezote("DESERCI&Oacute;N"); 

require_once('esta_lis_desp_carrera.php');

if($_REQUEST['cracod'] && $_REQUEST['peri']){
   $_SESSION['C'] = $_REQUEST['cracod'];
   $_SESSION['A'] = substr($_REQUEST['peri'],0,4);
   $_SESSION['G'] = substr($_REQUEST['peri'],4,1);

   require_once('esta_desercion.php');
}
?>
</BODY>
</HTML>