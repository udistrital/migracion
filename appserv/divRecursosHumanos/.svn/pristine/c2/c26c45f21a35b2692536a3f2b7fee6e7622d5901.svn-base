<?PHP
//NUMERO DE CUENTA DONDE SE CONSIGNA EL SUELDO
$sqlcta = "SELECT ban_cod,
	ban_nom_banco,
	cem_nro_cta,
	DECODE(cem_tipo, 'A','Ahorro','C','Corriente'),
	ban_web
	FROM PRCTAEMP,gebanco
	WHERE ban_cod = cem_ban_cod
	AND cem_emp_cod = ".$_SESSION["fun_cod"];
?>