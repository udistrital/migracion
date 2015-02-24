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

?>
<HTML>
<HEAD><TITLE>Notas Parciales</TITLE>
<link href="../script/print_estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY topmargin="0" leftmargin="0">

<?php
fu_tipo_user(30);
ob_start();
$docnroiden = $_SESSION['usuario_login'];

fu_print_cabezote("NOTAS PARCIALES");
$estado = 'A';

require_once(dir_script.'msql_notaspar_doc.php');
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consulta,"busqueda");
$row = $consulta;
//---------------------------
echo'<table align="center" border="1" width="670" cellspacing="0" cellpadding="0">
  <tr><td width="10%" align="right">'.$consulta[0][4].'</td>
  <td width="60%"><b>'.$consulta[0][5].'</b></td>
  <td width="10%" align="center"><b>Grupo</b></td>
  <td width="10%" align="center"><b>Inscritos</b></td>
  <td width="10%" align="center"><b>Periodo</b></td>
  </tr>
  <tr>
  <td width="10%" align="right">'.$consulta[0][0].'</td>
  <td width="60%" align="left"><b>'.$consulta[0][1].'</b></td>
  <td width="10%" align="center">'.$consulta[0][6].'</td>
  <td width="10%" align="center">'.$consulta[0][30].'</td>
  <td width="10%" align="center">'.$consulta[0][2].'-'.OCIResult($consulta, 4).'</td>
  </tr>
  </table>
  
  <table align="center" border="0" width="670" cellspacing="0" cellpadding="2">
    <tr>
      <td width="320"></td>
      <td width="200" colspan="8" align="right"><b>PORCENTAJES DE NOTAS</b></td>
      <td width="27"></td>
    </tr>
    <tr>
      <td width="250"></td>
      <td width="25" align="center">%1</td>
      <td width="25" align="center">%2</td>
      <td width="25" align="center">%3</td>
      <td width="25" align="center">%4</td>
      <td width="25" align="center">%5</td>
      <td width="25" align="center">LAB</td>
      <td width="25" align="center">EXA</td>
      <td width="26" align="center">HAB</td>
      <td width="20" align="center"></td>
	  <td width="20" align="center"></td>
	  <td width="20" align="center"></td>
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
  </table>';
?>
  <table width="670" border="1" align="center" cellpadding="1" cellspacing="0">
    <tr>
      <td width="20"></td>
      <td width="230"></td>
      <td width="200" colspan="8">
        <p align="right"><b>NOTAS PARCIALES</b></td>
      <td width="27"></td>
      <td width="27"></td>
      <td width="27"></td>
    </tr>
    <tr>
      <td width="20" align="center"><b>CODIGO</b></td>
      <td width="200" align="center"><b>NOMBRE</b></td>
      <td width="25" align="center"><b>1</b></td>
      <td width="25" align="center"><b>2</b></td>
      <td width="25" align="center"><b>3</b></td>
      <td width="25" align="center"><b>4</b></td>
      <td width="25" align="center"><b>5</b></td>
      <td width="25" align="center"><b>LAB</b></td>
      <td width="25" align="center"><b>EXA</b></td>
      <td width="25" align="center"><b>HAB</b></td>
      <td width="27" align="center"><b>ACU</b></td>
      <td width="25" align="center"><b>OBS</b></td>
      <td width="27" align="center"><b>DEF</b></td>
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
<br><br><br>
<table align="center" border="0" width="80%" cellspacing="1"><tr>
  <td width="25%">
    <p align="center">---------------------------</td>
  <td width="25%"></td>
  <td width="25%"></td>
  <td width="25%">
    <p align="center">---------------------------</td></tr>
  <tr>
  <td width="25%"><p align="center"><font size="2" face="Tahoma">Firma del Docente</td>
  <td width="25%"></td>
  <td width="25%"></td>
  <td width="25%"><p align="center"><font size="2" face="Tahoma">Recibido</td></tr>
</table>
</BODY>
</HTML>