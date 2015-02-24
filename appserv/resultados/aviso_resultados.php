<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<?PHP
include_once("../clase/multiConexion.class.php");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion(50);

//VINCULO EN PAGINA PRINCIPAL. RESULTADO DE ADMISIONES
$confec = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual";
$rowconfec = $conexion->ejecutarSQL($configuracion,$accesoOracle,$confec,"busqueda");
$fechahoy = $rowconfec[0][0];

$Qrey10 = "SELECT NVL(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'),
		NVL(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0'),
		TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
		TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
		FROM accaleventos,acasperiadm
		WHERE APE_ANO = ACE_ANIO
		AND APE_PER = ACE_PERIODO
		AND APE_ESTADO = 'X'
		AND ACE_COD_EVENTO = 20";
		
$rowc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$Qrey10,"busqueda");
$fecini = $rowc[0][2];
$fecfin = $rowc[0][3];

if(($rowc[0][0] == 0) || ($rowc[0][1] == 0))
{
	print"<p></p><center><div style='width:100%' class='Estilo13'>
	No se han programado fechas para la publicaci&oacute;n de resultados.</div></center>";
}
if($fechahoy < $rowc[0][0])
{
	print"<p></p><center><div style='width:100%' class='Estilo6' align='justify'><span>Resultados:</span><br>
	La publicaci&oacute;n de resultados de aspirantes, se har&aacute; a partir del $fecini.
	</font></div></center>";
}

if($fechahoy > $rowc[0][1])
{
	print"<p></p><center><div style='width:100%' class='Estilo6' align='justify'><span>Resultados:</span><br>
	La publicaci&oacute;n de resultados de aspirantes, se cerr&oacute; el $fecfin.
	</font></div></center>";
}
 
if(($fechahoy >= $rowc[0][0]) && ($fechahoy <= $rowc[0][1]))
{?>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<table width='50%' style="background-color:#E4E6DD;border-style:groove" align='center'>
<tr width='50%' align='center'>
<td width='50%' align='center'>
<!--<div align="right" style="background-color:#E4E5DB;width:auto; height:auto">
</div>
<center>-->
<img src="../img/12cw03002.png" border="0"><br>
<p class="Estilo13">
RESULTADOS DE ADMISIONES<br>
PRIMER PER&Iacute;ODO ACAD&Eacute;MICO A&Ntilde;O 2015<BR>
<BR>
PROGRAMAS DE PREGRADO<BR>
</p>
Gracias por preferir a la Universidad Distrital.
<BR>
<BR><!--
La publicaci&oacute;n de resultados de aspirantes, ser&aacute; el 23 de junio de 2013.
<a href="javascript:AbreVentana('index.php', 850, 650, 20, 20);">VER RESULTADOS </a>
</center>
<p></p></div>-->
</td>
</tr>
</table>
<?
	//require_once('capa_resultados.html'); 
	print"<p><center><div style='width:100%;font-size:25px' class='Estilo13' >
	<a href='index.php' title='Consulta de Resultados del Proceso de Admisiones.'>RESULTADOS DE ADMISIONES</a></div></center></p>";
}
?>
