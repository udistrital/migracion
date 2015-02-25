<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */
$conexion = "soporteoas";
//$esteRecursoDB = $this->miConfigurador->fabricaConexiones->setDbSys('oracle');

$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {
	//Este se considera un error fatal
	exit;
}

//SELECT '1' FROM DUAL;

$esteBloque['nombre']=$_REQUEST['bloqueNombre'];
$esteBloque['grupo']=$_REQUEST['bloqueGrupo'];

$maxSize=1*1024*1024;//tama�p en mb
$upload_dir = $this->ruta."/uploads/";
if (isset($_FILES["archivo"])&&$_FILES["archivo"]['size']<=$maxSize
	&&($_FILES["archivo"]['type']=="application/vnd.ms-excel"||$_FILES["archivo"]['type']=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")) {
	if ($_FILES["archivo"]["error"] > 0) {
		echo "Error: " . $_FILES["file"]["error"] . "<br>";
	} else {
		if(move_uploaded_file($_FILES["archivo"]["tmp_name"], $upload_dir . $_FILES["archivo"]["name"])){
			$ruta = $upload_dir ;
			$archivo =  $_FILES["archivo"]["name"];
			procesarExcel($ruta,$archivo,$this->miConfigurador ,$esteBloque, $esteRecursoDB ,$this->sql);
		}
		
	}
}else echo "Archivo Invalido";

function procesarExcel($ruta , $archivo, $miConfigurador ,$esteBloque,$esteRecursoDB,$sql){
	/** Include PHPExcel */
	require_once dirname(__FILE__) .'/Classes/PHPExcel.php';
	/** Include PHPExcel_IOFactory */
	require_once dirname(__FILE__) .'/Classes/PHPExcel/IOFactory.php';
	
	
	if (!file_exists($ruta . $archivo)) {
		exit("Por favor ingrese un archivo valido." . EOL);
	}
	
	
	$objPHPExcelReader = PHPExcel_IOFactory::load($ruta . $archivo);
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	$titulo="EstudiantesActivos".uniqid();
	// Set document properties
	$objPHPExcel->getProperties()   ->setCreator("Universidad Distrtal Oficina Asesora de Sistemas")
									->setTitle($titulo)
									->setSubject("Estudiantes Activos")
									->setDescription("Archivo resultado de validar los estudiantes activos")
									->setKeywords("estudiantes activos cedulas OAS")
									->setCategory("Validaciòn");
	
	$lastRow = $objPHPExcelReader->getActiveSheet()->getHighestRow();
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Cedulas');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Activo');
	for ($i=2;$i<=$lastRow;$i++){
		$celda = $objPHPExcelReader->getActiveSheet()->getCell('A'.$i)->getValue();
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i, $celda);
		
		//ejecuta consulta
		//
		//
		$cadena_sql = $sql->cadena_sql("consultarEstudianteActivo",$celda);
		$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
		if($registro==null){
			$strActivo = "NO";	
		}else $strActivo =$registro[0][0];
		
		//Llena Campos
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, $strActivo);
		//debe hacer consulta
	}
	
	//elimina todos los archivos excel de la carpeta
	array_map('unlink', glob( $ruta."*.xls"));
	array_map('unlink', glob( $ruta."*.xlsx"));
	
	//escribe el nuevo archivo
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($ruta.$titulo.'.xlsx');
	
	//URL de descarga
	$url=$miConfigurador->getVariableConfiguracion("host");
	$url.=$miConfigurador->getVariableConfiguracion("site");
	$url.="/blocks/".$esteBloque["nombre"];
	$url.="/".$esteBloque["grupo"];
	$url.="/uploads/";
	
	$urlDescarga=$url.$titulo.'.xlsx';
	
	//Salida HTML link descarga
	echo '<p style="text-align:center;" ><a';
	echo ' href="'.$urlDescarga.'">Descargar Listado de Estudiantes';
	echo '</a></p>';
	exit;
}
