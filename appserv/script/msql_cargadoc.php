<?php
$cod_consul = "SELECT distinct doc_nro_iden identificacion,
   					  LTRIM(doc_nombre||'  '||doc_apellido) nombre,
   					  dep_cod, 
   					  dep_nombre,
   					  cur_cra_cod,
   					  cra_nombre,
   					  tvi_cod,
   					  tvi_nombre,
					    CUR_ASI_COD,
   					  asi_nombre,
   					  (lpad(cur_cra_cod,3,0)||'-'||cur_grupo) grupo,
   					  cur_nro_ins,
					  cur_id
				   FROM accargas,acdocente,actipvin,acasi,accra,gedep,accursos,acasperi,achorarios
				  WHERE dep_cod = cra_dep_cod
   					AND car_tip_vin=tvi_cod
   					AND asi_cod = cur_asi_cod 
   					AND cur_ape_ano = ape_ano
   					AND cur_ape_per = ape_per
   					AND ape_estado = '$estado'
   					AND cur_id = hor_id_curso
   					AND hor_id = car_hor_id
   					AND car_doc_nro = $cedula
   					AND cra_cod = cur_cra_cod
   					AND doc_estado = 'A'
   					AND cra_estado = 'A'
   					AND car_doc_nro = doc_nro_iden
   					AND cur_estado = 'A'
   					AND car_estado = 'A'
			   ORDER BY dep_cod,cur_cra_cod,identificacion,tvi_cod,cur_asi_cod, grupo ASC";
?>
