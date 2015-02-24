<?php
$cod_consul = "SELECT dia_nombre,
                        MIN(hor_hora),
                        (MAX(hor_hora) + 1),
                        sal_nombre,
                        sed_abrev
                        FROM acasperi, achorarios, gedia, gesalones, gesede,accursos
                        WHERE ape_ano = cur_ape_ano
                        AND ape_per = cur_ape_per
                        AND ape_estado = 'A'
                        AND cur_asi_cod =".$_GET['asicod']."
                        AND cur_id =".$_GET['asigr']."
                        AND cur_id=hor_id_curso
                        AND hor_estado = 'A'
                        AND cur_estado = 'A'
                        AND hor_dia_nro  = dia_cod
                        AND hor_sal_id_espacio = sal_id_espacio
                        AND sal_sed_id = sed_id
                        GROUP BY dia_cod, dia_nombre, sal_nombre, sed_abrev
                        ORDER BY dia_cod, MIN(hor_hora) ASC";
?>