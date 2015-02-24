<?PHP
//DEVENGOD
$devengos = "SELECT liq_emp_cod,
	liq_con_tipo,
	liq_con_cod,
	con_nombre,
	liq_valor,
	liq_dias,
	decode(liq_tq_cod ,1,'Primera'
	,2,'Segunda'
	,3,'Mes'
	,4,'Intereses a la Cesantia'
	,5,'Prima Semestral'
	,6,'Prima de Vacaciones'
	,7,'Sueldo de Vacaciones'
	,8,'Prima de Navidad'
	,0,'Retroactivo'),
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
	decode(liq_cuotas,0,' ',999,' ',liq_cuotas)
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