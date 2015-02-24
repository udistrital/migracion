<? //Validar vigencia en calendario
function vercalendario($codevento,$cracod){
	global $oci_cn;
	$sql_cal_1 = "SELECT COUNT(ace_cod_evento)"; // --Si calendario vigente..
	$sql_cal_1 = $sql_cal_1."  FROM accaleventos";
	$sql_cal_1 = $sql_cal_1."   WHERE ace_cra_cod = ";
	
	$sql_cal_2 = " AND ace_cod_evento = "; //-- 18 -- Válida en cualquier periodo académico..==> NO requiere verfificar acasperi aquí.
	
	//$sql_cal_3 = " AND 20071016 >= TO_NUMBER(TO_CHAR(ace_fec_ini,'yyyymmdd'))"; 
	$sql_cal_3 = " AND TO_NUMBER(TO_CHAR(SYSDATE,'yyyymmdd')) >= TO_NUMBER(TO_CHAR(ace_fec_ini,'yyyymmdd'))"; 
	//$sql_cal_3 = $sql_cal_3." AND 20071109 <= TO_NUMBER(TO_CHAR(ace_fec_fin,'yyyymmdd'))"; 
	$sql_cal_3 = $sql_cal_3." AND TO_NUMBER(TO_CHAR(SYSDATE,'yyyymmdd')) <= TO_NUMBER(TO_CHAR(ace_fec_fin,'yyyymmdd'))";	
	$sql_cal_3 = $sql_cal_3." AND ace_estado = 'A'";
	
	$sql_cal =$sql_cal_1.$cracod.$sql_cal_2.$codevento.$sql_cal_3;
	$rs_cale = ociexebind($oci_cn,$sql_cal,3111,4,$xnull,$xnull);
	$cal = false;
$_SESSION["pr2"]=1; //Deshabilitado acceso por 'pr2'//
//---------------------------------------
//-----	***	No tener en cuenta fechas de calendario *** ------\\
			//echo "Calendario = ".$cta." sessio pr2 = ".$_SESSION["pr2"];
	if ($rs_cale != -1){
		$rowcal = OCIFetch($rs_cale);
		$cta = OCIResult($rs_cale,1);
		if ($cta == 1 || ($_SESSION["pr2"] == 0 && $cta !=1)) {
			$cal = true;
		}
	}
///---------------------------------------	
	ocifreestatement($rs_cale); 
	OCILogOff ($oci_cn);
	return $cal;
}

	//--- Devolver número de formato segun vinculación y tipo de evaluación ---\\
function obtenernumformato($tipovin,$tipoev){	
	$numformato = 0;
	if ($tipoev == "auto") {
		if ($tipovin == 1) { 						//DOCENTE PLANTA TIEMPO COMPLETO;} //79508767
			$numformato = "10";
		} elseif ($tipovin == "2") {					// DOCENTE TIEMPO COMPLETO OCASIONAL (CATEDRA) 80420923, 80271174;
			$numformato = "12";
		} elseif ($tipovin == "3" || $tipovin == "6") {// DOCENTE MEDIO TIEMPO OCASIONAL (CATEDRA); //MT y MTO 11252984 6-->4058613
			$numformato = "13";
		} elseif ($tipovin == "4" || $tipovin == "5") { 				//DOCENTE CATEDRA (CONTRATO) 19226603;
			$numformato = "6";
		}
	}elseif ($tipoev == "cpc"){
		if ($tipovin == "1") {  // DOCENTE PLANTA TIEMPO COMPLETO 
			$numf= "11";
		} elseif ($tipovin == "2") { 					// DOCENTE TIEMPO COMPLETO OCASIONAL (CATEDRA); 
			$numf = "9";
		} elseif ($tipovin == "3" || $tipovin == "6") {  // DOCENTE MEDIO TIEMPO OCASIONAL (CATEDRA); //MT y MTO 
			$numf = "14";
		} elseif ($tipovin == "4" || $tipovin == "5") { // DOCENTE CATEDRA (CONTRATO y HONORARIOS) HCC y HCH; 
			$numf = "8";
		}
		if ($numf < 10) {
			$numformato  = "0".$numf;
		} else {
			$numformato  = $numf;
		}
	}
	return $numformato;
}
?>