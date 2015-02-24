<?PHP
	include_once("../clase/multiConexion.class.php");

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


	$QryFecHoy = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual";
	
	$registroFecha=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFecHoy,"busqueda");
		

	$fechahoy = $registroFecha[0][0];

	$consulta = "SELECT NVL(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'),
		  NVL(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0'),
		  TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
		  TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
		  FROM accaleventos,acasperi
		 WHERE APE_ANO = ACE_ANIO
		   AND APE_PER = ACE_PERIODO
		   AND APE_ESTADO = 'A'
		   AND ACE_CRA_COD = (SELECT est_cra_cod FROM acest WHERE est_cod=".$_SESSION['usuario_login'].") 
		   AND ACE_COD_EVENTO = 16";
		   
	$registroCalendario=$conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
	
	//echo $consulta;
	
	$FormFecIni = $registroCalendario[0][2];
	$FormFecFin = $registroCalendario[0][3];

	if($registroCalendario[0][0] == "" || $registroCalendario[0][1] == ""){
	   die('<br><br><p align="center"><b><font color="#0000FF" size="3">No se han programado fechas para cancelar asignaturas.</font></p>');
	   exit;
	}
	if($fechahoy < $registroCalendario[0][0] && $registroCalendario[0][1] > '0')
	   header("Location: ../err/err_can_ini.php?fecI=$FormFecIni&fecF=$FormFecFin");

	elseif($fechahoy > $registroCalendario[0][1] && $registroCalendario[0][1] > '0')
			header("Location: ../err/err_can_fin.php?fec=$FormFecFin");

	elseif($registroCalendario[0][0] == '0' || $registroCalendario[0][1] == '0')
		   header("Location: ../err/err_add_sinfec");
		   	   

?>
