<?php
$cod_consul = "SELECT est_cod,
  					  est_nombre,
  					  est_nro_iden,
  					  cra_cod,
  					  cra_nombre,
  					  trunc(fa_promedio_nota(est_cod),2),
	   				  ins_asi_cod,
	   				  asi_nombre,
	   				  ins_gr,
					  CUR_PAR1,
	   				  INS_NOTA_PAR1,
					  CUR_PAR2,
	   				  INS_NOTA_PAR2,
					  CUR_PAR3,
	   				  INS_NOTA_PAR3,
					  CUR_PAR4,
	   				  INS_NOTA_PAR4,
					  CUR_PAR5,
	   				  INS_NOTA_PAR5,  
	   				  CUR_PAR6,
	   				  INS_NOTA_PAR6,
					  CUR_LAB,
	    			  	  INS_NOTA_LAB,
					  CUR_EXA,
	   				  INS_NOTA_EXA,
					  CUR_HAB,
	   				  INS_NOTA_HAB,
	   				  INS_NOTA_ACU,
	   				  INS_NOTA,
	   				  ins_obs,
                                          ins_ano,
                                          ins_per,
                                          (lpad(cur_cra_cod,3,0)||'-'||cur_grupo)  GRUPO
  				 FROM acest, accra, accursos, acins, acasi, acasperi
				WHERE ins_est_cod = $estcod
				  AND ins_asi_cod = cur_asi_cod
				  AND ins_gr = cur_id
				  AND ins_ano = cur_ape_ano
				  AND ins_per = cur_ape_per
				  AND ins_est_cod = est_cod
				  AND est_cra_cod = cra_cod
   				  AND ins_asi_cod = asi_cod
   				  AND ins_ano = ape_ano 
   				  AND ins_per = ape_per
   				  AND ape_estado = 'A'
			  ORDER BY ins_asi_cod,ins_gr";

$cod_consul_per_anterior = "SELECT est_cod,
  					  est_nombre,
  					  est_nro_iden,
  					  cra_cod,
  					  cra_nombre,
  					  trunc(fa_promedio_nota(est_cod),2),
	   				  ins_asi_cod,
	   				  asi_nombre,
	   				  ins_gr,
					  CUR_PAR1,
	   				  INS_NOTA_PAR1,
					  CUR_PAR2,
	   				  INS_NOTA_PAR2,
					  CUR_PAR3,
	   				  INS_NOTA_PAR3,
					  CUR_PAR4,
	   				  INS_NOTA_PAR4,
					  CUR_PAR5,
	   				  INS_NOTA_PAR5,  
	   				  CUR_PAR6,
	   				  INS_NOTA_PAR6,
					  CUR_LAB,
	    			  	  INS_NOTA_LAB,
					  CUR_EXA,
	   				  INS_NOTA_EXA,
					  CUR_HAB,
	   				  INS_NOTA_HAB,
	   				  INS_NOTA_ACU,
	   				  INS_NOTA,
	   				  ins_obs,
                                          ins_ano,
                                          ins_per,
                                          (lpad(cur_cra_cod,3,0)||'-'||cur_grupo)  GRUPO
  				 FROM acest, accra, accursos, acins, acasi, acasperi
				WHERE ins_est_cod = $estcod
				  AND ins_asi_cod = cur_asi_cod
				  AND ins_gr = cur_id
				  AND ins_ano = cur_ape_ano
				  AND ins_per = cur_ape_per
				  AND ins_est_cod = est_cod
				  AND est_cra_cod = cra_cod
   				  AND ins_asi_cod = asi_cod
   				  AND ins_ano = ape_ano 
   				  AND ins_per = ape_per
   				  AND ape_estado = 'P'
			  ORDER BY ins_asi_cod,ins_gr";

?>