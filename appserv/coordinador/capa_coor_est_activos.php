<?PHP
$capa = '<div id="cdfhijklmprstvxz" style="background-color:#FFFFE6; border-color:#FF3300; border-style:solid; border-width:thin; position:absolute; visibility:visible; text-align:justify; left: 231px; top:51px; width: 400px; height: 197px;">
<div align="right" style="width:auto; height:auto; padding:1">
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color:#FF3300;border-collapse:collapse">
<tr><td width="85%" align="left"><font color="#FFFFFF"><b>&nbsp;Atenci&oacute;n</b></font></td>
<td width="15%" align="center"><a href="javascript:void(0)" onClick="OcultarCapa(\'cdfhijklmprstvxz\')" style="cursor:pointer"><font color="#FFFFFF"><b>Cerrar</b></font></a></td></tr></table>
</div>
<br>
<strong>Se&ntilde;or Coordinador(a):</strong><br>Los estudiantes que se encuentran en los estados "A" y "B", son los &uacute;nicos que deben tener asignaturas inscritas.<br><br>
Para borrar los de estados diferentes, haga clic en la letra bajo la barra para revisar el listado y borrarles las asignaturas. Tenga en cuenta que estos cupos podr&iacute;an estar siendo necesitados por los estudiantes en estados activos.
<p></p></div>';

$aux=0;
if($totC > 0) $aux += $totC;
if($totD > 0) $aux += $totD;
if($totF > 0) $aux += $totF;
if($totH > 0) $aux += $totH;
if($totI > 0) $aux += $totI;
if($totJ > 0) $aux += $totJ;
if($totK > 0) $aux += $totK;
if($totL > 0) $aux += $totL;
if($totM > 0) $aux += $totM;
if($totP > 0) $aux += $totP;
if($totR > 0) $aux += $totR;
if($totS > 0) $aux += $totS;
if($totT > 0) $aux += $totT;
if($totV > 0) $aux += $totV;
if($totX > 0) $aux += $totX;
if($totZ > 0) $aux += $totZ;

if($aux > 0) print $capa;
?>