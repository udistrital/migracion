<?PHP
class TotalPorActividad{
	function CuentaActividad($doc, $act,$tipvinculacion){
		require(dir_conect.'conexion.php');
		$this->oci_conecta=$oci_conecta;			

		$this->QryCAct = OCIParse($this->oci_conecta, "SELECT COUNT(DPT_HORA)
											 FROM acdocplantrabajo,acasperi
											WHERE APE_ANO = DPT_APE_ANO
											  AND APE_PER = DPT_APE_PER
											  AND APE_ESTADO = 'A'
											  AND DPT_DOC_NRO_IDEN = $doc
											  AND DPT_DAC_COD = $act
											  AND DPT_ESTADO = 'A'
											  AND DPT_TVI_COD = $tipvinculacion");
	



		OCIExecute($this->QryCAct, OCI_DEFAULT); // or die(Ora_ErrorCode());
		$this->RowCAct = OCIFetch($this->QryCAct);
		
		
		
		if($this->RowCAct!=1) 
		$NroCAct=0;

		else $NroCAct = OCIResult($this->QryCAct,1);
		
		//echo "(NroCAct=".$NroCAct.")";

		//OCIFreeCursor($this->QryCAct); OCILogOff($this->oci_conecta);

		return $NroCAct;
	}
}
/* * CONSTRUCTOR DE LA CLASE
  	 require_once('class_TotalPorActividad.php');
	 $tot = new CuentaActividad;
	 echo $tot->CuentaActividad($doc, $act);*/
?>
