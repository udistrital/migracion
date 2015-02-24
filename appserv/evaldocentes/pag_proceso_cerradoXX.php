<?php 
require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');
require_once(dir_conect.'valida_pag.php');

include("funparamspag.php");

require_once(dir_eval.'conexion_ev06.php');

require_once(dir_conect.'fu_tipo_user.php');
include("vartextosfijos.php");

fu_tipo_user($_SESSION["usuario_nivel"]); // válido para pruebas, en producción debe corresponder al usuario que ingresa a evaluar 
   
	//Consultando separadadamente la vigencia del calendario
$sq_calev = " SELECT ROWNUM,TO_CHAR(ape_ano),TO_CHAR(ape_per),TO_CHAR(cal_cod_evento),cal_des_evento,";
$sq_calev .= "	   TO_CHAR(cal_fec_ini,'dd/mm/yyyy'),TO_CHAR(cal_fec_fin,'dd/mm/yyyy')";
$sq_calev .= "	   FROM acasperi,accalendario";
$sq_calev .= "	   WHERE ape_ano = cal_anio";
$sq_calev .= "	   		 AND ape_per = cal_periodo AND cal_estado = 'A' AND cal_cod_evento in(30,31,32,33,34,35)";
$sq_calev .= "	   order by cal_cod_evento";

echo $headuserpag	?>			
<BODY ><center>
				<font size = 5><EM>Proceso de Evaluaci&oacute;n Docente</EM></font><BR><?
					 $rs = ociexe($oci_cn,$sq_calev,1001,1);	//Página 10
					 if ($rs != -1){
						//print $sq_calev;
						$row = OCIFetch($rs);
						if  (!$row) {			//($rs.$EOF)
							?> <EM><font color="#336699">Programaci&oacute;n a&uacute;n no registrada en el sistema</font></EM> <?
						}
						else { 
							$cta = 0;
							do {
								if ($cta == 0){
									?><font size = 5><EM>Periodo Acad&eacute;mico 
									<? echo OCIResult($rs,2)." - ".OCIResult($rs,3) ?></EM></font><BR><BR><BR><BR><BR>
									<EM>Programaci&oacute;n de Actividades
									<BR><BR>
									<TABLE width="88%"BORDER=1 CELLSPACING=1 CELLPADDING=1 align="center">
									<tr>
										<td width="10%" align="center">Actividad</td>
										<td width="50%" align="center">Descripci&oacute;n</td>
										<td width="20%" align="center">Inicio</td>
										<td width="20%" align="center">Finalizaci&oacute;n</td>
									</tr><?
									$cta = 1;
								}?>
								<tr>
									<td align="center"><? echo OCIResult($rs,1)?></td>						
									<td ><? echo OCIResult($rs,5)?></td>
									<td align="center"><? echo OCIResult($rs,6)?></td>
									<td align="center"><? echo OCIResult($rs,7)?></td>
								</tr><?											
								
							}while (OCIFetch($rs));?>
							</TABLE><br /><br /><br />
							<?
						}
					 }
				echo "Fecha actual:     ".date("d-n-Y");?>
				<BR><BR><pre><font size = 2>
				</EM></pre></font><BR><BR><BR><BR>
				</center>
<?php 	echo $evcreds;?>
				</BODY>
				</HTML>
