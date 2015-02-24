<?php
   $pag = 4;
	//---------- Carga por carrera ---------'
function cargadoc($usuario, $cra) {	
	global $oci_cn;
	global $porcent;
	global $acumevals;
	global $acumgrupos;
	global $acumins;
	
// = ftocoordev, funaccess... vu2_coordinador------------
$sql_ca = "SELECT DISTINCT(doc_nro_iden),
				 (LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, 
				 cra_cod,cra_nombre,tvi_cod,tvi_nombre,asi_nombre,asi_cod,cur_nro
			 FROM acdocente, acasperi, acdoctipvin, accra, actipvin,accarga,accurso,acasi
			 WHERE doc_nro_iden = :usevaluador AND cra_cod = :cra
				 AND ape_ano = dtv_ape_ano 
				 AND ape_per = dtv_ape_per
				 AND ape_estado = 'A' 
				 AND doc_nro_iden = dtv_doc_nro_iden  AND doc_estado = 'A'
				 AND dtv_estado = 'A' 
				 AND dtv_cra_cod = cra_cod 
				 AND dtv_tvi_cod = tvi_cod   
				 AND cur_nro = car_cur_nro
				 AND ape_ano = car_ape_ano AND ape_ano = cur_ape_ano AND ape_per = cur_ape_per 
			     AND ape_per = car_ape_per AND car_estado = 'A' AND cur_estado = 'A' 
				 AND asi_cod = cur_asi_cod 
			     AND asi_cod = car_cur_asi_cod 
			  	 AND car_doc_nro_iden = doc_nro_iden 
			  	 AND cur_cra_cod = cra_cod 
			  	 AND cur_cra_cod = car_cra_cod
				 ORDER BY tvi_cod,doc_nombre";

				//---------------------------- ******************** ------------------\\
	$fmto=$fmtonum;
    $rs_ca = ociexebind($oci_cn,$sql_ca,990,4,$usuario,$cra);
	if ($rs_ca != -1){
		$row2 = OCIFetch($rs_ca);		 //Incluir carga del docente según carrera;
		if (!$row2) {?>
			<TR ><TD colspan=4 width="92%" align="center"><EM>------- Sin carga registrada -------</EM></EM></TD></TR>  <?
		} else {?>
			<TR>
					<TD colspan=4 width="58%" align="center"><font size=2 ><? echo "Asignatura"?> </font></TD>
					<TD colspan=4 width="12%" align="center"><font size=2 ><? echo "C&oacute;digo"?> </font></TD>
					<TD colspan=4 width="8%" align="center"><font size=2 ><? echo "Grupo"?> </font></TD>
					<TD colspan=4 width="12%" align="center"><font size=2 ><? echo "Evals./Inscritos"?> </font></TD>
					<TD colspan=4 width="12%" align="center"><font size=2 ><? echo "%Evals."?> </font></TD>
			</TR> <?
			$i=0;$j=0;
			do { 
				$i++;
				$reg_ca[$i][1] = substr(OCIResult($rs_ca,7),0,60);//asignatura
				$reg_ca[$i][2] = substr(OCIResult($rs_ca,8),0,12); //código
				$reg_ca[$i][3] = substr(OCIResult($rs_ca,9),0,2);//grupo
			} While (OCIFetch($rs_ca)); 
			$k=0;
			ocifreestatement($rs_ca); 
			ocilogoff($oci_cn);
			for ($k=1;$k<=$i;$k++) {		?>
				<TR>
					<TD colspan=4 width="60%"><EM><font size=2 ><? echo $reg_ca[$k][1]?></font></EM></TD>
					<TD colspan=4 width="12%" align="center"><EM><font size=2 ><? echo $reg_ca[$k][2]?></font></EM></TD>
					<TD colspan=4 width="8%" align="center"><EM><font size=2 ><? echo $reg_ca[$k][3]?></font></EM></TD>
					<TD colspan=4 width="18%" align="center"><EM><font size=2 ><?
										
					$sql_eve = "SELECT COUNT(epe_cur_asi_cod), cur_nro_ins
							  FROM  acasperi,accra,accurso,gedep,ACEVAPROEST
							  WHERE ape_ano = epe_ape_ano AND ape_per = epe_ape_per 
									AND ape_ano = cur_ape_ano AND ape_per = cur_ape_per AND cra_cod = cur_cra_cod
									AND dep_cod = cra_dep_cod AND epe_doc_nro_iden = ".$usuario."
									AND cur_asi_cod = epe_cur_asi_cod AND epe_cur_asi_cod = ".$reg_ca[$k][2]."
									AND cur_nro = epe_cur_nro AND epe_cur_nro = ".$reg_ca[$k][3]." 
							  GROUP BY dep_nombre,cra_cod,cra_nombre,cur_nro_ins,epe_cur_asi_cod,epe_cur_nro,cur_nro
							  ORDER BY dep_nombre,cra_cod,epe_cur_asi_cod,cur_nro";		
					//echo $sql_eve;
					$rs_eve = ociexe($oci_cn,$sql_eve,991,4);
					$acumevals = 0; $nroins = 0;
					if ($rs_eve != -1){
						$row3 = OCIFetch($rs_eve);	
						if (!$row3) {
							$v = "0/0";
						} else {
							$acumevals = OCIResult($rs_eve,1);
							$nroins = OCIResult($rs_eve,2);
							$v = $acumevals."/".$nroins;
						}
					}
					ocifreestatement($rs_eve); 
					OCILogOff($oci_cn);
					echo $v;?></font></EM></TD>
					<TD colspan=4 width='18%' align='center'><EM><?
					if ($nroins > 0 ) {
						$v = ($acumevals*100)/$nroins;
						$acumevals = substr($acumevals+$v,0,5);
						$acumgrupos++;
						$acumins = $acumins+$nroins;
						if ($v > $porcent){
							?> <font size=2> <? echo substr($v,0,2)."%";
						}else{
							?> <font size=2 color='red'><strong><? echo substr($v,0,2)."%"?></strong> <?
						}
					}else{
						$v ="0%";
						?> <font size=2 color='red'><strong><? echo $v; ?></strong> <?
					}
					
			}?></font></EM></TD></TR><?
		}
		//oci_close($rs_ca);
	}
}
?>
