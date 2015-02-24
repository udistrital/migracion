<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$confechoy = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'YYYYMMDD')) FROM dual";
$rowfechoy=$conexion->ejecutarSQL($configuracion,$accesoOracle,$confechoy,"busqueda");
$fechahoy = $rowfechoy[0][0];

$confechas ="SELECT TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD')),
		TO_NUMBER(TO_CHAR(ACE_FEC_FIN,'YYYYMMDD')),
		TO_CHAR(ACE_FEC_FIN,'dd-Mon-yy')
		FROM accaleventos
		WHERE ace_cra_cod =".$_SESSION["C"]."
		AND ace_anio = $ano
		AND ace_periodo = $per
		AND ace_cod_evento = 7";
$rowfechas=$conexion->ejecutarSQL($configuracion,$accesoOracle,$confechas,"busqueda");
$fecini = $rowfechas[0][0];
$fecfin = $rowfechas[0][1];
$fecha = $rowfechas[0][2];

$QryCierreSem = "SELECT 'S'
	FROM accaleventos
	WHERE ACE_ANIO = $ano
	AND ACE_PERIODO = $per
	AND ACE_CRA_COD = ".$_SESSION["C"]."
	AND ACE_COD_EVENTO = 71";
$RowCierreSem = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCierreSem,"busqueda");
$Cierre = $RowCierreSem[0][0];

if(($fecini == "") || ($fecfin == "")){
   $MsjCierreProceso = '<h3>No se han programado fechas para digitaci&oacute;n de notas parciales.</h3>';
   $btn_grabar = '<input type=submit name="upd" value="Grabar" disabled>';
   $btn_defini = '<input type="submit" name="notdef" value="Calcular Definitivas" disabled>';
}
elseif(($fechahoy < $fecini) || ($fechahoy > $fecfin)) {
   $MsjCierreProceso = '<h3>El proceso de digitaci&oacute;n de notas, termin&oacute; el '.$fecha.'<br>en este momento, solo podr&aacute; imprimir el reporte de notas.</h3>';
   $btn_grabar = '<input type=submit name="upd" value="Grabar" disabled>';
   $btn_defini = '<input type="submit" name="notdef" value="Calcular Definitivas" disabled>';
}
elseif($Cierre == 'S'){
		$porpar1 = "readonly";
		$notpar1 = "readonly";
		$porpar2 = "readonly";
		$notpar2 = "readonly";
		$porpar3 = "readonly";
		$notpar3 = "readonly";
		$porpar4 = "readonly";
		$notpar4 = "readonly";
		$porpar5 = "readonly";
		$notpar5 = "readonly";
		$porlab = "readonly";
		$notlab = "readonly";
		$porexa = "readonly";
		$notexa = "readonly";
		$porhab = "readonly";
		$nothab = "readonly";
		$tipobs = "readonly";
		$MsjCierreSem='<h3>En este momento no se pueden digitar mas notas, el semestre fue cerrado. <br>P&oacute;ngase en contacto con el Coordinador del Proyecto Curricular.</h3>';
		$btn_grabar = '<input type=submit name="upd" value="Grabar" disabled>';
		$btn_defini = '<input type="submit" name="notdef" value="Calcular Definitivas" disabled>';
}
else{
	 $MsjCierreProceso = "";
	 $btn_grabar = '<input type=submit name="upd" value="Grabar">';
	 $btn_defini = '<input type="submit" name="notdef" value="Calcular Acumulado">';
}

?>