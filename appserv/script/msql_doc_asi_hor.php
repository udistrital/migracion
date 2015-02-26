<?php
$cod_consul = "SELECT dia_nombre,
		MIN(hor_hora),
		(MAX(hor_hora) + 1),
		coalesce(sal_nombre,hor_sal_id_espacio),
		sed_id,
		edi_nombre 
		FROM acasperi,achorarios,gedia,gesalones,gesede,geedificio,accursos
		WHERE ape_ano = cur_ape_ano
		AND ape_per = cur_ape_per
		AND ape_estado = 'A'
		AND cur_asi_cod =".$_REQUEST['asicod']."
		AND cur_id =".$_REQUEST['asigr']."
    		AND hor_id_curso=cur_id
		AND hor_estado = 'A'
		AND hor_dia_nro  = dia_cod
		AND hor_sal_id_espacio = sal_id_espacio
		AND sal_edificio=edi_cod
		AND sal_sed_id = sed_id
		GROUP BY dia_cod, dia_nombre,coalesce(sal_nombre,hor_sal_id_espacio),sed_id,edi_nombre 
		ORDER BY dia_cod, MIN(hor_hora) ASC";
?>
