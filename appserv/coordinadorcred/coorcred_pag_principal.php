<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
include_once("../clase/funcionGeneral.class.php");
require_once("../clase/encriptar.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$cripto=new encriptar();

$funcion=new funcionGeneral();
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body>
<?php
fu_tipo_user(28);

$cedula = $_SESSION['usuario_login'];

$indice=$configuracion['host'].$configuracion['raiz_sga']."/index.php?";

//Descargar Manuales de usuario
$variable="pagina=admin_paginaPrincipalCoordinador";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&opcion=proyectos";
$variable.="&tipoUser=28";
$variable.="&modulo=Coordinador";
$variable.="&aplicacion=Condor";

$variable=$cripto->codificar_url($variable,$configuracion);
$enlacePagina=$indiceAcademico.$variable;

echo "<script>location.replace('".$indice.$enlacePagina."')</script>";

?>
</body>
</html>