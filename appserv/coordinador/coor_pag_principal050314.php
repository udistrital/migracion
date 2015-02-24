<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>

<style>
.mensaje{
	padding:20px;
	font-family: sans-serif;
	border: solid 2px #999999;
	border-radius: 10px 10px 10px 10px;
	box-shadow: 2px 2px 5px #999999;
	background: #FFFFFF;
	width:90%;
	margin:auto;
	font-size: 10pt;
}

.mensaje_title{
	padding:5px 20px 5px 20px ;
	font-family: sans-serif;
	border: solid 2px #999999;
	border-radius: 10px 10px 10px 10px;
	box-shadow: 2px 2px 5px #999999;
	color: #FFFFFF;
	background: #333333;
	width:90%;
	margin:auto;
	font-size: 10pt;
}
.mensaje li{
	font-size: 10pt;
}
</style>

</head>
<body>
<?php
fu_tipo_user(4);

$cedula = $_SESSION['usuario_login'];
$QryCra = "SELECT cra_cod,cra_nombre FROM accra WHERE CRA_EMP_NRO_IDEN = $cedula AND cra_estado = 'A'";



$RowCra = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCra,"busqueda");

echo'
    <div style="padding:20px; width="100%">';
$i=0;
while(isset($RowCra[$i][0]))
{
	echo'<br/><span class="Estilo5">'.$RowCra[$i][0].'-'.$RowCra[$i][1];
$i++;
}

echo '</div>';

	$cadena_sql="SELECT contenido FROM avisos where sysdate between fecha_publicacion and fecha_desfijacion and aplicacion='CONDOR' order by prioridad,fecha_publicacion";
	$mensajes=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");

	if(is_array($mensajes)){
		echo "<div class='mensaje_title'>";
		echo NOTICIAS;
		echo "</div>";
	}

	$m=0;
	while(isset($mensajes[$m][0]))
	{
		echo "<div class='mensaje'>";
		echo $mensajes[$m][0];
		echo "</div>";
		$m++;
	}
echo "<br/><br/>";
echo "<div class='mensaje'>";
echo'	
	<table border="0" width="100%" cellpadding="0">
   
	<tr><td width="67%" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
         
		<p align="justify" style="line-height: 100%">Si tiene m&aacute;s de un tipo de usuario como: (Decano, Coordinador &oacute; Docente), haga clic en el usuario deseado, en la lista &quot;<span class="Estilo5">Cambiar a Usuario</span>&quot;.</p>
		
		<p align="justify" style="line-height: 100%">Si cambia su correo electr&oacute;nico, direcci&oacute;n o tel&eacute;fono; no olvide actualizarlos en el men&uacute; &quot;<a href="coor_actualiza_dat.php" target="principal" onMouseOver="link();return true;" onClick="link();return true;">Datos Personales</a>&quot;. Recuerde que de la veracidad de sus datos, depende un efectivo ingreso al aplicativo.</p>
		
		<p align="justify" style="line-height: 100%">Con un efectivo control por parte de los usuarios, la informaci&oacute;n podr&aacute; ser completa y real, por lo que se sugiere que revise con especial cuidado y reporte a su Coordinador del Proyecto Curricular, cualquier inquietud o correcci&oacute;n que considere necesaria.</p>
		
        <p style="line-height: 100%" align="justify">La forma segura de salir de esta p&aacute;gina, es haciendo clic en el hiperv&iacute;nculo &quot;<a href="../conexion/salir.php" target="_top" onMouseOver="link();return true;" onClick="link();return true;" title="Salida segura"><strong>Salir</strong></a>&quot;. De esta forma nos aseguramos que otras personas no puedan manipular sus datos.</p>
		
      </td>
    </tr>
    <tr>
      <td width="100%" align="center" height="1">
        <hr noshade class="hr">
      </td>
    </tr>
    <tr>
      <td width="100%" align="center" height="1">
	  <a href="../generales/cambiar_mi_clave.php" target="_self" onMouseOver="link();return true;" onClick="link();return true;" title="Cambio de clave">Por seguridad, cambie su clave con frecuencia.</a></td>
    </tr>
	
	
</div><br><br><br>';
?>
</body>
</html>