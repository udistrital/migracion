<? //Validar vigencia en calendario
// -----------------------------------------------------------------------\\
/*
1	M�dulo:			Evaluaci�n Docente periodo 2007-3
2	Nombre:			funvalidcalendario.php
3	Descripci�n:	Funciones PHP que validan el ingreso de los usuarios al proceso de evaluaci�n docente
					y asignan el tipo de formato a presentar.
4	Tipo:			Script funci�nes PHP
5	Acceso a objetos: Tabla de la instancia SUDD en modo consulta:accaleventos
6	Aplicaci�n:		Sistema CONDOR
7	Ruta:			../evo73
8	Ambiente:		Producci�n AIX 5.3 / OAS 10g R. 10.1.2
9	Fecha en producci�n:	5 de octubre de 2007
10	Elaborado por:			Carlos E. Rodr�guez J.
11	Revisi�n No.:	01
12	Revisi�n No.:	02	12 de marzo de 2008
*/
// -----------------------------------------------------------------------\\

//Funci�n vercalendario() con dos par�metros de entrada retorna booleano
//						True:	Evento activo para le carrera consultada
//						False:	Evento no activio para la carrera consultada

function vercalendario($codevento,$cracod){
	global $oci_cn;
	require_once('dir_relativo.cfg');
	require_once(dir_conect.'valida_pag.php');
	include_once("../clase/multiConexion.class.php");
	
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../");
	
	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion('evaldocente');
	$sql_cal="SELECT COUNT(ace_cod_evento) "; // --Si calendario vigente..
	$sql_cal.="FROM accaleventos ";
	$sql_cal.="WHERE ace_cra_cod=$cracod ";
	$sql_cal.="AND ace_cod_evento=$codevento "; //-- 18 -- V�lida en cualquier periodo acad�mico..==> NO requiere verfificar acasperi aqu�.
	$sql_cal.="AND TO_NUMBER(TO_CHAR(SYSDATE,'yyyymmdd')) >= TO_NUMBER(TO_CHAR(ace_fec_ini,'yyyymmdd')) "; 
	$sql_cal.="AND TO_NUMBER(TO_CHAR(SYSDATE,'yyyymmdd')) <= TO_NUMBER(TO_CHAR(ace_fec_fin,'yyyymmdd')) ";	
	$sql_cal.="AND ace_estado = 'A'";
	
	$rs_cale =$conexion->ejecutarSQL($configuracion,$accesoOracle,$sql_cal,"busqueda");
	$cal = false;
//---------------------------------------
	$_SESSION["pr2"]=0; //Habilitado acceso por 'pr2'// Mantener deshabilitado en producci�n con valor diferente a 0
//-----	***	No tener en cuenta fechas de calendario *** ------\\
	if (is_array($rs_cale))
	{
		$cta = $rs_cale[0][0];
		if ($cta == 1 || ($cta != 0 && $cta !=1))
		{
			$cal = true;
		}
	}
//---------------------------------------	
	
//-----------Modo prueba---------
		//$cal = true;
		//return $cal;
//------------------------------
	
//-----------------------
return $cal;
//-------------------------	
}

	//--- Devolver n�mero de formato segun vinculaci�n y tipo de evaluaci�n ---\\
function obtenernumformato($tipovin,$tipoev){	
	$numformato = 0;
	if ($tipoev == "auto")
	{
		if ($tipovin == 1 || $tipovin == "8")
		{ 							//DOCENTE PLANTA TIEMPO COMPLETO;} //79508767
			$numformato = "10";
		}
		elseif ($tipovin == "2")
		{							// DOCENTE TIEMPO COMPLETO OCASIONAL (CATEDRA) 80420923, 80271174;
			$numformato = "12";
		}
		elseif ($tipovin == "3" || $tipovin == "6")
		{							// DOCENTE MEDIO TIEMPO OCASIONAL (CATEDRA); //MT y MTO 11252984 6-->4058613
			$numformato = "13";
		}
		elseif ($tipovin == "4" || $tipovin == "5")
		{ 							//DOCENTE CATEDRA (CONTRATO) 19226603;
			$numformato = "6";
		}
	}
	elseif ($tipoev == "cpc")
	{
		if ($tipovin == "1" || $tipovin == "8")
		{  // DOCENTE PLANTA TIEMPO COMPLETO 
			$numf= "11";
		}
		elseif ($tipovin == "2")
		{							// DOCENTE TIEMPO COMPLETO OCASIONAL (CATEDRA); 
			$numf = "9";
		} 
		elseif ($tipovin == "3" || $tipovin == "6")
		{ 							// DOCENTE MEDIO TIEMPO OCASIONAL (CATEDRA); //MT y MTO 
			$numf = "14";
		}
		 elseif ($tipovin == "4" || $tipovin == "5")
		{							 // DOCENTE CATEDRA (CONTRATO y HONORARIOS) HCC y HCH; 
			$numf = "8";
		}
		if ($numf < 10)
		{
			$numformato  = "0".$numf;
		}
		else
		{
			$numformato  = $numf;
		}
	}
	return $numformato;
}
?>
