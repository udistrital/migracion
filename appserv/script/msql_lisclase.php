<?php
$cod_consul = "SELECT cur_asi_cod,
	TRIM(asi_nombre),
        (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo),
        cur_id,
	cur_cra_cod,
	cra_nombre,
	cur_nro_cupo,
	cur_nro_ins,
	cur_ape_ano,
	cur_ape_per,
	ins_cra_cod,
	ins_est_cod,
	est_nombre,
	est_estado_est,
        CASE WHEN ins_sem=0 THEN 'Electiva'
        WHEN ins_sem=98 THEN 'CP'
        ELSE TO_CHAR(ins_sem,'999') END cur_semestre
	FROM accursos,accra,acasi,acasperi,acins,acest
	WHERE cur_asi_cod =".$_REQUEST['as']."
	AND cur_id =".$_REQUEST['cur']."
	AND cur_asi_cod = asi_cod
	AND cur_cra_cod = cra_cod
	AND cur_ape_ano = ape_ano
	AND cur_ape_per = ape_per
	AND ape_estado = '$estado'
	AND cur_asi_cod = ins_asi_cod
	AND cur_id     = ins_gr
	AND cur_ape_ano = ins_ano
	AND cur_ape_per = ins_per
	AND ins_est_cod = est_cod
	ORDER BY ins_est_cod";
?>
