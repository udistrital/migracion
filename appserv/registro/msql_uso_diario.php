<?PHP
//INGREOSO POR DIA
$QryUsoDia = "SELECT rba_ape_ano, rba_ape_per, (TO_CHAR(rba_fecha,'DD/Mon/YYYY')), COUNT(*), (TO_CHAR(rba_fecha,'YYYYMMDD'))
		FROM acasperi, acrecbanasplog 
		WHERE ape_ano = rba_ape_ano 
		AND ape_per = rba_ape_per
		AND ape_estado = 'A'
		AND EXISTS (SELECT rba_ref_pago
		FROM acasperiadm, acrecbanasp
		WHERE ape_ano = rba_ape_ano
		AND ape_per = rba_ape_per
		AND ape_estado = 'X'
		AND rba_ref_pago = mntac.acrecbanasplog.rba_ref_pago)
		GROUP BY rba_ape_ano, rba_ape_per, (TO_CHAR(rba_fecha,'DD/Mon/YYYY')), (TO_CHAR(rba_fecha,'YYYYMMDD'))
		ORDER BY (TO_CHAR(rba_fecha,'YYYYMMDD'))";
?>