<?php
//echo "NICKTHOR";EXIT;
/* incluimos primeramente el archivo que contiene la clase fpdf */
$basico = $_REQUEST["idBuscado"];
$conexion = "oracle";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
$cadena_sql = $this->sql->cadena_sql("buscarUsuario", $basico);
$cadena_sql = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//$fila = $cadena_sql;
//var_dump($cadena_sql);exit;
$esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque"); //Because esteBloque is an array OK

global $ruta;
$ruta = $this->miConfigurador->getVariableConfiguracion("raizDocumento")."/blocks/".$esteBloque["grupo"]."/".$esteBloque["nombre"];
require($ruta.'/librerias/fpdf/fpdf.php');

global $url;
$url = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."documentos";
global $url2;
$url2 = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."imagen/";
/**************************** Datos de llegada **************************/

global $modificable;
$modificable = $_REQUEST['textoModificable'];
global $ciudad;
$ciudad = utf8_decode($_REQUEST['nameCiudad']);
global $reviso;
$reviso = utf8_decode($_REQUEST['nombreReviso']);
global $cargo;
$cargo = utf8_decode($_REQUEST['cargoReviso']);

// echo "Elaboro";
// var_dump($developer);
// echo "Cargo";
// var_dump($changer);exit;

/**************************** Datos de llegada **************************/

global $funcion;
$funcion = new FunciongenerarReporte();
global $funcion2;
$funcion2 = new FunciongenerarReporte();

/* tenemos que generar una instancia de la clase */
$pdf = new FPDF();
$pdf->AddPage();

class PDF extends FPDF
{
// Cabecera de página
function Header()
	{
		/******************************************************/
		// Salto de línea
		$this->Ln(10);
	    // Logo
	    $url2 = $GLOBALS['url2'];
	    $this->Image($url2.'escudo.png',80,30,-300);
	    // Arial bold 15
	    /******************************************************/
	    // Salto de línea
	    $this->Ln(25);
	    $this->SetFont('Arial','B',15);
	    $this->Cell(50);
	   // $this->Cell(80,60,'EL JEFE DE LA DIVISION DE RECURSOS HUMANOS DE LA',0,0,'C');
	    /******************************************************/
	    // Salto de línea
	    $this->Ln(5);
	    $this->SetFont('Arial','B',15);
	    // Movernos a la derecha
	    //$this->Cell(50);
	    // Título
	    //$this->Cell(80,70,'EL SUSCRITO JEFE DE LA DIVISION DE RECURSOS HUMANOS',0,0,'C');
	    $division = utf8_decode('DIVISIÓN');
	    $jose = utf8_decode('JOSÉ');
	    $this->SetX(60);
	    $this->Cell(80,50,'EL JEFE DE LA '.$division.' DE RECURSOS HUMANOS DE LA',0,0,'C');
	    $this->SetX(60);
	    $this->Cell(80,60,'UNIVERSIDAD DISTRITAL FRANCISCO '.$jose.' DE CALDAS',0,0,'C');
	    $this->SetX(60);
	    $this->Cell(80,70,'CON NIT.899.999.230 - 7 ',0,0,'C');
	     /******************************************************/
	    $this->Ln(5);
	    $this->SetFont('Arial','B',15);
	    $this->Cell(50);
	    $this->Cell(80,80,'CERTIFICA: ',0,0,'C');
	    /******************************************************/
	}
	
	/*****************Genera Tabla en Reporte -- Inicio************************************/
	function TablaBasica($header)
	{
		$nameReviso = $GLOBALS['reviso'];
		$cargoReviso = $GLOBALS['cargo'];
 		//Cabecera
		$this->SetX(30);
		foreach($header as $col)
			$this->Cell(40,5,$col,1,"C");
		$this->Ln();
		$this->SetX(30);
		$reviso = utf8_decode('Revisó y Aprobó');
		$this->Cell(40,5,"$reviso",1);
		$this->SetX(70);
		$this->Cell(40,5,"$nameReviso",1);
		//$this->Cell(40,5,"MNFernandez",1);
		$this->SetX(110);
		$this->Cell(40,5,"$cargoReviso",1);
		//$this->Cell(40,5,"Prof. Universitario",1);
		$this->SetX(150);
		$this->Cell(40,5,"",1);
	}
	/*****************Genera Tabla en Reporte -- Fin************************************/
	/*****************Genera Tabla en Reporte -- Inicio************************************/
	function TablaBasica2($fila)
	{
		//function crearPDF($fila){
		$this->Line(50,200,150,200);//impresion de linea
		$this->SetY(201);
		$this->Cell(180,6,"".$fila['JEFE_RECURSOS_HUMANOS']."",0,0,'C');
		$this->SetXY(10, 205);
		$this->Cell(180,6,"Jefe de Division de Recursos Humanos",0,0,'C');
		//$this->SetY(240);
		//$this->Cell(180,6,"Carrera 7a No 40-53 Piso 6",0,0,'C');
		//$this->SetY(250);
		//$this->Cell(180,6,"PBX.3239300 Ext.2600-2604",0,0,'C');
		//}
	}
	function TablaBasica3($fila)
	{
		//function crearPDF($fila){
		//$this->Line(50,200,150,200);//impresion de linea
		//$this->SetY(201);
		//$this->Cell(180,6,"".$fila['JEFE_RECURSOS_HUMANOS']."",0,0,'C');
		//$this->SetXY(10, 205);
		//$this->Cell(180,6,"Jefe de Division de Recursos Humanos",0,0,'C');
		$this->Line(40,234,180,234);//impresion de linea
		$this->SetY(235);
		$this->Cell(180,6,"Division de Recursos Humanos",0,0,'C');
		$this->SetY(240);
		$this->Cell(180,6,"Carrera 7a No 40-53 Piso 6 Telefono 3239300 Ext.1604",0,0,'C');
		$this->SetY(245);
		$this->Cell(180,6,"Bogota D.C",0,0,'C');
		//}
	}
	/*****************Genera Tabla en Reporte -- Fin************************************/
		function crearPDF($fila){
		$url = $GLOBALS['url'];
		$ruta = $GLOBALS['ruta'];
		$textoModificable = $GLOBALS['modificable'];
		$lugar_expedicion = $GLOBALS['ciudad'];
		
		$this->Header();

// Creación del objeto de la clase heredada
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','',12);
	// Salto de línea
	/***************************Formato de Cedula en Millones --Inicio **************************/
	$cedula = number_format($fila['IDENTIFICACION']);
	/***************************Formato de Cedula en Millones --Fin **************************/
	/***************************Formato de Pesos --Inicio **************************/
	setlocale(LC_MONETARY, 'en_US');
	$salario_promedio = money_format('%(#10n', $fila['SALARIO_PROMEDIO']);
	/***************************Formato de Pesos --Fin **************************/
	$pdf->Ln(30);
	$pdf->Ln(20);
	$pdf->SetX(20);
	/*****************************************************/
	$funcion = $GLOBALS['funcion'];
	$Fecha_Ingreso=$funcion->calcularFecha($fila['FECHA_INGRESO']);
 	$funcion2 = $GLOBALS['funcion2'];
 	$Fecha_Resolucion=$funcion->calcularFecha($fila['FECHA_RESOLUCION']);
	/*****************************************************/
	$mujer = "Señora";
	$hombre= "Señor";
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
 		$variable = "el ".utf8_decode($hombre)."";
 	}
 	else {
 		$variable = "la ".utf8_decode($mujer)."";
 	}  	
	/*****************************************************/
 	/*****************************************************/
 	$tipoGenero = $fila['GENERO'];
 	if ($tipoGenero == "M") {
 		$variable2 = "vinculado";
 	}
 	else {
 		$variable2 = "vinculada ";
 	}
 	/*****************************************************/
 	/*****************************************************/
 	$tipoGenero = $fila['GENERO'];
 	if ($tipoGenero == "M") {
 		$variable3 = "adscrito";
 	}
 	else {
 		$variable3 = "adscrita";
 	}
 	/*****************************************************/
 	/*****************************************************/
 	$tipoGenero = $fila['GENERO'];
 	if ($tipoGenero == "M") {
 		$variable4 = "identificado";
 	}
 	else {
 		$variable4 = "identificada";
 	}
 	/*****************************************************/
//  	$lugar_expedicion = ucwords(strtolower($fila['LUGAR_EXPEDICION'])); 
 	
//  	if($lugar_expedicion=='Bogota'){
//  		$lugar_expedicion=utf8_decode('Bogotá D.C');
 		
//  	}

 	$tipo_identificacion = strtolower($fila['TIPO_IDENTIFICACION']);
 	if($tipo_identificacion=='cedula ciudadania'){
 			
 		$tipo_identificacion = utf8_decode('cédula de ciudadanía');
 	
 	}
 	
 	$institucionalizar = "institución";
 	$instituto = utf8_decode($institucionalizar);
 	
 	$desempenar = "desempeña";
 	$desempeno = utf8_decode($desempenar);
 	
 	$resolution = "resolución";
 	$resolucion = utf8_decode($resolution);
 	
 	/*****************************************************/
 	//$pdf->SetLineWidth(0.01);
	$pdf->Multicell(150,8,"Que ".$variable." ".trim($fila['PRIMER_NOMBRE'])." ".trim($fila['SEGUNDO_NOMBRE'])." ".trim($fila['PRIMER_APELLIDO'])." ".trim($fila['SEGUNDO_APELLIDO'])." ".$variable4." con ".$tipo_identificacion." ".$cedula.
		" de ".$lugar_expedicion.", se encuentra ".$variable2." a la ".$instituto." desde ".$Fecha_Ingreso.". Actualmente ".$desempeno." el cargo de " .$fila['CARGO']." ".$variable3." a la " .$fila['DEPENDENCIA'].".",0,'J');	
	$pdf->Ln(10);
	$pdf->SetX(20);
	$pdf->Multicell(150,8,"SALARIO PROMEDIO MENSUAL...........................................".$salario_promedio."",0,'L');
	$pdf->Ln(2);
	/*****************************************************/
	$year = "año";
	$pdf->SetX(20);
	//var_dump($_REQUEST['textoModificable']);exit;
	$pdf->Multicell(150,8,"".utf8_decode($textoModificable)."",0,'J');
// 	if (empty($_REQUEST['textoModificable'])) {
// 		$pdf->Multicell(150,8,"".utf8_decode('')."",0,'J');
//  	}
// 	if($_REQUEST['textoModificable']==NULL){
// 		$pdf->Multicell(150,8,"".utf8_decode('')."",0,'J');
// 	}
// 	else{
// 		$pdf->Multicell(150,8,"".utf8_decode($_REQUEST['textoModificable'])."",0,'J');		
// 	}
	$pdf->Ln(2);
	$pdf->SetX(20);
	/*****************Traducir los Meses de Ingles a Español -- Inicio************************************/

	$dia=date("l"); // Dia
	$mes=date("F"); // Mes
	$ano=date("Y"); // Año
	$dia2=date("d");
 
	if ($dia=="Monday") $dia="Lunes";
	if ($dia=="Tuesday") $dia="Martes";
	if ($dia=="Wednesday") $dia="Miércoles";
	if ($dia=="Thursday") $dia="Jueves";
	if ($dia=="Friday") $dia="Viernes";
	if ($dia=="Saturday") $dia="Sabado";
	if ($dia=="Sunday") $dia="Domingo";
 
	$mes=date("F");
 
	if ($mes=="January") $mes="Enero";
	if ($mes=="February") $mes="Febrero";
	if ($mes=="March") $mes="Marzo";
	if ($mes=="April") $mes="Abril";
	if ($mes=="May") $mes="Mayo";
	if ($mes=="June") $mes="Junio";
	if ($mes=="July") $mes="Julio";
	if ($mes=="August") $mes="Agosto";
	if ($mes=="September") $mes="Septiembre";
	if ($mes=="October") $mes="Octubre";
	if ($mes=="November") $mes="Noviembre";
	if ($mes=="December") $mes="Diciembre";
 
	$ano=date("Y");
	$dias = utf8_decode('días');
	
	/*****************Traducir los Mese de Ingles a Español -- Fin************************************/	
	$ciudad = utf8_decode('Bogotá');
 	$pdf->Multicell(150,8,"Se expide en ".$ciudad." D.C a los ".$dia2." ".$dias." del mes de ".$mes." del ".utf8_decode($year)." ".$ano.", a solicitud del interesado." ,0,'J');
	$pdf->SetXY(30, 200);
	$pdf->TablaBasica2($fila);
	//Títulos de las columnas
	$pdf->SetXY(40, 220);
	$header=array('','Nombre','Cargo','Firma');
	$pdf->AliasNbPages();
	$pdf->TablaBasica($header);
	$pdf->SetXY(30, 220);
	$pdf->TablaBasica3($fila);
	
	/*****************Genera Tabla en Reporte -- Fin************************************/
	$pdf->AddPage();
	/**
	 * Pagina 2
	 */
	$pdf->SetFont('Times','',12);
	// Salto de línea
	/*****************************************************/
	$cedula = number_format($fila['IDENTIFICACION']);
	/*****************************************************/
	$pdf->Ln(30);
	$pdf->Ln(20);
	$pdf->SetX(20);
	/*****************************************************/
	$funcion = $GLOBALS['funcion'];
	$Fecha_Ingreso=$funcion->calcularFecha($fila['FECHA_INGRESO']);
	$funcion2 = $GLOBALS['funcion2'];
	$Fecha_Resolucion=$funcion2->calcularFecha($fila['FECHA_RESOLUCION']);
	$funcion3 = $GLOBALS['funcion3'];
	$Fecha_Retiroo=$funcion->calcularFecha($fila['FECHA_RETIRO']);
	/*****************************************************/
	$mujer = "Señora";
	$hombre= "Señor";
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
		$variable = "el ".utf8_decode($hombre)."";
	}
	else {
		$variable = "la ".utf8_decode($mujer)."";
	}
	/*****************************************************/
	/*****************************************************/
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
		$variable2 = "vinculado";
	}
	else {
		$variable2 = "vinculada ";
	}
	/*****************************************************/
	/*****************************************************/
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
		$variable3 = "adscrito";
	}
	else {
		$variable3 = "adscrita";
	}
	/*****************************************************/
	/*****************************************************/
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
		$variable4 = "identificado";
	}
	else {
		$variable4 = "identificada";
	}
	/*****************************************************/
	//$lugar_expedicion = ucwords(strtolower($fila['LUGAR_EXPEDICION']));
	$tipo_identificacion = strtolower($fila['TIPO_IDENTIFICACION']);
	if($tipo_identificacion=='cedula ciudadania'){
	
		$tipo_identificacion = utf8_decode('cédula de ciudadanía');
	
	}
	
	//$lugar_expedicion = ucwords(strtolower($fila['LUGAR_EXPEDICION']));
	
// 	if($lugar_expedicion=='Bogota'){
// 		$lugar_expedicion=utf8_decode('Bogotá D.C');
	
// 	}
	
	
	/*****************************************************/
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
		$variable5 = "del interesado";
	}
	else {
		$variable5 = "de la interesada";
	}
	//$Fecha_Retiro = $funcion->calcularFecha3($fila['FECHA_RETIRO']);
	$desempenar = "desempeña";
	$desempeno = utf8_decode($desempenar);
	
	$Fecha_Retiro = $fila['FECHA_RETIRO'];
	$institucionalizar = "institución";
	$instituto = utf8_decode($institucionalizar);
	
	$resolution = "resolución";
	$resolucion = utf8_decode($resolution);
	
	if ($Fecha_Retiro ==NULL){
		$pdf->Multicell(150,8,"Que ".$variable." ".trim($fila['PRIMER_NOMBRE'])." ".trim($fila['SEGUNDO_NOMBRE'])." ".trim($fila['PRIMER_APELLIDO'])." ".trim($fila['SEGUNDO_APELLIDO'])." ".$variable4." con ".$tipo_identificacion." ".$cedula.
				" de ".$lugar_expedicion.", se encuentra ".$variable3." a la ".$instituto." desde ".$Fecha_Ingreso.". Actualmente ".$desempeno." el cargo de " .$fila['CARGO']." , ".$variable2." a la " .$fila['DEPENDENCIA'].".",0,'J');
		//break;
	}if($Fecha_Retiro !==NULL){
		$pdf->Multicell(150,8,"Que ".$variable." ".trim($fila['PRIMER_NOMBRE'])." ".trim($fila['SEGUNDO_NOMBRE'])." ".trim($fila['PRIMER_APELLIDO'])." ".trim($fila['SEGUNDO_APELLIDO'])." ".$variable4." con ".$tipo_identificacion." ".$cedula.
				" de ".$lugar_expedicion.", estuvo vinculado a la ".$instituto." desde ".$Fecha_Ingreso." hasta ".$Fecha_Retiroo." donde se ".$desempeno." en el cargo de " .$fila['CARGO'].".",0,'J');
	}
	$pdf->Ln(10);
	$pdf->SetX(20);
	/*****************************************************/
	$year = "año";
	/*****************************************************/
	$dia=date("l"); // Dia
	$mes=date("F"); // Mes
	$ano=date("Y"); // Año
	$dia2=date("d");
	
	if ($dia=="Monday") $dia="Lunes";
	if ($dia=="Tuesday") $dia="Martes";
	if ($dia=="Wednesday") $dia="Miércoles";
	if ($dia=="Thursday") $dia="Jueves";
	if ($dia=="Friday") $dia="Viernes";
	if ($dia=="Saturday") $dia="Sabado";
	if ($dia=="Sunday") $dia="Domingo";
	
	$mes=date("F");
	
	if ($mes=="January") $mes="Enero";
	if ($mes=="February") $mes="Febrero";
	if ($mes=="March") $mes="Marzo";
	if ($mes=="April") $mes="Abril";
	if ($mes=="May") $mes="Mayo";
	if ($mes=="June") $mes="Junio";
	if ($mes=="July") $mes="Julio";
	if ($mes=="August") $mes="Agosto";
	if ($mes=="September") $mes="Septiembre";
	if ($mes=="October") $mes="Octubre";
	if ($mes=="November") $mes="Noviembre";
	if ($mes=="December") $mes="Diciembre";
	
	$ano=date("Y");
	$dias = utf8_decode('días');
	
	$ciudad = utf8_decode('Bogotá');
	$pdf->Multicell(150,8,"Se expide en ".$ciudad." D.C a los ".$dia2." ".$dias." del mes de ".$mes." del ".utf8_decode($year)." ".$ano.", a solicitud ".$variable5."." ,0,'J');
	$pdf->SetXY(30, 200);
	$pdf->TablaBasica2($fila);
	//Títulos de las columnas
	$pdf->SetXY(40, 220);
	$header=array('','Nombre','Cargo','Firma');
	$pdf->AliasNbPages();
	$pdf->TablaBasica($header);
	$pdf->SetXY(30, 220);
	$pdf->TablaBasica3($fila);
	/**
	 * Pagina 3
	 */
	$pdf->AddPage();
	$pdf->SetFont('Times','',12);
	// Salto de línea
	/***************************Formato de Cedula en Millones --Inicio **************************/
	$cedula = number_format($fila['IDENTIFICACION']);
	/***************************Formato de Cedula en Millones --Fin **************************/
	/***************************Formato de Pesos --Inicio **************************/
	setlocale(LC_MONETARY, 'en_US');
	$sueldo_basico = money_format('%(#10n', $fila['SUELDO_BASICO_MENSUAL']);
	/***************************Formato de Pesos --Fin **************************/
	$pdf->Ln(30);
	$pdf->Ln(20);
	$pdf->SetX(20);
	/*****************************************************/
	$mujer = "Señora";
	$hombre= "Señor";
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
		$variable = "el ".utf8_decode($hombre)."";
	}
	else {
		$variable = "la ".utf8_decode($mujer)."";
	}
	/*****************************************************/
	/*****************************************************/
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
		$variable2 = "vinculado";
	}
	else {
		$variable2 = "vinculada ";
	}
	/*****************************************************/
	/*****************************************************/
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
		$variable3 = "adscrito";
	}
	else {
		$variable3 = "adscrita";
	}
	/*****************************************************/
	//$lugar_expedicion = ucwords(strtolower($fila['LUGAR_EXPEDICION']));
	$tipo_identificacion = strtolower($fila['TIPO_IDENTIFICACION']);
	if($tipo_identificacion=='cedula ciudadania'){
	
		$tipo_identificacion = utf8_decode('cédula de ciudadanía');
	
	}
		
	
// 	$lugar_expedicion = ucwords(strtolower($fila['LUGAR_EXPEDICION']));
	
// 	if($lugar_expedicion=='Bogota'){
// 		$lugar_expedicion=utf8_decode('Bogotá D.C');
	
// 	}
	
	
	/*****************************************************/
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
		$variable4 = "identificado";
	}
	else {
		$variable4 = "identificada";
	}
	/*****************************************************/
	$funcion = $GLOBALS['funcion'];
	$Fecha_Ingreso=$funcion->calcularFecha($fila['FECHA_INGRESO']);
	$funcion2 = $GLOBALS['funcion2'];
	$Fecha_Resolucion=$funcion2->calcularFecha($fila['FECHA_RESOLUCION']);
	/*****************************************************/
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
		$variable5 = "del interesado";
	}
	else {
		$variable5 = "de la interesada";
	}
	$institucionalizar = "institución";
	$instituto = utf8_decode($institucionalizar);
	
	$desempenar = "desempeña";
	$desempeno = utf8_decode($desempenar);
	
	$resolution = "resolución";
	$resolucion = utf8_decode($resolution);
	/*****************************************************/
	$pdf->Multicell(150,8,"Que ".$variable." ".trim($fila['PRIMER_NOMBRE'])." ".trim($fila['SEGUNDO_NOMBRE'])." ".trim($fila['PRIMER_APELLIDO'])." ".trim(utf8_decode($fila['SEGUNDO_APELLIDO']))." ".$variable4." con ".$tipo_identificacion." ".$cedula.
			" de ".$lugar_expedicion.", se encuentra ".$variable2." a la ".$instituto." desde ".$Fecha_Ingreso.". Actualmente ".$desempeno." el cargo de " .$fila['CARGO']." , ".$variable3." a la " .$fila['DEPENDENCIA'].".",0,'J');
	$pdf->Ln(10);
	$pdf->SetX(20);
	$pdf->Multicell(150,8,"VALOR SUELDO BASICO MENSUAL...........................................".$sueldo_basico."",0,'L');
	$pdf->Ln(10);
	$pdf->SetX(20);
	/*****************************************************/
	$year = "año";
	/*****************************************************/
	$dia=date("l"); // Dia
	$mes=date("F"); // Mes
	$ano=date("Y"); // Año
	$dia2=date("d");
	
	if ($dia=="Monday") $dia="Lunes";
	if ($dia=="Tuesday") $dia="Martes";
	if ($dia=="Wednesday") $dia="Miércoles";
	if ($dia=="Thursday") $dia="Jueves";
	if ($dia=="Friday") $dia="Viernes";
	if ($dia=="Saturday") $dia="Sabado";
	if ($dia=="Sunday") $dia="Domingo";
	
	$mes=date("F");
	
	if ($mes=="January") $mes="Enero";
	if ($mes=="February") $mes="Febrero";
	if ($mes=="March") $mes="Marzo";
	if ($mes=="April") $mes="Abril";
	if ($mes=="May") $mes="Mayo";
	if ($mes=="June") $mes="Junio";
	if ($mes=="July") $mes="Julio";
	if ($mes=="August") $mes="Agosto";
	if ($mes=="September") $mes="Septiembre";
	if ($mes=="October") $mes="Octubre";
	if ($mes=="November") $mes="Noviembre";
	if ($mes=="December") $mes="Diciembre";
	
	$ano=date("Y");
	$dias = utf8_decode('días');
	
	/*****************************************************/
	$ciudad = utf8_decode('Bogotá');
	$pdf->Multicell(150,8,"Se expide en ".$ciudad." D.C a los ".$dia2." ".$dias." del mes de ".$mes." del ".utf8_decode($year)." ".$ano.", a solicitud ".$variable5."." ,0,'J');	
	$pdf->SetXY(30, 200);
	$pdf->TablaBasica2($fila);
	//Títulos de las columnas
	$pdf->SetXY(40, 220);
	$header=array('','Nombre','Cargo','Firma');
	$pdf->AliasNbPages();
	$pdf->TablaBasica($header);
	$pdf->SetXY(30, 220);
	$pdf->TablaBasica3($fila);
	
    $pdf->Output($ruta."/documentos/everyone.pdf",'F');    
    echo "<script language='javascript'>window.open('".$url."/everyone.pdf','_self','');</script>";//para ver el archivo pdf generado
    
	//echo mostrarMensaje();
		}
		
}
// $mensaje = "Se creó la actividad exitosamente";
// $error = "exito";

// $datos = array("mensaje"=>$mensaje, "error"=>$error);
// $this->redireccionar("mostrarMensaje", $datos);
// //var_dump($expression);exit;
// //function mostrarMensaje(){
?>