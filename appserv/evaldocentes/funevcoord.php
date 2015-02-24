<?php
 include "funaccoord.php"; 
 include ("vartextosfijos.php");
	$tab[1] = "<TABLE WIDTH=&quot;100%&quot; BORDER=1 CELLSPACING=1 CELLPADDING=1>";
	$tab[2] = "</TABLE><br>";
	$tab[3] = "</TABLE>";
//--------------------------------- Docentes  --------------------------------------------------------
function blq($docente,$cra,$vin,$sec,$b) {
					//($Id $del $docente; $Cra_cod; $vinculo: 1 = $Estudiante, 2= $Docente, 3= $Coordinador { 
					//Sec para extraer de session el Encabezado; b= No. bloques; )
	$encab =$_SESSION["sec".$sec];
	for ($k=0; $k<=$b; $k++) { 			//Encabezado más No. bloques;
		//if b=4 and k=3 then	'Tercer bloque con pregunta nula
		//else
		global $tab;
			echo $tab[1];
				if ($k == 0) { 
					echo $tab[3];
					if ($encab != ""){
						echo $tab[1];
						?><center><TR><TD colspan=4 style="COLOR: red" bgcolor=gold width="930" align="center"> 
							<EM><? echo $encab?></EM></TD></TR></center><?
						echo $tab[3];
					}
					echo $tab[1];
					if ($vin == 30 || $vin == 4 || $vin == 16) {
						cargadoc($docente, $cra);	//Incluir carga del docente;
						echo $tab[2];
					}
				}
	}
}
function concat_sql2($docente,$cra){
	global $evanio, $evaper;
	$sqsel = "SELECT EPA_CRA_COD,EPA_FECHA_REG";
	$sqfrom = " FROM desarrollador1.ACEVAPROAUT_"; //06 10 12 13
    $sqcond = $sq_cond." WHERE epa_ape_ano = ".$evanio." AND epa_ape_per = ".$evaper;
	$sqcond = $sqcond." AND epa_doc_nro_iden = ".$docente." AND EPA_CRA_COD = ".$cra;	
	$squ = " UNION ";
	
	$sql = $sqsel.$sqfrom."06".$sqcond.$squ.$sqsel.$sqfrom."10".$sqcond;
	$sql = $sql.$squ.$sqsel.$sqfrom."12".$sqcond.$squ.$sqsel.$sqfrom."13".$sqcond;
	return $sql;
}
function concat_sql3($docente,$cra){
	global $evanio, $evaper;
	$sqsel = "SELECT EPC_CRA_COD,EPC_FECHA_REG";
	$sqfrom = " FROM desarrollador1.ACEVAPROCPC_"; //08 09 11 14
    $sqcond = $sq_cond." WHERE epc_ape_ano = ".$evanio." AND epc_ape_per = ".$evaper;
	$sqcond = $sqcond." AND epc_doc_nro_iden = ".$docente." AND EPC_CRA_COD = ".$cra;	
	$squ = " UNION ";
	
	$sql = $sqsel.$sqfrom."08".$sqcond.$squ.$sqsel.$sqfrom."09".$sqcond;
	$sql = $sql.$squ.$sqsel.$sqfrom."11".$sqcond.$squ.$sqsel.$sqfrom."14".$sqcond;
	return $sql;
}
function cta_autcpcev($docente,$cra,$tipo){
	global $oci_cn;
	if ($tipo=="a"){
		$sql_cta = concat_sql2($docente,$cra);
	}else if ($tipo=="c"){
		$sql_cta = concat_sql3($docente,$cra);
	}
	$rs_evcta = ociexe($oci_cn,$sql_cta,992,4);
//echo $sql_cta;	 
	  if ($rs_evcta != -1){
			$row4 = OCIFetch($rs_evcta);	
			if (!$row4) {
				$fecha = 0;
			} else {
				$fecha = OCIResult($rs_evcta,2);
			}
	  }
	  ocifreestatement($rs_evcta); 
	  //oci_close($rs_evcta);
	  OCILogOff($oci_cn);
	  return $fecha;
}
//---------------------------------  Carreras  --------------------------------------------------
function concat_sql4($cra){
	global $evanio, $evaper;
	$sqsel = "SELECT count(EPA_CRA_COD)";
	$sqfrom = " FROM desarrollador1.ACEVAPROAUT_"; //06 10 12 13
    $sqcond = $sq_cond." WHERE epa_ape_ano = ".$evanio." AND epa_ape_per = ".$evaper;
	$sqcond = $sqcond." AND EPA_CRA_COD = ".$cra;	
	$squ = " UNION ";
	
	$sql = $sqsel.$sqfrom."06".$sqcond.$squ.$sqsel.$sqfrom."10".$sqcond;
	$sql = $sql.$squ.$sqsel.$sqfrom."12".$sqcond.$squ.$sqsel.$sqfrom."13".$sqcond;
	return $sql;
}
function concat_sql5($cra){
	global $evanio, $evaper;
	$sqsel = "SELECT count(EPC_CRA_COD)";
	$sqfrom = " FROM desarrollador1.ACEVAPROCPC_"; //08 09 11 14
    $sqcond = $sq_cond." WHERE epc_ape_ano = ".$evanio." AND epc_ape_per = ".$evaper;
	$sqcond = $sqcond." AND EPC_CRA_COD = ".$cra;	
	$squ = " UNION ";
	
	$sql = $sqsel.$sqfrom."08".$sqcond.$squ.$sqsel.$sqfrom."09".$sqcond;
	$sql = $sql.$squ.$sqsel.$sqfrom."11".$sqcond.$squ.$sqsel.$sqfrom."14".$sqcond;
	return $sql;
}
function cta_xcraev($cra,$tipo){
	global $oci_cn;
	if ($tipo=="a"){
		$sql_ctac = concat_sql4($cra);
	}else if ($tipo=="c"){
		$sql_ctac = concat_sql5($cra);
	}
	$rs_ctac = ociexe($oci_cn,$sql_ctac,992,4);
//echo $sql_ctac;	 
	  if ($rs_cta != -1){
			$row4 = OCIFetch($rs_ctac);	
			if (!$row4) {
				$fecha = 0;
			} else {
				$fecha = OCIResult($rs_ctac,1);
			}
	  }
	  ocifreestatement($rs_ctac); 
	  OCILogOff($oci_cn);
	  return $fecha;
}
?>