<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(24);
?>
<HTML>
<HEAD>
<TITLE>Funcionarios</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../calendario/javascripts.js"></script>
</HEAD>
<BODY topmargin="0">
<?php
fu_cabezote("DATOS PERSONALES");

$funcod = $_SESSION['usuario_login'];
$foto = fun_foto.$_SESSION['usuario_login'].'.jpg';

if(!file_exists($foto)) {
	$foto="../img/sinfoto.png";
	$imgfoto='<img border="0" src="'.$foto.'" width="130" height="100" alt="Sin foto almacenada">';
}
else{ $imgfoto='<img border="0" src="'.$foto.'" width="130" height="100" alt="Foto del Funcionario">'; }

require_once('msql_datos_fun.php');
$rowDatos=$conexion->ejecutarSQL($configuracion,$accesoOracle,$datos,"busqueda");

//echo $datos;


$_SESSION['ccfun']=$rowDatos[0][2];
if(!is_array($rowDatos))
{
	echo "<script>location.replace('../err/err_sin_registros.php')</script>";
	exit;
}

echo'<p>&nbsp;</p><form name="dat" method=post action="'.$_SERVER['PHP_SELF'].'">
  <table width="97%" border="1" align="center" '. $EstiloTab .'>
  <tr>
    <td width="19%">&nbsp;</td>
    <td width="15%" align="right"><span class="Estilo5">C&oacute;digo:</span></td>
    <td colspan="5">'.$rowDatos[0][0].'</td>
  </tr>
  <tr>
    <td rowspan="7"><div align="center">'.$imgfoto.'</div></td>
    <td align="right"><SPAN class=Estilo5>Nombre:</SPAN></td>
    <td colspan="5"><b>'.$rowDatos[0][1].'<b></b></b></td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>Identificaci&oacute;n:</SPAN></td>
    <td width="14%" align="left">'.$rowDatos[0][2].'</td>
    <td width="10%" align="right"><SPAN class=Estilo5>Tipo:</SPAN></td>
    <td width="23%" align="left">'.$rowDatos[0][3].'</td>
    <td width="3%" align="right"><SPAN class=Estilo5>De:</SPAN></td>
    <td width="16%">'.$rowDatos[0][4].'</td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>Fecha Nac:</SPAN></td>
    <td width="14%" align="left">'.$rowDatos[0][6].'</td>
    <td align="right"><SPAN class=Estilo5>Lugar Nac:</SPAN></td>
    <td colspan="3">'.$rowDatos[0][5].'</td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>Sexo:</SPAN></td>
    <td colspan="5">'.$rowDatos[0][7].'</td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>Estado Civil:</SPAN></td>
    <td colspan="5">'.$rowDatos[0][8].'</td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>Direcci&oacute;n:</SPAN></td>
    <td colspan="5">'.$rowDatos[0][9].'</td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>Ciudad:</SPAN></td>
    <td colspan="5">'.$rowDatos[0][10].'</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><SPAN class=Estilo5>Tel&eacute;fono:</SPAN></td>
    <td width="14%" align="left">'.$rowDatos[0][11].'</td>
    <td align="right"><SPAN class=Estilo5>Tel. Alt:</SPAN></td>
    <td align="left">'.$rowDatos[0][12].'</td>
    <td align="right"><SPAN class=Estilo5>Cel:</SPAN></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><SPAN class=Estilo5>Fecha Ingreso:</SPAN></td>
    <td colspan="5">'.$rowDatos[0][13].'</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><SPAN class=Estilo5>R&eacute;gimen:</SPAN></td>
    <td colspan="5">'.$rowDatos[0][14].'</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><SPAN class=Estilo5>Cargo:</SPAN></td>
    <td colspan="5">'.$rowDatos[0][15].'</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><SPAN class=Estilo5>Dependencia:</SPAN></td>
    <td colspan="5">'.$rowDatos[0][16].'</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><SPAN class=Estilo5>Edad:</SPAN></td>
    <td colspan="5">'.$rowDatos[0][18]. ' A&ntilde;os</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><SPAN class=Estilo5>Antiguedad:</SPAN></td>
    <td colspan="5">'.$rowDatos[0][19]. ' A&ntilde;os</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><SPAN class=Estilo5>Correo Electr&oacute;nico:</SPAN></td>
    <td colspan="5">'.$rowDatos[0][20].'</td>
  </tr>
</table>
  </form><p>&nbsp;&nbsp;</p>';
require_once('inconsistencia.php');
?>
</BODY>
</HTML>
