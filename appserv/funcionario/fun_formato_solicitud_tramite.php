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
<head>
<title>Formato de Solicitud y Tr&aacute;mite</title>
<link href="../script/print_estilo.css" rel="stylesheet" type="text/css"/>
<script language="JavaScript" src="../script/clicder.js"></script>
</head>

<body background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:center">
<table width="90%" border="0" align="center">
  <tr>
    <td width="30%" align="center" valign="top"><img src="../img/12cw03007.png" alt="Universidad Distrital Francisco Jos&eacute; de Caldas" border="0"/><br/>
      <span class="titulo">Divisi&oacute;n de Recursos Humanos</span><br/>
      <span class="subtitulo">Formato de Solicitud y Tr&aacute;mite</span> </td>
    <td width="44%" align="center"><img src="../img/espacio.gif" width="221" height="8"/></td>
    <td width="26%" valign="bottom">
        <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
          <tr>
            <td valign="top">Radicado:<br/><img src="../img/espacio.gif" width="201" height="119"/></td>
          </tr>
        </table>
    </td>
  </tr>
</table>
<p></p>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="4">
<caption align="left" style="background-color:#D6D6D6">TR&Aacute;MITE A SOLICITAR</caption>
  <tr>
    <td align="left">Vacaciones</td>
    <td align="left"><label style="cursor:pointer" title="Vacaciones"><input type="checkbox" name="cb1" value="checkbox"/></label></td>
    <td align="left"><img src="../img/espacio.gif" width="50" height="1"></td>
    <td align="left">Licencia de paternidad </td>
    <td align="left"><label style="cursor:pointer" title="Licencia de paternidad"><input type="checkbox" name="cb9" value="checkbox"/></label></td>
    <td align="left"><img src="../img/espacio.gif" width="50" height="1"></td>
    <td align="left">Permiso</td>
    <td align="left"><label style="cursor:pointer" title="Permiso"><input type="checkbox" name="cb17" value="checkbox"/></label></td>
  </tr>
  <tr>
    <td align="left">Estado de cessant&iacute;as</td>
    <td align="left"><label style="cursor:pointer" title="Estado de cessant&iacute;as"><input type="checkbox" name="cb2" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Aixilio de defunci&oacute;n</td>
    <td align="left"><label style="cursor:pointer" title="Aixilio de defunci&oacute;n"><input type="checkbox" name="cb10" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Quinquenio</td>
    <td align="left"><label style="cursor:pointer" title="Quinquenio"><input type="checkbox" name="cb18" value="checkbox"/></label></td>
  </tr>
  <tr>
    <td align="left">Incapacidad</td>
    <td align="left"><label style="cursor:pointer" title="Incapacidad"><input type="checkbox" name="cb3" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Auxilio de libros</td>
    <td align="left"><label style="cursor:pointer" title="Auxilio de libros"><input type="checkbox" name="cb11" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Puntaje escalaf&oacute;n</td>
    <td align="left"><label style="cursor:pointer" title="Puntaje escalaf&oacute;n"><input type="checkbox" name="cb19" value="checkbox"/></label></td>
  </tr>
  <tr>
    <td align="left">Cesant&iacute;a definitiva</td>
    <td align="left"><label style="cursor:pointer" title="Cesant6iacute;a definitiva"><input type="checkbox" name="cb4" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Estado de vacaciones</td>
    <td align="left"><label style="cursor:pointer" title="Estado de vacaciones"><input type="checkbox" name="cb12" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Subsidio familiar</td>
    <td align="left"><label style="cursor:pointer" title="Subsidio familiar"><input type="checkbox" name="cb20" value="checkbox"/></label></td>
  </tr>
  <tr>
    <td align="left">Cesant&iacute;a parcial</td>
    <td align="left"><label style="cursor:pointer" title="Cesant&acute;ia parcial"><input type="checkbox" name="cb5" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Certificaciones</td>
    <td align="left"><label style="cursor:pointer" title="Certificaciones"><input type="checkbox" name="cb13" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Comisiones</td>
    <td align="left"><label style="cursor:pointer" title="Comisiones"><input type="checkbox" name="cb21" value="checkbox"/></label></td>
  </tr>
  <tr>
    <td align="left">Horas extras</td>
    <td align="left"><label style="cursor:pointer" title="Horas extras"><input type="checkbox" name="cb6" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Prestaciones sociales</td>
    <td align="left"><label style="cursor:pointer" title="Prestaciones sociales"><input type="checkbox" name="cb14" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Capacidad de endeudamiento</td>
    <td align="left"><label style="cursor:pointer" title="Capacidad de endeudamiento"><input type="checkbox" name="cb22" value="checkbox"/></label></td>
  </tr>
  <tr>
    <td align="left">Copia de resoluci&oacute;n</td>
    <td align="left"><label style="cursor:pointer" title="Copia de resoluci&oacute;n"><input type="checkbox" name="cb7" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Prima t&eacute;cnica</td>
    <td align="left"><label style="cursor:pointer" title="Prima t&eacute;cnica"><input type="checkbox" name="cb15" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Reintegro Retenciones en exceso sobre salarios </td>
    <td align="left"><label style="cursor:pointer" title="Reintegro Retenciones en exceso sobre salarios"><input type="checkbox" name="cb15" value="checkbox"/></label></td><td align="left"></td>
  </tr>
  <tr>
    <td align="left">Licencia de maternidad</td>
    <td align="left"><label style="cursor:pointer" title="Licencia de maternidad"><input type="checkbox" name="cb8" value="checkbox"/></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">Reajuste</td>
    <td align="left"><label style="cursor:pointer" title="Reajuste"><input type="checkbox" name="cb16" value="checkbox"/></label></td>
    <td align="left"></td>
    <td align="left"></td>
    <td align="left"></td>
  </tr>
</table>
<p></p>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="4">
<caption align="left" style="background-color:#D6D6D6">DISMINUCI&Oacute;N RETENCI&Oacute;N EN LA FUENTE</caption>
  <tr>
    <td width="27%" align="right">Salud</td>
    <td width="3%" align="left"><label style="cursor:pointer" title="Salud"><input name="drf" type="radio" value="radiobutton"></label></td>
    <td width="27%" align="right">Educaci&oacute;n</td>
    <td width="4%" align="left"><label style="cursor:pointer" title="Educaci&oacute;n"><input name="drf" type="radio" value="radiobutton"></label></td>
    <td width="34%" align="right"right>Vivienda</td>
    <td width="5%" align="left"><label style="cursor:pointer" title="Vivienda"><input name="drf" type="radio" value="radiobutton"></label></td>
  </tr>
</table>
<p></p>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="4">
<caption align="left" style="background-color:#D6D6D6">CAMBIO CUENTA PAGO DE N&Oacute;MINA</caption>
  <tr>
    <td width="21%" align="left">Cuenta corriente 
    <input name="ccpn" type="radio" value="radiobutton"></td>
    <td width="2%" align="left"><label style="cursor:pointer" title="Cuenta corriente"></label></td>
    <td width="17%" align="right" >Ahorro</td>
    <td width="3%" align="left"><label style="cursor:pointer" title="Ahorro"><input name="ccpn" type="radio" value="radiobutton"></label></td>
    <td width="19%" align="right">N&uacute;mero de cuenta</td>
    <td width="38%" align="left"><input type="text" name="nrocta" style="width:199"></td>
  </tr>
  <tr>
    <td align="left">Entidad financiera </td>
    <td colspan="5" align="left"><input type="text" name="entidad" style="width:505"></td>
  </tr>
</table>
<p></p>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>Otros (especifique, cu&aacute;l?) </td>
    <td><input type="text" name="otro" style="width:500"></td>
  </tr>
  <tr>
    <td>Observaciones</td>
    <td><input type="text" name="obs" style="width:500"></td>
  </tr>
  <tr>
    <td>Documentos que anexa </td>
    <td><input type="text" name="anexa" style="width:500"></td>
  </tr>
</table>
<p></p>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="4">
<caption align="left" style="background-color:#D6D6D6">DATOS DEL PETICIONARIO</caption>
  <tr>
    <td width="26%" align="right">Activo</td>
    <td width="8%" align="left"><label style="cursor:pointer" title="Activo"><input name="cb25" type="radio" value="radiobutton"></label></td>
    <td width="26%" align="right">Retirado</td>
    <td width="8%" align="left"><label style="cursor:pointer" title="Retirado"><input name="cb25" type="radio" value="radiobutton"></label></td>
    <td width="25%" align="right">Pensionado</td>
    <td width="7%" align="left"><label style="cursor:pointer" title="Pensionado"><input name="cb25" type="radio" value="radiobutton"></label></td>
  </tr>
  <tr>
    <td align="center" colspan="6">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2">Nombres y apellidos completos </td>
          <td>Documento de identidad </td>
        </tr>
        <tr>
          <td colspan="2"><input type="text" name="nombre" style="width:474" value="<? print $Rowdatos[0][1];?>" readonly></td>
          <td><input type="text" name="cc" style="width:225" value="<? print $_SESSION['usuario_login'];?>" readonly></td>
        </tr>
        <tr>
          <td>Cargo</td>
          <td>Dependencia</td>
          <td>Correo electr&oacute;nico </td>
        </tr>
        <tr>
          <td><input type="text" name="cargo" style="width:228; font-size:11px" value="<? print $Rowdatos[0][15];?>" readonly></td>
          <td><input type="text" name="dep" style="width:228; font-size:11px" value="<? print $Rowdatos[0][16];?>" readonly></td>
          <td><input type="text" name="email" style="width:225; font-size:11px" value="<? print $Rowdatos[0][20];?>" readonly></td>
        </tr>
        <tr>
          <td>Direcci&oacute;n</td>
          <td>&nbsp;</td>
          <td>Tel&eacute;fono</td>
        </tr>
        <tr>
          <td><input type="text" name="dir" style="width:228; font-size:11px" value="<? print $Rowdatos[0][9];?>" readonly></td>
          <td>&nbsp;</td>
          <td><input type="text" name="tel" style="width:225" value="<? print $Rowdatos[0][11];?>" readonly></td>
        </tr>
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="../img/espacio.gif" width="300" height="1"></td>
    <td align="center">-------------------------------------------------------------------------------</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">Firma</td>
  </tr>
</table>
<p></p>
<p align="center"><input name="button" type="button" onClick="javascript:window.print();" value="Imprimir" style="cursor:pointer; width:150" title="Clic par imprimir el reporte"></p>
<? 
?>
</body>
</html>
