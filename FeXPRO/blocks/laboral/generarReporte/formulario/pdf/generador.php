<?php
//echo "generador";exit;
/* incluimos primeramente el archivo que contiene la clase fpdf */
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
//var_dump($funcion);exit;
global $funcion2;
$funcion2 = new FunciongenerarReporte();

/*****************************************************************************/
$idGenerador = trim($_REQUEST["certificado"]);
//var_dump($idGenerador);exit;
$conexion = "oracle";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
$cadena_sql = $this->sql->cadena_sql("buscarInformacionUsuario", $idGenerador);
//var_dump($cadena_sql);exit;
$certificado_number = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//var_dump($certificado_number);exit;
//$certificado_number = $certificado_number[0];
//var_dump($certificado_number[0]);exit;
/*****************************************************************************/

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
		$this->Ln(20);
		// Logo
		$url2 = $GLOBALS['url2'];
		var_dump($url2);exit;
		$this->Image($url2.'escudo.png',80,40,-300);
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
	
	function crearPDF($fila){
		//$certificado_number[0];
		//var_dump($certificado_number[0]);exit;
		$url = $GLOBALS['url'];
		$ruta = $GLOBALS['ruta'];
	
		$this->Header();
			
		// Creación del objeto de la clase heredada
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Times','',12);
		// Salto de línea
		/*****************************************************/
		$cedula = number_format($fila['IDENTIFICACION']);
		var_dump($fila['IDENTIFICACION']);exit;
		/*****************************************************/
		$pdf->Ln(30);
		$pdf->Ln(20);
		$pdf->SetX(20);
		/*****************************************************/
		$funcion = $GLOBALS['funcion'];
		$Fecha_Ingreso=$funcion->calcularFecha($fila['FECHA_INGRESO']);
		$funcion2 = $GLOBALS['funcion2'];
		$Fecha_Resolucion=$funcion2->calcularFecha2($fila['FECHA_RESOLUCION']);
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
 		$lugar_expedicion = strtolower($fila['LUGAR_EXPEDICION']);
 		$tipo_identificacion = strtolower($fila['TIPO_IDENTIFICACION']);
 		/*****************************************************/
		$tipoGenero = $fila['GENERO'];
		if ($tipoGenero == "M") {
			$variable5 = "del interesado";
		}
		else {
			$variable5 = "de la interesada";
		}
		/*****************************************************/
		$pdf->Multicell(150,8,"Que ".$variable." ".$fila['PRIMER_NOMBRE']." ".$fila['SEGUNDO_NOMBRE']." ".$fila['PRIMER_APELLIDO']." ".$fila['SEGUNDO_APELLIDO']." ".$variable4." con ".$tipo_identificacion." ".$cedula.
				" de ".$lugar_expedicion.", se encuentra ".$variable3." a la institucion desde ".$Fecha_Ingreso.". Actualmente desempena el cargo de " .$fila['CARGO']." , ".$variable2." a la " .$fila['DEPENDENCIA']." mediante resolucion ".$fila['RESOLUCION']." del ".$Fecha_Resolucion.".",0,'J');
		$pdf->Ln(10);
		// 	$pdf->SetX(20);
		// 	$pdf->Multicell(150,8,"VALOR SUELDO BASICO MENSUAL..............................................................".$fila['salario']."",0,'J');
		//$pdf->Ln(10);
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
		$pdf->Multicell(150,8,"Se expide en Bogota D.C a los ".$dia2." dias del mes de ".$mes." del ".utf8_decode($year)." ".$ano.", a solicitud ".$variable5."." ,0,'J');
		$pdf->Line(50,210,150,210);//impresion de linea
		$pdf->Ln(30);
		$pdf->Cell(180,6,"".$fila['JEFE_RECURSOS_HUMANOS']."",0,0,'C');
		$pdf->Ln(5);
		$pdf->Cell(180,6,"Jefe e Division de Recursos Humanos",0,0,'C');
		$pdf->Ln(20);
		$pdf->Cell(180,6,"Carrera 7a No 40-53 Piso 6",0,0,'C');
		$pdf->Ln(5);
		$pdf->Cell(180,6,"PBX.3239300 Ext.2600-2604",0,0,'C');
		$pdf->Output($ruta."/documentos/prueba.pdf",'F');
		echo "<script language='javascript'>window.open('".$url."/prueba.pdf','_self','');</script>";//para ver el archivo pdf generado
// 		exit;
	}
}
?>