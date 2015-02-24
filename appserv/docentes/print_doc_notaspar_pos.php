<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);
?>
<HTML>
<HEAD><TITLE>Notas Parciales</TITLE>
</HEAD>
<BODY topmargin="0" leftmargin="0">

<?php
$docnroiden = $_SESSION['usuario_login'];
fu_print_cabezote("NOTAS PARCIALES");

$estado = 'P';
require_once(dir_script.'msql_notaspar_doc.php');
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consulta,"busqueda");
$row = $consulta;
//---------------------------
echo "<style>
table.notas { border: 1px solid; border-collapse: collapse; }
table.notas td{ border: 1px solid; border-collapse: collapse; }

</style>";
echo'<div align="center">
  <table class="notas" width="670" cellspacing="0" cellpadding="0">
  <tr>
  <td width="10%" align="right"><font face="Tahoma" size="2">'.$consulta[0][4].'</font></td>
  <td width="60%"><font face="Tahoma" size="2"><b>'.$consulta[0][5].'</b></font></td>
  <td width="10%" align="center"><font face="Tahoma" size="2"><b>Grupo</b></font></td>
  <td width="10%" align="center"><font face="Tahoma" size="2"><b>Inscritos</b></font></td>
  <td width="10%" align="center"><font face="Tahoma" size="2"><b>Periodo</b></font></td>
  </tr>
  <tr>
  <td width="10%" align="right"><font face="Tahoma" size="2">'.$consulta[0][0].'</font></td>
  <td width="60%" align="left"><font face="Tahoma" size="2"><b>'.$consulta[0][1].'</b></font></td>
  <td width="10%" align="center">'.$consulta[0][6].'</td>
  <td width="10%" align="center"><font face="Tahoma" size="2">'.$consulta[0][30].'</font></td>
  <td width="10%" align="center"><font face="Tahoma" size="2">'.$consulta[0][2].'-'.$consulta[0][4].'</font></td>
  </tr>
  </table>
  </center>
</div>

<div align="center">
  <center>
  <table border="0" width="670" cellspacing="0" cellpadding="2">
    <tr>
      <td width="320"></td>
      <td width="200" colspan="8" align="center"><font face="Tahoma" size="2"><b>PORCENTAJES DE NOTAS</b></font></td>
      <td width="27"></td>
    </tr>
    <tr>
      <td width="250"></td>
      <td width="25" align="center"><font face="Tahoma" size="2">%1</font></td>
      <td width="25" align="center"><font face="Tahoma" size="2">%2</font></td>
      <td width="25" align="center"><font face="Tahoma" size="2">%3</font></td>
      <td width="25" align="center"><font face="Tahoma" size="2">%4</font></td>
      <td width="25" align="center"><font face="Tahoma" size="2">%5</font></td>
      <td width="25" align="center"><font face="Tahoma" size="2">LAB</font></td>
      <td width="25" align="center"><font face="Tahoma" size="2">EXA</font></td>
      <td width="26" align="center"><font face="Tahoma" size="2">HAB</font></td>
      <td width="20" align="center"><font face="Tahoma" size="2"></font></td>
	  <td width="20" align="center"><font face="Tahoma" size="2"></font></td>
	  <td width="20" align="center"><font face="Tahoma" size="2"></font></td>
    </tr>
    <tr>
      <td width="250"></td>
      <td width="24" align="center">'.$consulta[0][11].'</td>
      <td width="25" align="center">'.$consulta[0][13].'</td>
      <td width="25" align="center">'.$consulta[0][15].'</td>
      <td width="25" align="center">'.$consulta[0][17].'</td>
      <td width="25" align="center">'.$consulta[0][19].'</td>
      <td width="25" align="center">'.$consulta[0][25].'</td>
      <td width="25" align="center">'.$consulta[0][21].'</td>
      <td width="26" align="center">'.$consulta[0][23].'</td>
      <td width="27" align="center"><font color="#ffffff" size="2" face="Tahoma">.</td>
	<td width="27" align="center"><font color="#ffffff" size="2" face="Tahoma">.</td>
	<td width="27" align="center"><font color="#ffffff" size="2" face="Tahoma">.</td>

    </tr>
  </table>
</div>';
?>
  <div align="center">
  <center>
  <table class="notas" width="670" cellspacing="0" cellpadding="1">
    <tr>
      <td width="70"></td>
      <td width="230"></td>
      <td width="200" colspan="8">
        <p align="right"><font face="Tahoma" size="2"><b>NOTAS PARCIALES</b></font></td>
      <td width="27"></td>
      <td width="27"></td>
      <td width="27"></td>
    </tr>
    <tr>
      <td width="20" align="center"><font face="Tahoma" size="2"><b>C&Oacute;DIGO</b></font></td>
      <td width="200" align="center"><font face="Tahoma" size="2"><b>NOMBRE</b></font></td>
      <td width="25" align="center"><font face="Tahoma" size="2"><b>1</b></font></td>
      <td width="25" align="center"><font face="Tahoma" size="2"><b>2</b></font></td>
      <td width="25" align="center"><font face="Tahoma" size="2"><b>3</b></font></td>
      <td width="25" align="center"><font face="Tahoma" size="2"><b>4</b></font></td>
      <td width="25" align="center"><font face="Tahoma" size="2"><b>5</b></font></td>
      <td width="25" align="center"><font face="Tahoma" size="2"><b>LAB</b></font></td>
      <td width="25" align="center"><font face="Tahoma" size="2"><b>EXA</b></font></td>
      <td width="25" align="center"><font face="Tahoma" size="2"><b>HAB</b></font></td>
      <td width="27" align="center"><font face="Tahoma" size="2"><b>ACU</b></font></td>
      <td width="25" align="center"><font face="Tahoma" size="2"><b>OBS</b></font></td>
      <td width="27" align="center"><font face="Tahoma" size="2"><b>DEF</b></font></td>
    </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr>
	<td width="20" align="right">'.$consulta[$i][7].'</td>
	<td width="200"><font face="Tahoma" size="1">'.$consulta[$i][8].'</td>
	<td width="24" align="center">'.$consulta[$i][10].'</td>
	<td width="25" align="center">'.$consulta[$i][12].'</td>
	<td width="25" align="center">'.$consulta[$i][14].'</td>
	<td width="25" align="center">'.$consulta[$i][16].'</td>
	<td width="25" align="center">'.$consulta[$i][18].'</td>
	<td width="25" align="center">'.$consulta[$i][22].'</td>
	<td width="25" align="center">'.$consulta[$i][20].'</td>
	<td width="26" align="center">'.$consulta[$i][24].'</td>
	<td width="27" align="center">'.$consulta[$i][29].'</td>
	<td width="27" align="center">'.$consulta[$i][27].'</td>
	<td width="27" align="center">'.$consulta[$i][26].'</td>
	</tr>';
	$i++;
	}
?>
<tr><td colspan="13" align="right" style="font-size:9px">Dise&ntilde;o: Oficina Asesora de Sistemas</td></tr>
</table>
<div align="center">
<br><br><br>
<table border="0" width="80%" cellspacing="1"><tr>
  <td width="25%">
    <p align="center">---------------------------</td>
  <td width="25%"></td>
  <td width="25%"></td>
  <td width="25%">
    <p align="center">---------------------------</td></tr>
  <tr>
  <td width="25%"><p align="center"><font size="2" face="Tahoma">Firma del Docente</font></td>
  <td width="25%"></td>
  <td width="25%"></td>
  <td width="25%"><p align="center"><font size="2" face="Tahoma">Recibido</font></td></tr>
</table>
</div>
</BODY>
</HTML>