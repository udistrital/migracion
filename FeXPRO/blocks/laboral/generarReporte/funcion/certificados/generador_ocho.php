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
	    $this->Cell(80,60,'NIT.899999230-7',0,0,'C');
	    /******************************************************/
	    // Salto de línea
	    $this->Ln(5);
	    $this->SetFont('Arial','B',15);
	    // Movernos a la derecha
	    $this->Cell(50);
	    // Título
	    $this->Cell(80,70,'EL SUSCRITO JEFE DE LA DIVISION DE RECURSOS HUMANOS',0,0,'C');
	     /******************************************************/
	    $this->Ln(5);
	    $this->SetFont('Arial','B',15);
	    $this->Cell(50);
	    $this->Cell(80,80,'CERTIFICA',0,0,'C');
	    /******************************************************/
	}
	
	/*****************Genera Tabla en Reporte -- Inicio************************************/
	function TablaBasica($header)
	{
		//Cabecera
		$this->SetX(30);
		foreach($header as $col)
			$this->Cell(40,5,$col,1,"C");
		$this->Ln();
		$this->SetX(30);
		$this->Cell(40,5,"Reviso",1);
		$this->SetX(70);
		$this->Cell(40,5,"",1);
		$this->SetX(110);
		$this->Cell(40,5,"",1);
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
		$this->SetY(210);
		$this->Cell(180,6,"Carrera 7a No 40-53 Piso 6",0,0,'C');
		$this->SetY(220);
		$this->Cell(180,6,"PBX.3239300 Ext.2600-2604",0,0,'C');
		//}
	}
	/*****************Genera Tabla en Reporte -- Fin************************************/
		function crearPDF($fila){
		$url = $GLOBALS['url'];
		$ruta = $GLOBALS['ruta'];
	
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
 	$Fecha_Resolucion=$funcion->calcularFecha2($fila['FECHA_RESOLUCION']);
	/*****************************************************/
// 	setlocale(LC_ALL,"es_ES");
// 	echo strftime("%A %d de %B del %Y");
// 	$Tesla=$fila['FECHA_RESOLUCION']; 
// 	$Maxwell = utf8_encode(strftime("%A %d de %B del %Y", strtotime($Tesla)));
	//Salida: viernes 24 de febrero del 2012
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
 	$lugar_expedicion = ucwords(strtolower($fila['LUGAR_EXPEDICION'])); 
 	
 	if($lugar_expedicion=='Bogota'){
 		$lugar_expedicion=utf8_decode('Bogotá');
 		
 	}
 	
 	
 	$tipo_identificacion = strtolower($fila['TIPO_IDENTIFICACION']);
 	/*****************************************************/
	$pdf->Multicell(150,8,"Que ".$variable." ".trim($fila['PRIMER_NOMBRE'])." ".trim($fila['SEGUNDO_NOMBRE'])." ".trim($fila['PRIMER_APELLIDO'])." ".trim($fila['SEGUNDO_APELLIDO'])." ".$variable4." con ".$tipo_identificacion." ".$cedula.
		" de ".$lugar_expedicion.", se encuentra ".$variable2." a la institucion desde ".$Fecha_Ingreso.". Actualmente desempena el cargo de " .$fila['CARGO']." ".$variable3." a la " .$fila['DEPENDENCIA'].", mediante resolucion ".$fila['RESOLUCION']." del ".$Fecha_Resolucion.".",0,'J');	
	$pdf->Ln(10);
	$pdf->SetX(20);
	$pdf->Multicell(150,8,"SALARIO PROMEDIO MENSUAL...........................................".$salario_promedio."",0,'L');
	$pdf->Ln(2);
	/*****************************************************/
	$year = "Año";
	$pdf->SetX(20);
	//$pdf->Multicell(150,8,"Lo anterior no incluye el incremento salarial para el ".utf8_decode($year)." 2013.",0,'J');
	$pdf->Multicell(150,8,"".utf8_decode($_REQUEST['textoModificable'])."",0,'J');
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
	if ($mes=="September") $mes="Setiembre";
	if ($mes=="October") $mes="Octubre";
	if ($mes=="November") $mes="Noviembre";
	if ($mes=="December") $mes="Diciembre";
 
	$ano=date("Y");
	
	/*****************Traducir los Mese de Ingles a Español -- Fin************************************/	
	$ciudad = utf8_decode('Bogotá');
 	$pdf->Multicell(150,8,"Se expide en ".$ciudad." D.C a los ".$dia2." dias del mes de ".$mes." del ".utf8_decode($year)." ".$ano.", a solicitud del interesado." ,0,'J');
// 	$pdf->Line(50,200,150,200);//impresion de linea
// 	$pdf->Ln(10);
// 	$pdf->Cell(180,6,"".$fila['JEFE_RECURSOS_HUMANOS']."",0,0,'C');
// 	$pdf->Ln(5);
// 	$pdf->Cell(180,6,"Jefe e Division de Recursos Humanos",0,0,'C');
// 	$pdf->Ln(10);
// 	$pdf->Cell(180,6,"Carrera 7a No 40-53 Piso 6",0,0,'C');
// 	$pdf->Ln(5);
// 	$pdf->Cell(180,6,"PBX.3239300 Ext.2600-2604",0,0,'C');
	//Creación del objeto de la clase heredada
	//$pdf=new PDF();
	$pdf->SetXY(30, 200);
	$pdf->TablaBasica2($fila);
	//Títulos de las columnas
	$pdf->SetXY(40, 245);
	$header=array('','Nombre','Cargo','Firma');
	$pdf->AliasNbPages();
	$pdf->TablaBasica($header);
	/*****************Genera Tabla en Reporte -- Fin************************************/
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
	$lugar_expedicion = ucwords(strtolower($fila['LUGAR_EXPEDICION']));
	$tipo_identificacion = strtolower($fila['TIPO_IDENTIFICACION']);
	
	
	$lugar_expedicion = ucwords(strtolower($fila['LUGAR_EXPEDICION']));
	
	if($lugar_expedicion=='Bogota'){
		$lugar_expedicion=utf8_decode('Bogotá');
	
	}
	
	
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
	$Fecha_Resolucion=$funcion2->calcularFecha2($fila['FECHA_RESOLUCION']);
	/*****************************************************/
	$tipoGenero = $fila['GENERO'];
	if ($tipoGenero == "M") {
		$variable5 = "del interesado";
	}
	else {
		$variable5 = "de la interesada";
	}
	/*****************************************************/
	$pdf->Multicell(150,8,"Que ".$variable." ".trim($fila['PRIMER_NOMBRE'])." ".trim($fila['SEGUNDO_NOMBRE'])." ".trim($fila['PRIMER_APELLIDO'])." ".trim($fila['SEGUNDO_APELLIDO'])." ".$variable4." con ".$tipo_identificacion." ".$cedula.
			" de ".$lugar_expedicion.", se encuentra ".$variable2." a la institucion desde ".$Fecha_Ingreso.". Actualmente desempena el cargo de " .$fila['CARGO']." , ".$variable3." a la " .$fila['DEPENDENCIA']." mediante resolucion ".$fila['RESOLUCION']." del ".$Fecha_Resolucion." .",0,'J');
	$pdf->Ln(10);
	$pdf->SetX(20);
	$pdf->Multicell(150,8,"VALOR SUELDO BASICO MENSUAL...........................................".$sueldo_basico."",0,'L');
	$pdf->Ln(10);
	$pdf->SetX(20);
	/*****************************************************/
	$year = "Año";
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
	if ($mes=="September") $mes="Setiembre";
	if ($mes=="October") $mes="Octubre";
	if ($mes=="November") $mes="Noviembre";
	if ($mes=="December") $mes="Diciembre";
	
	$ano=date("Y");
	
	/*****************************************************/
	$ciudad = utf8_decode('Bogotá');
	$pdf->Multicell(150,8,"Se expide en ".$ciudad." D.C a los ".$dia2." dias del mes de ".$mes." del ".utf8_decode($year)." ".$ano.", a solicitud ".$variable5."." ,0,'J');
	// 		$pdf->Line(50,200,150,200);//impresion de linea
	// 		$pdf->Ln(15);
	// 		$pdf->Cell(180,6,"".$fila['JEFE_RECURSOS_HUMANOS']."",0,0,'C');
	// 		$pdf->Ln(5);
	// 		$pdf->Cell(180,6,"Jefe e Division de Recursos Humanos",0,0,'C');
	// 		$pdf->Ln(15);
	// 		$pdf->Cell(180,6,"Carrera 7a No 40-53 Piso 6",0,0,'C');
	// 		$pdf->Ln(5);
	// 		$pdf->Cell(180,6,"PBX.3239300 Ext.2600-2604",0,0,'C');
	
	$pdf->SetXY(30, 200);
	$pdf->TablaBasica2($fila);
	//Títulos de las columnas
	$pdf->SetXY(40, 245);
	$header=array('','Nombre','Cargo','Firma');
	$pdf->AliasNbPages();
	$pdf->TablaBasica($header);
	
    $pdf->Output($ruta."/documentos/sueldobasico_promediomensual.pdf",'F');    
    echo "<script language='javascript'>window.open('".$url."/sueldobasico_promediomensual.pdf','_self','');</script>";//para ver el archivo pdf generado
    
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