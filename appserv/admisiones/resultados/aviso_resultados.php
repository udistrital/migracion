<?PHP
require_once('../conexion/conexion.php');

//VINCULO EN PAGINA PRINCIPAL. RESULTADO DE ADMISIONES

$confec = OCIParse($oci_conecta, "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual");
OCIExecute($confec) or die(ora_errorcode());
$rows = OCIFetch($confec);
$fechahoy = OCIResult($confec, 1);

$Qrey10 = OCIParse($oci_conecta, "SELECT NVL(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'),
										 NVL(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0'),
										 TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
										 TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
  				   				    FROM accaleventos,acasperiadm
 				  				   WHERE APE_ANO = ACE_ANIO
									 AND APE_PER = ACE_PERIODO
									 AND APE_ESTADO = 'X'
   									 AND ACE_COD_EVENTO = 20");
OCIExecute($Qrey10) or die(ora_errorcode());
$rowc = OCIFetch($Qrey10);
$fecini = OCIResult($Qrey10, 3);
$fecfin = OCIResult($Qrey10, 4);

if(OCIResult($Qrey10, 1) == 0 || OCIResult($Qrey10, 2) == 0)
   print"<p></p><center><div style='width:100%' class='Estilo13'>
   No se han programado fechas para la publicación de resultados.</div></center>";

if($fechahoy < OCIResult($Qrey10, 1))
   print"<p></p><center><div style='width:100%' class='Estilo6' align='justify'><span class='Estilo13'>Resultados:</span><br>
   La publicación de resultados de aspirantes, se hará a partir del $fecini.
   </font></div></center>";
   
if($fechahoy >= OCIResult($Qrey10, 1) && $fechahoy <= OCIResult($Qrey10, 2)){
	require_once('capa_resultados.html'); 
	print"<p></p><center><div style='width:100%' class='Estilo13'>
	<a href='resultados/index.php' title='Consulta de Resultados del Proceso de Admisiones.'>RESULTADOS DE ADMISIONES</a></div></center>";
}
OCIFreeCursor($confec);
OCIFreeCursor($Qrey10);
OCILogOff($oci_conecta);
?>