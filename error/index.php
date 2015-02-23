<?
// Listado de posibles fuentes para la dirección IP, en orden de prioridad
        	$fuentes_ip = array(
            	"HTTP_X_FORWARDED_FOR",
            	"HTTP_X_FORWARDED",
            	"HTTP_FORWARDED_FOR",
            	"HTTP_FORWARDED",
            	"HTTP_X_COMING_FROM",
            	"HTTP_COMING_FROM",
            	"REMOTE_ADDR",
        	);

        	foreach ($fuentes_ip as $fuentes_ip) {
            		// Si la fuente existe captura la IP
            		if (isset($_SERVER[$fuentes_ip])) {
            		    	$proxy_ip = $_SERVER[$fuentes_ip];
            		    	break;
            		}
        	}

        	$proxy_ip = (isset($proxy_ip)) ? $proxy_ip : @getenv("REMOTE_ADDR");
        	// Regresa la IP
        
?><html>
<head>
	<title>Acceso no autorizado - Universidad Distrital Francisco Jos&eacute; de Caldas.</title>
</head>
<body>
<table align="center" width="80%" cellpadding="7" border=0>
<tr><td>
<table width="100%" align="center"><tr>
<td><img src="/error/escud.gif" WIDTH=120 HEIGHT=150></td>
<td >
<h1 align="center">UNIVERSIDAD DISTRITAL <br>"FRANCISCO JOS&Eacute; DE CALDAS"</h1><br>
<h2 align="center">OFICINA ASESORA DE SISTEMAS.</h2></td>
<td><img src="/error/oas.png" WIDTH=100 HEIGHT=100></td>

</tr></table>


</td>

</tr>
<tr>
<td bgcolor="#DC011B" valign="middle">
<? if($_REQUEST['id']==401){ ?>
<h1 align="center"><blink>ACCESO REQUIERE AUTORIZACI&Oacute;N</blink></h1>
<? }
else if($_REQUEST['id']==403){ ?>
<h1 align="center"><blink>ACCESO NO AUTORIZADO</blink></h1>
<? }
elseif($_REQUEST['id']==404){ ?>
<h1 align="center"><blink>LA PAGINA NO EXISTE<blink></h1>
<? }
else{ ?>
<h1 align="center"><blink>ERROR INTERNO DEL SERVIDOR<blink></h1>
<? }?>

</td>
</tr>
<tr>
<td><h3>Se ha creado un registro de acceso ilegal desde la direcci&oacute;n: <b><? echo $proxy_ip ?></b>.</h3></td>
</tr>
<tr>
<td>
Si considera que esto es un error por favor comuniquese con el administrador del sistema.
</td>
</tr>
<tr>
<td style="font-size:12;" align="center">
<hr>
Ambiente de desarrollo para aplicaciones web.<br>
Universidad Distrital Francisco Jos&eacute; de Caldas. <br>
Oficina Asesora de Sistemas
</td>
</tr>
</table>
</body>
<html>
