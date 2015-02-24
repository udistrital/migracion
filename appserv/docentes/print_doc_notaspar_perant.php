<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
?>
<HTML>
<HEAD><TITLE>Notas Parciales</TITLE>
<link href="../script/print_estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY topmargin="0" leftmargin="0">

<?php
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);
$docnroiden = $_SESSION['usuario_login'];

fu_print_cabezote("NOTAS PARCIALES");
$estado = 'P';

$_SESSION["A"] = $_REQUEST['asicod'];
$_SESSION["G"] = $_REQUEST['asigr'];

$consultapa = "SELECT doc_nro_iden eca_nro_iden, 
		(doc_nombre||' '||doc_apellido) eca_nombre, 
		cur_ape_ano, 
		cur_ape_per,
		cur_asi_cod, 
		asi_nombre, 
		cur_nro, 
		ins_est_cod, 
		substr(est_nombre, 1,32),
		est_estado_est,
		ins_nota_par1, 
		cur_par1, 
		ins_nota_par2, 
		cur_par2, 
		ins_nota_par3, 
		cur_par3, 
		ins_nota_par4, 
		cur_par4,
		ins_nota_par5, 
		cur_par5,
		ins_nota_exa, 
		cur_exa, 
		ins_nota_lab, 
		cur_hab, 
		ins_nota_hab, 
		cur_lab, 
		ins_nota, 
		ins_obs,
		cur_hab, 
		ins_nota_acu,
		cur_nro_ins
		FROM acinshis, accursohis, acasperi, acasi, acest, accargahis, acdocente, acdoctipvin
		WHERE doc_nro_iden = $docnroiden
		AND asi_cod =".$_SESSION["A"]."
		AND cur_nro =".$_SESSION["G"]."
		AND cur_ape_ano = ins_ano
		AND cur_ape_per = ins_per
		AND cur_asi_cod = ins_asi_cod
		AND cur_asi_cod = asi_cod
		AND cur_nro = ins_gr
		AND cur_ape_ano = ape_ano
		AND cur_ape_per = ape_per
		AND ape_estado = '$estado'
		AND ins_ano = ape_ano
		AND ins_per = ape_per
		AND ins_est_cod = est_cod
		AND ins_estado = 'A'
		AND cur_asi_cod = car_cur_asi_cod
		AND cur_nro = car_cur_nro
		AND cur_ape_ano = car_ape_ano
		AND cur_ape_per = car_ape_per
		AND cur_cra_cod = car_cra_cod
		AND car_doc_nro_iden = doc_nro_iden
		AND cur_ape_ano = dtv_ape_ano
		AND cur_ape_per = dtv_ape_per
		AND car_doc_nro_iden = dtv_doc_nro_iden
		AND car_cra_cod = dtv_cra_cod
		AND cur_estado = 'A'
		AND car_estado = 'A'
		ORDER BY cur_asi_cod, cur_nro, ins_est_cod";
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consultapa,"busqueda");
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
  <td width="10%" align="center">'.$consulta[0][2].'-'.$consulta[0][3].'</td>
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
      <td width="20" align="center"><b>C&Oacute;DIGO</b></td>
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
<tr><td colspan="13" align="right" style="font-size:9px">Dise&ntilde;&oacute;: Oficina Asesora de Sistemas</td></tr>
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
<p>&nbsp;</p>
</BODY>
</HTML>