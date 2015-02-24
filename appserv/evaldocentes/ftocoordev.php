<?php	include("funparamspag.php");
		include ('funevcoord.php');//??		

	if (!isset($_SESSION['usuario_login'])) {
			   session_destroy();
			   }
	include("vartextosfijos.php");
	echo $headuserpag;
//--------****************************------------------------------
	$sec = $_SESSION["sec"];
	$fmtonum = $_SESSION["fmto".$sec];
	$docente = $_SESSION["docente".$sec];
	$vin = $_SESSION["usuario_nivel"];
	$nomdoc = $_SESSION["nomdoc".$sec];
	$acumevals = 0;
	$acumgrupos = 0;
	$acumins = 0;
		
		// --------- arreglo de nombres de vinculación ---\\
			// idem en vu2_decano y ftocoordev ---!!
	   	$vinsel2x[1] = 'Planta T. Completo';
	   	$vinsel2x[2] = 'T. Completo Ocasional';
	   	$vinsel2x[3] = 'Medio T. Ocasional';
	   	$vinsel2x[4] = 'H. C. (Contrato)';
	   	$vinsel2x[5] = 'H. C. (Honorarios)';
	   	$vinsel2x[6] = 'Planta Medio Tiempo';?>

<BODY bgcolor="c6c384" oncontextmenu="return false"><?
//----------------------------------------------------------------------?>
<form name="coordeval" method=post action="fungrabcoord.php" target="iframe_m"> <?
	
	switch ($sec) {
		case 1:	//Docentes..
			$docente = $_POST["doc"];
			$porcent = $_POST["ptje"];
			// validar sólo digitos numéricos ...
			
			$vin = 30;
/*			if (!empty($_POST['inc1'])) {$carga = $_POST['inc1'];}else {$carga=0;}
			if (!empty($_POST['inc2'])) {$auto = $_POST['inc2'];}else {$auto=0;}
			if (!empty($_POST['inc3'])) {$est = $_POST['inc3'];}else {$est=0;}
			if (!empty($_POST['inc4'])) {$cpc = $_POST['inc4'];}else {$cpc=0;}
*/		
	//echo " carga ".$carga."doc ".$doc." auto ".$auto." est ".$est." cpc ".$cpc;
//-------------------------------------------------------------------------------------------------------------
if ($docente == "") {echo "Para consultar digite una identificación de docente"; exit;}
		//Similares en vu2__coordinador, vu2_docente.. 
$sql_d = "SELECT doc_nro_iden,
					(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre,
					cra_cod,cra_nombre,tvi_cod,tvi_nombre
		FROM acdocente, acasperi, acdoctipvin, accra, actipvin,accarga,accurso,acasi
		WHERE doc_nro_iden = :usevaluador
				AND ape_ano = dtv_ape_ano
				AND ape_per = dtv_ape_per
				AND ape_estado = 'A'
				AND doc_nro_iden = dtv_doc_nro_iden  AND doc_estado = 'A'
				AND dtv_estado = 'A'
				AND dtv_cra_cod = cra_cod
				AND dtv_tvi_cod = tvi_cod
				AND cur_nro = car_cur_nro
				AND ape_ano = car_ape_ano AND ape_ano = cur_ape_ano 
				AND ape_per = cur_ape_per 
				AND ape_per = car_ape_per AND car_estado = 'A' 
				AND cur_estado = 'A' AND asi_cod = cur_asi_cod 
				AND asi_cod = car_cur_asi_cod 
				AND car_doc_nro_iden = doc_nro_iden 
				AND cur_cra_cod = cra_cod 
				AND cur_cra_cod = car_cra_cod
		GROUP BY doc_nro_iden, doc_nombre,doc_apellido,cra_cod,cra_nombre,tvi_cod,tvi_nombre
		ORDER BY cra_cod";
//-------------------------			
	     $rs = ociexebind($oci_cn,$sql_d,901,2,$docente,$xnull);
	  	 if ($rs != -1){
			$row = OCIFetch($rs); 
            if  (!$row) {
			      ?><EM><font color="#3336699">No existen registros de carga acad&eacute;mica para el docente <? echo $docente?></font></EM><BR> <?
            }else {?>
				<TABLE width="100%"BORDER=0 CELLSPACING=1 CELLPADDING=1>
					<tr><td align="center">Evaluaci&oacute;n Docente</td></tr>						
					<tr><td align="center">Periodo Acad&eacute;mico <? echo $evanio." - ".$evaper ?></td></tr>				
					<tr><td align="center">Informe de Evaluaciones</td></tr>		
				</TABLE><br /><?
				$var = 0;
				require_once("funvalidcalendario.php");
				  do { //Almacene en variables de sesion
					 $var = $var + 1;			
					 $tipovin = OCIResult($rs,5);
					 $nomtvi = substr(OCIresult($rs,6),0,40);
					 $cra = OCIResult($rs,3);
					 $cranom = OCIResult($rs,4);
					 $nomdoc = OCIResult($rs,2);
					 $numformato = obtenernumformato($tipovin,"auto");
					 $_SESSION["sec".$var] = "";
					 $_SESSION["cra".$var] = OCIResult($rs,3);
					 $_SESSION["docente".$var] =OCIResult($rs,1);
					 $_SESSION["fmto".$var] = $numformato;
					 $_SESSION["nomtvi".$var] = $nomtvi;
					 $_SESSION["cranom".$var] = $cranom;
					 $_SESSION["nomdoc".$var] = $nomdoc;
				  }while (OCIFetch($rs));
		 		  ocifreestatement($rs);
				  OCILogOff($oci_cn); 
					 
				  for($i=1;$i<=$var;$i++){	 
					 if ($i==1){?>
						<TABLE  width="98%"BORDER=0 CELLSPACING=1 CELLPADDING=1>
							<tr><td align="left" width="20%">Fecha</td>						
								<td align="left" width="80%"><strong><? echo date("Y-n-d") ?></strong></td>
							</tr>				
							<tr><td align="left" width="20%">Docente</td>						
								<td align="left" width="80%"><strong><? echo $_SESSION["nomdoc".$i] ?></strong></td>
							</tr>				
							<tr><td align="left" width="20%">Identificaci&oacute;n</td>						
								<td align="left" width="80%"><strong><? echo $_SESSION["docente".$i]?></strong></td>
							</tr>
						</TABLE><br />	
				    <? } ?>
						
						<TABLE width="98%"BORDER=0 CELLSPACING=1 CELLPADDING=1>
							<tr><td align="left"><strong><? echo $_SESSION["cra".$i]." - ".$_SESSION["cranom".$i]." - ".$_SESSION["nomtvi".$i] ?></strong></td></tr>						
						</TABLE>
						<TABLE width="68%"BORDER=0 CELLSPACING=1 CELLPADDING=1>
							<tr><td align="left" width="60%">Autoevaluación</td>
								<td align="left" width="10%"><? 
								$fecha = cta_autcpcev($_SESSION["docente".$i],$_SESSION["cra".$i],"a");
								if ($fecha == 0) {?> 
								<? echo "No"?><td align="left" width="30%"> </td><?
								}else {echo "S&iacute";?></td>
								<td align="left" width="30%">Fecha: <? echo $fecha?><? }?></td></tr>
							<tr><td align="left" width="60%">Evaluaci&oacute;n de Consejo Curricular</td>
								<td align="left" width="10%"><? 
								$fecha = cta_autcpcev($_SESSION["docente".$i],$_SESSION["cra".$i],"c");
								if ($fecha == 0) {?> 
								<? echo "No";
								?><td align="left" width="30%"> </td><?
								}else {echo "S&iacute";?></td>
								<td align="left" width="30%">Fecha: <? echo $fecha?><? }?></td></tr>
						</TABLE>
						<TABLE width="98%"BORDER=0 CELLSPACING=1 CELLPADDING=1>
							<tr><td align="left">Evaluaci&oacute;n de estudiantes:</td></tr>						
						</TABLE>
						<?
						blq ($_SESSION["docente".$i],$_SESSION["cra".$i],$vin,$i,0,$oci_cn);
					}
						
          } 						//------------- finaliza  $row
	} 							//------------- finaliza  $rs
			break; 

//------------------------------
		//		Registros de Evaluacion por Proyecto Curricular Seleccionado \\
//------------------------------			
		case 2:
			$cra = $_POST["cra"];
			$vin = 4;
			$porcent = $_POST["ptje"];
if ($cra == "") {echo "Para consultar digite un código de carrera vigente"; exit;}

$sql_d = "SELECT DISTINCT(doc_nro_iden),
				 (LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, 
				 cra_cod,cra_nombre,tvi_cod,tvi_nombre
			 FROM acdocente, acasperi, acdoctipvin, accra, actipvin,accarga,accurso,acasi
			 WHERE cra_cod = :cra
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
				 
				 
	     $rs = ociexebind($oci_cn,$sql_d,902,2,$xnull,$cra);
	  	 if ($rs != -1){
			$row = OCIFetch($rs); 
            if  (!$row) {
			      ?><EM><font color="#3336699">No existen docentes con carga acad&eacute;mica registrada. Verifique el código de carrera: <? echo $cra?></font></EM><BR> <?
            }else {?>
				<TABLE width="100%"BORDER=0 CELLSPACING=1 CELLPADDING=1>
					<tr><td align="center">Evaluaci&oacute;n Docente</td></tr>						
					<tr><td align="center">Periodo Acad&eacute;mico <? echo $evanio." - ".$evaper ?></td></tr>				
					<tr><td align="center">Informe de Evaluaciones de <? OCIResult($rs,4)?></td></tr>		
				</TABLE><br /><?
				$var = 0;
				require_once("funvalidcalendario.php");
				do { //Almacene en variables de sesion
					 $var = $var + 1;			
					 $tipovin = OCIResult($rs,5);
					 $nomtvi = substr(OCIresult($rs,6),0,40);
					 $cra = OCIResult($rs,3);
					 $cranom = OCIResult($rs,4);
					 $nomdoc = OCIResult($rs,2);
					 $numformato = obtenernumformato($tipovin,"auto");
					 $_SESSION["sec".$var] = "";
					 $_SESSION["cra".$var] = OCIResult($rs,3);
					 $_SESSION["docente".$var] =OCIResult($rs,1);
					 $_SESSION["fmto".$var] = $numformato;
					 $_SESSION["nomtvi".$var] = $nomtvi;
					 $_SESSION["cranom".$var] = $cranom;
					 $_SESSION["nomdoc".$var] = $nomdoc;
					 $_SESSION["tipovin".$var] = $tipovin;
					 
				 }while (OCIFetch($rs));
		 		  ocifreestatement($rs);
				  OCILogOff($oci_cn); 
				
				  $tipovinactual = 0;
				  for($i=1;$i<=$var;$i++){
					 if ($i==1){?>
						<TABLE  width="98%"BORDER=0 CELLSPACING=1 CELLPADDING=1>
							<tr><td align="left" width="20%">Fecha</td>						
								<td align="left" width="80%"><strong><? echo date("Y-n-d") ?></strong></td>
							</tr>				
							<tr><td align="left" width="20%">Proyecto Curricular</td>						
								<td align="left" width="80%"><strong><? echo $_SESSION["cranom".$i] ?></strong></td>
							</tr>				
							<tr><td align="left" width="20%">C&oacute;digo</td>						
								<td align="left" width="80%"><strong><? echo $_SESSION["cra".$i]?></strong></td>
							</tr>
						</TABLE><br />
				    <? }
				  	 if ($tipovinactual != $_SESSION["tipovin".$i]){ 
					 	$tipovinactual = $_SESSION["tipovin".$i];?>
						<TABLE  width="98%"BORDER=0 CELLSPACING=1 CELLPADDING=1>
							<tr><td align="center" width="100%"><strong>Docentes de <? echo $vinsel2x[$tipovinactual] ?></strong></td>						
							</tr>				
						</TABLE><br /><?
						$ctadocentes = 1;
					 }; ?>
						<TABLE width="98%"BORDER=0 CELLSPACING=1 CELLPADDING=1>
							<tr><td align="left"><strong><? echo $ctadocentes++.".-  ".$_SESSION["nomdoc".$i]." - ID No. ".$_SESSION["docente".$i] ?></strong></td></tr>						
						</TABLE>
						<TABLE width="68%"BORDER=0 CELLSPACING=1 CELLPADDING=1>
							<tr><td align="left" width="60%">Autoevaluación</td>
								<td align="left" width="10%"><? 
								$fecha = cta_autcpcev($_SESSION["docente".$i],$_SESSION["cra".$i],"a");
								if ($fecha == 0) {?> 
								<? echo "No"?><td align="left" width="30%"> </td><?
								}else {echo "S&iacute";?></td>
								<td align="left" width="30%">Fecha: <? echo $fecha?><? }?></td></tr>
							<tr><td align="left" width="60%">Evaluaci&oacute;n de Consejo Curricular</td>
								<td align="left" width="10%"><? 
								$fecha = cta_autcpcev($_SESSION["docente".$i],$_SESSION["cra".$i],"c");
								if ($fecha == 0) {?> 
								<? echo "No";
								?><td align="left" width="30%"> </td><?
								}else {echo "S&iacute";?></td>
								<td align="left" width="30%">Fecha: <? echo $fecha?><? }?></td></tr>
						</TABLE>
						<TABLE width="98%"BORDER=0 CELLSPACING=1 CELLPADDING=1>
							<tr><td align="left">Evaluaci&oacute;n de estudiantes:</td></tr>						
						</TABLE>
						<?
						blq ($_SESSION["docente".$i],$_SESSION["cra".$i],$vin,$i,0,$oci_cn);
					}
						
          } 						//------------- finaliza  $row
	} 							//------------- finaliza  $rs
				 
				 
// ?????			echo "Total inscritos: ".$acumins." Acumevals: ".$acumevals." Porcentaje general: ".($acumevals*100/$acumgrupos)." Total grupos: ".$acumgrupos; 
				 
			break;
		case 8:
/*			$sq6 = concat_sql("epc_cra_cod","ACEVAPROCPC_08","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,337,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,1,3,$numpre,0,0,0,0,0,0,0,0); 		break;
			}else $fmtonum = 0;
			break;
		case 9:
			$sq6 = concat_sql("epc_cra_cod","ACEVAPROCPC_09","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,338,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,1,6,$numpre,0,0,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			break; 
		case 10:
			$sq6 = concat_sql("epa_cra_cod","ACEVAPROAUT_10","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,339,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,2,1,2,2,$numpre,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			break; 
		case 11:
			$sq6 = concat_sql("epc_cra_cod","ACEVAPROCPC_11","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,340,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,1,6,$numpre,0,0,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			break; 
		case 12:
			$sq6 = concat_sql("epa_cra_cod","ACEVAPROAUT_12","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,341,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,2,1,2,2,$numpre,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			break; 
		case 13:
			$sq6 = concat_sql("epa_cra_cod","ACEVAPROAUT_13","epa_doc_nro_iden",$docente,$cra,"epa_ape_per","epa_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,342,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"auto");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,2,1,2,2,$numpre,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
			break; 
		case 14:
			$sq6 = concat_sql("epc_cra_cod","ACEVAPROCPC_14","epc_doc_nro_iden",$docente,$cra,"epc_ape_per","epc_ape_ano");
			$rsrep = ociexe($oci_cn,$sq6,343,1);
			$verif_repite = verif_repite_ev($rsrep,$oci_cn,$docente,$nomdoc,$nomtvi,$cranom,"cpc");
			if ($verif_repite == 0){
				blq ($docente,$cra,$vin,$sec,1,6,$numpre,0,0,0,0,0,0,0,0);
			}else $fmtonum = 0; // ???
*/			break; 
		} 
				//blq1(Id del docente; cra_cod; estudiante o docente o coordinador
				//sec para extraer Encabezado, No. bloques, hasta la pregunta x1 del 1er bloque, 
				//hasta la pregunta x2 del 2do bloque,... )
				//Máximo 5 bloques	?>
	<INPUT type="hidden" id=text_sec name=text_sec size=12 value=<? echo $sec?>>
	<INPUT type="hidden" id=text_fmto name=text_fmto size=12 value=<? echo $fmtonum?>>
	<INPUT type="hidden" id=text_doc name=text_doc size=15 value=<? echo $docente?>>

<P>&nbsp;</P>
</form>
	<INPUT type="hidden" value =<? echo $fmtonum?> name="boton_grabar" id="b">
	<? echo  $evcreds;?>
</BODY>
</HTML>