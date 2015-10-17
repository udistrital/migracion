<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_cabezote("DERECHOS PECUNIARIOS");
define('DIAS', 30);
define('CERTIFICADO_NOTAS', 0.5);
define('CONSTANCIA_ESTUDIO', 0.25);
define('COPIA_ACTA_GRADO', 2);
define('DERECHOS_GRADO', 5);
define('DUPLICADO_DIPLOMA', 4);
define('CARNE', 0.5);
define('DUPLICADO_CARNE', 1);
define('SISTEMATIZACION', 0.08);
define('INS_PRE', 3);
define('INS_POS', 4);
define('CURSOS_INTERMEDIOS', 7);

$anio_cativo = "SELECT TO_CHAR(current_timestamp, 'YYYY')";
$rows_anio = $conexion->ejecutarSQL($configuracion,$accesoOracle,$anio_cativo,"busqueda");
$anio = $rows_anio[0][0];

$smlv = "SELECT smi_valor FROM acsalmin WHERE smi_ano = $anio";
$row_smlv = $conexion->ejecutarSQL($configuracion,$accesoOracle,$smlv,"busqueda");
$minimo = $row_smlv[0][0];
$minimoD = $row_smlv[0][0]/DIAS;

$certificado_notas = number_format(round($minimoD * CERTIFICADO_NOTAS));
$constancia_estudio = number_format(round($minimoD * CONSTANCIA_ESTUDIO));
$copia_acta_grado = number_format(round($minimoD * COPIA_ACTA_GRADO));
$derechos_grado = number_format(round($minimoD * DERECHOS_GRADO));
$duplicado_diploma = number_format(round($minimoD * DUPLICADO_DIPLOMA));
$carne = number_format(round($minimoD * CARNE));
$duplicado_carne = number_format(round($minimoD * DUPLICADO_CARNE));
$sistematizacion = number_format(round($minimo * SISTEMATIZACION));
$ins_pre = number_format(round($minimoD * INS_PRE));
$ins_pos = number_format(round($minimoD * INS_POS));
$cursos_intermedios = number_format(round($minimoD * CURSOS_INTERMEDIOS));
?>
<html>
<head>
<title>Pecuniarios</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
</head>

<body>
<br>
  <center><span class="Estilo5">CONSEJO SUPERIOR UNIVERSITARIO<br>ACUERDO N&deg;. 005 DE MARZO 3 DE 1995</span></center>
  <table width="454" border="0" align="center">
    <tr>
      <td><div align="justify">
        <p>&quot;Por la cual se actualizan algunos derechos pecuniarios para el a&ntilde;o <? print $anio;?>&quot;.<br>
            Fijar los valores por concepto de derechos pecuniarios para el a&ntilde;o <? print $anio;?> de confirmaci&oacute;n con la siguiente tabla.<br>
            <br>
        </p>
        </div></td>
    </tr>
  </table>
  <table width="454" border="0" align="center">
    <tr>
      <td width="360">SALARIO M&Iacute;NIMO LEGAL MENSUAL VIGENTE PARA EL A&Ntilde;O <? print $anio;?></td>
      <td width="84" align="right">$<? print number_format($minimo); ?></td>
    </tr>
    <tr>
      <td>SALARIO M&Iacute;NIMO LEGAL DIARIO VIGENTE PARA EL A&Ntilde;O <? print $anio;?></td>
      <td align="right">$<? print number_format($minimoD); ?></td>
    </tr>
  </table>
  <p></p>
  <table width="454" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr class="tr">
      <td width="253" align="center">Concepto</td>
      <td width="58" align="center" title="C&oacute;digo del concepto">C&oacute;digo</td>
      <td width="52" align="center" title="Cantidad de salario m&iacute;nimo diario">Factor</td>
      <td width="81" align="center" title="Valor del concepto">Costo</td>
    </tr>
    <tr onMouseOver="this.className='raton_arr'" onMouseOut="this.className='raton_aba'">
      <td><div align="left">CERTIFICADO DE NOTAS </div></td>
      <td><div align="center">41</div></td>
      <td align="center"><? print CERTIFICADO_NOTAS; ?></td>
      <td align="right">$<? print $certificado_notas; ?></td>
    </tr>
    <tr onMouseOver="this.className='raton_arr'" onMouseOut="this.className='raton_aba'">
      <td><div align="left">CONSTANCIAS DE ESTUDIO </div></td>
      <td align="center">41</td>
      <td align="center"><? print CONSTANCIA_ESTUDIO;?></td>
      <td align="right">$<? print $constancia_estudio;?></td>
    </tr>
    <tr onMouseOver="this.className='raton_arr'" onMouseOut="this.className='raton_aba'">
      <td align="left">COPIAS DE ACTAS DE GRADO</td>
      <td align="center">50</td>
      <td align="center"><? print COPIA_ACTA_GRADO;?></td>
      <td align="right">$<? print $copia_acta_grado;?></td>
    </tr>
    <tr onMouseOver="this.className='raton_arr'" onMouseOut="this.className='raton_aba'">
      <td align="left">DERECHOS DE GRADO</td>
      <td align="center">50</td>
      <td align="center"><? print DERECHOS_GRADO;?></td>
      <td align="right">$<? print $derechos_grado;?></td>
    </tr>
    <tr onMouseOver="this.className='raton_arr'" onMouseOut="this.className='raton_aba'">
      <td align="left">DUPLICADO DE DIPLOMAS</td>
      <td align="center">50</td>
      <td align="center"><? print DUPLICADO_DIPLOMA;?></td>
      <td align="right">$<? print $duplicado_diploma;?></td>
    </tr>
    <tr onMouseOver="this.className='raton_arr'" onMouseOut="this.className='raton_aba'">
      <td align="left">CARN&Eacute;</td>
      <td align="center">42</td>
      <td align="center"><? print CARNE;?></td>
      <td align="right">$<? print $carne;?></td>
    </tr>
    <tr onMouseOver="this.className='raton_arr'" onMouseOut="this.className='raton_aba'">
      <td align="left">DUPLICADO DE CARN&Eacute;</td>
      <td align="center">42</td>
      <td align="center"><? print DUPLICADO_CARNE;?></td>
      <td align="right">$<? print $duplicado_carne;?></td>
    </tr>
    <tr onMouseOver="this.className='raton_arr'" onMouseOut="this.className='raton_aba'">
      <td align="left">SISTEMATIZACI&Oacute;N</td>
      <td align="center">&nbsp;</td>
      <td align="center"><? print SISTEMATIZACION;?></td>
      <td align="right">$<? print $sistematizacion;?></td>
    </tr>
    <tr onMouseOver="this.className='raton_arr'" onMouseOut="this.className='raton_aba'">
      <td align="left">FORMULARIO DE INSCRIPCI&Oacute;N (PREGRADO) </td>
      <td align="center">11</td>
      <td align="center"><? print INS_PRE;?></td>
      <td align="right">$<? print $ins_pre;?></td>
    </tr>
    <tr onMouseOver="this.className='raton_arr'" onMouseOut="this.className='raton_aba'">
      <td align="left">FORMULARIO DE INSCRIPCI&Oacute;N (POSGRADO)</td>
      <td align="center">12</td>
      <td align="center"><? print INS_POS;?></td>
      <td align="right">$<? print $ins_pos;?></td>
    </tr>
    <tr onMouseOver="this.className='raton_arr'" onMouseOut="this.className='raton_aba'">
      <td align="left">CURSOS VACACIONALES</td>
      <td align="center">31</td>
      <td align="center"><? print CURSOS_INTERMEDIOS;?></td>
      <td align="right">$<? print $cursos_intermedios;?></td>
    </tr>
    <tr>
      <td colspan="4"><p align="center" class="StoryTitle"><br>
        CONSIGNAR EN LA CUENTA DE LA UNIVERSIDAD DISTRITAL<br>N&deg;. 230-81461-8 DEL BANCO DE OCCIDENTE</p>
        <p></p>
     </td>
    </tr>
  </table>
</body>
</html>