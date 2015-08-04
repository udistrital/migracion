<?PHP
//DEVENGOD
$devengos = "SELECT liq_emp_cod,
	liq_con_tipo,
	liq_con_cod,
	con_nombre,
	liq_valor,
	liq_dias,
	(CASE WHEN liq_tq_cod  = 1 THEN 'Primera'  
	WHEN liq_tq_cod  = 2 THEN 'Segunda'  
	WHEN liq_tq_cod  = 3 THEN 'Mes'  
	WHEN liq_tq_cod  = 4 THEN 'Intereses a la Cesantia'  
	WHEN liq_tq_cod  = 5 THEN 'Prima Semestral'  
	WHEN liq_tq_cod  = 6 THEN 'Prima de Vacaciones'  
	WHEN liq_tq_cod  = 7 THEN 'Sueldo de Vacaciones'  
	WHEN liq_tq_cod  = 8 THEN 'Prima de Navidad'  
	WHEN liq_tq_cod  = 0 THEN 'Retroactivo' END),
	initcap(mes_nombre),
	liq_ano
	FROM mntpe.prliquid, mntpe.prcon, gemes
	WHERE liq_emp_cod = ".$_SESSION["fun_cod"]."
	AND liq_ano = '".$_REQUEST['anio']."'
	AND liq_mes = '".$_REQUEST['mes']."'
	AND liq_tq_cod = '".$_REQUEST['tipq']."'
	AND liq_con_tipo = con_tipo
	AND liq_con_cod = con_cod
	AND mes_cod = liq_mes
	AND liq_con_tipo = 1
	ORDER BY con_tipo, con_cod";

//DESCUENTOS
$descto = "SELECT liq_emp_cod,
	liq_con_tipo,
	liq_con_cod,
	(CASE WHEN liq_con_cod=39 THEN con_nombre||' - '||(SELECT EPS_NOMBRE 
                FROM PEEMPEPS,PEEPS
                WHERE EPS_COD = EPE_EPS_COD
                AND EPE_EMP_COD =  liq_emp_cod
                AND epe_estado='A')
        WHEN liq_con_cod=91 THEN con_nombre||' - '||(SELECT FPE_NOMBRE 
                FROM PEEMPFONPEN,PEFONPEN
                WHERE FPE_COD = EFP_FPE_COD
                AND EFP_EMP_COD = liq_emp_cod
                AND efp_estado='A')
        ELSE con_nombre
        END) con_nombre,
	liq_valor,
	(CASE WHEN liq_cuotas = 0 THEN ' ' WHEN liq_cuotas = 999 THEN ' ' END)
	FROM mntpe.prliquid, mntpe.prcon
	WHERE liq_emp_cod = ".$_SESSION["fun_cod"]."
	AND liq_ano = '".$_REQUEST['anio']."'
	AND liq_mes = '".$_REQUEST['mes']."'
	AND liq_tq_cod = '".$_REQUEST['tipq']."'
	AND liq_con_tipo = con_tipo
	AND liq_con_cod = con_cod
	AND liq_con_tipo = 2
	ORDER BY con_tipo, con_cod";
?>