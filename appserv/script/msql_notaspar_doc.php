<?php
if(isset($_REQUEST['as']))
{
    $_SESSION["A"]=$_REQUEST['as'];
}
if(isset($_REQUEST["gr"]))
{
    $_SESSION["G"]=$_REQUEST["gr"];
}

$cod_consulta = "SELECT distinct doc_nro_iden eca_nro_iden, 
		(doc_nombre||' '||doc_apellido) eca_nombre, 
		cur_ape_ano,
		cur_ape_per,
		cur_asi_cod,
		asi_nombre,
		(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo), 
		ins_est_cod,
		est_nombre,
		est_estado_est,
		ins_nota_par1, 
		cur_par1, 
		ins_nota_par2, 
		cur_par2, 
		ins_nota_par3, 
		cur_par3, 
		ins_nota_par4, 
		cur_par4,
		ins_nota_par5, 
		cur_par5,
		ins_nota_par6, 
		cur_par6,
		ins_nota_exa, 
		cur_exa, 
		ins_nota_lab, 
		cur_hab, 
		ins_nota_hab, 
		cur_lab, 
		ins_nota, 
		ins_obs,
		cur_hab, 
		ins_nota_acu,
		cur_nro_ins,
		cur_cra_cod,
		(coalesce(cur_par1,0)+coalesce(cur_par2,0)+coalesce(cur_par3,0)+coalesce(cur_par4,0)+coalesce(cur_par5,0)+coalesce(cur_exa,0)+coalesce(cur_lab,0)),
                cur_id
		FROM acins, accursos, acasperi, acasi, acest, accargas, acdocente, achorarios
		WHERE doc_nro_iden = $docnroiden
		AND asi_cod =".$_SESSION["A"]."
		AND cur_id =".$_SESSION["G"]."
		AND cur_ape_ano = ins_ano
		AND cur_ape_per = ins_per
		AND cur_asi_cod = ins_asi_cod
		AND cur_asi_cod = asi_cod
		AND cur_id = ins_gr
		AND cur_ape_ano = ape_ano
		AND cur_ape_per = ape_per
		AND ape_estado = '$estado'
		AND ins_est_cod = est_cod
		AND ins_estado = 'A'
		AND car_hor_id = hor_id
		AND hor_id_curso=cur_id
		AND car_doc_nro = doc_nro_iden
		AND cur_estado = 'A'
		AND car_estado = 'A'
		ORDER BY cur_asi_cod, cur_id, ins_est_cod";
?>