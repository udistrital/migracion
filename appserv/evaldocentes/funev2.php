<?php
function ociexe($oci_cn,$sql_x,$clave,$s){		//Ejecutar funciones OCI y manejar errores;
	 //echo $sql_x;
			$rs = OCIParse($oci_cn,$sql_x);      
			$rs2exe = OCIExecute($rs, OCI_DEFAULT);
			if (!$rs2exe) {
				$e = OCIError($rs);
				//echo $sql_x.' Error '.$pag.'  coe'.$e['code'].' mensaje '.$e['message']. 'formato '.$fmto.' clave '.$clave;
				append_logev($pag,$e['code'],$e['message'],$fmto,$clave);
				return -1;
			}
			else {
			return $rs;	}
}
function ociexebind($oci_cn,$sql_x,$clave,$s,$usev,$cracod){
//echo $oci_cn."  ".$sql_x;//."  usev= ".$usev." cra= ".$cracod;
			$rs = OCIParse($oci_cn,$sql_x);   
			if ($clave == 311 || $clave == 312 ||$clave == 313 || 
				$clave == 315 || $clave == 322 || $clave == 901){   
				
				OCIBindByName($rs, ':usevaluador', $usev);
				
			}elseif($clave == 902){
				OCIBindByName($rs, ':cra', $cracod);
			}elseif($clave == 321 || $clave == 319 || $clave == 3111){
				//
			}elseif($clave == 317 || $clave == 320 || $clave == 323 || $clave == 324 || 
					$clave == 990 || $clave == 444){
				OCIBindByName($rs, ':usevaluador', $usev);
				OCIBindByName($rs, ':cra', $cracod);
			}
			$rs2exe = OCIExecute($rs, OCI_DEFAULT);
			if (!$rs2exe) {
				$e = OCIError($rs);
				append_logev($pag,$e['code'],$e['message'],$fmto,$clave);
				return -1;
			}else {
				return $rs;	
			}
}
?>
