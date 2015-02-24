<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_script.'msql_ano_per.php');
?>
<HTML>
<HEAD><TITLE>Oficina Asesora de Sistemas</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</HEAD>
<body>
<p>&nbsp;</p>
<table width="60%" height="60%" border="1" cellpadding="0" cellspacing="0" align="center">
<tr>
  <td valign="top">
  </td>
  </tr>
<tr align="center">
  <td valign="middle"> 
		
		<table width="75%" border="0" cellpadding="2" cellspacing="0" align="center">
		<tr><td valign="top">
		<div>
		  <h3>Aviso</h3>
		</div>
		
		<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
		
		<p align="justify">La captura de notas parciales para el período académico <? print $ano.'-'.$per; ?> está cerrada.</p>
		<p align="justify">Por lo anterior, no podr&aacute; dar permisos para tal fin.</p>
		  
		<p align="center"><strong>Calendario Académico</strong></p>
		
		</fieldset>

		  </td></tr>
	  </table>
    
    </td>
</tr>
</table>
<?php fu_pie(); ?>
</body>
</html>