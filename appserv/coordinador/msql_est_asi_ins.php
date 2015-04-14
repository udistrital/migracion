<?php
$cod_consul = "SELECT distinct est_cod,
		est_nombre,
		est_nro_iden,
		cra_cod,
		cra_nombre,
		trunc(fa_promedio_nota(est_cod)::numeric,2),
		asi_cod,
		asi_nombre,
		(lpad(Cur_Cra_Cod::text,3,'0')||'-'||cur_Grupo),
		doc_nro_iden,
		(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))),
		doc_email,
                ins_gr
		FROM ACCRA
                INNER JOIN ACEST ON cra_cod=est_cra_cod
                INNER JOIN ACINS ON est_cra_cod=ins_cra_cod AND est_cod=ins_est_cod
                INNER JOIN ACASI ON asi_cod=ins_asi_cod
                INNER JOIN ACASPERI ON ape_ano=ins_ano AND ape_per=ins_per
                INNER JOIN ACCURSOS ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per AND ins_gr=cur_id AND cur_asi_cod=ins_asi_cod
                INNER JOIN ACHORARIOS ON hor_id_curso=cur_id
                LEFT OUTER JOIN ACCARGAS ON car_hor_id=hor_id
                LEFT OUTER JOIN ACDOCENTE ON car_doc_nro=doc_nro_iden
		WHERE est_estado_est IN($estados)
		AND ape_estado  = 'A'
		AND ins_estado  = 'A'
		AND est_cod =".$_REQUEST['estcod']."
		order by asi_cod";
?>