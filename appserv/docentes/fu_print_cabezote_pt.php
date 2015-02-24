<?PHP
function fu_print_cabezote_pt($titulo){
	$fec = date("j-M-Y");
	$hor = date("g:i:s A");
	$escudo = dir_img.'Uescudo.gif';
echo <<< HTML
	<div align="center"><table border="0" width="100%">
	<tr><td width="14%" rowspan="2" align="center"><IMG SRC=$escudo></td>
    <td width="86%" align="center"><b><font face="Arial" size="+1">UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS
	</font></b></td></tr>
	<tr><td width="86%" align="center" valign="top"><b><font face="Arial" size="2"><br>$titulo<br>
    </font></b></td></tr></table>

	<table border="0" width="90%" cellpadding="2" height="3"><tr><td width="16%" align="center"><div align="right">
    <input name="button" type="button" onClick="javascript:window.print();" value="Impreso: " style="cursor:pointer" title="Clic par imprimir el reporte">
    </div></td>

    <td width="14%" align="center"><div align="left"><font face="Tahoma" size="2">$fec</font></div></td>
    <td width="14%" align="center"><div align="left"><font face="Tahoma" size="2">$hor</font></div></td>
    <td width="17%" align="center">&nbsp;</td>
    <td width="17%" align="center">&nbsp;</td>
    <td width="17%" align="center">&nbsp;</td>
	<td width="17%" align="center">&nbsp;</td>
    </tr></table></div><BR>
HTML;
}
?>