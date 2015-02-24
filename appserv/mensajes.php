<?PHP
if($_SERVER['HTTP_REFERER'] == ""){
	die('<p align="center"><b><font color="#FF0000"><u>Acceso incorrecto!</u></font></b></p>');
  exit;
}
?>
<html>
<head>
<title>Mensajes</title>
<script language="JavaScript" src="script/clicder.js"></script>
<script language="JavaScript" src="script/ventana.js"></script>
<link href="script/estinx.css" rel="stylesheet" type="text/css">
</head>
<body style="margin-top:0; margin-bottom:0; margin-left:0; background-color:#F4F5EB"><table width="245" height="100" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#7A8180" style="border-collapse: collapse"><tr>
<td style="background-image:url('img/notioas.png');background-repeat:no-repeat; background-position:right 0%"><span class="Estilo2">NotiC&Oacute;NDOR</span>
<span class="Estilo6" style="text-align:justify">
<marquee id="NotiOas" direction="up" behavior="scroll" scrollamount="3" scrolldelay="250"
onMouseOver="getElementById('NotiOas').scrollAmount=0;" 
onMouseOut="getElementById('NotiOas').scrollAmount=3; getElementById('NotiOas').direction='up';" width="243" height="100">
<?
require_once('mensajes_contenido.php');
?>
<br>
</marquee>
</span><div align="right">
<a class="CapLink" onClick="getElementById('NotiOas').direction='up'; getElementById('NotiOas').scrollAmount=3;"><img src="img/up.png" width="13" height="13" border="0" title="Desplazar arriba"></a><a class="CapLink" onClick="getElementById('NotiOas').scrollAmount=0;">
<img src="img/stop.png" alt="Detener" width="13" height="13" border="0" title="Detener"></a>
<a class="CapLink" onClick="getElementById('NotiOas').direction='down'; getElementById('NotiOas').scrollAmount=3;">
<img src="img/down.png" width="13" height="13" border="0" title="Desplazar abajo"></a></div>
</td></tr>
</table>
</body>
</html>