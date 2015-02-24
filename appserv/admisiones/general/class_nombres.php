<?PHP
class Nombres{
// 1 NOMBRE DEL DOCENTE
	function NombreDocente($cedula){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryDoc = OCIParse($this->oci_conecta, "SELECT LTRIM(doc_nombre||'  '||doc_apellido) FROM acdocente WHERE doc_nro_iden = $cedula AND doc_estado = 'A'");
		OCIExecute($this->QryDoc, OCI_DEFAULT);
		$this->RowDoc = OCIFetch($this->QryDoc);

		if($this->RowDoc != 1) $DocNom = "Sin nombre";
		else $DocNom = OCIResult($this->QryDoc, 1);

		return $DocNom;
		OCIFreeCursor($this->QryDoc); OCILogOff($this->oci_conecta);
	}
// 2 NOMBVRE DEL FUNCIONARIO
	function NombreFuncionario($cedula){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryFun = OCIParse($this->oci_conecta, "SELECT emp_nombre FROM mntpe.peemp WHERE emp_nro_iden = $cedula AND emp_estado_e = 'A'");
		OCIExecute($this->QryFun, OCI_DEFAULT);
		$this->RowFun = OCIFetch($this->QryFun);

		if($this->RowFun != 1) $FunNom = "Sin nombre";
		else $FunNom = OCIResult($this->QryFun, 1);

		return $FunNom;
		OCIFreeCursor($this->QryFun); OCILogOff($this->oci_conecta);
	}
// 3 NOMBRE DEL ESTUDIANTE
	function NombreEstudiante($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryEst = OCIParse($this->oci_conecta, "SELECT est_nombre FROM acest WHERE est_cod = $codigo AND est_estado_est IN('A','B','H','J','L','V')");
		OCIExecute($this->QryEst);
		$this->RowEst = OCIFetch($this->QryEst);

		if($this->RowEst != 1) $EstNom = "Sin nombre";
		else $EstNom = OCIResult($this->QryEst, 1);

		return $EstNom;
		OCIFreeCursor($this->QryEst); OCILogOff($this->oci_conecta);
	}
// 4 NOMBRE DE LA ASIGNATURA
	function NombreAsignatura($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryAsi = OCIParse($this->oci_conecta,"SELECT asi_nombre FROM acasi WHERE asi_cod = $codigo AND asi_estado = 'A'");
		OCIExecute($this->QryAsi, OCI_DEFAULT);
		$this->RowAsi = OCIFetch($this->QryAsi);
	
		if($this->RowAsi != 1) $Asinom = "Sin nombre";
		else $Asinom = OCIResult($this->QryAsi, 1);
	
		return $Asinom;
		OCIFreeCursor($this->QryAsi); OCILogOff($this->oci_conecta);
	}
// 5 NOMBRE DE LA CARRERA
	function NombreCarrera($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryCra = OCIParse($this->oci_conecta,"SELECT cra_nombre FROM accra WHERE cra_cod = $codigo AND cra_estado = 'A'");
		OCIExecute($this->QryCra, OCI_DEFAULT);
		$this->RowCra = OCIFetch($this->QryCra);
	
		if($this->RowCra != 1) $CraNom = "Sin nombre";
		else $CraNom = OCIResult($this->QryCra, 1);
	
		return $CraNom;
		OCIFreeCursor($this->QryCra); OCILogOff($this->oci_conecta);
	}
// 6 NOMBRE DE LA SEDE
	function NombreSede($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QrySed = OCIParse($this->oci_conecta,"SELECT sed_nombre FROM gesede WHERE sed_cod = $codigo AND sed_estado = 'A'");
		OCIExecute($this->QrySed, OCI_DEFAULT);
		$this->RowSed = OCIFetch($this->QrySed);
	
		if($this->RowSed != 1) $SedNom = "Sin nombre";
		else $SedNom = OCIResult($this->QrySed, 1);
	
		return $SedNom;
		OCIFreeCursor($this->QrySed); OCILogOff($this->oci_conecta);
	}
// 7  NOMBRE DE LA FACULTAD
	function NombreFacultad($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryFac = OCIParse($this->oci_conecta,"SELECT DEP_NOMBRE FROM GEDEP WHERE DEP_COD = $codigo AND DEP_ESTADO = 'A'");
		OCIExecute($this->QryFac, OCI_DEFAULT);
		$this->RowFac = OCIFetch($this->QryFac);
	
		if($this->RowFac != 1) $FacNom = "Sin nombre";
		else $FacNom = OCIResult($this->QryFac, 1);
	
		return $FacNom;
		OCIFreeCursor($this->QryFac); OCILogOff($this->oci_conecta);
	}
// 8 NOMBRE DEL ESTADO
	function NombreEstado($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryEstado = OCIParse($this->oci_conecta,"SELECT ESTADO_NOMBRE FROM ACESTADO WHERE ESTADO_COD = '$codigo'");
		OCIExecute($this->QryEstado, OCI_DEFAULT);
		$this->RowEstado = OCIFetch($this->QryEstado);
	
		if($this->RowEstado != 1) $EstadoNom = "Sin nombre";
		else $EstadoNom = OCIResult($this->QryEstado, 1);
	
		return $EstadoNom;
		OCIFreeCursor($this->QryEstado); OCILogOff($this->oci_conecta);
	}
// 9 NOMBRE DE LA FACULTAD A QUE PERTENECE LA CARRERA
	function NombreFacultadCraCod($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryFacCraCod = OCIParse($this->oci_conecta,"SELECT DEP_NOMBRE FROM GEDEP,ACCRA WHERE DEP_COD = CRA_DEP_COD
																				 AND DEP_ESTADO = 'A'
																			   	 AND CRA_ESTADO = 'A'
																			   	 AND CRA_COD = '$codigo'");
		OCIExecute($this->QryFacCraCod, OCI_DEFAULT);
		$this->RowFacCraCod = OCIFetch($this->QryFacCraCod);
	
		if($this->RowFacCraCod != 1) $NomFacCraCod = "Sin nombre";
		else $NomFacCraCod = OCIResult($this->QryFacCraCod, 1);
	
		return $NomFacCraCod;
		OCIFreeCursor($this->QryFacCraCod); OCILogOff($this->oci_conecta);
	}
// 10 NOMBRE DEL LUGAR
	function NombreCiudad($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryCiudad = OCIParse($this->oci_conecta,"SELECT LUG_NOMBRE FROM GELUGAR WHERE LUG_COD = '$codigo' AND LUG_ESTADO = 'A'");
		OCIExecute($this->QryCiudad, OCI_DEFAULT);
		$this->RowCiudad = OCIFetch($this->QryCiudad);
	
		if($this->RowCiudad != 1) $NomCiudad = "Sin nombre";
		else $NomCiudad = OCIResult($this->QryCiudad, 1);
	
		return $NomCiudad;
		OCIFreeCursor($this->QryCiudad); OCILogOff($this->oci_conecta);
	}
// 11 MEDIOS DE PUBLICIDAD
	function MediPublicidad($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryMedPub = OCIParse($this->oci_conecta,"SELECT med_nombre FROM acmedio WHERE med_cod = '$codigo' AND med_estado = 'A'");
		OCIExecute($this->QryMedPub, OCI_DEFAULT);
		$this->RowMedPub = OCIFetch($this->QryMedPub);
	
		if($this->RowMedPub != 1) $NomMedPub = "Sin nombre";
		else $NomMedPub = OCIResult($this->QryMedPub, 1);
	
		return $NomMedPub;
		OCIFreeCursor($this->QryMedPub); OCILogOff($this->oci_conecta);
	}
// 12 PUNTAJE MINIMO DE INSCRIPCION POR CARRERA
	function PuntosMin($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryPtosMin = OCIParse($this->oci_conecta,"SELECT ATM_PTOS 
											   FROM ACASPTOSMIN,ACASPERIADM
											  WHERE APE_ANO = ATM_APE_ANO
											    AND APE_PER = ATM_APE_PER
											    AND ATM_CRA_COD = $codigo
											    AND APE_ESTADO = 'A'
											    AND ATM_TIP_ICFES = 'N'
											    AND ATM_ESTADO = 'A'");
		OCIExecute($this->QryPtosMin, OCI_DEFAULT);
		$this->RowPtosMin = OCIFetch($this->QryPtosMin);

		if($this->RowPtosMin != 1) $PtosMin = 0;
		else $PtosMin = OCIResult($this->QryPtosMin, 1);

		return $PtosMin;
		OCIFreeCursor($this->QryPtosMin); OCILogOff($this->oci_conecta);
	}
// 13 NOMBRE DEL DEPARTAMENTO
	function NombreDepartamento($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryDpto = OCIParse($this->oci_conecta,"SELECT dep_nombre FROM mntge.gedepartamento WHERE dep_cod = $codigo AND dep_estado = 'A'");
		OCIExecute($this->QryDpto, OCI_DEFAULT);
		$this->RowDpto = OCIFetch($this->QryDpto);
	
		if($this->RowDpto != 1) $NomDpto = "Sin nombre";
		else $NomDpto = OCIResult($this->QryDpto, 1);
	
		return $NomDpto;
		OCIFreeCursor($this->QryDpto); OCILogOff($this->oci_conecta);
	}
// 14 NOMBRE DEL MUNICIPIO
	function NombreMunicipio($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryMun = OCIParse($this->oci_conecta,"SELECT mun_nombre FROM mntge.gemunicipio WHERE mun_cod = $codigo AND mun_estado = 'A'");
		OCIExecute($this->QryMun, OCI_DEFAULT);
		$this->RowMun = OCIFetch($this->QryMun);
	
		if($this->RowMun != 1) $NomMun = "Sin nombre";
		else $NomMun = OCIResult($this->QryMun, 1);
	
		return $NomMun;
		OCIFreeCursor($this->QryMun); OCILogOff($this->oci_conecta);
	}
// 15 NOMBRE DEL LOCALIDAD
	function NombreLocalidad($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryLocal = OCIParse($this->oci_conecta,"SELECT loc_nombre
											FROM aclocalidad,acasperiadm
											WHERE ape_ano = loc_ape_ano
											  AND ape_per = loc_ape_per
											  AND ape_estado = 'X'
											  AND loc_estado = 'A'
											  AND loc_nro = $codigo");
		OCIExecute($this->QryLocal, OCI_DEFAULT);
		$this->RowLocal = OCIFetch($this->QryLocal);
	
		if($this->RowLocal != 1) $NomLocal = "Sin nombre";
		else $NomLocal = OCIResult($this->QryLocal, 1);
	
		return $NomLocal;
		OCIFreeCursor($this->QryLocal); OCILogOff($this->oci_conecta);
	}
// 16 NOMBRE DEL ESTRATO
	function NombreEstrato($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryEstrato = OCIParse($this->oci_conecta,"SELECT str_nombre
															FROM acestrato,acasperiadm
															WHERE ape_ano = str_ape_ano
															  AND ape_per = str_ape_per
															  AND ape_estado = 'X'
															  AND str_estado = 'A'
															  AND str_nro = $codigo");
		OCIExecute($this->QryEstrato, OCI_DEFAULT);
		$this->RowEstrato = OCIFetch($this->QryEstrato);
	
		if($this->RowEstrato != 1) $NomEstrato = "Sin nombre";
		else $NomEstrato = OCIResult($this->QryEstrato, 1);
	
		return $NomEstrato;
		OCIFreeCursor($this->QryEstrato); OCILogOff($this->oci_conecta);
	}
// 17 TIPO DE INSCRIPCIÓN
	function NombreTipoIns($codigo){
		$this->oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);
		$this->QryTipIns = OCIParse($this->oci_conecta,"SELECT ti_nombre FROM actipins WHERE ti_cod = $codigo AND ti_estado = 'A' ORDER BY ti_nombre");
		OCIExecute($this->QryTipIns, OCI_DEFAULT);
		$this->RowTipIns = OCIFetch($this->QryTipIns);
	
		if($this->RowTipIns != 1) $NomTipIns = "Sin nombre";
		else $NomTipIns = OCIResult($this->QryTipIns, 1);
	
		return $NomTipIns;
		OCIFreeCursor($this->QryTipIns); OCILogOff($this->oci_conecta);
	}
}
/*
CONSTRUCTOR DE LA CLASE
include_once('class_nombres.php');
$nom = new Nombres;
$nombre = $nom->NombreEstudiante($codigo);
*/
?>