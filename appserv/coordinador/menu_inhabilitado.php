<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once('../generales/gen_link.php');
require_once("../clase/config.class.php");
require_once("../clase/encriptar.class.php");

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$cripto=new encriptar();
	fu_tipo_user(4);
	ob_start();
?>
<html>
<head>
<!--<link href="../script/estilo_menu.css" rel="stylesheet" type="text/css">
<link href="../generales/marcos/apariencia.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>-->
<link href='../script/menu.css' rel="stylesheet" type="text/css">
<script type="text/javascript" src='../script/menu.js'></script>
<link href="../generales/marcos/apariencia.css" rel="stylesheet" type="text/css">
</head>

<body class="menu">


    <table class="contenidotabla" align="center" width="100%">
	<tr>
	<td id="mainContainer">
            <center>
                <img src="<?echo $configuracion['host'].$configuracion['site'].$configuracion['grafico']?>/loading.gif"><br>
                <font size="1">En este momento se esta<br>ejecutando un proceso.<br>Por favor espere ...</font>
            </center>
	</td>
</tr>

	</table>

</body>
</html>
