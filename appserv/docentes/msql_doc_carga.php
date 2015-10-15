<?php
$QryCarga = "SELECT hor_cod codigo,
	hor_rango rango,
	coalesce((SELECT distinct (cur_asi_cod||' - '||(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)||' / '||coalesce(sal_nombre,hor_sal_id_espacio)||' <br/> '||sed_id||' / '||edi_nombre)
	FROM gehora,acasperi,gesede,accursos,achorarios,accargas,gesalones,geedificio
	WHERE ape_estado = 'A'
	AND x.hor_cod = hor_cod
	AND hor_dia_nro = 1
	AND ape_ano = cur_ape_ano
	AND ape_per = cur_ape_per
	AND hor_cod = hor_hora
	AND sed_id = sal_sed_id
	AND achorarios.hor_estado = 'A'
	AND hor_sal_id_espacio=sal_id_espacio
	AND sal_edificio=edi_cod
	AND cur_estado = 'A'
	AND cur_id = hor_id_curso
	AND hor_id = car_hor_id
	AND car_estado = 'A'
	AND car_doc_nro = ".$_SESSION['usuario_login']."),' ') LUNES,

	coalesce((SELECT distinct (cur_asi_cod||' - '||(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)||' / '||coalesce(sal_nombre,hor_sal_id_espacio)||' <br/> '||sed_id||' / '||edi_nombre)
	FROM gehora,acasperi,gesede,accursos,achorarios,accargas,gesalones,geedificio
	WHERE ape_estado = 'A'
	AND x.hor_cod = hor_cod
	AND hor_dia_nro = 2
	AND ape_ano = cur_ape_ano
	AND ape_per = cur_ape_per
	AND hor_cod = hor_hora
	AND sed_id = sal_sed_id
	AND achorarios.hor_estado = 'A'
	AND hor_sal_id_espacio=sal_id_espacio
	AND sal_edificio=edi_cod
	AND cur_estado = 'A'
	AND cur_id = hor_id_curso
	AND hor_id = car_hor_id
	AND car_estado = 'A'
	AND car_doc_nro = ".$_SESSION['usuario_login']."),' ') MARTES,
	coalesce((SELECT distinct (cur_asi_cod||' - '||(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)||' / '||coalesce(sal_nombre,hor_sal_id_espacio)||' <br/> '||sed_id||' / '||edi_nombre)
	FROM gehora,acasperi,gesede,accursos,achorarios,accargas,gesalones,geedificio
	WHERE ape_estado = 'A'
	AND x.hor_cod = hor_cod
	AND hor_dia_nro = 3
	AND ape_ano = cur_ape_ano
	AND ape_per = cur_ape_per
	AND hor_cod = hor_hora
	AND sed_id = sal_sed_id
	AND achorarios.hor_estado = 'A'
	AND hor_sal_id_espacio=sal_id_espacio
	AND sal_edificio=edi_cod
	AND cur_estado = 'A'
	AND cur_id = hor_id_curso
	AND hor_id = car_hor_id
	AND car_estado = 'A'
	AND car_doc_nro = ".$_SESSION['usuario_login']."),' ') MIERCOLES,
	coalesce((SELECT distinct (cur_asi_cod||' - '||(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)||' / '||coalesce(sal_nombre,hor_sal_id_espacio)||' <br/> '||sed_id||' / '||edi_nombre)
	FROM gehora,acasperi,gesede,accursos,achorarios,accargas,gesalones,geedificio
	WHERE ape_estado = 'A'
	AND x.hor_cod = hor_cod
	AND hor_dia_nro = 4
	AND ape_ano = cur_ape_ano
	AND ape_per = cur_ape_per
	AND hor_cod = hor_hora
	AND sed_id = sal_sed_id
	AND achorarios.hor_estado = 'A'
	AND hor_sal_id_espacio=sal_id_espacio
	AND sal_edificio=edi_cod
	AND cur_estado = 'A'
	AND cur_id = hor_id_curso
	AND hor_id = car_hor_id
	AND car_estado = 'A'
	AND car_doc_nro = ".$_SESSION['usuario_login']."),' ') JUEVES,
	coalesce((SELECT distinct (cur_asi_cod||' - '||(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)||' / '||coalesce(sal_nombre,hor_sal_id_espacio)||' <br/> '||sed_id||' / '||edi_nombre)
	FROM gehora,acasperi,gesede,accursos,achorarios,accargas,gesalones,geedificio
	WHERE ape_estado = 'A'
	AND x.hor_cod = hor_cod
	AND hor_dia_nro = 5
	AND ape_ano = cur_ape_ano
	AND ape_per = cur_ape_per
	AND hor_cod = hor_hora
	AND sed_id = sal_sed_id
	AND achorarios.hor_estado = 'A'
	AND hor_sal_id_espacio=sal_id_espacio
	AND sal_edificio=edi_cod
	AND cur_estado = 'A'
	AND cur_id = hor_id_curso
	AND hor_id = car_hor_id
	AND car_estado = 'A'
	AND car_doc_nro = ".$_SESSION['usuario_login']."),' ') VIERNES,
	coalesce((SELECT distinct (cur_asi_cod||' - '||(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)||' / '||coalesce(sal_nombre,hor_sal_id_espacio)||' <br/> '||sed_id||' / '||edi_nombre)
	FROM gehora,acasperi,gesede,accursos,achorarios,accargas,gesalones,geedificio
	WHERE ape_estado = 'A'
	AND x.hor_cod = hor_cod
	AND hor_dia_nro = 6
	AND ape_ano = cur_ape_ano
	AND ape_per = cur_ape_per
	AND hor_cod = hor_hora
	AND sed_id = sal_sed_id
	AND achorarios.hor_estado = 'A'
	AND hor_sal_id_espacio=sal_id_espacio
	AND sal_edificio=edi_cod
	AND cur_estado = 'A'
	AND cur_id = hor_id_curso
	AND hor_id = car_hor_id
	AND car_estado = 'A'
	AND car_doc_nro = ".$_SESSION['usuario_login']."),' ') SABADO,
	coalesce((SELECT distinct (cur_asi_cod||' - '||(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)||' / '||coalesce(sal_nombre,hor_sal_id_espacio)||' <br/> '||sed_id||' / '||edi_nombre)
	FROM gehora,acasperi,gesede,accursos,achorarios,accargas,gesalones,geedificio
	WHERE ape_estado = 'A'
	AND x.hor_cod = hor_cod
	AND hor_dia_nro = 7
	AND ape_ano = cur_ape_ano
	AND ape_per = cur_ape_per
	AND hor_cod = hor_hora
	AND sed_id = sal_sed_id
	AND achorarios.hor_estado = 'A'
	AND hor_sal_id_espacio=sal_id_espacio
	AND sal_edificio=edi_cod
	AND cur_estado = 'A'
	AND cur_id = hor_id_curso
	AND hor_id = car_hor_id
	AND car_estado = 'A'
	AND car_doc_nro = ".$_SESSION['usuario_login']."),' ') DOMINGO
	FROM gehora x
	WHERE hor_estado = 'A'
	ORDER BY hor_cod ASC";
	
?>
