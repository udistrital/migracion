<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_general.'msql_ano_per_resultado.php');
require_once('fu_pie_pagAdm.php');
include_once("../clase/multiConexion.class.php");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion(50);

$log = "<embed width='57' height='58' src='../img/cdrlogo.png'>";

if($_POST['cred']==""){
   header("Location: err/err_crednull.php");
   exit;
}
$QryDat = "SELECT asp_nombre||' '||asp_apellido,
										 ape_ano,
										 ape_per,
										 asp_nro_iden_act,
										 DECODE(asp_tip_doc_act, '1','CEDULA CIUDADANIA','2','TARJETA IDENTIDAD','3','CEDULA EXTRANJERIA.'),
										 TO_CHAR(asp_fecha_nac,'dd/mm/yyyy'),
										 asp_sexo,
										 DECODE(asp_estado_civil, 1,'SOLTERO',2,'CASADO', 3,'OTRO'),
										 (asp_tipo_sangre||asp_rh) rh,
										 asp_def_sit_militar,
										 asp_ser_militar,
										 cra_cod,
										 cra_nombre,
										 asp_cred,
										 asp_veces,
										 asp_cod_plantel,
										 asp_tipo_colegio,
										 asp_hermanos,
										 asp_fuera_bogota,
										 loc_nombre,
										 asp_telefono,
										 asp_estrato,
										 ti_cod,
										 ti_nombre,
										 asp_nro_iden, 
										 DECODE(asp_tip_doc, '1','CEDULA CIUDADANIA','2','TARJETA IDENTIDAD','3','CEDULA EXTRANJERIA'),
										 asp_snp,
										 asp_bio biologia,
										 asp_qui quimica,
										 asp_fis fisica,
										 asp_soc sociales,
										 asp_cie_soc cienciassociales,
										 asp_apt_verbal lenguaje,
										 asp_apt_mat matematicas,
										 asp_fil filosofia,
										 asp_his historia,
										 asp_geo geografia,
										 asp_ptos_cal
									FROM acasperiadm, actipins, accra, acasp,aclocalidad
								   WHERE ape_ano = asp_ape_ano
									 AND ape_per = asp_ape_per
									 AND ape_estado = 'X'
									 AND asp_cred = ".$_POST['cred']."
									 AND ti_cod = asp_ti_cod
									 AND cra_cod = asp_cra_cod
									 AND loc_ape_ano = ape_ano
									 AND loc_ape_per = ape_per
									 AND loc_nro = asp_localidad
									 AND loc_estado = 'A'";
									 
$RowDat = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryDat,"busqueda");

if(!is_array($RowDat))
{
	header("Location: ../aspirantes/err/err_aspirante.php");
	exit;
}
?>
<html>
<head>
<title>Comit&eacute; de Admisiones</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../script/KeyIntro.js"></script>
</head>
<body>
<?PHP
print'<table width="750" align="center" cellpadding="3" cellspacing="0" style="border-color:#999999; border-style:double">
  <tr bgcolor="#E4E5DB">
    <td width="98">
	  <a href="http://www.udistrital.edu.co" title="Universidad Distrital Francisco Jos&eacute; de Caldas" target="_self">
	  <img src="../img/EscudoUD.gif" width="90" height="110" border="0"></a>
	</td>
	<td width="677" align="center">
	  <br><img src="../img/12cw03003.png" border="0"><br>
      <span class="Estilo14">VICERRECTOR&Iacute;A ACAD&Eacute;MICA - COMIT&Eacute; DE ADMISIONES</span><br><br>
      <span class="Estilo12">CONSULTA DE ASPIRANTES PARA EL <br>'.$peri.' PER&Iacute;ODO ACAD&Eacute;MICO DE '.$ano.'</span>
	</td>
    <td width="97" align="center" title="Sistema de Informaci&oacute;n C&oacute;ndor">'.$log.'<span class="Estilo5"><br>C&Oacute;NDOR</span></td>
  </tr>
  <tr>
    <td colspan="3">
	
  <br>
  <table width="600" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#999999" style="border-collapse:collapse;border-style:solid;border-width:1">
  <tr>
    <td width="150" align="right"><span class="Estilo5">Nombre:</span></td>
    <td colspan="3">'.$RowDat[0][0].'</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Identificaci&oacute;n:</span></td>
    <td width="200">'.$RowDat[0][3].'</td>
    <td align="right"><span class="Estilo5">Tipo:</span></td>
    <td>'.$RowDat[0][4].'</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Fecha de Nacimiento:</span></td>
    <td>'.$RowDat[0][5].'</td>
    <td align="right"><span class="Estilo5">Sexo:</span></td>
    <td>'.$RowDat[0][6].'</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Estado Civil:</span></td>
    <td>'.$RowDat[0][7].'</td>
    <td align="right"><span class="Estilo5"> <!-- Tipo Sangre: --> </span></td>
    <td>'.$RowDat[0][8].'</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Defini&oacute; Situaci&oacute;n Militar:</span></td>
    <td>'.$RowDat[0][9].'</td>
    <td align="right"><span class="Estilo5">Prest&oacute;:</span></td>
    <td>'.$RowDat[0][10].'</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Localidad:</span></td>
    <td>'.$RowDat[0][19].'</td>
    <td align="right"><span class="Estilo5">Estrato:</span></td>
    <td>'.$RowDat[0][21].'</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Carrera:</span></td>
    <td colspan="3">'.$RowDat[0][12].'</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Tipo de Inscripci&oacute;n:</span></td>
    <td>'.$RowDat[0][23].'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Se presenta a la UD por:</span></td>
    <td>'.$RowDat[0][14].' Vez</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Docu. Presento ICFES:</span></td>
    <td>'.$RowDat[0][24].'</td>
    <td align="right"><span class="Estilo5">Tipo:</span></td>
    <td>'.$RowDat[0][25].'</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Registro del ICFES:</span></td>
    <td>'.$RowDat[0][26].'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" colspan="4"><span class="Estilo5">RESULTADOS DEL ICFES</span></td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Biolog&iacute;a:</span></td>
    <td>'.$RowDat[0][27].'</td>
    <td align="right"><span class="Estilo5">Qu&iacute;mica:</span></td>
    <td>'.$RowDat[0][28].'</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">F&iacute;sica:</span></td>
    <td>'.$RowDat[0][29].'</td>
    <td align="right"><span class="Estilo5">Sociales:</span></td>
    <td>'.$RowDat[0][30].'</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Ciencias Sociales:</span></td>
    <td>'.$RowDat[0][31].'</td>
    <td align="right"><span class="Estilo5">Lenguaje:</span></td>
    <td>'.$RowDat[0][32].'</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Matem&aacute;ticas:</span></td>
    <td>'.$RowDat[0][33].'</td>
    <td align="right"><span class="Estilo5">Filosof&iacute;a:</span></td>
    <td>'.$RowDat[0][34].'</td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo5">Historia:</span></td>
    <td>'.$RowDat[0][35].'</td>
    <td align="right"><span class="Estilo5">Geograf&iacute;a:</span></td>
    <td>'.$RowDat[0][36].'</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><span class="Estilo5">PUNTAJE:</span></td>
    <td>'.$RowDat[0][37].'</td>
  </tr>
</table>
<table width="600" border="0" align="center">
  <tr>
    <td width="33%" align="right"><form action="listados.php" method="post" name="list" target="_self"><input name="cl" type="submit" value="Consultar Listados" style="cursor:pointer" title="Consultar resultados de aspirantes en listados PDF."></form></td>
	<td width="33%" align="center"><form action="index.php" method="post" name="list" target="_self"><input name="cr" type="submit" value="Credencial" style="cursor:pointer" title="Consultar resultados de aspirantes por n&uacute;mero de credencial."></form></td>
    <td width="33%" align="center"><form action="http://www.udistrital.edu.co/" method="post" name="salida" target="_self"><input name="salir" type="submit" value="Salir" style="cursor:pointer" title="Salir de esta p&aacute;gina."></form></td>
  </tr>
 
</table>';
echo '<table>
		<tr>
			<td>
				  Se&ntilde;or aspirante, tenga en cuenta que si usted se present&oacute; con un ex&aacute;men de estado ICFES o SABER 11, presentado en septiembre del a&ntilde;o 2010, debe consultar el procedimiento establecido por el ICFES para la transformaci&oacute;n de su ex&aacute;men de estado a la escala hist&oacute;rica del ex&aacute;men SABER 11. Mayor informaci&oacute;n consultar las siguientes direcciones electr&oacute;nicas:<br>
				. http://www.icfes.gov.co/index.php?option=com_content&task=view&id=654&Itemid=959<br>
				. http://www.icfes.gov.co/index.php?option=com_docman&task=cat_view&gid=101&Itemid=650
			</td>
		</tr>
      </table>';
fu_pie();
	print'</td>
  </tr> 
  </tr>
</table>';
?>