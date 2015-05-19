<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(24);

$funcod = $_SESSION['usuario_login'];
require_once('msql_datos_fun.php');
$Rowdatos = $conexion->ejecutarSQL($configuracion,$accesoOracle,$datos,"busqueda");

?>
<html>
<head>
<title>Formato de reintegro de vacaciones</title>
<link href="../script/print_estilo.css" rel="stylesheet" type="text/css"/>
<script language="JavaScript" src="../script/clicder.js"></script>
</head>

<body>
<table width="65%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td scope="row"><img src="../img/12cw03002.png" width="50" height="66"></td>
    <td><div align="center"><img src="../img/12cw03003.png" width="360" height="37"><br>
      <img src="../img/12cw03005.png" width="360" height="19"><br>
    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p align="center">INFORME DE DISFRUTE DE VACACIONES</p>
<table align="center" width="62%"  border="1" cellspacing="0" cellpadding="2">
  <tr>
    <td colspan="2" align="left">NOMBRES Y APELLIDOS COMPLETOS:</td>
    <td colspan="2" align="left"><input type="text" name="nombre" style="width:350" value="<? print $Rowdatos[0][1];?>" readonly></td>
  </tr>
  <tr>
    <td width="10%" align="left">CARGO:</td>
    <td width="31%" align="left"><input type="text" name="cargo" style="width:200" value="<? print $Rowdatos[0][15];?>" readonly></td>
    <td align="left">DEPENDENCIA:</td>
    <td align="left"><input type="text" name="dependencia" style="width:263; font-size:10px" value="<? print $Rowdatos[0][16];?>" readonly></td>
  </tr>

  <tr>
    <td colspan="2" align="left">DIRECCI&Oacute;N RESIDENCIA:</td>
    <td colspan="2" align="left"><input type="text" name="dir" style="width:350" value="<? print $Rowdatos[0][9];?>"></td>
  </tr>
  <tr>
    <td colspan="2" align="left">CORREO ELECTR&Oacute;NICO: </td>
    <td colspan="2" align="left"><input type="text" name="correo" style="width:350" value="<? print $Rowdatos[0][20];?>"></td>
  </tr>
  <tr>
    <td align="left">TEL&Eacute;FONO:</td>
    <td align="left"><input type="text" name="tel" style="width:200" value="<? print $Rowdatos[0][11];?>"></td>
    <td width="9%" align="left"><div align="right">CELULAR:</div></td>
    <td align="left"><input type="text" name="cel" style="width:263"  value="<? $Rowdatos[0][12];?>"></td>
  </tr>
  <tr>
    <td align="left">C.C.:</td>
    <td align="left"><input type="text" name="cc" style="width:200" value="<? print $_SESSION['usuario_login'];?>" readonly></td>
    <td align="left"><div align="right">DE:</div></td>
    <td align="left"><input type="text" name="de" style="width:263" value="<? print $Rowdatos[0][4];?>" readonly></td>
  </tr>
  <tr>
    <td colspan="2" align="left">RESOLUCI&Oacute;N QUE CONCEDI&Oacute; EL DISFRUTE:</td>
    <td colspan="2" align="left"><input type="text" name="res" style="width:350"></td>
  </tr>
  <tr>
    <td colspan="2" align="left">FECHA DE SALIDA :</td>
    <td colspan="2" align="left"><input type="text" name="feci" style="width:350"></td>
  </tr>
  <tr>
    <td colspan="2" align="left">FECHA DE INGRESO:</td>
    <td colspan="2" align="left"><input type="text" name="fecf" style="width:350"></td>
  </tr>
  <tr>
    <td colspan="2" align="left" valign="top">OBSERVACIONES:</td>
    <td colspan="2" align="left"><textarea name="obs" cols="50" rows="5" style="font-size:11px; text-align:justify"></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="left"><img src="../img/espacio.gif" width="1" height="50"></td>
    <td colspan="2" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="left">FIRMA:..............................................</td>
    <td colspan="2" align="left">VoBo:........................................</td>
  </tr>
</table>


<br>
<p align="center">FIRMA JEFE Y/O DECANO:.............................................</p>
<p align="center"><strong>NOTA:</strong> DILIGENCIE TOTALMENTE ESTE INFORME</p>
<p align="center"><input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:150" title="Clic par imprimir el reporte"></p>
<? 
?>
</body>
</html>
