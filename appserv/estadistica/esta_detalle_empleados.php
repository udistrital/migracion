<?PHP
if(isset($_REQUEST['t']) == 'ADM')
{
	$QryAdm = "SELECT des_descripcion,COUNT(emp_nro_iden)
	FROM pecargo, pecargodes, peemp
	WHERE des_tc_cod = car_tc_cod
	AND car_tc_cod NOT IN('DP','DC','DH','PA','PD')
	AND car_cod = emp_car_cod
	AND emp_estado_e <> 'R'
	GROUP BY des_descripcion";
	
		$RowAdm = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryAdm,"busqueda");
	
	print'<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<caption>ADMINISTRATIVOS</caption>
		<tr class="tr">
			<td align="center">Tipo de Funcionario</td>
			<td align="center">Total</td>
		</tr>';
			$i=0;
			while(isset($RowAdm[$i][0]))
			{
				print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
				<td align="left">'.$RowAdm[$i][0].'</td>
				<td align="right">'.$RowAdm[$i][1].'</td></tr>';
				$i = $i+$RowAdm[$i][1];
				$i++;
			}
		print'<tr>
		<td align="right"><b>Total de Administrativos:</b></td>
		<td align="right"><b>'.$i.'</b></td>
	</table><p></p>';
}
elseif(isset($_REQUEST['t']) == 'DOP'){
	$QryAdm = "SELECT des_descripcion, COUNT(emp_nro_iden)
	FROM pecargo, pecargodes, peemp
	WHERE des_tc_cod = car_tc_cod
	AND car_tc_cod IN('DP','DC','DH')
	AND car_cod = emp_car_cod
	AND emp_estado_e <> 'R'
	GROUP BY des_descripcion";

	$RowAdm = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryAdm,"busqueda");
		
	print'<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<caption>DOCENTES DE PLANTA</caption>
	<tr class="tr">
	<td align="center">Tipo de Funcionario</td>
	<td align="center">Total</td>
	</tr>';
	$total=0;
	$i=0;
	while(isset($RowAdm[$i][0]))
	{
		print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		 <td align="left">'.$RowAdm[$i][0].'</td>
		 <td align="right">'.$RowAdm[$i][1].'</td></tr>';
		 $total = $total+$RowAdm[$i][1];
	$i++;
	}
	print'<tr>
	<td align="right"><b>Total de Docentes de Planta:</b></td>
	<td align="right"><b>'.$total.'</b></td>
	</table><p></p>';
}
elseif(isset($_REQUEST['t']) == 'DVE'){
	   print'<h3>Sin Detalle</h3>';
	   /*
	   $QryAdm = OCIParse($oci_conecta, "");
		OCIExecute($QryAdm) or die(ora_errorcode());
		$RowAdm = OCIFetch($QryAdm);
		
		print'<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<caption>DOCENTES DE VIN. ESPECIAL</caption>
		<tr class="tr">
		<td align="center">Tipo de Funcionario</td>
		<td align="center">Total</td>
		</tr>';
		$adm = 0; 
		do{ 
		 print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		 <td align="left">'.OCIResult($QryAdm,1).'</td>
		 <td align="right">'.OCIResult($QryAdm,2).'</td></tr>';
		 $adm = $adm+OCIResult($QryAdm,2);
		}while($RowAdm = OCIFetch($QryAdm));
		print'<tr>
		<td align="right"><b>Total Docentes de Vinculaci�n Especial:</b></td>
		<td align="right"><b>'.$adm.'</b></td>
		</table><p></p>';
		OCIFreeCursor($QryAdm);
		*/
}
elseif(isset($_REQUEST['t']) == 'PEN'){
	$QryAdm = "SELECT des_descripcion, COUNT(emp_nro_iden)
		FROM mntpe.pecargo, mntpe.pecargodes, mntpe.peemp
		WHERE des_tc_cod = car_tc_cod
		AND car_tc_cod IN('PA','PD')
		AND car_cod = emp_car_cod
		AND emp_estado_e <> 'R'
		GROUP BY des_descripcion";
	
	$RowAdm = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryAdm,"busqueda");
	
	print'<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<caption>PENSIONADOS</caption>
	<tr class="tr">
	<td align="center">Tipo de Funcionario</td>
	<td align="center">Total</td>
	</tr>';
	$i=0;
	while(isset($RowAdm[$i][0]))
	{
		print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		 <td align="left">'.$RowAdm[$i][0].'</td>
		 <td align="right">'.$RowAdm[$i][1].'</td></tr>';
		 $i = $i+$RowAdm[$i][1];
	$i++;
	}
	print'<tr>
	<td align="right"><b>Total de Pensionados:</b></td>
	<td align="right"><b>'.$i.'</b></td>
	</table><p></p>';
}
?>